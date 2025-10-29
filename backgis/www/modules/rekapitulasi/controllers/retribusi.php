<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of penjadwalan
 *
 * @author Eva & Yogi Cahyana
 */
class Retribusi extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->permohonan = new tmpermohonan();
        $this->status = new trstspermohonan();
        $this->perizinan = new trperizinan();
        $this->retribusi = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->retribusi = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '10') {
                $enabled = TRUE;
                $this->retribusi = new user_auth();
            }
        }
		
        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
//        $retribusi = new tmpermohonan();
//
//        $retribusi->limit(0)->get();
//        $retribusi->trperizinan->get();
//        $retribusi->trstspermohonan->get();
//        $status = new trstspermohonan();
//
//
//        $data['list_tahun'] = $retribusi->group_by('d_tahun','ASC')->limit(0)->get();
      
        $data['list'] = $this->perizinan->limit(0)->get();
      
        


        $this->load->vars($data);

        $js =  "
                 $(document).ready(function() {
                    $(\"#tabs\").tabs();

                    $('a[rel*=pendaftar_box]').facebox();
                    $('a[rel*=perusahaan_box]').facebox();
                } );

                 $(function() {
                $(\".tarif\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
                });
                $(document).ready(function() {
                        oTable = $('#retribusi').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);

        $this->session_info['page_name'] = "Rekapitulasi Retribusi";
        $this->template->build('retribusi_list', $this->session_info);
    }
    public function FilterData() {
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

        $this->load->vars($data);

        $js =  "
                 $(document).ready(function() {
                    $(\"#tabs\").tabs();

                    $('a[rel*=pendaftar_box]').facebox();
                    $('a[rel*=perusahaan_box]').facebox();
                } );

                 $(function() {
                $(\".tarif\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
                });
                $(document).ready(function() {
                        oTable = $('#retribusi').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);

        $this->session_info['page_name'] = "Rekapitulasi Retribusi";
        $this->template->build('view_retribusi', $this->session_info);
    }


    public function filter() {
        $retribusi = new tmpermohonan();
        $retribusi->where('d_tahun',$this->input->post('d_tahun'))->get();
        $retribusi->trperizinan->group_by('id')->get();
        $retribusi->trstspermohonan->get();


        $data['list_tahun'] = $this->permohonan->group_by('d_tahun','ASC')->get();
        $data['list'] = $retribusi->where_join_field('tmpermohonan_trperizinan','trperizinan_id')->get();
     

        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#retribusi').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";


        $this->session_info['page_name'] = "Lihat berdasarkan Data Entry";
        $this->template->build('retribusi_list', $this->session_info);
    }

    public function view() {

        $perizinan = new trperizinan();
        $status = new trstspermohonan();
        $retribusi = new tmpermohonan();
        $data['range'] = '';

        $data['list'] = $retribusi->where_join_field('tmpermohonan_trperizinan','trperizinan_id')->get();
        $data['list_tahun'] = $retribusi->group_by('d_tahun','ASC')->get();
    

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
        $this->template->build('view_rekapretribusi', $this->session_info);
    }
    public function datalist() {

        $this->permohonan->get();
        $this->permohonan->set_json_content_type();
        echo $this->permohonan->json_for_data_table();

    }

    public function cetak($tgla = null, $tglb = null) {

        $this->settings = new settings();
        $this->settings->where('name','app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name','app_city')->get();

//        $permohonan = new tmpermohonan();
//        $retribusi = new trretribusi();
//
//        // Penanggalan
//        if($tgla){
//        $awal = $tgla;
//        }else
//        {$awal =date('YYYY/mm/dd');}
//
//       if($tglb){
//       $akhir = $tglb;
//       }else
//       {$akhir = date('YYYY/mm/dd');}
//
//        //$permohonan->get_by_id($id);
//        $list = $this->perizinan->get();
//
//
//        $p_kelurahan = $permohonan->tmpemohon->trkelurahan->get();
//        $p_kecamatan = $permohonan->tmpemohon->trkelurahan->trkecamatan->get();
//        $p_kabupaten = $permohonan->tmpemohon->trkelurahan->trkecamatan->trkabupaten->get();

        //path of the template file
        $nama_surat = "cetak_rekapretribusi";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/'.$nama_surat.'.odt');
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
//            $alamat = $permohonan->tmpemohon->a_pemohon . ' ' . $p_kelurahan->n_kelurahan . ', ' .
//                    $p_kecamatan->n_kecamatan . ', ' . ucwords(strtolower($p_kabupaten->n_kabupaten));
            $wilayah->get_by_id($app_city->value);
            $odf->setVars('kota', ucwords(strtolower($wilayah->ibukota)));
        } else {
//            $alamat = $permohonan->tmpemohon->a_pemohon;
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
        $odf->setVars('periode','Rekapitulasi Retribusi Periode '.$this->lib_date->mysql_to_human($tgla).' s/d '.$this->lib_date->mysql_to_human($tglb));

        $i = NULL;
        $query_data = "select a.id, a.n_perizinan, a.v_perizinan
                    from trperizinan a, trkelompok_perizinan_trperizinan b
                    where b.trkelompok_perizinan_id = 4 /*Izin Bertarif*/
                    and a.id = b.trperizinan_id";
        $hasil_data = mysql_query($query_data);
        while ($data = mysql_fetch_assoc(@$hasil_data)){
            $i++;
            $izin_jadi = 0;
            $retribusi = 0;
            $terbayar = 0;
            $terhutang = 0;
            $query = "select a.id, a.c_status_bayar bayar, d.nilai_retribusi retribusi from tmpermohonan a
                    inner join tmpermohonan_trperizinan b on a.id = b.tmpermohonan_id
                    inner join tmbap_tmpermohonan c on a.id = c.tmpermohonan_id
                    inner join tmbap d on d.id = c.tmbap_id
                     where b.trperizinan_id = '".$data['id']."' and d.c_penetapan = 1 and d.status_bap = 1
                     and a.d_terima_berkas between '$tgla' and '$tglb'";
            $results = mysql_query($query);
            while ($rows = mysql_fetch_assoc(@$results)){
                $izin_jadi++;
                $nilai_ret = 0;
                $query2 = "select a.v_prosentase_retribusi persen
                         from tmkeringananretribusi a, tmkeringananretribusi_tmpermohonan b
                         where b.tmpermohonan_id = '".$rows['id']."'
                         and a.id = b.tmkeringananretribusi_id";
                $hasil_data2 = mysql_query($query2);
                $count_data = mysql_num_rows(@$hasil_data2);
                $data2 = mysql_fetch_object(@$hasil_data2);
                if ($count_data)
                    $nilai_ret = ($data2->persen * 0.01) * $rows['retribusi'];
                else
                    $nilai_ret = $rows['retribusi'];
                $retribusi = $retribusi + $nilai_ret;
                if($rows['bayar'] == "1") $terbayar = $terbayar + $nilai_ret;
                else $terhutang = $terhutang + $nilai_ret;
            }
                    
        $listeArticles = array(
                array(	'content1' => $i,
                        'content2' => $data['n_perizinan'],
                        'content3' => $izin_jadi,
                        'content4' => 'Rp. '. $this->terbilang->nominal($retribusi).',00',
                        'content5' => 'Rp. '. $this->terbilang->nominal($terbayar).',00',
                        'content6' => 'Rp. '. $this->terbilang->nominal($terhutang).',00',
                ),
        );

        $article = $odf->setSegment('articles');
        foreach($listeArticles AS $element) {
                $article->titreArticle1($element['content1']);
                $article->texteArticle2($element['content2']);
                $article->texteArticle3($element['content3']);
                $article->texteArticle4($element['content4']);
                $article->texteArticle5($element['content5']);
                $article->texteArticle6($element['content6']);
                $article->merge();
        }
        }
        $odf->mergeSegment($article);
        
        //export the file
        $odf->exportAsAttachedFile($nama_surat.'.odt');
    }

      public function pick_retribusi_list($idizin = NULL) {
        $ss = $this->perizinan->where('id',$idizin)->get();
        $data['page_name'] ='<b>'.$ss->n_perizinan.'</b>' ;

        $data['list'] = $ss;

        $this->load->vars($data);
        $this->load->view('listretribusi_load', $data);
    }

}
