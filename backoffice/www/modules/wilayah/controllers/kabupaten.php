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

class Kabupaten extends WRC_AdminCont {

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
        $this->kabupaten = new trkabupaten();
        $this->propinsi = new trpropinsi();
       
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

    public function index() {

//        $data['list_kabupaten']  = $this->sql();
//      $data['list_kabupaten'] = $this->kabupaten->order_by('id', 'ASC')->get();
        

//        $this->load->vars($data);

        $js =  "
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
        
        $this->session_info['page_name'] = "Data Kabupaten";
//        $this->template->build('kabupaten_list', $this->session_info);
        $this->template->build('kabupaten_list_ajax', $this->session_info);
    }

    public function create() {

        $data['list_propinsi'] = $this->propinsi->order_by('n_propinsi', 'ASC')->get();
        $data['nama']  = "";
        $data['kode_daerah']  = "";
		$data['ibukota']  = "";
        $data['keterangan']  = "";
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
        $this->session_info['page_name'] = "Tambah Kabupaten";
        $this->template->build('kabupaten_edit', $this->session_info);
    }

    public function edit($id_edit = NULL) {
        $kabupaten = $this->kabupaten->get_by_id($id_edit);
        $data['list_propinsi'] = $this->propinsi->order_by('n_propinsi', 'ASC')->get();
        $js_date = "
                $(document).ready(function() {
                $('#form').validate();
                    $(\"#tabs\").tabs();
                } );
            ";
        $this->template->set_metadata_javascript($js_date);
        $propinsi = new trpropinsi();
        $propinsi->where_related('trkabupaten','id',$id_edit)->get();
        $data['nama'] = $kabupaten->n_kabupaten;
        $data['kode_daerah'] = $kabupaten->kode_daerah;
		$data['ibukota'] = $kabupaten->ibukota;
        $data['propinsi'] = $kabupaten->trpropinsi->id;
        $data['save_method'] = "update";
        $data['id'] = $kabupaten->id;
        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Kabupaten";
        $this->template->build('kabupaten_edit', $this->session_info);
    }

    public function save() {
//        $this->propinsi->id = $this->input->post('nama');
//      $this->kegiatan->keterangan = $this->input->post('keterangan');
//        if(! $this->propinsi->save()) {
//            echo '<p>' . $this->propinsi->error->string . '</p>';
//        } else {
//            redirect('wilayah');
//        }
        $propinsi = $this->input->post('propinsi_pemohon');
        $kabupaten = $this->input->post('nama_kabupaten');
		$f_ibukota = $this->input->post('nama_ibukota');
		$kode_daerah = $this->input->post('kode_daerah');
        $sql2 = "insert into trkabupaten(n_kabupaten,ibukota, kode_daerah)
            values ('".$kabupaten."','".$f_ibukota."','$kode_daerah');";

        $query2 = $this->db->query($sql2);

        $coba = $this->kabupaten->where('n_kabupaten',$kabupaten)->get();
        $id = $coba->id;

        $sql = "insert into trkabupaten_trpropinsi(trkabupaten_id,trpropinsi_id)
                values ('".$id."','".$propinsi."');";
        $query = $this->db->query($sql);

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Wilayah','Insert kabupaten ".$kabupaten."','".$tgl."','".$u_ser."')");

        redirect('wilayah/kabupaten');
    }

    public function update() {
//        $update = $this->propinsi
//                ->where('id', $this->input->post('id'))
//                ->update(array('n_propinsi' => $this->input->post('nama'),
//
//                  ));
//        if($update) {
//            redirect('wilayah');
//        }
        $propinsi = $this->input->post('propinsi_pemohon');
        $kabupaten = $this->input->post('nama_kabupaten');
		$f_ibukota = $this->input->post('nama_ibukota');
        $kode_daerah = $this->input->post('kode_daerah');
        $id = $this->input->post('id');
        $sql ="update trkabupaten set n_kabupaten='".$kabupaten."', ibukota='".$f_ibukota."', kode_daerah='$kode_daerah' where id='".$id."'";
        $query = $this->db->query($sql);

       
        $sql2="update trkabupaten_trpropinsi set trpropinsi_id = '".$propinsi."'
              where trkabupaten_id = '".$id."'";
        $query = $this->db->query($sql2);

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Wilayah','Update kabupaten ".$kabupaten."','".$tgl."','".$u_ser."')");


         redirect('wilayah/kabupaten');
    }

    public function delete($id = NULL) {
        $this->kabupaten->where('id',$id)->get();
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Wilayah','Delete kabupaten ".$this->kabupaten->n_kabupaten."','".$tgl."','".$u_ser."')");

        $sql = "delete from trkabupaten where id='".$id."'";
        $query = $this->db->query($sql);

        $sql2 = "delete from trkabupaten_trpropinsi where trkabupaten_id='".$id."'";
        $query2 = $this->db->query($sql2);

        if($query2)
        {
        
        redirect('wilayah/kabupaten');
        }

	

    }

    public function sql()
    {
        $query = "select c.id, a.n_kabupaten, b.n_propinsi, a.ibukota, a.kode_daerah from trkabupaten as a
                  inner join trkabupaten_trpropinsi as c on c.trkabupaten_id = a.id
                  inner join trpropinsi as b on c.trpropinsi_id = b.id group by c.id ";
        $hasil = $this->db->query($query);
       return $hasil->result();
    }

    public function getKabupatenDatatables()
    {
        $obj = new trkabupaten();
        $obj->start_cache();
        $columns = array('id', 'n_kabupaten', 'kode_daerah');
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
                echo $this->buildAction($obj->order_by('id','n_kabupaten', 'ibukota', 'kode_daerah')->get());
            }
        }
    }

    private function buildAction($obj)
    {

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
            $action .= anchor(site_url('wilayah/kabupaten/edit') . '/' . $list->id, img($img_edit)) . "&nbsp;";


            $relasi =
                (
                    $list->trkecamatan->trkelurahan->tmpemohon->id ||
                    $list->trkecamatan->trkelurahan->tmperusahaan->id ||
                    $list->trkecamatan->trkelurahan->tmpemohon_sementara->id ||
                    $list->trkecamatan->trkelurahan->tmperusahaan_sementara->id
                ) ? true : false;

            if (!$relasi)
                $action .= anchor(site_url('wilayah/kabupaten/delete') . '/' . $list->id, img($img_delete)) . "&nbsp;";

                $aaData[] = array(
                    $i,
                    $list->n_kabupaten,
                    $list->trpropinsi->n_propinsi,
                    $list->ibukota,
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
