<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of penjadwalan
 *
 * @author Eva
 */
class Izin extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->permohonan      = new tmpermohonan();
        $this->status    = new trstspermohonan();
        $this->perizinan = new trperizinan();
        $this->mohonstatus = new tmpermohonan_trstspermohonan();
        $this->izin = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->izin = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '10') {
                $enabled = TRUE;
                $this->izin = new user_auth();
            }
        }
		
        if(!$enabled) {
            redirect('dashboard');
        }*/

    }

    public function index() {


        $data['range'] = '';

//        $data['list_tahun'] = $this->permohonan->limit(0)->group_by('d_tahun','ASC')->get();
        $data['list'] = $this->perizinan->limit(0)->get();
//        $data['jum1'] = $this->mohonstatus->where('trstspermohonan_id',13)->count();
//        $data['jum3'] = $this->mohonstatus->where('trstspermohonan_id',14)->count();


        $this->load->vars($data);

        $js =  "
                 $(document).ready(function() {
                    $(\"#tabs\").tabs();

                    $('a[rel*=pendaftar_box]').facebox();
                    $('a[rel*=perusahaan_box]').facebox();
                } );

                $(document).ready(function() {
                        oTable = $('#izin').dataTable({
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

        $this->session_info['page_name'] = "Rekapitulasi Perizinan";
        $this->template->build('izin_list', $this->session_info);
    }

    public function rekap() {
        $tgla = $this->input->post('tgla');
        $tglb = $this->input->post('tglb');
        $now = $this->lib_date->get_date_now();
        $tgl_before = date("Y-m-d", mktime(0, 0, 0, date("m"), 1, date("Y")));
        $tgl_after = date("Y-m-d", mktime(0, 0, 0, date("m")+1, 0, date("Y")));

        if($tgla && $tglb){
            $data['tgla'] = $tgla;
            $data['tglb'] = $tglb;
        }else{
            $tgla = $tgl_before;
            $tglb = $tgl_after;
            $data['tgla'] = $tgla;
            $data['tglb'] = $tglb;
        }
//        $query = $this->perizinan->get();
//        $data['list'] = $query;

        $this->load->vars($data);
        $js =  "
                $(document).ready(function() {
                        oTable = $('#realisasi').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );

                ";

        $this->template->set_metadata_javascript($js);

        $this->session_info['page_name'] = "Rekapitulasi Perizinan";
        $this->template->build('izin_rekap', $this->session_info);

        }

    public function filter() {
        $permohonan = new tmpermohonan();
        $izin = new trperizinan();
        $permohonan->where_join_field('tmpermohonan_trperizinan','trperizinan_id')
                   ->where('c_izin_selesai',1)->get();

        $periodeakhir = $this->input->post('periodeakhir');
        $periodeawal = $this->input->post('periodeawal');
        $s = $permohonan->where("d_perpanjangan BETWEEN '$periodeawal' AND '$periodeakhir'")->get();
        $data['periodeakhir'] = $this->input->post('periodeakhir');
        $data['periodeawal'] = $this->input->post('periodeawal');
        $data['list'] = $s->$izin->get();

        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#izin').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );

                $(function() {
                $(\".tarif\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
                ";

        $this->template->set_metadata_javascript($js);

        $this->session_info['page_name'] = "Rekapitulasi Perizinan";
        $this->template->build('view_izin', $this->session_info);
    }

    public function view() {
        $permohonan = new tmpermohonan();
        $perizinan = new trperizinan();
        $status = new trstspermohonan();
        $data['periodeakhir'] = $this->input->post('periodeakhir');
        $data['periodeawal']  = $this->input->post('periodeawal');
        $data['range'] = '';

        $periodeakhir = $this->input->post('periodeakhir');
        $periodeawal  = $this->input->post('periodeawal');
        $data['list_tahun'] = $this->izin->group_by('d_tahun','ASC')->get();
        $data['list'] = $this->perizinan->get();
        $data['listlist']=$permohonan->where("d_entry BETWEEN '$periodeawal' AND '$periodeakhir'")->get();
        $data['jum1']  = $this->status->where('id',14)->get();
        $data['jum13'] = $this->status->where('id', 13)->count();
        $data['jum14'] = $this->status->where('id', 14)->count();


        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#izin').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );

                $(function() {
                $(\".tarif\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
                ";

        $this->template->set_metadata_javascript($js);

        $this->session_info['page_name'] = "Rekapitulasi Perizinan";
        $this->template->build('view_izin', $this->session_info);
    }

    public function datalist() {

        $this->izin->get();
        $this->izin->set_json_content_type();
        echo $this->izin->json_for_data_table();

    }

     public function cetak($tgla = null, $tglb = null) {

        $this->settings = new settings();
        $this->settings->where('name','app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name','app_city')->get();

        //$listeArticles3 = NULL;
//        $permohonan = new tmpermohonan();

        //$permohonan->get_by_id($id);
//        if($this->input->post('periodeawal')){
//        $awal = $this->input->post('periodeawal');
//        }else
//        {$awal =date('YYYY/mm/dd');}
//
//       if($this->input->post('periodeakhir')){
//       $akhir = $this->input->post('periodeakhir');
//       }else
//       {$akhir = date('YYYY/mm/dd');}
//
//        $list = $this->perizinan->get();// $permohonan->where("d_entry BETWEEN '$awal' AND '$akhir'")->get();
//        $p_kelurahan = $permohonan->tmpemohon->trkelurahan->get();
//        $p_kecamatan = $permohonan->tmpemohon->trkelurahan->trkecamatan->get();
//        $p_kabupaten = $permohonan->tmpemohon->trkelurahan->trkecamatan->trkabupaten->get();

        //path of the template file
        $nama_surat = "cetak_rekapizin";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/'.$nama_surat.'.odt');
       // $odf->setImage('header', 'assets/css/' . $app_folder . '/images/dinas_1.jpg', '17.5', '4.5');
        $odf->setVars('rangeawal',$this->lib_date->mysql_to_human($tgla));
        $odf->setVars('rangeakhir',$this->lib_date->mysql_to_human($tglb));
//        $odf->setImage('header', 'assets/css/' . $app_folder . '/images/dinas_1.jpg', '17.5', '3.5');

 //logo
        $this->tr_instansi = new Tr_instansi();
        $logo = $this->tr_instansi->get_by_id(14);
        $odf->setImage('logo', 'uploads/logo/' . $logo->value, '1.7', '1.7');

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
//            $alamat =  $permohonan->tmpemohon->a_pemohon . ' ' . $p_kelurahan->n_kelurahan . ', ' .
//                    $p_kecamatan->n_kecamatan . ', ' . ucwords(strtolower($p_kabupaten->n_kabupaten));
            $wilayah->get_by_id($app_city->value);
            //$odf->setVars('kabupaten', ucwords(strtolower($wilayah->n_kabupaten)));
            $odf->setVars('kota', ucwords(strtolower($wilayah->ibukota)));
        } else {
//            $alamat =  $permohonan->tmpemohon->a_pemohon;
            //$odf->setVars('kabupaten', 'setempat');
            $odf->setVars('kota', '...........');
        }

        $gede_kota=strtoupper($wilayah->n_kabupaten);
        $kecil_kota=ucwords(strtolower($wilayah->n_kabupaten));
        $odf->setVars('kota4', $gede_kota);

        //alamat
        $this->tr_instansi = new Tr_instansi();
        $alamat = $this->tr_instansi->get_by_id(12);
        $odf->setVars('alamat', ucwords(strtolower($alamat->value)).' - '.$kecil_kota);
        


        $tgl_skr = $this->lib_date->get_date_now();
        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($tgl_skr));
        $i = NULL;
        $query_data = "select id, n_perizinan, v_perizinan from trperizinan";
        $results = mysql_query($query_data);
        while ($data = mysql_fetch_assoc(@$results)){
            $i++;
            $jumlah_masuk = 0;
            $jumlah_terbit = 0;
            $terbit_ambil = 0;
            $terbit_proses = 0;
            $jumlah_tolak = 0;
            $tolak_ambil = 0;
            $tolak_proses = 0;

            $jumlah_proses = 0;

            $query = "select a.id jumlah from tmpermohonan a
                     inner join tmpermohonan_trperizinan b on a.id = b.tmpermohonan_id
                     where b.trperizinan_id = '".$data['id']."'
                     and a.d_terima_berkas between '$tgla' and '$tglb'";
            $hasil_data = mysql_query($query);
            $jumlah_masuk = mysql_num_rows(@$hasil_data);
            $query2 = "select a.id, a.c_izin_selesai, a.c_penetapan, d.status_bap from tmpermohonan a
                    inner join tmpermohonan_trperizinan b on a.id = b.tmpermohonan_id
                    inner join tmbap_tmpermohonan c on a.id = c.tmpermohonan_id
                    inner join tmbap d on d.id = c.tmbap_id
                     where b.trperizinan_id = '".$data['id']."'
                     and a.d_terima_berkas between '$tgla' and '$tglb'";
            $hasil_data2 = mysql_query($query2);
            while ($rows_data2 = mysql_fetch_assoc(@$hasil_data2)){
                if($rows_data2['c_penetapan'] == "1"){
                    $jumlah_terbit++;
                    if($rows_data2['c_izin_selesai'] == "1") $terbit_ambil++;
                    else $terbit_proses++;
                }else if($rows_data2['status_bap'] == "2"){
                    $jumlah_tolak++;
                    if($rows_data2['c_izin_selesai'] == "1") $tolak_ambil++;
                    else $tolak_proses++;
                }
            }
            $jumlah_proses = $jumlah_masuk - ($terbit_ambil + $tolak_ambil);

        $listeArticles3 = array(
                array(	'property' =>$i,
                        'content' =>  $data['n_perizinan'],
                        'content1' => $jumlah_masuk,
                        'content2' =>  $jumlah_terbit,
                        'content3' =>  $terbit_ambil,
                        'content4' =>  $terbit_proses,
                        'content5' =>  $jumlah_tolak,
                        'content6' =>  $tolak_ambil,
                        'content7' =>  $tolak_proses,
                        'content8' =>  $jumlah_proses,
                ),

        );

       // if($listeArticles3){

        $article3 = $odf->setSegment('articles3');
        foreach($listeArticles3 AS $element) {

                $article3->titreArticle3($element['property']);
                $article3->texteArticle3($element['content']);
                $article3->texteArticle4($element['content1']);
                $article3->texteArticle5($element['content2']);
                $article3->texteArticle6($element['content3']);
                $article3->texteArticle7($element['content4']);
                $article3->texteArticle8($element['content5']);
                $article3->texteArticle9($element['content6']);
                $article3->texteArticle10($element['content7']);
                $article3->texteArticle11($element['content8']);
                $article3->merge();
        }

        }
    
        $odf->mergeSegment($article3);
    
        //export the file
        $odf->exportAsAttachedFile($nama_surat.'.odt');
    }

      public function pick_pendaftar_list($idizin = NULL) {
        $ss = $this->perizinan->where('id',$idizin)->get();
        $data['page_name'] ='<b>'.$ss->n_perizinan.'</b>' ;

        $data['list'] = $ss;

        $this->load->vars($data);
        $this->load->view('listpendaftaran_load', $data);
    }

      public function pick_pendaftar2_list($idizin = NULL) {
        $ss = $this->perizinan->where('id',$idizin)->get();
        $data['page_name'] ='<b>'.$ss->n_perizinan.'</b>' ;

        $data['list'] = $ss;

        $this->load->vars($data);
        $this->load->view('listpendaftaran2_load', $data);
    }

         public function pick_pendaftar3_list($idizin = NULL) {
        $ss = $this->perizinan->where('id',$idizin)->get();
        $data['page_name'] ='<b>'.$ss->n_perizinan.'</b>' ;

        $data['list'] = $ss;

        $this->load->vars($data);
        $this->load->view('listpendaftaran3_load', $data);
    }



}
