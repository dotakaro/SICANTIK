<?php

/**
 * Description of Pemohon Izin
 *
 * @author agusnur
 * Created : 13 Aug 2010
 */
class Mobile_pemohon extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->load->model('pemohon/tmpemohon');
        $this->load->model('mobile_user');
        $this->pemohon = new tmpemohon();
        $this->propinsi = new trpropinsi();
        $this->kabupaten = new trkabupaten();
        $this->kecamatan = new trkecamatan();
        $this->kelurahan = new trkelurahan();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if ($list_auth->id_role === '9') {
                $enabled = TRUE;
            }
        }

        if (!$enabled) {
            redirect('dashboard');
        }*/
    }

    function _funcwilayah() {
        $data['list_propinsi'] = $this->propinsi->order_by('n_propinsi', 'ASC')->get();
        $data['list_kabupaten'] = $this->kabupaten->order_by('n_kabupaten', 'ASC')->get();
        $data['list_kecamatan'] = $this->kecamatan->order_by('n_kecamatan', 'ASC')->get();
        $data['list_kelurahan'] = $this->kelurahan->order_by('n_kelurahan', 'ASC')->get();

        return $data;
    }

    public function index() {
        $data['list'] = $this->pemohon->order_by('id', 'DESC')
                        ->limit(2000)->get();
        $this->load->vars($data);

        $js = "
                function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Data Pemohon Mobile";
        $this->template->build('pemohon_list', $this->session_info);
    }

    /*
     * create is a method to show page for creating data
     */

    public function create() {
        $data = $this->_funcwilayah();
        $data['save_method'] = "save";
        $data['id_pemohon'] = "";
        $data['no_refer'] = "";
        $data['cmbsource'] = NULL;
        $data['nama_pemohon'] = "";
        $data['no_telp'] = "";
        $data['check_ctr'] = 0;
        $data['propinsi_pemohon'] = NULL;
        $data['kabupaten_pemohon'] = NULL;
        $data['kecamatan_pemohon'] = NULL;
        $data['kelurahan_pemohon'] = NULL;
        $data['alamat_pemohon'] = "";
        $data['alamat_pemohon_luar'] = "";

        $js = "
                $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();
                } );

                $(document).ready(function() {
                        $('#propinsi_pemohon_id').change(function(){
                            $.post('" . base_url() . "pelayanan/pendaftaran/kabupaten_pemohon', { propinsi_id: $('#propinsi_pemohon_id').val() },
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

                function Check(){
                    if(document.form.Check_ctr.checked == true){
                        document.form.propinsi_pemohon.disabled = false ;
                        document.form.kabupaten_pemohon.disabled = false ;
                        document.form.kecamatan_pemohon.disabled = false ;
                        document.form.kelurahan_pemohon.disabled = false ;
                    }else{
                        document.form.propinsi_pemohon.disabled = true ;
                        document.form.kabupaten_pemohon.disabled = true ;
                        document.form.kecamatan_pemohon.disabled = true ;
                        document.form.kelurahan_pemohon.disabled = true ;
                    }
                }
            ";

        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Data Pemohon";
        $this->template->build('pemohon_edit', $this->session_info);
    }

    /*
     * edit is a method to show page for updating data
     */

    public function edit($id_pemohon = NULL) {
        $this->mobile_user = new mobile_user();
        $dataUser = $this->mobile_user->where('tmpemohon_id', $id_pemohon)->get();
        if(!$dataUser){
            redirect('mobile_pemohon');
        }
        $p_pemohon = $this->pemohon->get_by_id($id_pemohon);
        $p_kelurahan = $p_pemohon->trkelurahan->get();
        $p_kecamatan = $p_pemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $p_pemohon->trkelurahan->trkecamatan->trkabupaten->get();
        $p_propinsi = $p_pemohon->trkelurahan->trkecamatan->trkabupaten->trpropinsi->get();

        $data = $this->_funcwilayah();
        $data['save_method'] = "update";
        $data['id_pemohon'] = $id_pemohon;
        $data['no_refer'] = $p_pemohon->no_referensi;
        $data['cmbsource'] = $p_pemohon->source;
        $data['nama_pemohon'] = $p_pemohon->n_pemohon;
        $data['no_telp'] = $p_pemohon->telp_pemohon;
        $data['check_ctr'] = $p_pemohon->cek_prop;
        $data['propinsi_pemohon'] = $p_propinsi->id;
        $data['kabupaten_pemohon'] = $p_kabupaten->id;
        $data['kecamatan_pemohon'] = $p_kecamatan->id;
        $data['kelurahan_pemohon'] = $p_kelurahan->id;
        $data['alamat_pemohon'] = $p_pemohon->a_pemohon;
        $data['alamat_pemohon_luar'] = $p_pemohon->a_pemohon_luar;
        $data['mobile_username'] = $dataUser->username;
        $data['active'] = $dataUser->active;
        $data['last_login'] = $dataUser->last_login;
        $data['mobile_user_id'] = $dataUser->id;

        $js = "
                var base_url = '" . base_url() . "';
                $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();
                } );

                $(document).ready(function() {
                        $('#propinsi_pemohon_id').change(function(){
                            $.post('" . base_url() . "pelayanan/pendaftaran/kabupaten_pemohon', { propinsi_id: $('#propinsi_pemohon_id').val() },
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

                function Check(){
                    if(document.form.Check_ctr.checked == true){
                        document.form.propinsi_pemohon.disabled = false ;
                        document.form.kabupaten_pemohon.disabled = false ;
                        document.form.kecamatan_pemohon.disabled = false ;
                        document.form.kelurahan_pemohon.disabled = false ;
                    }else{
                        document.form.propinsi_pemohon.disabled = true ;
                        document.form.kabupaten_pemohon.disabled = true ;
                        document.form.kecamatan_pemohon.disabled = true ;
                        document.form.kelurahan_pemohon.disabled = true ;
                    }
                }
            ";

        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Data Pemohon";
        $this->template->build('pemohon_edit', $this->session_info);
    }

    /*
     * Save and update for manipulating data.
     */

    public function save() {

        /* Input Data Pemohon */
        $this->pemohon->no_referensi = $this->input->post('no_refer');
        $this->pemohon->source = $this->input->post('cmbsource');
        $this->pemohon->n_pemohon = $this->input->post('nama_pemohon');
        $this->pemohon->telp_pemohon = $this->input->post('no_telp');
        $this->pemohon->a_pemohon = $this->input->post('alamat_pemohon');
        $this->pemohon->a_pemohon_luar = $this->input->post('alamat_pemohon_luar');

        /* Input Relasi Tabel */
//        if ($this->input->post('Check_ctr')) {
            $this->pemohon->cek_prop = "0";
            $this->kelurahan->get_by_id($this->input->post('kelurahan_pemohon'));
            $this->pemohon->save($this->kelurahan);
//        } else {
//            $this->pemohon->cek_prop = "1";
//            $this->pemohon->save();
//        }

        //Ambil Pemohon Tersimpan
        $pemohon_akhir = new pemohon();
        $pemohon_akhir->select_max('id')->get();

        //Nomor Index
        $inisial = strtoupper(substr($pemohon_akhir->n_pemohon, 0, 1));
        $archive_lama = new tmarchive();
        $archive_lama
                ->where('i_inisial', $inisial)
                ->order_by('id DESC')
                ->get();
        if ($archive_lama->id) {
            $archive_lama->get_by_id($archive_lama->id);
            $data_urut = $archive_lama->i_urut + 1;
        }else
            $data_urut = 1;

        //Nomor Urut Index
        $i_urut = strlen($data_urut);
        for ($i = 3; $i > $i_urut; $i--) {
            $data_urut = "0" . $data_urut;
        }

        //Grup Index
        $grup = substr($data_urut, 0, 1) + 1;

        $archive = new tmarchive();
        $archive->i_archive = $inisial . $grup . "-" . $data_urut;
        $archive->i_inisial = $inisial;
        $archive->i_urut = $data_urut;

        $update = $archive->save($pemohon_akhir);

        if (!$update) {
            echo '<p>' . $this->pemohon->error->string . '</p>';
        } else {
            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $g = $this->sql($u_ser);
//          $jam = date("H:i:s A");
            $p = $this->db->query("call log ('Pemohon','Input data pemohon ".$this->input->post('no_refer')."','".$tgl."','".$u_ser."')");

            redirect('mobile_pemohon');
        }
    }

    public function update() {
        $id_pemohon = $this->input->post('id_pemohon');
        $mobileUserId = $this->input->post('mobile_user_id');
        $nama_pemohon = $this->input->post('nama_pemohon');
        $pemohon = new tmpemohon();
        $mobileUser = new mobile_user();
        $pemohon->get_by_id($id_pemohon);
        $mobileUser->get_by_id($mobileUserId);

        $pemohon->no_referensi = $this->input->post('no_refer');
        $pemohon->source = $this->input->post('cmbsource');
        $pemohon->n_pemohon = $nama_pemohon;
        $pemohon->telp_pemohon = $this->input->post('no_telp');
        $pemohon->a_pemohon = $this->input->post('alamat_pemohon');
        $pemohon->a_pemohon_luar = $this->input->post('alamat_pemohon_luar');
        $mobileUser->active = $this->input->post('active');

        $pemohon->trkelurahan->get();
//        $pemohon_lurah = new tmpemohon_trkelurahan();
//        $pemohon_lurah->where('tmpemohon_id', $this->input->post('id_pemohon'))
//        ->update(array('trkelurahan_id' => $this->input->post('kelurahan_pemohon')));
        $pemohon_lurah = new tmpemohon_trkelurahan();
        $pemohon_lurah->where('tmpemohon_id', $id_pemohon)->get();
        $pemohon_lurah->delete();
        //if ($this->input->post('Check_ctr')) {
            $pemohon->cek_prop = "0";
            $kelurahan_p = new trkelurahan();
            $kelurahan_p->get_by_id($this->input->post('kelurahan_pemohon'));
            $update = $pemohon->save(array($kelurahan_p));

            $mobileUser->save();
       // } else {
//            $pemohon->cek_prop = "1";
//            $update = $pemohon->save();
//        }

        $list_archive = new tmarchive_tmpemohon();
        $list_archive->where('tmpemohon_id', $id_pemohon)->get();
        if ($list_archive->tmarchive_id) {
//            $update = $pemohon->save();
        } else {
            $inisial = strtoupper(substr($nama_pemohon, 0, 1));
            $archive_lama = new tmarchive();
            $archive_lama
                    ->where('i_inisial', $inisial)
                    ->order_by('id DESC')
                    ->get();
            if ($archive_lama->id) {
                $archive_lama->get_by_id($archive_lama->id);
                $data_urut = $archive_lama->i_urut + 1;
            }else
                $data_urut = 1;

            //Nomor Urut Index
            $i_urut = strlen($data_urut);
            for ($i = 3; $i > $i_urut; $i--) {
                $data_urut = "0" . $data_urut;
            }

            //Grup Index
            $grup = substr($data_urut, 0, 1) + 1;

            $archive = new tmarchive();
            $archive->i_archive = $inisial . $grup . "-" . $data_urut;
            $archive->i_inisial = $inisial;
            $archive->i_urut = $data_urut;

            $update = $archive->save($pemohon);
        }

        if ($update) {
            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $g = $this->sql($u_ser);
//          $jam = date("H:i:s A");
            $p = $this->db->query("call log ('Pemohon','Edit data pemohon ".$this->input->post('no_refer')."','".$tgl."','".$u_ser."')");

            redirect('mobile_pemohon');
        }
    }

    public function activate($pemohonId) {
        $return = array();
        $success = false;
        $this->mobile_user = new mobile_user();
        $getUser = $this->mobile_user->where('tmpemohon_id', $pemohonId)->get();
        if($getUser->id){
            $getUser->active = 1;
            if($getUser->save()){
                $success = true;
            }
        }
        $return['success'] = $success;
        echo json_encode($return);
    }

    public function delete($uid = NULL) {
        $kelurahan = new tmpemohon_trkelurahan();
        $kelurahan->where('tmpemohon_id', $uid);
        $kelurahan->delete();

        $list_archive = new tmarchive_tmpemohon();
        $list_archive->where('tmpemohon_id', $uid)->get();

        $get_archive = new tmarchive();
        $get_archive->get_by_id($list_archive->tmarchive_id);
        $get_archive->delete();

        $archive_list = new tmarchive_tmpemohon();
        $archive_list->where('tmpemohon_id', $uid);
        $archive_list->delete();

        $pemohon = new tmpemohon();
        $pemohon->get_by_id($uid);
        $pemohon->delete();

        $no_referensi = $this->pemohon->get_by_id($uid);
        
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql($u_ser);
//      $jam = date("H:i:s A");
        $p = $this->db->query("call log ('Pemohon','Hapus data pemohon ".$no_referensi->no_referensi."','".$tgl."','".$u_ser."')");

        redirect('mobile_pemohon');
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
    
    public function register_id_exist(){
        $no_refer = mysql_real_escape_string($_POST['no_refer']);
        
        $sql = "SELECT no_referensi FROM tmpemohon WHERE no_referensi = '$no_refer'";
        $hasil = $this->db->query($sql);
        $result = $hasil->row();
        if($result) {
            $output = false;
        } else {
            $output = true;
        }
        
        echo json_encode($output);
    }
    
    ////Fungsi untuk mencari id pemohon apakah sudah ada atau tidak
//    public function id_exist($id_p){
//        $this->db->where('no_referensi', $id_p);
//        $query = $this->db->get('tmpemohon');
//        if($query->num_rows() > 0){
//            return TRUE;
//        } else {
//            return FALSE;
//        }
//    }
//    
//    //AJAX Request, jika id / no referensi ada
//    public function register_id_exist(){
//        if(array_key_exists('no_refer', $_POST)){
//            if($this->id_exist($this->input->post('no_refer')) == TRUE){
//                echo "1";
//            } else {
//                echo "0";
//            }
//        }
//    }
    

}
