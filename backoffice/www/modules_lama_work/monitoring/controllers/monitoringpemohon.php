<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of monitoring class
 *
 * @author  Yogi Cahyana dan zulfah
 * @since   1988
 *
 */
class Monitoringpemohon extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();

        $this->pemohon = new tmpemohon();
        $this->permohonan = new tmpermohonan();
        $this->perizinan = new trperizinan();
        $this->monitoringpemohon = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->monitoringpemohon = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '2') {
                $enabled = TRUE;
                $this->monitoringpemohon = new user_auth();
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
        $stspermohonan = new trstspermohonan();
        $data['liststspermohonan'] = $stspermohonan->get();

        $data['listpemohon'] = $this->pemohon->limit(1000)->get();
        $data['listpermohonan'] = $this->permohonan->limit(100)->get();
        $data['list_ijin'] = $this->pemohon->limit(1000)->order_by('id', 'DESC')->get();

        $this->load->vars($data);

        $this->session_info['page_name'] = "Monitoring Per Pemohon";
        $this->template->build('listpemohon', $this->session_info);
    }

    public function filterdata() {
        $this->pemohon->where('id', $this->input->post('namapemohon'))->get();
        $data['$listpemohon'] = $this->pemohon->order_by('id', 'ASC')->get();

        $data['listpermohonan'] = $this->pemohon->tmpermohonan->get($this->pemohon);
        $this->load->vars($data);
        $this->session_info['page_name'] = "Monitoring Per Pemohon : " . $this->input->post('namapemohon');
        $this->template->build('listpemohon', $this->session_info);
    }

    public function cetak_monitoring_pemohon() {
        $this->settings = new settings();
        $this->settings->where('name','app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name','app_city')->get();

        $permohonan = new tmpermohonan();
        
        $p_kelurahan = $permohonan->tmpemohon->trkelurahan->get();
        $p_kecamatan = $permohonan->tmpemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $permohonan->tmpemohon->trkelurahan->trkecamatan->trkabupaten->get();

        //path of the template file
        $nama_surat = "cetak_monitoring_pemohon";
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
         $odf->setVars('title','Monitoring Per Pemohon');

        $i = 1;


                        $relasi = new tmpemohon_tmpermohonan();
                        $list_relasi = $relasi->where('tmpemohon_id',$this->input->post('pemohon'))->get();
                        if($relasi->tmpemohon_id){
                        foreach ($list_relasi as $data_relasi){
                            $data = new tmpermohonan();
                            $data->where('id',$data_relasi->tmpermohonan_id)->get();
                            $data->tmpemohon->get();
                            $data->tmpemohon->trkelurahan->get();
                            $data->trstspermohonan->get();
                            $data->trperizinan->get();

                            
        $listeArticles3 = array(
                array(	'property' =>  $i,
                        'content1' =>  $data->pendaftaran_id,//$data->n_perizinan,
                        'content2' =>  $data->trperizinan->n_perizinan,
                        'content3' =>  $this->lib_date->mysql_to_human($data->d_terima_berkas),
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
      }else{
           $listeArticles3 = array(
                array(	'property' =>  " ",
                        'content1' =>  " ",//$data->n_perizinan,
                        'content2' =>  " ",
                        'content3' =>  " ",
                        'content4' =>  " ",
                        'content5' =>  " ",
                        'content6' =>  " ",
                        'content7' =>  " ",

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
      }
        $odf->mergeSegment($article3);

        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat.'_'.$no_daftar.'.odt');
    }


    public function selector($id_pemohon = NULL) {
        $iDisplayStart = NULL;
        $iSortCol_0 = NULL;
        $sEcho = 88;
        $iFilteredTotal = NULL;

        if (isset ($_GET['sEcho'])) {
            $sEcho = intval($_GET['sEcho']);
        }

        // Where we have to start?
        if (isset ($_GET['iDisplayStart'])) {
            $iDisplayStart = $this->input->get('iDisplayStart');
        }


        // How much?
        if (isset ($_GET['iSortCol_0'])) {
            $iSortCol_0 = $this->input->get('iSortCol_0');
        }

        // Do we must search something?? NO!!
        if($this->input->get('sSearch') !== "") {

        }

        $this->pemohon->where('id', $id_pemohon);
        $this->pemohon->get($iDisplayStart, $iSortCol_0);

        $iTotal = $this->pemohon->tmpermohonan->count();
        $lists = $this->pemohon->tmpermohonan->get();

        $iFilteredTotal = $iTotal;
        $sOutput = '{';
	$sOutput .= '"sEcho": '.$sEcho.', ';
	$sOutput .= '"iTotalRecords": '.$iTotal.', ';
	$sOutput .= '"iTotalDisplayRecords": '.$iFilteredTotal.', ';
	$sOutput .= '"aaData": [ ';
        $i = 0;

        foreach($lists as $list) {
            $list->tmpemohon->get();
            $list->trperizinan->get();
            $list->trstspermohonan->get();
            $list->tmpemohon->trkelurahan->get();

            $i++;
            $sOutput .= "[";
            $sOutput .= '"'.$i.'",';
            $sOutput .= '"'.$list->pendaftaran_id. '",';
            $sOutput .= '"'.$list->trperizinan->n_perizinan. '",';
            $sOutput .= '"'.$this->lib_date->mysql_to_human($list->d_terima_berkas). '",';
            $sOutput .= '"'.$list->tmpemohon->n_pemohon. '",';
            $sOutput .= '"'.$list->trstspermohonan->n_sts_permohonan. '",';
            $sOutput .= '"'.$list->tmpemohon->a_pemohon. '",';
            $sOutput .= '"'.$list->tmpemohon->trkelurahan->n_kelurahan. '"';
            $sOutput .= "],";
        }

	$sOutput = substr_replace( $sOutput, "", -1 );
	$sOutput .= '] }';

	echo $sOutput;

    }

}

// This is the end of monitoring class
