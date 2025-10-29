<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of maintainer class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class Maintainer extends WRC_AdminCont {
    
//    public function __construct() {
//        parent::__construct();
//        $enabled = FALSE;
//        $list_auths = $this->session_info['app_list_auth'];
//
//        foreach ($list_auths as $list_auth) {
//            if($list_auth->id_role === '10') {
//                $enabled = TRUE;
//            }
//        }
//
//        if(!$enabled) {
//            redirect('dashboard');
//        }
//
//        $this->keys = new keys();
//        $this->logs = new logs();
//
//    }

//    public function index() {
//        $data['list'] = $this->keys->get();
//        $this->load->vars($data);
//
//        $js =  "
//                $(document).ready(function() {
//                        oTable = $('#key').dataTable({
//                                \"bJQueryUI\": true,
//                                \"sPaginationType\": \"full_numbers\"
//                        });
//                } );
//                ";
//
//        $this->template->set_metadata_javascript($js);
//
//        $this->session_info['page_name'] = "Manajemen API Key";
//        $this->template->build('list', $this->session_info);
//    }
//
//    public function generate() {
//
//    }
//
//
//    // --------------------------------------------------------------------
//
//    /* Helper Methods */
//
//    private function _generate_key() {
//        $this->load->helper('security');
//
//        do {
//            $salt = dohash(time() . mt_rand());
//            $new_key = substr($salt, 0, config_item('rest_key_length'));
//        }
//
//        // Already in the DB? Fail. Try again
//        while (self::_key_exists($new_key));
//
//        return $new_key;
//    }
//
//    // --------------------------------------------------------------------
//
//    /* Private Data Methods */
//
//    private function _get_key($key) {
//        return $this->rest->db->where('key', $key)->get('keys')->row();
//    }
//
//    // --------------------------------------------------------------------
//
//    private function _key_exists($key) {
//        return $this->rest->db->where('key', $key)->count_all_results('keys') > 0;
//    }
//
//    // --------------------------------------------------------------------
//
//    private function _insert_key($key, $data) {
//        var_dump($data);
//
//        $data['key'] = $key;
//        $data['date_created'] = function_exists('now') ? now() : time();
//
//        return $this->rest->db->set($data)->insert('keys');
//    }
//
//    // --------------------------------------------------------------------
//
//    private function _update_key($key, $data) {
//        return $this->rest->db->where('key', $key)->update('keys', $data);
//    }
//
//    // --------------------------------------------------------------------
//
//    private function _delete_key($key) {
//        return $this->rest->db->where('key', $key)->delete('keys');
//    }

}

// This is the end of maintainer class
