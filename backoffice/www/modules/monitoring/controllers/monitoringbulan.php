<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of monitoring class
 *
 * @author  Yogi Cahyana & zulfah
 * @since   1.0
 *
 */
class Monitoringbulan extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();

        $this->pemohon = new tmpemohon();
        $this->permohonan = new tmpermohonan();
        $this->perijinan = new trperizinan();
        $this->monitoringbulan = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->monitoringbulan = NULL;

        foreach ($list_auths as $list_auth) {
            if ($list_auth->id_role === '2') {
                $enabled = TRUE;
                $this->monitoringbulan = new user_auth();
            }
        }

        if (!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {

        $data['listpemohon'] = $this->pemohon->limit(0)->get();
        $data['listpermohonan'] = $this->permohonan->limit(0)->get();
        $data['list_ijin'] = $this->perijinan->order_by('id', 'ASC')->get();

        $this->load->vars($data);

        $js = "
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
        $this->session_info['page_name'] = "Monitoring Per Bulan Masuk";
        $this->template->build('listbulan', $this->session_info);
    }

    public function cetak_monitoring_bulan($tgla = null, $tglb = null) {
        $this->settings = new settings();
        $this->settings->where('name', 'app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name', 'app_city')->get();

        $permohonan = new tmpermohonan();
        
        $p_kelurahan = $permohonan->tmpemohon->trkelurahan->get();
        $p_kecamatan = $permohonan->tmpemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $permohonan->tmpemohon->trkelurahan->trkecamatan->trkabupaten->get();

        //path of the template file
        $nama_surat = "cetak_monitoring_bulan_generic";
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
        $odf->setVars('title', 'Monitoring per Jangka Waktu');
        $odf->setVars('periode_awal',$this->lib_date->mysql_to_human($tgla));
        $odf->setVars('periode_akhir',$this->lib_date->mysql_to_human($tglb));

        $i = 1;

        $data['listpemohon'] = $this->pemohon->get();
        $listpermohonan = $this->permohonan->where_related("trstspermohonan",'id <> 1')->where("d_terima_berkas >= '$tgla' AND d_terima_berkas <= '$tglb'")->get();

        if ($this->permohonan->id) {
            foreach ($listpermohonan as $data) {
                $data->tmpemohon->get();
                $data->trperizinan->get();
                $data->trstspermohonan->get();
                $data->tmpemohon->trkelurahan->get();


                $listeArticles3 = array(
                    array('property' => $i,
                        'content1' => $data->pendaftaran_id, //$data->n_perizinan,
                        'content2' => $data->trperizinan->n_perizinan,
                        'content3' => $this->lib_date->mysql_to_human($data->d_terima_berkas),
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
        } else {
            $listeArticles3 = array(
                array('property' => '',
                    'content1' => '', //$data->n_perizinan,
                    'content2' => '',
                    'content3' => '',
                    'content4' => '',
                    'content5' => '',
                    'content6' => '',
                    'content7' => '',
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
        }
        $odf->mergeSegment($article3);

        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat . '_' . $no_daftar . '.odt');
    }

    public function getPerbulan() {
        $tgla = $this->input->post('tgla');
        $tglb = $this->input->post('tglb');

        $data['listpemohon'] = $this->pemohon->get();
        $data['listpermohonan'] = $this->permohonan->where("d_entry BETWEEN '$tgla' AND '$tglb'")->get();

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
        $this->session_info['page_name'] = "Monitoring Per Bulan masuk";
        $this->template->build('view_bulan', $this->session_info);
    }

    public function getBetween() {
        $tgla = $this->input->post('tgla');
        $tglb = $this->input->post('tglb');
        $this->load->database();
        $this->db->select('a.*, c.n_pemohon, c.a_pemohon, e.n_perizinan');
        $this->db->from('tmpermohonan as a');
        $this->db->join('tmpemohon_tmpermohonan as b', 'a.id=tmpermohonan_id');
        $this->db->join('tmpemohon as c', 'b.tmpemohon_id=c.id');
        $this->db->join('tmpemohon_trperizinan as d', 'c.id=d.tmpemohon_id');
        $this->db->join('trperizinan as e', 'e.id=d.trperizinan_id');
        $this->db->where("a.d_terima_berkas BETWEEN '$tgla' AND '$tglb'");
        $this->db->groupby('a.id');
        $query = $this->db->get('');
        return $query->result();

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
        $this->session_info['page_name'] = "Monitoring Per Bulan Masuk";
        $this->template->build('view_bulan', $this->session_info);
    }

}

// This is the end of monitoring class
