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

class Setting_menu extends WRC_AdminCont{

    public function __construct(){
        parent::__construct();
        $this->load->model('menu');
    }

    public function index(){
//        $data['list'] = $this->menu->get();
        $queryMenu = "
            SELECT m.*,pm.title as parent_title FROM menus m
              LEFT JOIN menus pm ON pm.id=m.parent
        ";
        $data['list'] = $this->db->query($queryMenu)->result();
        $this->load->vars($data);

        $js =  "function confirm_link(text){
                   if(confirm(text)){ return true;
                   }else{ return false; }
               }
               $(document).ready(function() {
                       oTable = $('#listMenu').dataTable({
                               \"bJQueryUI\": true,
                               \"sPaginationType\": \"full_numbers\"
                       });
               } );
               ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Menu";
        $this->template->build('index', $this->session_info);
    }

    public function add(){
        $listMenu = array(0=>'No Parent');
        $getMenu = $this->menu->get();
        if($getMenu->id){
            foreach($getMenu as $key=>$menu){
                $listMenu[$menu->id] = $menu->title;
            }
        }
        $data['listMenu'] = $listMenu;
        $data['save_method'] = 'save';
        $this->load->vars($data);
        $js ="";
        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Add Menu";
        $this->template->build('add', $this->session_info);
    }

    public function edit($idMenu){
        $getData = $this->menu->get_by_id($idMenu);
        if(!$getData->id){//Jika data tidak ditemukan, redirect ke index
            $this->session->set_flashdata('flash_message', array('message' => 'Maaf, Menu yang anda akses tidak ada','class' => 'error'));
            redirect('setting_menu');
        }

        $listMenu = array(0=>'No Parent');
        $menuObj2 = new menu();
        $getMenuList = $menuObj2->get();
        if($getMenuList->id){
            foreach($getMenuList as $key=>$menu){
                $listMenu[$menu->id] = $menu->title;
            }
        }
        $data['listMenu'] = $listMenu;
        $data['data'] = $getData;
        $data['save_method'] = 'update';
        $this->load->vars($data);
        $js ="";
        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Edit Menu";
        $this->template->build('edit', $this->session_info);
    }

    public function save(){
        // Load the MPTT library
//        $this->load->library('Zebra_Mptt');
//        $mptt = new Zebra_Mptt('menus', 'id', 'title', 'lft', 'rgt', 'parent_id');

        $this->menu = new menu();
        $title = $this->input->post('title');
        $link = $this->input->post('link');
        $parentId = $this->input->post('parent');
        $menuOrder = $this->input->post('menu_order');

        $this->menu->title = $title;
        $this->menu->link = $link;
        $this->menu->parent = $parentId;
        $this->menu->menu_order = $menuOrder;

        if(!$this->menu->save()){
            $this->session->set_flashdata('flash_message', array('message' => 'Gagal menyimpan Menu. Silahkan coba lagi','class' => 'error'));
        }else{
            $this->session->set_flashdata('flash_message', array('message' => 'Berhasil menyimpan Menu','class' => 'success'));
            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $p = $this->db->query("call log ('Setting Menu','Save Menu" . $title . "','" . $tgl . "','" . $u_ser . "')");

            //Masukkan ke Parent
//            $mptt->move($this->menu->id, $parentId);

            redirect('setting_menu');
        }
    }

    public function update(){
        // Load the MPTT library
//        $this->load->library('Zebra_Mptt');
//        $mptt = new Zebra_Mptt('menus', 'id', 'title', 'lft', 'rgt', 'parent_id');

        $this->menu = new menu();
        $idMenu = $this->input->post('id');
        $title = $this->input->post('title');
        $link = $this->input->post('link');
        $parentId = $this->input->post('parent');
        $menuOrder = $this->input->post('menu_order');

        $getMenu = $this->menu->get_by_id($idMenu);
        if(!$getMenu->id){
            $this->session->set_flashdata('flash_message', array('message' => 'Menu tidak valid','class' => 'error'));
        }

        //Pindahkan ke Parent Baru
//        $mptt->move($idMenu, $parentId);

        $this->menu->title = $title;
        $this->menu->link = $link;
        $this->menu->menu_order = $menuOrder;
        $this->menu->parent = $parentId;

        if(!$this->menu->save()){
            $this->session->set_flashdata('flash_message', array('message' => 'Gagal menyimpan Menu. Silahkan coba lagi','class' => 'error'));
        }else{
            $this->session->set_flashdata('flash_message', array('message' => 'Berhasil menyimpan Menu','class' => 'success'));
            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $p = $this->db->query("call log ('Setting Menu','Save Menu" . $title . "','" . $tgl . "','" . $u_ser . "')");

            redirect('setting_menu');
        }
    }

    public function delete($idMenu){
        $getData = $this->menu->get_by_id($idMenu);
        if(!$getData->id){//Jika data tidak ditemukan, redirect ke index
            $this->session->set_flashdata('flash_message', array('message' => 'Menu tidak valid','class' => 'error'));
            redirect('setting_menu');
        }else{
            $title = $getData->title;
            if($this->menu->delete()){//Jika berhasil delete
                $this->session->set_flashdata('flash_message', array('message' => 'Menu berhasil dihapus','class' => 'success'));
                $tgl = date("Y-m-d H:i:s");
                $u_ser = $this->session->userdata('username');
                $p = $this->db->query("call log ('Setting Menu','Delete Menu" . $title. "','" . $tgl . "','" . $u_ser . "')");
            }else{
                $this->session->set_flashdata('flash_message', array('message' => 'Terjadi kesalahan saat menghapus Menu. Silahkan coba lagi','class' => 'error'));
            }
            redirect('setting_menu');
        }
    }
}