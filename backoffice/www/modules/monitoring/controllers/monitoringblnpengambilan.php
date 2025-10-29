<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of monitoring class
 *
 * @author  Yogi Cahyana dan zulfah
 * @since   1.0
 *
 */

class Monitoringblnpengambilan extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();

        $this->pemohon = new tmpemohon();
        $this->permohonan = new tmpermohonan();
        $this->perijinan = new trperizinan();
        $this->monitoringblnpengambilan = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->monitoringblnpengambilan = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '2') {
                $enabled = TRUE;
                $this->monitoringblnpengambilan = new user_auth();
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {

        $data['listpemohon'] = $this->pemohon->limit(0)->get();
        $data['listpermohonan'] = $this->permohonan->limit(0)->get();
        $data['list_ijin'] = $this->perijinan->order_by('id','ASC')->get();


        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#monitoring').dataTable({
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
        $this->session_info['page_name'] = "Monitoring Per Bulan Pengambilan Izin";
        $this->template->build('listbulanpengambilan', $this->session_info);
    }


      public function getPerbulan() {
        $tgla = $this->input->post('tgla');
        $tglb = $this->input->post('tglb');

        $data['listpemohon'] = $this->pemohon->get();
        $data['listpermohonan'] = $this->permohonan->where("d_terima_berkas BETWEEN '$tgla' AND '$tglb'")->get();

        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#monitoring').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Monitoring Per Bulan Pengambilan Izin";
        $this->template->build('view_bulanpengambilan', $this->session_info);
    }
 public function cetak_blnpengambilan($tgla = NULL, $tglb = NULL) {
        $this->settings = new settings();
        $this->settings->where('name','app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name','app_city')->get();
        $permohonan = new tmpermohonan();
    

        $p_kelurahan = $permohonan->tmpemohon->trkelurahan->get();
        $p_kecamatan = $permohonan->tmpemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $permohonan->tmpemohon->trkelurahan->trkecamatan->trkabupaten->get();

        //path of the template file
        $nama_surat = "cetak_monitoring_generic";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/'.$nama_surat.'.odt');
        $odf->setImage('header', 'assets/css/' . $app_folder . '/images/dinas_1.jpg', '17.5', '3.5');

        //membuat kota
        $wilayah = new trkabupaten();
        if ($app_city->value !== '0') {
            $alamat =  $permohonan->tmpemohon->a_pemohon . ' ' . $p_kelurahan->n_kelurahan . ', ' .
                    $p_kecamatan->n_kecamatan . ', ' . ucwords(strtolower($p_kabupaten->n_kabupaten));
            $wilayah->get_by_id($app_city->value);
            //$odf->setVars('kabupaten', ucwords(strtolower($wilayah->n_kabupaten)));
            $odf->setVars('kota', ucwords(strtolower($wilayah->n_kabupaten)));
        } else {
            $alamat =  $permohonan->tmpemohon->a_pemohon;
            //$odf->setVars('kabupaten', 'setempat');
            $odf->setVars('kota', '...........');
        }


        $odf->setVars('tanggal', $this->lib_date->mysql_to_human(date('YYYY/mm/dd')));
        $odf->setVars('title','Monitor Per Pengambil Bulan Izin');

        $i = 1;
      
                            $list_mohon = new tmpermohonan();
                            $list_mohon->where("d_terima_berkas BETWEEN '$tgla' AND '$tglb'")->get();
                           foreach($list_mohon as $data){
                            $data->tmpemohon->get();
                            $data->tmpemohon->trkelurahan->get();
                            $data->trstspermohonan->get();
                            $data->trperizinan->get();

                  $listeArticles3 = array(
                array(	'property' =>  $i,
                        'content1' =>  $data->pendaftaran_id,//$data->n_perizinan,
                        'content2' =>  $data->trperizinan->n_perizinan,
                        'content3' =>  $this->lib_date->mysql_to_human($data->d_entry),
                        'content4' =>  $data->tmpemohon->n_pemohon,
                        'content5' =>  $data->trstspermohonan->n_sts_permohonan,
                        'content6' =>  $data->tmpemohon->a_pemohon,
                        'content7' =>  $data->tmpemohon->trkelurahan->n_kelurahan,

                ),

        );

        $article3 = $odf->setSegment('articles3');
        foreach($listeArticles3 AS $element) {

                $article3->titreArticle3($element['property']);
                $article3->texteArticle3($element['content1']);
                $article3->texteArticle4($element['content2']);
                $article3->texteArticle5($element['content3']);
                $article3->texteArticle6($element['content4']);
                $article3->texteArticle7($element['content5']);
                $article3->texteArticle8($element['content6']);
                $article3->texteArticle9($element['content7']);
                $article3->merge();
        }

        $i++;
        }
      
     
        $odf->mergeSegment($article3);

        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat.'_'.$no_daftar.'.odt');
    }

}

// This is the end of monitoring class
