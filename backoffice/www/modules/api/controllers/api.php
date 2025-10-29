<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of api class
 *
 * @author  Muhammad Rizky
 * @since   1.0
 *
 */

class Api extends REST_Controller
{

    public function permohonan_get()
    {

        if (!$this->get('pendaftaran')) {
            $this->response(NULL, 400);
        }
        $pendaftaran = $this->get('pendaftaran');
        $pendaftaran_id = $pendaftaran;

        // Get permohonan
        $permohonan = new tmpermohonan();
        $permohonan->where('pendaftaran_id', $pendaftaran_id);
        $list = $permohonan->get();
        $permohonan = array();
        foreach ($list as $dt) {

            $dt->trperizinan->get();

            // Get who has permohonan
            $dt->tmpemohon->get();

            // Get who far these permohonan
            $dt->trstspermohonan->get();

            $data = array();
            $data['id'] = $dt->id;
            $data['no_pendaftaran'] = $dt->pendaftaran_id;
            if ($dt->trstspermohonan->id == '1') //jika sementara
            {
                $data['nama'] = $dt->tmpemohon_sementara->n_pemohon;
                $data['telp'] = $dt->tmpemohon_sementara->telp_pemohon;
                $data['alamat'] = $dt->tmpemohon_sementara->a_pemohon;
            } else {
                $data['nama'] = $dt->tmpemohon->n_pemohon;
                $data['telp'] = $dt->tmpemohon->telp_pemohon;
                $data['alamat'] = $dt->tmpemohon->a_pemohon;
            }
            $data['permohonan'] = $dt->trperizinan->n_perizinan;
            $data['tracking'] = $dt->trstspermohonan->n_sts_permohonan;


            // Get what Izin is

            $permohonan[] = $data;
        }


        if ($permohonan) {
            $this->response($permohonan, 200);
        } else {
            $this->response(array('error' => 'Tidak ada data..'), 404);
        }
    }

    public function duedatenotification_get()
    {
        $date_now = date('Y-m-d');
        $trperizinan = new trperizinan();
        $trperizinan->get();

        $tmpermohonan = new tmpermohonan();
        $tmpermohonan->where_related($trperizinan);
        $tmpermohonan->where('d_berlaku_izin < ', $date_now + 7);
        $tmpermohonan->where('due_notif !=', 1);
        $tmpermohonan->limit(10);
        $tmpermohonan->get();
        foreach ($tmpermohonan as $tmpermohonansingle) {
            $tmpemohon = $tmpermohonansingle->tmpemohon->get();
            $perizinan = $tmpermohonansingle->trperizinan->get();
            $tmsk = $tmpermohonansingle->tmsk->get();
            $text = "Halo " . $tmpemohon->n_pemohon . " \r\n" . $perizinan->n_perizinan . " milik Anda, Dengan nomor izin \r\nNomor : " . $tmsk->no_surat . "\r\nTelah mendekati atau telah habis masa berlakunya (" . $tmpermohonansingle->d_berlaku_izin . "). Silahkan memperpanjang izin Anda. Terima kasih!";
            $this->send_whatsapp($tmpemohon->telp_pemohon, $text);
            $this->sendEmail($tmpemohon->email_pemohon, "Notifikasi Izin", $text);
            $tmpermohonansingle->due_notif = 1;
            $tmpermohonansingle->save();
        }
    }

    public function cekmasaberlaku_get()
    {
        $id_permohonan = $this->get('id');
        $query = "SELECT a.v_property
                FROM tmproperty_jenisperizinan a 
                INNER JOIN tmproperty_jenisperizinan_trproperty b 
                ON a.id=b.tmproperty_jenisperizinan_id 
                INNER JOIN trproperty c 
                ON b.trproperty_id=c.id 
                INNER JOIN tmpermohonan d 
                ON a.pendaftaran_id=d.pendaftaran_id 
                INNER JOIN tmpermohonan_trperizinan e 
                ON d.id= e.tmpermohonan_id 
                INNER JOIN trperizinan_trproperty f 
                ON (b.trproperty_id=f.trproperty_id AND f.trperizinan_id=e.trperizinan_id) 
                INNER JOIN trproperty g ON f.c_parent=g.id 
                WHERE d.id=$id_permohonan AND f.c_sk_id=1 AND  c.short_name = 'DMASABERLAKU'";
        $result = $this->db->query($query);

        $row = $result->row();
        die(print_r($row));
        $hasil = $row->v_property;
        $akhir_masa_berlaku = strtotime($hasil);

        echo date("Y-m-d", $akhir_masa_berlaku);
    }

    public function cekmasaberlakureklame_get($id_permohonan)
    {
        $query = "SELECT a.v_property
                    FROM tmproperty_jenisperizinan a 
                    INNER JOIN tmproperty_jenisperizinan_trproperty b 
                    ON a.id=b.tmproperty_jenisperizinan_id 
                    INNER JOIN trproperty c 
                    ON b.trproperty_id=c.id 
                    INNER JOIN tmpermohonan d 
                    ON a.pendaftaran_id=d.pendaftaran_id 
                    INNER JOIN tmpermohonan_trperizinan e 
                    ON d.id= e.tmpermohonan_id 
                    INNER JOIN trperizinan_trproperty f 
                    ON (b.trproperty_id=f.trproperty_id AND f.trperizinan_id=e.trperizinan_id) 
                    INNER JOIN trproperty g ON f.c_parent=g.id 
                    WHERE d.id=$id_permohonan AND f.c_sk_id=1 AND  c.short_name = 'WAKTUREKLAME'";
        $result = $this->db->query($query);
        $row = $result->row();
        $hasil = $row->v_property;
        $akhir_masa_berlaku = strtotime($hasil);

        echo date("Y-m-d", $akhir_masa_berlaku);
    }


    public function cekperizinan_get()
    {
        if (!$this->get('no_izin')) {
            $this->response(NULL, 400);
        }
        $noizin = $this->get('no_izin');

        $dataFound = false;
        $query =
            "SELECT
a.pendaftaran_id,
c.n_pemohon,
i.n_perizinan,
tmsk.no_surat,
a.d_terima_berkas,
a.d_berlaku_izin,
c.a_pemohon,
c.telp_pemohon,
tmsk.tgl_surat,
tmperusahaan.n_perusahaan,
tmperusahaan.npwp,
tmperusahaan.a_perusahaan,
tmperusahaan.d_tgl_berdiri,
tmperusahaan.i_telp_perusahaan,
trkecamatan.n_kecamatan,
trkabupaten.n_kabupaten,
trpropinsi.n_propinsi,
trkelurahan.n_kelurahan
FROM
tmpermohonan AS a
INNER JOIN tmpemohon_tmpermohonan AS b ON a.id = b.tmpermohonan_id
INNER JOIN tmpemohon AS c ON c.id = b.tmpemohon_id
INNER JOIN tmpermohonan_trperizinan AS h ON h.tmpermohonan_id = a.id
INNER JOIN trperizinan AS i ON i.id = h.trperizinan_id
INNER JOIN tmpermohonan_tmsk ON tmpermohonan_tmsk.tmpermohonan_id = a.id
INNER JOIN tmsk ON tmsk.id = tmpermohonan_tmsk.tmsk_id
INNER JOIN tmpermohonan_tmperusahaan ON tmpermohonan_tmperusahaan.tmpermohonan_id = tmpermohonan_tmsk.tmpermohonan_id
INNER JOIN tmperusahaan ON tmperusahaan.id = tmpermohonan_tmperusahaan.tmperusahaan_id
INNER JOIN tmperusahaan_trkelurahan ON (tmpermohonan_tmperusahaan.tmperusahaan_id = tmperusahaan_trkelurahan.tmperusahaan_id)
INNER JOIN trkecamatan_trkelurahan ON (tmperusahaan_trkelurahan.trkelurahan_id = trkecamatan_trkelurahan.trkelurahan_id)
INNER JOIN trkecamatan ON (trkecamatan_trkelurahan.trkecamatan_id = trkecamatan.id)
INNER JOIN trkabupaten_trkecamatan ON (trkecamatan_trkelurahan.trkecamatan_id = trkabupaten_trkecamatan.trkecamatan_id)
INNER JOIN trkabupaten ON (trkabupaten_trkecamatan.trkabupaten_id = trkabupaten.id)
INNER JOIN trkabupaten_trpropinsi ON (trkabupaten_trkecamatan.trkabupaten_id = trkabupaten_trpropinsi.trkabupaten_id)
INNER JOIN trpropinsi ON (trkabupaten_trpropinsi.trpropinsi_id = trpropinsi.id)
INNER JOIN trkelurahan ON trkelurahan.id = tmperusahaan_trkelurahan.trkelurahan_id
WHERE
tmsk.base64 = '{$noizin}'";
        $result = $this->db->query($query)->result_array();
        $num_result = count($result);

        //Jika kosong, ya kosonglah
        if (empty($result)) {
            $this->response(array('error' => 'No Izin tidak Terdaftar!'), 404);
        }
        if ($result) {
            $this->response($result, 200);
        } else {
            $this->response(array('error' => 'Tidak ada data..'), 404);
        }
    }

    public function cekperizinanizin_get()
    {
        if (!$this->get('no_izin')) {
            $this->response(NULL, 400);
        }
        $noizin = base64_decode($this->get('no_izin'));

        $dataFound = false;
        $query =
            "SELECT
a.pendaftaran_id,
c.n_pemohon,
i.n_perizinan,
tmsk.no_surat,
a.d_terima_berkas,
a.d_berlaku_izin,
c.a_pemohon,
c.telp_pemohon,
tmsk.tgl_surat,
tmperusahaan.n_perusahaan,
tmperusahaan.npwp,
tmperusahaan.a_perusahaan,
tmperusahaan.d_tgl_berdiri,
tmperusahaan.i_telp_perusahaan,
trkecamatan.n_kecamatan,
trkabupaten.n_kabupaten,
trpropinsi.n_propinsi,
trkelurahan.n_kelurahan
FROM
tmpermohonan AS a
INNER JOIN tmpemohon_tmpermohonan AS b ON a.id = b.tmpermohonan_id
INNER JOIN tmpemohon AS c ON c.id = b.tmpemohon_id
INNER JOIN tmpermohonan_trperizinan AS h ON h.tmpermohonan_id = a.id
INNER JOIN trperizinan AS i ON i.id = h.trperizinan_id
INNER JOIN tmpermohonan_tmsk ON tmpermohonan_tmsk.tmpermohonan_id = a.id
INNER JOIN tmsk ON tmsk.id = tmpermohonan_tmsk.tmsk_id
INNER JOIN tmpermohonan_tmperusahaan ON tmpermohonan_tmperusahaan.tmpermohonan_id = tmpermohonan_tmsk.tmpermohonan_id
INNER JOIN tmperusahaan ON tmperusahaan.id = tmpermohonan_tmperusahaan.tmperusahaan_id
INNER JOIN tmperusahaan_trkelurahan ON (tmpermohonan_tmperusahaan.tmperusahaan_id = tmperusahaan_trkelurahan.tmperusahaan_id)
INNER JOIN trkecamatan_trkelurahan ON (tmperusahaan_trkelurahan.trkelurahan_id = trkecamatan_trkelurahan.trkelurahan_id)
INNER JOIN trkecamatan ON (trkecamatan_trkelurahan.trkecamatan_id = trkecamatan.id)
INNER JOIN trkabupaten_trkecamatan ON (trkecamatan_trkelurahan.trkecamatan_id = trkabupaten_trkecamatan.trkecamatan_id)
INNER JOIN trkabupaten ON (trkabupaten_trkecamatan.trkabupaten_id = trkabupaten.id)
INNER JOIN trkabupaten_trpropinsi ON (trkabupaten_trkecamatan.trkabupaten_id = trkabupaten_trpropinsi.trkabupaten_id)
INNER JOIN trpropinsi ON (trkabupaten_trpropinsi.trpropinsi_id = trpropinsi.id)
INNER JOIN trkelurahan ON trkelurahan.id = tmperusahaan_trkelurahan.trkelurahan_id
WHERE
tmsk.no_surat = '{$noizin}'";
        $result = $this->db->query($query)->result_array();
        $num_result = count($result);

        //Jika kosong, ya kosonglah
        if (empty($result)) {
            $this->response(array('error' => 'No Izin tidak Terdaftar!'), 404);
        }
        if ($result) {
            $this->response($result, 200);
        } else {
            $this->response(array('error' => 'Tidak ada data..'), 404);
        }
    }

    /**
     * Fungsi untuk mendapatkan tracking status perizinan
     * @author Indra
     */
    public function permohonan2_get()
    {

        if (!$this->get('pendaftaran')) {
            $this->response(NULL, 400);
        }
        $pendaftaran = trim($this->get('pendaftaran'));
        $pendaftaran_id = $pendaftaran;

        $dataFound = false;
        $query =
            "SELECT a.pendaftaran_id,c.n_pemohon, c.a_pemohon,c.telp_pemohon,
                    g.n_sts_permohonan,i.n_perizinan, f.trstspermohonan_id
             FROM tmpermohonan a
                INNER JOIN tmpemohon_tmpermohonan b ON a.id=b.tmpermohonan_id
                INNER JOIN tmpemohon c ON c.id=b.tmpemohon_id
                INNER JOIN tmpermohonan_tmtrackingperizinan d ON d.tmpermohonan_id=a.id
                INNER JOIN tmtrackingperizinan e ON e.id=d.tmtrackingperizinan_id
                INNER JOIN tmtrackingperizinan_trstspermohonan f ON f.tmtrackingperizinan_id=e.id
                INNER JOIN trstspermohonan g ON g.id=f.trstspermohonan_id
                INNER JOIN tmpermohonan_trperizinan h ON h.tmpermohonan_id=a.id
                INNER JOIN trperizinan i ON i.id=h.trperizinan_id
            WHERE a.pendaftaran_id = {$pendaftaran_id}";
        $result = $this->db->query($query)->result_array();
        $num_result = count($result);

        //Jika kosong, coba cari di pendaftaran sementara
        if (empty($result)) {
            $querySementara =
                "SELECT a.pendaftaran_id,c.n_pemohon, c.a_pemohon,c.telp_pemohon,
                    g.n_sts_permohonan,i.n_perizinan, f.trstspermohonan_id
                 FROM tmpermohonan a
					INNER JOIN tmpemohon_sementara_tmpermohonan b ON a.id=b.tmpermohonan_id
                    INNER JOIN tmpemohon_sementara c ON c.id=b.tmpemohon_sementara_id
					INNER JOIN tmpermohonan_tmtrackingperizinan d ON d.tmpermohonan_id=a.id
					INNER JOIN tmtrackingperizinan e ON e.id=d.tmtrackingperizinan_id
					INNER JOIN tmtrackingperizinan_trstspermohonan f ON f.tmtrackingperizinan_id=e.id
					INNER JOIN trstspermohonan g ON g.id=f.trstspermohonan_id
					INNER JOIN tmpermohonan_trperizinan h ON h.tmpermohonan_id=a.id
					INNER JOIN trperizinan i ON i.id=h.trperizinan_id
				WHERE a.pendaftaran_id = {$pendaftaran_id}";
            $result = $this->db->query($querySementara)->result_array();
            $num_result = count($result);

            //Jika ditemukan di pendaftaran sementara
            $last_trstspermohonan_id = $result[($num_result - 1)]['trstspermohonan_id'];
            $result[($num_result - 1)]['current'] = 1;
            $query_all_langkah =
                "SELECT
                    g.pendaftaran_id, i.n_pemohon, i.a_pemohon,i.telp_pemohon, b.n_sts_permohonan, e.n_perizinan, a.trstspermohonan_id
                FROM trlangkah_perizinan a
                    INNER JOIN trstspermohonan b ON b.id = a.trstspermohonan_id
                    INNER JOIN trkelompok_perizinan c ON c.id = a.trkelompok_perizinan_id
                    INNER JOIN trkelompok_perizinan_trperizinan d ON d.trkelompok_perizinan_id=c.id
                    INNER JOIN trperizinan e ON e.id=d.trperizinan_id
                    INNER JOIN tmpermohonan_trperizinan f ON f.trperizinan_id=e.id
                    INNER JOIN tmpermohonan g ON g.id = f.tmpermohonan_id
                    INNER JOIN tmpemohon_sementara_tmpermohonan h ON h.tmpermohonan_id = g.id
                    INNER JOIN tmpemohon_sementara i ON i.id=h.tmpemohon_sementara_id
                    WHERE g.pendaftaran_id={$pendaftaran_id}
                        AND a.urut > (
                            SELECT aa.urut FROM trlangkah_perizinan aa
                            INNER JOIN trstspermohonan bb ON bb.id = aa.trstspermohonan_id
                            INNER JOIN trkelompok_perizinan cc ON cc.id = aa.trkelompok_perizinan_id
                            INNER JOIN trkelompok_perizinan_trperizinan dd ON dd.trkelompok_perizinan_id=cc.id
                            INNER JOIN trperizinan ee ON ee.id=dd.trperizinan_id
                            INNER JOIN tmpermohonan_trperizinan ff ON ff.trperizinan_id=ee.id
                            INNER JOIN tmpermohonan gg ON gg.id = ff.tmpermohonan_id
                            INNER JOIN tmpemohon_sementara_tmpermohonan hh ON hh.tmpermohonan_id = gg.id
                            INNER JOIN tmpemohon_sementara ii ON ii.id=hh.tmpemohon_sementara_id
                            WHERE gg.pendaftaran_id={$pendaftaran_id}
                                AND bb.id = {$last_trstspermohonan_id}
                            ORDER BY aa.urut ASC
                            LIMIT 0,1
                        )
                    ORDER BY a.urut ASC";
            $result_langkah = $this->db->query($query_all_langkah)->result_array();
            if (!empty($result_langkah)) {
                $result = array_merge($result, $result_langkah);
            }
        } else {
            $last_trstspermohonan_id = $result[($num_result - 1)]['trstspermohonan_id'];
            $result[($num_result - 1)]['current'] = 1;
            $query_all_langkah =
                "SELECT
                    g.pendaftaran_id, i.n_pemohon, i.a_pemohon,i.telp_pemohon, b.n_sts_permohonan, e.n_perizinan, a.trstspermohonan_id
                FROM trlangkah_perizinan a
                    INNER JOIN trstspermohonan b ON b.id = a.trstspermohonan_id
                    INNER JOIN trkelompok_perizinan c ON c.id = a.trkelompok_perizinan_id
                    INNER JOIN trkelompok_perizinan_trperizinan d ON d.trkelompok_perizinan_id=c.id
                    INNER JOIN trperizinan e ON e.id=d.trperizinan_id
                    INNER JOIN tmpermohonan_trperizinan f ON f.trperizinan_id=e.id
                    INNER JOIN tmpermohonan g ON g.id = f.tmpermohonan_id
                    INNER JOIN tmpemohon_tmpermohonan h ON h.tmpermohonan_id = g.id
                    INNER JOIN tmpemohon i ON i.id=h.tmpemohon_id
                    WHERE g.pendaftaran_id={$pendaftaran_id}
                        AND a.urut > (
                            SELECT aa.urut FROM trlangkah_perizinan aa
                            INNER JOIN trstspermohonan bb ON bb.id = aa.trstspermohonan_id
                            INNER JOIN trkelompok_perizinan cc ON cc.id = aa.trkelompok_perizinan_id
                            INNER JOIN trkelompok_perizinan_trperizinan dd ON dd.trkelompok_perizinan_id=cc.id
                            INNER JOIN trperizinan ee ON ee.id=dd.trperizinan_id
                            INNER JOIN tmpermohonan_trperizinan ff ON ff.trperizinan_id=ee.id
                            INNER JOIN tmpermohonan gg ON gg.id = ff.tmpermohonan_id
                            INNER JOIN tmpemohon_tmpermohonan hh ON hh.tmpermohonan_id = gg.id
                            INNER JOIN tmpemohon ii ON ii.id=hh.tmpemohon_id
                            WHERE gg.pendaftaran_id={$pendaftaran_id}
                                AND bb.id = {$last_trstspermohonan_id}
                            ORDER BY aa.urut ASC
                            LIMIT 0,1
                        )
                    ORDER BY a.urut ASC";
            $result_langkah = $this->db->query($query_all_langkah)->result_array();
            if (!empty($result_langkah)) {
                $result = array_merge($result, $result_langkah);
            }
        }

        if ($result) {
            $this->response($result, 200);
        } else {
            $this->response(array('error' => 'Tidak ada data..'), 404);
        }
    }

    public function listtolak_get()
    {

        if (!$this->get('limit') || !$this->get('offset')) {
            $this->response(NULL, 400);
        }
        $limit = $this->get('limit');
        $offset = $this->get('offset');

        $query = "SELECT A.id, A.pendaftaran_id, A.d_terima_berkas, A.d_survey,
		        A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
		        C.id idizin, C.n_perizinan, C.c_keputusan, E.n_pemohon, E.a_pemohon,
		        G.id idjenis, G.n_permohonan
		        FROM tmpermohonan as A
		        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
		        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
		        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
		        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
		        INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
		        INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
		        WHERE A.c_pendaftaran = 1
		        AND A.c_izin_dicabut = 0
		        AND A.c_izin_selesai = 0
				AND A.d_terima_berkas
            AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                            INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                            WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = 9 )> 0 order by A.id DESC LIMIT {$offset}, {$limit}";

        $result = $this->db->query($query)->result_array();

        ##### Count total Rows #######
        $query_count = "SELECT COUNT(A.id)as count
                FROM tmpermohonan as A
		        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
		        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
		        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
		        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
		        INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
		        INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
		        WHERE A.c_pendaftaran = 1
		        AND A.c_izin_dicabut = 0
		        AND A.c_izin_selesai = 0
				AND A.d_terima_berkas
            AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                            INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                            WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = 9 )> 0 order by A.id DESC";
        $result_count = $this->db->query($query_count)->result_array();
        $total_rows = $result_count[0]['count'];
        foreach ($result as $key => $res) {
            $result[$key]['num_rows'] = $total_rows;
        }
        ####################################

        if ($result) {
            $this->response($result, 200);
        } else {
            $this->response(array('error' => 'Tidak ada data..'), 404);
        }
    }

    public function listpermohonan_get()
    {

        if (!$this->get('limit')) {
            $this->response(NULL, 400);
        }
        $limit = $this->get('limit');

        $query = "SELECT a.pendaftaran_id,c.n_pemohon, i.n_perizinan
            FROM tmpermohonan a
            INNER JOIN tmpemohon_tmpermohonan b ON a.id=b.tmpermohonan_id
            INNER JOIN tmpemohon c ON c.id=b.tmpemohon_id
            INNER JOIN tmpermohonan_trperizinan h ON h.tmpermohonan_id=a.id
            INNER JOIN trperizinan i ON i.id=h.trperizinan_id
            WHERE a.c_izin_selesai = 0 ORDER BY a.d_terima_berkas DESC LIMIT {$limit}";

        $result = $this->db->query($query)->result_array();
        if ($result) {
            $this->response($result, 200);
        } else {
            $this->response(array('error' => 'Tidak ada data..'), 404);
        }
    }

    /**
     * Fungsi untuk generate xml permohonan yang sudah dalam proses cetak
     * Daftar izin yang dalam proses (semua daftar pemohon dikurangi daftar izin yang sudah proses cetak izin pada back office)
     * @author Indra
     *
     */
    public function listpermohonanproses_get()
    {

        if (!$this->get('limit') || !$this->get('offset')) {
            $this->response(NULL, 400);
        }
        $limit = $this->get('limit');
        $offset = $this->get('offset');

        $query = "SELECT a.pendaftaran_id,c.n_pemohon, i.n_perizinan
            FROM tmpermohonan a
            INNER JOIN tmpemohon_tmpermohonan b ON a.id=b.tmpermohonan_id
            INNER JOIN tmpemohon c ON c.id=b.tmpemohon_id
            INNER JOIN tmpermohonan_trperizinan h ON h.tmpermohonan_id=a.id
            INNER JOIN trperizinan i ON i.id=h.trperizinan_id
            WHERE a.c_izin_selesai = 0
              
            ORDER BY a.d_terima_berkas DESC LIMIT {$offset}, {$limit}";

        $result = $this->db->query($query)->result_array();

        ##### Count total Rows #######
        $query_count = "SELECT COUNT(pendaftaran_id)as count
            FROM tmpermohonan a
            INNER JOIN tmpemohon_tmpermohonan b ON a.id=b.tmpermohonan_id
            INNER JOIN tmpemohon c ON c.id=b.tmpemohon_id
            INNER JOIN tmpermohonan_trperizinan h ON h.tmpermohonan_id=a.id
            INNER JOIN trperizinan i ON i.id=h.trperizinan_id
            WHERE a.c_izin_selesai = 0
              AND a.id IN (SELECT tp.id
                FROM
                tmpermohonan tp
                INNER JOIN tmpermohonan_trstspermohonan tpts ON tp.id = tpts.tmpermohonan_id
                WHERE 
				  tp.id IN (
                        SELECT MAX(tpts.id)AS permohonan_status_id
                        FROM tmpermohonan_trstspermohonan tpts
                        GROUP BY tmpermohonan_id
                  )
                  AND tpts.trstspermohonan_id IN (1,2,3,4,5,6,7,8,10,13,18)
              )";
        $result_count = $this->db->query($query_count)->result_array();
        $total_rows = $result_count[0]['count'];
        foreach ($result as $key => $res) {
            $result[$key]['num_rows'] = $total_rows;
        }
        ####################################

        if ($result) {
            $this->response($result, 200);
        } else {
            $this->response(array('error' => 'Tidak ada data..'), 404);
        }
    }

    /**
     * Fungsi untuk generate xml permohonan yang sudah diterbitkan
     * Daftar izin yang sudah diterbitkan (semua izin yang sudah berada pada proses cetak izin pada back office)
     * @author Indra
     *
     */
    public function listpermohonanterbit_get()
    {

        if (!$this->get('limit') || !$this->get('offset')) {
            $this->response(NULL, 400);
        }
        $limit = $this->get('limit');
        $offset = $this->get('offset');

        $query = "SELECT
a.pendaftaran_id,
c.n_pemohon,
i.n_perizinan,
tmsk.no_surat
FROM
tmpermohonan AS a
INNER JOIN tmpemohon_tmpermohonan AS b ON a.id = b.tmpermohonan_id
INNER JOIN tmpemohon AS c ON c.id = b.tmpemohon_id
INNER JOIN tmpermohonan_trperizinan AS h ON h.tmpermohonan_id = a.id
INNER JOIN trperizinan AS i ON i.id = h.trperizinan_id
INNER JOIN tmpermohonan_tmsk ON tmpermohonan_tmsk.tmpermohonan_id = a.id
INNER JOIN tmsk ON tmsk.id = tmpermohonan_tmsk.tmsk_id
WHERE
a.c_izin_selesai = 1 AND
a.id IN (SELECT tp.id
                FROM
                tmpermohonan tp
                INNER JOIN tmpermohonan_trstspermohonan tpts ON tp.id = tpts.tmpermohonan_id
                WHERE 
				  
					tp.id IN (
                        SELECT MAX(tpts.id)AS permohonan_status_id
                        FROM tmpermohonan_trstspermohonan tpts
                        GROUP BY tmpermohonan_id
                    )
					OR tp.id IN (
                        SELECT tpts.id AS permohonan_status_id
                        FROM tmpermohonan_trstspermohonan tpts
                        where tpts.trstspermohonan_id IN (14) 
                    )
				   	
                  AND tpts.trstspermohonan_id IN (12, 17)
              )
            ORDER BY a.d_terima_berkas DESC LIMIT {$offset}, {$limit}";

        $result = $this->db->query($query)->result_array();

        ##### Count total Rows #######
        $query_count = "SELECT COUNT(pendaftaran_id)as count
            FROM tmpermohonan a
            INNER JOIN tmpemohon_tmpermohonan b ON a.id=b.tmpermohonan_id
            INNER JOIN tmpemohon c ON c.id=b.tmpemohon_id
            INNER JOIN tmpermohonan_trperizinan h ON h.tmpermohonan_id=a.id
            INNER JOIN trperizinan i ON i.id=h.trperizinan_id
            WHERE a.c_izin_selesai = 0
              AND a.id IN (SELECT tp.id
                FROM
                tmpermohonan tp
                INNER JOIN tmpermohonan_trstspermohonan tpts ON tp.id = tpts.tmpermohonan_id
                WHERE
                  (	
					tp.id IN (
                        SELECT MAX(tpts.id)AS permohonan_status_id
                        FROM tmpermohonan_trstspermohonan tpts
                        GROUP BY tmpermohonan_id
                    )
					OR tp.id IN (
                        SELECT tpts.id AS permohonan_status_id
                        FROM tmpermohonan_trstspermohonan tpts
                        where tpts.trstspermohonan_id IN (14) 
                    )
				  )
                  AND tpts.trstspermohonan_id IN (12, 17)
              )";
        $result_count = $this->db->query($query_count)->result_array();
        $total_rows = $result_count[0]['count'];
        foreach ($result as $key => $res) {
            $result[$key]['num_rows'] = $total_rows;
        }
        ####################################

        if ($result) {
            $this->response($result, 200);
        } else {
            $this->response(array('error' => 'Tidak ada data..'), 404);
        }
    }

    /**
     * Fungsi untuk generate xml permohonan yang sudah selesai namun belum diambil / diserahkan
     * Daftar izin yang belum diambil (semua izin yang sudah berada pada proses cetak izin pada back office dikurangi izin yang sudah diserahkan)
     * @author Indra
     *
     */
    public function listpermohonanambil_get()
    {

        if (!$this->get('limit') || !$this->get('offset')) {
            $this->response(NULL, 400);
        }
        $limit = $this->get('limit');
        $offset = $this->get('offset');

        $query = "SELECT a.pendaftaran_id,c.n_pemohon, i.n_perizinan
            FROM tmpermohonan a
            INNER JOIN tmpemohon_tmpermohonan b ON a.id=b.tmpermohonan_id
            INNER JOIN tmpemohon c ON c.id=b.tmpemohon_id
            INNER JOIN tmpermohonan_trperizinan h ON h.tmpermohonan_id=a.id
            INNER JOIN trperizinan i ON i.id=h.trperizinan_id
            WHERE a.c_izin_selesai = 0
              AND a.id IN (SELECT tp.id
                FROM
                tmpermohonan tp
                INNER JOIN tmpermohonan_trstspermohonan tpts ON tp.id = tpts.tmpermohonan_id
                WHERE 
				  tp.id IN (
                        SELECT MAX(tpts.id)AS permohonan_status_id
                        FROM tmpermohonan_trstspermohonan tpts
                        GROUP BY tmpermohonan_id
                  )
                  AND tpts.trstspermohonan_id IN (14)
              )
            ORDER BY a.d_terima_berkas DESC LIMIT {$offset}, {$limit}";

        $result = $this->db->query($query)->result_array();

        ##### Count total Rows #######
        $query_count = "SELECT COUNT(pendaftaran_id)as count
            FROM tmpermohonan a
            INNER JOIN tmpemohon_tmpermohonan b ON a.id=b.tmpermohonan_id
            INNER JOIN tmpemohon c ON c.id=b.tmpemohon_id
            INNER JOIN tmpermohonan_trperizinan h ON h.tmpermohonan_id=a.id
            INNER JOIN trperizinan i ON i.id=h.trperizinan_id
            WHERE a.c_izin_selesai = 0
              AND a.id IN (SELECT tp.id
                FROM
                tmpermohonan tp
                INNER JOIN tmpermohonan_trstspermohonan tpts ON tp.id = tpts.tmpermohonan_id
                WHERE 
				  tp.id IN (
                        SELECT MAX(tpts.id)AS permohonan_status_id
                        FROM tmpermohonan_trstspermohonan tpts
                        GROUP BY tmpermohonan_id
                  )
                  AND tpts.trstspermohonan_id IN (14)
              )";
        $result_count = $this->db->query($query_count)->result_array();
        $total_rows = $result_count[0]['count'];
        foreach ($result as $key => $res) {
            $result[$key]['num_rows'] = $total_rows;
        }
        ####################################

        if ($result) {
            $this->response($result, 200);
        } else {
            $this->response(array('error' => 'Tidak ada data..'), 404);
        }
    }

    public function propinsi_get()
    {
        $prop = new trpropinsi();
        $list_prop = $prop->order_by('n_propinsi', 'ASC')->get();

        $data1 = array();
        $i = 0;
        foreach ($list_prop as $all) {
            $data1[$i]['id'] = $all->id;
            $data1[$i]['nama_propinsi'] = $all->n_propinsi;
            $i++;
        }

        if ($data1) {
            $this->response($data1, 200);
        } else {
            $this->response(NULL, 404);
        }
    }

    function jenisnama_get()
    {
        if (!$this->get('id')) {
            $this->response(NULL, 400);
        }
        $id = $this->get('id');

        $dt = new trperizinan();
        $list = $dt->where("id = $id")->get();

        $data = array();
        $i = 0;
        foreach ($list as $all) {
            $data[$i]['id'] = $all->id;
            $data[$i]['naam'] = $all->n_perizinan;
            $data[$i]['kelompok_izin_id'] = $all->trkelompok_perizinan->id;
            $i++;
        }

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(NULL, 404);
        }
    }

    public function kabupaten_get()
    {

        if (!$this->get('id_prop')) {
            $this->response(NULL, 400);
        }
        $propinsi_id = $this->get('id_prop');

        // Get permohonan
        $propinsi = new trpropinsi();
        $propinsi->where('id', $propinsi_id)->get();

        // Get what Izin is
        $list = $propinsi->trkabupaten->order_by('n_kabupaten', 'ASC')->get();

        $data = array();
        $i = 0;
        foreach ($list as $all) {
            $data[$i]['id'] = $all->id;
            $data[$i]['nama_kabupaten'] = $all->n_kabupaten;
            $i++;
        }

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(NULL, 404);
        }
    }

    public function kelurahan_get()
    {

        if (!$this->get('id_kec')) {
            $this->response(NULL, 400);
        }
        $id = $this->get('id_kec');


        $dt = new trkecamatan();
        $dt->where('id', $id)->get();

        // Get what Izin is
        $list = $dt->trkelurahan->order_by('n_kelurahan', 'ASC')->get();

        $data = array();
        $i = 0;
        foreach ($list as $all) {
            $data[$i]['id'] = $all->id;
            $data[$i]['nama_kelurahan'] = $all->n_kelurahan;
            $i++;
        }

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(NULL, 404);
        }
    }

    public function kecamatan_get()
    {

        if (!$this->get('id_kab')) {
            $this->response(NULL, 400);
        }
        $kabupaten_id = $this->get('id_kab');
        $kabupaten = new trkabupaten();
        $kabupaten->where('id', $kabupaten_id)->get();


        $list = $kabupaten->trkecamatan->order_by('n_kecamatan', 'ASC')->get();

        $data = array();
        $i = 0;
        foreach ($list as $all) {
            $data[$i]['id'] = $all->id;
            $data[$i]['nama_kecamatan'] = $all->n_kecamatan;
            $i++;
        }
        //   echo $i;
        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(NULL, 404);
        }
    }

    //------------------------------

    public function jumlahIzinGangguan_get()
    {

        if (!$this->get('izin')) {
            $this->response(NULL, 400);
        }
        $izin = $this->get('izin');
        $d_izin = $izin;
        $id_kab = $this->uri->segment('5');

        //        $ok = $this->get('ok');
        //        $d_ok = $ok;


        // Get kabupaten
        $kabupaten = new trkabupaten();
        $kabupaten->get_by_id($id_kab);

        $perizinan = new trperizinan();
        $perizinan->where('d_entry', $d_izin);
        $total = $perizinan->count();

        $data = array();
        $data['jumlahizin'] = $total;
        $data['Kabupaten'] = $kabupaten->n_kabupaten;

        $perizinan = array();
        $perizinan[] = $data;

        if ($perizinan) {
            $this->response($perizinan, 200);
        } else {
            $this->response(array('error' => 'Tidak ada data..'), 404);
        }
    }


    public function jumlahPerizinan_get()
    {

        // Get perizinan

        $perizinan = new trperizinan();

        $data = array();
        $data['jumlahPerizinan'] = $perizinan->count();

        //        $data['no_pendaftaran'] = $permohonan->pendaftaran_id;
        //        $data['nama'] = $permohonan->tmpemohon->n_pemohon;
        //        $data['telp'] = $permohonan->tmpemohon->telp_pemohon;
        //        $data['alamat'] = $permohonan->tmpemohon->a_pemohon;
        //        $data['permohonan'] = $permohonan->trperizinan->n_perizinan;
        //        $data['tracking'] = $permohonan->trstspermohonan->n_sts_permohonan;

        $permohonan = array();
        $permohonan[] = $data;

        if ($permohonan) {
            $this->response($permohonan, 200);
        } else {
            $this->response(array('error' => 'Tidak ada data..'), 404);
        }
    }



    public function ambilPermohonan_get()
    {

        if (!$this->get('permohonan')) {
            $this->response(NULL, 400);
        }
        $d_entry = $this->get('permohonan');
        $entry = $d_entry;

        // Get permohonan

        $permohonan_tetap = new tmpermohonan();
        $permohonan_tetap = $permohonan_tetap->where_related('trstspermohonan', 'id >', 8)
            ->where('d_terima_berkas', $d_entry)
            ->where_related('trstspermohonan', 'id !=', 9);

        $permohonan_proses = new tmpermohonan();
        $permohonan_proses = $permohonan_proses->where_related('trstspermohonan', 'id <=', 9)
            ->where('d_terima_berkas', $d_entry)
            ->where_related('trstspermohonan', 'id !=', 8);

        $data = array();
        $data['ditetapkan'] = $permohonan_tetap->count();
        $data['diproses'] = $permohonan_proses->count();
        //        $data['no_pendaftaran'] = $permohonan->pendaftaran_id;
        //        $data['nama'] = $permohonan->tmpemohon->n_pemohon;
        //        $data['telp'] = $permohonan->tmpemohon->telp_pemohon;
        //        $data['alamat'] = $permohonan->tmpemohon->a_pemohon;
        //        $data['permohonan'] = $permohonan->trperizinan->n_perizinan;
        //        $data['tracking'] = $permohonan->trstspermohonan->n_sts_permohonan;

        $permohonan = array();
        $permohonan[] = $data;

        if ($permohonan) {
            $this->response($permohonan, 200);
        } else {
            $this->response(array('error' => 'Tidak ada data..'), 404);
        }
    }

    public function perusahaan_get()
    {

        if (!$this->get('getdatawpbynpwp')) {
            $this->response(NULL, 400);
        }
        $npwp = $this->get('getdatawpbynpwp');
        echo $npwp;
        // // Get permohonan
        //        $perusahaan = new tmperusahaan();
        //        $perusahaan->where('npwp', $npwp);
        //        $perusahaan->get();
        //
        //        $data = array();
        //        if($perusahaan->id) $getdata = "1";
        //        else $getdata = "2";
        //        $data['getdata'] = $getdata;
        //        $data['id_perusahaan'] = $perusahaan->id;
        //        $data['npwp'] = $perusahaan->npwp;
        //        $data['nodaftar'] = $perusahaan->no_daftar;
        //        $data['nama_perusahaan'] = $perusahaan->n_perusahaan;
        //        $data['alamat_usaha'] = $perusahaan->a_perusahaan;
        //        $data['rt'] = $perusahaan->rt;
        //        $data['rw'] = $perusahaan->rw;
        //        $data['telp_perusahaan'] = $perusahaan->i_telp_perusahaan;
        //        $data['fax'] = $perusahaan->i_fax;
        //        $data['email'] = $perusahaan->email;
        //
        //        $perusahaan = array();
        //        $perusahaan[] = $data;
        //
        //        if($perusahaan) {
        //            $this->response($perusahaan, 200);
        //        } else {
        //            $this->response(array('error' => 'Tidak ada data..'), 404);
        //        }
    }

    public function pengaduan_post()
    {

        $pesan = new tmpesan();
        $sumber = new trsumber_pesan();
        $sumber->get_by_id('5');
        $stat = new trstspesan();
        $stat->get_by_id('9');

        $pesan->nama = $this->post('nama');
        $pesan->alamat = $this->post('alamat');
        $pesan->kelurahan = $this->post('kelurahan');
        $pesan->kecamatan = $this->post('kecamatan');
        $pesan->e_pesan = $this->post('e_pesan');
        $pesan->d_entry = $this->post('d_entry');
        //$pesan->trsumber_pesan->

        if ($pesan->save(array($sumber, $stat))) {
            $message = array(
                'status' => 'success'
            );

            $this->response($message, 200); // 200 being the HTTP response code
        } else {
            $message = array(
                'status' => 'failed'
            );

            $this->response($message, 200); // 200 being the HTTP response code
        }
    }

    public function pendaftaran_post()
    {
        //Ditambahkan pada 26 Dec 2014, untuk request dari angular js (mobile)
        if ($this->get('source') == 'mobile') {
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true); //Decode menjadi associative array
        }

        $perizinan = new trperizinan();
        $perizinan->get_by_id($this->post('jenis_izin_id'));

        $jenis_permohonan = new trjenis_permohonan();
        $jenis_permohonan->get_by_id($this->post('jenis_permohonan_id'));

        /* Penomoran Pendaftaran
         * Awal
         */
        $data_id = new tmpermohonan();
        $data_id->select_max('id')->get();
        $data_id->get_by_id($data_id->id);

        $data_tahun = date("Y");
        //Per Tahun Auto Restart NoUrut
        if ($data_id->d_tahun === $data_tahun)
            $data_urut = $data_id->i_urut + 1;
        else {
            $data_urut = 1;
            $year = new year();
            $year->tahun = $data_tahun;
            $year->save();
        }

        $i_urut = strlen($data_urut);
        for ($i = 5; $i > $i_urut; $i--) {
            $data_urut = "0" . $data_urut;
        }

        $data_izin = $perizinan->id;
        $i_izin = strlen($data_izin);
        for ($i = 3; $i > $i_izin; $i--) {
            $data_izin = "0" . $data_izin;
        }

        $data_jenis = $jenis_permohonan->id;
        $i_izin = strlen($data_jenis);
        for ($i = 2; $i > $i_izin; $i--) {
            $data_jenis = "0" . $data_jenis;
        }

        $data_bulan = date("n");
        $i_bulan = strlen($data_bulan);
        for ($i = 2; $i > $i_bulan; $i--) {
            $data_bulan = "0" . $data_bulan;
        }

        $permohonan = new tmpermohonan();
        $permohonan->i_urut = $data_urut;
        $permohonan->d_tahun = $data_tahun;

        $app_folder = new settings();
        $app_folder->where('name', 'app_folder')->get();
        $app_folder = $app_folder->value;
        if ($app_folder === "Bantul") {
            $nomor_pendaftaran = $data_urut . "/"
                . $data_izin . "/" . $data_jenis . "/"
                . $data_bulan . "/" . $data_tahun;
        } else {
            $nomor_pendaftaran = $data_urut
                . $data_izin . $data_jenis
                . $data_bulan . $data_tahun;
        }


        $username = new user();
        $username->where('username', $this->session->userdata('username'))->get();
        if ($username->id)
            $user = $username->realname;
        else
            $user = "................................";


        $permohonan->i_entry = $user;
        $permohonan->pendaftaran_id = $nomor_pendaftaran;
        $permohonan->d_terima_berkas = date('Y-m-d');
        $permohonan->d_survey = date('Y-m-d');
        $permohonan->a_izin = " ";
        $permohonan->keterangan = $this->input->post('keterangan');
        $permohonan->c_pendaftaran = '2';
        $permohonan->trunitkerja_id = $this->input->post("unit_kerja_id");
        //-------upload file

        $config['upload_path'] = './assets/upload/';
        $config['allowed_types'] = 'pdf|doc|docx|word|xlsx|xl';
        $config['max_size'] = '2000';
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload('file');

        $permohonan->file_ttd = $this->input->post('file');

        //------upload file
        $tgl_skr = $this->lib_date->get_date_now();
        $permohonan->d_entry = $tgl_skr;
        $tgl_daftar = $this->input->post('tgl_daftar');
        $tgl_durasi = $this->lib_date->set_date($tgl_daftar, $perizinan->v_hari);
        $libur = new tmholiday();
        $hari_libur = $libur->where('date >=', $tgl_daftar)->where('date <=', $tgl_durasi)->count();
        $hari_durasi = $perizinan->v_hari + $hari_libur;
        $permohonan->d_selesai_proses = $this->lib_date->set_date($tgl_skr, $hari_durasi);
        $permohonan->save($perizinan);
        /* Penomoran Pendaftaran
         * Akhir
         */

        $permohonan_akhir = new tmpermohonan();
        $permohonan_akhir->select_max('id')->get();
        //$permohonan_akhir->where('i_urut', $data_urut)->where('d_tahun', $data_tahun)->get();

        /* Input Data Pemohon */
        $pemohon = new tmpemohon_sementara();
        $pemohon->no_referensi = $this->post('no_refer');
        $pemohon->source = $this->post('cmbsource');
        $pemohon->n_pemohon = $this->post('nama_pemohon');
        $pemohon->telp_pemohon = $this->post('no_telp');
        $pemohon->a_pemohon = $this->post('alamat_pemohon');
        $pemohon->a_pemohon_luar = " ";
        $kelurahan_p = new trkelurahan();
        $kelurahan_p->get_by_id($this->post('kelurahan_pemohon'));
        $pemohon->save(array($permohonan_akhir, $kelurahan_p));

        /* Input Data Perusahaan okok */
        // edited by mucktar
        if ($this->post('nama_perusahaan') && $this->post('nama_perusahaan') != '') {
            $perusahaan = new tmperusahaan_sementara();
            $perusahaan->n_perusahaan = $this->post('nama_perusahaan');
            $perusahaan->npwp = $this->post('npwp');
            $perusahaan->i_telp_perusahaan = $this->post('telpPerusahaan');
            $perusahaan->a_perusahaan = $this->post('alamat_usaha');
            $perusahaan->no_reg_perusahaan = $this->post('no_registrasi');
            $kelurahan_u = new trkelurahan();
            $kelurahan_u->get_by_id($this->post('kelurahan_usaha'));
            $perusahaan->save(array($permohonan_akhir, $kelurahan_u));
        }

        /* Input Data Syarat Perizinan */
        $syarat_pendaftaran = new tmpermohonan_trsyarat_perizinan();
        $syarat_pendaftaran->where('tmpermohonan_id', $permohonan_akhir->id)->get();
        $syarat_pendaftaran->delete();

        $syarat = $this->post('pemohon_syarat');
        $syarat_len = count($syarat);

        $is_array = NULL;
        for ($i = 0; $i < $syarat_len; $i++) {
            if ($is_array !== $syarat[$i]) {
                $syarat_daftar = new tmpermohonan_trsyarat_perizinan();
                $syarat_daftar->tmpermohonan_id = $permohonan_akhir->id;
                $syarat_daftar->trsyarat_perizinan_id = $syarat[$i];
                $syarat_daftar->save();
            }
            $is_array = $syarat[$i];
        }

        /* Input Data Tracking Progress */
        $tracking_izin = new tmtrackingperizinan();
        $tracking_izin->pendaftaran_id = $nomor_pendaftaran;
        $tracking_izin->status = 'Insert';
        $tracking_izin->d_entry = $this->lib_date->get_date_now();
        $sts_izin = new trstspermohonan();
        $sts_izin->get_by_id('1'); //pendaftaran sementara [Lihat Tabel trstspermohonan()]
        $permohonan_akhir->save($sts_izin);
        $tracking_izin->save($sts_izin);
        $tracking_izin->save($permohonan_akhir);

        $save = $permohonan_akhir->save($jenis_permohonan);
        if ($save) {
            $message = array(
                'status' => 'success',
                'success' => true, //Ditambahkan untuk return ke mobile
                'no_pendaftaran' => $nomor_pendaftaran
            );
            //            echo $nomor_pendaftaran;exit();
            $this->response($message, 200); // 200 being the HTTP response code
        } else {
            $message = array(
                'status' => 'failed',
                'success' => false, //Ditambahkan untuk return ke mobile
                'no_pendaftaran' => ' '
            );
        }
        $this->response($message, 200); // 200 being the HTTP response code

    }

    public function jenisPerizinanList_get()
    {
        $perizinan = new trperizinan();
        $list_perizinan = $perizinan->order_by('n_perizinan', 'ASC')->get();

        $data = array();
        $i = 0;
        foreach ($list_perizinan as $all) {
            $data[$i]['id'] = $all->id;
            $data[$i]['jenis_perizinan'] = $all->n_perizinan;
            $data[$i]['kelompok_izin'] = $all->trkelompok_perizinan->n_kelompok;
            $data[$i]['v_hari'] = $all->v_hari;
            $i++;
        }

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(NULL, 404);
        }
    }

    public function jenisPerizinanTarifList_get()
    {
        $perizinan = new trperizinan();
        $list_perizinan = $perizinan->where_related_trkelompok_perizinan('id', 1)->order_by('n_perizinan', 'ASC')->get();

        $data = array();
        $i = 0;
        foreach ($list_perizinan as $all) {
            $data[$i]['id'] = $all->id;
            $data[$i]['jenis_perizinan'] = $all->n_perizinan;
            $data[$i]['v_hari'] = $all->v_hari;
            $i++;
        }

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(NULL, 404);
        }
    }

    // mengambil jumlah syarat perizinan berdasarkan id izin

    public function syaratPerizinan_get()
    {
        if (!$this->get('perizinan')) {
            $this->response(NULL, 400);
        }
        $perizinan = $this->get('perizinan');
        $perizinan_id = $perizinan;

        $izin = new trsyarat_perizinan();
        $syarat = $izin->where_related('trperizinan', 'id', $perizinan_id)->get();

        $data = array();
        $i = 0;
        foreach ($syarat as $all) {
            $data[$i]['id'] = $all->id;
            $data[$i]['syarat_perizinan'] = $all->v_syarat;
            $i++;
        }

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(NULL, 404);
        }
    }

    // mengambil jumlah syarat perizinan berdasarkan id izin

    // mengambil jumlah dasar hukum berdasarkan id izin

    public function dasarHukum_get()
    {
        if (!$this->get('perizinan')) {
            $this->response(NULL, 400);
        }
        $perizinan = $this->get('perizinan');
        $perizinan_id = $perizinan;

        $hukum = new trdasar_hukum();
        $list = $hukum->where_related('trperizinan', 'id', $perizinan_id)->get();

        $data = array();
        $i = 0;
        foreach ($hukum as $all) {
            $data[$i]['id'] = $all->id;
            $data[$i]['dasar hukum'] = $all->deskripsi;
            $i++;
        }

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(NULL, 404);
        }
    }

    // mengambil jumlah dasar hukum berdasarkan id izin

    // mengambil nilai retribusi berdasarkan id izin

    public function nilaiRetribusi_get()
    {
        if (!$this->get('perizinan')) {
            $this->response(NULL, 400);
        }
        $perizinan = $this->get('perizinan');
        $perizinan_id = $perizinan;

        // Get perizinan
        $izin = new trperizinan();
        $izin->where('id', $perizinan_id)->get();

        $v_retribusi = NULL;
        if ($izin->trretribusi->m_perhitungan == "0") {
            $v_retribusi = $izin->trretribusi->v_retribusi;
        } elseif ($izin->trretribusi->m_perhitungan == "1") {
            $v_retribusi = "Perhitungan Manual";
        }


        $data = array();
        $data['nama_perizinan'] = $izin->n_perizinan;
        $data['retribusi'] = $v_retribusi;


        $izin = array();
        $izin[] = $data;

        if ($izin) {
            $this->response($izin, 200);
        } else {
            $this->response(array('error' => 'Tidak ada data..'), 404);
        }
    }

    // mengambil nilai retribusi berdasarkan id izin

    public function jenisPermohonanList_get()
    {
        $permohonan = new trjenis_permohonan();
        $list_permohonan = $permohonan->get();

        $data = array();
        $i = 0;
        foreach ($list_permohonan as $all) {
            $data[$i]['id'] = $all->id;
            $data[$i]['jenis_permohonan'] = $all->n_permohonan;
            $i++;
        }

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(NULL, 404);
        }
    }

    public function pengaduanViaSMS_get()
    {
        $pesan = new tmpesan();
        $sumber_pesan = new trsumber_pesan();

        $sumber_pesan->where('id', '2')->get();
        $list_pesan = $pesan->get($sumber_pesan);

        $data = array();
        $i = 0;

        foreach ($list_pesan as $all) {
            $data[$i]['id'] = $all->id;
            $pengirim = $all->telp;
            $len = strlen($all->telp) - 3;
            $data[$i]['pengirim'] = substr($all->telp, 0, $len) . 'xxx';
            $data[$i]['pesan'] = $all->e_pesan;
            $i++;
        }

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(NULL, 404);
        }
    }

    //    public function sql($entry)
    //    {
    //        $query = "select * from tmpermohonan as a
    //                  inner join tmpermohonan_trstspermohonan as b on b.tmpermohonan_id=a.id
    //                  inner join trstspermohonan as c on b.trstspermohonan_id=c.id
    //                  where (c.id>=8 and c.id != 9) and a.d_terima_berkas = '".$entry."';
    //                  ";
    //        $hasil = $this->db->query($query);
    //        return $hasil->num_rows();
    //
    //    }

    /**
     * Fungsi untuk mendapatkan item retribusi berdasarkan id izin yang dipilih
     */
    public function itemRetribusi_get()
    {
        $data = array();

        if (!$this->get('trperizinan_id')) {
            $this->response(null, 400);
        }
        $trperizinan_id = $this->get('trperizinan_id');

        //Ambil data Setting Tarif untuk Jenis Izin yang diajukan
        $this->load->model('setting_tarif/setting_tarif_item');
        $this->load->model('setting_tarif/setting_tarif_harga');
        $setting_tarif_item = new setting_tarif_item();
        $list_item_tarif = $setting_tarif_item->where('trperizinan_id', $trperizinan_id)
            ->where('deleted', 0)->order_by('nama_item', 'ASC')->get();

        foreach ($list_item_tarif as $key => $item_tarif) {
            $data[$key]['nama_item'] = $item_tarif->nama_item;
            $data[$key]['kode_item'] = strtolower($this->_remove_whitespace($item_tarif->nama_item));
            $data[$key]['satuan'] = $item_tarif->satuan;

            $setting_tarif_harga = new setting_tarif_harga();
            $kategori_harga = $setting_tarif_harga->where('setting_tarif_item_id', $item_tarif->id)->get();
            $num_kategori_harga = $setting_tarif_harga->where('setting_tarif_item_id', $item_tarif->id)->count();

            $option_kategori = array(); //versi Lama
            $options_kategori = array(); //versi baru

            if ($num_kategori_harga > 0) { //Jika ada option, munculkan dropdown
                foreach ($kategori_harga as $index => $kategori) {
                    $option_kategori[$kategori->harga] = $kategori->kategori . ' - ' . $kategori->harga;
                    $options_kategori[$index]['harga'] = $kategori->harga;
                    $options_kategori[$index]['kategori'] = $kategori->kategori;
                }
            }
            $data[$key]['option'] = $option_kategori;
            $data[$key]['options'] = $options_kategori;
        }

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(NULL, 404);
        }
    }

    /**
     * Fungsi untuk mendapatkan item retribusi berdasarkan id izin yang dipilih
     */
    public function formulaRetribusi_get()
    {

        if (!$this->get('trperizinan_id')) {
            $this->response(NULL, 400);
        }

        $trperizinan_id = $this->get('trperizinan_id');

        $data = array();

        //Ambil data Setting Tarif untuk Jenis Izin yang diajukan
        $this->load->model('setting_formula/setting_formula_retribusi');
        $setting_formula_retribusi = new setting_formula_retribusi();
        $formula_retribusi = $setting_formula_retribusi->get_formula_javascript($trperizinan_id);

        $data[]['formula'] = $formula_retribusi;

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(NULL, 404);
        }
    }

    /**
     * @author Indra
     * Fungsi API untuk mendapatkan nama provinsi, kabupaten, kecamatan, dan kelurahan untuk digunakan di Mobile Apps
     */
    public function detailwilayah_get($provinsiId = 3, $kabupatenId = 43, $kecamatanId = 372, $kelurahanId = 944)
    {
        if (!$this->get('provinsi_id') || !$this->get('kabupaten_id') || !$this->get('kecamatan_id') || !$this->get('kelurahan_id')) {
            $this->response(NULL, 400);
        }

        $provinsiId = $this->get('provinsi_id');
        $kabupatenId = $this->get('kabupaten_id');
        $kecamatanId = $this->get('kecamatan_id');
        $kelurahanId = $this->get('kelurahan_id');

        $namaProvinsi = '';
        $namaKabupaten = '';
        $namaKecamatan = '';
        $namaKelurahan = '';

        //Ambil Data Provinsi
        $provinsi = new trpropinsi();
        $getProvinsi = $provinsi->where('id', $provinsiId)->get();
        if ($getProvinsi->id) {
            $namaProvinsi = $getProvinsi->n_propinsi;
        }

        //Ambil Data Kabupaten
        $kabupaten = $provinsi->trkabupaten;
        $getKabupaten = $kabupaten->where('id', $kabupatenId)->get();
        if ($getKabupaten->id) {
            $namaKabupaten = $getKabupaten->n_kabupaten;
        }

        //Ambil Data Kecamatan
        $kecamatan = $kabupaten->trkecamatan;
        $getKecamatan = $kecamatan->where('id', $kecamatanId)->get();
        if ($getKecamatan->id) {
            $namaKecamatan = $getKecamatan->n_kecamatan;
        }

        //Ambil Data Kelurahan
        $kelurahan = $kecamatan->trkelurahan;
        $getKelurahan = $kelurahan->where('id', $kelurahanId)->get();
        if ($getKelurahan->id) {
            $namaKelurahan = $getKelurahan->n_kelurahan;
        }

        $data = array(
            'nama_provinsi' => $namaProvinsi,
            'nama_kabupaten' => $namaKabupaten,
            'nama_kecamatan' => $namaKecamatan,
            'nama_kelurahan' => $namaKelurahan,
        );

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(NULL, 404);
        }
    }

    /**
     * @author Indra
     * Fungsi untuk melakukan kalkulasi nilai retribusi dari Mobile Apps
     */
    public function kalkulasiRetribusi_post()
    {
        $nilaiRetribusi = 0;
        $success = false;
        $data = array();

        //Ditambahkan pada 26 Dec 2014, untuk request dari angular js (mobile)
        if ($this->get('source') == 'mobile') {
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true); //Decode menjadi associative array
        }
        if (!$this->post('trperizinan_id')) {
            $this->response(NULL, 400);
        }
        $trperizinan_id = $this->post('trperizinan_id');

        //BEGIN - Ambil Item Tarif untuk set nilainya menjadi 0 terlebih dahulu
        $this->load->model('setting_tarif/setting_tarif_item');
        $setting_tarif_item = new setting_tarif_item();
        $list_item_tarif = $setting_tarif_item->where('trperizinan_id', $trperizinan_id)
            ->where('deleted', 0)->order_by('nama_item', 'ASC')->get();

        foreach ($list_item_tarif as $key => $item_tarif) {
            $namaVariabel = strtolower($this->_remove_whitespace($item_tarif->nama_item));
            $$namaVariabel = 0; //Buat Variabel dan set nilainya 0
        }
        //AND - Ambil Item Tarif untuk set nilainya menjadi 0 terlebih dahulu

        //Ambil data Setting Tarif untuk Jenis Izin yang diajukan
        $this->load->model('setting_formula/setting_formula_retribusi');
        $settingFormula = new setting_formula_retribusi();
        $getFormula = $settingFormula->where('trperizinan_id', $trperizinan_id)->get();
        if ($getFormula->id) { //Jika ada  formula, lakukan perhitungan
            $formula = $getFormula->formula;
            extract($_POST);
            $calcFormula = '$nilaiRetribusi =' . $formula . ';';
            eval($calcFormula);
            $success = true;
        }
        $data['success'] = $success;
        $data['nilai_retribusi'] = $nilaiRetribusi;

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(NULL, 404);
        }
    }

    /**
     * @author Indra
     * Fungsi untuk mendapatkan data detail perizinan agam untuk Mobile Apps
     */
    function detailperizinan_get()
    {
        if (!$this->get('trperizinan_id')) {
            $this->response(NULL, 400);
        }
        $id = $this->get('trperizinan_id');

        $trperizinan = new trperizinan();
        $getPerizinan = $trperizinan->where("id", $id)->get();

        $data = array();
        if ($getPerizinan->id) {
            $data['id'] = $getPerizinan->id;
            $data['nama_perizinan'] = $getPerizinan->n_perizinan;
            $data['ada_retribusi'] = ($getPerizinan->trkelompok_perizinan->id == 4) ? true : false;
        }

        if ($data) {
            $this->response($data, 200);
        } else {
            $this->response(NULL, 404);
        }
    }

    /**
     * Fungsi untuk Registrasi User dari Mobile Apps
     */
    public function register_post()
    {
        // untuk request dari angular js (mobile)
        if ($this->get('source') == 'mobile') {
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true); //Decode menjadi associative array
        }

        if (
            !$this->post('jenis_identitas') || !$this->post('id_pemohon') || !$this->post('nama_pemohon') ||
            !$this->post('no_telp') || !$this->post('provinsi_pemohon') || !$this->post('email_pemohon') || !$this->post('password')
        ) {
            $this->response(NULL, 400);
        } else {
            $this->load->model('pemohon/tmpemohon');
            $this->load->model('mobile_pemohon/mobile_user');
            $this->load->model('pelayanan/trkelurahan');
            $mobilePemohon = new tmpemohon();
            $mobileUser = new mobile_user();

            //Default Value untuk Data Optional
            $alamatPemohon  = null;
            $kabupatenPemohon = null;
            $kecamatanPemohon = null;
            $kelurahanPemohon = null;

            $jenisIdentitas = $this->post('jenis_identitas');
            $idPemohon = $this->post('id_pemohon');
            $namaPemohon = $this->post('nama_pemohon');
            $noTelpPemohon = $this->post('no_telp');
            $emailPemohon = $this->post('email_pemohon');
            $passwordPemohon = $this->post('password');

            if ($this->post('alamat_pemohon')) {
                $alamatPemohon = $this->post('alamat_pemohon');
            }
            $provinsiPemohon = $this->post('provinsi_pemohon');
            if ($this->post('kabupaten_pemohon')) {
                $kabupatenPemohon = $this->post('kabupaten_pemohon');
            }
            if ($this->post('kecamatan_pemohon')) {
                $kecamatanPemohon = $this->post('kecamatan_pemohon');
            }
            if ($this->post('kelurahan_pemohon')) {
                $kelurahanPemohon = $this->post('kelurahan_pemohon');
            }

            $mobilePemohon->no_referensi = $idPemohon;
            $mobilePemohon->n_pemohon = $namaPemohon;
            $mobilePemohon->telp_pemohon = $noTelpPemohon;
            $mobilePemohon->a_pemohon = $alamatPemohon;
            $mobilePemohon->i_user = 'Mobile App';
            $mobilePemohon->d_entry = date('Y-m-d');
            $mobilePemohon->cek_prop = 1;
            $mobilePemohon->source = $jenisIdentitas;
            $mobilePemohon->email_pemohon = $emailPemohon;
            $trkelurahan = new trkelurahan();
            $trkelurahan->get_by_id($kelurahanPemohon);

            if ($mobilePemohon->save(array($trkelurahan))) {
                //            if($mobilePemohon->save()){
                //Save ke Mobile User
                $mobileUser->username = $emailPemohon;
                $mobileUser->realname = $namaPemohon;
                $mobileUser->password = md5($passwordPemohon);
                $mobileUser->tmpemohon_id = $mobilePemohon->id;
                if ($mobileUser->save()) {
                    $message = array(
                        'success' => true,
                    );
                } else {
                    $message = array(
                        'success' => false
                    );
                }
                $this->response($message, 200); // 200 being the HTTP response code
            } else {
                $message = array(
                    'success' => false
                );
                $this->response($message, 200); // 200 being the HTTP response code
            }
        }
    }

    /*
     * Fungsi untuk API login Mobile Apps
     */
    function login_post()
    {
        // untuk request dari angular js (mobile)
        if ($this->get('source') == 'mobile') {
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true); //Decode menjadi associative array
        }
        if (
            !$this->post('username') || !$this->post('password')
        ) {
            $this->response(NULL, 400);
        } else {
            $success = false;
            $mobileUserId = null;
            $this->load->model('pemohon/tmpemohon');
            $this->load->model('mobile_pemohon/mobile_user');
            $mobileUser = new mobile_user();
            $username = $this->post('username');
            $password = md5($this->post('password'));
            $existingUser = $mobileUser->where('username', $username)->where('password', $password)->where('active', 1)->get();
            if ($existingUser->id) { //Jika user ditemukan
                $success = true;
                $mobileUserId = $existingUser->id;
            }
            $message = array(
                'success' => $success,
                'mobile_user_id' => $mobileUserId
            );
            $this->response($message, 200); // 200 being the HTTP response code
        }
    }

    /**
     * Fungsi untuk mendapatkan Data Detail User dari Mobile Apps
     */
    function detUser_get()
    {
        if (!$this->get('mid')) { //Jika ada mobile user id
            $this->response(null, 400);
        } else {
            $data = array();
            $mobileUserId = $this->get('mid');
            $this->load->model('pemohon/tmpemohon');
            $this->load->model('mobile_pemohon/mobile_user');
            $mobileUser = new mobile_user();
            $mobilePemohon = new tmpemohon();
            $existingUser = $mobileUser->where('id', $mobileUserId)->get();
            if ($existingUser->id) { //Jika User ditemukan
                $existingPemohon = $mobilePemohon->where('id', $existingUser->tmpemohon_id)->get();
                if ($existingPemohon->id) {
                    $data['mobile_user_id'] = $mobileUser->id;
                    $data['jenis_identitas'] = $existingPemohon->source;
                    $data['no_identitas'] = $existingPemohon->no_referensi;
                    $data['nama_pemohon'] = $existingPemohon->n_pemohon;
                    $data['telp_pemohon'] = $existingPemohon->telp_pemohon;
                    $data['alamat_pemohon'] = $existingPemohon->a_pemohon;
                    $data['email'] = $existingPemohon->email_pemohon;
                    $data['kelurahan_pemohon_id'] = $existingPemohon->trkelurahan->id;
                    $data['kecamatan_pemohon_id'] = $existingPemohon->trkelurahan->trkecamatan->id;
                    $data['kabupaten_pemohon_id'] = $existingPemohon->trkelurahan->trkecamatan->trkabupaten->id;
                    $data['provinsi_pemohon_id'] = $existingPemohon->trkelurahan->trkecamatan->trkabupaten->trpropinsi->id;
                    $data['kelurahan_pemohon'] = $existingPemohon->trkelurahan->n_kelurahan;
                    $data['kecamatan_pemohon'] = $existingPemohon->trkelurahan->trkecamatan->n_kecamatan;
                    $data['kabupaten_pemohon'] = $existingPemohon->trkelurahan->trkecamatan->trkabupaten->n_kabupaten;
                    $data['provinsi_pemohon'] = $existingPemohon->trkelurahan->trkecamatan->trkabupaten->trpropinsi->n_propinsi;
                }
            }

            if ($data) {
                $this->response($data, 200);
            } else {
                $this->response(null, 404);
            }
        }
    }

    /**
     * Fungsi untuk mendapatkan data history Pendaftaran User Mobile Apps
     */
    function hisDaftar_get()
    {
        if (!$this->get('mid')) {
            $this->response(NULL, 400);
        }
        $this->load->model('pemohon/tmpemohon');
        $this->load->model('mobile_pemohon/mobile_user');
        $mobileUserId = $this->get('mid');

        $mobileUser = new mobile_user();
        $existingUser = $mobileUser->get_by_id($mobileUserId);
        if ($existingUser->id) { //Jika User ditemukan
            $data = array();
            $mobilePemohonId = $existingUser->tmpemohon_id;
            $mobilePemohon = new tmpemohon();

            $existingPemohon = $mobilePemohon->get_by_id($mobilePemohonId);
            if ($existingPemohon->id) { //Jika Pemohon ditemukan
                $allPermohonan = $existingPemohon->tmpermohonan->get(); //Ambil data permohonannya
                foreach ($allPermohonan as $index => $permohonan) {
                    $status_permohonan = $permohonan->trstspermohonan->get();
                    $data[$index]['no_pendaftaran'] = $permohonan->pendaftaran_id;
                    $data[$index]['nama_izin'] = $permohonan->trperizinan->n_perizinan;
                    $data[$index]['status_permohonan'] = $status_permohonan->n_sts_permohonan;
                    $data[$index]['tgl_terima_berkas'] = $permohonan->d_terima_berkas;
                    $data[$index]['tgl_survey'] = $permohonan->d_survey;
                    $data[$index]['tgl_selesai_proses'] = $permohonan->d_selesai_proses;
                    $data[$index]['tahun'] = $permohonan->d_tahun;
                    $data[$index]['izin_selesai'] = (int) $permohonan->c_izin_selesai;
                }
            }

            $this->response($data, 200);
        } else {
            $this->response(null, 404);
        }
    }

    /**
     * Fungsi untuk mendapatkan History Pengaduan dari User Mobile Apps
     */
    function hisPengaduan_get()
    {
        if (!$this->get('mid')) {
            $this->response(NULL, 400);
        }
        $this->load->model('pemohon/tmpemohon');
        $this->load->model('mobile_pemohon/mobile_user');
        $mobileUserId = $this->get('mid');

        $mobileUser = new mobile_user();
        $existingUser = $mobileUser->get_by_id($mobileUserId);
        if ($existingUser->id) { //Jika User ditemukan
            $data = array();
            //Ambil Data History Pengaduan
            $getPengaduan = $mobileUser->tmpesan->order_by('d_entry', 'DESC')->get();
            if ($getPengaduan->id) { //Jika ditemukan history Pengaduan
                foreach ($getPengaduan as $index => $pengaduan) {
                    $data[$index]['id'] = $pengaduan->id;
                    $data[$index]['e_pesan'] = $pengaduan->e_pesan;
                    $data[$index]['c_tindak_lanjut'] = $pengaduan->c_tindak_lanjut;
                    $data[$index]['nama'] = $pengaduan->nama;
                    $data[$index]['telp'] = $pengaduan->telp;
                    $data[$index]['alamat'] = $pengaduan->alamat;
                    $data[$index]['kelurahan'] = $pengaduan->kelurahan;
                    $data[$index]['kecamatan'] = $pengaduan->kecamatan;
                    $data[$index]['i_entry'] = $pengaduan->i_entry;
                    $data[$index]['d_entry'] = date('M d, Y', strtotime($pengaduan->d_entry));
                    $data[$index]['e_tindak_lanjut'] = $pengaduan->e_tindak_lanjut;
                    $data[$index]['c_skpd_tindaklanjut'] = $pengaduan->c_skpd_tindaklanjut;
                    $data[$index]['d_tindak_lanjut'] = $pengaduan->d_tindak_lanjut;
                    $data[$index]['d_tindaklanjut_selesai'] = $pengaduan->d_tindaklanjut_selesai;
                    $data[$index]['nama_penanggungjawab'] = $pengaduan->nama_penanggungjawab;
                    $data[$index]['e_pesan_koreksi'] = $pengaduan->e_pesan_koreksi;
                    $data[$index]['c_sts_setuju'] = $pengaduan->c_sts_setuju;
                }
            }
            if ($data) {
                $this->response($data, 200);
            } else {
                $this->response(null, 404);
            }
        } else {
            $this->response(null, 404);
        }
    }

    /**
     * @author Indra
     * Fungsi untuk post data Pengaduan dari Mobile Apps
     */
    public function pengaduan_mobile_post()
    {
        $postdata = file_get_contents("php://input");
        $_POST = json_decode($postdata, true); //Decode menjadi associative array

        $pesan = new tmpesan();
        $sumber = new trsumber_pesan();
        $sumber->get_by_id(5); //Sumber Online
        $stat = new trstspesan();
        $stat->get_by_id(9);

        $mobileUser = new mobile_user();
        $mobileUser->get_by_id($this->post('mobile_user_id'));

        $pesan->nama = $this->post('nama');
        $pesan->alamat = $this->post('alamat');
        $pesan->kelurahan = $this->post('kelurahan');
        $pesan->kecamatan = $this->post('kecamatan');
        $pesan->e_pesan = $this->post('deskripsi_pengaduan');
        $pesan->d_entry = date('Y-m-d');

        if ($pesan->save(array($sumber, $stat, $mobileUser))) {
            $message = array(
                'success' => 1
            );
            $this->response($message, 200); // 200 being the HTTP response code
        } else {
            $message = array(
                'success' => 0
            );
            $this->response($message, 200); // 200 being the HTTP response code
        }
    }

    /**
     * Fungsi untuk menerima SMS yang masuk ke Gateway. Message diforward oleh Aplikasi SMS Gateway
     */
    public function receiveSms_get()
    {
        ob_start();
        $nama = null;
        $tipe_sms = null;
        $isi_sms = null;
        $replied = 0;
        $reply_utf8 = null;

        $this->load->model('sms_interaktif/sms_masuk');
        $this->sms_masuk = new sms_masuk();

        //setup PHP UTF-8 stuff
        setlocale(LC_CTYPE, 'en_US.UTF-8');
        mb_internal_encoding("UTF-8");
        mb_http_output('UTF-8');

        //read parameters from HTTP Get URL
        //        $phone = $_GET["phone"];
        $phone = $this->get('phone');
        //        $smscenter = $_GET["smscenter"];
        $smscenter = $this->get('smscenter');
        //        $text_utf8 = rawurldecode($_GET["text"]);
        $text_utf8 = rawurldecode($this->get('text'));

        //if parameters are not present in HTTP url, they can be also present in HTTP header
        /*$headers = getallheaders();
        if (empty($phone)){
            $phone = $headers["phone"];
        }
        if (empty($smscenter)) {
            $smscenter = $headers["smscenter"];
        }
        if (empty($text_utf8)) {
            $text_utf8 = rawurldecode($headers["text"]);
        }*/
        $tipe_pertanyaan = $this->sms_masuk->key_pertanyaan;
        $tipe_tracking = $this->sms_masuk->key_tracking;
        $parsed_sms = $this->sms_masuk->parse_sms($text_utf8);
        //        print_r($parsed_sms);exit();
        if (!empty($parsed_sms)) { //Jika ada hasil parsing (valid)
            $isi_sms = $parsed_sms['isi_sms'];
            $nama = $parsed_sms['nama']; //diisi hasil Parsing dari Raw Message
            $tipe_sms = $parsed_sms['tipe_sms'];
            $no_pendaftaran = $parsed_sms['no_pendaftaran'];

            //create reply SMS
            //        $reply_utf8 = mb_strtoupper($text_utf8); // mare reply message uppercased input message
            switch ($tipe_sms) {
                case $this->sms_masuk->key_tracking: //Jika tipenya tracking, create reply berisi status terakhir
                    $this->load->model('pelayanan/tmpermohonan');
                    $permohonan = new tmpermohonan();
                    $data_permohonan = $permohonan->get_by_pendaftaran_id($no_pendaftaran);
                    if ($data_permohonan->id) { //Jika ada nomor pendaftarannya, ambil status terakhir
                        $status_permohonan = $data_permohonan->trstspermohonan->get();
                        $reply_utf8 = 'Permohonan anda nomor ' . $no_pendaftaran . ' sedang dalam status ' . $status_permohonan->n_sts_permohonan;
                    } else {
                        $reply_utf8 = 'Maaf, no pendaftaran yang anda masukkan tidak ada';
                    }

                    $replied = 1;
                    break;
                case $this->sms_masuk->key_pertanyaan: //Jika tipenya Pertanyaan, create reply terima kasih
                    $reply_utf8 = 'Pesan anda telah diterima. Terima kasih telah menggunakan Aplikasi Perizinan Agam';
                    $replied = 1;
                    break;
            }
        } else { //Jika sms tidak valid
            $reply_utf8 = 'Mohon maaf, pesan yang anda kirim tidak dikenali. Mohon kirim sesuai format SMS Center.'; //SMS Reply jika sudah diterima oleh Gateway
            $tipe_sms = $this->sms_masuk->key_invalid;
            $replied = 1;
        }

        //write reply to HTTP header
        $reply_header = rawurlencode($reply_utf8);
        header('Content-Type: text/html; charset=utf-8');
        header("text: $reply_header"); //if you don't want reply sms, comment out this this line
        $reply_sent = true;
        if (!$reply_sent) {
            $replied = 0;
        }

        //Simpan Data SMS ke database
        $this->sms_masuk->no_hp = $phone;
        $this->sms_masuk->nama = $nama;
        $this->sms_masuk->raw_sms = $text_utf8;
        $this->sms_masuk->tipe_sms = $tipe_sms;
        $this->sms_masuk->isi_sms = $isi_sms;
        $this->sms_masuk->replied = $replied;
        $this->sms_masuk->reply_sms = $reply_utf8;
        $this->sms_masuk->save();

        // Debug outputs:
        echo "phone = $phone\n";
        echo "smscenter = $smscenter\n";
        echo "text_utf8 = $text_utf8\n";
        echo "reply_utf8 = $reply_utf8\n\n";

        $logFile = 'system' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'receivedsms' . date('Ymd') . '.txt'; //Path ke Log File
        file_put_contents($logFile, ob_get_contents(), FILE_APPEND);
    }

    /**
     * Fungsi untuk mengirim email ke inbox server sms
     * @author Indra
     */
    public function sendEmail_post()
    {
        ob_start();

        $subject = $this->post('subject');
        $message = $this->post('message');
        $to = $this->post('to');

        $this->load->model('notification_setting/setting_notifikasi');
        $this->setting_notifikasi = new setting_notifikasi();
        $success = $this->setting_notifikasi->send_email($message, $subject, $to, 'text');

        // Debug outputs:
        echo "\n";
        echo "to = $to\n";
        echo "subject = $subject\n";
        echo "message = $message\n";
        echo "\n";

        $logFile = 'system' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'forwarded_email_' . date('Ymd') . '.txt'; //Path ke Log File
        file_put_contents($logFile, ob_get_contents(), FILE_APPEND);
        ob_end_clean();

        $message = array(
            'success' => $success
        );
        $this->response($message, 200);
    }


    /**
     * Fungsi unuk mendapatkan daftar Unit Kerja yang boleh membuat suatu Izin berdasarkan Jenis izin yang dipilih
     */
    public function list_unit_get()
    {
        $return = array();
        if (!$this->get('trperizinan_id')) {
            $this->response(NULL, 400);
        }
        $trperizinanId = $this->get('trperizinan_id');
        $this->load->model('unitkerja/trunitkerja');
        $this->trunitkerja = new trunitkerja();
        if (!empty($trperizinanId) && !is_null($trperizinanId)) {
            $getUnitKerja = $this->trunitkerja->distinct(true)
                ->where_in_related('trperizinan', 'id', array($trperizinanId))
                //                ->where_in_related('trunitkerja_user/user','username',$this->session->userdata('username'))
                ->get();
        }
        if ($getUnitKerja->id) {
            foreach ($getUnitKerja as $key => $row) {
                $return[$key]['id'] = $row->id;
                $return[$key]['n_unitkerja'] = $row->n_unitkerja;
                $return[$key]['flag_institusi_daerah'] = $row->flag_institusi_daerah;
            }
        }
        $this->response($return, 200);
    }


    private function _remove_whitespace($string)
    {
        return preg_replace("/\s+/", "", $string);
    }
}

// This is the end of api class