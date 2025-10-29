<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @created 2015-03-25
 * @author Indra
 * Class Acl
 */
class Acl
{
    var $perms = array(); //Array : Stores the permissions for the user
    var $userID; //Integer : Stores the ID of the current user
    var $userRoles = array(); //Array : Stores the roles of the current user
    var $ci;
    function __construct($config=array()) {
        $this->ci = &get_instance();
        $this->userID = floatval($config['userID']);
        $this->userRoles = $this->getUserRoles();
        $this->buildACL();
    }

    function buildACL() {
        //first, get the rules for the user's role
        if (count($this->userRoles) > 0)
        {
            $this->perms = array_merge($this->perms,$this->getRolePerms($this->userRoles));
        }
        //then, get the individual user permissions
        $this->perms = array_merge($this->perms,$this->getUserPerms($this->userID));
    }

    function getPermKeyFromID($permID) {
        $ret = new stdClass();
        //$strSQL = "SELECT `permKey` FROM `".DB_PREFIX."permissions` WHERE `ID` = " . floatval($permID) . " LIMIT 1";
        $this->ci->db->select('perm_key');
        $this->ci->db->where('id',floatval($permID));
        $sql = $this->ci->db->get('permissions',1);
        $data = $sql->result();
        if(isset($data[0])){
            $ret = $data[0]->perm_key;
        }
        return $ret;
    }

    function getPermNameFromID($permID) {
        //$strSQL = "SELECT `permName` FROM `".DB_PREFIX."permissions` WHERE `ID` = " . floatval($permID) . " LIMIT 1";
        $this->ci->db->select('perm_name');
        $this->ci->db->where('id',floatval($permID));
        $sql = $this->ci->db->get('permissions',1);
        $data = $sql->result();
        return $data[0]->perm_name;
    }

    function getRoleNameFromID($roleID) {
        //$strSQL = "SELECT `roleName` FROM `".DB_PREFIX."roles` WHERE `ID` = " . floatval($roleID) . " LIMIT 1";
        $this->ci->db->select('description');
        $this->ci->db->where('id',floatval($roleID),1);
        $sql = $this->ci->db->get('user_auth');
        $data = $sql->result();
        return $data[0]->description;
    }

    function getUserRoles() {
        //$strSQL = "SELECT * FROM `".DB_PREFIX."user_roles` WHERE `userID` = " . floatval($this->userID) . " ORDER BY `addDate` ASC";
        $this->ci->db->where(array('user_id'=>floatval($this->userID)));
//        $this->ci->db->order_by('add_date','asc');
        $sql = $this->ci->db->get('user_user_auth');
        $data = $sql->result();

        $resp = array();
        foreach( $data as $row )
        {
            $resp[] = $row->user_auth_id;
        }
        return $resp;
    }

    function getAllRoles($format='ids') {
        $format = strtolower($format);
        //$strSQL = "SELECT * FROM `".DB_PREFIX."roles` ORDER BY `roleName` ASC";
        $this->ci->db->order_by('description','asc');
        $sql = $this->ci->db->get('user_auth');
        $data = $sql->result();

        $resp = array();
        foreach( $data as $row )
        {
            if ($format == 'full')
            {
                $resp[] = array("id" => $row->ID,"name" => $row->description);
            } else {
                $resp[] = $row->id;
            }
        }
        return $resp;
    }

    function getAllPerms($format='ids') {
        $format = strtolower($format);
        //$strSQL = "SELECT * FROM `".DB_PREFIX."permissions` ORDER BY `permKey` ASC";

        $this->ci->db->order_by('perm_key','asc');
        $sql = $this->ci->db->get('permisssions');
        $data = $sql->result();

        $resp = array();
        foreach( $data as $row )
        {
            if ($format == 'full')
            {
                $resp[$row->permKey] = array('id' => $row->ID, 'name' => $row->description, 'key' => $row->perm_key);
            } else {
                $resp[] = $row->id;
            }
        }
        return $resp;
    }

    function getRolePerms($role) {
        if (is_array($role))
        {
            //$roleSQL = "SELECT * FROM `".DB_PREFIX."role_perms` WHERE `roleID` IN (" . implode(",",$role) . ") ORDER BY `ID` ASC";
            $this->ci->db->where_in('user_auth_id',$role);
        } else {
            //$roleSQL = "SELECT * FROM `".DB_PREFIX."role_perms` WHERE `roleID` = " . floatval($role) . " ORDER BY `ID` ASC";
            $this->ci->db->where(array('user_auth_id'=>floatval($role)));
        }

        $this->ci->db->order_by('id','asc');
        $sql = $this->ci->db->get('role_perms'); //$this->db->select($roleSQL);
        $data = $sql->result();
        $perms = array();
        foreach( $data as $row )
        {
            $pK = strtolower($this->getPermKeyFromID($row->permission_id));
            if ($pK == '') { continue; }
            if ($row->value === '1') {
                $hP = true;
            } else {
                $hP = false;
            }

            //Perbaikan untuk multirole - Added by Indra
            //Jika sudah allow, value didefault Allow
            if(isset($perms[$pK])){
                if($perms[$pK]['value'] == true){
                    $hP = true;
                }
            }

            $perms[$pK] = array('perm' => $pK,'inheritted' => true,'value' => $hP,'name' => $this->getPermNameFromID($row->permission_id),'id' => $row->permission_id);

        }
        return $perms;
    }

    function getUserPerms($userID) {
        //$strSQL = "SELECT * FROM `".DB_PREFIX."user_perms` WHERE `userID` = " . floatval($userID) . " ORDER BY `addDate` ASC";

        $this->ci->db->where('user_id',floatval($userID));
        $this->ci->db->order_by('add_date','asc');
        $sql = $this->ci->db->get('user_perms');
        $data = $sql->result();

        $perms = array();
        foreach( $data as $row )
        {
            $pK = strtolower($this->getPermKeyFromID($row->permission_id));
            if ($pK == '') { continue; }
            if ($row->value == '1') {
                $hP = true;
            } else {
                $hP = false;
            }
            $perms[$pK] = array('perm' => $pK,'inheritted' => false,'value' => $hP,'name' => $this->getPermNameFromID($row->permission_id),'id' => $row->permission_id);
        }
        return $perms;
    }

    function hasRole($roleID) {
        foreach($this->userRoles as $k => $v)
        {
            if (floatval($v) === floatval($roleID))
            {
                return true;
            }
        }
        return false;
    }

    function hasPermission($permKey) {
        $permKey = strtolower($permKey);
        if (array_key_exists($permKey,$this->perms))
        {
            if ($this->perms[$permKey]['value'] === '1' || $this->perms[$permKey]['value'] === true)
            {
                return true;
            } else {
                return false;
            }
        } else {
            //Saat Nambah Record di permissions, tambahkan juga ke role_perms
//            return false;
            return true;//Jika suatu permission belum didaftarkan, maka otomatis boleh akses
        }
    }
}