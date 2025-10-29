<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * model untuk table permission
 * @author  Indra
 *
 */

class Role_permission extends DataMapper{
    var $table = 'role_perms';

    public function __construct() {
        parent::__construct();
    }

    public function enablePermission($roleId, $permissionId){
        $ret = false;
        $getExistingRolePerm = $this->db->where('user_auth_id', $roleId)->where('permission_id', $permissionId)->get($this->table)->result();
        if(!empty($getExistingRolePerm)){//Jika sudah ada data di database
            $data = array(
                'value'=>1
            );
            $this->db->where('id',$getExistingRolePerm[0]->id);
            $ret = $this->db->update($this->table,$data);
        }else{//Jika belum ada di database, create
            $data = array(
                'user_auth_id'=>$roleId,
                'permission_id'=>$permissionId,
                'value'=>1,
                'add_date'=>date('Y-m-d H:i:s')
            );
            $ret = $this->db->insert($this->table,$data);
        }
        return $ret;
    }
}
?>