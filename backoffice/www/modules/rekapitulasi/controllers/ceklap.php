<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of monitoring class
 *
 * @author  Yogi Cahyana
 * @since   2011
 *
 */
class Ceklap extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();

        $this->pemohon = new tmpemohon();
        $this->permohonan = new tmpermohonan();
        $this->stspermohonan = new trstspermohonan();
        $this->perizinan = new trperizinan();
        $this->propinsi = new trpropinsi();
        $this->kabupaten = new trkabupaten();
        $this->kecamatan = new trkecamatan();
        $this->kelurahan = new trkelurahan();
        $this->monitoringkecamatan = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->monitoringkecamatan = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '10') {
                $enabled = TRUE;
                $this->monitoringkecamatan = new user_auth();
            }
        }
		
        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
//        $data['listpemohon'] = $this->pemohon->limit(0)->get();
//        $data['list_ijin'] = $this->perizinan->order_by('id','ASC')->get();
//        $data = $this->_funcwilayah();

         $js =  "
                $(document).ready(function() {
                        oTable = $('#realisasi').dataTable({
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

                function finishAjax(id, response){
                  $('#'+id).html(unescape(response));
                  $('#'+id).fadeIn();
                }
            ";

        $data['error']="";
        $this->template->set_metadata_javascript($js);
        $data['listpermohonan'] = $this->permohonan
                ->where('c_pendaftaran', 1) //Pendaftaran selesai
                //->where('c_izin_selesai', 0) //SK Belum diserahkan
                //->where('c_izin_dicabut', 0) //Permohonan tidak dicabut
                ->order_by('id', 'DESC')->limit(0)->get();
        $this->load->vars($data);

        $this->session_info['page_name'] = "Rekapitulasi Tinjauan Lapangan";
        $this->template->build('list_ceklap', $this->session_info);
    }

     public function filterData() {
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


        $js =  "
                $(document).ready(function() {
                        oTable = $('#realisasi2').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );

                ";

        $this->template->set_metadata_javascript($js);
        $this->load->vars($data);
        
        $this->session_info['page_name'] = "Rekapitulasi Tinjauan Lapangan";
        $this->template->build('view_ceklap', $this->session_info);
    }

     public function cetak($tgla = null, $tglb = null) {
        $this->settings = new settings();
        $this->settings->where('name','app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name','app_city')->get();

//        $permohonan = new tmpermohonan();
//        $p_kelurahan = $permohonan->tmpemohon->trkelurahan->get();
//        $p_kecamatan = $permohonan->tmpemohon->trkelurahan->trkecamatan->get();
//        $p_kabupaten = $permohonan->tmpemohon->trkelurahan->trkecamatan->trkabupaten->get();

        //path of the template file
        $nama_surat = "cetak_cek_lapangan";
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
        $odf->setVars('title','Laporan Hasil Cek Lapangan Periode '.$this->lib_date->mysql_to_human($tgla).' s/d '.$this->lib_date->mysql_to_human($tglb));

        $i = NULL;
        $query_data = "SELECT A.id, A.pendaftaran_id, A.d_terima_berkas, A.d_survey, A.a_izin,
        C.id idizin, C.n_perizinan, E.n_pemohon, E.a_pemohon
        FROM tmpermohonan as A
        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
        WHERE A.c_pendaftaran = 1
        AND A.c_tinjauan = 1
        
        AND A.d_terima_berkas between '$tgla' and '$tglb'
        order by A.id DESC";
        $hasil_data = mysql_query($query_data);
        $jumlah = mysql_num_rows(@$hasil_data);
        if($jumlah==0)
        {
           $this->error();
        }
        else
        {
            
        while ($data = mysql_fetch_assoc(@$hasil_data)){
            $i++;
            $n_perusahaan = NULL;
            $query_data2 = "SELECT a.n_perusahaan
            FROM tmperusahaan a, tmpermohonan_tmperusahaan b
            WHERE b.tmpermohonan_id = '".$data['id']."'
            AND a.id = b.tmperusahaan_id";
            $hasil_data2 = mysql_query($query_data2);
            $jml_perusahaan = mysql_num_rows(@$hasil_data2);
            $rows_data2 = mysql_fetch_object(@$hasil_data2);
            if ($jml_perusahaan) $n_perusahaan = $rows_data2->n_perusahaan;
            else $n_perusahaan = "-";

            $listeArticles3 = array(
                    array(	'property' =>  $i,
                            'content1' =>  $data['pendaftaran_id'],//$data->n_perizinan,
                            'content2' =>  $this->lib_date->mysql_to_human($data['d_terima_berkas']),
                            'content6' =>  $this->lib_date->mysql_to_human($data['d_survey']),
                            'content3' =>  $data['n_pemohon'].' '.$data['a_pemohon'],
                            'content4' =>  $data['n_perizinan'],
                            'content5' =>  $n_perusahaan.' '.$data['a_izin'],
                    ),

            );

            $article3 = $odf->setSegment('articles3');
            foreach($listeArticles3 AS $element) {

                    $article3->titreArticle3($element['property']);
                    $article3->texteArticle1($element['content1']);
                    $article3->texteArticle2($element['content2']);
                    $article3->texteArticle3($element['content3']);
                    $article3->texteArticle4($element['content4']);
                    $article3->texteArticle5($element['content5']);
                    $article3->texteArticle6($element['content6']);
                    $article3->merge();
            }
        }
        $odf->mergeSegment($article3);

        //export the file
        $odf->exportAsAttachedFile($nama_surat.'.odt');

        }
   }

    public function error() {
//        $data['listpemohon'] = $this->pemohon->limit(0)->get();
//        $data['list_ijin'] = $this->perizinan->order_by('id','ASC')->get();
//        $data = $this->_funcwilayah();

         $js =  "
                $(document).ready(function() {
                        oTable = $('#realisasi').dataTable({
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

                function finishAjax(id, response){
                  $('#'+id).html(unescape(response));
                  $('#'+id).fadeIn();
                }
            ";
        $data['error']="Tidak ada data...";
        $this->template->set_metadata_javascript($js);
        $data['listpermohonan'] = $this->permohonan
                ->where('c_pendaftaran', 1) //Pendaftaran selesai
                //->where('c_izin_selesai', 0) //SK Belum diserahkan
               // ->where('c_izin_dicabut', 0) //Permohonan tidak dicabut
                ->order_by('id', 'DESC')->limit(0)->get();
        $this->load->vars($data);

        $this->session_info['page_name'] = "Rekapitulasi Tinjauan Lapangan";
        $this->template->build('list_ceklap', $this->session_info);
    }


}

// This is the end of monitoring class
