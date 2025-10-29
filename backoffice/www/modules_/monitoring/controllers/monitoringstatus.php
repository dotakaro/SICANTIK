<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of monitoring class
 *
 * @author  Yogi Cahyana & zulfah
 * @since   1988
 *
 */
class Monitoringstatus extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();

        $this->pemohon = new tmpemohon();
        $this->permohonan = new tmpermohonan();
        $this->perizinan = new trperizinan();
        $this->monitoringpengizin = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->monitoringpengizin = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '2') {
                $enabled = TRUE;
                $this->monitoringpengizin = new user_auth();
            }
        }

		if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
        $stspermohonan = new trstspermohonan();
        $data['liststspermohonan'] = $stspermohonan->get();

        $data['listpemohon'] = $this->pemohon->limit(0)->get();
        $data['listpermohonan'] = $this->permohonan->limit(0)->get();
        $data['list_ijin'] = $this->perizinan->order_by('id', 'ASC')->get();

        $this->load->vars($data);

        $this->session_info['page_name'] = "Monitoring Per Pengambilan Izin";
        $this->template->build('list_pengizin', $this->session_info);
    }

    public function filterdata() {
        $this->perizinan->where('id', $this->input->post('jenis_izin'))->get();
        $data['list_ijin'] = $this->perizinan->order_by('id', 'ASC')->get();
        $data['listpermohonan'] = $this->perizinan->tmpermohonan->get($this->perizinan);
        $this->load->vars($data);
        $this->session_info['page_name'] = "Monitoring Pengambilan Izin : " . $this->input->post('jenis_izin');
        $this->template->build('list_pengizin', $this->session_info);
    }
	
	

     public function cetak_monitoring_ambil() {
	
	 $tgla = $this->input->post('first_date');
	 $tglb = $this->input->post('second_date');
	 $idizin = $this->input->post('list_status');
	 
        $this->settings = new settings();
        $this->settings->where('name','app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name','app_city')->get();

        $permohonan = new tmpermohonan();
        $list = $this->perizinan->where('id',$this->input->post('jenis_izin'))->get();

        $p_kelurahan = $permohonan->tmpemohon->trkelurahan->get();
        $p_kecamatan = $permohonan->tmpemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $permohonan->tmpemohon->trkelurahan->trkecamatan->trkabupaten->get();

        //path of the template file
        $nama_surat = "cetak_monitoring_status_generic";
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
            $alamat =  $permohonan->tmpemohon->a_pemohon . ' ' . $p_kelurahan->n_kelurahan . ', ' .
                    $p_kecamatan->n_kecamatan . ', ' . ucwords(strtolower($p_kabupaten->n_kabupaten));
            $wilayah->get_by_id($app_city->value);
            //$odf->setVars('kabupaten', ucwords(strtolower($wilayah->n_kabupaten)));
            $odf->setVars('kota', ucwords(strtolower($wilayah->ibukota)));
        } else {
            $alamat =  $permohonan->tmpemohon->a_pemohon;
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


  $stspermohonan = new trstspermohonan();

  $list_status = $stspermohonan->where('id',$idizin)->get();

        $odf->setVars('tanggal', $this->lib_date->mysql_to_human(date('Y/m/d')));
        $odf->setVars('title','Monitoring Per Status');
        $odf->setVars('status',$list_status->n_sts_permohonan);
        $odf->setVars('periode_awal',$this->lib_date->mysql_to_human($tgla));
        $odf->setVars('periode_akhir',$this->lib_date->mysql_to_human($tglb));


        $i = 1;

	 
						$relasi = new tmpermohonan_trperizinan();
						//$list_relasi = $relasi->where('trperizinan_id',$this->input->post('ambilizin'))->get();
						//$listpermohonan = $this->permohonan->where("d_entry BETWEEN '$tgla' AND '$tglb'")->get();
                       
					    $list_relasi = $this->getTabel($tgla, $tglb, $idizin);
					    
						if($list_relasi){
                        foreach ($list_relasi as $data_relasi){
						
                          /*  $data = new tmpermohonan();
							$data->where('id',$data_relasi->tmpermohonan_id)->get();
                            $data->tmpemohon->get();
                            $data->tmpemohon->trkelurahan->get();
                            $data->trstspermohonan->get(); */ 
		

        $listeArticles3 = array(
                array(	'property' =>  $i,
                        'content1' =>  $data_relasi->pendaftaran_id,//$data->n_perizinan,
                        'content2' =>  $data_relasi->n_perizinan,
                        'content3' =>  $this->lib_date->mysql_to_human($data_relasi->d_terima_berkas),
                        'content4' =>  $data_relasi->n_pemohon,
                        'content5' =>  $data_relasi->n_sts_permohonan,
                        'content6' =>  $data_relasi->a_pemohon,
                        'content7' =>  $data_relasi->n_kelurahan,

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


    public function selector($id_izin = NULL) {
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

        $this->perizinan->where('id', $id_izin);
        $this->perizinan->get($iDisplayStart, $iSortCol_0);

        $iTotal = $this->perizinan->tmpermohonan->count();
        $lists = $this->perizinan->tmpermohonan->get();

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
	
	public function getTabel($tgla,$tglb,$id)
	{
		
	$sql = "select a.pendaftaran_id, a.d_entry, b.n_pemohon, c.n_perizinan , c.id, a.d_terima_berkas, d.n_sts_permohonan, b.a_pemohon, l.n_kelurahan
	from tmpermohonan as a
	left join tmpemohon_tmpermohonan as  x on a.id = x.tmpermohonan_id
	left join tmpemohon as b on b.id = x.tmpemohon_id
	left join tmpermohonan_trperizinan as y ON  a.id =  y.tmpermohonan_id
	left join trperizinan as c on c.id = y.trperizinan_id
	left join tmpermohonan_trstspermohonan as z on a.id = z.tmpermohonan_id
	left join trstspermohonan as d on d.id = z.trstspermohonan_id
	left join tmpemohon_trkelurahan as k on k.tmpemohon_id = b.id
	left join trkelurahan as l on l.id = k.trkelurahan_id
	where a.d_terima_berkas >= '".$tgla."'
	and  a.d_terima_berkas <= '".$tglb."'
	and d.id = '".$id."'";

	$query = $this->db->query($sql);
	return $query->result();

	}

	

}

// This is the end of monitoring class
