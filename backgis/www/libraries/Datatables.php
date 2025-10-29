<?php

if (!defined("BASEPATH"))
    exit("No direct script access allowed");

/**
 * Datatables(.net) for CodeIgniter
 * 
 * This class/library is an attempt to port the native Datatables php script
 * found at http://datatables.net/examples/data_sources/server_side.html for CodeIgniter
 *
 * @package    CodeIgniter
 * @subpackage libraries
 * @category   library
 * @version    1.1
 * @author     Vincent Bambico <vb@newmediaservices.com.au>
 * @author     Dichi Al Faridi <dichi.alfaridi@gmail.com>
 * @link       http://codeigniter.com/forums/viewthread/160896/
 */
class Datatables {

    /**
     * CodeIgniter global variable
     *
     * @global object $ci
     * @name   $ci
     */
    protected $ci;

    /**
     * Copies an instance of CI
     */
    public function __construct() {
        $this->ci = & get_instance();
    }

    /**
     * Builds all the necessary query segments and performs the main query based on passed arguments
     *
     * @param string $table
     * @param string $columns
     * @param string $index
     * @return string
     */
    public function generate($table, $columns, $index) {
        $sLimit = $this->get_paging();
        $sOrder = $this->get_ordering($columns);
        $sWhere = $this->get_filtering($columns);
        $rResult = $this->get_display_data($table, $columns, $sWhere, $sOrder, $sLimit);
        $rResultFilterTotal = $this->get_data_set_length();
        $aResultFilterTotal = $rResultFilterTotal->result_array();
        $iFilteredTotal = $aResultFilterTotal[0]["FOUND_ROWS()"];
        $rResultTotal = $this->get_total_data_set_length($table, $index, $sWhere);
        $aResultTotal = $rResultTotal->result_array();
        $iTotal = $aResultTotal[0]["COUNT($index)"];
        return $this->produce_output($columns, $iTotal, $iFilteredTotal, $rResult);
    }

    /*
     * New method for adding button like column. :) Hope this work!
     */

    public function generate_with_relation($table, $columns, $index, $join_column) {
        $sLimit = $this->get_paging();
        $sOrder = $this->get_ordering($columns);
        $sWhere = $this->get_filtering($columns);
        $sJoinColumn = $this->get_join_column($join_column);
        $rResult = $this->get_display_data($table, $columns, $sWhere, $sOrder, $sLimit);
        $rResultFilterTotal = $this->get_data_set_length();
        $aResultFilterTotal = $rResultFilterTotal->result_array();
        $iFilteredTotal = $aResultFilterTotal[0]["FOUND_ROWS()"];
        $rResultTotal = $this->get_total_data_set_length($table, $index, $sWhere);
        $aResultTotal = $rResultTotal->result_array();
        $iTotal = $aResultTotal[0]["COUNT($index)"];
        return $this->produce_output($columns, $iTotal, $iFilteredTotal, $rResult);
    }

    /**
     * Creates some function that can make JOIN query. Currently work with
     * DataMapper table scheme.
     *
     * @return string
     */
    protected function get_join_column($join_column) {
        $join_field = "";
        $i = 0;
        $count = intval(count($join_column) / 3);
        for ($i = 1; $i <= $count; $i++) {
            echo $join_column['table_' . $i];
            echo "<br />";
            $count_columns = count($join_column['columns_table_' . $i]);
            for ($j = 0; $j < $count_columns; $j++) {
                echo $join_column['columns_table_' . $i][$j];
                echo "<br />";
            }

            echo $join_column['index_table_' . $i];
            echo "<br />";
        }

        return $join_field;
    }

    protected function get_join_permohonan() {
        $join_field = "";
        if($this->ci->input->post("sSearch") === "") {
            $join_field = "WHERE ";
        }
        
        return $join_field;
    }

    /**
     * Creates some column in case using select, edit, delete function
     *
     * @return string
     */
    protected function get_addition_button($type, $link, $id) {
        $button = "";
        switch ($type) {
            case 'delete':
                break;
            case 'edit':
                break;
            case 'select':
                break;
        }

        return $button;
    }

    /**
     * Creates a pagination query segment
     *
     * @return string
     */
    protected function get_paging() {
        $sLimit = "";

        if ($this->ci->input->post("iDisplayStart") && $this->ci->input->post("iDisplayLength") != "-1")
            $sLimit = "LIMIT " . $this->ci->input->post("iDisplayStart") . ", " . $this->ci->input->post("iDisplayLength");
        else {
            $iDisplayLength = $this->ci->input->post("iDisplayLength");

            if (empty($iDisplayLength))
                $sLimit = "LIMIT " . "0,10";
            else
                $sLimit = "LIMIT 0," . $iDisplayLength;
        }

        return $sLimit;
    }

    /**
     * Creates a sorting query segment
     *
     * @param string $columns
     * @return string
     */
    protected function get_ordering($columns) {
        $sOrder = "";

        if ($this->ci->input->post("iSortCol_0") != null) {
            $sOrder = "ORDER BY ";

            for ($i = 0; $i < intval($this->ci->input->post("iSortingCols")); $i++)
                $sOrder .= $columns[intval($this->ci->input->post("iSortCol_" . $i))] . " " . $this->ci->input->post("sSortDir_" . $i) . ", ";

            $sOrder = substr_replace($sOrder, "", -2);
        }

        return $sOrder;
    }

    /**
     * Creates a filtering query segment
     *
     * @param string $columns
     * @return string
     */
    protected function get_filtering($columns) {
        $sWhere = "";

        if ($this->ci->input->post("sSearch") != "") {
            $sWhere = "WHERE ";

            for ($i = 0; $i < count($columns); $i++)
                $sWhere .= $columns[$i] . " LIKE '%" . $this->ci->input->post("sSearch") . "%' OR ";

            $sWhere = substr_replace($sWhere, "", -3);
        }

        return $sWhere;
    }

    /**
     * Combines all created query segments to build the main query
     *
     * @param string $table
     * @param string $columns
     * @param string $sWhere
     * @param string $sOrder
     * @param string $sLimit
     * @return object
     */
    protected function get_display_data($table, $columns, $sWhere, $sOrder, $sLimit) {
        return $this->ci->db->query
                ("
        SELECT SQL_CALC_FOUND_ROWS " . implode(", ", $columns) . "
        FROM $table
        $sWhere
        $sOrder
        $sLimit
      ");
    }

    /**
     * Gets all matched rows
     *
     * @return object
     */
    protected function get_data_set_length() {
        return $this->ci->db->query("SELECT FOUND_ROWS()");
    }

    /**
     * Gets the count of all rows found
     *
     * @param string $table
     * @param string $index
     * @param string $sWhere
     * @return string
     */
    protected function get_total_data_set_length($table, $index, $sWhere) {
        return $this->ci->db->query
                ("
        SELECT COUNT(" . $index . ")
        FROM $table
        $sWhere
      ");
    }

    /**
     * Builds a JSON encoded string data
     *
     * @param string $columns
     * @param string $iTotal
     * @param string $iFilteredTotal
     * @param string $rResult
     * @return string
     */
    protected function produce_output($columns, $iTotal, $iFilteredTotal, $rResult) {
        $aaData = array();

        $i = $this->ci->input->post("iDisplayStart");
        foreach ($rResult->result_array() as $row_key => $row_val) {
            /*
             * Add a column that auto increment for creating number column.
             */
            $i++;
            $aaData[$row_key][] = $i;

            foreach ($row_val as $col_key => $col_val) {
                if ($row_val[$col_key] == "version")
                    $aaData[$row_key][$col_key] = ($aaData[$row_key][$col_key] == 0) ? "-" : $col_val;
                else {
                    /*
                      you can manipulate your result data here
                      like wrapping your result in additional html tags for example:

                      $aaData[$row_key][] = '<span class="additionalTag">' . $col_val . '</span>';

                      you can also add further logic based on column specific values
                      but by default, I'm leaving it as queried
                     */
                    $aaData[$row_key][] = $col_val;
                }
            }

            /*
              add additional columns here
              like adding a Delete Row control for example:

              $aaData[$row_key][] = '<a href="#">Delete Button</a>';
             */
        }

        $sOutput = array
            (
            "sEcho" => intval($this->ci->input->post("sEcho")),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => $aaData
        );

        return json_encode($sOutput);
    }

}
?>
