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

class Pesan extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->pengaduan = new tmpesan();
        $this->stspesan = new trstspesan();
        $this->propinsi = new trpropinsi();
        $this->kabupaten = new trkabupaten();
        $this->kecamatan = new trkecamatan();
        $this->kelurahan = new trkelurahan();
        $this->pesan = new user_auth();
        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->pesan = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '14') {
                $enabled = TRUE;
                $this->pesan = new user_auth();
            }
        }
		
        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
        $data['list'] = $this->pengaduan->where("c_tindak_lanjut <> 'Hapus' " )->order_by('id', 'desc')->get();
        $data['liststspesan'] = $this->stspesan->get();

        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#pesan').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
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
        $this->session_info['page_name'] = "Daftar pengaduan / saran";
        $this->template->build('list', $this->session_info);
    }

    public function create() {
        // menampilakan combobox
        $data = $this->_funcwilayah();
        $status = new trstspesan();
        $data['list_status'] = $status->get();
        $sumber = new trsumber_pesan();
        $data['list_sumber'] = $sumber->get();  
        $data['propinsi_usaha'] = "";
        $data['kabupaten_usaha'] = "";

        $data['e_pesan']  = "";
        $data['RbTindakLanjut']  = "";
        $data['d_entry']  = "";
        $data['nama']  = "";
        $data['alamat']  = "";
        $data['telp']  = "";
        $data['kelurahan_usaha']  = "";
        $data['kecamatan_usaha']  = "";
        $data['tmpesan_id']  = "";
        $data['tmpesan_id']  = "";
        $data['save_method'] = "save";

 $js =  "
                $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();
                } );

               $(function() {
                $(\"#pesan\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });

           $(document).ready(function() {
                       
                        $('#propinsi_pemohon_id').change(function(){
                                        $.post('".base_url()."pelayanan/pendaftaran/kabupaten_pemohon', { propinsi_id: $('#propinsi_pemohon_id').val() },
                                                       function(data) {
                                                         $('#show_kabupaten_pemohon').html(data);
                                                         $('#show_kecamatan_pemohon').html('Data Tidak tersedia');
                                                         $('#show_kelurahan_pemohon').html('Data Tidak tersedia');
                                                       });
                                         }); 
                       
                });
                

                function finishAjax(id, response){
                  $('#'+id).html(unescape(response));
                  $('#'+id).fadeIn();
                }

                function Check(chk){
                    if(document.myform.Check_ctr.checked==true){
                    for (i = 0; i < chk.length; i++)
                    chk[i].checked = true ;
                    }else{

                    for (i = 0; i < chk.length; i++)
                    chk[i].checked = false ;
                    }
                }
            ";

        $this->template->set_metadata_javascript($js);
        $this->load->vars($data); 
        $this->session_info['page_name'] = "Tambah Pengaduan";
        $this->template->build('create', $this->session_info);
    }

    public function save() {

        $this->pengaduan->e_pesan = $this->input->post('e_pesan');
        $this->pengaduan->c_tindak_lajut = $this->input->post('RbTindakLanjut');
        $this->pengaduan->nama = $this->input->post('nama');
        $this->pengaduan->telp = $this->input->post('telp');
        $sumber = new trsumber_pesan();
        $sumber->get_by_id($this->input->post('sumber_pesan'));
        $status = new trstspesan();
        $status->get_by_id($this->input->post('status_pesan'));
        $this->pengaduan->alamat = $this->input->post('alamat');
        $this->pengaduan->kelurahan = $this->input->post('kelurahan_pemohon');
        $this->pengaduan->kecamatan = $this->input->post('kecamatan_pemohon');
        $this->pengaduan->d_entry = $this->input->post('d_entry');

        if(! $this->pengaduan->save($status)) {
            echo '<p>' . $this->pengaduan->error->string . '</p>';
        }
        if(! $this->pengaduan->save($sumber)) {
            echo '<p>' . $this->pengaduan->error->string . '</p>';
        }
        else {
             $tgl = date("Y-m-d H:i:s");
             $u_ser = $this->session->userdata('username');
             $g = $this->sql2($u_ser);
             $p = $this->db->query("call log ('Daftar Pengaduan / Saran','Insert ".$this->input->post('nama')."','".$tgl."','".$u_ser."')");
             redirect('pesan');

        }

    }

 //public function edit($id_pesan = NULL) 
// {
//        $this->pengaduan->where('id', $id_pesan);
//        $this->pengaduan->get();
//        $idKelurahan =  $this->pengaduan->kelurahan;
//        $u_kelurahan = $this->kelurahan->get_by_id($idKelurahan);
//        $u_kecamatan = $u_kelurahan->trkecamatan->get();
//        $u_kabupaten = $u_kelurahan->trkecamatan->trkabupaten->get();
//        $u_propinsi  = $u_kelurahan->trkecamatan->trkabupaten->trpropinsi->get();
// }

    public function edit($id_pesan = NULL) {
  
        $data = $this->_funcwilayah();
        $this->pengaduan->where('id', $id_pesan);
        $this->pengaduan->get();
        $sumber_id = $this->pengaduan->trsumber_pesan->get();
        $status_id = $this->pengaduan->trstspesan->get();
        
        $idKelurahan =  $this->pengaduan->kelurahan;
        $u_kelurahan = $this->kelurahan->get_by_id($idKelurahan);
        $u_kecamatan = $u_kelurahan->trkecamatan->get();
        $u_kabupaten = $u_kelurahan->trkecamatan->trkabupaten->get();
        $u_propinsi  = $u_kelurahan->trkecamatan->trkabupaten->trpropinsi->get();
        
        $status = new trstspesan();
        $data['list_status'] = $status->order_by('id','DESC')->get()->all;
        $sumber = new trsumber_pesan();
        $data['list_sumber'] = $sumber->get();
        $data['RbTindakLanjut'] = $this->pengaduan->c_tindak_lanjut;
        $data['id'] = $this->pengaduan->id;
        $data['e_pesan'] = $this->pengaduan->e_pesan;
        $data['nama'] = $this->pengaduan->nama;
        $data['telp'] = $this->pengaduan->telp;
        $data['alamat'] = $this->pengaduan->alamat;
        $data['e_pesan_koreksi'] = $this->pengaduan->e_pesan_koreksi;
        $data['kelurahan_usaha'] = $this->pengaduan->kelurahan;
        $data['kecamatan_usaha'] = $this->pengaduan->kecamatan;
        $data['propinsi_usaha'] = $u_propinsi->id;
        $data['kabupaten_usaha'] = $u_kabupaten->id;
        $data['sumber_pesan'] = $sumber_id->id;
        $data['status_pesan'] = $status_id->id;
        $data['list_kelurahan'] = $this->kelurahan->order_by('n_kelurahan','ASC')->get();
         
         $js_date = "
             $(document).ready(function() {
                    $(\"#tabs\").tabs();
                    $('#form').validate();
                } );
            $(function() {
                $(\"#pesan\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
  $(document).ready(function() {
                        
                                 $('#propinsi_pemohon_id').change(function(){
                                        $.post('".base_url()."pelayanan/pendaftaran/kabupaten_pemohon', { propinsi_id: $('#propinsi_pemohon_id').val() },
                                                       function(data) {
                                                         $('#show_kabupaten_pemohon').html(data);
                                                         $('#show_kecamatan_pemohon').html('Data Tidak tersedia');
                                                         $('#show_kelurahan_pemohon').html('Data Tidak tersedia');
                                                       });
                                         });
                     
                       
                });

                function finishAjax(id, response){
                  $('#'+id).html(unescape(response));
                  $('#'+id).fadeIn();
                }
            ";
            
        $this->template->set_metadata_javascript($js_date);
        $data['d_entry'] = $this->pengaduan->d_entry;
        $data['save_method'] = "update";
        
        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Pengaduan";
        $this->template->build('edit', $this->session_info);
    }

    public function update() {
        $update = $this->pengaduan
                ->where('id', $this->input->post('id'))
                ->update(array(
                    'id' => $this->input->post('id'),
                    'c_tindak_lanjut' => $this->input->post('RbTindakLanjut'),
                    'nama' => $this->input->post('nama'),
                    'telp' => $this->input->post('telp'),
                    'alamat' => $this->input->post('alamat'),
                    'kecamatan' => $this->input->post('kecamatan_pemohon'),
                    'e_pesan_koreksi' => $this->input->post('e_pesan_koreksi'),
                    'kelurahan' => $this->input->post('kelurahan_pemohon'),
                    'd_entry' => $this->input->post('d_entry')));
        $sumber = new tmpesan_trsumber();
        $sumber->where('tmpesan_id', $this->input->post('id'))
        ->update(array('trsumber_pesan_id' => $this->input->post('sumber_pesan')));

        $status = new tmpesan_trstspesan();
        $status->where('tmpesan_id', $this->input->post('id'))
        ->update(array('trstspesan_id' => $this->input->post('status_pesan')));

        if($update) {
             $tgl = date("Y-m-d H:i:s");
             $u_ser = $this->session->userdata('username');
             $g = $this->sql2($u_ser);
             $p = $this->db->query("call log ('Daftar Pengaduan/Saran','Update ".$this->input->post('nama')."','".$tgl."','".$u_ser."')");

            redirect('pesan');
        }
    }

    public function delete($pesan_id = NULL) {
        $this->pengaduan->where('id', $pesan_id)->get();
        if($this->pengaduan->delete()) {
            redirect('pesan');
        }
    }


    public function filterdata() {
	$this->stspesan->where('id', $this->input->post('sts_pesan'))->get();
	$data['liststspesan'] = $this->stspesan->order_by('id', 'ASC')->get();

        $data['sts_pesan'] = $this->stspesan->get_by_id($this->input->post('sts_pesan'));


        $data['list'] = $this->stspesan->tmpesan->where("c_tindak_lanjut <> 'Hapus'" )->get($this->stspesan);
        $this->load->vars($data);


        $js = "
        $(document).ready(function() {
                oTable = $('#pesan').dataTable({
                        \"bJQueryUI\": true,
                        \"sPaginationType\": \"full_numbers\"
                });
        } );
        ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = $this->stspesan->n_sts_pesan;
        $this->template->build('view_pesan', $this->session_info);
    }

    public function filPesPerBul($tgla = Null, $tglb = Null) {
//        $tgla = $this->input->post('tgla');
//        $tglb = $this->input->post('tglb');

        $data['list'] = $this->pengaduan->where("c_tindak_lanjut <> 'Hapus' AND d_entry BETWEEN '$tgla' AND '$tglb' " )->get();
        $data['liststspesan'] = $this->stspesan->get();

        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#pesan').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
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
        $this->session_info['page_name'] = "Manajemen Pesan";
        $this->template->build('view', $this->session_info);
    }

   function _funcwilayah(){
        $data['list_propinsi'] = $this->propinsi->order_by('n_propinsi','ASC')->get();
        //$data['list_kabupaten'] = $this->kabupaten->order_by('n_kabupaten','ASC')->get();
        //$data['list_kecamatan'] = $this->kecamatan->order_by('n_kecamatan','ASC')->get();
        //$data['list_kelurahan'] = $this->kelurahan->order_by('n_kelurahan','ASC')->get();


        return $data;
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
