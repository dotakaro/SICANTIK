<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of penjadwalan
 *
 * @author Yogi Cahyana
 * 
 */
class Realisasi extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->permohonan = new tmpermohonan();
        $this->perizinan = new trperizinan();
        $this->realisasi = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->realisasi = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '10') {
                $enabled = TRUE;
                $this->realisasi = new user_auth();
            }
        }
		
        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
//        $data['list_tahun'] = $this->permohonan->group_by('d_tahun','ASC')->get();
        $data['list'] = $this->perizinan->limit(0)->get();
        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#realisasi').dataTable({
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

        $this->session_info['page_name'] = "Realisasi Penerimaan";
        $this->template->build('realisasi_listZ', $this->session_info);
    }


    public function view() {
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

        $this->session_info['page_name'] = "Realisasi Penerimaan";
        $this->template->build('list_rekap', $this->session_info);

        }

        
    public function cetak_reporting($tgla = null, $tglb = null) {
        $this->settings = new settings();
        $this->settings->where('name','app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name','app_city')->get();

//        $permohonan = new tmpermohonan();
//        $permohonan->get_by_id($data_tahun);
//
//
//        $p_kelurahan = $permohonan->tmpemohon->trkelurahan->get();
//        $p_kecamatan = $permohonan->tmpemohon->trkelurahan->trkecamatan->get();
//        $p_kabupaten = $permohonan->tmpemohon->trkelurahan->trkecamatan->trkabupaten->get();

        //path of the template file
        $nama_surat = "cetak_report";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/'.$nama_surat.'.odt');
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
        
        $odf->setVars('judul', 'Realisasi Penerimaan Periode '. $this->lib_date->mysql_to_human($tgla)." - ".$this->lib_date->mysql_to_human($tglb));

        //membuat kota
        $wilayah = new trkabupaten();
        if ($app_city->value !== '0') {
            $wilayah->get_by_id($app_city->value);
            $gede_kota=strtoupper($wilayah->n_kabupaten);
            $kecil_kota=ucwords(strtolower($wilayah->n_kabupaten));
            $odf->setVars('kota4', $gede_kota);
			$odf->setVars('kota', strtolower($wilayah->ibukota));
			$tgl_skr = $this->lib_date->get_date_now();
			$odf->setVars('tglskr', $this->lib_date->mysql_to_human($tgl_skr));
        } else {
                       
            $odf->setVars('kota4', '...........');
			$odf->setVars('kota', '.............');
			$odf->setVars('tglskr', '.............');
        }

         //alamat
        $this->tr_instansi = new Tr_instansi();
        $alamat = $this->tr_instansi->get_by_id(12);
        $odf->setVars('alamat', ucwords(strtolower($alamat->value)).' - '.$kecil_kota);


//        $odf->setVars('tanggal', $this->lib_date->mysql_to_human(date('YYYY/mm/dd')));
//        $odf->setVars('tahun', $data_tahun);

        $i = 0;

//        $list = $this->perizinan->get();
//         foreach ($list as $data){
        $query_data = "select a.id, a.n_perizinan, a.v_perizinan
                                from trperizinan a, trkelompok_perizinan_trperizinan b
                                where b.trkelompok_perizinan_id = 4 /*Izin Bertarif*/
                                and a.id = b.trperizinan_id";
        $hasil_data = mysql_query($query_data);
        while ($data = mysql_fetch_assoc(@$hasil_data)){
            $i++;
            $jumlah = 0;
            $query = "select a.id, d.nilai_retribusi jumlah from tmpermohonan a
                    inner join tmpermohonan_trperizinan b on a.id = b.tmpermohonan_id
                    inner join tmbap_tmpermohonan c on a.id = c.tmpermohonan_id
                    inner join tmbap d on d.id = c.tmbap_id
                     where a.c_status_bayar = 1 and b.trperizinan_id = '".$data['id']."'
                     and a.d_terima_berkas between '$tgla' and '$tglb'";
            $results = mysql_query($query);
            while ($rows = mysql_fetch_assoc(@$results)){
                $nilai_ret = 0;
                $query2 = "select a.v_prosentase_retribusi persen
                         from tmkeringananretribusi a, tmkeringananretribusi_tmpermohonan b
                         where b.tmpermohonan_id = '".$rows['id']."'
                         and a.id = b.tmkeringananretribusi_id";
                $hasil_data2 = mysql_query($query2);
                $count_data = mysql_num_rows(@$hasil_data2);
                $data2 = mysql_fetch_object(@$hasil_data2);
                if ($count_data)
                    $nilai_ret = ($data2->persen * 0.01) * $rows['jumlah'];
                else
                    $nilai_ret = $rows['jumlah'];
                $jumlah = $jumlah + $nilai_ret;
            }
            if ($data['v_perizinan']) $target = 'Rp. '.$this->terbilang->nominal($data['v_perizinan']).',00';
            else $target = "Rp. 0,00";
            $listeArticles3 = array(
                    array(	'property' =>  $i,
                            'content' =>   $data['n_perizinan'],
                            'content9' =>  $target,
                            'content7' =>  'Rp. '.$this->terbilang->nominal($jumlah).',00',

                    ),

            );

            $article3 = $odf->setSegment('articles3');
            foreach($listeArticles3 AS $element) {

                    $article3->titreArticle3($element['property']);
                    $article3->texteArticle3($element['content']);
                    $article3->texteArticle4($element['content9']);
                    $article3->texteArticle5($element['content7']);
                    $article3->merge();
            }
        }
        $odf->mergeSegment($article3);

        //export the file
//        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat.'.odt');
    }

    public function cetak_reportAll($data_tahun = NULL) {
        $this->settings = new settings();
        $this->settings->where('name','app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name','app_city')->get();

        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($data_tahun);

        $list = $this->perizinan->get();

        $p_kelurahan = $permohonan->tmpemohon->trkelurahan->get();
        $p_kecamatan = $permohonan->tmpemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $permohonan->tmpemohon->trkelurahan->trkecamatan->trkabupaten->get();

        //path of the template file
        $nama_surat = "cetak_report";
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
        $odf->setVars('tahun', $data_tahun);

        $i = 1;
                    foreach ($list as $data){
                        
                        $z = 0;
                        $jumlah = 0;

                        $retribusi = new trretribusi();
                        $harga = $data->$retribusi->get();

                        $relasi = new tmpermohonan_trperizinan();
                        $list_relasi = $relasi->where('trperizinan_id',$data->id)->get();
                        $jumlah = $relasi->where('trperizinan_id',$data->id)->count();



        $listeArticles3 = array(
                array(	'property' =>$i,
                        'content' =>   $data->n_perizinan,
                        'content9' =>  'Rp '.$data->v_perizinan.',00',
                        'content7' =>  'Rp '.$harga->v_retribusi*$jumlah.',00',

                ),

        );

        $article3 = $odf->setSegment('articles3');
        foreach($listeArticles3 AS $element) {

                $article3->titreArticle3($element['property']);
                $article3->texteArticle3($element['content']);
                $article3->texteArticle4($element['content9']);
                $article3->texteArticle5($element['content7']);
                $article3->merge();
        }
        $i++;
        }
        $odf->mergeSegment($article3);

        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat.'_'.$no_daftar.'.odt');
    }

    // ##############################Untul reporting dari rekapitulasi####################################

    public function cetak_report($tgla = null, $tglb = null) {
        $this->settings = new settings();
        $this->settings->where('name','app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name','app_city')->get();

        //path of the template file
        $nama_surat = "cetak_laporannya";
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

        $wilayah = new trkabupaten();
         if ($app_city->value !== '0')
         {
            $wilayah->get_by_id($app_city->value);
            $gede_kota=strtoupper($wilayah->n_kabupaten);
            $kecil_kota=ucwords(strtolower($wilayah->n_kabupaten));
            $odf->setVars('kota4', $gede_kota);
			$odf->setVars('kota', strtolower($wilayah->ibukota));
			$tgl_skr = $this->lib_date->get_date_now();
			$odf->setVars('tglskr', $this->lib_date->mysql_to_human($tgl_skr));
         }
         else
         {
              $odf->setVars('kota4','.......');
			  $odf->setVars('kota', '.............');
			$odf->setVars('tglskr', '............');
        }
          $this->tr_instansi = new Tr_instansi();
        $alamat = $this->tr_instansi->get_by_id(12);
        $odf->setVars('alamat', ucwords(strtolower($alamat->value)).' - '.$kecil_kota);

        //membuat kota
//        $wilayah = new trkabupaten();
//        if ($app_city->value !== '0') {
//            $alamat =  $permohonan->tmpemohon->a_pemohon . ' ' . $p_kelurahan->n_kelurahan . ', ' .
//                    $p_kecamatan->n_kecamatan . ', ' . ucwords(strtolower($p_kabupaten->n_kabupaten));
//            $wilayah->get_by_id($app_city->value);
            //$odf->setVars('kabupaten', ucwords(strtolower($wilayah->n_kabupaten)));
//            $odf->setVars('kota', ucwords(strtolower($wilayah->n_kabupaten)));
//        } else {
//            $alamat =  $permohonan->tmpemohon->a_pemohon;
            //$odf->setVars('kabupaten', 'setempat');
//            $odf->setVars('kota', '...........');
//        }



//        $odf->setVars('tanggal', $this->lib_date->mysql_to_human(date('YYYY/mm/dd')));

        $odf->setVars('judul', 'Rekap Pendaftaran Periode '. $this->lib_date->mysql_to_human($tgla)." - ".$this->lib_date->mysql_to_human($tglb));
        $i=1;
                        $jumlah = 0;
        $query_data = "select id, n_perizinan, v_perizinan from trperizinan";
        $hasil_data = mysql_query($query_data);
        while ($data = mysql_fetch_assoc(@$hasil_data)){
            $jumlah = 0;
            $izin = new trperizinan();
            $izin->get_by_id($data['id']);
            $permohonan = new tmpermohonan();
            $jumlah = $permohonan->where("d_terima_berkas between '$tgla' and '$tglb'")
                    ->where_related($izin)->count();
   
        $listeArticles3 = array(
                array(	'property' =>$i,
                        'content' =>   $data['n_perizinan'],
                        'content2' =>  $jumlah,

                ),

        );

        $article3 = $odf->setSegment('articles3');
        foreach($listeArticles3 AS $element) {

                $article3->titreArticle3($element['property']);
                $article3->texteArticle3($element['content']);
                $article3->texteArticle4($element['content2']);
                $article3->merge();
        }
        $i++;
        }
        $odf->mergeSegment($article3);

        //export the file
        $odf->exportAsAttachedFile($nama_surat.'.odt');
    }

    public function cetak_LapAll($data_tahun = null) {
        $this->settings = new settings();
        $this->settings->where('name','app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name','app_city')->get();

        $permohonan = new tmpermohonan();


        $list = $this->perizinan->get();
        $permohonan->get_by_id($data_tahun);
        $p_kelurahan = $permohonan->tmpemohon->trkelurahan->get();
        $p_kecamatan = $permohonan->tmpemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $permohonan->tmpemohon->trkelurahan->trkecamatan->trkabupaten->get();

        //path of the template file
        $nama_surat = "cetak_laporannya";
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

        $i=1;
        foreach ($list as $data){

                        $x = 0;
                        $o = 0;
                        $z = 0;
                        $y = 0;
                        $hasil = 0;
                        $selesai = 0;
                        $belum = 0;
                        $relasi = new tmpermohonan_trperizinan();
                        $status = new trstspermohonan();
                        $permohonan = new tmpermohonan();
                        $relasistatus = new tmpermohonan_trstspermohonan();
                        $jumlah = $relasi->where('trperizinan_id',$data->id)->count();
                        $list_relasi = $relasi->where('trperizinan_id',$data->id)->get();

                        // Izin Selesai
                         foreach ($list_relasi as $data_relasi){
                            $daftar = new tmpermohonan();
                            $daftar->get_by_id($data_relasi->tmpermohonan_id);
                                      if($daftar->c_izin_selesai === '1'){
                                        $y++;
                                        $selesai = $y;
                                        }
                                            if($daftar->c_izin_selesai === '0'){
                                                $o++;
                                                $belum = $o;
                                                }
                         }

        $listeArticles3 = array(
                array(	'property' =>$i,
                        'content' =>   $data->n_perizinan,
                        'content8' =>  $permohonan->tmpemohon->n_pemohon,
                        'content9' =>  $jumlah,
                      

                ),

        );

        $article3 = $odf->setSegment('articles3');
        foreach($listeArticles3 AS $element) {

                $article3->titreArticle3($element['property']);
                $article3->texteArticle3($element['content']);
                $article3->texteArticle4($element['content9']);
                $article3->texteArticle11($element['content8']);
                $article3->merge();
        }
        $i++;
        }
        $odf->mergeSegment($article3);

        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat.'_'.$no_daftar.$data_tahun.'.odt');
    }


}
