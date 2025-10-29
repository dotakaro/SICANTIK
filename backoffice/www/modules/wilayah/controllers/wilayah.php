<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Jenis Kegiatan class
 *
 * @author Muhammad Rizky
 * Created : 08 Okt 2010
 *
 */
class Wilayah extends WRC_AdminCont
{

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

    public function __construct()
    {
        parent::__construct();
        $this->propinsi = new propinsi();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '5') {
                $enabled = TRUE;
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index()
    {
//        $data['list'] = $this->propinsi->order_by('id', 'ASC')->get();
//        $this->load->vars($data);

        $js = "
                function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                
                /*$(document).ready(function() {
                        oTable = $('#kegiatan').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );*/
                ";

        $this->template->set_metadata_javascript($js);

        $this->session_info['page_name'] = "Data Provinsi";
//        $this->template->build('provinsi_list', $this->session_info);
        $this->template->build('provinsi_list_ajax', $this->session_info);
    }

    public function create()
    {
        $data['nama'] = "";
        $data['kode_daerah'] = "";
        $data['keterangan'] = "";
        $data['save_method'] = "save";
        $data['id'] = "";
        $js_date = "
                $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();
                } );
            ";
        $this->template->set_metadata_javascript($js_date);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Provinsi";
        $this->template->build('provinsi_edit', $this->session_info);
    }

    public function edit($id_edit = NULL)
    {
        $this->propinsi->get_by_id($id_edit);
        $js_date = "
                $(document).ready(function() {
                $('#form').validate();
                    $(\"#tabs\").tabs();
                } );
            ";
        $this->template->set_metadata_javascript($js_date);

        $data['nama'] = $this->propinsi->n_propinsi;
        $data['kode_daerah'] = $this->propinsi->kode_daerah;
        $data['save_method'] = "update";
        $data['id'] = $this->propinsi->id;

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Propinsi";
        $this->template->build('provinsi_edit', $this->session_info);
    }

    public function save()
    {
        $this->propinsi->n_propinsi = $this->input->post('nama');
        $this->propinsi->kode_daerah = $this->input->post('kode_daerah');
//      $this->kegiatan->keterangan = $this->input->post('keterangan');

        if (!$this->propinsi->save()) {
            echo '<p>' . $this->propinsi->error->string . '</p>';
        } else {
            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $p = $this->db->query("call log ('Setting Wilayah','Insert Propinsi " . $this->input->post('nama') . "','" . $tgl . "','" . $u_ser . "')");

            redirect('wilayah');
        }

    }

    public function update()
    {
        /*$update = $this->propinsi
                ->where('id', $this->input->post('id'))
                ->update(array(
                    'n_propinsi' => $this->input->post('nama'),
                    'kode_daerah' => $this->input->post('kode_daerah'),
                  ));*/
        $propinsi = $this->propinsi->get_by_id($this->input->post('id'));
        $propinsi->n_propinsi = $this->input->post('nama');
        $propinsi->kode_daerah = $this->input->post('kode_daerah');
//        if($update) {
        if ($this->propinsi->save()) {
            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $p = $this->db->query("call log ('Setting Wilayah','Update Propinsi " . $this->input->post('nama') . "','" . $tgl . "','" . $u_ser . "')");

            redirect('wilayah');
        }
    }

    public function delete($id = NULL)
    {

        $this->propinsi->where('id', $id)->get();
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting User','Delete pengguna " . $this->propinsi->n_propinsi . "','" . $tgl . "','" . $u_ser . "')");
        $this->propinsi->delete();

        //added 12-04-2013
        //by mucktar

        $kabupaten = new trkabupaten_trpropinsi();
        $kecamatan = new trkabupaten_trkecamatan();
        $kelurahan = new trkecamatan_trkelurahan();

        //data kabupaten dengan current propinsi
        $data_kabupaten = $kabupaten->where('trpropinsi_id', $id)->get();
        $kabupaten->delete();

        //loop setiap kabupaten untuk mendapat data kecamatan
        foreach ($data_kabupaten as $s_kabupaten) {

            $data_kecamatan = $kecamatan->where('trkabupaten_id', $s_kabupaten->trkabupaten_id)->get();
            $kecamatan->delete();

            foreach ($data_kecamatan as $s_kecamatan) {

                $kelurahan->where('trkecamatan_id', $s_kecamatan->trkecamatan_id)->get();
                $kelurahan->delete();
            }
        }
        redirect('wilayah');
        //end added
    }

    public function getWilayahDatatables()
    {
        $obj = new trpropinsi();
        $obj->start_cache();
        $columns = array('id', 'n_propinsi', 'kode_daerah');
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
                echo $this->buildAction($obj->order_by('id','n_propinsi','kode_daerah')->get());
            }
        }
    }

    private function buildAction($obj) {

        $aaData = array();

        $i = $this->iDisplayStart;

        foreach ($obj as $list) {
            $i++;

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
            $action .= anchor(site_url('wilayah/edit') . '/' . $list->id, img($img_edit)) . "&nbsp;";

            $relasi =
                (
                    $list->trkabupaten->trkecamatan->trkelurahan->id ||
                    $list->trkabupaten->trkecamatan->trkelurahan->id ||
                    $list->trkabupaten->trkecamatan->trkelurahan->id ||
                    $list->trkabupaten->trkecamatan->trkelurahan->id
                ) ? true : false;

            if (!$relasi)
                $action .= anchor(site_url('wilayah/delete') . '/' . $list->id, img($img_delete)) . "&nbsp;";

            $aaData[] = array(
                $i,
                $list->n_propinsi,
                $list->kode_daerah,
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

}

// This is the end of holiday class
