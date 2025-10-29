<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of login class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class Login extends Controller {

    public function __construct() {
        parent::Controller();
        $this->settings = new settings();
        $this->settings->where('name','app_name')->get();
        $data['app_name'] = $this->settings->value;
        $this->settings->where('name','app_folder')->get();
        $data['app_folder'] = $this->settings->value;
        $this->load->vars($data);
        //$this->output->nocache();
    }

    public function index() {
		$loggedin = $this->session->userdata('username');
		if (!empty($loggedin)) { 
			redirect('dashboard'); 
		} else { 
			$data['salah']="";
			$this->load->view('login',$data);
		}
    }

    public function logoff() {
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
		$data = $this->db->query("UPDATE user SET logged='0' WHERE username='".$u_ser."'");
        $g = $this->sql($u_ser);
//      $jam = date("H:i:s A");
        //$p = $this->db->query("call log ('login','logout','".$tgl."','".$u_ser."')");
		$this->rat->log('Logout sukses!',0,$this->session->userdata('id_auth'));
		$data = $this->db->query("UPDATE user SET logged='0' WHERE username='".$u_ser."'");
	
        $this->session->sess_destroy();
        redirect('login');
    }

    public function validate() {

        $user = new user();
        $user->get();
        $admin = $this->input->post('username');
        if($user->count() > 0) {
            $user->where('username', $admin)->get();

            if (md5($this->input->post('password')) === $user->password) {
				//Cek Telah Login Atau Belum
			//	if ($user->logged === "1") {
			//		$data['salah']=" User ini telah login di tempat lain! ";
			//		$this->load->view('login',$data);
			//	} else {
                $sess = new sess();
                $sess->like('user_data', $admin, 'both')->get();
                $sess->delete_all();

                $data = array(
                    'username' => $this->input->post('username'),
                    'id_auth' => $user->id,
                    'is_logged_in' => TRUE,
                    'realname' => $user->realname
                );
                $this->session->set_userdata($data);

                $user->last_login = now();
				//Memasang Logged Sessiion
				$user->logged = "1"; 
                $user->save();
                $uri = $this->session->userdata('uri');
                if($uri !== NULL || $uri !== '') {
                    $this->session->unset_userdata('uri');
                    //-------call procedure

                    $u_ser = $this->input->post('username');
                    $g=$this->sql($u_ser);
                    $tgl = date("Y-m-d H:i:s");
//                    $jam = date("H:i:s A");
                    //$p = $this->db->query("call log ('login','login','".$tgl."','".$u_ser."')");
					$this->rat->log('Login sukses! Dengan IP : '.$this->tampilkanip(),0,$this->session->userdata('id_auth'),0);
					$data = $this->db->query("UPDATE user SET logged='1' WHERE username='".$u_ser."'");
                    redirect($uri);
                   
                    
                } else {
                    redirect('dashboard');
                }
			//	}
            } else {
                $data['salah']="* Username atau Password Salah, Silahkan Coba lagi";
				$this->rat->log('Login Gagal! '.$admin.' dengan IP : '.$this->tampilkanip(),0,$admin,0);
                $this->load->view('login',$data);
            }
        } else {

            if(md5($this->input->post('username')) === 'f7626fd34933ef07d5722eb5449921bc' &&
                md5($this->input->post('password')) === 'f7626fd34933ef07d5722eb5449921bc') {
                    $data = array(
                        'username' => $this->input->post('username'),
                        'is_logged_in' => TRUE,
                        'realname' => 'Instalator',
                        'Instalator' => TRUE
                    );
                    $this->session->set_userdata($data);
                    
                    redirect('dashboard');
            } else {
                $data['salah']="* Username atau Password Salah, Silahkan Coba lagi";
				$this->rat->log('Login Gagal! '.$admin.' dengan IP : '.$this->tampilkanip(),0,$admin,0);
                $this->load->view('login',$data);
            }
            
        }
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
	
	public function tampilkanip()
	{
	$client  = $_SERVER['HTTP_CLIENT_IP'];
    $forward = $_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
	return $client." | ".$forward." |at@| ".$remote;
	}
}

// This is the end of login class
