<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of pesan class
 *
 * @author  Yogi Cahyana
 * @since   1.0
 *
 */

class Pesanbalasan extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->pesan = new tmpesan();
        $this->status = new trstspesan();
        $this->sumber = new trsumber_pesan();
        $this->pesanbalasan = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->pesanbalasan = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '14') {
                $enabled = TRUE;
                $this->pesanbalasan = new user_auth();
            }
        }
		
        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
        $first_date = $this->input->post('tgla')==null?$this->lib_date->set_date(date('Y-m-d'), -2):$this->input->post('tgla');
        $second_date = $this->input->post('tglb')==null?$this->lib_date->set_date(date('Y-m-d'), 0):$this->input->post('tglb');
        
        $data['list'] = $this->pesan->where('c_tindak_lanjut', 'Ya')->where("d_entry BETWEEN '$first_date' AND '$second_date'")->where('c_sts_setuju','Ya')->order_by('id', 'ASC')->get();
        $data['liststatus'] = $this->status->get();
        $data['listsumber'] = $this->sumber->get();
        
        $data['tgla'] =$first_date;
        $data['tglb'] =$second_date;

        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                 $('#form').validate();
                        oTable = $('#pesan').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                        $('.page-help').each(function() {
                                var \$link = $(this);
                                var \$dialog = $('<div></div>')
                                        .load(\$link.attr('href') + ' #content')
                                        .dialog({
                                                autoOpen: false,
                                                modal: true,
                                                show:'blind',
                                                hide:'blind',
                                                title: \$link.attr('title'),
                                                width: 500,
                                                height: 300,
                                                buttons: {
                                                    'Tutup': function() {
                                                        $(this).dialog('close');
                                                    },
                                                    'Simpan' : function() {
                                                        $(this).dialog('close');
                                                    }
                                                }
                                        });

                                \$link.click(function() {
                                        \$dialog.dialog('open');
                                        return false;
                                });
                        });
                } );
                 $(function() {
                $(\".cetak\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Pesan Pengaduan Dan Balasan Pengaduan";
        $this->template->build('listbalasan', $this->session_info);
    }
    public function filterdata() {
        $tgla = $this->input->post('tgla');
        $tglb = $this->input->post('tglb');

        $data['list'] = $this->pesan->where('c_tindak_lanjut', 'Ya')->where("d_entry BETWEEN '$tgla' AND '$tglb'")->where('c_sts_setuju','Ya')->order_by('id', 'ASC')->get();
        $data['liststatus'] = $this->status->get();
        $data['listsumber'] = $this->sumber->get();

        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#pesan').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                        $('.page-help').each(function() {
                                var \$link = $(this);
                                var \$dialog = $('<div></div>')
                                        .load(\$link.attr('href') + ' #content')
                                        .dialog({
                                                autoOpen: false,
                                                modal: true,
                                                show:'blind',
                                                hide:'blind',
                                                title: \$link.attr('title'),
                                                width: 500,
                                                height: 300,
                                                buttons: {
                                                    'Tutup': function() {
                                                        $(this).dialog('close');
                                                    },
                                                    'Simpan' : function() {
                                                        $(this).dialog('close');
                                                    }
                                                }
                                        });

                                \$link.click(function() {
                                        \$dialog.dialog('open');
                                        return false;
                                });
                        });
                } );
                 $(function() {
                $(\".cetak\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Pesan Pengaduan Dan Balasan Pengaduan";
        $this->template->build('view_cetak', $this->session_info);
    }

     public function cetak($tgla = null, $tglb = null) {
        $this->settings = new settings();
        $this->settings->where('name','app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name','app_city')->get();

        $pengaduan = new tmpesan();
        $pengaduan->get();
        $status = $pengaduan->trstspesan->get();
        $sumber = $pengaduan->trsumber_pesan->get();

        //path of the template file
        $nama_surat = "lap_pengaduan";
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
             $wilayah->get_by_id($app_city->value);
            //$odf->setVars('kabupaten', ucwords(strtolower($wilayah->n_kabupaten)));
              $gede_kota=strtoupper($wilayah->n_kabupaten);
        $kecil_kota=ucwords(strtolower($wilayah->n_kabupaten));
        $odf->setVars('kota4', $gede_kota);

        } else {
          
            //$odf->setVars('kabupaten', 'setempat');
             $odf->setVars('kota4', '------------');
        }
     //alamat
        $this->tr_instansi = new Tr_instansi();
        $alamat = $this->tr_instansi->get_by_id(12);
        $odf->setVars('alamat', ucwords(strtolower($alamat->value)).' - '.$kecil_kota);


//        $odf->setVars('tanggal', $this->lib_date->mysql_to_human(date('YYYY/mm/dd')));
        $odf->setVars('title','Periode tanggal : '.$this->lib_date->mysql_to_human($tgla).' s/d '.$this->lib_date->mysql_to_human($tglb));

        $i = 1;

                $list = $this->pesan->where('c_tindak_lanjut', 'Ya')
                                    ->where("d_entry BETWEEN '$tgla' AND '$tglb'")
                                    ->where('c_sts_setuju','Ya')->order_by('id', 'ASC')->get();

                       if($this->pesan->id){
                       foreach ($list as $data){
                       $data->trstspesan->get();
                       $data->trsumber_pesan->get();


        $listeArticles3 = array(
                array(	'property' =>  $i,
                        'content1' =>  $this->lib_date->mysql_to_human($data->d_entry).' '.$data->nama.', '.$data->alamat.', Surat diterima tanggal '. $this->lib_date->mysql_to_human($data->d_tindak_lanjut),
                        'content2' =>  $data->e_pesan,
                        'content3' =>  $data->c_skpd_tindaklanjut,
                        'content4' =>  $data->e_tindak_lanjut,
                ),

        );

        $article3 = $odf->setSegment('articles3');
        foreach($listeArticles3 AS $element) {

                $article3->titreArticle3($element['property']);
                $article3->texteArticle1($element['content1']);
                $article3->texteArticle2($element['content2']);
                $article3->texteArticle3($element['content3']);
                $article3->texteArticle4($element['content4']);
                $article3->merge();
        }

        $i++;
        }
                        }else{
        $listeArticles3 = array(
                array(	'property' =>  '',
                        'content1' =>  '',//$data->n_perizinan,
                        'content2' =>  '',
                        'content3' =>  '',
                        'content4' =>  '',
                ),

        );


        $article3 = $odf->setSegment('articles3');
        foreach($listeArticles3 AS $element) {

                $article3->titreArticle3($element['property']);
                $article3->texteArticle1($element['content1']);
                $article3->texteArticle2($element['content2']);
                $article3->texteArticle3($element['content3']);
                $article3->texteArticle4($element['content4']);
                $article3->merge();
        }
                        }
        $odf->mergeSegment($article3);

        //export the file
        $no_daftar = str_replace('/', '', $pengaduan->id);
        $odf->exportAsAttachedFile($nama_surat.'_'.$no_daftar.'.odt');
    }

     public function cetak_jawaban($id_pesan = null) {
        $this->settings = new settings();
        $this->settings->where('name','app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name','app_city')->get();

        $pengaduan = new tmpesan();
        $pengaduan->get_by_id($id_pesan);
        $status = $pengaduan->trstspesan->get();
        $sumber = $pengaduan->trsumber_pesan->get();
        //path of the template file
        $nama_surat = "surat_jawaban";
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
        $this->tr_instansi = new Tr_instansi();
        $alamat = $this->tr_instansi->get_by_id(12);
        
        if ($app_city->value !== '0') {
             $wilayah->get_by_id($app_city->value);
            //$odf->setVars('kabupaten', ucwords(strtolower($wilayah->n_kabupaten)));
              $gede_kota=strtoupper($wilayah->n_kabupaten);
        $kecil_kota=ucwords(strtolower($wilayah->n_kabupaten));
        $odf->setVars('kota4', $gede_kota);
             //alamat
        $odf->setVars('alamat', ucwords(strtolower($alamat->value)).' - '.$kecil_kota);
        $odf->setVars('kota', ucwords(strtolower($wilayah->ibukota)));
        

        } else {

            //$odf->setVars('kabupaten', 'setempat');
             $odf->setVars('kota4', '------------');
             $odf->setVars('kota','--------------');
        }
        
        $app_kantor = $this->settings->where('name','app_kantor')->get();
          if ($app_kantor->value !== '0')
          {
            $odf->setVars('kantor',$app_kantor->value);
          }
          else
          {
            $odf->setVars('kantor','---------');
          }
         
        $petugas = 1;
        $pegawai = new tmpegawai();
        $pegawai->where('status', $petugas)->get();
        $odf->setVars('jabatan', strtoupper($pegawai->n_jabatan));
        $odf->setVars('nama_pejabat', strtoupper($pegawai->n_pegawai));
        $odf->setVars('nip_pejabat', $pegawai->nip);
        

//        $odf->setVars('tanggal', $this->lib_date->mysql_to_human(date('YYYY/mm/dd')));


                       $list = $this->pesan->where('id', $id_pesan)->get();
                       foreach ($list as $data){
                       $data->trstspesan->get();
                       $data->trsumber_pesan->get();

//$odf->setVars('nama', $this->lib_date->mysql_to_human($data->d_entry).' '.$data->nama.', '.$data->alamat.', Surat diterima tanggal '. $this->lib_date->mysql_to_human($data->d_tindak_lanjut));
                        $odf->setVars('nama', $data->nama);
                         $odf->setVars('alamat_pemohon', $data->alamat);
                        $odf->setVars('isi', $data->e_pesan_koreksi);
                        $odf->setVars('balasan', $data->e_tindak_lanjut);
                        $odf->setVars('tgl_masuk', $this->lib_date->mysql_to_human($data->d_entry));

                        }

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql2($u_ser);
        $p = $this->db->query("call log ('Daftar Balasan','Cetak Surat Balasan ".$pengaduan->nama."','".$tgl."','".$u_ser."')");

        //export the file
        $no_daftar = str_replace('/', '', $pengaduan->id);
        $odf->exportAsAttachedFile($nama_surat.'_'.$no_daftar.'.odt');
    }

    public function cetakSop($tgla = null, $tglb = null) {
        $this->settings = new settings();
        $this->settings->where('name','app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name','app_city')->get();

        $pengaduan = new tmpesan();
        $pengaduan->get();
        $status = $pengaduan->trstspesan->get();
        $sumber = $pengaduan->trsumber_pesan->get();

        //path of the template file
        $nama_surat = "lap_pengaduan_detail";
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
             $wilayah->get_by_id($app_city->value);
            //$odf->setVars('kabupaten', ucwords(strtolower($wilayah->n_kabupaten)));
              $gede_kota=strtoupper($wilayah->n_kabupaten);
        $kecil_kota=ucwords(strtolower($wilayah->n_kabupaten));
        $odf->setVars('kota4', $gede_kota);

        } else {

            //$odf->setVars('kabupaten', 'setempat');
             $odf->setVars('kota4', '------------');
        }

          //alamat
        $this->tr_instansi = new Tr_instansi();
        $alamat = $this->tr_instansi->get_by_id(12);
        $odf->setVars('alamat', ucwords(strtolower($alamat->value)).' - '.$kecil_kota);


//        $odf->setVars('tanggal', $this->lib_date->mysql_to_human(date('YYYY/mm/dd')));
        $odf->setVars('title','Periode tanggal : '.$this->lib_date->mysql_to_human($tgla).' s/d '.$this->lib_date->mysql_to_human($tglb));
//......................................................................................................
        $i = 1;
        $sumber = $this->sumber->where('sop !=', '1')->get();
        foreach ($sumber as $sum){
                $list = $this->pesan
                        ->where_related($sum)
                        ->where('c_tindak_lanjut', 'Ya')
                        ->where("d_entry BETWEEN '$tgla' AND '$tglb'")
                        ->where('c_sts_setuju','Ya')
                        ->order_by('id', 'ASC')->get();
                       if($this->pesan->id){
                       foreach ($list as $data){
                       $data->trstspesan->get();
                       $data->trsumber_pesan->get();

                       
        $listeArticles3 = array(
                array(	'property' =>  $i,
                        'content1' =>  $this->lib_date->mysql_to_human($data->d_entry).' '.$data->nama.', '.$data->alamat.', Surat diterima tanggal '. $this->lib_date->mysql_to_human($data->d_tindak_lanjut),
                        'content2' =>  $data->e_pesan,
                        'content3' =>  $data->c_skpd_tindaklanjut,
                        'content4' =>  $data->e_tindak_lanjut,
                ),

        );
        $article3 = $odf->setSegment('articles3');
        foreach($listeArticles3 AS $element) {

                $article3->titreArticle3($element['property']);
                $article3->texteArticle1($element['content1']);
                $article3->texteArticle2($element['content2']);
                $article3->texteArticle3($element['content3']);
                $article3->texteArticle4($element['content4']);
                $article3->merge();
        }

        $i++;
        }
                        }else{
        $listeArticles3 = array(
                array(	'property' =>  '',
                        'content1' =>  '',//$data->n_perizinan,
                        'content2' =>  '',
                        'content3' =>  '',
                        'content4' =>  '',
                ),

        );


        $article3 = $odf->setSegment('articles3');
        foreach($listeArticles3 AS $element) {

                $article3->titreArticle3($element['property']);
                $article3->texteArticle1($element['content1']);
                $article3->texteArticle2($element['content2']);
                $article3->texteArticle3($element['content3']);
                $article3->texteArticle4($element['content4']);
                $article3->merge();
        }
        }
                        }
        $odf->mergeSegment($article3);
//......................................................................................................
   $i = 1;
        $sumber = $this->sumber->where('sop', '1')->get();
        foreach ($sumber as $sum){
                $list = $this->pesan
                        ->where_related($sum)
                        ->where('c_tindak_lanjut', 'Ya')
                        ->where("d_entry BETWEEN '$tgla' AND '$tglb'")
                        ->where('c_sts_setuju','Ya')
                        ->order_by('id', 'ASC')->get();
                       if($this->pesan->id){
                       foreach ($list as $data){
                       $data->trstspesan->get();
                       $data->trsumber_pesan->get();


        $listeArticles1 = array(
                array(	'property' =>  $i,
                        'content1' =>  $this->lib_date->mysql_to_human($data->d_entry).' '.$data->nama.', '.$data->alamat.', Surat diterima tanggal '. $this->lib_date->mysql_to_human($data->d_tindak_lanjut),
                        'content2' =>  $data->e_pesan,
                        'content3' =>  $data->c_skpd_tindaklanjut,
                        'content4' =>  $data->e_tindak_lanjut,
                ),

        );

        $article1 = $odf->setSegment('articles1');
        foreach($listeArticles1 AS $element) {

                $article1->titreArticle1($element['property']);
                $article1->texteArticle1($element['content1']);
                $article1->texteArticle2($element['content2']);
                $article1->texteArticle3($element['content3']);
                $article1->texteArticle4($element['content4']);
                $article1->merge();
        }

        $i++;
        }
                        }else{
        $listeArticles1 = array(
                array(	'property' =>  '',
                        'content1' =>  '',//$data->n_perizinan,
                        'content2' =>  '',
                        'content3' =>  '',
                        'content4' =>  '',
                ),

        );


        $article1 = $odf->setSegment('articles1');
        foreach($listeArticles1 AS $element) {

                $article1->titreArticle1($element['property']);
                $article1->texteArticle1($element['content1']);
                $article1->texteArticle2($element['content2']);
                $article1->texteArticle3($element['content3']);
                $article1->texteArticle4($element['content4']);
                $article1->merge();
        }
        }
                        }
        $odf->mergeSegment($article1);

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql2($u_ser);
        $p = $this->db->query("call log ('Daftar Balasan','Cetak Detail','".$tgl."','".$u_ser."')");


        //export the file
        $no_daftar = str_replace('/', '', $pengaduan->id);
        $odf->exportAsAttachedFile($nama_surat.'_'.$no_daftar.'.odt');
    }

     public function sql2($u_ser)
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

// This is the end of pesan class
