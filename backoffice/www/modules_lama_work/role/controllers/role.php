<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of role class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class Role extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->role = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->role = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '1') {
                $enabled = TRUE;
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        } else {
            $this->role = new user_auth();
        }*/
    }

    public function index() {
        $data['list'] = $this->role->get();
        $this->load->vars($data);
        
        $js =  "
             function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                        oTable = $('#role').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Hak Akses";
        $this->template->build('list', $this->session_info);
    }

    public function create() {
        $data['description']  = "";
        $data['id_role']  = "";
        $data['save_method'] = "save";
        $data['id'] = "";

        $this->load->vars($data);
        $js =  "
                $(document).ready(function() {
                    $('#form').validate();
                });
                ";

        $this->template->set_metadata_javascript($js);
        
        $this->session_info['page_name'] = "Tambah Hak Akses";
        $this->template->build('edit', $this->session_info);
    }

    /*
     * edit is a method to show page for updating data
     */
    public function edit($id_role = NULL) {

        $this->role->where('id', $id_role);
        $this->role->get();

        $data['id'] = $this->role->id;
        $data['id_role']     = $this->role->id_role;
        $data['description'] = $this->role->description;
        $data['save_method'] = "update";
        
        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Hak Akses";
        $this->template->build('edit', $this->session_info);

    }


    /**
     * Not yet in use, because of some limit of dataTables
     */
    public function datalist() {

        $this->role->get();
        $this->role->set_json_content_type();
        echo $this->role->json_for_data_table();

    }

    /*
     * Save and update for manipulating data.
     */
    public function save() {

        $this->role->description = $this->input->post('description');
        $this->role->id_role = $this->input->post('id_role');
        if(! $this->role->save()) {
            echo '<p>' . $this->role->error->string . '</p>';
        } else {
            redirect('role');
        }

    }

    public function update() {
        $update = $this->role
                ->where('id', $this->input->post('id'))
                ->update(array('id_role' => $this->input->post('id_role'),
                    'description' => $this->input->post('description')));
        if($update) {
            redirect('role');
        }
    }

    public function delete($id = NULL) {
        $this->role->where('id', $id)->get();
        if($this->role->delete()) {
            redirect('role');
        }
    }

    /*
     * Method for validating
     */

}

// This is the end of role class
