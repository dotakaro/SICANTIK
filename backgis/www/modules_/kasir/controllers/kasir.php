<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of kasir class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class Kasir extends WRC_AdminCont {

    private $_status_kasir = 13;

    public function __construct() {
        parent::__construct();
        $this->permohonan = new tmpermohonan();
        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '16') {
                $enabled = TRUE;
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
//        $daftar = new tmpermohonan();
//        $query = $daftar
//                ->where('c_pendaftaran', 1) //Pendaftaran selesai
//                ->where('c_izin_selesai', 0) //SK Belum diserahkan
//                ->where('c_izin_dicabut', 0) //Permohonan tidak dicabut
//                ->order_by('d_terima_berkas', 'DESC')->limit(1500)->get();
        $tgla = $this->input->post('tgla');
        $tglb = $this->input->post('tglb');
        $now = $this->lib_date->get_date_now();
        $tgl_before = $this->lib_date->set_date($now, -2);
        $tgl_now = $this->lib_date->set_date($now, 0);

        if($tgla && $tglb){
            $data['tgla'] = $tgla;
            $data['tglb'] = $tglb;
        }else{
            $tgla = $tgl_before;
            $tglb = $tgl_now;
            $data['tgla'] = $tgla;
            $data['tglb'] = $tglb;
        }
        $username = new user();
        $username->where('username', $this->session->userdata('username'))->get();

//        $query_filter_user = "AND J.user_id = '".$username->id."'";
//        if($this->__is_administrator()){
            $query_filter_user="";
//        }

        $status_kasir = $this->_status_kasir;
        $current_unitkerja = $this->__get_current_unitkerja();

        $query = "SELECT DISTINCT A.id, A.pendaftaran_id, A.d_terima_berkas, A.d_survey,
            A.d_perubahan, A.d_perpanjangan, A.d_daftarulang, A.c_status_bayar,
            C.id idizin, C.n_perizinan, E.n_pemohon,
            G.id idjenis, G.n_permohonan
            FROM tmpermohonan as A
            INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
            INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
            INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
            INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
            INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
            INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
            LEFT JOIN tmbap_tmpermohonan as H ON H.tmpermohonan_id = A.id
            LEFT JOIN tmbap as I ON H.tmbap_id = I.id
            LEFT JOIN trperizinan_user AS J ON J.trperizinan_id = C.id
            WHERE A.c_pendaftaran = 1
            AND A.c_izin_dicabut = 0
            AND A.c_izin_selesai = 0
            /*AND I.c_skrd <> 0*/ ".
            $query_filter_user.
            " AND A.d_terima_berkas between '".$tgla."' and '".$tglb."'
            AND A.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})
            AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
            AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = {$status_kasir})>0
            order by A.id DESC";

        $data['list'] = $query;
        $data['c_bap'] = "1";
        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#permohonan').dataTable({
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
        $this->session_info['page_name'] = "Pembayaran Retribusi";
        $this->template->build('list', $this->session_info);
    }

    public function edit($id = NULL) {
        $this->permohonan->where('id', $id)->get();
        $this->permohonan->tmpemohon->get();
        $this->permohonan->trperizinan->get();
        $this->permohonan->tmbap->get();
        $this->permohonan->tmsk->get();
        $this->permohonan->trperizinan->trretribusi->get();
        $retribusi = $this->permohonan->tmbap->nilai_bap_awal;
        $keringanan = $this->permohonan->tmkeringananretribusi->get();

        /*Update 31 Mar 2014*/
        if($keringanan->id){
            $nilai_keringanan = ($keringanan->v_prosentase_retribusi * 0.01) * $retribusi;
            $nilai_ret = $retribusi - $nilai_keringanan;
        }else{
            $nilai_ret = $retribusi;
        }
        /****************/

        /*if ($keringanan->id)
        {
            $nilai_ret1 = ($keringanan->v_prosentase_retribusi * 0.01) * $retribusi;
            $nilai_ret = $retribusi-$nilai_ret1;
            //hitung manual
            $manRet = $this->sql_ret($this->permohonan->pendaftaran_id);
            if (!empty($manRet->v_tinjauan))
            {
                        $man_diskon = ($keringanan->v_prosentase_retribusi * 0.01) * $manRet->v_tinjauan;
                        $nilai_retManual = $manRet->v_tinjauan-$man_diskon;
            }
            else
            {
                        $man_diskon = ($keringanan->v_prosentase_retribusi * 0.01) * 0;
                        $nilai_retManual = 0-$man_diskon;
            }
            
        } else
        {
            $manRet = $this->sql_ret($this->permohonan->pendaftaran_id);
            if (!empty($manRet->v_tinjauan))
            {
            $nilai_retManual = $manRet->v_tinjauan;    
            }
            else
            {
                $nilai_retManual =" ";
            }
            
            $nilai_ret = $retribusi;
        }*/
            
        $data['retribusi'] = $nilai_ret;
        $data['id'] = $id;
        $data['m_hitung'] = $this->permohonan->trperizinan->trretribusi->m_perhitungan;
        //$data['nilaiRet'] = $nilai_retManual;
        $data['no_pendaftaran'] = $this->permohonan->pendaftaran_id;
        $data['nama_pendaftar'] = $this->permohonan->tmpemohon->n_pemohon;
        $data['no_surat'] = $this->permohonan->tmsk->no_surat;
        $data['jenis'] = $this->permohonan->trperizinan->n_perizinan;
        $data['status'] = $this->permohonan->c_status_bayar;
        $this->load->vars($data);

        /*$js =  "
                $(document).ready(function(){
                    $('#kasir').submit(function() {
                            $.ajax({
                                    type: 'POST',
                                    url: $('#kasir').attr('action'),
                                    async: false,
                                    success: function(data) {
                                        $('#money').hide();
                                        $('#print-fly').fadeIn('slow');
                                    }
                            })
                            return false;
                    });
                });
                ";*/
        $js = "";
        $this->template->set_metadata_javascript($js);

        $this->session_info['page_name'] = "Pembayaran Retribusi";
        $this->template->build('edit', $this->session_info);
    }
    
    public function sql_ret($id)
    {
        $query = "select v_tinjauan from tmproperty_jenisperizinan as a
                 inner join tmproperty_jenisperizinan_trproperty as b on a.id = b.tmproperty_jenisperizinan_id
                 inner join trproperty as c on c.id = b.trproperty_id
                 where a.pendaftaran_id = '".$id."' and c.id = '45'"; 
                 
        $hasil = $this->db->query($query);
        return $hasil->row();
    }

    public function save() {
        $this->permohonan->where('id', $this->input->post('id'))
                ->update('c_status_bayar', 1);

        //Cek Tracking Progress
        $updated = FALSE;
        $permohonan = new tmpermohonan();
        $id_permohonan = $this->input->post('id');
        $permohonan->get_by_id($id_permohonan);
        $sts_awal = new trstspermohonan();
        $sts_awal->get_by_id('13'); //Kasir [Lihat Tabel trstspermohonan()]
        $list_track = $permohonan->tmtrackingperizinan->get();
        if($list_track){
            foreach ($list_track as $data_track){
                $data_status = new tmtrackingperizinan_trstspermohonan();
                $data_status->where('tmtrackingperizinan_id', $data_track->id)
                ->where('trstspermohonan_id', $sts_awal->id)->get();
                if($data_status->tmtrackingperizinan_id){
                    $updated = TRUE;
                    break;
                }
            }
        }else{
            $updated = FALSE;
        }

        $status_izin = $permohonan->trstspermohonan->get();

        $status_skr = "13"; //Kasir [Lihat Tabel trstspermohonan()]
        /**Edited By Indra**/
		//$id_status = "14"; //Penyerahan Izin [Lihat Tabel trstspermohonan()]
		//$id_status = "17"; //Pembuatan Izin [Lihat Tabel trstspermohonan()] ->Status akan diupdate menjadi ini
        $this->load->model('permohonan/trlangkah_perizinan');
        $langkah_perizinan = new trlangkah_perizinan();
        $id_status = $langkah_perizinan->nextStep($permohonan->trperizinan->trkelompok_perizinan->id, $status_izin->id);
		/******************/

        if($status_izin->id == $status_skr){
            // Input Data Tracking Progress
            /*$sts_izin = new trstspermohonan();
            $sts_izin->get_by_id($status_skr);
            $data_status = new tmtrackingperizinan_trstspermohonan();
            $list_tracking = $permohonan->tmtrackingperizinan->get();
            if($list_tracking){
                $tracking_id = 0;
                foreach ($list_tracking as $data_track){
                    $data_status = new tmtrackingperizinan_trstspermohonan();
                    $data_status->where('tmtrackingperizinan_id', $data_track->id)
                    ->where('trstspermohonan_id', $sts_izin->id)->get();
                    if($data_status->tmtrackingperizinan_id){
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
            $this->__input_tracking_progress($id_permohonan, $status_skr, $id_status);
        }

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql2($u_ser);
        $p = $this->db->query("call log ('Pembayaran Retribusi','Pembayaran ".$permohonan->pendaftaran_id."','".$tgl."','".$u_ser."')");

        redirect('kasir/edit/'.$this->input->post('id'));
    }

    public function cetak($id = NULL) {

        // Setting app
        $this->settings = new settings();
        $this->settings->where('name', 'app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name', 'app_city')->get();

        $nama_surat = "kuitansi";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/' . $nama_surat . '.odt');

        $this->permohonan->where('id',$id)->get();
        $this->permohonan->tmpemohon->get();
        $this->permohonan->trperizinan->get();
        $this->permohonan->tmbap->get();

        $p_kelurahan = $this->permohonan->tmpemohon->trkelurahan->get();
        $p_kecamatan = $this->permohonan->tmpemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $this->permohonan->tmpemohon->trkelurahan->trkecamatan->trkabupaten->get();

        $no_daftar = str_replace('/', '', $this->permohonan->pendaftaran_id);

        //Cek Tracking Progress
        $updated = FALSE;
        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($id);
        $sts_awal = new trstspermohonan();
        $sts_awal->get_by_id('13'); //Sudah Membayar [Lihat Tabel trstspermohonan()]
        $list_track = $permohonan->tmtrackingperizinan->get();
        if($list_track){
            foreach ($list_track as $data_track){
                $data_status = new tmtrackingperizinan_trstspermohonan();
                $data_status->where('tmtrackingperizinan_id', $data_track->id)
                ->where('trstspermohonan_id', $sts_awal->id)->get();
                if($data_status->tmtrackingperizinan_id){
                    $updated = TRUE;
                    break;
                }
            }
        }else{
            $updated = FALSE;
        }

        //membuat kota
        $wilayah = new trkabupaten();
        if ($app_city->value !== '0') {
            $alamat = $this->permohonan->tmpemohon->a_pemohon . ' ' . $p_kelurahan->n_kelurahan . ', ' .
                    $p_kecamatan->n_kecamatan . ', ' . ucwords(strtolower($p_kabupaten->n_kabupaten));
            $wilayah->get_by_id($app_city->value);
            //$odf->setVars('kabupaten', ucwords(strtolower($wilayah->n_kabupaten)));
//            $odf->setVars('kota', ucwords(strtolower($wilayah->n_kabupaten)));
        } else {
            $alamat = $this->permohonan->tmpemohon->a_pemohon;
            //$odf->setVars('kabupaten', 'setempat');
//            $odf->setVars('kota', '...........');
        }

//        if (($this->permohonan->tmbap->nilai_retribusi > 250000) &&
//                ($this->permohonan->tmbap->nilai_retribusi < 1000000)) {
//            if($this->permohonan->tmbap->nilai_retribusi){
//            $odf->setVars('jumlahretribusi', 'Rp. '.$this->terbilang->nominal($this->permohonan->tmbap->nilai_retribusi + 6000, 2));
//            $odf->setVars('bilangan', $this->terbilang->terbilang($this->permohonan->tmbap->nilai_retribusi + 6000) . ' rupiah.');
//            }else{
//            $odf->setVars('jumlahretribusi', 'Rp. '.$this->terbilang->nominal(6000, 2));
//            $odf->setVars('bilangan', $this->terbilang->terbilang(6000) . ' rupiah.');
//            }
//        } else {

        $retribusi = $this->permohonan->tmbap->nilai_bap_awal;
        $keringanan = $this->permohonan->tmkeringananretribusi->get();

        /*Update 31 Mar 2014*/
        if($keringanan->id){
            $nilai_keringanan = ($keringanan->v_prosentase_retribusi * 0.01) * $retribusi;
            $nilai_ret = $retribusi - $nilai_keringanan;
        }else{
            $nilai_ret = $retribusi;
        }
        $odf->setVars('jumlahretribusi', 'Rp. '.$this->terbilang->nominal($nilai_ret));
        $odf->setVars('bilangan', "Terbilang : ".$this->terbilang->terbilang(0) . ' rupiah.');
        /****************/

        $m_hitung = $this->permohonan->trperizinan->trretribusi->m_perhitungan;
                        
            /*if ($m_hitung=="0")
            {
            if($this->permohonan->tmbap->nilai_bap_awal){
            $retribusi = $this->permohonan->tmbap->nilai_bap_awal;
            $keringanan = $this->permohonan->tmkeringananretribusi->get();
            if ($keringanan->id)
            {
                $nilai_ret1 = ($keringanan->v_prosentase_retribusi * 0.01) * $retribusi;
                $nilai_ret = $retribusi-$nilai_ret1;
            }else
            {
                $nilai_ret = $retribusi;
            }
                
            $odf->setVars('jumlahretribusi', 'Rp. '.$this->terbilang->nominal($nilai_ret));
            $odf->setVars('bilangan', "Terbilang : ".$this->terbilang->terbilang($nilai_ret) . ' rupiah.');
          
            }else{
            $odf->setVars('jumlahretribusi', 'Rp. '.$this->terbilang->nominal(0, 2));
            $odf->setVars('bilangan', "Terbilang : ".$this->terbilang->terbilang(0) . ' rupiah.');
          
  
            }
            }
            else
            {
                $keringanan = $this->permohonan->tmkeringananretribusi->get();
                $nilai = $this->sql_ret($this->permohonan->pendaftaran_id);
                if($nilai)
                {
                    if ($keringanan->id)
                {
            //hitung manual
            $manRet = $this->sql_ret($this->permohonan->pendaftaran_id);
            $man_diskon = ($keringanan->v_prosentase_retribusi * 0.01) * $manRet->v_tinjauan;
            $nilai_retManual = $manRet->v_tinjauan-$man_diskon;
            
                } else
                {
            $manRet = $this->sql_ret($this->permohonan->pendaftaran_id);
            $nilai_retManual = $manRet->v_tinjauan;
           // $nilai_ret = $retribusi;
                }
                    
                    $odf->setVars('jumlahretribusi', 'Rp. '.$this->terbilang->nominal($nilai_retManual));
                     $odf->setVars('bilangan', "Terbilang : ".$this->terbilang->terbilang($nilai_retManual) . ' rupiah.');
                 
                }
                else
                {
                    $odf->setVars('jumlahretribusi', 'Rp. '.$this->terbilang->nominal(0, 2));
            $odf->setVars('bilangan', "Terbilang : ".$this->terbilang->terbilang(0) . ' rupiah.');
        
                }
            }*/
//        }

        $tgl_skr = $this->lib_date->get_date_now();
        $odf->setVars('tanggal',$this->lib_date->mysql_to_human($tgl_skr));
        //$odf->setVars('urut', $this->permohonan->pendaftaran_id);
        //Penomoran Kwitansi
        $no_daftar = $this->permohonan->pendaftaran_id;
        $data_izin = $this->permohonan->trperizinan->id;
        $data_unit = $this->permohonan->trperizinan->trunitkerja->get();
        $unit_kerja = $data_unit->n_unitkerja;
//        $i_izin = strlen($data_izin);
//        for($i=3;$i>$i_izin;$i--){
//            $data_izin = "0".$data_izin;
//        }
//        $data_izin = 'K'.$data_izin;
//        $no_kwitansi = substr($no_daftar, 0, 6).$data_izin.substr($no_daftar, 9, 11);
        if($data_izin == 1 || $data_izin == 89){
            $no_rek = "004.111.000422";
            $ayat = "122.023";
        }
        else if($data_izin == 2 || $data_izin == 3 || $data_izin == 88){
            $no_rek = "004.111.000420";
            $ayat = "122.026";
        }
        else if($data_izin == 14){
            $no_rek = "004.111.000428";
            $ayat = "122.028";
        }else{
            $no_rek = "";
            $ayat = "";
        }
        $odf->setVars('atas_nama', $unit_kerja);
        $odf->setVars('no_rek', $no_rek);
        $odf->setVars('ayat', $ayat);
        $odf->setVars('nama_izin', $this->permohonan->trperizinan->n_perizinan);
        $odf->setVars('no_daftar', $this->permohonan->pendaftaran_id);
        $odf->setVars('nama', $this->permohonan->tmpemohon->n_pemohon);
        $odf->setVars('alamat', $this->permohonan->tmpemohon->a_pemohon);        
        $odf->exportAsAttachedFile($nama_surat . '_' . $no_daftar . '.odt');
    }

    public function sql2($u_ser)
    {
        $query = "select a.description
	from user_auth as a
	inner join user_user_auth as  x on a.id = x.user_auth_id
	inner join user as b on b.id = x.user_id
        where b.id = (select id from user where username='".$u_ser."')";
        $hasil = $this->db->query($query);
        return $hasil->row();
    }

}

// This is the end of kasir class
