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
class Monitoringkecamatan extends WRC_AdminCont {

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
            if ($list_auth->id_role === '2') {
                $enabled = TRUE;
                $this->monitoringkecamatan = new user_auth();
            }
        }

		if (!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
        $data['listpemohon'] = $this->pemohon->limit(0)->get();
        $data['list_ijin'] = $this->perizinan->order_by('id', 'ASC')->get();
        $data = $this->_funcwilayah();

        $js = "
                $(document).ready(function() {
                        oTable = $('#monitoring').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );


                 $(document).ready(function() {
                        $('#propinsi_id').change(function(){
                                $('#show_kabupaten').fadeOut();
                                $.post('" . base_url() . "monitoring/monitoringkecamatan/kabupaten_pemohon', {
                                    propinsi_id: $('#propinsi_id').val()
                                }, function(response){
                                    setTimeout(\"finishAjax('show_kabupaten', '\"+escape(response)+\"')\", 400);
                                });
                                return false;
                        });
                        $('#kabupaten_id').change(function(){
                                $('#show_kecamatan_pemohon').fadeOut();
                                $.post('" . base_url() . "monitoring/monitoringkecamatan/kecamatan_pemohon', {
                                    kabupaten_id: $('#kabupaten_id').val()
                                }, function(response){
                                    setTimeout(\"finishAjax('show_kecamatan_pemohon', '\"+escape(response)+\"')\", 400);
                                });
                                return false;
                        });
                        $('#kecamatan_id').change(function(){
                                $('#show_kelurahan_pemohon').fadeOut();
                                $.post('" . base_url() . "monitoring/monitoringkecamatan/kelurahan_pemohon', {
                                    kecamatan_id: $('#kecamatan_id').val()
                                }, function(response){
                                    setTimeout(\"finishAjax('show_kelurahan_pemohon', '\"+escape(response)+\"')\", 400);
                                });
                                return false;
                        });
                });

                function finishAjax(id, response){
                  $('#'+id).html(unescape(response));
                  $('#'+id).fadeIn();
                }
            ";

        $this->template->set_metadata_javascript($js);
        $data['listpermohonan'] = $this->permohonan->limit(0)->get();
        $this->load->vars($data);

        $this->session_info['page_name'] = "Monitoring Per perizinan";
        $this->template->build('listkecamatan', $this->session_info);
    }

    function _funcwilayah() {
        $data['list_propinsi'] = $this->propinsi->where('id', '12')->order_by('n_propinsi', 'ASC')->get();
        $data['list_kabupaten'] = $this->kabupaten->where_related($this->propinsi)->order_by('n_kabupaten', 'ASC')->get();
        $data['list_kecamatan'] = $this->kecamatan->order_by('n_kecamatan', 'ASC')->get();
        $data['list_kelurahan'] = $this->kelurahan->order_by('n_kelurahan', 'ASC')->get();

        return $data;
    }

    public function kabupaten_pemohon() {
        $data['kabupaten_id'] = 'kabupaten_pemohon';
        $data['kecamatan_id'] = 'kecamatan_pemohon';

        $this->load->vars($data);
        $this->load->view('kabupaten_load_lagi', $data);
    }

    public function kecamatan_pemohon() {
        $data['kecamatan_id'] = 'kecamatan_pemohon';
        $data['kelurahan_id'] = 'kelurahan_pemohon';

        $this->load->vars($data);
        $this->load->view('kecamatan_load_lagi', $data);
    }

    public function kelurahan_pemohon() {
        $data['kelurahan_id'] = 'kelurahan_pemohon';

        $this->load->vars($data);
        $this->load->view('kelurahan_load_lagi', $data);
    }

    public function getKecamatan() {
        $this->kelurahan = new trkelurahan();
        $this->pemohon = new tmpemohon();

        $hasil = $this->input->post('mon_kelurahan');
        $kelu = new trkelurahan();
        $pemo = new tmpemohon();
        $permohonan = new tmpermohonan();

        $kelu->where('id', $hasil);

        $data['list_kelurahan'] = $kelu->get();
        $data['list_pemohon'] = $pemo->where_related($kelu)->get();
        $data['listpermohonan'] = $permohonan->get();
        $data['hasil'] = $this->input->post('mon_kelurahan');
        $this->load->vars($data);

        $js = "
                $(document).ready(function() {
                        oTable = $('#monitoring').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Monitoring Per Wilayah";
        $this->template->build('view_kelurahan', $this->session_info);
    }

    public function cetak_kelurahan($hasil = NULL,$first= null,$second= null) {
        $this->settings = new settings();
        $this->settings->where('name', 'app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name', 'app_city')->get();

        $permohonan = new tmpermohonan();

        $p_kelurahan = $permohonan->tmpemohon->trkelurahan->get();
        $p_kecamatan = $permohonan->tmpemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $permohonan->tmpemohon->trkelurahan->trkecamatan->trkabupaten->get();

        //path of the template file
        $nama_surat = "cetak_monitoring_kecamatan";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/' . $nama_surat . '.odt');
//        $odf->setImage('header', 'assets/css/' . $app_folder . '/images/dinas_1.jpg', '17.5', '3.5');

           //logo
        $this->tr_instansi = new Tr_instansi();
        $logo = $this->tr_instansi->get_by_id(14);
        $odf->setImage('logo', 'uploads/logo/' . $logo->value, '1.7', '1.7');

        //badan
        $this->tr_instansi = new Tr_instansi();
        $nama_bdan = $this->tr_instansi->get_by_id(9);
        $odf->setVars('badan', strtoupper($nama_bdan->value));
        $odf->setVars('periode_awal',$this->lib_date->mysql_to_human($first));
         $odf->setVars('periode_akhir',$this->lib_date->mysql_to_human($second));
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
            $alamat = $permohonan->tmpemohon->a_pemohon . ' ' . $p_kelurahan->n_kelurahan . ', ' .
                    $p_kecamatan->n_kecamatan . ', ' . ucwords(strtolower($p_kabupaten->n_kabupaten));
            $wilayah->get_by_id($app_city->value);
            //$odf->setVars('kabupaten', ucwords(strtolower($wilayah->n_kabupaten)));
            $odf->setVars('kota', ucwords(strtolower($wilayah->ibukota)));
        } else {
            $alamat = $permohonan->tmpemohon->a_pemohon;
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
        
        $odf->setVars('tanggal', $this->lib_date->mysql_to_human(date('Y/m/d')));
        $odf->setVars('title', 'Monitor Per Desa dan Kecamatan');

        $i = 1;

        $list_mohonkelu = new tmpemohon_trkelurahan();
        $list_mohonkelu->where('trkelurahan_id', $hasil)->get(); 
        
        foreach ($list_mohonkelu as $datapemohon) {
            $list_mohonpemohon = new tmpemohon_tmpermohonan();
            $list_mohonpemohon = $list_mohonpemohon->where('tmpemohon_id', $datapemohon->tmpemohon_id)->get();
            foreach ($list_mohonpemohon as $data_p) 
                {
                $list_permohonan = new tmpermohonan();
                $list_permohonan->where('id', $data_p->tmpermohonan_id)->where('d_terima_berkas >= ', $first)->where('d_terima_berkas <=', $second)->get();
                foreach ($list_permohonan as $data) {
$odf->setVars('kelurahan',$data->tmpemohon->trkelurahan->n_kelurahan);

                    $listeArticles3 = array(
                        array('property' => $i,
                            'content1' => $data->pendaftaran_id, //$data->n_perizinan,
                            'content2' => $data->trperizinan->n_perizinan,
                            'content3' => $this->lib_date->mysql_to_human($data->d_entry),
                            'content4' => $data->tmpemohon->n_pemohon,
                            'content5' => $data->trstspermohonan->n_sts_permohonan,
                            'content6' => $data->tmpemohon->a_pemohon,
                            'content7' => $data->tmpemohon->trkelurahan->n_kelurahan,
                        ),
                    );

                    $article3 = $odf->setSegment('articles3');
                    foreach ($listeArticles3 AS $element) {

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
            }
        }
        $odf->mergeSegment($article3);
        
        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat . '_' . $no_daftar . '.odt');
    }

}

// This is the end of monitoring class
