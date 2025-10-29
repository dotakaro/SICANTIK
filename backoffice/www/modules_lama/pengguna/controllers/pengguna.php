<?php

/**
 * Description of user
 *
 * @author Dichi Al Faridi
 */
class Pengguna extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->load->model('trunitkerja_user');

        $this->user = NULL;
        $this->user_auth = NULL;
        $this->perizinan = NULL;

        $enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->role = NULL;
        $this->user = new user();
        $this->user_auth = new user_auth();
        $this->perizinan = new trperizinan();
        /*foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '4') {
                $enabled = TRUE;
            }
        }*/
		
        $url = $this->uri->segment(2);

        /*if($url === 'password' || $enabled) {
            $this->user = new user();
            $this->user_auth = new user_auth();
            $this->perizinan = new trperizinan();
        } else {
            redirect('dashboard');
        }*/
    }

    public function index() {
        $data['list'] = $this->user->get();
        $data['ket_exist'] = NULL;
        $this->load->vars($data);
        $js =  "
            function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                        oTable = $('#user').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                });";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Pengguna";
        $this->template->build('list', $this->session_info);
    }

    public function index_list($exist = NULL) {
        $data['list'] = $this->user->get();
        $data['ket_exist'] = $exist;
        
        $this->load->vars($data);
        $js =  "
            function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                        oTable = $('#user').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                });";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Pengguna";
        $this->template->build('list', $this->session_info);
    }

    /*
     * create is a method to show page for creating data
     */
    public function create() {
        $data['real_name'] = "";
        $data['user_name'] = "";
        $data['password']  = "";
        $data['save_method'] = "save";
        $data['id'] = "";
        $js = "
                $(document).ready(function() {
                    $('#form').validate({
						rules:{
							real_name:{
								required:true,
							},
							user_name_:{
								required:true,
							},
							password: {
								required: true
							},
							confirm_password: {
								required: true,
								equalTo: \"#password\"
							}
						},
						messages:{
							confirm_password:{
								equalTo:\"Confirm Password harus sama dengan Password\"
							}
						}
					});
                    $(\"#tabs\").tabs();

                    $('#unit_akses').michaelMultiselect();
                } );
            ";
        $this->template->set_metadata_javascript($js);
        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Pengguna";
        $this->template->build('edit', $this->session_info);
    }

    /*
     * edit is a method to show page for updating data
     */
    public function edit($id_user = NULL) {
        /***BEGIN - Ambil Daftar Unit Kerja dan Hak Akses ****/
        $masterUnit = new trunitkerja();
        $getUnit = $masterUnit->get();

        $listHakAkses = array();
        $objAkses = new trunitkerja_user();
        $getHakAkses = $objAkses->where('user_id', $id_user)->get();
        if($getHakAkses->id){
            foreach($getHakAkses as $indexHakAkses=>$hakAkses){
                $listHakAkses[] = $hakAkses->trunitkerja_id;
            }
        }
        /***END   - Ambil Daftar Unit Kerja dan Hak Akses****/

        $this->user->where('id', $id_user);
        $this->user->get();
        $js =  "
                $(document).ready(function() {
                    $(\"#tabs\").tabs();

                    $('#form_password').validate({
                        rules: {        	        		
                            password1 :'required',
                            old_password:{
								required: true,
                                remote: '".site_url("pengguna/validate_old_password/{$id_user}")."'
                            },
							confirm_password: {
								required: true,
								equalTo: \"#password1\"
							}		
                        },  	                          
                        messages: {
                            old_password :' Masukkan Password Lama yang valid',
							confirm_password:{
								equalTo:'Confirm Password harus sama dengan Password'
							}
                        }
                    });
					
					$('#form_reset_password').validate({
                        rules: {        	        		
                            new_reset_password :{
								required:true
							},
                            new_confirm_password: {
								required: true,
								equalTo: \"#new_reset_password\"
							}		
                        },  	                          
                        messages: {
                            new_confirm_password:{
								equalTo:'Confirm Password harus sama dengan Password'
							}
                        }
                    });                 
            
                    oTable = $('#peran_list').dataTable({
                            \"bJQueryUI\": true,
                            \"sPaginationType\": \"full_numbers\"
                    });

                    oTable = $('#izin_list').dataTable({
                            \"bJQueryUI\": true,
                            \"sPaginationType\": \"full_numbers\"
                    });

                    $('#unit_akses').michaelMultiselect();
                });
                ";
        $this->template->set_metadata_javascript($js);
        $data['id'] = $this->user->id;
        $data['real_name'] = $this->user->realname;
        $data['user_name'] = $this->user->username;
        $data['peran_list'] = $this->user->user_auth->get();
        $data['izin_list'] = $this->user->trperizinan->get();
        $data['password']  = "";
        $data['save_method'] = "update";
		$data['is_admin'] = $this->__is_administrator();
		$data['getUnit'] = $getUnit;
		$data['listHakAkses'] = $listHakAkses;

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Pengguna";
        $this->template->build('edit', $this->session_info);
    }

    /*
     * Save and update for manipulating data.
     */
    public function save() {
        $user = $this->input->post('user_name_');
        $pengguna = new user();
        $pengguna->where('username', $user)->get();
        if($pengguna->id){
            $this->session->set_flashdata('flash_message', array('message' => 'Maaf, Username sudah digunakan','class' => 'error'));
            redirect('pengguna/index_list/'.str_replace("-", "", strtolower(url_title($user))));
        }else{
            $this->user->username = $user;
            $this->user->realname = $this->input->post('real_name');
            $this->user->password = md5($this->input->post('password'));
            if(! $this->user->save()) {
                $this->session->set_flashdata('flash_message', array('message' => 'Maaf, terjadi kesalahan dalam meyimpan data. Silahkan coba lagi','class' => 'error'));
                echo '<p>' . $this->user->error->string . '</p>';
            } else {
                $tgl = date("Y-m-d H:i:s");
                $u_ser = $this->session->userdata('username');
                $p = $this->db->query("call log ('Setting User','Insert pengguna ".$this->input->post('real_name')."','".$tgl."','".$u_ser."')");
                $this->session->set_flashdata('flash_message', array('message' => 'Data Pengguna berhasil disimpan','class' => 'success'));
//                $this->index();
                redirect('pengguna');
            }
        }
    }

    public function update($method = NULL) {
        $update = NULL;
        $userId = $this->input->post('id');
        switch($method){
            case 'editPassword':
                $update = $this->user
                    ->where('id', $this->input->post('id'))
                    ->update(array(
                        'password' => md5($this->input->post('password1'))
                    ));
                break;
            case 'editName':
                $update = $this->user
                    ->where('id', $this->input->post('id'))
                    ->update(array(
                        'realname' => $this->input->post('real_name')
                    ));
                break;
            case 'resetPassword':
                $update = $this->user
                    ->where('id', $this->input->post('id'))
                    ->update(array(
                        'password' => md5($this->input->post('new_reset_password'))
                    ));
                break;
            case 'editHakAkses':
                ## BEGIN - Update Data Unit Akses ##
                $objAkses = new trunitkerja_user();
                //Hapus semua data Hak Akses untuk Perizinan tersebut
                $getExistingAkses = $objAkses->where('user_id', $userId)->get();
                $getExistingAkses->delete_all();
                $update = true;
                if($this->input->post('unit_akses') && is_array($this->input->post('unit_akses'))){
                    foreach($this->input->post('unit_akses') as $indexUnitAkses=>$unitAkses){
                        $objAksesNew = new trunitkerja_user();
                        $objAksesNew->trunitkerja_id = $unitAkses;
                        $objAksesNew->user_id = $userId;
                        $update = $objAksesNew->save();
                    }
                }
                ## END - Update Data Unit Akses ##
                break;
            default:
                break;
        }

        if($update) {
           $tgl = date("Y-m-d H:i:s");
           $u_ser = $this->session->userdata('username');
           $p = $this->db->query("call log ('Setting User','Update pengguna ".$this->input->post('real_name')."','".$tgl."','".$u_ser."')");
           redirect('pengguna');
        }
    }

    public function delete($id = NULL) {
        $this->user->where('id', $id)->get();
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting User','Delete pengguna ".$this->user->realname."','".$tgl."','".$u_ser."')");

        if($this->user->delete()) {
            redirect('pengguna');
        }
    }

    public function roles($id = NULL, $cek_all = NULL) {
        if($id == NULL) $id = $this->input->post('id');
        if($cek_all == NULL) $cek_all = $this->input->post('cek_all');
        $data['backto']=$this->uri->segment(4,'');

        $this->user->where('id', $id);
        $this->user->get();

        $js =  "
                $(document).ready(function() {
                    $(\"#tabs\").tabs();
                } );
				function check_uncheckAll(field,nilai)
				{
					for(i=0; i< field.length; i++)
					{
						field[i].checked=nilai;
					}
				}
                ";

        $this->template->set_metadata_javascript($js);

        $data['cek_all'] = $cek_all;
        $data['id'] = $this->user->id;
        $data['real_name'] = $this->user->realname;
        $data['user_name'] = $this->user->username;
        

        $data['list']       = $this->user_auth->get();

        $perizinan = new trperizinan();
        $data['list_izin']  = $perizinan->order_by('n_perizinan', 'ASC')->get();

        $data['user_role'] = $this->user->user_auth->get();
        $data['izin_role'] = $this->user->trperizinan->get();
        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Hak Akses Pengguna";
        $this->template->build('roles', $this->session_info);
    }

    public function flush() {

        $id = $this->input->post('id');
        $role_list = $this->input->post('peran');
        $role_list_len = count($role_list);

        $izin_list = $this->input->post('izin');
        $izin_list_len = count($izin_list);

        if($role_list > 0) {
            for($i=0;$i<$role_list_len;$i++) {
                $this->user->get_by_id($id);
                $this->user_auth->get_by_id($role_list[$i]);
                $this->user->save($this->user_auth);
            }
        }
        
        if($izin_list > 0) {
            for($i=0;$i<$izin_list_len;$i++) {
                $this->user->get_by_id($id);
                $this->perizinan->get_by_id($izin_list[$i]);
                $this->user->save($this->perizinan);
            }
        }
        if($this->input->post('backto')=="yes")
        {
            redirect('petugas');
        }
        redirect('pengguna/edit' . "/" . $id);
        
    }

    public function deleterole($uid = NULL, $gid = NULL) {

        $this->user->get_by_id($uid);
        $this->user_auth->get_by_id($gid);
        $this->user->delete($this->user_auth);

        redirect('pengguna/edit' . "/" . $uid);

    }

    public function deleteizin($uid = NULL, $gid = NULL) {

        $this->user->get_by_id($uid);
        $this->perizinan->get_by_id($gid);
        $this->user->delete($this->perizinan);

        redirect('pengguna/edit' . "/" . $uid);
    }

    public function password() {
        $data['password']  = "";

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Password";
        $this->template->build('password', $this->session_info);
    }
    
    //added 12 -04 2013
    //by mucktar
	//Edited by Indra 2013-07-28
    public function validate_old_password($id=''){
        $old_password = $this->input->get('old_password');
		$passwd = new user();
        $db_password = $passwd->where('id',$id)->get();
        
        if(md5($old_password)==$db_password->password){
           echo 'true'; 
        }else{
           echo 'false'; 
        }
    }

}
