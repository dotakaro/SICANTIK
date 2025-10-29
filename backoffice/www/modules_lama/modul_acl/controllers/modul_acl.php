<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of hitung retribusi class
 * Class untuk Hitung Retribusi
 * @author  Indra Halim
 * @since   1.0
 *
 */

class Modul_acl extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->load->model('permission');
    }

    public function index() {
        $data['list'] = array();
        $this->load->vars($data);

        $js =  "function confirm_link(text){
                   if(confirm(text)){ return true;
                   }else{ return false; }
               }
               $(document).ready(function() {
                       oTable = $('#listApi').dataTable({
                               \"bJQueryUI\": true,
                               \"sPaginationType\": \"full_numbers\"
                       });
               } );
               ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Modul Access Control List";
        $this->template->build('index', $this->session_info);
    }

    public function list_aco(){
        $permissionData = $this->permission->get();
        $data['list'] = $permissionData;

        $this->load->vars($data);

        $js =  "function confirm_link(text){
                   if(confirm(text)){ return true;
                   }else{ return false; }
               }
               function delete_all_acl(){
                    var confirmation = window.confirm('Apakah anda yakin ingin menghapus semua ACL beserta Permission untuk setiap Peran?');
                    if(confirmation){
                        parent.location = '".site_url('modul_acl/delete_all_acl')."';
                    }
               }
               $(document).ready(function() {
                       oTable = $('#listAco').dataTable({
                               \"bJQueryUI\": true,
                               \"sPaginationType\": \"full_numbers\"
                       });
               } );
               ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Objek Access Control";
        $this->template->build('list_aco', $this->session_info);
    }

    public function add_aco(){
        $this->load->helper('file');
        $data = array();
        $data = array_merge($data, $this->_prepareFormAco());
        $data['save_method'] = 'save_add_aco';

        $this->load->vars($data);
        $js ="";
        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Add ACO";
        $this->template->build('add_aco', $this->session_info);
    }

    public function edit_aco($permissionId){
        $this->load->helper('file');
        $data = array();
        $data = array_merge($data, $this->_prepareFormAco());

        $getData = $this->permission->get_by_id($permissionId);
        if(!$getData->id){//Jika data tidak ditemukan, redirect ke List ACO
            $this->session->set_flashdata('flash_message', array('message' => 'Maaf, ACO yang anda akses tidak ada','class' => 'error'));
            redirect('modul_acl/list_aco');
        }
        $data['data'] = $getData;
        $data['save_method'] = 'save_edit_aco';
        $this->load->vars($data);
        $js ="";
        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Edit ACO";
        $this->template->build('edit_aco', $this->session_info);
    }

    /**
     * Fungsi untuk menyimpan data aco baru
     */
    public function save_add_aco(){
        $permName = $this->input->post('perm_name');
        $permKey = $this->input->post('perm_key');
        $mainModuleName = $this->input->post('main_module_name');
        $this->permission->perm_name = $permName;
        $this->permission->perm_key = $permKey;
        $this->permission->main_module_name = $mainModuleName;
        if($this->permission->save()){
            $this->session->set_flashdata('flash_message', array('message' => 'Data ACO berhasil disimpan','class' => 'success'));
            redirect('modul_acl/list_aco');
        }else{
            $this->session->set_flashdata('flash_message', array('message' => 'Data ACO tidak berhasil disimpan. Silahkan coba lagi','class' => 'error'));
            redirect('modul_acl/add_aco/');
        }
    }

    /**
     * Fungsi untuk menyimpan perubahan pada aco
     */
    public function save_edit_aco(){
        $acoId = $this->input->post('id');
        if(!$this->permission->get_by_id($acoId)){//Jika tidak ada data tersebut, redirect ke List ACO
            $this->session->set_flashdata('flash_message', array('message' => 'Maaf, ACO yang anda akses tidak ada','class' => 'error'));
            redirect('modul_acl/list_aco');
        }
        $permName = $this->input->post('perm_name');
        $permKey = $this->input->post('perm_key');
        $mainModuleName = $this->input->post('main_module_name');
        $this->permission->perm_name = $permName;
        $this->permission->perm_key = $permKey;
        $this->permission->main_module_name = $mainModuleName;
        if($this->permission->save()){
            $this->session->set_flashdata('flash_message', array('message' => 'Data ACO berhasil disimpan','class' => 'success'));
            redirect('modul_acl/list_aco');
        }else{
            $this->session->set_flashdata('flash_message', array('message' => 'Data ACO tidak berhasil disimpan. Silahkan coba lagi','class' => 'error'));
            redirect('modul_acl/edit_aco/'.$acoId);
        }
    }

    public function sync_with_menu(){
        //BEGIN - Copy menu ke table permissions
        $queryCopyMenu = "INSERT INTO permissions(perm_key, perm_name, main_module_name)
                SELECT a.link, a.title, b.title FROM menus a
                LEFT JOIN  menus b  ON a.parent = b.id
                WHERE a.link NOT IN ('/', '#','login/logoff')";
        $success = $this->db->query($queryCopyMenu);
        //END - Copy menu ke table permissions

        //BEGIN - Insert semua permission ke table role_permissions untuk setiap role
        $this->load->model('role/user_auth');
        $objRole = new user_auth();
        $objPermission = new permission();
        $getRole = $objRole->get();
        if($getRole->id){//Jika ada Data Master ROle
            $getPermission = $objPermission->get();
            if($getPermission->id){//Jika ada data Master Permission
                foreach($getPermission as $indexPermission=>$permission){
                    foreach($getRole as $key=>$role){
                        $rolePerm = new Role_permission();
                        //cek apakah record dengan user_auth_id dan permission_id yang sama sudah ada atau belum
                        $existingRecord = $rolePerm->where('user_auth_id', $role->id)->where('permission_id', $permission->id)->get();
                        if(!$existingRecord->id){//Jika belum ada, insert baru
                            $rolePerm->user_auth_id = $role->id;
                            $rolePerm->permission_id = $permission->id;
                            $rolePerm->value = 1;
                            $rolePerm->add_date = date('Y-m-d');
                            $rolePerm->save();
                        }
                    }
                }
            }
        }
        //END - Insert semua permission ke table role_permissions untuk setiap role

        $this->session->set_flashdata('flash_message', array('message' => 'Sinkronisasi berhasil','class' => 'success'));
        redirect('modul_acl');
    }

    public function delete_all_acl(){
        $objAcl = new permission();
        $getAcl = $objAcl->get();

        $rolePerm = new Role_permission();
        $rolePerm->get()->delete_all();

        if($getAcl->delete_all()){
            $this->session->set_flashdata('flash_message', array('message' => 'Semua ACL telah dihapus','class' => 'success'));
            redirect('modul_acl');
        }else{
            $this->session->set_flashdata('flash_message', array('message' => 'Delete tidak berhasil','class' => 'error'));
            redirect('modul_acl');
        }
    }

    /**
     *
     */
    public function build_aco(){
        exit();
        $this->load->helper('file');
        $allAcos = array();//Daftar ACO yang discan
        $newAcos = array();//Daftar ACO yang akan dibuild

        $controllers = array();

        ##BEGIN - Scan Direktori www/modules##
        $modulesPath = APPPATH.'modules/';
        $allDir = get_dir_info($modulesPath);
//        var_dump($allDir);
        ##END - Scan Direktori www/modules##

        // Scan files in the www/modules/controllers directory
        if(!empty($allDir)){
            foreach($allDir as $indexModule => $dirName){
                $allAcos[$indexModule]['main_module_name'] = $dirName;

                $pathToControllers = $modulesPath.$dirName.'/controllers';

                // Set the second param to TRUE or remove it if you
                // don't have controllers in sub directories
                $files = get_dir_file_info($pathToControllers, FALSE);

                // Loop through file names removing .php extension
                foreach (array_keys($files) as $index2=>$file)
                {
                    if(substr($file, -4, 4) == EXT){//Jika 4 karakter paling kanan dari nama file sama dengan .php
                        $pathToClass = $pathToControllers.'/'.$file;

                        $controllerName = str_replace(EXT, '', $file);
                        $className = $controllerName;
                        $allMethods = get_public_methods($pathToClass, $className);
//                        var_dump($allMethods);
                        $allAcos[$indexModule]['controllers'][$index2]['controller_name'] = $className;
                        if(!empty($allMethods)){
                            $allAcos[$indexModule]['controllers'][$index2]['methods'] = $allMethods;
                        }
                    }
                }
            }
        }
        var_dump($allAcos);exit();
        exit();
    }

    function list_role(){
        $this->load->model('role/user_auth');
        $roleData = $this->user_auth->get();
        $data['list'] = $roleData;
        $this->load->vars($data);

        $js =  "function confirm_link(text){
                   if(confirm(text)){ return true;
                   }else{ return false; }
               }
               $(document).ready(function() {
                       oTable = $('#listRole').dataTable({
                               \"bJQueryUI\": true,
                               \"sPaginationType\": \"full_numbers\"
                       });
               } );
               ";
        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Daftar Peran";
        $this->template->build('list_role', $this->session_info);
    }

    function add_role(){
        $this->load->model('role/user_auth');
        $this->load->model('role_permission');

        $permissionTree = $this->_prepareNewPermissionTree();

        $data['permissionTree'] = $permissionTree;
        $data['save_method'] = 'save_role';
        $this->load->vars($data);
        $js ="";
        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Tambah Peran";
        $this->template->build('add_role', $this->session_info);
    }

    function edit_role($roleId){
        $this->load->model('role/user_auth');
        $this->load->model('role_permission');

        $permissionTree = $this->_preparePermissionTree($roleId);

        $getDataRole = $this->user_auth->get_by_id($roleId);
        if(!$getDataRole->id){//Jika data tidak ditemukan, redirect ke List ACO
            $this->session->set_flashdata('flash_message', array('message' => 'Maaf, Peran yang anda akses tidak ada','class' => 'error'));
            redirect('modul_acl/list_role');
        }
        $data['dataRole'] = $getDataRole;
        $data['permissionTree'] = $permissionTree;
        $data['save_method'] = 'save_role';
        $this->load->vars($data);
        $js ="";
        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Edit Peran";
        $this->template->build('edit_role', $this->session_info);
    }

    /**
     * Fungsi untuk load semua Permission dan disusun ke dalam tree ul li pada saat Add Role Baru
     */
    private function _prepareNewPermissionTree(){
        $html = '';
        $x = 0;
        $getAllModule = $this->db->select('main_module_name')->distinct()->get('permissions')->result();
        if(!empty($getAllModule)){
            $html = '<ul>';
            foreach($getAllModule as $indexModule=>$module){
                $html .= '<li id="module_'.$indexModule.'">'.$module->main_module_name;
                //Ambil Permission yang modulnya sesuai di atas
                $getPermissions = $this->db
                    ->select('permissions.*')
                    ->where('main_module_name', $module->main_module_name)
                    ->get('permissions')
                    ->result();
                if(!empty($getPermissions)){
                    $html .= '<ul>';
                    foreach($getPermissions as $indexPerm=>$permission){
                        $selected = 'select:false';
                        $html .=
                            '<li data="'.$selected.',id:'.$x.'" id="'.$x.'">'.
                            '<input type="checkbox" id="permission_'.$x.'" name="permission['.$x.']" value="'.$permission->id.'"/>'.
                            '<strong>'.$permission->perm_key.'</strong> - '.$permission->perm_name.
                            '</li>';
                        $x++;
                    }
                    $html .= '</ul>';
                }
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }


    /**
     * Fungsi untuk load semua Permission dan disusun ke dalam tree ul li
     */
    private function _preparePermissionTree($roleId){
        $html = '';
        $x = 0;
        $getAllModule = $this->db->select('main_module_name')->distinct()->get('permissions')->result();
        if(!empty($getAllModule)){
            $html = '<ul>';
            foreach($getAllModule as $indexModule=>$module){
                $html .= '<li id="module_'.$indexModule.'">'.$module->main_module_name;
                //Ambil Permission yang modulnya sesuai di atas
                $getPermissions = $this->db
                    ->select('permissions.*, role_perms.value AS allowed')
                    ->join('role_perms', 'role_perms.permission_id = permissions.id AND role_perms.user_auth_id = '.$roleId, 'left')
                    ->where('main_module_name', $module->main_module_name)
                    ->get('permissions')
                    ->result();
                if(!empty($getPermissions)){
                    $html .= '<ul>';
                    foreach($getPermissions as $indexPerm=>$permission){
                        $selected = 'select:false';
                        if($permission->allowed == 1){
                            $selected = 'select:true';
                        }
                        $html .=
                            '<li data="'.$selected.',id:'.$x.'" id="'.$x.'">'.
                                '<input type="checkbox" id="permission_'.$x.'" name="permission['.$x.']" value="'.$permission->id.'"/>'.
                                '<strong>'.$permission->perm_key.'</strong> - '.$permission->perm_name.
                            '</li>';
                        $x++;
                    }
                    $html .= '</ul>';
                }
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }

    /**
     * Fungsi untuk menyimpan Role beserta Permission
     */
    function save_role(){
        $numSuccess = 0;
        $numFailed = 0;
        $roleId = null;
        $this->load->model('role/user_auth');
        $this->load->model('role_permission');

        ##BEGIN - Save Data Role
        if($this->input->post('id')){
            $roleId = $this->input->post('id');
            $roleDesc = $this->input->post('description');
            $this->user_auth->id = $roleId;
            $this->user_auth->description = $roleDesc;
            $this->user_auth->save();
        }else{
            $roleDesc = $this->input->post('description');
            $this->user_auth->description = $roleDesc;
            if(!$this->user_auth->save()){
                $this->session->set_flashdata('flash_message', array('message' => 'Terjadi kesalahan saat menyimpan Role','class' => 'error'));
            }else{
                $roleId = $this->user_auth->id;
            }
        }
        ##END - Save Data Role

        $this->user_auth->disableAllRolePermission($roleId);
        foreach($this->input->post('permission') as $key=>$permissionId){//Looping setiap data permission yang dipost
            if($this->role_permission->enablePermission($roleId, $permissionId)){//Enable Permission untuk Permission ID dan Role ID tersebut
                $numSuccess++;
            }else{
                $numFailed++;
            }
        }
        if($numFailed == 0){
            $this->session->set_flashdata('flash_message', array('message' => 'Data Role berhasil disimpan','class' => 'success'));
        }else{
            $this->session->set_flashdata('flash_message', array('message' => 'Terjadi kesalahan saat menyimpan Role','class' => 'error'));
        }
        redirect('modul_acl/list_role');
    }

    private function _prepareFormAco(){
        $queryModulUtama = "SELECT a.link, a.title, b.title FROM menus a
                                LEFT JOIN  menus b  ON a.parent = b.id
                                WHERE a.link NOT IN ('/', '#','login/logoff')"; //Query mirip dengan fungsi sync_with_menu()
        $getModulUtama = $this->db->query($queryModulUtama);
        $resultModulUtama = $getModulUtama->result();
        if(!empty($resultModulUtama)){
            foreach($resultModulUtama as $modulUtama){
                $listDir[$modulUtama->title] = $modulUtama->title;
            }
        }
        $listData = array();
        $listData['listDir'] = $listDir;
        return $listData;
    }

}
?>