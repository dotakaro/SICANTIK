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

class Pesanpengiriman extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->pesan = new tmpesan();
        $this->stspesan = new trstspesan();
        $this->pesanpengiriman = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->pesanpengiriman = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '14') {
                $enabled = TRUE;
                $this->pesanpengiriman = new user_auth();
            }
        }
		
        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
        $data['list'] = $this->pesan->where("c_tindak_lanjut <> 'Hapus' " )
                                    ->where('c_sts_setuju','Ya')->order_by('id', 'ASC')->get();
        $data['liststspesan'] = $this->stspesan->get();

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
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Pengiriman Respon Pengaduan";
        $this->template->build('listpengiriman', $this->session_info);
    }

    public function create() {
        // menampilakan combobox
        $stspesan = new trstspesan();
        $data['list_pesan'] = $stspesan->order_by('id','ASC')->get()->all;

         $js_date = "
            $(function() {
                $(\"#pesan\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
            ";
        $this->template->set_metadata_javascript($js_date);


        $data['e_pesan']  = "";
        $data['list_stspesan']  = "";
        $data['RbTindakLanjut']  = "";
        $data['d_entry']  = "";
        $data['nama']  = "";
        $data['alamat']  = "";
        $data['kelurahan']  = "";
        $data['kecamatan']  = "";
        $data['save_method'] = "save";
        $data['jenis_status'] = $this->pesan->get_by_id($this->input->post('jenis_status'));

        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Pesan";
        $this->template->build('create', $this->session_info);
    }

    public function save() {

        $this->pesan->e_pesan = $this->input->post('e_pesan');
        $this->pesan->c_tindak_lajut = $this->input->post('RbTindakLanjut');
        $this->pesan->nama = $this->input->post('nama');
        $this->pesan->alamat = $this->input->post('alamat');
        $this->pesan->kelurahan = $this->input->post('kelurahan');
        $this->pesan->kecamatan = $this->input->post('kecamatan');
        $this->pesan->d_entry = $this->input->post('d_entry');

        if(! $this->pesan->save()) {
            echo '<p>' . $this->pesan->error->string . '</p>';
        } else {
            redirect('pesan/pesanpengiriman');

        }

    }


    public function edit($id_pesan = NULL) {

        $this->pesan->where('id', $id_pesan);
        $this->pesan->get();

        $stspesan = new trstspesan();

        $data['list_pesan'] = $stspesan->order_by('id','ASC')->get()->all;
        $statuspesan = new trstspesan();

        $this->pesan->trstspesan->get();

        $data['id'] = $this->pesan->id;
        $data['e_pesan'] = $this->pesan->e_pesan;
        $data['e_pesan_koreksi'] = $this->pesan->e_pesan_koreksi;
        $data['nama'] = $this->pesan->nama;
        $data['alamat'] = $this->pesan->alamat;
        $data['telp'] = $this->pesan->telp;
        $data['kelurahan'] = $this->pesan->kelurahan;
        $data['kecamatan'] = $this->pesan->kecamatan;

         $js_date = "
            $(function() {
                $(\"#pesan\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
            ";
        $this->template->set_metadata_javascript($js_date);
        $data['jenis_status'] = $this->pesan->c_tindak_lanjut;
        $data['d_entry'] = $this->pesan->d_entry;
        $data['save_method'] = "update";

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Pesan";
        $this->template->build('edit', $this->session_info);
    }

    public function update() {
        $update = $this->pesan
                ->where('id', $this->input->post('id'))
                ->update(array(
                    'id' => $this->input->post('id'),
                    'e_pesan' => $this->input->post('e_pesan'),
                    'e_pesan_koreksi' => $this->input->post('e_pesan_koreksi'),
                    'c_tindak_lanjut' => $this->input->post('c_tindak_lanjut'),
                    'nama' => $this->input->post('nama'),
                    'alamat' => $this->input->post('alamat'),
                    'kecamatan' => $this->input->post('kecamatan'),
                    'kelurahan' => $this->input->post('kelurahan'),
                    'e_tindak_lanjut' => $this->input->post('e_tindak_lanjut'),
                    'c_skpd_tindaklanjut' => $this->input->post('c_skpd_tindaklanjut'),
                    'telp' => $this->input->post('telp'),
                    'd_tindak_lanjut' => $this->input->post('d_tindak_lanjut'),
                    'd_tindaklanjut_selesai' => $this->input->post('d_tindaklanjut_selesai'),
                    'nama_penanggungjawab' => $this->input->post('nama_penanggungjawab'),
                    'd_entry' => $this->input->post('d_entry')));
        if($update) {

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql2($u_ser);
        $p = $this->db->query("call log ('Pengiriman Respon Pengaduan','Update ".$this->input->post('nama')."','".$tgl."','".$u_ser."')");

            redirect('pesan/pesanpengiriman');
        }
    }

    public function delete($pesan_id = NULL) {
        $this->pesan->where('id', $pesan_id)->get();
        if($this->pesan->delete()) {
            redirect('pesan');
        }
    }

public function viewPesan($id_pesan = NULL) {
  $this->pesan->where('id', $id_pesan);
        $this->pesan->get();

        $stspesan = new trstspesan();

        $data['list_pesan'] = $stspesan->order_by('id','ASC')->get()->all;
        $statuspesan = new trstspesan();

        $this->pesan->trstspesan->get();

        $data['id'] = $this->pesan->id;
        $data['e_pesan'] = $this->pesan->e_pesan;
        $data['e_pesan_koreksi'] = $this->pesan->e_pesan_koreksi;
        $data['telp'] = $this->pesan->telp;
        $data['nama'] = $this->pesan->nama;
        $data['alamat'] = $this->pesan->alamat;
        $data['c_tindak_lanjut'] = $this->pesan->c_tindak_lanjut;
        $data['kelurahan'] = $this->pesan->kelurahan;
        $data['kecamatan'] = $this->pesan->kecamatan;
        $data['nama_penanggungjawab'] = $this->pesan->nama_penanggungjawab;
        $data['e_tindak_lanjut'] = $this->pesan->e_tindak_lanjut;
        $data['c_skpd_tindaklanjut'] = $this->pesan->c_skpd_tindaklanjut;
        $data['jenis_status'] = $this->pesan->c_tindak_lanjut;
            $js =  "
                $(document).ready(function() {
                    $(\"#tabs\").tabs();
                    $('#form').validate();
                } );

                $(function() {
                $(\".pesan\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
                
            ";
        $this->template->set_metadata_javascript($js);
        $data['d_tindak_lanjut'] = $this->pesan->d_tindak_lanjut;
        $data['d_tindaklanjut_selesai'] = $this->pesan->d_tindaklanjut_selesai;    
        $data['d_entry'] = $this->pesan->d_entry;
        $data['save_method'] = "update";

        $this->load->vars($data);
        $this->session_info['page_name'] = "Detail Pengaduan";
        $this->template->build('list_view', $this->session_info);
}
public function cetak_surat($id_pesan = NULL) {
        $nama_surat = "surat_balasan";
        $this->settings = new settings();
        $this->settings->where('name','app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name','app_city')->get();

        $pengaduan = new tmpesan();
        $pengaduan->get_by_id($id_pesan);
        $status = $pengaduan->trstspesan->get();
        $sumber = $pengaduan->trsumber_pesan->get();
        $permohonan = new tmpermohonan();
        $p_kelurahan = $permohonan->tmpemohon->trkelurahan->get();
        $p_kecamatan = $permohonan->tmpemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $permohonan->tmpemohon->trkelurahan->trkecamatan->trkabupaten->get();

        //Tampilkan Tgl Pengiriman Pesan Pengaduan
        if($pengaduan->d_entry){
            if($pengaduan->d_entry != '0000-00-00') $tgl_kirim = $this->lib_date->mysql_to_human($pengaduan->d_entry);
            else $tgl_kirim = "";
        }else $tgl_kirim = "";

        //Tampilkan Tgl Selesai Tidak Lanjut Pengaduan
        if($pengaduan->d_tindaklanjut_selesai){
            if($pengaduan->d_tindaklanjut_selesai != '0000-00-00') $tgl_selesai = $this->lib_date->mysql_to_human($pengaduan->d_tindaklanjut_selesai);
            else $tgl_selesai = "";
        }else $tgl_selesai = "";

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
        

        // Untuk Tnjuan Surat di Header Surat Balasan   
        $listeArticles4 = array(
                array(	'property4' => 'Kepada Yth.',
                        'content4' => 'Kepala' .' '. ucwords(strtolower($pengaduan->c_skpd_tindaklanjut)),
                ),
        );
        $article4 = $odf->setSegment('articles4');
        foreach($listeArticles4 AS $element4) {
                $article4->titreArticle4($element4['property4']);
                $article4->texteArticle4($element4['content4']);
                $article4->merge();
        }
        $odf->mergeSegment($article4);

        // Untuk Tanggal Header Surat Balasan
        $tgl=$this->lib_date->mysql_to_human(date ("Y-m-d"));
        $listeArticles3 = array(
                array(	'property3' => '',
                        'content3' =>', '. $tgl,
                ),

        );
        $article3 = $odf->setSegment('articles3');
        foreach($listeArticles3 AS $element3) {
                $article3->titreArticle3($element3['property3']);
                $article3->texteArticle3($element3['content3']);
                $article3->merge();
        }
        $odf->mergeSegment($article3);

        // Untuk Header Surat Balasan      
        $listeArticles2 = array(
                array(	'property2' => 'Nomor',
                        'content2' => $pengaduan->id,
                ),
                array(	'property2' => 'Perihal',
                        'content2' =>  'Pengaduan',
                ),

        );
        $article2 = $odf->setSegment('articles2');
        foreach($listeArticles2 AS $element2) {
                $article2->titreArticle2($element2['property2']);
                $article2->texteArticle2($element2['content2']);
                $article2->merge();
        }
        $odf->mergeSegment($article2);


                // Untuk isi Surat Balasan
        $listeArticles5 = array(
                array(	'property5' => ' ',
                        'content5' =>  $pengaduan->e_pesan_koreksi,
                ),

        );
        $article5 = $odf->setSegment('articles5');
        foreach($listeArticles5 AS $element5) {
                $article5->titreArticle5($element5['property5']);
                $article5->texteArticle5($element5['content5']);
                $article5->merge();
        }
        $odf->mergeSegment($article5);



        // Untuk header Isi Surat Balasan
        $listeArticles1 = array(
                array(	'property1' => 'Nama',
                        'content1' => $pengaduan->nama,
                ),
                array(	'property1' => 'Alamat',
                        'content1' => $pengaduan->alamat,
                ),
                array(	'property1' => 'Telp',
                        'content1' => $pengaduan->telp,
                ),
                array(	'property1' => 'Tanggal Pengiriman',
                        'content1' => $this->lib_date->mysql_to_human( $pengaduan->d_entry),
                ),                   
        );
        $article1 = $odf->setSegment('articles1');
        foreach($listeArticles1 AS $element) {
                $article1->titreArticle1($element['property1']);
                $article1->texteArticle1($element['content1']);
                $article1->merge();
        }
        $odf->mergeSegment($article1);

         // Untuk Tanggal footer Surat Balasan
        $tgl=$this->lib_date->mysql_to_human(date ("Y-m-d"));
        $listeArticles6 = array(
                array(	'property6' => '',
                        'content6' => ', '. $tgl,
                ),

        );
        $article6 = $odf->setSegment('articles6');
        foreach($listeArticles6 AS $element6) {
                $article6->titreArticle6($element6['property6']);
                $article6->texteArticle6($element6['content6']);
                $article6->merge();
        }
        $odf->mergeSegment($article6);

   
         // Untuk Penangguang Jawab urat Balasan

        $listeArticles7 = array(
                array(	'property7' => '',
                        'content7' => $pengaduan->nama_penanggungjawab,
                ),  

        );
        $article7 = $odf->setSegment('articles7');
        foreach($listeArticles7 AS $element7) {
                $article7->titreArticle7($element7['property7']);
                $article7->texteArticle7($element7['content7']);
                $article7->merge();
        }
        $odf->mergeSegment($article7);

        //export the file
        $no_daftar = str_replace('/', '', $pengaduan->id);
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql2($u_ser);

        $p = $this->db->query("call log ('Pengiriman Respon Pengaduan','Cetak Balasan ".$pengaduan->nama."','".$tgl."','".$u_ser."')");

        $odf->exportAsAttachedFile($nama_surat.' no.'.  $no_daftar.'.odt');
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
