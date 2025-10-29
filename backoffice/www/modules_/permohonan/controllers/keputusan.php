<?php

/**
 * Description of Pembuatan Surat Keputusan
 *
 * @author agusnur
 * Dated : 19 Dec 2010
 */

class Keputusan extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->keputusan = new tmsurat_keputusan();
    }
		
	/*private function __configure_authentication(){
        $enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->keputusan = NULL;
        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '9' or $list_auth->id_role === '13') {
                $enabled = TRUE;
                $this->keputusan = new tmsurat_keputusan();
            }
        }
		
        if(!$enabled) {
            redirect('dashboard');
        }
	}*/

    public function edit($id_daftar = NULL) {
//		$this->__configure_authentication();
		
        $permohonan = new tmpermohonan();
        $permohonan = $permohonan->get_by_id($id_daftar);

        $petugas = 1; //1 -> Jabatan Penandatangan
        $perizinan = $permohonan->trperizinan->get();
        $surat_awal = $permohonan->tmsurat_keputusan->get();
        $sts_cetak = 1;
        
        $app_folder = new settings();
        $app_folder->where('name','app_folder')->get();
        $app_folder = $app_folder->value . "/";
        $app_city = new settings();
        $app_city->where('name','app_city')->get();
        $app_city = $app_city->value;
        $app_kan =  $this->settings->where('name', 'app_kantor')->get();
        
        if($surat_awal->id){
        }else{
            /* Input Data */
            $data_id = new tmsurat_keputusan();

            $data_id->select_max('id')->get();
            $data_id->get_by_id($data_id->id);

            $data_tahun = date("Y");
            //Per Tahun Auto Restart NoUrut
            if($permohonan->d_tahun === $data_tahun)
            $data_urut = $data_id->i_urut + 1;
            else $data_urut = 1;

            $data_izin = $perizinan->id;
            $i_izin = strlen($data_izin);
            for($i=3;$i>$i_izin;$i--){
                $data_izin = "0".$data_izin;
            }

            $data_bulan = $this->lib_date->set_month_roman(date("n"));

            $data_sk = "DP";
            /*$no_surat = $data_urut."/"
                    .$data_sk."/".$data_izin."/"
                    .$data_bulan."/".$data_tahun;*/
			
			/*START Ambil Setting Report Component*/
			###sudah ada di permohonan\penetapan\save##
			$this->load->model('report_component/Report_component_model');
			$this->report_component_model=new Report_component_model();
			$setting_component_sk=$this->report_component_model->get_report_component($this->report_component_model->kode_sk,$perizinan->id, $id_daftar);
			/*END Ambil Setting Report Component*/
			
			/*START Ambil No Surat Keputusan jika ada*/
			if(isset($setting_component_sk['format_nomor']) && 
				$setting_component_sk['format_nomor']!=''){
				$no_surat = $setting_component_sk['format_nomor'];
			}else{		
				$no_surat = $data_urut."/"
	                    .$data_sk."/".$data_izin."/"
	                    .$data_bulan."/".$data_tahun;
			}
			/*END Ambil No Surat Keputusan jika ada*/			
					
            $surat_sk = new tmsurat_keputusan();
            $surat_sk->c_status = $sts_cetak;
            $surat_sk->i_urut = $data_urut;
            $surat_sk->no_surat = $no_surat;
            $tgl_skr = $this->lib_date->get_date_now();
            $surat_sk->tgl_surat = $tgl_skr;
            $surat_sk->c_cetak = 1;

            $pemohon = $permohonan->tmpemohon->get();
            $perusahaan = $permohonan->tmperusahaan->get();
            $surat_sk->ket1 = "Pemohon";
            $surat_sk->nama1 = $pemohon->n_pemohon;
            $surat_sk->alamat1 = $pemohon->a_pemohon;
            $surat_sk->ket2 = "Perusahaan";
            $surat_sk->nama2 = $perusahaan->n_perusahaan;
            $surat_sk->alamat2 = $perusahaan->a_perusahaan;
            $nama_izin = $perizinan->n_perizinan;
            $badan = strtolower($app_kan->value);
			
			
			/***edited by Indra***/
            //$surat_sk->content1 = $nama_izin.' ini berlaku sejak ditetapkan sampai dengan tanggal '.$this->lib_date->mysql_to_human($this->lib_date->set_date($tgl_skr, (365*$perizinan->v_berlaku_tahun))).' dan izin pembaharuan diajukan kepada Kepala '.ucwords($badan).' selambat-lambatnya 3 (tiga) bulan sebelum habis masa berlakunya keputusan ini.';
			if($perizinan->v_berlaku_tahun !=''&&$perizinan->v_berlaku_tahun !=NULL&&$perizinan->v_berlaku_tahun!=0){//Jika ada masa berlakunya
            	$akhir_masa_berlaku=$this->lib_date->modDate($tgl_skr,"+".$perizinan->v_berlaku_tahun,$perizinan->v_berlaku_satuan);
				$surat_sk->content1 = $nama_izin.' ini berlaku sejak ditetapkan sampai dengan tanggal '.$this->lib_date->mysql_to_human($akhir_masa_berlaku).' dan izin pembaharuan diajukan kepada Kepala '.ucwords($badan).' selambat-lambatnya 3 (tiga) bulan sebelum habis masa berlakunya keputusan ini.';
			}else{
				$surat_sk->content1 = '';
			}
				
            /*****************/
			
			$surat_sk->content2 = $nama_izin.' ini dapat dicabut untuk selama-lamanya bila pelaksanaannya tidak sesuai dengan ketentuan peraturan perundang-undangan yang berlaku;';
            $surat_sk->content3 = 'Keputusan ini berlaku sejak tanggal ditetapkan.';

            /* Input Relasi Tabel*/
            $pegawai = new tmpegawai();
            $pegawai->where('status', $petugas)->get();
            $perizinan = $permohonan->trperizinan->get();
            $permohonan->d_berlaku_keputusan = $this->lib_date->set_date($tgl_skr, 365); //per tahun
            $permohonan->save();

            $surat_sk->save(array($permohonan, $pegawai));
        }

        $surat = $permohonan->tmsurat_keputusan->get();
        $save = "update";
        $data['save_method'] = $save;
        $data['daftar'] = $permohonan;
        $data['id_daftar'] = $permohonan->id;
        $data['id_surat'] = $surat->id;
        $data['no_surat'] = $surat->no_surat;
        $data['sts_surat'] = $surat->c_status;
        $data['tgl_surat'] = $surat->tgl_surat;
        $data['ket1'] = $surat->ket1;
        $data['nama1'] = $surat->nama1;
        $data['alamat1'] = $surat->alamat1;
        $data['ket2'] = $surat->ket2;
        $data['nama2'] = $surat->nama2;
        $data['alamat2'] = $surat->alamat2;
        $data['content1'] = $surat->content1;
        $data['content2'] = $surat->content2;
        $data['content3'] = $surat->content3;
        $data['salinan1'] = $surat->salinan1;
        $data['salinan2'] = $surat->salinan2;
        $data['salinan3'] = $surat->salinan3;
        $data['salinan4'] = $surat->salinan4;
        $data['salinan5'] = $surat->salinan5;

        $js =  "$(function() {
                    $(\"#inputTanggal\").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'yy-mm-dd',
                        closeText: 'X'
                    });
                });
                var base_url = '". base_url() ."';
                $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();
                } );
            ";

        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Data Pembuatan SK";
        $this->template->build('keputusan_edit', $this->session_info);
    }

    public function update() {
//        $this->__configure_authentication();
		
		$surat_awal = new tmsurat_keputusan();
        $surat_awal->get_by_id($this->input->post('id_surat'));
        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($this->input->post('id_daftar'));
        $perizinan = $permohonan->trperizinan->get();
        $nama_izin = $perizinan->n_perizinan;
        
        $app_folder = new settings();
        $app_folder->where('name','app_folder')->get();
        $app_folder = $app_folder->value . "/";
        $app_city = new settings();
        $app_city->where('name','app_city')->get();
        $app_city = $app_city->value;
        $app_kan =  $this->settings->where('name', 'app_kantor')->get();
        
        $surat = new tmsurat_keputusan();
        $surat->get_by_id($this->input->post('id_surat'));
        $surat->no_surat = $this->input->post('no_surat');
        $surat->tgl_surat = $this->input->post('tgl_surat');
        $surat->ket1 = $this->input->post('ket1');
        $surat->nama1 = $this->input->post('nama1');
        $surat->alamat1 = $this->input->post('alamat1');
        $surat->ket2 = $this->input->post('ket2');
        $surat->nama2 = $this->input->post('nama2');
        $surat->alamat2 = $this->input->post('alamat2');
        $badan = strtolower($app_kan->value);
        if($surat_awal->tgl_surat == $this->input->post('tgl_surat')){
        	$surat->content1 = $this->input->post('content1');
        }
		else{
 	       	/**Edited by Indra***/
			//$surat->content1 = $nama_izin.' ini berlaku sejak ditetapkan sampai dengan tanggal '.$this->lib_date->mysql_to_human($this->lib_date->set_date($this->input->post('tgl_surat'), (365*$perizinan->v_berlaku_tahun))).' dan izin pembaharuan diajukan kepada Kepala '.ucwords($badan).' selambat-lambatnya 3 (tiga) bulan sebelum habis masa berlakunya keputusan ini.';
			$akhir_masa_berlaku=$this->lib_date->modDate($this->input->post('tgl_surat'),"+".$perizinan->v_berlaku_tahun,$perizinan->v_berlaku_satuan);
			$surat->content1 = $nama_izin.' ini berlaku sejak ditetapkan sampai dengan tanggal '.$this->lib_date->mysql_to_human($akhir_masa_berlaku).' dan izin pembaharuan diajukan kepada Kepala '.ucwords($badan).' selambat-lambatnya 3 (tiga) bulan sebelum habis masa berlakunya keputusan ini.';
			/********************/
        }
		$surat->content2 = $this->input->post('content2');
        $surat->content3 = $this->input->post('content3');
        $surat->salinan1 = $this->input->post('salinan1');
        $surat->salinan2 = $this->input->post('salinan2');
        $surat->salinan3 = $this->input->post('salinan3');
        $surat->salinan4 = $this->input->post('salinan4');
        $surat->salinan5 = $this->input->post('salinan5');
		
        $permohonan->d_berlaku_keputusan = $this->lib_date->set_date($this->input->post('tgl_surat'), 365); //per tahun
        $permohonan->save();

       $tgl = date("Y-m-d H:i:s");
       $u_ser = $this->session->userdata('username');
       $g = $this->sql($u_ser);
//     $jam = date("H:i:s A");
       $p = $this->db->query("call log ('Pembuatan Izin','Update ".$permohonan->pendaftaran_id."','".$tgl."','".$u_ser."')");

        $update = $surat->save();
        if($update) {
            redirect('permohonan/sk');
        }
    }
	
    public function cetak($id_daftar = NULL,$trperizinan_id=NULL) {
		$nama_surat = "cetak_keputusan";
        $app_folder = new settings();
        $app_folder->where('name','app_folder')->get();
        $app_folder = $app_folder->value . "/";
        $app_city = new settings();
        $app_city->where('name','app_city')->get();
        $app_city = $app_city->value;
        $app_kan =  $this->settings->where('name', 'app_kantor')->get();

        $petugas = 1; //1 -> Jabatan Penandatangan
        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($id_daftar);
        $perizinan = $permohonan->trperizinan->get();
        $surat_awal = $permohonan->tmsurat_keputusan->get();
        $sts_cetak = 1;
        
        $this->tr_instansi = new Tr_instansi();
        $nama_bdan = $this->tr_instansi->get_by_id(9);
        $badan = strtolower($app_kan->value);
        if($surat_awal->id){
            $surat_sk = new tmsurat_keputusan();
            $surat_sk->get_by_id($surat_awal->id);
            $surat_sk->c_status = $sts_cetak;
            $tgl_skr = $this->lib_date->get_date_now();
            $surat_sk->tgl_surat = $tgl_skr;
            $surat_sk->save();
            $pegawai = new tmpegawai();
            $pegawai->where('status', $petugas)->get();

            /* Input Relasi Tabel*/
            $perizinan = $permohonan->trperizinan->get();
            $permohonan->d_berlaku_keputusan = $this->lib_date->set_date($tgl_skr, 365);
            $permohonan->save();
        }else{
            /* Input Data */
            $data_id = new tmsurat_keputusan();

            $data_id->select_max('id')->get();
            $data_id->get_by_id($data_id->id);

            $data_tahun = date("Y");
            //Per Tahun Auto Restart NoUrut
            if($permohonan->d_tahun === $data_tahun)
            $data_urut = $data_id->i_urut + 1;
            else $data_urut = 1;

            $i_urut = strlen($data_urut);
            for($i=4;$i>$i_urut;$i--){
                $data_urut = "0".$data_urut;
            }

            $data_izin = $perizinan->id;
            $i_izin = strlen($data_izin);
            for($i=3;$i>$i_izin;$i--){
                $data_izin = "0".$data_izin;
            }

            $data_bulan = $this->lib_date->set_month_roman(date("n"));

            $data_sk = "DP";
            $surat_sk = new tmsurat_keputusan();
            $surat_sk->c_status = $sts_cetak;
            $surat_sk->i_urut = $data_urut;
            
			/*START Ambil Setting Report Component*/
			###sudah ada di permohonan\penetapan\save##
			$this->load->model('report_component/Report_component_model');
			$this->report_component_model=new Report_component_model();
			$setting_component_sk=$this->report_component_model->get_report_component($this->report_component_model->kode_sk,$trperizinan_id, $id_daftar);
			/*END Ambil Setting Report Component*/
			
			/*START Ambil No Surat Keputusan jika ada*/
			if(isset($setting_component_sk['format_nomor']) && 
				$setting_component_sk['format_nomor']!=''){
				$no_surat = $setting_component_sk['format_nomor'];
			}else{		
				$no_surat = $data_urut."/"
	                    .$data_sk."/".$data_izin."/"
	                    .$data_bulan."/".$data_tahun;
			}
			/*END Ambil No Surat Keputusan jika ada*/
			
			$surat_sk->no_surat = $no_surat;
            
			$tgl_skr = $this->lib_date->get_date_now();
            $surat_sk->tgl_surat = $tgl_skr;
            $surat_sk->c_cetak = 1;

            $pemohon = $permohonan->tmpemohon->get();
            $perusahaan = $permohonan->tmperusahaan->get();
            $surat_sk->ket1 = "Pemohon";
            $surat_sk->nama1 = $pemohon->n_pemohon;
            $surat_sk->alamat1 = $pemohon->a_pemohon;
            $surat_sk->ket2 = "Perusahaan";
            $surat_sk->nama2 = $perusahaan->n_perusahaan;
            $surat_sk->alamat2 = $perusahaan->a_perusahaan;
            $nama_izin = $perizinan->n_perizinan;
            
			/***Edited by Indra******/
			//$surat_sk->content1 = $nama_izin.' ini berlaku sejak ditetapkan sampai dengan tanggal '.$this->lib_date->mysql_to_human($this->lib_date->set_date($tgl_skr, (365*$perizinan->v_berlaku_tahun))).' dan izin pembaharuan diajukan kepada Kepala '.ucwords($badan).' selambat-lambatnya 3 (tiga) bulan sebelum habis masa berlakunya keputusan ini.';
			if($perizinan->v_berlaku_tahun !=''&&$perizinan->v_berlaku_tahun !=NULL&&$perizinan->v_berlaku_tahun!=0){//Jika ada masa berlakunya
				$akhir_masa_berlaku=$this->lib_date->modDate($tgl_skr,"+".$perizinan->v_berlaku_tahun,$perizinan->v_berlaku_satuan);
				$surat_sk->content1 = $nama_izin.' ini berlaku sejak ditetapkan sampai dengan tanggal '.$this->lib_date->mysql_to_human($akhir_masa_berlaku).' dan izin pembaharuan diajukan kepada Kepala '.ucwords($badan).' selambat-lambatnya 3 (tiga) bulan sebelum habis masa berlakunya keputusan ini.';
			}else{
				$surat_sk->content1='';
			}
			/********************/
            $surat_sk->content2 = $nama_izin.' ini dapat dicabut untuk selama-lamanya bila pelaksanaannya tidak sesuai dengan ketentuan peraturan perundang-undangan yang berlaku;';
            $surat_sk->content3 = 'Keputusan ini berlaku sejak tanggal ditetapkan.';

            /* Input Relasi Tabel*/
            $pegawai = new tmpegawai();
            $pegawai->where('status', $petugas)->get();
            $perizinan = $permohonan->trperizinan->get();
            $permohonan->d_berlaku_keputusan = $this->lib_date->set_date($tgl_skr, 365); //per tahun
            $permohonan->save();
            
            $surat_sk->save(array($permohonan, $pegawai));
        }
		
        //Status cetak SK
        $sk = new tmsurat_keputusan();
        $sk->get_by_id($surat_awal->id);
        $sk->c_cetak = $surat_awal->c_cetak + 1;
        $sk->save();

        $surat = $permohonan->tmsurat_keputusan->get();
        $pemohon = $permohonan->tmpemohon->get();
        $jenis_izin = $permohonan->trperizinan->get();
        //$petugas = $surat->tmpegawai->get();

		####Commented by Indra####
		####Report has been replace by Jasper Report####
        //path of the template file
        /*$this->load->plugin('odf');
        $odf = new odf('assets/odt/'.$nama_surat.'.odt');
//        $odf->setImage('header', 'assets/css/'.$app_folder.'/images/dinas_1.jpg', '17.5', '3.5');
        $odf->setVars ('ttd', '');

         //logo
        $this->tr_instansi = new Tr_instansi();
        $logo = $this->tr_instansi->get_by_id(14);
        if($logo->value!=="")
        {
           $odf->setImage('logo', 'uploads/logo/' . $logo->value, '1.7', '1.7');
        }
        else
        {
          $odf->setVars('logo', ' ');
        }

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

        //fill the template with the variables
        $nama_izin = $jenis_izin->n_perizinan;
        $odf->setVars('nama_izin', strtoupper($nama_izin));
        $odf->setVars('nama_izin2', $nama_izin);
        $odf->setVars('no_surat', $surat->no_surat);
        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($surat->tgl_surat));
        $odf->setVars('jabatan', $pegawai->n_jabatan);
        $odf->setVars('nama_pejabat', strtoupper($pegawai->n_pegawai));
        $odf->setVars('nip_pejabat', $pegawai->nip);
        $odf->setVars('kantor', $app_kan->value);
        $odf->setVars('salinan', "");

        $wilayah = new trkabupaten();
        if($app_city !== '0'){
            $wilayah->get_by_id($app_city);
            $kota = $wilayah->ibukota;
            //$kota = $wilayah->n_kabupaten;
            $odf->setVars('kota', $kota);
        }else{
            $kota = "..............";
            $odf->setVars('kota', $kota);
        }

         $gede_kota=strtoupper($wilayah->n_kabupaten);
        $kecil_kota=ucwords(strtolower($wilayah->n_kabupaten));
        $odf->setVars('kota4', $gede_kota);

        //alamat
        $this->tr_instansi = new Tr_instansi();
        $alamat = $this->tr_instansi->get_by_id(12);
        $odf->setVars('alamat', ucwords(strtolower($alamat->value)).' - '.$kecil_kota);
        

        //Content Menimbang
        $list_menimbang = $permohonan->trperizinan->trmenimbang->get();
        $i = 1;
        $abjad = array('', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k',
            'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y','z');
        foreach($list_menimbang as $data){
            if($i == 1){
                $listeArticles = array(
                        array(  'property' => 'Menimbang',
                                'content' => ':',
                                'content1' => $abjad[$i].'.',
                                'content2' => $data->deskripsi,
                        ),
                );
            }else{
                $listeArticles = array(
                        array(  'property' => '',
                                'content' => '',
                                'content1' => $abjad[$i].'.',
                                'content2' => $data->deskripsi,
                        ),
                );
            }
            $article = $odf->setSegment('articles1');
            foreach($listeArticles AS $element) {
                    $article->titreArticle($element['property']);
                    $article->texteArticle($element['content']);
                    $article->texteArticle1($element['content1']);
                    $article->texteArticle2($element['content2']);
                    $article->merge();
            }
            $i++;
        }
        if($i == '1'){
            $listeArticles = array(
                    array(  'property' => 'Menimbang',
                            'content' => ':',
                            'content1' => '',
                            'content2' => '',
                    ),
            );
            $article = $odf->setSegment('articles1');
            foreach($listeArticles AS $element) {
                    $article->titreArticle($element['property']);
                    $article->texteArticle($element['content']);
                    $article->texteArticle1($element['content1']);
                    $article->texteArticle2($element['content2']);
                    $article->merge();
            }
        }
        $odf->mergeSegment($article);

        //Content Mengingat
        $list_mengingat = $permohonan->trperizinan->trmengingat->where('type', '0')->get();
        $i = 1;
        foreach($list_mengingat as $data){
            if($i == 1){
                $listeArticles = array(
                        array(  'property' => 'Mengingat',
                                'content' => ':',
                                'content1' => $i.'.',
                                'content2' => $data->deskripsi,
                        ),
                );
            }else{
                $listeArticles = array(
                        array(  'property' => '',
                                'content' => '',
                                'content1' => $i.'.',
                                'content2' => $data->deskripsi,
                        ),
                );
            }
            $article = $odf->setSegment('articles2');
            foreach($listeArticles AS $element) {
                    $article->titreArticle($element['property']);
                    $article->texteArticle($element['content']);
                    $article->texteArticle1($element['content1']);
                    $article->texteArticle2($element['content2']);
                    $article->merge();
            }
            $i++;
        }
        if($i == '1'){
            $listeArticles = array(
                    array(  'property' => 'Mengingat',
                            'content' => ':',
                            'content1' => '',
                            'content2' => '',
                    ),
            );
            $article = $odf->setSegment('articles2');
            foreach($listeArticles AS $element) {
                    $article->titreArticle($element['property']);
                    $article->texteArticle($element['content']);
                    $article->texteArticle1($element['content1']);
                    $article->texteArticle2($element['content2']);
                    $article->merge();
            }
        }
        $odf->mergeSegment($article);

        //Content Surat Keputusan
//        $list_keputusan = $surat;
//        $i = 1;
//        foreach($list_keputusan as $data){
//            $listeArticles = array(
//                    array(  'property' => 'Nama '.$data->ket1,
//                            'content' => ':',
//                            'content1' => $data->nama1,
//                    ),
//                    array(  'property' => 'Alamat',
//                            'content' => ':',
//                            'content1' => $data->alamat1,
//                    ),
//                    array(  'property' => 'Nama '.$data->ket2,
//                            'content' => ':',
//                            'content1' => $data->nama2,
//                    ),
//                    array(  'property' => 'Alamat',
//                            'content' => ':',
//                            'content1' => $data->alamat2,
//                    ),
//            );
//            $article = $odf->setSegment('articles3');
//            foreach($listeArticles AS $element) {
//                    $article->titreArticle($element['property']);
//                    $article->texteArticle($element['content']);
//                    $article->texteArticle1($element['content1']);
//                    $article->merge();
//            }
//            $i++;
//        }
//        if($i == '1'){
//            $listeArticles = array(
//                    array(  'property' => '',
//                            'content' => '',
//                            'content1' => '',
//                    ),
//            );
//            $article = $odf->setSegment('articles3');
//            foreach($listeArticles AS $element) {
//                    $article->titreArticle($element['property']);
//                    $article->texteArticle($element['content']);
//                    $article->texteArticle1($element['content1']);
//                    $article->merge();
//            }
//        }
//        $odf->mergeSegment($article);
        
        
        
        $listeArticles = array(
                array(  'property' => '',
                        'content' => '',
                        'content1' => '2.',
                        'content2' => $surat->content1,
                ),array(  'property' => '', 'content' => '', 'content1' => '', 'content2' => '',),
                array(  'property' => '',
                        'content' => '',
                        'content1' => '3.',
                        'content2' => $surat->content2,
                ),array(  'property' => '', 'content' => '', 'content1' => '', 'content2' => '',),
                array(  'property' => '',
                        'content' => '',
                        'content1' => '4.',
                        'content2' => $surat->content3,
                ),array(  'property' => '', 'content' => '', 'content1' => '', 'content2' => '',),
        );
        $article = $odf->setSegment('articles4');
        foreach($listeArticles AS $element) {
                $article->titreArticle($element['property']);
                $article->texteArticle($element['content']);
                $article->texteArticle1($element['content1']);
                $article->texteArticle2($element['content2']);
                $article->merge();
        }
        $odf->mergeSegment($article);
        $statussalinan=FALSE;
        //Content Salinan
        for($i=1;$i<=5;$i++){
            if($i == "1") $salinan = $surat->salinan1;
            else if($i == "2") $salinan = $surat->salinan2;
            else if($i == "3") $salinan = $surat->salinan3;
            else if($i == "4") $salinan = $surat->salinan4;
            else if($i == "5") $salinan = $surat->salinan5;
            if($salinan){
                $statussalinan=TRUE;
                $listeArticles = array(
                        array(  'property' => $i.'. ',
                                'content' => $salinan,
                        ),
                );
            }else{
                $listeArticles = array(
                        array(  'property' => '',
                                'content' => '',
                        ),
                );
            }
            $article = $odf->setSegment('articles5');
            foreach($listeArticles AS $element) {
                    $article->titreArticle($element['property']);
                    $article->texteArticle($element['content']);
                    $article->merge();
                    
            }
        }
        if($statussalinan)
            {
                $odf->setVars('salinan', "Salinan : ");
            }
        $odf->mergeSegment($article);

          $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql($u_ser);
//      $jam = date("H:i:s A");
        $p = $this->db->query("call log ('Pembuatan Izin','Cetak SK ".$permohonan->pendaftaran_id."','".$tgl."','".$u_ser."')");

        //Content Property
        
         $perizinan = new trperizinan();
        $perizinan->get_by_id($jenis_izin->id);
        $list_daftar = $permohonan->tmproperty_jenisperizinan->get();
        $lists = $perizinan->trproperty->include_join_fields()->where('c_type', 2)->order_by('c_parent_order', "asc")->get();

        $property = $odf->setSegment('property');
        foreach ($lists as $list) {
            $property->nama($list->n_property);
            $children = $perizinan->trproperty->where('c_sk_id', 1)->where_join_field($perizinan, 'c_parent', $list->id)->include_join_fields()->order_by('c_order', "asc")->get();
            foreach ($children as $child_) {
                if ($list->id !== $child_->id) {
                    $property->child->child($child_->n_property);
// ................................ Isi ...........................
                    if ($list_daftar->id) {
                        foreach ($list_daftar as $data_daftar) {
                            $entry_property = new tmproperty_jenisperizinan_trproperty();
                            $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                                    ->where('trproperty_id', $child_->id)->get();
                            $izin_property = new trperizinan_trproperty();
                            $izin_property->where('trperizinan_id', $jenis_izin->id)
                                    ->where('trproperty_id', $child_->id)->get();
                            if ($entry_property->tmproperty_jenisperizinan_id) {
                                $entry_daftar = new tmproperty_jenisperizinan();
                                $koefret = new trkoefesientarifretribusi();
                                $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);
                                $pil = $koefret->get_by_id($entry_daftar->k_tinjauan);
                                $data_koefisient = $entry_daftar->v_tinjauan;
                                $isilow = strtolower($pil->kategori . " " . $data_koefisient . " " . $izin_property->satuan);
                                $isi = ucwords($isilow);
                                $property->child->isi($isi);
                            }
                        }
                    }

                    if ($child_->join_c_retribusi_id === '1') {
                        $property->child->indeks("");
                    } else {
                        $property->child->indeks("");
                    }
                    $property->child->merge();
                }
            }
            $property->merge();
        }
        $odf->mergeSegment($property);
        
        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat.'_'.$no_daftar.'.odt');*/
		
		##########################
		redirect('report_generator/cetak/SK/'.$id_daftar.'/'.$trperizinan_id);
    }

      public function sql($u_ser)
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
