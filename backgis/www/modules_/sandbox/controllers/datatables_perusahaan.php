<?php

/**
 * Description of datatables
 *
 * @author alfaridi
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Datatables_perusahaan extends WRC_AdminCont {
    /*
     * The Object
     */

    var $obj;

    /*
     * Variable for generating JSON.
     */
    var $iTotalRecords;
    var $iTotalDisplayRecords;

    /*
     * Variable that taken form input.
     */
    var $iDisplayStart;
    var $iDisplayLength;
    var $iSortingCols;
    var $sSearch;
    var $sEcho;

    public function __construct() {
        parent::__construct();
    }

    public function getDataTables() {
        $obj = new tmperusahaan();
        $obj->start_cache();
        $columns = array('n_perusahaan', 'npwp', 'a_perusahaan');
        $this->iTotalRecords = $obj->count();
        $this->sEcho = $this->input->post('sEcho');
        for ($i = 0; $i < 2; $i++) {
            /**
             * Filtering
             */
            if ($this->input->post('sSearch')) {
                foreach ($columns as $position => $column) {
                    if ($position == 0) {
                        $obj->like($column, $this->input->post('sSearch'));
                    } else {
                        $obj->or_like($column, $this->input->post('sSearch'));
                    }
                }
            }

            /**
             * Ordering
             */
//            if ($this->input->post("iSortCol_0") != null && $this->input->post("iSortCol_0") != "") {
//                for ($i = 0; $i < intval($this->input->post("iSortingCols")); $i++) {
//                    $obj->order_by($columns[intval($this->input->post("iSortCol_" . $i))], $this->input->post("sSortDir_" . $i));
//                }
//            }

            if ($i === 0) {
                $this->iTotalDisplayRecords = $obj->count();
            } else if ($i === 1) {
                if ($this->input->post("iDisplayStart") && $this->input->post("iDisplayLength") != "-1") {
                    $this->iDisplayStart = $this->input->post("iDisplayStart");
                    $this->iDisplayLength = $this->input->post("iDisplayLength");

                    $obj->limit($this->iDisplayLength, $this->iDisplayStart);
                } else {
                    $this->iDisplayLength = $this->input->post("iDisplayLength");

                    if (empty($this->iDisplayLength)) {
                        $this->iDisplayLength = 10;
                        $obj->limit($this->iDisplayLength);
                    }
                    else
                        $obj->limit($this->iDisplayLength);
                }
                $obj->stop_cache();
                echo $this->test($obj->order_by('id','desc')->get());
            }
        }
    }

    private function test($obj) {
       
        $aaData = array();

        $i = $this->iDisplayStart;

        foreach ($obj as $list) {
            $i++;
            $permohonan = new tmpermohonan();
            $action = NULL;

            $img_edit = array(
                'src' => base_url() . 'assets/images/icon/property.png',
                'alt' => 'Edit',
                'title' => 'Edit',
                'border' => '0',
            );
            $confirm_text = 'Apakah Anda yakin akan menghapusnya?';
            $img_delete = array(
                'src' => base_url() . 'assets/images/icon/minus.png',
                'alt' => 'Delete',
                'title' => 'Delete',
                'border' => '0',
                'onClick' => 'return confirm_link(\'' . $confirm_text . '\')',
            );
            $action .= anchor(site_url('perusahaan/edit') . '/' . $list->id, img($img_edit)) . "&nbsp;";
            if(!$list->$permohonan->id)
            {
                //$action .= $list->$permohonan->pendaftaran_id;
                $action .= anchor(site_url('perusahaan/delete') . '/' . $list->id, img($img_delete)) . "&nbsp;";
            }
            
            $aaData[] = array(
                $i,
                $list->n_perusahaan,
                $list->npwp,
                $list->a_perusahaan,
                $action
            );
        }

        $sOutput = array
            (
            "sEcho" => intval($this->sEcho),
            "iTotalRecords" => $this->iTotalRecords,
            "iTotalDisplayRecords" => $this->iTotalDisplayRecords,
            "aaData" => $aaData
        );

        return json_encode($sOutput);
    }

    public function index() {
        $string = NULL;

        $count = $obj->count();

        $string .= '{';

        $string .= '"sEcho":0,';
        $string .= '"iTotalRecords":"' . $this->iTotalRecords . '",';
        $string .= '"iTotalDisplayRecords":"' . $this->iTotalDisplayRecords . '",';
        $string .= '"aaData":[';

        $i = 0;
        foreach ($obj->get() as $list) {
            $string .= '[';
            $string .= '"' . $list->engine . '",';
            $string .= '"' . $list->browser . '",';
            $string .= '"' . $list->platform . '",';
            $string .= '"' . $list->version . '",';
            $string .= '"' . $list->grade . '"';
            $i++;
            if ($count === $i) {
                $string .= ']';
            } else {
                $string .= '],';
            }
        }

        $string .= ']}';
        echo $string;
    }

}
