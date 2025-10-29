<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of role_mdl class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class user_auth extends DataMapper {

    var $table = 'user_auth';
    var $has_many = array('tralur_perizinan', 'user');

    public function __construct() {
        parent::__construct();
    }

    public function disableAllRolePermission($roleId){
        //Update semua record menjadi not allowed
        return $this->db->where('user_auth_id', $roleId)->update('role_perms',array('value'=>0));
    }

}

// This is the end of role_mdl class
