<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of penjadwalan
 *
 *
 * @author Eva & Yogi Cahyana
 */
class SKRD extends WRC_AdminCont {

    private $_status_skrd = 10;

    public function __construct() {
        parent::__construct();
        $this->skrd = new tmpermohonan();
        $this->pemohon = new tmpemohon();
        $this->keringanan = new tmkeringananretribusi();
        $this->perizinan = new trperizinan();
        $this->bap = new tmbap();
        $this->retribusi = new trretribusi();
        $this->property = new trproperty();
//        $this->roi = new trretribusi_objek_ijin();
        $this->skrd = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->skrd = NULL;

        foreach ($list_auths as $list_auth) {
            if ($list_auth->id_role === '13') {
                $enabled = TRUE;
                $this->skrd = new user_auth();
            }
        }
		
        if (!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index($sALL=0) {
//        $daftar = new tmpermohonan();
//        $query = $daftar
//                        ->where('c_pendaftaran', 1) //Pendaftaran selesai
//                        ->where('c_izin_selesai', 0) //SK Belum diserahkan
//                        ->where('c_izin_dicabut', 0) //Permohonan tidak dicabut
//                        ->order_by('id', 'DESC')->get();
        $tgla = $this->input->post('tgla');
        $tglb = $this->input->post('tglb');
        $now = $this->lib_date->get_date_now();
        $tgl_before = $this->lib_date->set_date($now, -2);
        $tgl_now = $this->lib_date->set_date($now, 0);

        if ($tgla && $tglb) {
            $data['tgla'] = $tgla;
            $data['tglb'] = $tglb;
        } else {
            $tgla = $tgl_before;
            $tglb = $tgl_now;
            $data['tgla'] = $tgla;
            $data['tglb'] = $tglb;
        }
        $username = new user();
        $username->where('username', $this->session->userdata('username'))->get();
        
//		if($this->__is_administrator()){
			$query_filter_user="";
			$query_join_user="";
//		}else{
//			$query_join_user=" INNER JOIN trperizinan_user AS H ON  H.trperizinan_id = C.id ";
//			$query_filter_user=" AND H.user_id = '" . $username->id . "' ";
//		}

        $status_skrd = $this->_status_skrd;//Lihat di tabel trstspermohonan
        $current_unitkerja = $this->__get_current_unitkerja();

		if($sALL==1){
			$query = "SELECT A.id, A.pendaftaran_id, A.d_terima_berkas, A.d_survey,
		        A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
		        C.id idizin, C.n_perizinan, E.n_pemohon,
		        G.id idjenis, G.n_permohonan, A.c_izin_selesai
		        FROM tmpermohonan as A
		        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
		        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
		        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
		        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
		        INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
		        INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
		        ".$query_join_user."
				WHERE A.c_pendaftaran = 1
		        AND A.c_izin_dicabut = 0
		        /*AND C.c_tarif = 1*/
		        AND A.c_izin_selesai = 1".$query_filter_user."
		        AND A.d_terima_berkas between '$tgla' and '$tglb'
		        AND A.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})
                AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
		        AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                            INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                            WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = {$status_skrd})>0
		        order by A.id DESC";
        }else{
			$query = "SELECT A.id, A.pendaftaran_id, A.d_terima_berkas, A.d_survey,
		        A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
		        C.id idizin, C.n_perizinan, E.n_pemohon,
		        G.id idjenis, G.n_permohonan, A.c_izin_selesai
		        FROM tmpermohonan as A
		        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
		        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
		        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
		        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
		        INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
		        INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
		        ".$query_join_user."
				WHERE A.c_pendaftaran = 1
		        AND A.c_izin_dicabut = 0
		        /*AND C.c_tarif = 1*/
		        AND A.c_izin_selesai = 0".$query_filter_user."
		        AND A.d_terima_berkas between '$tgla' and '$tglb'
		        AND A.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})
                AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
		        AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                    INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                    WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = {$status_skrd})>0
		        order by A.id DESC";
		}

		$data['list'] = $query;
		$data['sALL']=$sALL;
        $data['c_bap'] = '1';
        $data['jenisizin'] = "";
        $data['nopendaftaran'] = "";
        $data['namapemohon'] = "";
        $data['tanggalpermohonan'] = "";
        $data['save_method'] = "save";
        $data['id'] = "";

        $this->load->vars($data);

        $js = "
                $(document).ready(function() {
                        oTable = $('#skrd').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                $(function() {
                    $(\".monbulan\").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'yy-mm-dd',
                        closeText: 'X'
                    });
                });
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Data Pembuatan SKRD";
        $this->template->build('skrd_list', $this->session_info);
    }

    public function view($idjenisizin=NULL, $nopendaftaran=NULL, $idpemohon=NULL) {
        $skrd = new tmpermohonan();
        $pemohon = new tmpemohon();

        $data['list'] = $skrd->where('pendaftaran_id', $this->input->post('nomorpendaftaran'))->get();

        $data['idjenisizin'] = $idjenisizin;
        $data['nopendaftaran'] = $nopendaftaran;
        $data['idpemohon'] = $idpemohon;

        $this->load->vars($data);

        $js = "
                $(document).ready(function() {
                        oTable = $('#skrd').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Data Pembuatan SKRD";
        $this->template->build('skrd', $this->session_info);
    }

    public function edit($id_pemohon = NULL) {
        $data['list'] = $this->sk->getPerizinan();
        $this->sk->where('id_pemohon', $id_pemohon);
        $this->sk->getPerizinan();

        $data['id'] = $this->sk->id_pemohon;
        $data['jenisizin'] = $this->sk->n_perizinan;
        $data['nopendaftaran'] = $this->sk->d_entry;
        $data['namapemohon'] = $this->sk->n_pemohon;
        $data['maksudpemohon'] = $this->perizinan->n_permohonan;
        $data['namapemilik'] = $this->perizinan->id_pemohon;
        $data['alamatpemilik'] = $this->perizinan->id_pemohon;
        $data['luastanah'] = $this->perizinan->id_pemohon;
        $data['lokasitanah'] = $this->perizinan->id_pemohon;
        $data['luasbangunan'] = $this->perizinan->id_pemohon;
        $data['fungsibangunan'] = $this->perizinan->id_pemohon;
        $data['strukturbangunan'] = $this->perizinan->id_pemohon;
        $data['save_method'] = "update";
        $data['view'] = "index";

        $this->load->vars($data);
        $this->session_info['page_name'] = "Entry Pendataan";
        $this->template->build('edit_sk', $this->session_info);
    }

    public function diskon($id=NULL, $izin=NULL) {

        $permohonan = new tmpermohonan();
        $daftar = $permohonan->where('id', $id)->get();
        $data_izin = $this->perizinan->where('id', $izin)->get();
//        $j_permohonan = new trjenis_permohonan();
        $relasi = new tmkeringananretribusi_tmpermohonan();
        $relasi->where('tmpermohonan_id', $id);
        $data['listpermohonan'] = $daftar;
        $data['listpemohon'] = $daftar->tmpemohon->get();
        $data['listjenis'] = $daftar->trjenis_permohonan->get();
        $data['listizin'] = $data_izin;
        $diskon = new tmkeringananretribusi();
        $diskon->where('id', $relasi->permohonan_id)->get();
        $data['listdist'] = $diskon->get();

        $data['id'] = "";
        $data['e_berdasarkan'] = "";
        $data['i_nomor_surat'] = "";
        $data['e_surat'] = "";
        $data['n_pemohon'] = "";
        $data['v_prosentase_retribusi'] = "";
        $js = "
           $(document).ready(function() {
            $('#form').validate();
                        oTable = $('#skrd').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                $(function() {
                    $(\".monbulan\").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'yy-mm-dd',
                        closeText: 'X'
                    });
                });
            ";
        $data['i_entry'] = "";
        $data['d_entry'] = "";
        $data['save_method'] = "save";

        $this->load->vars($data);
         $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Pengurangan/ Keringanan/ Pembebasan Retribusi";
        $this->template->build('diskon', $this->session_info);
    }

    public function editdiskon($id=NULL, $izin=NULL, $disk = null) {

        $permohonan = new tmpermohonan();
        $daftar = $permohonan->where('id', $id)->get();
        $data_izin = $this->perizinan->where('id', $izin)->get();
//        $j_permohonan = new trjenis_permohonan();
        $relasi = new tmkeringananretribusi_tmpermohonan();
        $relasi->where('tmpermohonan_id', $id);
        $data['listpermohonan'] = $daftar;
        $data['listpemohon'] = $daftar->tmpemohon->get();
        $data['listjenis'] = $daftar->trjenis_permohonan->get();
        $data['listizin'] = $data_izin;

        $diskon = new tmkeringananretribusi();
        $diskon->where('id', $disk)->get();
        $data['listdist'] = $diskon->where('id', $disk)->get();

        $data['e_berdasarkan'] = $diskon->e_berdasarkan;
        $data['i_nomor_surat'] = $diskon->i_nomor_surat;
        $data['e_surat'] = $diskon->e_surat;
        $data['n_pemohon'] = $diskon->n_pemohon;
        $data['v_prosentase_retribusi'] = $diskon->v_prosentase_retribusi;
        $js_date = "
        $(document).ready(function() {
                    $('#form').validate();
                    
                } );
        
            $(function() {
                $(\"#skrd\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
            ";
        $data['i_entry'] = $diskon->i_entry;
        $data['d_entry'] = $diskon->d_entry;
        $data['save_method'] = "update";

        $this->load->vars($data);
        $this->session_info['page_name'] = "Pengurangan/ Keringanan/ Pembebasan Retribusi";
        $this->template->build('diskon', $this->session_info);
    }

    public function update() {
        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($this->input->post('permohonan_id'));
        $update = $this->keringanan
                        ->where('id', $this->input->post('ids'))
                        ->update(array(
                            'e_berdasarkan' => $this->input->post('e_berdasarkan'),
                            'i_nomor_surat' => $this->input->post('i_nomor_surat'),
                            'e_surat' => $this->input->post('e_surat'),
                            'n_pemohon' => $this->input->post('n_pemohon'),
                            'v_prosentase_retribusi' => $this->input->post('v_prosentase_retribusi'),
                            'i_entry' => $this->input->post('i_entry'),
                            'd_entry' => $this->input->post('d_entry')));
                            
           //update nilai retribusinya
            $persentase = $this->input->post('v_prosentase_retribusi');
            $retribusi = $permohonan->tmbap->nilai_bap_awal;
             $keringanan = ($persentase * 0.01) * $retribusi;
            $hasil = $retribusi-$keringanan;
            
            $isi = array('nilai_retribusi' => $hasil);
            $this->bap->where('id', $permohonan->tmbap->id)
                      ->update($isi);

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql($u_ser);
//     $jam = date("H:i:s A");
        $p = $this->db->query("call log ('Permohonan','Update keringanan retribusi','" . $tgl . "','" . $u_ser . "')");


        if ($update) {
            redirect('permohonan/skrd');
        }
    }

    /*
     * Save and update for manipulating data.
     */

    public function save() {
        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($this->input->post('permohonan_id'));
        $this->keringanan->e_berdasarkan = $this->input->post('e_berdasarkan');
        $this->keringanan->i_nomor_surat = $this->input->post('i_nomor_surat');
        $this->keringanan->e_surat = $this->input->post('e_surat');
        $this->keringanan->n_pemohon = $this->input->post('n_pemohon');
        $this->keringanan->v_prosentase_retribusi = $this->input->post('v_prosentase_retribusi');
        $this->keringanan->i_entry = $this->input->post('i_entry');
        $this->keringanan->d_entry = $this->input->post('d_entry');
        if (!$this->keringanan->save($permohonan)) {
            echo '<p>' . $this->keringanan->error->string . '</p>';
        } else {
            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $g = $this->sql($u_ser);
     $jam = date("H:i:s A");

            $p = $this->db->query("call log ('Permohonan','Pembuatan keringanan retribusi " . $permohonan->pendaftaran_id . "','" . $tgl . "','" . $u_ser . "')");
           
            $persentase = $this->input->post('v_prosentase_retribusi');
            $retribusi = $permohonan->tmbap->nilai_bap_awal;
            $keringanan = ($persentase * 0.01) * $retribusi;
            $hasil = $retribusi-$keringanan;
            
            $isi = array('nilai_retribusi' => $hasil);
            $this->bap->where('id', $permohonan->tmbap->id)
                      ->update($isi);
           
           
            redirect('permohonan/skrd');
        }
    }

    /**
     * Not yet in use, because of some limit of dataTables
     */
    public function datalist() {

        $this->sk->get();
        $this->sk->set_json_content_type();
        echo $this->sk->json_for_data_table();
    }

    public function delete($id = NULL) {
        $this->perizinan->where('id_pemohon', $id)->get();
        if ($this->perizinan->delete()) {
            redirect('perizinan');
        }
    }

    public function cetakSKRDgeneric($id=NULL, $izin=NULL) {

        $this->settings = new settings();
        $this->settings->where('name', 'app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name', 'app_city')->get();

        $permohonan = new tmpermohonan();
        $retribusi = new trretribusi();

        $permohonan->get_by_id($id);

        $data1 = $permohonan->trperizinan->get();
        $data2 = $permohonan->tmpemohon->get();
        $data3 = $permohonan->tmbap->get();
        $data4 = $data1->$retribusi->get(); //where('perizinan_id',$izin)->get();
        $data5 = $permohonan->tmperusahaan->get();
        $data6 = $permohonan->trperizinan->trdasar_hukum->get();
        $data7 = $permohonan->trperizinan->trretribusi;
        $data8 = $permohonan->tmkeringananretribusi->get();

        $p_kelurahan = $data2->trkelurahan->get();
        $p_kecamatan = $data2->trkelurahan->trkecamatan->get();
        $p_kabupaten = $data2->trkelurahan->trkecamatan->trkabupaten->get();

        //Cek Tracking Progress
        $updated = FALSE;
        $daftar_awal = new tmpermohonan();
        $daftar_awal->get_by_id($id);
        $sts_awal = new trstspermohonan();
        $sts_awal->get_by_id('10'); //Menetapkan Retribusi dan Mencetak Izin [Lihat Tabel trstspermohonan()]
        $list_track = $daftar_awal->tmtrackingperizinan->get();
        if ($list_track) {
            foreach ($list_track as $data_track) {
                $data_status = new tmtrackingperizinan_trstspermohonan();
                $data_status->where('tmtrackingperizinan_id', $data_track->id)
                        ->where('trstspermohonan_id', $sts_awal->id)->get();
                if ($data_status->tmtrackingperizinan_id) {
                    $updated = TRUE;
                    break;
                }
            }
        } else {
            $updated = FALSE;
        }

        $status_izin = $permohonan->trstspermohonan->get();

        $status_skr = "8"; //Diizinkan [Lihat Tabel trstspermohonan()]
        $id_status = "10"; //SKRD [Lihat Tabel trstspermohonan()]
        if ($status_izin->id == $status_skr) {
            /* Input Data Tracking Progress */
            $sts_izin = new trstspermohonan();
            $sts_izin->get_by_id($status_skr);
            $data_status = new tmtrackingperizinan_trstspermohonan();
            $list_tracking = $permohonan->tmtrackingperizinan->get();
            if ($list_tracking) {
                $tracking_id = 0;
                foreach ($list_tracking as $data_track) {
                    $data_status = new tmtrackingperizinan_trstspermohonan();
                    $data_status->where('tmtrackingperizinan_id', $data_track->id)
                            ->where('trstspermohonan_id', $sts_izin->id)->get();
                    if ($data_status->tmtrackingperizinan_id) {
                        $tracking_id = $data_status->tmtrackingperizinan_id;
                    }
                }
            }
            $tracking_izin = new tmtrackingperizinan();
            $tracking_izin->get_by_id($tracking_id);
            //$tracking_izin->pendaftaran_id = $permohonan->pendaftaran_id;
            $tracking_izin->status = 'Update';
            $tracking_izin->d_entry = $this->lib_date->get_date_now();
            $tracking_izin->save();

            /* [Lihat Tabel trstspermohonan()] */
            $tracking_izin2 = new tmtrackingperizinan();
            $tracking_izin2->pendaftaran_id = $permohonan->pendaftaran_id;
            $tracking_izin2->status = 'Insert';
            $tracking_izin2->d_entry_awal = $this->lib_date->get_date_now();
            $tracking_izin2->d_entry = $this->lib_date->get_date_now();
            $sts_izin2 = new trstspermohonan();
            $sts_izin2->get_by_id($id_status); //[Lihat Tabel trstspermohonan()]
            $sts_izin2->save($permohonan);
            $tracking_izin2->save($permohonan);
            $tracking_izin2->save($sts_izin2);
        }

        //Status cetak SKRD
        $bap = new tmbap();
        $bap->get_by_id($data3->id);
        $bap->c_skrd = $data3->c_skrd + 1;
        $bap->save();

        //path of the template file
        $nama_surat = "cetak_skrd_generic";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/' . $nama_surat . '.odt');
//        $odf->setImage('header', 'assets/css/' . $app_folder . '/images/dinas_1.jpg', '17.5', '3.5');
        //logo
        $this->tr_instansi = new Tr_instansi();
        $logo = $this->tr_instansi->get_by_id(14);
         if($logo->value!=="")
            {
                $odf->setImage('logo', 'uploads/logo/' . $logo->value, '1.7', '1.7');
            }
        else
            {
                 $odf->setVars('logo', ' ');
            }

        //badan
        $this->tr_instansi = new Tr_instansi();
        $nama_bdan = $this->tr_instansi->get_by_id(9);
        $odf->setVars('badan', strtoupper($nama_bdan->value));

        //telpon
        $this->tr_instansi = new Tr_instansi();
        $tlp = $this->tr_instansi->get_by_id(10);
        $odf->setVars('tlp', $tlp->value);

        //fax
        $this->tr_instansi = new Tr_instansi();
        $tlp = $this->tr_instansi->get_by_id(13);
        $odf->setVars('fax', $tlp->value);


        //membuat kota
        $wilayah = new trkabupaten();
        if ($app_city->value !== '0') {
            $alamat = $data2->a_pemohon . ' ' . $p_kelurahan->n_kelurahan . ', ' .
                    $p_kecamatan->n_kecamatan . ', ' . ucwords(strtolower($p_kabupaten->n_kabupaten));
            $wilayah->get_by_id($app_city->value);
            $odf->setVars('kota', $wilayah->ibukota);
        } else {
            $alamat = $data2->a_pemohon;
            $odf->setVars('kota', '...........');
        }

        $gede_kota = strtoupper($wilayah->n_kabupaten);
        $kecil_kota = ucwords(strtolower($wilayah->n_kabupaten));
        $odf->setVars('kota4', $gede_kota);

        //alamat
        $this->tr_instansi = new Tr_instansi();
        $alamat = $this->tr_instansi->get_by_id(12);
        $odf->setVars('alamat', ucwords(strtolower($alamat->value)) . ' - ' . $kecil_kota);


        $petugas = 1;
        $pegawai = new tmpegawai();
        //$pegawai->where('status', $petugas)->get();
        $odf->setVars('jabatan', $pegawai->n_jabatan);
        $odf->setVars('nama_pejabat', $pegawai->n_pegawai);
        $odf->setVars('nip_pejabat', $pegawai->nip);
        $kantor = $this->settings->where('name', 'app_kantor')->get();
        $odf->setVars('kantor', $kantor->value);
        $odf->setVars('ttd', '');

        $odf->setVars('title', 'SURAT KETETAPAN RETRIBUSI DAERAH');
        $odf->setVars('IZIN', $data1->n_perizinan);
        $tgl_skr = $this->lib_date->get_date_now();
        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($tgl_skr));
        $odf->setVars('no_skrd', $data3->no_skrd);
       // $odf->setVars('nilairetribusi', 'Rp. ' . number_format($data7->v_retribusi, 2, ',', '.'));
       
        $odf->setVars('i_surat', $data8->i_nomor_surat);
        $odf->setVars('berdasarkan', $data8->e_berdasarkan);

        if ($data8->id)
        {
            $total = ($data8->v_prosentase_retribusi * 0.01) * $data3->nilai_bap_awal;
            $jumlah = $data3->nilai_bap_awal-$total;
             $odf->setVars('diskon', $data8->v_prosentase_retribusi);
        }
        else
        {
            $jumlah =  $data3->nilai_bap_awal;
            $total = $data3->nilai_bap_awal;
             $odf->setVars('diskon', '0');
        }
            
               
         $this->perizinan = new trperizinan();
        $perizinan = $this->perizinan->get_by_id($data1->id);
        $property = $perizinan->trretribusi->get();
        
        //if ($data8->id)
//        {
//            $hitung = ($data8->v_prosentase_retribusi * 0.01) * $property->v_retribusi;
//            $total = $property->v_retribusi-$hitung;
//        }
//          else
//          {
//              $total = $property->v_retribusi;    
//          }
        
        
        if ($property->m_perhitungan == "1") {
            $prop = '45';
            $prop_nilai = $this->getTinjauan($id, $izin, $prop);
            if (isset($prop_nilai->v_tinjauan)) {
                if (isset($data8->v_prosentase_retribusi))
                {
                $totalManual = ($data8->v_prosentase_retribusi * 0.01) * $prop_nilai->v_tinjauan;
                $hasil = $prop_nilai->v_tinjauan-$totalManual;
                $odf->setVars('totalretribusi', ' Rp.' . number_format($hasil, 2, ',', '.'));
                 $odf->setVars('bilangan', $this->terbilang->terbilang($hasil) . ' rupiah.');
                 //$odf->setVars('jumlahretribusi', $prop_nilai->v_tinjauan);//rido
                 $odf->setVars('jumlahretribusi', ' Rp.' . number_format($prop_nilai->v_tinjauan, 2, ',', '.') . ' = Rp. ' . number_format($totalManual, 2, ',', '.'));
                  $odf->setVars('jumlahretribusi1', ' Rp.' . number_format($prop_nilai->v_tinjauan, 2, ',', '.'));
                }
                else
                {
                    $odf->setVars('totalretribusi', 'Rp.' . $prop_nilai->v_tinjauan);
                     $odf->setVars('bilangan', $this->terbilang->terbilang($prop_nilai->v_tinjauan) . ' rupiah.');
                     $odf->setVars('jumlahretribusi', 'Rp. '. $this->terbilang->nominal($prop_nilai->v_tinjauan).' = Rp. 0,00' );
                     $odf->setVars('jumlahretribusi1', 'Rp. '. $this->terbilang->nominal($prop_nilai->v_tinjauan));
                }
            } else {
                $odf->setVars('totalretribusi', '.......' );
                 $odf->setVars('bilangan',' rupiah.');
                 $odf->setVars('jumlahretribusi', '........');
            }
        } else {
              $odf->setVars('jumlahretribusi1', 'Rp. ' . $this->terbilang->nominal($data3->nilai_bap_awal, 2));
            $odf->setVars('totalretribusi', ' Rp.' . number_format($jumlah, 2, ',', '.'));
             $odf->setVars('bilangan', $this->terbilang->terbilang($jumlah) . ' rupiah.');
             $odf->setVars('jumlahretribusi', 'Rp. ' . $this->terbilang->nominal($data3->nilai_bap_awal, 2). ' = Rp. ' .$this->terbilang->nominal($data3->nilai_bap_awal, 2)) ;
        }
        
        
        

        //dasar hukum

        $i = 1;
        $izin_hukum = new trdasar_hukum_trperizinan();
        $list_hukum = $izin_hukum->where('type', 1)
                        ->where('trperizinan_id', $data1->id)->order_by('trdasar_hukum_id', 'ASC')->get(); //17 2
        if ($list_hukum->id) {
            foreach ($list_hukum as $hukum) {
                $dasar_hukum = new trdasar_hukum();
                if ($hukum->id) {
                    $data6 = $dasar_hukum->where('id', $hukum->trdasar_hukum_id)->get();
                    $desk = $data6->deskripsi;
                } else {
                    $desk = ' ';
//                      $i=' ';
                }


                $listeArticles2 = array(
                    array('property' => $i . '.',
                        'content' => "gfghcvghvvgv",
                    ),
                );

                $article2 = $odf->setSegment('articles2');
                foreach ($listeArticles2 AS $element2) {
                    $article2->titreArticle2($element2['property']);
                    $article2->texteArticle2($element2['content']);
                    $article2->merge();
                }
                $i++;
            }
        } else {
            $desk = '';
            $listeArticles2 = array(
                array('property' => '',
                    'content' => $desk,
                ),
            );

            $article2 = $odf->setSegment('articles2');
            foreach ($listeArticles2 AS $element2) {
                $article2->titreArticle2($element2['property']);
                $article2->texteArticle2($element2['content']);
                $article2->merge();
            }
        }
        $odf->mergeSegment($article2);

        //skrd
        $listeArticles = array(
            array('property' => '1. Nomor Pendaftaran',
                'content' => $permohonan->pendaftaran_id,
            ),
            array('property' => '2. Nama Pemohon',
                'content' => $data2->n_pemohon,
            ),
            array('property' => '3. Alamat',
                'content' => $data2->a_pemohon,
            ),
        );

        $article = $odf->setSegment('articles');
        foreach ($listeArticles AS $element) {
            $article->titreArticle($element['property']);
            $article->texteArticle($element['content']);
            $article->merge();
        }

        $odf->mergeSegment($article);
//
//        $z = 0;
//        $list_property2 = $permohonan->trperizinan->trproperty->order_by('c_parent asc, c_order asc')->get();
//        foreach ($list_property2 as $data2) {
//
//
//            $koefprop = new trkoefesientarifretribusi_trproperty();
//            $koefprop->where('trproperty_id', $data2->id)->get();
//
//            $propp = new trproperty();
//            $propp->where('id', $koefprop->trproperty_id)->get();
//
//            if ($koefprop->trproperty_id) {
//                $z++;
//                $np2 = $propp->n_property;
//
//                if ($z == 1
//                    )$tanda = ' = ';
//                else
//                    $tanda = ' x ';
//
//
//
//                $listeArticles1 = array(
//                    array('property1' => $tanda . $np2,
//                        'content1' => '',
//                    ),
//                );
//                $article1 = $odf->setSegment('articles1');
//                foreach ($listeArticles1 AS $element1) {
//                    $article1->titreArticle($element1['property1']);
//                    $article1->texteArticle($element1['content1']);
//                    $article1->merge();
//                }
//            }
//        }
//        $odf->mergeSegment($article1);
        //dari SK properti dengan c_sk_id

        $i = 4;
        $list_content = $permohonan->tmproperty_jenisperizinan->get();
        $list_property = $permohonan->trperizinan->trproperty->where('c_type', 0)->get();

        $data_entry = '';
        $id_koefisien = '';
        if ($list_property->id) {
            foreach ($list_property as $data) {
                if ($list_content->id) {
                    foreach ($list_content as $data_daftar) {
                        $entry_property = new tmproperty_jenisperizinan_trproperty();
                        $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                                ->where('trproperty_id', $data->id)->get();
                        if ($entry_property->tmproperty_jenisperizinan_id) {
                            $entry_daftar = new tmproperty_jenisperizinan();
                            $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);

                            $data_entry = $entry_daftar->v_tinjauan;
                            $id_koefisien = $entry_daftar->k_tinjauan;
                        }
                    }
                } else {
                    $data_entry = '';
                    $id_koefisien = '';
                }
                $izin_property = new trperizinan_trproperty();
                $izin_property->where('trperizinan_id', $izin)
                        ->where('trproperty_id', $data->id)->get();
                $id_sk = $izin_property->c_skrd_id;

                if ($id_sk == '1') {
                    if ($id_koefisien) {
                        $data_koefisien = new trkoefesientarifretribusi();
                        $data_koefisien->get_by_id($id_koefisien);
                        $data_property = $i . '. ' . $data->n_property;
                        $i++;
                        if ($data_entry) {
                            $all_entry = $data_koefisien->kategori . ' ' . $data_entry . '';
                            $titik = ':';
                        } else {
                            $all_entry = $data_koefisien->kategori;
                            $titik = ':';
                        }
                        $listeArticles3 = array(
                            array('property3' => $data_property,
                                'content3' => $all_entry,
                                'content33' => $titik,
                            ),
                        );
                    } else {
                        $listeArticles3 = array(
                            array('property3' => '2',
                                'content3' => '2',
                                'content33' => '2',
                            ),
                        );
                    }
                    $article3 = $odf->setSegment('articles3');
                    foreach ($listeArticles3 AS $element3) {
                        if ($element3['property3'] == '2' && $element3['content3'] == "2" && $element3['content33'] == '2') {
                            
                        } else {
                            $article3->titreArticle3($element3['property3']);
                            $article3->texteArticle3($element3['content3']);
                            $article3->texteArticle4($element3['content33']);
                            $article3->merge();
                        }
                    }
                }
            }
        } else {
            $listeArticles3 = array(
                array('property3' => '',
                    'content3' => '',
                    'content33' => '',
                ),
            );
            $article3 = $odf->setSegment('articles3');
            foreach ($listeArticles3 AS $element3) {
                $article3->titreArticle3($element3['property3']);
                $article3->texteArticle3($element3['content3']);
                $article3->texteArticle4($element3['content33']);
                $article3->merge();
            }
        }
        $odf->mergeSegment($article3);

        /////////////////////////
        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat . '_' . $no_daftar . '.odt');
    }

	/*
	* @ModifiedAuthor Indra Halim
	* Last Modified 14th June 2013
	*/	
    public function cetakSKRD($id=NULL, $izin=NULL) {

        $this->settings = new settings();
        $this->settings->where('name', 'app_folder')->get();
        $app_folder = $this->settings->value . "/";
//        $app_city = $this->settings->where('name', 'app_city')->get();
        $app_city = $this->settings->where('name', 'app_city')->get();

        $permohonan = new tmpermohonan();
        $retribusi = new trretribusi();

        $permohonan->get_by_id($id);

        $data1 = $permohonan->trperizinan->get();
        $data2 = $permohonan->tmpemohon->get();
        $data3 = $permohonan->tmbap->get();
        $data4 = $data1->$retribusi->get(); //where('perizinan_id',$izin)->get();
        $data5 = $permohonan->tmperusahaan->get();
        $data6 = $permohonan->trperizinan->trdasar_hukum->get();
        $data7 = $permohonan->trperizinan->trretribusi;
        $data8 = $permohonan->tmkeringananretribusi->get();

        $p_kelurahan = $data2->trkelurahan->get();
        $p_kecamatan = $data2->trkelurahan->trkecamatan->get();
        $p_kabupaten = $data2->trkelurahan->trkecamatan->trkabupaten->get();

        //Cek Tracking Progress
        $updated = FALSE;
        $daftar_awal = new tmpermohonan();
        $daftar_awal->get_by_id($id);
        $sts_awal = new trstspermohonan();
        $sts_awal->get_by_id('10'); //Menetapkan Retribusi dan Mencetak Izin [Lihat Tabel trstspermohonan()]
        $list_track = $daftar_awal->tmtrackingperizinan->get();
        if ($list_track) {
            foreach ($list_track as $data_track) {
                $data_status = new tmtrackingperizinan_trstspermohonan();
                $data_status->where('tmtrackingperizinan_id', $data_track->id)
                        ->where('trstspermohonan_id', $sts_awal->id)->get();
                if ($data_status->tmtrackingperizinan_id) {
                    $updated = TRUE;
                    break;
                }
            }
        } else {
            $updated = FALSE;
        }

        $status_izin = $permohonan->trstspermohonan->get();
		
		/**Edited By Indra**/
        //$status_skr = "8"; //Diizinkan [Lihat Tabel trstspermohonan()]
        //$id_status = "10"; //SKRD [Lihat Tabel trstspermohonan()]
        $status_skr = $this->_status_skrd; //Menetapkan Retribusi dan Mencetak SKRD [Lihat Tabel trstspermohonan()]
        //$id_status = 13; //Kasir [Lihat Tabel trstspermohonan()]

        ### Mencari langkah berikutnya ###
        $this->load->model('permohonan/trlangkah_perizinan');
        $langkah_perizinan = new trlangkah_perizinan();
        $id_status = $langkah_perizinan->nextStep($permohonan->trperizinan->trkelompok_perizinan->id, $status_izin->id);
        ##################################

        /*******************/
		if ($status_izin->id == $status_skr) {
            // Input Data Tracking Progress
            /*$sts_izin = new trstspermohonan();
            $sts_izin->get_by_id($status_skr);
            $data_status = new tmtrackingperizinan_trstspermohonan();
            $list_tracking = $permohonan->tmtrackingperizinan->get();
            if ($list_tracking) {
                $tracking_id = 0;
                foreach ($list_tracking as $data_track) {
                    $data_status = new tmtrackingperizinan_trstspermohonan();
                    $data_status->where('tmtrackingperizinan_id', $data_track->id)
                            ->where('trstspermohonan_id', $sts_izin->id)->get();
                    if ($data_status->tmtrackingperizinan_id) {
                        $tracking_id = $data_status->tmtrackingperizinan_id;
                    }
                }
            }
            $tracking_izin = new tmtrackingperizinan();
            $tracking_izin->get_by_id($tracking_id);
            //$tracking_izin->pendaftaran_id = $permohonan->pendaftaran_id;
            $tracking_izin->status = 'Update';
            $tracking_izin->d_entry = $this->lib_date->get_date_now();
            $tracking_izin->save();

            // [Lihat Tabel trstspermohonan()]
            $tracking_izin2 = new tmtrackingperizinan();
            $tracking_izin2->pendaftaran_id = $permohonan->pendaftaran_id;
            $tracking_izin2->status = 'Insert';
            $tracking_izin2->d_entry_awal = $this->lib_date->get_date_now();
            $tracking_izin2->d_entry = $this->lib_date->get_date_now();
            $sts_izin2 = new trstspermohonan();
            $sts_izin2->get_by_id($id_status); //[Lihat Tabel trstspermohonan()]
            $sts_izin2->save($permohonan);
            $tracking_izin2->save($permohonan);
            $tracking_izin2->save($sts_izin2);*/

            $this->__input_tracking_progress($id, $status_skr, $id_status);
        }

		//Status cetak SKRD
        //$bap = new tmbap();
        //$bap->get_by_id($data3->id);
        //$bap->c_skrd = $data3->c_skrd + 1;
        //$bap->save();

        $all_bap = new tmbap();
        $all_bap->where_related('tmpermohonan','id',$id)->select('id')->get();
        $all_bap->update_all('c_skrd',$all_bap->c_skrd+1);

		
		#####Diganti dengan jasper report######
        /*
        //path of the template file
        $nama_surat = "cetak_skrd";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/' . $nama_surat . '.odt');
//        $odf->setImage('header', 'assets/css/' . $app_folder . '/images/dinas_1.jpg', '17.5', '3.5');
        $odf->setVars('ttd', '');

        //logo
        $this->tr_instansi = new Tr_instansi();
        $logo = $this->tr_instansi->get_by_id(14);
         if($logo->value!=="")
            {
                $odf->setImage('logo', 'uploads/logo/' . $logo->value, '1.7', '1.7');
            }
        else
            {
                 $odf->setVars('logo', ' ');
            }

        //badan
        $this->tr_instansi = new Tr_instansi();
        $nama_bdan = $this->tr_instansi->get_by_id(9);
        $odf->setVars('badan', strtoupper($nama_bdan->value));

        //telpon
        $this->tr_instansi = new Tr_instansi();
        $tlp = $this->tr_instansi->get_by_id(10);
        $odf->setVars('tlp', $tlp->value);

        //fax
        $this->tr_instansi = new Tr_instansi();
        $tlp = $this->tr_instansi->get_by_id(13);
        $odf->setVars('fax', $tlp->value);

        $this->tr_instansi = new Tr_instansi();
        $alamat = $this->tr_instansi->get_by_id(12);

        //membuat kota
        $wilayah = new trkabupaten();
        if (isset($app_city->value)) {
            $wilayah->get_by_id($app_city->value);
            $gede_kota = strtoupper($wilayah->n_kabupaten);
            $kecil_kota = ucwords(strtolower($wilayah->n_kabupaten));
            $odf->setVars('kota4', $gede_kota);
            $odf->setVars('kota', $wilayah->ibukota);
            $odf->setVars('alamat', ucwords(strtolower($alamat->value)) . ' - ' . $kecil_kota);

        } else {
            $odf->setVars('alamat','...........');
            $odf->setVars('kota', '............');
            $odf->setVars('kota4', '...........');
        }

     
        $petugas = 1;
        $pegawai = new tmpegawai();
        $pegawai->where('status', $petugas)->get();
        $odf->setVars('jabatan', $pegawai->n_jabatan);
        $odf->setVars('nama_pejabat', $pegawai->n_pegawai);
        $odf->setVars('nip_pejabat', $pegawai->nip);
        $odf->setVars('n_pemohon', $data2->n_pemohon);

		//ini yang digunakan.. founded by Shinta
        $odf->setVars('title', 'SURAT KETETAPAN RETRIBUSI DAERAH');
        $odf->setVars('IZIN', $data1->n_perizinan);
        $tgl_skr = $this->lib_date->get_date_now();
        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($tgl_skr));
        $odf->setVars('no_skrd', $data3->no_skrd);
        $odf->setVars('i_surat', $data8->i_nomor_surat);
        $odf->setVars('berdasarkan', $data8->e_berdasarkan);


//mengambil dari tmbap..????? ---------
        if ($data8->id)
        {
            $hitung = ($data8->v_prosentase_retribusi * 0.01) * $data3->nilai_bap_awal;
            $total = $data3->nilai_bap_awal-$hitung;
            $hitung2 = $hitung;
             $odf->setVars('diskon', $data8->v_prosentase_retribusi);
        }
        else
        {
             $odf->setVars('diskon', '0');
            $hitung2 = '0';
            $total = $data3->nilai_bap_awal;    
        }
          
//--------------------------- 

       

        $this->perizinan = new trperizinan();
        $perizinan = $this->perizinan->get_by_id($data1->id);
        $property = $perizinan->trretribusi->get();

        
        if ($property->m_perhitungan == "1") {
            $prop = '45';
            $prop_nilai = $this->getTinjauan($id, $izin, $prop);
            if (isset($prop_nilai->v_tinjauan)) {
                if (isset($data8->v_prosentase_retribusi))
                {
                $totalManual = ($data8->v_prosentase_retribusi * 0.01) * $prop_nilai->v_tinjauan;
                $hasil = $prop_nilai->v_tinjauan - $totalManual;
                $odf->setVars('totalretribusi', 'Rp. ' . number_format($hasil, 2, ',', '.'));
                 $odf->setVars('bilangan', $this->terbilang->terbilang($hasil) . ' rupiah.');
                }
                else
                {
				//ini yang dipanggil
                    $odf->setVars('totalretribusi', 'Rp. ' . $prop_nilai->v_tinjauan);
                     $odf->setVars('bilangan', $this->terbilang->terbilang($prop_nilai->v_tinjauan) . ' rupiah.');
                }
            } else {
                $odf->setVars('totalretribusi', '.......' );
                 $odf->setVars('bilangan',' rupiah.');
            }
        } else {
            $odf->setVars('totalretribusi', 'Rp. ' . number_format($total, 2, ',', '.'));
             $odf->setVars('bilangan', $this->terbilang->terbilang($total) . ' rupiah.');
        }
        //dasar hukum

        $i = 1;
//        $izin_hukum = new trdasar_hukum_trperizinan();
//        $list_hukum = $izin_hukum->where('type', 0)
//                        ->where('trperizinan_id', $data1->id)->order_by('trdasar_hukum_id', 'ASC')->get(); //17 2
        $list_hukum = $this->getDatahukum($data1->id);


        if ($list_hukum) {
            foreach ($list_hukum as $hukum) {
                $dasar_hukum = new trdasar_hukum();
                if ($hukum->id) {
                    $data6 = $dasar_hukum->where('id', $hukum->trdasar_hukum_id)->get();
                    $desk = $data6->deskripsi;
                } else {
                    $desk = ' ';
//                      $i=' ';
                }


                $listeArticles2 = array(
                    array('property' => $i . '.',
                        'content' => $desk,
                    ),
                );

                $article2 = $odf->setSegment('articles2');
                foreach ($listeArticles2 AS $element2) {
                    $article2->titreArticle2($element2['property']);
                    $article2->texteArticle2($element2['content']);
                    $article2->merge();
                }
                $i++;
            }
        } else {
            $desk = '';
            $listeArticles2 = array(
                array('property' => '',
                    'content' => $desk,
                ),
            );

            $article2 = $odf->setSegment('articles2');
            foreach ($listeArticles2 AS $element2) {
                $article2->titreArticle2($element2['property']);
                $article2->texteArticle2($element2['content']);
                $article2->merge();
            }
        }
        $odf->mergeSegment($article2);

        //skrd
        $listeArticles = array(
            array('property' => 'Nomor Pendaftaran',
                'content' => $permohonan->pendaftaran_id,
            ),
            array('property' => 'Nama Pemohon',
                'content' => $data2->n_pemohon,
            ),
            array('property' => 'Alamat',
                'content' => $data2->a_pemohon,
            ),
        );

        $article = $odf->setSegment('articles');
        foreach ($listeArticles AS $element) {
            $article->titreArticle($element['property']);
            $article->texteArticle($element['content']);
            $article->merge();
        }

        $odf->mergeSegment($article);

        $z = 0;
        $list_property2 = $permohonan->trperizinan->trproperty->order_by('c_parent asc, c_order asc')->get();
        foreach ($list_property2 as $data2) {
            
            $izin_property = new trperizinan_trproperty();
            $izin_property->where('trperizinan_id', $data1->id)
                    ->where('trproperty_id', $data2->id)->get();
            $id_retribusi = $izin_property->c_retribusi_id;
            
            //echo 'ID Retribusi :'.$id_retribusi.'<br>';
            //if ($id_retribusi == '1') {
            if(true){

                $koefprop = new trkoefesientarifretribusi_trproperty();
                $koefprop->where('trproperty_id', $data2->id)->get();

                $propp = new trproperty();
                $propp->where('id', $koefprop->trproperty_id)->get();

                if ($koefprop->trproperty_id) {
                   //error di mulai dari sini
                    $z++;
                    $np2 = $propp->n_property;

                    if ($z == 1)
                        $tanda = ' = ';
                    else
                        $tanda = ' x ';

//jika hitung manual

                    $this->perizinan = new trperizinan();
                    $perizinan = $this->perizinan->get_by_id($data1->id);
                    $property = $perizinan->trretribusi->get();
                    //edited 11-04-2013
                    //by mucktar
                    if ($property->m_perhitungan == "1") {
                        $prop = '45';
                        $prop_nilai = $this->getTinjauan($id, $izin, $prop);
                        if (isset($prop_nilai->v_tinjauan)) {
						//ini untuk rumusnya
                            //$odf->setVars('hitungmanual', 'Rp. ' . $prop_nilai->v_tinjauan . '= Rp. '. number_format($hitung2, 2, ',', '.'));
                            $odf->setVars('hitungmanual', 'Rp. ' . $prop_nilai->v_tinjauan );
                            $odf->setVars('jumlahretribusi1', $prop_nilai->v_tinjauan);
                        } else {
                            $odf->setVars('hitungmanual', '.........' . '= Rp. .......');
                            $odf->setVars('jumlahretribusi1','');
                        }

                        $rums = '414';
                        $rums_nilai = $this->getTinjauan($id, $izin, $rums);
                        if (isset($rums_nilai->v_tinjauan)) {
                            $odf->setVars('rumus', $rums_nilai->v_tinjauan." = ");
                        } else {
                            $odf->setVars('rumus', '.......');
                        }

                        $odf->setVars('nilairetribusi', '');
                        $odf->setVars('jumlahretribusi', '');
                        

                        $listeArticles1 = array(
                            array('property1' => '',
                                'content1' => '',
                            ),
                        );
                    } else {
                        $odf->setVars('rumus', '');
                        $odf->setVars('hitungmanual', ' ' );
                        $odf->setVars('nilairetribusi', ' x Rp.' . number_format($data7->v_retribusi, 2, ',', '.'));
                        $odf->setVars('jumlahretribusi', ' Rp' . $this->terbilang->nominal($data3->nilai_bap_awal). ' = '. number_format($hitung2, 2, ',', '.'));
                        $odf->setVars('jumlahretribusi1', ' Rp' . $this->terbilang->nominal($data3->nilai_bap_awal));
                        
                        $listeArticles1 = array(
                            array('property1' => $tanda . $np2,
                                'content1' => '',
                            ),
                        );
                    }
                    $article1 = $odf->setSegment('articles1');
                    foreach ($listeArticles1 AS $element1) {
                        $article1->titreArticle($element1['property1']);
                        $article1->texteArticle($element1['content1']);
                        $article1->merge();
                    }
                    
                    $odf->mergeSegment($article1);
                }
            }
        }
        
        //$odf->mergeSegment($article1);
        //end edit


        //dari SK properti dengan c_sk_id

        $i = 4;
        $list_content = $permohonan->tmproperty_jenisperizinan->get();
        $list_property = $permohonan->trperizinan->trproperty->where('c_type', 0)->get();

        foreach ($list_property as $data) {
            $property_satuan = new trperizinan_trproperty();
            $property_satuan->where('trproperty_id', $data->id)->get();
            if ($list_content->id) {
                foreach ($list_content as $data_daftar) {
                    $entry_property = new tmproperty_jenisperizinan_trproperty();
                    $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                            ->where('trproperty_id', $data->id)->get();
                    if ($entry_property->tmproperty_jenisperizinan_id) {
                        $entry_daftar = new tmproperty_jenisperizinan();
                        $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);

                        $data_entry = $entry_daftar->v_tinjauan;
                        $id_koefisien = $entry_daftar->k_tinjauan;
                    }
                }
            } else {
                $data_entry = '';
                $id_koefisien = '';
            }
            $izin_property = new trperizinan_trproperty();
            $izin_property->where('trperizinan_id', $izin)
                    ->where('trproperty_id', $data->id)->get();
            $id_sk = $izin_property->c_skrd_id;

            if ($data_entry) {
                if ($id_sk == '1') {
                    $data_koefisien = new trkoefesientarifretribusi();
                    $data_koefisien->get_by_id($id_koefisien);
                    $data_property = '' . $data->n_property;
                    $i++;
                    if ($data_entry) {
                        $all_entry = $data_koefisien->kategori . ' ' . $data_entry . " " . $property_satuan->satuan;
                        $titik = ':';
                    } else {
                        $all_entry = $data_koefisien->kategori;
                        $titik = ':';
                    }
                    $listeArticles3 = array(
                        array('property3' => $data_property,
                            'content3' => $all_entry,
                            'content33' => $titik,
                        ),
                    );
                    $article3 = $odf->setSegment('articles3');
                    foreach ($listeArticles3 AS $element3) {
                        $article3->titreArticle3($element3['property3']);
                        $article3->texteArticle3($element3['content3']);
                        $article3->texteArticle4($element3['content33']);
                        $article3->merge();
                    }
                }
            }
            else
            {
                //$listeArticles3 = array(
//                        array('property3' => "",
//                            'content3' => "",
//                            'content33' => "",
//                        ),
//                    );
$listeArticles3=array();
                    $article3 = $odf->setSegment('articles3');
                    
                    foreach ($listeArticles3 AS $element3) {
                        $article3->titreArticle3($element3['property3']);
                        $article3->texteArticle3($element3['content3']);
                        $article3->texteArticle4($element3['content33']);
                        $article3->merge();
                    }
            }
        }
        $odf->mergeSegment($article3);

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql2($u_ser);
//      $jam = date("H:i:s A");
        $p = $this->db->query("call log ('Pembuatan SKRD','Cetak SKRD " . $permohonan->pendaftaran_id . "','" . $tgl . "','" . $u_ser . "')");


        /////////////////////////
        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat . '_' . $no_daftar . '.odt');
		*/
		redirect('report_generator/cetak/SKRD/'.$id.'/'.$izin);
    }

    public function cetakSKRD2($id=NULL, $idjenis=NULL) {

        $this->settings = new settings();
        $this->settings->where('name', 'app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name', 'app_city')->get();


        $permohonan = new tmpermohonan();
        $perizinan = new trperizinan();
        $property = new trproperty();
        $koefesien = new trkoefesientarifretribusi();
        $jenisproperty = new tmproperty_jenisperizinan();


        $permohonan->where('id', $id)->get();
        $permohonan->trperizinan->where('id', $idjenis)->get();

        $status_izin = $permohonan->trstspermohonan->get();

        $status_skr = "8"; //Diizinkan [Lihat Tabel trstspermohonan()]
        $id_status = "10"; //SKRD [Lihat Tabel trstspermohonan()]
        if ($status_izin->id == $status_skr) {
            /* Input Data Tracking Progress */
            $sts_izin = new trstspermohonan();
            $sts_izin->get_by_id($status_skr);
            $data_status = new tmtrackingperizinan_trstspermohonan();
            $list_tracking = $permohonan->tmtrackingperizinan->get();
            if ($list_tracking) {
                $tracking_id = 0;
                foreach ($list_tracking as $data_track) {
                    $data_status = new tmtrackingperizinan_trstspermohonan();
                    $data_status->where('tmtrackingperizinan_id', $data_track->id)
                            ->where('trstspermohonan_id', $sts_izin->id)->get();
                    if ($data_status->tmtrackingperizinan_id) {
                        $tracking_id = $data_status->tmtrackingperizinan_id;
                    }
                }
            }
            $tracking_izin = new tmtrackingperizinan();
            $tracking_izin->get_by_id($tracking_id);
            //$tracking_izin->pendaftaran_id = $permohonan->pendaftaran_id;
            $tracking_izin->status = 'Update';
            $tracking_izin->d_entry = $this->lib_date->get_date_now();
            $tracking_izin->save();

            /* [Lihat Tabel trstspermohonan()] */
            $tracking_izin2 = new tmtrackingperizinan();
            $tracking_izin2->pendaftaran_id = $permohonan->pendaftaran_id;
            $tracking_izin2->status = 'Insert';
            $tracking_izin2->d_entry_awal = $this->lib_date->get_date_now();
            $tracking_izin2->d_entry = $this->lib_date->get_date_now();
            $sts_izin2 = new trstspermohonan();
            $sts_izin2->get_by_id($id_status); //[Lihat Tabel trstspermohonan()]
            $sts_izin2->save($permohonan);
            $tracking_izin2->save($permohonan);
            $tracking_izin2->save($sts_izin2);
        }

        $perusahaan = $permohonan->tmperusahaan->get();
        $bap = $permohonan->tmbap->get();
        $pemohon = $permohonan->tmpemohon->get();

        $p_kelurahan = $pemohon->trkelurahan->get();
        $p_kecamatan = $pemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $pemohon->trkelurahan->trkecamatan->trkabupaten->get();

        $daftar = $permohonan->pendaftaran_id;


        $listform = $permohonan->trperizinan->trproperty->order_by('c_parent asc, c_order asc')->get();
        $list_daftar = $permohonan->tmproperty_jenisperizinan->get();
        $permohonan->$perizinan->$property->$jenisproperty->where('pendaftaran_id', $daftar)->get();
        $k_property = $permohonan->trperizinan->$property->$jenisproperty->k_property;
        $permohonan->trperizinan->$property->$koefesien->where('id', $k_property)->get();

        $retribusi = $permohonan->trperizinan->trretribusi->get();

		######Diganti dengan Jasper######
		/*
        //path of the template file
        $nama_surat = "cetak_skrd2";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/' . $nama_surat . '.odt');

        //logo
        $this->tr_instansi = new Tr_instansi();
        $logo = $this->tr_instansi->get_by_id(14);
          if($logo->value!=="")
            {
                $odf->setImage('logo', 'uploads/logo/' . $logo->value, '1.7', '1.7');
            }
        else
            {
                 $odf->setVars('logo', ' ');
            }

        //badan
        $this->tr_instansi = new Tr_instansi();
        $nama_bdan = $this->tr_instansi->get_by_id(9);
        $odf->setVars('badan', strtoupper($nama_bdan->value));

        //telpon
        $this->tr_instansi = new Tr_instansi();
        $tlp = $this->tr_instansi->get_by_id(10);
        $odf->setVars('tlp', $tlp->value);

        //fax
        $this->tr_instansi = new Tr_instansi();
        $tlp = $this->tr_instansi->get_by_id(13);
        $odf->setVars('fax', $tlp->value);

        $petugas = 1;
        $pegawai = new tmpegawai();
        $pegawai->where('status', $petugas)->get();
        $odf->setVars('jabatan', $pegawai->n_jabatan);
        $odf->setVars('nama', $pegawai->n_pegawai);
        $odf->setVars('nip', $pegawai->nip);
        
        $odf->setVars('no_skrd', $bap->no_skrd);
        $data8 = $permohonan->tmkeringananretribusi->get();
        
        if ($data8->id)
        {
            $hitung = ($data8->v_prosentase_retribusi * 0.01) * $bap->nilai_bap_awal;
            $total = $bap->nilai_bap_awal-$hitung;
        }
        else
        {
            $total = $bap->nilai_bap_awal;    
        }

        $this->perizinan = new trperizinan();
        $perizinan = $this->perizinan->get_by_id($idjenis);
        $property = $perizinan->trretribusi->get();
        if ($property->m_perhitungan == "1") {
            $prop = '45';
            $prop_nilai = $this->getTinjauan($id, $idjenis, $prop);
            if (isset($prop_nilai->v_tinjauan)) {
                if ($data8->id)
                {
                    $hitung = ($data8->v_prosentase_retribusi * 0.01) * $prop_nilai->v_tinjauan;
                    $totalM = $prop_nilai->v_tinjauan-$hitung;
                    $odf->setVars('keringanan', $data8->v_prosentase_retribusi .'% x Rp.' .$this->terbilang->nominal($prop_nilai->v_tinjauan). '= Rp. ' . $this->terbilang->nominal($hitung));
                }
                else
                {
                    $odf->setVars('keringanan', '.........');
                    $totalM = $prop_nilai->v_tinjauan;    
                }
                
                $odf->setVars('jumlahretribusi', 'Rp.' . $totalM);
                $odf->setVars('bilangan', $this->terbilang->terbilang($totalM) . ' rupiah.');
            } else {
                $odf->setVars('keringanan', '......');
                $odf->setVars('jumlahretribusi', '.......');
                $odf->setVars('bilangan', ' rupiah.');
            }
        } else 
        {
                if ($data8->id)
                {
                   $odf->setVars('keringanan', $data8->v_prosentase_retribusi .'% x Rp.' .$this->terbilang->nominal($bap->nilai_bap_awal). '= Rp. ' . $this->terbilang->nominal($hitung));
                }
                else
                {
                    $odf->setVars('keringanan', '.........');    
                }
            $odf->setVars('jumlahretribusi', 'Rp' . $this->terbilang->nominal($total, 2));
            $odf->setVars('bilangan', $this->terbilang->terbilang($total) . ' rupiah.');
        }

        $this->tr_instansi = new Tr_instansi();
        $alamat = $this->tr_instansi->get_by_id(12);
        $wilayah = new trkabupaten();
        if ($app_city->value !== '0') {
            $wilayah->get_by_id($app_city->value);
            $odf->setVars('kota', $wilayah->ibukota);
            $gede_kota = strtoupper($wilayah->n_kabupaten);
            $kecil_kota = ucwords(strtolower($wilayah->n_kabupaten));
            $odf->setVars('kota4', $gede_kota);
            if (isset($alamat->value))
            {
            $odf->setVars('alamat', ucwords(strtolower($alamat->value)) . ' - ' . $kecil_kota);
            }
            else
            {
                $odf->setVars('alamat','---------------');
                
            }
        } else {
            $alamat = $pemohon->a_pemohon;
            // $odf->setVars('kabupaten', 'setempat');
            $odf->setVars('kota', '...........');
            $odf->setVars('kota4', '...........');
            $odf->setVars('alamat','---------------');
        }
        //alamat
       
        

        $kantor = $this->settings->where('name', 'app_kantor')->get();
        $odf->setVars('kantor', $kantor->value);
        $odf->setVars('TITLE', 'PERHITUNGAN RETRIBUSI ' . strtoupper($permohonan->$perizinan->n_perizinan));

        $tgl_skr = $this->lib_date->get_date_now();
        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($tgl_skr));
        $z = 0;
        //break
        $i = 1;
        $no = 1;
        $list_property = $permohonan->trperizinan->trproperty->where('c_type', 1)
                        ->order_by('c_parent asc,c_order asc')->get();
        $list_content = $permohonan->tmproperty_jenisperizinan->get();
        $list_koefisien = $list_property->trkoefesientarifretribusi->get();
        $data_entry = '';
        $id_koefisien = '';
        $data_entryt = 1;
        $data_koefisient = '';
        $kategori = '';
        if ($list_property->id) {
            foreach ($list_property as $data) {
                if ($list_content->id) {
                    foreach ($list_content as $data_daftar) {
                        $entry_property = new tmproperty_jenisperizinan_trproperty();
                        $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                                ->where('trproperty_id', $data->id)->get();
                        if ($entry_property->tmproperty_jenisperizinan_id) {
                            $entry_daftar = new tmproperty_jenisperizinan();
                            $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);

                            $data_entry = $entry_daftar->v_property;
                            $id_koefisien = $entry_daftar->k_property;
                            $data_entryt = $entry_daftar->v_tinjauan;
                            $data_koefisient = $entry_daftar->k_tinjauan;
                        }
                    }
                } else {
                    $data_entry = '';
                    $id_koefisien = '';
                    $data_entryt = 1;
                    $data_koefisient = '';
                }
                if ($id_koefisien) {
                    $data_koefisien = new trkoefesientarifretribusi();
                    $data_koefisien->get_by_id($id_koefisien);
                    $kategori = $data_koefisien->kategori;
                } else {
                    $kategori = '';
                }
                $koefprop = new trkoefesientarifretribusi_trproperty();
                $koefprop->where('trproperty_id', $data->id)->get();

                $propp = new trproperty();
                $propp->where('id', $koefprop->trproperty_id)->get();
                if ($koefprop->trproperty_id) {
                    $titik = ":";
                    $np2 = $propp->n_property;

                    $listeArticles = array(
                        array('property' => 'Nama Pemilik',
                            'content' => $pemohon->n_pemohon,
                        ),
                        array('property' => 'Letak Usaha',
                            'content' => $pemohon->a_pemohon,
                        ),
                    );
                    $article = $odf->setSegment('articles');
                    foreach ($listeArticles AS $element) {
                        $article->titreArticle($element['property']);
                        $article->texteArticle($element['content']);
                        $article->merge();
                    }
                    $odf->mergeSegment($article);

                    $listeArticles3 = array(
                        array(
                            'content3' => $np2,
                            'content33' => $kategori,
                            'property' => $titik,
                            'property2' => $no . '.',
                        ),
                    );

                    $article3 = $odf->setSegment('articles3');
                    foreach ($listeArticles3 AS $element3) {
                        $article3->texteArticle3($element3['content3']);
                        $article3->texteArticle4($element3['content33']);
                        $article3->titreArticle3($element3['property']);
                        $article3->titreArticle4($element3['property2']);
                        $article3->merge();
                    }
                    $no++;
                } else {
                    $listeArticles = array(
                        array('property' => 'Nama Pemilik',
                            'content' => $pemohon->n_pemohon,
                        ),
                        array('property' => 'Letak Usaha',
                            'content' => $pemohon->a_pemohon,
                        ),
                    );
                    $article = $odf->setSegment('articles');
                    foreach ($listeArticles AS $element) {
                        $article->titreArticle($element['property']);
                        $article->texteArticle($element['content']);
                        $article->merge();
                    }
                    $odf->mergeSegment($article);


                    /////
                    $listeArticles3 = array(
                        array(
                            'content3' => ' ',
                            'content33' => ' ',
                            'property' => ' ',
                        ),
                    );

                    $article3 = $odf->setSegment('articles3');
                    foreach ($listeArticles3 AS $element3) {
                        $article3->texteArticle3($element3['content3']);
                        $article3->texteArticle4($element3['content33']);
                        $article3->titreArticle3($element3['property']);
                        $article3->merge();
                    }
                }
            }
        } else {
            $listeArticles3 = array(
                array(
                    'content3' => '',
                    'content33' => '',
                    'property' => '',
                ),
            );

            $article3 = $odf->setSegment('articles3');
            foreach ($listeArticles3 AS $element3) {
                $article3->texteArticle3($element3['content3']);
                $article3->texteArticle4($element3['content33']);
                $article3->titreArticle3($element3['property']);
                $article3->merge();
            }
        }
        $odf->mergeSegment($article3);
        //break
        $z = 1;

        foreach ($list_property as $data2) {

            $izin_property = new trperizinan_trproperty();
            $izin_property->where('trperizinan_id', $idjenis)
                    ->where('trproperty_id', $data2->id)->get();
            $id_retribusi = $izin_property->c_retribusi_id;
            if ($id_retribusi == '1') {

                $koefprop = new trkoefesientarifretribusi_trproperty();
                $koefprop->where('trproperty_id', $data2->id)->get();
                $y = 1;
                foreach ($koefprop as $data3) {
                    if ($y == 1
                    )
                        $np = $data2->n_property;
                    else
                        $np = "";
                    $y++;
                    $koeftarif = new trkoefesientarifretribusi();
                    $idprop = $koeftarif->get_by_id($data3->trkoefesientarifretribusi_id);
                    $koefisien = $idprop->kategori;
                    $indeks = $idprop->index_kategori;
                    $harga = $idprop->harga;

                    $listeArticles5 = array(
                        array('property5' => $np,
                            'content55' => $koefisien,
                            'content555' => $indeks,
                            'content5555' => $harga,
                        ),
                    );

                    $article5 = $odf->setSegment('articles5');
                    foreach ($listeArticles5 AS $element5) {
                        $article5->titreArticle5($element5['property5']);
                        $article5->texteArticle6($element5['content55']);
                        $article5->texteArticle7($element5['content555']);
                        $article5->texteArticle8($element5['content5555']);
                        $article5->merge();
                    }
                }
                $z++;
            }
        }
        if ($z == '1') {
            $listeArticles5 = array(
                array('property5' => '',
                    'content55' => '',
                    'content555' => '',
                ),
            );

            $article5 = $odf->setSegment('articles5');
            foreach ($listeArticles5 AS $element5) {
                $article5->titreArticle5($element5['property5']);
                $article5->texteArticle6($element5['content55']);
                $article5->texteArticle7($element5['content555']);
                $article5->merge();
            }
        }

        $odf->mergeSegment($article5);

        $z = 0;
        foreach ($list_property as $data2) {
            $izin_property = new trperizinan_trproperty();
            $izin_property->where('trperizinan_id', $idjenis)
                    ->where('trproperty_id', $data2->id)->get();
            $id_retribusi = $izin_property->c_retribusi_id;
            if ($id_retribusi == '1') {

                $koefprop = new trkoefesientarifretribusi_trproperty();
                $koefprop->where('trproperty_id', $data2->id)->get();

                $propp = new trproperty();
                $propp->where('id', $koefprop->trproperty_id)->get();

                if ($koefprop->trproperty_id) {
                    $z++;
                    $np2 = $propp->n_property;

                    if ($z == 1
                    )
                        $tanda = ' = ';
                    else
                        $tanda = ' x ';


                    $this->perizinan = new trperizinan();
                    $perizinan = $this->perizinan->get_by_id($idjenis);
                    $property = $perizinan->trretribusi->get();
                    if ($property->m_perhitungan == "1") {

                        $rums = '414';
                        $rums_nilai = $this->getTinjauan($id, $idjenis, $rums);
                        if (isset($rums_nilai->v_tinjauan)) {
                            $odf->setVars('rumus', $rums_nilai->v_tinjauan);
                        } else {
                             $odf->setVars('rumus','......');
                        }

                        $odf->setVars('nilairetribusi', '');
                        $listeArticles1 = array(
                            array('property1' => '',
                                'content1' => '',
                            ),
                        );
                    } else {
                        $odf->setVars('nilairetribusi', ' x Rp.' . number_format($retribusi->v_retribusi, 2, ',', '.'));
                        $odf->setVars('rumus', '');
                        $listeArticles1 = array(
                            array('property1' => $tanda . $np2,
                                'content1' => '',
                            ),
                        );
                    }

                    $article1 = $odf->setSegment('articles1');
                    foreach ($listeArticles1 AS $element1) {
                        $article1->titreArticle($element1['property1']);
                        $article1->texteArticle($element1['content1']);
                        $article1->merge();
                    }
                }
            }
        }
        if ($z == '0') {
            $listeArticles1 = array(
                array('property1' => '',
                    'content1' => '',
                ),
            );
            $article1 = $odf->setSegment('articles1');
            foreach ($listeArticles1 AS $element1) {
                $article1->titreArticle($element1['property1']);
                $article1->texteArticle($element1['content1']);
                $article1->merge();
            }
        }
        $odf->mergeSegment($article1);

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql2($u_ser);
//      $jam = date("H:i:s A");
        $p = $this->db->query("call log ('Pembuatan SKRD','Cetak Lamp SKRD " . $permohonan->pendaftaran_id . "','" . $tgl . "','" . $u_ser . "')");


        //break
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat . '_' . $no_daftar . '.odt');
		*/
		redirect('report_generator/cetak/SKRD2/'.$id.'/'.$idjenis);
    }

    public function cetakSKRDimb($id=NULL, $izin=NULL) {

        $this->settings = new settings();
        $this->settings->where('name', 'app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name', 'app_city')->get();


        $permohonan = new tmpermohonan();
        $retribusi = new trretribusi();

        $permohonan->get_by_id($id);

        $data1 = $permohonan->trperizinan->get();
        $data2 = $permohonan->tmpemohon->get();
        $data3 = $permohonan->tmbap->get();
        $data4 = $data1->$retribusi->get(); //where('perizinan_id',$izin)->get();
        $data5 = $permohonan->tmperusahaan->get();
        $data6 = $permohonan->trperizinan->trdasar_hukum->get();
        $data7 = $permohonan->trperizinan->trretribusi;
        $data8 = $permohonan->tmkeringananretribusi->get();

        $p_kelurahan = $data2->trkelurahan->get();
        $p_kecamatan = $data2->trkelurahan->trkecamatan->get();
        $p_kabupaten = $data2->trkelurahan->trkecamatan->trkabupaten->get();

        //Cek Tracking Progress
        $updated = FALSE;
        $daftar_awal = new tmpermohonan();
        $daftar_awal->get_by_id($id);
        $sts_awal = new trstspermohonan();
        $sts_awal->get_by_id('10'); //Menetapkan Retribusi dan Mencetak Izin [Lihat Tabel trstspermohonan()]
        $list_track = $daftar_awal->tmtrackingperizinan->get();
        if ($list_track) {
            foreach ($list_track as $data_track) {
                $data_status = new tmtrackingperizinan_trstspermohonan();
                $data_status->where('tmtrackingperizinan_id', $data_track->id)
                        ->where('trstspermohonan_id', $sts_awal->id)->get();
                if ($data_status->tmtrackingperizinan_id) {
                    $updated = TRUE;
                    break;
                }
            }
        } else {
            $updated = FALSE;
        }

        $status_izin = $permohonan->trstspermohonan->get();

        $status_skr = "8"; //Diizinkan [Lihat Tabel trstspermohonan()]
        $id_status = "10"; //SKRD [Lihat Tabel trstspermohonan()]
        if ($status_izin->id == $status_skr) {
            /* Input Data Tracking Progress */
            $sts_izin = new trstspermohonan();
            $sts_izin->get_by_id($status_skr);
            $data_status = new tmtrackingperizinan_trstspermohonan();
            $list_tracking = $permohonan->tmtrackingperizinan->get();
            if ($list_tracking) {
                $tracking_id = 0;
                foreach ($list_tracking as $data_track) {
                    $data_status = new tmtrackingperizinan_trstspermohonan();
                    $data_status->where('tmtrackingperizinan_id', $data_track->id)
                            ->where('trstspermohonan_id', $sts_izin->id)->get();
                    if ($data_status->tmtrackingperizinan_id) {
                        $tracking_id = $data_status->tmtrackingperizinan_id;
                    }
                }
            }
            $tracking_izin = new tmtrackingperizinan();
            $tracking_izin->get_by_id($tracking_id);
            //$tracking_izin->pendaftaran_id = $permohonan->pendaftaran_id;
            $tracking_izin->status = 'Update';
            $tracking_izin->d_entry = $this->lib_date->get_date_now();
            $tracking_izin->save();

            /* [Lihat Tabel trstspermohonan()] */
            $tracking_izin2 = new tmtrackingperizinan();
            $tracking_izin2->pendaftaran_id = $permohonan->pendaftaran_id;
            $tracking_izin2->status = 'Insert';
            $tracking_izin2->d_entry_awal = $this->lib_date->get_date_now();
            $tracking_izin2->d_entry = $this->lib_date->get_date_now();
            $sts_izin2 = new trstspermohonan();
            $sts_izin2->get_by_id($id_status); //[Lihat Tabel trstspermohonan()]
            $sts_izin2->save($permohonan);
            $tracking_izin2->save($permohonan);
            $tracking_izin2->save($sts_izin2);
        }

        //Status cetak SKRD
        $bap = new tmbap();
        $bap->get_by_id($data3->id);
        $bap->c_skrd = $data3->c_skrd + 1;
        $bap->save();

        //path of the template file

        $this->perizinan = new trperizinan();
        $perizinan = $this->perizinan->get_by_id($data1->id);
        $property = $perizinan->trretribusi->get();
        if ($property->m_perhitungan == "1") {
            $prop_nilai = $this->property->get_by_id('45');
            $prop_jenis = $prop_nilai->tmproperty_jenisperizinan->get();
            $nama_surat = "cetak_skrd_imbManual";
        } else {
            $nama_surat = "cetak_skrd_imb";
        }



        $this->load->plugin('odf');
        $odf = new odf('assets/odt/' . $nama_surat . '.odt');
//        $odf->setImage('header', 'assets/css/' . $app_folder . '/images/dinas_1.jpg', '17.5', '3.5');
        $odf->setVars('ttd', '');

        //logo
        $this->tr_instansi = new Tr_instansi();
        $logo = $this->tr_instansi->get_by_id(14);
           if($logo->value!=="")
            {
                $odf->setImage('logo', 'uploads/logo/' . $logo->value, '1.7', '1.7');
            }
        else
            {
                 $odf->setVars('logo', ' ');
            }

        //badan
        $this->tr_instansi = new Tr_instansi();
        $nama_bdan = $this->tr_instansi->get_by_id(9);
        $odf->setVars('badan', strtoupper($nama_bdan->value));

        //telpon
        $this->tr_instansi = new Tr_instansi();
        $tlp = $this->tr_instansi->get_by_id(10);
        $odf->setVars('tlp', $tlp->value);

        //fax
        $this->tr_instansi = new Tr_instansi();
        $tlp = $this->tr_instansi->get_by_id(13);
        $odf->setVars('fax', $tlp->value);

        //membuat kota
        $wilayah = new trkabupaten();
        if ($app_city->value !== '0') {
            $alamat = $data2->a_pemohon . ' ' . $p_kelurahan->n_kelurahan . ', ' .
                    $p_kecamatan->n_kecamatan . ', ' . ucwords(strtolower($p_kabupaten->n_kabupaten));
            $wilayah->get_by_id($app_city->value);
            $odf->setVars('kota', $wilayah->ibukota);
        } else {
            $alamat = $data2->a_pemohon;
            $odf->setVars('kota', '...........');
        }

        $gede_kota = strtoupper($wilayah->n_kabupaten);
        $kecil_kota = ucwords(strtolower($wilayah->n_kabupaten));
        $odf->setVars('kota4', $gede_kota);

        //alamat
        $this->tr_instansi = new Tr_instansi();
        $alamat = $this->tr_instansi->get_by_id(12);
        $odf->setVars('alamat', ucwords(strtolower($alamat->value)) . ' - ' . $kecil_kota);


        $petugas = 1;
        $pegawai = new tmpegawai();
        $pegawai->where('status', $petugas)->get();
        $odf->setVars('jabatan', $pegawai->n_jabatan);
        $odf->setVars('nama_pejabat', $pegawai->n_pegawai);
        $odf->setVars('nip_pejabat', $pegawai->nip);

        $kantor = $this->settings->where('name', 'app_kantor')->get();
        $odf->setVars('kantor', $kantor->value);
        $odf->setVars('title', 'SURAT KETETAPAN RETRIBUSI DAERAH');
        $odf->setVars('IZIN', $data1->n_perizinan);
        $tgl_skr = $this->lib_date->get_date_now();
        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($tgl_skr, 1));
        $odf->setVars('no_skrd', $data3->no_skrd);
//        $odf->setVars('nilairetribusi',' x Rp.'. number_format($data7->v_retribusi,2,',','.'));
        $retribusi_gedung = new tmretribusi_rinci_imb();
        $retribusi_gedung->where_related($permohonan)
                ->where('c_imb', 1)->get();
        if($retribusi_gedung->v_retribusi==0)
        {
            $odf->setVars('bangunan_gedung','');
        }
        else
        {
            $odf->setVars('bangunan_gedung', $this->terbilang->nominal($retribusi_gedung->v_retribusi));
        }
        
        $retribusi_prasarana = new tmretribusi_rinci_imb();
        $retribusi_prasarana->select_sum('v_retribusi')
                ->where_related($permohonan)
                ->where('c_imb', 2)->get();
        if($retribusi_prasarana->v_retribusi==0)
        {
            $odf->setVars('prasarana','');
        }
        else
        {
            $odf->setVars('prasarana', $this->terbilang->nominal($retribusi_prasarana->v_retribusi));
        }
        
        $jumlah_retribusi = $retribusi_gedung->v_retribusi + $retribusi_prasarana->v_retribusi;
//        $jumlah_retribusi = $data3->nilai_retribusi;



     
        $odf->setVars('i_surat', $data8->i_nomor_surat);
        $odf->setVars('berdasarkan', $data8->e_berdasarkan);

       
        //dasar hukum

        $i = 1;
        $izin_hukum = new trdasar_hukum_trperizinan();
        $list_hukum = $izin_hukum->where('type', 1)
                        ->where('trperizinan_id', $data1->id)->order_by('trdasar_hukum_id', 'ASC')->get(); //17 2
        if ($list_hukum->id) {
            foreach ($list_hukum as $hukum) {
                $dasar_hukum = new trdasar_hukum();
                if ($hukum->id) {
                    $data6 = $dasar_hukum->where('id', $hukum->trdasar_hukum_id)->get();
                    $desk = $data6->deskripsi;
                } else {
                    $desk = ' ';
//                      $i=' ';
                }


                $listeArticles2 = array(
                    array('property' => $i . '.',
                        'content' => $desk,
                    ),
                );

                $article2 = $odf->setSegment('articles2');
                foreach ($listeArticles2 AS $element2) {
                    $article2->titreArticle2($element2['property']);
                    $article2->texteArticle2($element2['content']);
                    $article2->merge();
                }
                $i++;
            }
        } else {
            $desk = '';
            $listeArticles2 = array(
                array('property' => '',
                    'content' => $desk,
                ),
            );

            $article2 = $odf->setSegment('articles2');
            foreach ($listeArticles2 AS $element2) {
                $article2->titreArticle2($element2['property']);
                $article2->texteArticle2($element2['content']);
                $article2->merge();
            }
        }
        $odf->mergeSegment($article2);

        //skrd
        $listeArticles = array(
            array('property' => 'Nomor Pendaftaran',
                'content' => $permohonan->pendaftaran_id,
            ),
            array('property' => 'Nama Pemohon',
                'content' => $data2->n_pemohon,
            ),
            array('property' => 'Alamat',
                'content' => $data2->a_pemohon,
            ),
        );

        $article = $odf->setSegment('articles');
        foreach ($listeArticles AS $element) {
            $article->titreArticle($element['property']);
            $article->texteArticle($element['content']);
            $article->merge();
        }

        $odf->mergeSegment($article);

        $z = 0;
        $list_property2 = $permohonan->trperizinan->trproperty->order_by('c_parent asc, c_order asc')->get();
        foreach ($list_property2 as $data2) {


            $koefprop = new trkoefesientarifretribusi_trproperty();
            $koefprop->where('trproperty_id', $data2->id)->get();

            $propp = new trproperty();
            $propp->where('id', $koefprop->trproperty_id)->get();
        }

        $i = 4;
        $list_content = $permohonan->tmproperty_jenisperizinan->get();
//        $list_property = $permohonan->trperizinan->trproperty->where('c_type', 0)->get();
        $list_property = $permohonan->trperizinan->trproperty->get();


        foreach ($list_property as $data) {
            $property_satuan = new trperizinan_trproperty();
            $property_satuan->where('trproperty_id', $data->id)->get();
            if ($list_content->id) {
                foreach ($list_content as $data_daftar) {
                    $entry_property = new tmproperty_jenisperizinan_trproperty();
                    $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                            ->where('trproperty_id', $data->id)->get();
                    if ($entry_property->tmproperty_jenisperizinan_id) {
                        $entry_daftar = new tmproperty_jenisperizinan();
                        $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);

                        $data_entry = $entry_daftar->v_tinjauan;
                        $id_koefisien = $entry_daftar->k_tinjauan;
                    }
                }
            } else {
                $data_entry = '';
                $id_koefisien = '';
            }
            
            // if ($data8->id)
//            $total = ($data8->v_prosentase_retribusi * 0.01) * $data3->nilai_retribusi;
//             else
//            $total = $data3->nilai_retribusi;
         $manual = $permohonan->trperizinan->trretribusi->v_retribusi;   
         if ($data8->id)
         {
            $hitung = ($data8->v_prosentase_retribusi * 0.01) * $manual;
            $total = $manual-$hitung;
            $odf->setVars('diskon', $data8->v_prosentase_retribusi);
         }else {
            $odf->setVars('diskon', '0');
            $total = $manual;
         }
            
            if ($property->m_perhitungan == "1") {
                $prop = '45';
                $prop_nilai = $this->getTinjauan($id, $izin, $prop);
                if (isset($prop_nilai->v_tinjauan)) {
                    if (isset($data8->v_prosentase_retribusi))
                    {
                    $totalManual1 = ($data8->v_prosentase_retribusi * 0.01) * $prop_nilai->v_tinjauan;
                    $totalManual = $prop_nilai->v_tinjauan-$totalManual1;
                    $odf->setVars('totalretribusi', ' Rp.' . number_format($totalManual, 2, ',', '.'));
                    $odf->setVars('bilangan', $this->terbilang->terbilang($totalManual) . ' rupiah.');
                    $odf->setVars('jumlahretribusi', 'Rp.' . number_format($prop_nilai->v_tinjauan, 2, ',', '.') . ' =Rp. '. number_format($totalManual1, 2, ',', '.'));
                    $odf->setVars('jumlahretribusi1', 'Rp.' . number_format($prop_nilai->v_tinjauan, 2, ',', '.'));
                    }
                    else
                    {
                    $odf->setVars('totalretribusi', 'Rp.' . $prop_nilai->v_tinjauan);
                    $odf->setVars('bilangan', $this->terbilang->terbilang($prop_nilai->v_tinjauan) . ' rupiah.');  
                    $odf->setVars('jumlahretribusi', 'Rp.' . number_format($prop_nilai->v_tinjauan, 2, ',', '.').' = 0,00');
                    $odf->setVars('jumlahretribusi1', 'Rp.' . number_format($prop_nilai->v_tinjauan, 2, ',', '.'));
                    }
                } else {
                    $odf->setVars('bilangan',  '----');    
                    $odf->setVars('jumlahretribusi', '---------');
                    $odf->setVars('jumlahretribusi1', '---------');
                    $odf->setVars('totalretribusi', '----');
                }
                $rums = '414';
                $rums_nilai = $this->getTinjauan($id, $izin, $rums);
                if (isset($rums_nilai->v_tinjauan)) {
                    $odf->setVars('hitungmanual', $rums_nilai->v_tinjauan);
                } else {
                    $odf->setVars('hitungmanual', '------');
                }
            } else {
                $odf->setVars('totalretribusi', ' Rp.' . number_format($total, 2, ',', '.'));
                $odf->setVars('jumlahretribusi', 'Rp' . $this->terbilang->nominal($manual, 2)) . ' ';
                $odf->setVars('jumlahretribusi1', 'Rp' . $this->terbilang->nominal($manual, 2)) . ' ';
            }




            $izin_property = new trperizinan_trproperty();
            $izin_property->where('trperizinan_id', $izin)
                    ->where('trproperty_id', $data->id)->get();
            $id_sk = $izin_property->c_skrd_id;

            if ($data_entry) {
                if ($id_sk == '1') {
                    $data_koefisien = new trkoefesientarifretribusi();
                    $data_koefisien->get_by_id($id_koefisien);
                    $data_property = '' . $data->n_property;
                    $i++;
                    if ($data_entry) {
                        $all_entry = $data_koefisien->kategori . ' ' . $data_entry . " " . $property_satuan->satuan;
                        $titik = ':';
                    } else {
                        $all_entry = $data_koefisien->kategori;
                        $titik = ':';
                    }
                    $listeArticles3 = array(
                        array('property3' => $data_property,
                            'content3' => $all_entry,
                            'content33' => $titik,
                        ),
                    );
                    $article3 = $odf->setSegment('articles3');
                    foreach ($listeArticles3 AS $element3) {
                        $article3->titreArticle3($element3['property3']);
                        $article3->texteArticle3($element3['content3']);
                        $article3->texteArticle4($element3['content33']);
                        $article3->merge();
                    }
                }
            }
            else
            {
                 $listeArticles3 = array();
                    $article3 = $odf->setSegment('articles3');
                    foreach ($listeArticles3 AS $element3) {
                        $article3->titreArticle3($element3['property3']);
                        $article3->texteArticle3($element3['content3']);
                        $article3->texteArticle4($element3['content33']);
                        $article3->merge();
                    }
            }
        }
        $odf->mergeSegment($article3);

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql2($u_ser);
//      $jam = date("H:i:s A");
        $p = $this->db->query("call log ('Pembuatan SKRD','Cetak SKRD " . $permohonan->pendaftaran_id . "','" . $tgl . "','" . $u_ser . "')");


        /////////////////////////
        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat . '_' . $no_daftar . '.odt');
    }

    public function cetakLampImb($id=NULL, $idjenis=NULL) {

        $this->settings = new settings();
        $this->settings->where('name', 'app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name', 'app_city')->get();

        $permohonan = new tmpermohonan();
        $permohonan->where('id', $id)->get();
        $perizinan = $permohonan->trperizinan->where('id', $idjenis)->get();

        $status_izin = $permohonan->trstspermohonan->get();

        $status_skr = "8"; //Diizinkan [Lihat Tabel trstspermohonan()]
        $id_status = "10"; //SKRD [Lihat Tabel trstspermohonan()]
        if ($status_izin->id == $status_skr) {
            /* Input Data Tracking Progress */
            $sts_izin = new trstspermohonan();
            $sts_izin->get_by_id($status_skr);
            $data_status = new tmtrackingperizinan_trstspermohonan();
            $list_tracking = $permohonan->tmtrackingperizinan->get();
            if ($list_tracking) {
                $tracking_id = 0;
                foreach ($list_tracking as $data_track) {
                    $data_status = new tmtrackingperizinan_trstspermohonan();
                    $data_status->where('tmtrackingperizinan_id', $data_track->id)
                            ->where('trstspermohonan_id', $sts_izin->id)->get();
                    if ($data_status->tmtrackingperizinan_id) {
                        $tracking_id = $data_status->tmtrackingperizinan_id;
                    }
                }
            }
            $tracking_izin = new tmtrackingperizinan();
            $tracking_izin->get_by_id($tracking_id);
            //$tracking_izin->pendaftaran_id = $permohonan->pendaftaran_id;
            $tracking_izin->status = 'Update';
            $tracking_izin->d_entry = $this->lib_date->get_date_now();
            $tracking_izin->save();

            /* [Lihat Tabel trstspermohonan()] */
            $tracking_izin2 = new tmtrackingperizinan();
            $tracking_izin2->pendaftaran_id = $permohonan->pendaftaran_id;
            $tracking_izin2->status = 'Insert';
            $tracking_izin2->d_entry_awal = $this->lib_date->get_date_now();
            $tracking_izin2->d_entry = $this->lib_date->get_date_now();
            $sts_izin2 = new trstspermohonan();
            $sts_izin2->get_by_id($id_status); //[Lihat Tabel trstspermohonan()]
            $sts_izin2->save($permohonan);
            $tracking_izin2->save($permohonan);
            $tracking_izin2->save($sts_izin2);
        }

        $perusahaan = $permohonan->tmperusahaan->get();
        $bap = $permohonan->tmbap->get();
        $pemohon = $permohonan->tmpemohon->get();

        $p_kelurahan = $pemohon->trkelurahan->get();
        $p_kecamatan = $pemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $pemohon->trkelurahan->trkecamatan->trkabupaten->get();

        $daftar = $permohonan->pendaftaran_id;

        //path of the template file
        $nama_surat = "cetak_lamp_skrd_imb";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/' . $nama_surat . '.odt');
//        $odf->setImage('header', 'assets/css/' . $app_folder . '/images/dinas_1.jpg', '17.5', '3.5');
        //logo
        $this->tr_instansi = new Tr_instansi();
        $logo = $this->tr_instansi->get_by_id(14);
           if($logo->value!=="")
            {
                $odf->setImage('logo', 'uploads/logo/' . $logo->value, '1.7', '1.7');
            }
        else
            {
                 $odf->setVars('logo', ' ');
            }

        //badan
        $this->tr_instansi = new Tr_instansi();
        $nama_bdan = $this->tr_instansi->get_by_id(9);
        $odf->setVars('badan', strtoupper($nama_bdan->value));

        //telpon
        $this->tr_instansi = new Tr_instansi();
        $tlp = $this->tr_instansi->get_by_id(10);
        $odf->setVars('tlp', $tlp->value);

        //fax
        $this->tr_instansi = new Tr_instansi();
        $tlp = $this->tr_instansi->get_by_id(13);
        $odf->setVars('fax', $tlp->value);

        $petugas = 1;
        $pegawai = new tmpegawai();
        $pegawai->where('status', $petugas)->get();
        $odf->setVars('jabatan', $pegawai->n_jabatan);
        $odf->setVars('nama', $pegawai->n_pegawai);
        $odf->setVars('nip', $pegawai->nip);
        $odf->setVars('no_skrd', $bap->no_skrd);

        $wilayah = new trkabupaten();
        if ($app_city->value !== '0') {
            $alamat = $pemohon->a_pemohon . ' ' . $p_kelurahan->n_kelurahan . ', ' .
                    $p_kecamatan->n_kecamatan . ', ' . ucwords(strtolower($p_kabupaten->n_kabupaten));
            $wilayah->get_by_id($app_city->value);
            $odf->setVars('kota', $wilayah->ibukota);
        } else {
            $alamat = $pemohon->a_pemohon;
            $odf->setVars('kota', '...........');
        }
        $gede_kota = strtoupper($wilayah->n_kabupaten);
        $kecil_kota = ucwords(strtolower($wilayah->n_kabupaten));
        $odf->setVars('kota4', $gede_kota);

        //alamat
        $this->tr_instansi = new Tr_instansi();
        $alamat = $this->tr_instansi->get_by_id(12);
        $odf->setVars('alamat', ucwords(strtolower($alamat->value)) . ' - ' . $kecil_kota);



        $tgl_skr = $this->lib_date->get_date_now();
        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($tgl_skr));
        $kantor = $this->settings->where('name', 'app_kantor')->get();
        $odf->setVars('kantor', $kantor->value);
        //Content Property
        $list_property = $perizinan->trproperty->order_by('id asc')->get();
        $list_content = $permohonan->tmproperty_jenisperizinan->get();
        foreach ($list_property as $data) {
            if ($list_content->id) {
                foreach ($list_content as $data_daftar) {
                    $entry_property = new tmproperty_jenisperizinan_trproperty();
                    $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                            ->where('trproperty_id', $data->id)->get();
                    if ($entry_property->tmproperty_jenisperizinan_id) {
                        $entry_daftar = new tmproperty_jenisperizinan();
                        $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);

                        $data_entry = $entry_daftar->v_tinjauan;
                        $id_koefisien = $entry_daftar->k_tinjauan;

                        $izin_property = new trperizinan_trproperty();
                        $izin_property->where('trperizinan_id', $idjenis)
                                ->where('trproperty_id', $data->id)->get();
                        if ($data->id == '4' || $data->id == '9' || $data->id == '11' || $data->id == '33') {
                            if ($data->c_type == '1') {
                                $data_koefisien = new trkoefesientarifretribusi();
                                $data_koefisien->get_by_id($id_koefisien);
                                $data_property = $data->n_property;
                                if ($data_entry)
                                    $all_entry = $data_koefisien->kategori . ' (' . $data_entry . ')';
                                else
                                    $all_entry = $data_koefisien->kategori;
                                $titik = ":";
                            }else if ($data->c_type == '2') {
                                $data_property = $data->n_property;
                                $titik = "";
                                $all_entry = "";
                            } else {
                                $data_property = $data->n_property;
                                $titik = ":";
                                $all_entry = $data_entry;
                            }
                            $listeArticles = array(
                                array('property' => $data_property,
                                    'titik' => $titik,
                                    'content' => $all_entry,
                                ),
                            );
                            $article = $odf->setSegment('articles1');
                            foreach ($listeArticles AS $element) {
                                $article->titreArticle($element['property']);
                                $article->texteArticle2($element['titik']);
                                $article->texteArticle($element['content']);
                                $article->merge();
                            }
                        }
                    }
                }
            }
        }
        $odf->mergeSegment($article);

        //Daftar Indeks
        $it = NULL;
        $list_property = $perizinan->trproperty->order_by('c_parent asc, c_order asc')->get();
        $list_content = $permohonan->tmproperty_jenisperizinan->get();
        $xx = 0;
        $nilai_klas = 0;
        $nilai_waktu = 0;
        $nilai_it = 0;
        foreach ($list_property as $data) {
            if ($list_content->id) {
                foreach ($list_content as $data_daftar) {
                    $entry_property = new tmproperty_jenisperizinan_trproperty();
                    $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                            ->where('trproperty_id', $data->id)->get();
                    if ($entry_property->tmproperty_jenisperizinan_id) {
                        $entry_daftar = new tmproperty_jenisperizinan();
                        $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);

                        $data_entry = $entry_daftar->v_tinjauan;
                        $id_koefisien = $entry_daftar->k_tinjauan;

                        $izin_property = new trperizinan_trproperty();
                        $izin_property->where('trperizinan_id', $idjenis)
                                ->where('trproperty_id', $data->id)->get();
                        if ($data->id == '11' || $data->id == '14') {
                            $xx++;
                            $data_koefisien = new trkoefesientarifretribusi();
                            $data_koefisien->get_by_id($id_koefisien);
                            $data_property = $data->n_property;
                            $all_entry = $data_koefisien->kategori;
                            $index_entry = $data_koefisien->index_kategori;
                            if ($xx == '1') {
                                $it = $index_entry;
                                $nilai_it = $index_entry;
                            } else {
                                $it = $it . " x " . $index_entry;
                                $nilai_it = $nilai_it * $index_entry;
                            }
                            $listeArticles = array(
                                array('property' => $data_property,
                                    'content' => $all_entry,
                                    'index' => $index_entry,
                                ),
                            );
                            $article = $odf->setSegment('articles2');
                            foreach ($listeArticles AS $element) {
                                $article->titreArticle($element['property']);
                                $article->texteArticle($element['content']);
                                $article->texteArticle2($element['index']);
                                $article->merge();
                            }
                        }
                    }
                }
            }
        }
        $odf->mergeSegment($article);

        //Hanya Property KLASIFIKASI
        $it = $it . " x (";
        $list_property = $perizinan->trproperty->order_by('c_parent asc, c_order asc')->get();
        $list_content = $permohonan->tmproperty_jenisperizinan->get();
        $list_klasifikasi = $permohonan->tmproperty_klasifikasi->get();
        $yy = 0;
        $nilai_klas = 0;
        foreach ($list_property as $data) {
            if ($list_content->id) {
                foreach ($list_content as $data_daftar) {
                    $entry_property = new tmproperty_jenisperizinan_trproperty();
                    $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                            ->where('trproperty_id', $data->id)->get();
                    if ($entry_property->tmproperty_jenisperizinan_id) {
                        $entry_daftar = new tmproperty_jenisperizinan();
                        $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);

                        $data_entry = $entry_daftar->v_tinjauan;
                        $id_koefisien = $entry_daftar->k_tinjauan;

                        $izin_property = new trperizinan_trproperty();
                        $izin_property->where('trperizinan_id', $idjenis)
                                ->where('trproperty_id', $data->id)->get();
                        if ($data->id == '12') { //Hanya Property KLASIFIKASI
                            $index_property = 0;
                            $nilai_klas = 0;
                            $list_koefisien = new trkoefesientarifretribusi();
                            $list_koefisien->where_related($data)->get();
                            if ($list_koefisien->id) {
                                foreach ($list_koefisien as $row_koef) {
                                    if ($list_klasifikasi->id) {
                                        $data_property = $row_koef->kategori;
                                        $index_property = $row_koef->index_kategori;
                                        foreach ($list_klasifikasi as $data_klasifikasi) {
                                            $entry_koefisien = new tmproperty_klasifikasi_trkoefesientarifretribusi();
                                            $entry_koefisien->where('tmproperty_klasifikasi_id', $data_klasifikasi->id)
                                                    ->where('trkoefesientarifretribusi_id', $row_koef->id)->get();
                                            if ($entry_koefisien->tmproperty_klasifikasi_id) {
                                                $entry_daftar_klasifikasi = new tmproperty_klasifikasi();
                                                $entry_daftar_klasifikasi->get_by_id($entry_koefisien->tmproperty_klasifikasi_id);

                                                $klasifikasi_id = $entry_daftar_klasifikasi->id;
                                                $entry_klasifikasi = $entry_daftar_klasifikasi->v_tinjauan;
                                                $koef_klasifikasi = $entry_daftar_klasifikasi->k_tinjauan;
                                                $data_retribusi = new trkoefisienretribusilev1();
                                                $data_retribusi->get_by_id($koef_klasifikasi);
                                                $all_entry = $data_retribusi->kategori;
                                                $index_entry = $data_retribusi->index_kategori;
                                            }
                                        }
                                    }
                                    $yy++;
                                    if ($yy == '1')
                                        $it = $it . "(" . $index_property . " x " . $index_entry . ")";
                                    else
                                        $it = $it . " + (" . $index_property . " x " . $index_entry . ")";
                                    $nilai_klas = $nilai_klas + ($index_property * $index_entry);
                                    $listeArticles = array(
                                        array('property' => $data_property,
                                            'content' => $all_entry,
                                            'index_property' => $index_property,
                                            'index_entry' => $index_entry,
                                        ),
                                    );
                                    $article = $odf->setSegment('articles3');
                                    foreach ($listeArticles AS $element) {
                                        $article->titreArticle($element['property']);
                                        $article->texteArticle($element['content']);
                                        $article->texteArticle2($element['index_property']);
                                        $article->texteArticle3($element['index_entry']);
                                        $article->merge();
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $it = $it . ") ";
        $odf->mergeSegment($article);

        //Waktu Penggunaan
        $list_property = $perizinan->trproperty->order_by('c_parent asc, c_order asc')->get();
        $list_content = $permohonan->tmproperty_jenisperizinan->get();
        foreach ($list_property as $data) {
            if ($list_content->id) {
                foreach ($list_content as $data_daftar) {
                    $entry_property = new tmproperty_jenisperizinan_trproperty();
                    $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                            ->where('trproperty_id', $data->id)->get();
                    if ($entry_property->tmproperty_jenisperizinan_id) {
                        $entry_daftar = new tmproperty_jenisperizinan();
                        $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);

                        $data_entry = $entry_daftar->v_tinjauan;
                        $id_koefisien = $entry_daftar->k_tinjauan;

                        $izin_property = new trperizinan_trproperty();
                        $izin_property->where('trperizinan_id', $idjenis)
                                ->where('trproperty_id', $data->id)->get();
                        if ($data->id == '13') {
                            $data_koefisien = new trkoefesientarifretribusi();
                            $data_koefisien->get_by_id($id_koefisien);
                            $data_property = $data->n_property;
                            $all_entry = $data_koefisien->kategori;
                            $index_entry = $data_koefisien->index_kategori;
                            $it = $it . " x " . $index_entry;
                            $nilai_waktu = $index_entry;
                            $odf->setVars('waktu', $data_property);
                            $odf->setVars('wakturetribusi', $all_entry);
                            $odf->setVars('indexwakturetribusi', $index_entry);
                        }
                    }
                }
            }
        }

        //Hanya Property PRASARANA
        $list_property = $perizinan->trproperty->order_by('c_parent asc, c_order asc')->get();
        $list_content = $permohonan->tmproperty_jenisperizinan->get();
        $list_prasarana = $permohonan->tmproperty_prasarana->get();
        foreach ($list_prasarana as $data_prasarana) {
            $entry_koefisien = new tmproperty_prasarana_trkoefesientarifretribusi();
            $entry_koefisien->where('tmproperty_prasarana_id', $data_prasarana->id)->get();
            if ($entry_koefisien->trkoefesientarifretribusi_id) {
                $data_koefisien = new trkoefesientarifretribusi();
                $data_koefisien->get_by_id($entry_koefisien->trkoefesientarifretribusi_id);

                $entry_prasarana = $data_prasarana->v_tinjauan;
                $koef_prasarana = $data_prasarana->k_tinjauan;
                $data_retribusi = new trkoefisienretribusilev1();
                $data_retribusi->get_by_id($koef_prasarana);

//                if($entry_prasarana){
                $data_property = $data_koefisien->kategori;
                $index_property = $data_koefisien->index_kategori;
                $all_entry = $data_retribusi->kategori;
                $index_entry = $data_retribusi->index_kategori;
                $listeArticles = array(
                    array('property' => $data_property,
                        'content' => $all_entry,
                        'index_property' => '',
                        'index_entry' => $index_entry,
                    ),
                );
                $article = $odf->setSegment('articles4');
                foreach ($listeArticles AS $element) {
                    $article->titreArticle($element['property']);
                    $article->texteArticle($element['content']);
                    $article->texteArticle2($element['index_property']);
                    $article->texteArticle3($element['index_entry']);
                    $article->merge();
                }
//                }
            }
        }
        $odf->setVars('index', $it);
        $odf->setVars('totalindex', $nilai_it * $nilai_klas * $nilai_waktu);
        $odf->mergeSegment($article);
        
        $data8 = $permohonan->tmkeringananretribusi->get();
        if ($data8->id)
        {
            $hitung = ($data8->v_prosentase_retribusi * 0.01) * $bap->nilai_retribusi;
            $total = $bap->nilai_retribusi-$hitung;
            $hitung2 = $hitung;
        }
        else
        {
            $hitung2 = $bap->nilai_retribusi;
            $total = $bap->nilai_retribusi;    
        }
        
        //nilai retribusi manual & rumus perhitungan manual
        $this->perizinan = new trperizinan();
        $perizinan = $this->perizinan->get_by_id($idjenis);
        $property = $perizinan->trretribusi->get();
        if ($property->m_perhitungan == "1") {
            $prop = '45';
            $prop_nilai = $this->getTinjauan($id, $idjenis, $prop);
            if (isset($prop_nilai->v_tinjauan)) {
                if ($data8->id)
                {
                    $hitung = ($data8->v_prosentase_retribusi * 0.01) * $prop_nilai->v_tinjauan;
                    $totalM = $prop_nilai->v_tinjauan-$hitung;
                    $odf->setVars('keringanan', $data8->v_prosentase_retribusi.'% x Rp.'.$this->terbilang->nominal($prop_nilai->v_tinjauan) . ' = Rp. ' .$this->terbilang->nominal($hitung));
                }
                else
                {
                    $odf->setVars('keringanan', ' ');
                    $totalM = $prop_nilai->v_tinjauan;    
                }
        
                $odf->setVars('rpretribusi', ' ');
                $odf->setVars('jumlahretribusi', ' ');
                
                $odf->setVars('bilanganretribusi', $this->terbilang->terbilang($totalM) . ' rupiah.');
                $odf->setVars('hitungmanual', 'Rp.' . $this->terbilang->nominal($totalM));
            } else {
                $odf->setVars('keringanan', ' ');
                $odf->setVars('rpretribusi', ' ');
                $odf->setVars('jumlahretribusi', ' ');
                $odf->setVars('bilanganretribusi', ' rupiah.');
                $odf->setVars('hitungmanual', '---------');
            }

            $rums = '414';
            $rums_nilai = $this->getTinjauan($id, $idjenis, $rums);
            if (isset($rums_nilai->v_tinjauan)) {
                $odf->setVars('rumus', 'Rumus :' . $rums_nilai->v_tinjauan);
            } else {
                $odf->setVars('rumus', '');
            }
        } else {
            //$total = $permohonan->trperizinan->trretribusi->v_retribusi;
            $odf->setVars('keringanan', $data8->v_prosentase_retribusi.'% x Rp.'.$this->terbilang->nominal($bap->nilai_retribusi) . ' = Rp. ' .$this->terbilang->nominal($hitung2));
            $odf->setVars('rpretribusi', '= Rp.');
            $odf->setVars('jumlahretribusi', $this->terbilang->nominal($total, 2));
            $odf->setVars('bilanganretribusi', $this->terbilang->terbilang($total) . ' rupiah.');
            $odf->setVars('rumus', ' ');
            $odf->setVars('hitungmanual', ' ');
        }

        //Retribusi
        $retribusi_imb = new tmretribusi_rinci_imb();
        $retribusi_imb->where_related($permohonan)->order_by('c_imb asc, id asc')->get();
        foreach ($retribusi_imb AS $data_imb) {

            //nilai retribusi manual & rumus perhitungan manual

            if ($property->m_perhitungan == "1") {


                $listeArticles = array(
                    array('property' => ' ',
                        'content' => ' ',
                        'rupiah' => ' ',
                        'value' => ' ',
                    ),
                );
            } else {

                $listeArticles = array(
                    array('property' => $data_imb->e_parameter_parent,
                        'content' => $data_imb->e_parameter,
                        'rupiah' => '= Rp. ',
                        'value' => $this->terbilang->nominal($data_imb->v_retribusi, 2),
                    ),
                );
            }
            $article = $odf->setSegment('articles5');
            foreach ($listeArticles AS $element) {
                $article->titreArticle($element['property']);
                $article->texteArticle($element['content']);
                $article->texteArticle2($element['rupiah']);
                $article->texteArticle3($element['value']);
                $article->merge();
            }
        }
        $odf->mergeSegment($article);

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql2($u_ser);
//      $jam = date("H:i:s A");
        $p = $this->db->query("call log ('Pembuatan SKRD','Cetak Lamp SKRD " . $permohonan->pendaftaran_id . "','" . $tgl . "','" . $u_ser . "')");


        //break
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat . '_' . $no_daftar . '.odt');
    }

    public function cetakSKRDgeneric_archive($id=NULL, $izin=NULL) {

        $this->settings = new settings();
        $this->settings->where('name', 'app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name', 'app_city')->get();

        $permohonan = new tmpermohonan();
        $retribusi = new trretribusi();

        $permohonan->get_by_id($id);

        $data1 = $permohonan->trperizinan->get();
        $data2 = $permohonan->tmpemohon->get();
        $data3 = $permohonan->tmbap->get();
        $data4 = $data1->$retribusi->get(); //where('perizinan_id',$izin)->get();
        $data5 = $permohonan->tmperusahaan->get();
        $data6 = $permohonan->trperizinan->trdasar_hukum->get();
        $data7 = $permohonan->trperizinan->trretribusi;
        $data8 = $permohonan->tmkeringananretribusi->get();

        $p_kelurahan = $data2->trkelurahan->get();
        $p_kecamatan = $data2->trkelurahan->trkecamatan->get();
        $p_kabupaten = $data2->trkelurahan->trkecamatan->trkabupaten->get();

        //Cek Tracking Progress
        $updated = FALSE;
        $daftar_awal = new tmpermohonan();
        $daftar_awal->get_by_id($id);
        $sts_awal = new trstspermohonan();
        $sts_awal->get_by_id('10'); //Menetapkan Retribusi dan Mencetak Izin [Lihat Tabel trstspermohonan()]
        $list_track = $daftar_awal->tmtrackingperizinan->get();
        if ($list_track) {
            foreach ($list_track as $data_track) {
                $data_status = new tmtrackingperizinan_trstspermohonan();
                $data_status->where('tmtrackingperizinan_id', $data_track->id)
                        ->where('trstspermohonan_id', $sts_awal->id)->get();
                if ($data_status->tmtrackingperizinan_id) {
                    $updated = TRUE;
                    break;
                }
            }
        } else {
            $updated = FALSE;
        }

        //Status cetak SKRD
        $bap = new tmbap();
        $bap->get_by_id($data3->id);
        $bap->c_skrd = $data3->c_skrd + 1;
        $bap->save();

        //path of the template file
        $nama_surat = "cetak_skrd_generic";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/' . $nama_surat . '.odt');
        $odf->setImage('header', 'assets/css/' . $app_folder . '/images/dinas_1.jpg', '17.5', '3.5');

        //membuat kota
        $wilayah = new trkabupaten();
        if ($app_city->value !== '0') {
            $alamat = $data2->a_pemohon . ' ' . $p_kelurahan->n_kelurahan . ', ' .
                    $p_kecamatan->n_kecamatan . ', ' . ucwords(strtolower($p_kabupaten->n_kabupaten));
            $wilayah->get_by_id($app_city->value);
            $odf->setVars('kota', $wilayah->n_kabupaten);
        } else {
            $alamat = $data2->a_pemohon;
            $odf->setVars('kota', '...........');
        }

        $petugas = 1;
        $pegawai = new tmpegawai();
        $pegawai->where('status', $petugas)->get();
        $odf->setVars('jabatan', $pegawai->n_jabatan);
        $odf->setVars('nama_pejabat', $pegawai->n_pegawai);
        $odf->setVars('nip_pejabat', $pegawai->nip);

        $odf->setVars('title', 'SURAT KETETAPAN RETRIBUSI DAERAH');
        $odf->setVars('IZIN', $data1->n_perizinan);
        $tgl_skr = $this->lib_date->get_date_now();
        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($tgl_skr));
        $odf->setVars('no_skrd', $data3->no_skrd);
        $odf->setVars('nilairetribusi', 'Rp. ' . number_format($data7->v_retribusi, 2, ',', '.'));
        $odf->setVars('jumlahretribusi', 'Rp. ' . $this->terbilang->nominal($data3->nilai_retribusi, 2)) . ' ';
        $odf->setVars('diskon', $data8->v_prosentase_retribusi);
        $odf->setVars('i_surat', $data8->i_nomor_surat);

        if ($data8->id)
            $total = ($data8->v_prosentase_retribusi * 0.01) * $data3->nilai_retribusi;
        else
            $total = $data3->nilai_retribusi;

        $odf->setVars('bilangan', $this->terbilang->terbilang($total) . ' rupiah.');

        $odf->setVars('totalretribusi', ' Rp.' . number_format($total, 2, ',', '.'));

        //dasar hukum

        $i = 1;
        $izin_hukum = new trdasar_hukum_trperizinan();
        $list_hukum = $izin_hukum->where('type', 1)
                        ->where('trperizinan_id', $data1->id)->order_by('trdasar_hukum_id', 'ASC')->get(); //17 2
        if ($list_hukum->id) {
            foreach ($list_hukum as $hukum) {
                $dasar_hukum = new trdasar_hukum();
                if ($hukum->id) {
                    $data6 = $dasar_hukum->where('id', $hukum->trdasar_hukum_id)->get();
                    $desk = $data6->deskripsi;
                } else {
                    $desk = ' ';
//                      $i=' ';
                }


                $listeArticles2 = array(
                    array('property' => $i . '.',
                        'content' => $desk,
                    ),
                );

                $article2 = $odf->setSegment('articles2');
                foreach ($listeArticles2 AS $element2) {
                    $article2->titreArticle2($element2['property']);
                    $article2->texteArticle2($element2['content']);
                    $article2->merge();
                }
                $i++;
            }
        } else {
            $desk = '';
            $listeArticles2 = array(
                array('property' => '',
                    'content' => $desk,
                ),
            );

            $article2 = $odf->setSegment('articles2');
            foreach ($listeArticles2 AS $element2) {
                $article2->titreArticle2($element2['property']);
                $article2->texteArticle2($element2['content']);
                $article2->merge();
            }
        }
        $odf->mergeSegment($article2);

        //skrd
        $listeArticles = array(
            array('property' => 'Nomor Pendaftaran',
                'content' => $permohonan->pendaftaran_id,
            ),
            array('property' => 'Nama Pemohon',
                'content' => $data2->n_pemohon,
            ),
            array('property' => 'Alamat',
                'content' => $data2->a_pemohon,
            ),
        );

        $article = $odf->setSegment('articles');
        foreach ($listeArticles AS $element) {
            $article->titreArticle($element['property']);
            $article->texteArticle($element['content']);
            $article->merge();
        }

        $odf->mergeSegment($article);

        $i = 4;
        $list_content = $permohonan->tmproperty_jenisperizinan->get();
        $list_property = $permohonan->trperizinan->trproperty->where('c_type', 0)->get();

        foreach ($list_property as $data) {
            $property_satuan = new trperizinan_trproperty();
            $property_satuan->where('trproperty_id', $data->id)->get();
            if ($list_content->id) {
                foreach ($list_content as $data_daftar) {
                    $entry_property = new tmproperty_jenisperizinan_trproperty();
                    $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                            ->where('trproperty_id', $data->id)->get();
                    if ($entry_property->tmproperty_jenisperizinan_id) {
                        $entry_daftar = new tmproperty_jenisperizinan();
                        $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);

                        $data_entry = $entry_daftar->v_tinjauan;
                        $id_koefisien = $entry_daftar->k_tinjauan;
                    }
                }
            } else {
                $data_entry = '';
                $id_koefisien = '';
            }
            $izin_property = new trperizinan_trproperty();
            $izin_property->where('trperizinan_id', $izin)
                    ->where('trproperty_id', $data->id)->get();
            $id_sk = $izin_property->c_skrd_id;

            if ($data_entry) {
                if ($id_sk == '1') {
                    $data_koefisien = new trkoefesientarifretribusi();
                    $data_koefisien->get_by_id($id_koefisien);
                    $data_property = '' . $data->n_property;
                    $i++;
                    if ($data_entry) {
                        $all_entry = $data_koefisien->kategori . ' ' . $data_entry . " " . $property_satuan->satuan;
                        $titik = ':';
                    } else {
                        $all_entry = $data_koefisien->kategori;
                        $titik = ':';
                    }
                    $listeArticles3 = array(
                        array('property3' => $data_property,
                            'content3' => $all_entry,
                            'content33' => $titik,
                        ),
                    );
                    $article3 = $odf->setSegment('articles3');
                    foreach ($listeArticles3 AS $element3) {
                        $article3->titreArticle3($element3['property3']);
                        $article3->texteArticle3($element3['content3']);
                        $article3->texteArticle4($element3['content33']);
                        $article3->merge();
                    }
                }
            }
        }
        $odf->mergeSegment($article3);

        /////////////////////////
        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat . '_' . $no_daftar . '.odt');
    }

    public function cetakSKRD_archive($id=NULL, $izin=NULL) {

        $this->settings = new settings();
        $this->settings->where('name', 'app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name', 'app_city')->get();

        $permohonan = new tmpermohonan();
        $retribusi = new trretribusi();

        $permohonan->get_by_id($id);

        $data1 = $permohonan->trperizinan->get();
        $data2 = $permohonan->tmpemohon->get();
        $data3 = $permohonan->tmbap->get();
        $data4 = $data1->$retribusi->get(); //where('perizinan_id',$izin)->get();
        $data5 = $permohonan->tmperusahaan->get();
        $data6 = $permohonan->trperizinan->trdasar_hukum->get();
        $data7 = $permohonan->trperizinan->trretribusi;
        $data8 = $permohonan->tmkeringananretribusi->get();

        $p_kelurahan = $data2->trkelurahan->get();
        $p_kecamatan = $data2->trkelurahan->trkecamatan->get();
        $p_kabupaten = $data2->trkelurahan->trkecamatan->trkabupaten->get();

        //Cek Tracking Progress
        $updated = FALSE;
        $daftar_awal = new tmpermohonan();
        $daftar_awal->get_by_id($id);
        $sts_awal = new trstspermohonan();
        $sts_awal->get_by_id('10'); //Menetapkan Retribusi dan Mencetak Izin [Lihat Tabel trstspermohonan()]
        $list_track = $daftar_awal->tmtrackingperizinan->get();
        if ($list_track) {
            foreach ($list_track as $data_track) {
                $data_status = new tmtrackingperizinan_trstspermohonan();
                $data_status->where('tmtrackingperizinan_id', $data_track->id)
                        ->where('trstspermohonan_id', $sts_awal->id)->get();
                if ($data_status->tmtrackingperizinan_id) {
                    $updated = TRUE;
                    break;
                }
            }
        } else {
            $updated = FALSE;
        }

        /* Input Data Tracking Progress */
        if ($updated) {
            $sts_izin = new trstspermohonan();
            $sts_izin->get_by_id('10'); //Menetapkan Retribusi dan Mencetak Izin [Lihat Tabel trstspermohonan()]
            $data_status = new tmtrackingperizinan_trstspermohonan();
            $list_tracking = $permohonan->tmtrackingperizinan->get();
            if ($list_tracking) {
                $tracking_id = 0;
                foreach ($list_tracking as $data_track) {
                    $data_status = new tmtrackingperizinan_trstspermohonan();
                    $data_status->where('tmtrackingperizinan_id', $data_track->id)
                            ->where('trstspermohonan_id', $sts_izin->id)->get();
                    if ($data_status->tmtrackingperizinan_id) {
                        $tracking_id = $data_status->tmtrackingperizinan_id;
                    }
                }
            }
            $tracking_izin = new tmtrackingperizinan();
            $tracking_izin->get_by_id($tracking_id);
            //$tracking_izin->pendaftaran_id = $permohonan->pendaftaran_id;
            $tracking_izin->status = 'Update';
            $tracking_izin->d_entry = $this->lib_date->get_date_now();
            $tracking_izin->save();
//            $tracking_izin->save($sts_izin);
//            $tracking_izin->save($permohonan);
        } else {
            $tracking_izin = new tmtrackingperizinan();
            $tracking_izin->pendaftaran_id = $permohonan->pendaftaran_id;
            $tracking_izin->status = 'Insert';
            $tracking_izin->d_entry_awal = $this->lib_date->get_date_now();
            $tracking_izin->d_entry = $this->lib_date->get_date_now();
            $sts_izin = new trstspermohonan();
            $sts_izin->get_by_id('10'); //Menetapkan Retribusi dan Mencetak Izin [Lihat Tabel trstspermohonan()]
            $sts_izin->save($permohonan);
            $tracking_izin->save($permohonan);
            $tracking_izin->save($sts_izin);
        }

        //Status cetak SKRD
        $bap = new tmbap();
        $bap->get_by_id($data3->id);
        $bap->c_skrd = $data3->c_skrd + 1;
        $bap->save();

        //path of the template file
        $nama_surat = "cetak_skrd";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/' . $nama_surat . '.odt');
        $odf->setImage('header', 'assets/css/' . $app_folder . '/images/dinas_1.jpg', '17.5', '3.5');
        $odf->setVars('ttd', '');

        //membuat kota
        $wilayah = new trkabupaten();
        if ($app_city->value !== '0') {
            $alamat = $data2->a_pemohon . ' ' . $p_kelurahan->n_kelurahan . ', ' .
                    $p_kecamatan->n_kecamatan . ', ' . ucwords(strtolower($p_kabupaten->n_kabupaten));
            $wilayah->get_by_id($app_city->value);
            $odf->setVars('kota', $wilayah->n_kabupaten);
        } else {
            $alamat = $data2->a_pemohon;
            $odf->setVars('kota', '...........');
        }

        $petugas = 1;
        $pegawai = new tmpegawai();
        $pegawai->where('status', $petugas)->get();
        $odf->setVars('jabatan', $pegawai->n_jabatan);
        $odf->setVars('nama_pejabat', $pegawai->n_pegawai);
        $odf->setVars('nip_pejabat', $pegawai->nip);

        $odf->setVars('title', 'SURAT KETETAPAN RETRIBUSI DAERAH');
        $odf->setVars('IZIN', $data1->n_perizinan);
        $tgl_skr = $this->lib_date->get_date_now();
        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($tgl_skr));
        $odf->setVars('no_skrd', $data3->no_skrd);
        $odf->setVars('nilairetribusi', ' x Rp.' . number_format($data7->v_retribusi, 2, ',', '.'));
        $odf->setVars('jumlahretribusi', 'Rp' . $this->terbilang->nominal($data3->nilai_retribusi, 2)) . ' ';
        $odf->setVars('diskon', $data8->v_prosentase_retribusi);
        $odf->setVars('i_surat', $data8->i_nomor_surat);

        if ($data8->id)
            $total = ($data8->v_prosentase_retribusi * 0.01) * $data3->nilai_retribusi;
        else
            $total = $data3->nilai_retribusi;

        $odf->setVars('bilangan', $this->terbilang->terbilang($total) . ' rupiah.');

        $odf->setVars('totalretribusi', ' Rp.' . number_format($total, 2, ',', '.'));

        //dasar hukum

        $i = 1;
        $izin_hukum = new trdasar_hukum_trperizinan();
        $list_hukum = $izin_hukum->where('type', 1)
                        ->where('trperizinan_id', $data1->id)->order_by('trdasar_hukum_id', 'ASC')->get(); //17 2
        if ($list_hukum->id) {
            foreach ($list_hukum as $hukum) {
                $dasar_hukum = new trdasar_hukum();
                if ($hukum->id) {
                    $data6 = $dasar_hukum->where('id', $hukum->trdasar_hukum_id)->get();
                    $desk = $data6->deskripsi;
                } else {
                    $desk = ' ';
//                      $i=' ';
                }


                $listeArticles2 = array(
                    array('property' => $i . '.',
                        'content' => $desk,
                    ),
                );

                $article2 = $odf->setSegment('articles2');
                foreach ($listeArticles2 AS $element2) {
                    $article2->titreArticle2($element2['property']);
                    $article2->texteArticle2($element2['content']);
                    $article2->merge();
                }
                $i++;
            }
        } else {
            $desk = '';
            $listeArticles2 = array(
                array('property' => '',
                    'content' => $desk,
                ),
            );

            $article2 = $odf->setSegment('articles2');
            foreach ($listeArticles2 AS $element2) {
                $article2->titreArticle2($element2['property']);
                $article2->texteArticle2($element2['content']);
                $article2->merge();
            }
        }
        $odf->mergeSegment($article2);

        //skrd
        $listeArticles = array(
            array('property' => '1. Nomor Pendaftaran',
                'content' => $permohonan->pendaftaran_id,
            ),
            array('property' => '2. Nama Pemohon',
                'content' => $data2->n_pemohon,
            ),
            array('property' => '3. Alamat',
                'content' => $data2->a_pemohon,
            ),
        );

        $article = $odf->setSegment('articles');
        foreach ($listeArticles AS $element) {
            $article->titreArticle($element['property']);
            $article->texteArticle($element['content']);
            $article->merge();
        }

        $odf->mergeSegment($article);

        $z = 0;
        $list_property2 = $permohonan->trperizinan->trproperty->order_by('c_parent asc, c_order asc')->get();
        foreach ($list_property2 as $data2) {


            $koefprop = new trkoefesientarifretribusi_trproperty();
            $koefprop->where('trproperty_id', $data2->id)->get();

            $propp = new trproperty();
            $propp->where('id', $koefprop->trproperty_id)->get();

            if ($koefprop->trproperty_id) {
                $z++;
                $np2 = $propp->n_property;

                if ($z == 1
                )
                    $tanda = ' = ';
                else
                    $tanda = ' x ';



                $listeArticles1 = array(
                    array('property1' => $tanda . $np2,
                        'content1' => '',
                    ),
                );
                $article1 = $odf->setSegment('articles1');
                foreach ($listeArticles1 AS $element1) {
                    $article1->titreArticle($element1['property1']);
                    $article1->texteArticle($element1['content1']);
                    $article1->merge();
                }
            }
        }
        $odf->mergeSegment($article1);


        //dari SK properti dengan c_sk_id

        $i = 4;
        $list_content = $permohonan->tmproperty_jenisperizinan->get();
        $list_property = $permohonan->trperizinan->trproperty->where('c_type', 0)->get();

        foreach ($list_property as $data) {
            $property_satuan = new trperizinan_trproperty();
            $property_satuan->where('trproperty_id', $data->id)->get();
            if ($list_content->id) {
                foreach ($list_content as $data_daftar) {
                    $entry_property = new tmproperty_jenisperizinan_trproperty();
                    $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                            ->where('trproperty_id', $data->id)->get();
                    if ($entry_property->tmproperty_jenisperizinan_id) {
                        $entry_daftar = new tmproperty_jenisperizinan();
                        $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);

                        $data_entry = $entry_daftar->v_tinjauan;
                        $id_koefisien = $entry_daftar->k_tinjauan;
                    }
                }
            } else {
                $data_entry = '';
                $id_koefisien = '';
            }
            $izin_property = new trperizinan_trproperty();
            $izin_property->where('trperizinan_id', $izin)
                    ->where('trproperty_id', $data->id)->get();
            $id_sk = $izin_property->c_skrd_id;

            if ($data_entry) {
                if ($id_sk == '1') {
                    $data_koefisien = new trkoefesientarifretribusi();
                    $data_koefisien->get_by_id($id_koefisien);
                    $data_property = $i . '. ' . $data->n_property;
                    $i++;
                    if ($data_entry) {
                        $all_entry = $data_koefisien->kategori . ' ' . $data_entry . ' ' . $property_satuan->satuan;
                        $titik = ':';
                    } else {
                        $all_entry = $data_koefisien->kategori;
                        $titik = ':';
                    }
                    $listeArticles3 = array(
                        array('property3' => $data_property,
                            'content3' => $all_entry,
                            'content33' => $titik,
                        ),
                    );
                    $article3 = $odf->setSegment('articles3');
                    foreach ($listeArticles3 AS $element3) {
                        $article3->titreArticle3($element3['property3']);
                        $article3->texteArticle3($element3['content3']);
                        $article3->texteArticle4($element3['content33']);
                        $article3->merge();
                    }
                }
            }
        }
        $odf->mergeSegment($article3);

        /////////////////////////
        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat . '_' . $no_daftar . '.odt');
    }

    public function cetakSKRDimb_archive($id=NULL, $izin=NULL) {

        $this->settings = new settings();
        $this->settings->where('name', 'app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name', 'app_city')->get();

        $permohonan = new tmpermohonan();
        $retribusi = new trretribusi();

        $permohonan->get_by_id($id);

        $data1 = $permohonan->trperizinan->get();
        $data2 = $permohonan->tmpemohon->get();
        $data3 = $permohonan->tmbap->get();
        $data4 = $data1->$retribusi->get(); //where('perizinan_id',$izin)->get();
        $data5 = $permohonan->tmperusahaan->get();
        $data6 = $permohonan->trperizinan->trdasar_hukum->get();
        $data7 = $permohonan->trperizinan->trretribusi;
        $data8 = $permohonan->tmkeringananretribusi->get();

        $p_kelurahan = $data2->trkelurahan->get();
        $p_kecamatan = $data2->trkelurahan->trkecamatan->get();
        $p_kabupaten = $data2->trkelurahan->trkecamatan->trkabupaten->get();

        //Cek Tracking Progress
        $updated = FALSE;
        $daftar_awal = new tmpermohonan();
        $daftar_awal->get_by_id($id);
        $sts_awal = new trstspermohonan();
        $sts_awal->get_by_id('10'); //Menetapkan Retribusi dan Mencetak Izin [Lihat Tabel trstspermohonan()]
        $list_track = $daftar_awal->tmtrackingperizinan->get();
        if ($list_track) {
            foreach ($list_track as $data_track) {
                $data_status = new tmtrackingperizinan_trstspermohonan();
                $data_status->where('tmtrackingperizinan_id', $data_track->id)
                        ->where('trstspermohonan_id', $sts_awal->id)->get();
                if ($data_status->tmtrackingperizinan_id) {
                    $updated = TRUE;
                    break;
                }
            }
        } else {
            $updated = FALSE;
        }

        //path of the template file
        $nama_surat = "cetak_skrd_imb";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/' . $nama_surat . '.odt');
        $odf->setImage('header', 'assets/css/' . $app_folder . '/images/dinas_1.jpg', '17.5', '3.5');
        $odf->setVars('ttd', '');

        //membuat kota
        $wilayah = new trkabupaten();
        if ($app_city->value !== '0') {
            $alamat = $data2->a_pemohon . ' ' . $p_kelurahan->n_kelurahan . ', ' .
                    $p_kecamatan->n_kecamatan . ', ' . ucwords(strtolower($p_kabupaten->n_kabupaten));
            $wilayah->get_by_id($app_city->value);
            $odf->setVars('kota', $wilayah->n_kabupaten);
        } else {
            $alamat = $data2->a_pemohon;
            $odf->setVars('kota', '...........');
        }

        $petugas = 1;
        $pegawai = new tmpegawai();
        $pegawai->where('status', $petugas)->get();
        $odf->setVars('jabatan', $pegawai->n_jabatan);
        $odf->setVars('nama_pejabat', $pegawai->n_pegawai);
        $odf->setVars('nip_pejabat', $pegawai->nip);

        $odf->setVars('title', 'SURAT KETETAPAN RETRIBUSI DAERAH');
        $odf->setVars('IZIN', $data1->n_perizinan);
        $tgl_skr = $this->lib_date->get_date_now();
        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($tgl_skr, 1));
        $odf->setVars('no_skrd', $data3->no_skrd);
//        $odf->setVars('nilairetribusi',' x Rp.'. number_format($data7->v_retribusi,2,',','.'));
        $retribusi_gedung = new tmretribusi_rinci_imb();
        $retribusi_gedung->where_related($permohonan)
                ->where('c_imb', 1)->get();
        $odf->setVars('bangunan_gedung', $this->terbilang->nominal($retribusi_gedung->v_retribusi));
        $retribusi_prasarana = new tmretribusi_rinci_imb();
        $retribusi_prasarana->select_sum('v_retribusi')
                ->where_related($permohonan)
                ->where('c_imb', 2)->get();
        $odf->setVars('prasarana', $this->terbilang->nominal($retribusi_prasarana->v_retribusi));
        $jumlah_retribusi = $retribusi_gedung->v_retribusi + $retribusi_prasarana->v_retribusi;
//        $jumlah_retribusi = $data3->nilai_retribusi;
        $odf->setVars('jumlahretribusi', 'Rp' . $this->terbilang->nominal($jumlah_retribusi, 2)) . ' ';
        $odf->setVars('diskon', $data8->v_prosentase_retribusi);
        $odf->setVars('i_surat', $data8->i_nomor_surat);

        if ($data8->id)
            $total = ($data8->v_prosentase_retribusi * 0.01) * $data3->nilai_retribusi;
        else
            $total = $data3->nilai_retribusi;

        $odf->setVars('bilangan', $this->terbilang->terbilang($total) . ' rupiah.');

        $odf->setVars('totalretribusi', ' Rp.' . number_format($total, 2, ',', '.'));

        //dasar hukum

        $i = 1;
        $izin_hukum = new trdasar_hukum_trperizinan();
        $list_hukum = $izin_hukum->where('type', 1)
                        ->where('trperizinan_id', $data1->id)->order_by('trdasar_hukum_id', 'ASC')->get(); //17 2
        if ($list_hukum->id) {
            foreach ($list_hukum as $hukum) {
                $dasar_hukum = new trdasar_hukum();
                if ($hukum->id) {
                    $data6 = $dasar_hukum->where('id', $hukum->trdasar_hukum_id)->get();
                    $desk = $data6->deskripsi;
                } else {
                    $desk = ' ';
//                      $i=' ';
                }


                $listeArticles2 = array(
                    array('property' => $i . '.',
                        'content' => $desk,
                    ),
                );

                $article2 = $odf->setSegment('articles2');
                foreach ($listeArticles2 AS $element2) {
                    $article2->titreArticle2($element2['property']);
                    $article2->texteArticle2($element2['content']);
                    $article2->merge();
                }
                $i++;
            }
        } else {
            $desk = '';
            $listeArticles2 = array(
                array('property' => '',
                    'content' => $desk,
                ),
            );

            $article2 = $odf->setSegment('articles2');
            foreach ($listeArticles2 AS $element2) {
                $article2->titreArticle2($element2['property']);
                $article2->texteArticle2($element2['content']);
                $article2->merge();
            }
        }
        $odf->mergeSegment($article2);

        //skrd
        $listeArticles = array(
            array('property' => '1. NOMOR PENDAFTARAN',
                'content' => $permohonan->pendaftaran_id,
            ),
            array('property' => '2. NAMA PEMOHON',
                'content' => $data2->n_pemohon,
            ),
            array('property' => '3. ALAMAT',
                'content' => $data2->a_pemohon,
            ),
        );

        $article = $odf->setSegment('articles');
        foreach ($listeArticles AS $element) {
            $article->titreArticle($element['property']);
            $article->texteArticle($element['content']);
            $article->merge();
        }

        $odf->mergeSegment($article);

        $z = 0;
        $list_property2 = $permohonan->trperizinan->trproperty->order_by('c_parent asc, c_order asc')->get();
        foreach ($list_property2 as $data2) {


            $koefprop = new trkoefesientarifretribusi_trproperty();
            $koefprop->where('trproperty_id', $data2->id)->get();

            $propp = new trproperty();
            $propp->where('id', $koefprop->trproperty_id)->get();
        }

        $i = 4;
        $list_content = $permohonan->tmproperty_jenisperizinan->get();
//        $list_property = $permohonan->trperizinan->trproperty->where('c_type', 0)->get();
        $list_property = $permohonan->trperizinan->trproperty->get();


        foreach ($list_property as $data) {
            if ($list_content->id) {
                foreach ($list_content as $data_daftar) {
                    $entry_property = new tmproperty_jenisperizinan_trproperty();
                    $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                            ->where('trproperty_id', $data->id)->get();
                    if ($entry_property->tmproperty_jenisperizinan_id) {
                        $entry_daftar = new tmproperty_jenisperizinan();
                        $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);

                        $data_entry = $entry_daftar->v_tinjauan;
                        $id_koefisien = $entry_daftar->k_tinjauan;
                    }
                }
            } else {
                $data_entry = '';
                $id_koefisien = '';
            }
            $izin_property = new trperizinan_trproperty();
            $izin_property->where('trperizinan_id', $izin)
                    ->where('trproperty_id', $data->id)->get();
            $id_sk = $izin_property->c_skrd_id;

            if ($id_sk == '1') {
                $data_koefisien = new trkoefesientarifretribusi();
                $data_koefisien->get_by_id($id_koefisien);
                $data_property = $i . '. ' . $data->n_property;
                $i++;
                if ($data_entry) {
                    $all_entry = $data_koefisien->kategori . ' ' . $data_entry . '';
                    $titik = ':';
                } else {
                    $all_entry = $data_koefisien->kategori;
                    $titik = ':';
                }
                $listeArticles3 = array(
                    array('property3' => $data_property,
                        'content3' => $all_entry,
                        'content33' => $titik,
                    ),
                );
                $article3 = $odf->setSegment('articles3');
                foreach ($listeArticles3 AS $element3) {
                    $article3->titreArticle3($element3['property3']);
                    $article3->texteArticle3($element3['content3']);
                    $article3->texteArticle4($element3['content33']);
                    $article3->merge();
                }
            }
        }
        $odf->mergeSegment($article3);

        /////////////////////////
        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat . '_' . $no_daftar . '.odt');
    }

    public function sql() {
        $query = "select a.n_kabupaten from trkabupaten a where
            a.id = (select value from settings where name='app_city')";

        $sql = $this->db->query($query);
        return $sql->result();
    }

    public function sql2($u_ser) {
        $query = "select a.description
	from user_auth as a
	inner join user_user_auth as  x on a.id = x.user_auth_id
	inner join user as b on b.id = x.user_id
        where b.id = (select id from user where username='" . $u_ser . "')";
        $hasil = $this->db->query($query);
        return $hasil->row();
    }

    public function getDatahukum($id) {
        $query = "select a.id,a.trdasar_hukum_id,a.trperizinan_id
        from trdasar_hukum_trperizinan as a
        inner join trperizinan as b on b.id=a.trperizinan_id
        inner join trdasar_hukum as c on c.id = a.trdasar_hukum_id
        where a.trperizinan_id = '" . $id . "' and c.type=1
	";
        $hasil = $this->db->query($query);
        return $hasil->result();
    }

    public function getTinjauan($daftar, $izin, $jnsProp) {
        $query = "select a.id,a.v_property,a.v_tinjauan from tmproperty_jenisperizinan as a
        inner join tmpermohonan_tmproperty_jenisperizinan as b on b.tmproperty_jenisperizinan_id=a.id
        inner join tmpermohonan as c on c.id=b.tmpermohonan_id
        inner join tmproperty_jenisperizinan_trproperty as d on d.tmproperty_jenisperizinan_id=a.id
        inner join trproperty as e on e.id=d.trproperty_id
        where c.id='".$daftar."' and e.id='".$jnsProp."'";
        $hasil = $this->db->query($query);
        return $hasil->row();
    }

}

// This is the end of role class
