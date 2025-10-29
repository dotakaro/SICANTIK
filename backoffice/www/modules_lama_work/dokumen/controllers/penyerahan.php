<?php

/**
 * Description of Penyerahan Salinan Dokumen
 *
 * @author agusnur
 * Created : 22 Sep 2010
 */

class Penyerahan extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->sk = new tmsk();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '15') {
                $enabled = TRUE;
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
//        $daftar = new tmpermohonan();
//        $query = $daftar
//                ->where('c_pendaftaran', 1) //1->Pendaftaran Belum selesai
//                ->where('c_izin_selesai', 1) //1->SK diserahkan
//                ->where('c_izin_dicabut', 0) //Permohonan tidak dicabut
//                ->order_by('d_terima_berkas', 'DESC')->limit(1500)->get();
        $tgla = $this->input->post('tgla');
        $tglb = $this->input->post('tglb');
        $now = $this->lib_date->get_date_now();
        $tgl_before = $this->lib_date->set_date($now, -2);
        $tgl_now = $this->lib_date->set_date($now, 0);

        if($tgla && $tglb){
            $data['tgla'] = $tgla;
            $data['tglb'] = $tglb;
        }else{
            $tgla = $tgl_before;
            $tglb = $tgl_now;
            $data['tgla'] = $tgla;
            $data['tglb'] = $tglb;
        }
        $username = new user();
        $username->where('username', $this->session->userdata('username'))->get();
        $query = "SELECT A.id, A.pendaftaran_id, A.d_terima_berkas, A.file_ttd,
        A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
        C.n_perizinan, E.n_pemohon, G.id idjenis,
        K.id idsk, K.tgl_surat, K.no_surat, K.c_status_salinan
        FROM tmpermohonan as A
        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
        INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
        INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
	INNER JOIN tmbap_tmpermohonan H ON A.id = H.tmpermohonan_id
	INNER JOIN tmbap I ON H.tmbap_id = I.id
	INNER JOIN tmpermohonan_tmsk J ON A.id = J.tmpermohonan_id
	INNER JOIN tmsk K ON J.tmsk_id = K.id
         INNER JOIN trperizinan_user AS L ON L.trperizinan_id = C.id
        WHERE A.c_pendaftaran = 1
        AND A.c_izin_dicabut = 0
        AND A.c_izin_selesai = 1
        AND K.c_is_requested = 1
        AND K.c_status_salinan != 0
        AND I.status_bap = 1
         AND L.user_id = '".$username->id."'
        AND A.d_terima_berkas between '$tgla' and '$tglb'
        AND A.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})
        AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
        order by A.id DESC";
        $data['list'] = $query;
        $data['c_bap'] = "1";
        $this->load->vars($data);

        $js =  "
                function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }

                $(document).ready(function() {
                        oTable = $('#penyerahan').dataTable({
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
        $this->session_info['page_name'] = "Penyerahan Salinan Surat Izin";
        $this->template->build('penyerahan_list', $this->session_info);
    }

    public function status($id = NULL) {
        $status_salinan = NULL;
        $sk = new tmsk();
        $sk->where('id', $id)->get();
        $count = intval($sk->c_status_salinan_order);
        $status_salinan = 3;
        $sk->where('id',$id)
                ->update(array(
            'c_is_requested' => '0',
            'c_status_salinan' => '0',
        ));

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql2($u_ser);
        $pendaftaran = $this->get_pendaftaran($id);
        $p = $this->db->query("call log ('Penyerahan Salinan','Penyerahan Salinan ".$pendaftaran->pendaftaran_id."','".$tgl."','".$u_ser."')");

        redirect('dokumen/penyerahan');
    }

    /*
     * Save and update for manipulating data.
     */
    public function save() {
        $id_daftar = $this->input->post('id_daftar');
        //$file_ttd = $this->input->post('file_ttd');
        $file_ttd = $_FILES['file_ttd'];

        /* (pretty self-explanatory) */
        $nama_gambar = 'ttd_'.$id_daftar.".png";
        $pathfile = "assets/upload/ttd/";

        if($file_ttd['size'] > 0){            
            /* Create upload file */
//            $lebar_maks = 300;
//            $src_img = imagecreatefromjpeg($file_ttd['tmp_name']);
//            $lebar_awal = imagesx($src_img);
//            $tinggi_awal = imagesy($src_img);
//
//            if($lebar_awal > $lebar_maks) $new_w = $lebar_maks;
//            else $new_w = $lebar_awal;
//            $new_h = ($new_w / $lebar_awal) * $tinggi_awal;
//            $dst_img = imagecreatetruecolor($new_w,$new_h);
//            imagecopyresized($dst_img,$src_img,0,0,0,0,$new_w,$new_h,imagesx($src_img),imagesy($src_img));
//            imagejpeg($dst_img, $pathfile.$nama_gambar);
            copy($file_ttd['tmp_name'], $pathfile.$nama_gambar);
            
            $daftar = new tmpermohonan();
            $daftar->get_by_id($id_daftar);
            $daftar->file_ttd = $nama_gambar;

            $update = $daftar->save();
        }

        if(!$update) {
            echo '<p>' . $daftar->error->string . '</p>';
        } else {
            redirect('dokumen/penyerahan');
        }
    }

    public function cetak($id_daftar = NULL, $id_sk = NULL) {

//        $this->sk->where('id',$id_sk)
//                ->update(array(
//            'c_is_requested' => '0',
//            'c_status_salinan' => '0'
//        ));
        
        $nama_surat = "cetak_sk_salinan";
        $this->settings = new settings();
        $this->settings->where('name','app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name','app_city')->get();

        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($id_daftar);
        $pemohon = $permohonan->tmpemohon->get();
         
		$surat = $permohonan->tmsk->get();
        $surat_sk = new tmsk();
        $surat_sk->where('id', $surat->id)->update(array('c_status' => 1));

        $jenis_izin = $permohonan->trperizinan->get();
		
		
		$petugas = $surat->tmpegawai->get();
		
		/*Added by Indra*/
		$trperizinan_id = $jenis_izin->id;
		redirect('report_generator/cetak/IZIN_COPY/'.$id_daftar.'/'.$trperizinan_id);
		/***************/
		
		
		/*Remarked by Indra
        //path of the template file
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/'.$nama_surat.'.odt');
//        $odf->setImage('header', 'assets/css/'.$app_folder.'/images/dinas_1.jpg', '17.5', '3.5');

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
       
        if($permohonan->nip_ttd)
        {
            $odf->setVars ('ttd', $permohonan->nip_ttd);
        }        
        else 
        {
            $odf->setVars ('ttd', '');
        }
        //fill the template with the variables
        $nama_izin = $jenis_izin->n_perizinan;
        $data_judul = $jenis_izin->c_judul;
        $odf->setVars('nama_izin', $nama_izin);
        $odf->setVars('no_surat', $surat->no_surat);
        $tgl_skr = $this->lib_date->get_date_now();
//        $tgl_berlaku = $this->lib_date->set_date($tgl_skr, $jenis_izin->v_berlaku_tahun * 365);
//        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($surat->tgl_surat, 1));
        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($tgl_skr));
        $odf->setVars('jabatan', $petugas->n_jabatan);
        $odf->setVars('nama_pejabat', $petugas->n_pegawai);
        $odf->setVars('nip_pejabat', $petugas->nip);
//        $odf->setVars('nama_pejabat', $permohonan->nama_ttd);
//        $odf->setVars('nip_pejabat', $permohonan->nip_ttd);
        if($jenis_izin->c_foto == 1){
        $odf->setVars('ket_pemohon', "Tanda tangan pemegang");
        $odf->setVars('nama_pemohon', strtoupper($pemohon->n_pemohon));
        }else{
        $odf->setVars('ket_pemohon', "");
        $odf->setVars('nama_pemohon', "");
        }
        $wilayah = new trkabupaten();
        if($app_city->value !== '0'){
            $wilayah->get_by_id($app_city->value);
            $kota = ucwords(strtolower($wilayah->ibukota));
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
        

        //Head Ketetapan SK
        $listeArticles = array(
                array(	'property' => '',
                        'content' => 'Berdasarkan :',
                ),
        );
        $article = $odf->setSegment('articles1');
        foreach($listeArticles AS $element) {
                $article->titreArticle($element['property']);
                $article->texteArticle($element['content']);
                $article->merge();
        }
        $odf->mergeSegment($article);

        //Content Ketetapan SK
        $list_ketetapan = $permohonan->trperizinan->trdasar_hukum->where('type','0')->get();
        $i = 1;
        foreach($list_ketetapan as $data){
            $listeArticles = array(
                    array(  'property' => $i.'.',
                            'content' => $data->deskripsi,
                    ),
            );
            $article = $odf->setSegment('articles2');
            foreach($listeArticles AS $element) {
                    $article->titreArticle($element['property']);
                    $article->texteArticle($element['content']);
                    $article->merge();
            }
            $i++;
        }
        if($i == '1'){
            $listeArticles = array(
                    array(  'property' => '',
                            'content' => '',
                    ),
            );
            $article = $odf->setSegment('articles2');
            foreach($listeArticles AS $element) {
                    $article->titreArticle($element['property']);
                    $article->texteArticle($element['content']);
                    $article->merge();
            }
        }
        $odf->mergeSegment($article);

        //Head Property
        if($data_judul == "1") $head = "Mengizinkan";
        else $head = "Memberikan ".$nama_izin." kepada";
        $listeArticles = array(
                array(	'property' => '',
                        'content' => $head.' :',
                ),
        );
        $article = $odf->setSegment('articles3');
        foreach($listeArticles AS $element) {
                $article->titreArticle($element['property']);
                $article->texteArticle($element['content']);
                $article->merge();
        }
        $odf->mergeSegment($article);

        //Content Property
        $i = 1;
        $list_property = $permohonan->trperizinan->trproperty->order_by('c_parent_order asc, c_order asc')->get();
        $list_content = $permohonan->tmproperty_jenisperizinan->get();
        foreach($list_property as $data){
           $property_satuan = new trperizinan_trproperty();
           $property_satuan->where('trproperty_id', $data->id)->get();
            if($list_content->id){
                foreach ($list_content as $data_daftar){
                    $entry_property = new tmproperty_jenisperizinan_trproperty();
                    $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                    ->where('trproperty_id', $data->id)->get();
                    if($entry_property->tmproperty_jenisperizinan_id){
                        $entry_daftar = new tmproperty_jenisperizinan();
                        $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);

                        $kelompok = $permohonan->trperizinan->trkelompok_perizinan->get();
                        if($kelompok->id == 2 || $kelompok->id == 4){
                            $data_entry = $entry_daftar->v_tinjauan;
                            $id_koefisien = $entry_daftar->k_tinjauan;
                        }else{
                            $data_entry = $entry_daftar->v_property;
                            $id_koefisien = $entry_daftar->k_property;
                        }

                        $izin_property = new trperizinan_trproperty();
                        $izin_property->where('trperizinan_id', $jenis_izin->id)
                        ->where('trproperty_id', $data->id)->get();
                        $id_sk = $izin_property->c_sk_id;
                        if($id_sk == '1'){
                            if($data->c_type == '1'){
                                $data_koefisien = new trkoefesientarifretribusi();
                                $data_koefisien->get_by_id($id_koefisien);
                                $no = '';
                                $data_property = $data->n_property;
//                                if($data_entry) $all_entry = $data_koefisien->kategori.' ('.$data_entry.')';
//                                else $all_entry = $data_koefisien->kategori;
                                if($data_entry) $all_entry = $data_entry;
                                else $all_entry = '';
                                $titik = ":";
                                $i++;
                            }else if($data->c_type == '2'){
                                $no = '';
                                $data_property = $data->n_property;
                                $titik = "";
                                $all_entry = "";
                            }else{
                                $no = '';
                                $data_property = $data->n_property;
                                $titik = ":";
                                $all_entry = $data_entry." ".$property_satuan->satuan;
                                $i++;
                            }
                            $listeArticles = array(
                                    array(  'no' => '',
                                            'property' => $data_property,
                                            'titik' => $titik,
                                            'content' => $all_entry,
                                    ),
                            );
                            $article = $odf->setSegment('articles4');
                            foreach($listeArticles AS $element) {
                                    $article->titreArticle($element['no']);
                                    $article->texteArticle2($element['property']);
                                    $article->texteArticle3($element['titik']);
                                    $article->texteArticle($element['content']);
                                    $article->merge();
                            }
                        }
                    }
                }
            }
//            if(empty($data_entry)) $data_entry = '';
//            if(empty($id_koefisien)) $id_koefisien = '';
        }
        if($i == '1'){
            $listeArticles = array(
                    array(  'no' => '',
                            'property' => '',
                            'titik' => '',
                            'content' => '',
                    ),
            );
            $article = $odf->setSegment('articles4');
            foreach($listeArticles AS $element) {
                        $article->titreArticle($element['no']);
                        $article->texteArticle2($element['property']);
                        $article->texteArticle3($element['titik']);
                        $article->texteArticle($element['content']);
                    $article->merge();
            }
        }
        $odf->mergeSegment($article);

        //Head Property
        $listeArticles = array(
                array(	'property' => '',
                        'content' => 'Dengan ketentuan :',
                ),
        );
        $article = $odf->setSegment('articles5');
        foreach($listeArticles AS $element) {
                $article->titreArticle($element['property']);
                $article->texteArticle($element['content']);
                $article->merge();
        }
        $odf->mergeSegment($article);

        $i = 1;
        $list_ketentuan = $permohonan->trperizinan->trketetapan->get();
        foreach($list_ketentuan as $data){
            $listeArticles = array(
                    array(  'property' => $i.'.',
                            'content' => $data->n_ketetapan,
                    ),
            );
            $article = $odf->setSegment('articles6');
            foreach($listeArticles AS $element) {
                    $article->titreArticle($element['property']);
                    $article->texteArticle($element['content']);
                    $article->merge();
            }
            $i++;
        }
        if($i == '1'){
            $listeArticles = array(
                    array(  'property' => '',
                            'content' => '',
                    ),
            );
            $article = $odf->setSegment('articles6');
            foreach($listeArticles AS $element) {
                    $article->titreArticle($element['property']);
                    $article->texteArticle($element['content']);
                    $article->merge();
            }
        }
        $odf->mergeSegment($article);

        //Content Retribusi
        $data_bap = $permohonan->tmbap->get();
        $retribusi = $data_bap->nilai_retribusi;
        $keringanan = $permohonan->tmkeringananretribusi->get();
        if ($keringanan->id)
            $nilai_ret = ($keringanan->v_prosentase_retribusi * 0.01) * $retribusi;
        else
            $nilai_ret = $retribusi;
        $odf->setVars('nor', $i.'. ');
        if($nilai_ret) $nilair = $this->terbilang->nominal($nilai_ret, 2);
        else $nilair = "0";
        $izin_kelompok = $jenis_izin->trkelompok_perizinan->get();
        if($izin_kelompok->id == 4) $ket_retribusi = "Wajib membayar retribusi sebesar Rp. ".$nilair;
        else $ket_retribusi = "Proses penerbitan izin ini tidak dikenai retribusi";
        $odf->setVars('retribusi', $ket_retribusi);
        $i++;

        //Content Masa Berlaku
        $berlaku = $permohonan->d_berlaku_izin;
        if($jenis_izin->c_berlaku == 1){
        $odf->setVars('nob', $i.'. ');
//        if($berlaku){
            if($berlaku != '0000-00-00') $nilaib = $this->lib_date->mysql_to_human($berlaku);
            else $nilaib = "..............";
//        }
//        else $nilaib = "..............";
        if($jenis_izin->id == 2 || $jenis_izin->id == 3 || $jenis_izin->id == 88)
        $masa_berlaku = $jenis_izin->n_perizinan." ini berlaku sepanjang bangunan, pemilik dan fungsi bangunan tidak mengalami perubahan.";
        else $masa_berlaku = $jenis_izin->n_perizinan.' ini berlaku sampai dengan '.$nilaib;
        }else{
            $odf->setVars('nob', '');
            $masa_berlaku = '';
        }
        $odf->setVars('masaberlaku', $masa_berlaku);

        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql2($u_ser);
        $p = $this->db->query("call log ('Penyerahan Salinan','Cetak Salinan ".$no_daftar."','".$tgl."','".$u_ser."')");

        //export the file
        
        $odf->exportAsAttachedFile($nama_surat.'_'.$no_daftar.'.odt');
		*/
    }

    public function hapus($id = NULL) {
        $sk = new tmsk();
        $sk->where('id', $id)->get();
        $sk->where('id',$id)
                ->update(array(
            'c_is_requested' => '0',
            'c_status_salinan' => '0',
        ));

        redirect('dokumen/penyerahan');
    }

     public function sql2($u_ser)
    {
        $query = "select a.description from user_auth as a
                  inner join user_user_auth as  x on a.id = x.user_auth_id
                  inner join user as b on b.id = x.user_id
                  where b.id = (select id from user where username='".$u_ser."')";
        $hasil = $this->db->query($query);
        return $hasil->row();
    }

    public function get_pendaftaran($id)
    {
        $query="select a.pendaftaran_id from tmpermohonan as a
                inner join tmpermohonan_tmsk as b on a.id=b.tmpermohonan_id
                inner join tmsk as c on b.tmsk_id=c.id
                where c.id = '".$id."';";

        $hasil = $this->db->query($query);
        return $hasil->row();
    }

}

// This is the end of role class
