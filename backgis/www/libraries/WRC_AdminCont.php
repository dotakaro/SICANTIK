<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of WRC_AdminCont class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 * @ModifiedAuthor Indra Halim
 * 
 */

class WRC_AdminCont extends MY_Controller {

    var $session_info = array();

    //Dikelompokkan agar memudahkan logic untuk pengembangan lebih lanjut
//	private $_id_izin_dengan_tinjauan=array(2,3,4);//Sesuai ID kelompok izin yang memiliki Tinjauan Lapangan dari tabel trkelompok_perizinan
//	private $_id_izin_dengan_bap = array(2,3,4);//Sesuai Request Agam
//    private $_id_izin_dengan_rekomendasi=array(1,2,3,4,5);//Sesuai ID kelompok izin yang memiliki Tinjauan Lapangan dari tabel trkelompok_perizinan
//    private $_id_izin_dengan_tarif = array(4,5); //ID kelompok izin yang memiliki tarif dari tabel trkelompok_perizinan

    protected $__status_ditolak = 9;//Status Ditolak

    public function __construct() {
        parent::__construct();

        $this->load->model('pelayanan/trkelompok_perizinan');
        $this->trkelompok_perizinan = new trkelompok_perizinan();

        if(! isset($this->session_info['is_logged_in']) )
            $this->session_info['is_logged_in'] = $this->session->userdata('is_logged_in');

        if($this->session_info['is_logged_in'] === false) {
            $data = array(
                'uri' => uri_string()
            );
            $this->session->set_userdata($data);
            redirect('login');
        }

//        $this->output->enable_profiler(TRUE);
        // Global setting from session
        $this->session_info['username'] = $this->session->userdata('username');
        $this->session_info['id_auth']  = $this->session->userdata('id_auth');
        $this->session_info['last_log'] = $this->session->userdata('last_log');
        $this->session_info['realname'] = $this->session->userdata('realname');

        $this->settings = new settings();
        
        $this->settings->where('name','app_name')->get();
        $this->session_info['app_name'] = $this->settings->value;

        $this->settings->where('name','app_right')->get();
        $this->session_info['app_footer'] = "Copyright ©" . date('Y') . " " . $this->settings->value;

        $this->settings->where('name','app_folder')->get();
        $folder = $this->settings->value . "/";
        $this->session_info['app_folder'] = $this->settings->value;

        $user = new user();
        $user->where('username', $this->session_info['username'])->get();
        $this->session_info['app_list_auth'] = $user->user_auth->order_by('id_role', 'DESC')->get();

        $this->load->library('Menu_loader');
        // Setting up the template
        $this->template->set_layout('admin/layout');
        $this->template->enable_parser(FALSE); // default true

        $this->template
                ->set_style('css', 'facebox.css')
                ->set_style('css', $folder . 'admin.css')
                ->set_style('css', $folder . 'form.css')
                ->set_style('css', $folder . 'default.css')
                ->set_style('css', $folder . 'dropdown.css')
                ->set_style('css', $folder . 'default.ultimate.css')
                ->set_style('css', $folder . 'demo_table.css')
                ->set_style('css', $folder . 'demo_table_jui.css')
                ->set_style('css', $folder . 'themes/smoothness/jquery-ui-1.8.2.custom.css')
                ->set_style('css', 'global.css')
                ->set_style('css', 'jquery.multiselect.css')
                ->set_style('css', 'jquery.multiselect.filter.css')
                ->set_style('css', 'jquery.ui.combogrid.css')
                ->set_style('css', 'inputosaurus.css')
                ->set_style('css', 'loadover.css')
                ->set_style('css', 'jquery.dynatree-1.2.5-all/skin/ui.dynatree.css')
//                ->set_style('css', 'tautocomplete/tautocomplete.css')
                ->set_style('css', 'michael-multiselect/common.css')//Untuk setting hak akses Modul Setting Perizinan
                ->set_style('css', 'michael-multiselect/ui.multiselect.css')//Untuk setting hak akses Modul Setting Perizinan
                ->set_style('js', 'base_url.js')
//                ->set_style('js', 'jquery-1.4.2.min.js')
//                ->set_style('js', 'jquery-1.5.min.js')
                ->set_style('js', 'jquery-1.7.min.js')
                ->set_style('js', 'jquery.dataTables.js')
                ->set_style('js', 'jsonp.js')
                ->set_style('js', 'facebox.js')
                ->set_style('js', 'jquery-ui-1.8.2.custom.min.js')
                ->set_style('js', 'jquery.multiselect.min.js')
                ->set_style('js', 'jquery.multiselect.filter.min.js')
                ->set_style('js', 'jquery.validate.js')
                ->set_style('js', 'jquery.ui.combogrid-1.6.2.mod.js')
                ->set_style('js', 'inputosaurus.js')
                ->set_style('js', 'loadover.js')
                ->set_style('js', 'batra.js')
//                ->set_style('js', 'tautocomplete/tautocomplete.js')//Untuk Enhancement modul Pendaftaran
                ->set_style('js', 'michael-multiselect/ui.multiselect.js')//Untuk setting hak akses Modul Setting Perizinan
                ->set_style('js', 'michael-multiselect/localisation/jquery.localisation-min.js')//Untuk setting hak akses Modul Setting Perizinan
                ->set_style('js', 'michael-multiselect/scrollTo/jquery.scrollTo-min.js')//Untuk setting hak akses Modul Setting Perizinan
                ->set_style('js', 'jquery.dynatree-1.2.5-all/jquery.dynatree.min.js');//Untuk modul Property API

        $this->template->set_partial('header', 'admin/partials/header', FALSE);
        $this->template->set_partial('title', 'admin/partials/title', FALSE);
        $this->template->set_partial('navigation', 'admin/partials/navigation', FALSE);
        $this->template->set_partial('footer', 'admin/partials/footer', FALSE);

//        $this->output->cache(30);

        ##BEGIN - Checking ACL
        // Use whatever user script you would like, just make sure it has an ID field to tie into the ACL with
//        $this->load->library('user',array('username'=>'admin','password'=>'abc123') );

        // Get the user's ID and add it to the config array
        $config = array('userID'=>$this->session->userdata['id_auth']);

        // Load the ACL library and pas it the config array
        $this->load->library('Acl',$config);

        // Get the perm key
        // I'm using the URI to keep this pretty simple ( http://www.example.com/test/this ) would be 'test_this'
//        $acl_test = $this->uri->segment(1).'_';
        $acl_test = '/'.$this->uri->segment(1);
//        $acl_test .= ($this->uri->segment(2)!="")?$this->uri->segment(2):'index';
        $acl_test .= ($this->uri->segment(2)!="")? '/'.$this->uri->segment(2) : '';
        $acl_test .= ($this->uri->segment(3)!="" && (!is_numeric($this->uri->segment(3)) || $this->uri->segment(1)== 'pendaftaran' ) ) ? '/'.$this->uri->segment(3) : '';//Jika segment ke 3 adalah id, tidak perlu dimasukkan ke key pengecekan

        // If the user does not have permission either in 'user_perms' or 'role_perms' redirect to login, or restricted, etc
        if($acl_test != '_index'){//Agar di menu utama tidak dicek
            if ( !$this->acl->hasPermission($acl_test) ) {//Jika tidak ada permission
                $this->session->set_flashdata('flash_message', array('message' => 'Maaf, anda tidak memiliki otoriasi pada [Modul : <strong>'.$this->uri->segment(1).'</strong>] [Key : <strong>'.$acl_test.'</strong>] untuk mengakses halaman tersebut','class' => 'error'));
                redirect('/');
            }
        }
        ##END - Checking ACL
    }
	
	/**
	* Fungsi untuk mengecek apakah seorang user adalah administrator atau tidak
	* @Author Indra Halim
	*/
	protected function __is_administrator(){
		$ret=false;
		$id_role_administrator=18;
		$user=new user();
		$result=$user->where('username', $this->session_info['username'])->where_related('user_auth','id_role',$id_role_administrator)->count();
        if($result>0){
			$ret=true;
		}
		return $ret;
	}

    /**
     * @author Indra
     * Fungsi untuk mendapatkan daftar kelompok izin yang memiliki tinjauan
     * @return array
     */
	protected function __get_izin_dengan_tinjauan(){
        $listIdTinjauan = array();
        $idStatusTinjauan = array(4,19);//Penjadwalan Tinjauan, Entry Hasil Tinjauan
        $getKelompok = $this->trkelompok_perizinan
            ->select('id')->distinct(true)
            ->where_in_related('trlangkah_perizinan/trstspermohonan','id', $idStatusTinjauan)
            ->get();
        if($getKelompok->id){
            foreach($getKelompok as $kelompokIzin){
                $listIdTinjauan[] = $kelompokIzin->id;
            }
        }
//		return $this->_id_izin_dengan_tinjauan;
		return $listIdTinjauan;
	}

    /**
     * @author Indra
     * Fungsi untuk mendapatkan daftar kelompok izin yang memiliki rekomendasi
     * @return array
     */
	protected function __get_izin_dengan_rekomendasi(){
        $listIdRekomendasi = array();
        $idStatusRekomendasi = array(5);//Rekomendasi
        $getKelompok = $this->trkelompok_perizinan
            ->select('id')->distinct(true)
            ->where_in_related('trlangkah_perizinan/trstspermohonan','id', $idStatusRekomendasi)
            ->get();
        if($getKelompok->id){
            foreach($getKelompok as $kelompokIzin){
                $listIdRekomendasi[] = $kelompokIzin->id;
            }
        }
//		return $this->_id_izin_dengan_rekomendasi;
		return $listIdRekomendasi;
	}

    /**
     * Fungsi untuk mendapatkan daftar id kelompok izin yang memiliki tarif
     * NOTES: Fungsi ini jangan dihilangkan, karena sering dipakai. Contohnya saat ambil SK
     * @return array
     */
    protected function __get_izin_dengan_tarif(){
        $listIdTarif = array();
        $idStatusTarif = array(13);//Kasir
        $getKelompok = $this->trkelompok_perizinan
            ->select('id')->distinct(true)
            ->where_in_related('trlangkah_perizinan/trstspermohonan','id', $idStatusTarif)
            ->get();
        if($getKelompok->id){
            foreach($getKelompok as $kelompokIzin){
                $listIdTarif[] = $kelompokIzin->id;
            }
        }
//        return $this->_id_izin_dengan_tarif;
        return $listIdTarif;
    }

    protected function __get_izin_dengan_bap(){
        $listIdBap = array();
        $idStatusBap = array(6);//BAP
        $getKelompok = $this->trkelompok_perizinan
            ->select('id')->distinct(true)
            ->where_in_related('trlangkah_perizinan/trstspermohonan','id', $idStatusBap)
            ->get();
        if($getKelompok->id){
            foreach($getKelompok as $kelompokIzin){
                $listIdBap[] = $kelompokIzin->id;
            }
        }
//        return $this->_id_izin_dengan_bap;
        return $listIdBap;
    }

    /**
     * Fungsi untuk insert tracking progress dengan status baru dan mengupdate status lama
     * @param $id_permohonan
     * @param $status_skr
     * @param $status_baru
     */
    protected function __input_tracking_progress($id_permohonan, $status_skr, $status_baru){
        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($id_permohonan);

        $status_izin = $permohonan->trstspermohonan->get();

        /* Cek Data Tracking Progress */
        $sts_izin = new trstspermohonan();
        $sts_izin->get_by_id($status_skr);
        $data_status = new tmtrackingperizinan_trstspermohonan();
        $list_tracking = $permohonan->tmtrackingperizinan->get();
        if ($list_tracking) {
            $tracking_id = 0;
            foreach ($list_tracking as $data_track) {
                $data_status = new tmtrackingperizinan_trstspermohonan();
                $data_status->where('tmtrackingperizinan_id', $data_track->id)
                    ->where('trstspermohonan_id', $sts_izin->id)->get();
                if ($data_status->tmtrackingperizinan_id) {
                    $tracking_id = $data_status->tmtrackingperizinan_id;
                }
            }
        }

        /*Update Data Tracking Progress*/
        $tracking_izin = new tmtrackingperizinan();
        $last_tracking = $tracking_izin->get_by_id($tracking_id);
        if($last_tracking->id){
            //$tracking_izin->pendaftaran_id = $permohonan->pendaftaran_id;
            $tracking_izin->status = 'Update';
            $tracking_izin->d_entry = $this->lib_date->get_date_now();
            $tracking_izin->save(); //Update status lama
        }

        /* [Lihat Tabel trstspermohonan()] */
        $tracking_izin2 = new tmtrackingperizinan();
        $tracking_izin2->pendaftaran_id = $permohonan->pendaftaran_id;
        $tracking_izin2->status = 'Insert';
        $tracking_izin2->d_entry_awal = $this->lib_date->get_date_now();
        $tracking_izin2->d_entry = $this->lib_date->get_date_now();
        $sts_izin2 = new trstspermohonan();
        $sts_izin2->get_by_id($status_baru); //[Lihat Tabel trstspermohonan()]
        $sts_izin2->save($permohonan); //Insert Status Baru
        $tracking_izin2->save($permohonan);
        if($tracking_izin2->save($sts_izin2)){
            //echo 'berhasil input status '.$status_baru."<br>";
        }

        ##BEGIN - Kirim Notifikasi##
        //Added 16 Dec 2014
        $this->load->model('notification_setting/setting_notifikasi');
        $this->setting_notifikasi = new setting_notifikasi();
        if($this->setting_notifikasi->send_notification($status_baru, $id_permohonan)){
            //berhasil kirim notifikasi
            //echo 'berhasil kirim';
        }else{
            //tidak berhasil kirim notifikasi
            //echo 'tidak berhasil kirim';
        }
        //exit();
        ##END - Kirim Notifikasi##
    }

    protected function __get_current_unitkerja(){
        #### Ambil Unit Kerja dari user yang login ####
        $this->load->model('pengguna/user');
        $this->user = new user();
        $user_id = $this->session_info['id_auth'];
        $current_user = $this->user->get_by_id($user_id);
        $current_unitkerja = $current_user->tmpegawai->trunitkerja->get();
        return $current_unitkerja;
        ################################################
    }

    protected function __get_current_unitakses(){
        //Ambil List Unit Kerja yang berhak diakses datanya oleh User yang sedang login
        $listUnitIds = array();

        $this->load->model('unitkerja/trunitkerja');
        $this->trunitkerja = new trunitkerja();
        $user_id = $this->session_info['id_auth'];
//        $current_user = $this->user->get_by_id($user_id);
        $hakAkses = $this->trunitkerja
            ->where_in_related('trunitkerja_user/user','id',$user_id)->get();
        if($hakAkses->id){
            foreach($hakAkses as $key=>$akses){
                $listUnitIds[] = $akses->id;
            }
        }
        return $listUnitIds;
    }

    /**
     * @author Indra Halim
     * Fungsi untuk mengupdate status suatu permohonan menjadi ditolak
     * Fungsi ini diambil dari controller Penetapan, fungsi save()
     * @param $tmpermohonan_id
     * @param $status_sekarang
     */
    protected function __rejectPermohonan($tmpermohonan_id, $status_sekarang){
        $result = false;
        $this->load->model('pelayanan/tmpermohonan');
        $this->load->model('permohonan/tmsk');
        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($tmpermohonan_id);

        if($permohonan->id){
            $tgl_skr = $this->lib_date->get_date_now();

            $permohonan->c_penetapan = 2;//Ditolak
            $permohonan->save();

            $data_id = new tmsk();

            $data_id->select_max('id')->get();
            $data_id->get_by_id($data_id->id);

            //Per Tahun Auto Restart NoUrut
            $data_tahun = date("Y");
            if($permohonan->d_tahun == $data_tahun)
                $data_urut = $data_id->i_urut;
            else $data_urut = 1;

            $surat_sk = new tmsk();
            $surat_sk->i_urut = $data_urut;
            $surat_sk->no_surat = "Ditolak";
            $surat_sk->c_status = 1;
            $surat_sk->tgl_surat = $tgl_skr;

            /* Input Relasi Tabel*/
            /*$petugas = 1; //1 -> Jabatan Penandatangan
            $pegawai = new tmpegawai();
            $pegawai->where('status', $petugas)->get();*/

    //        $surat_sk->save(array($permohonan, $pegawai));
            $surat_sk->save(array($permohonan));

            //Kirim SMS Izin ditolak
    //        $text = "Surat " . $nama_izin . " dgn no daftar " . $no_pendaftaran ." telah ditolak. Dgn alasan " . $bap->c_pesan;

            /*if(strlen($text) > 160) {
                $text = NULL;
                $text = "Surat Anda dgn no daftar " . $no_pendaftaran . " telah ditolak. ";
            }*/

            $id_status = $this->__status_ditolak;
            $this->__input_tracking_progress($tmpermohonan_id, $status_sekarang, $id_status);
            $result = true;
        }
        return $result;
    }

    public function __is_rejected_permohonan($tmpermohonan_id){
        $this->load->model('pelayanan/tmpermohonan');
        $is_rejected = false;
        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($tmpermohonan_id);
        if($permohonan->c_penetapan == 2){
            $is_rejected = true;
        }
        return $is_rejected;
    }
}

// This is the end of WRC_AdminCont class