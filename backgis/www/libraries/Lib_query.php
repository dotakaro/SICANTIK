<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Lib_query
 * This class created to use global query
 *
 * @author agusnur24
 *
 */
class Lib_query {

    public function __construct() {
        $this->CI = & get_instance();
    }

    function add_searching($aColumns, $WhereORAnd = ' WHERE ', $sSearch = "") {
        $sWhere = " ";
        if (isset($sSearch) && $sSearch != "") {
            $sWhere = " $WhereORAnd (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $sSearch . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ') ';
        }
        return $sWhere;
    }

    function add_sort($aColumns, $order_colum, $order_by) {
        $sql = NULL;
        for ($i = 0; $i < count($aColumns); $i++) {
            if ($i == $order_colum) {
                if ($i == 1) {
                    $sql = " ORDER BY $aColumns[$i] $order_by ";
                    break;
                } else {
                    $sql = " ORDER BY $aColumns[$i] $order_by ";
                    break;
                }
            }
        }
        return $sql;
    }

}
