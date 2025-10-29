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

class Kelurahan extends WRC_AdminCont {

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
        $this->propinsi = new trpropinsi();
        $this->kabupaten = new trkabupaten();
        $this->kecamatan = new trkecamatan();
        $this->kelurahan = new trkelurahan();

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
//        $data['list_kelurahan'] = $this->kelurahan->get();
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
        $this->session_info['page_name'] = "Data Kelurahan";
//        $this->template->build('kelurahan_list', $this->session_info);
        $this->template->build('kelurahan_list_ajax', $this->session_info);
    }

    public function create() {
        $data = $this->_funcwilayah();

        $data['propinsi']="0";
        $data['kabupaten']="0";
        $data['kecamatan']="0";

        $data['nama']  = "";
        $data['kode_daerah']  = "";
        $data['keterangan']  = "";
        $data['save_method'] = "save";
        $data['id'] = "";
       $js = "
                $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();

                } );

                $(document).ready(function() {
                       
                         $('#propinsi_pemohon_id').change(function(){
                                        $.post('".base_url()."pelayanan/pendaftaran/kabupaten_pemohon', { propinsi_id: $('#propinsi_pemohon_id').val() },
                                                       function(data) {
                                                         $('#show_kabupaten_pemohon').html(data);
                                                         $('#show_kecamatan_pemohon').html('Data Tidak tersedia');
                                                         $('#show_kelurahan_pemohon').html('Data Tidak tersedia');
                                                       });
                                         });

                });

                function finishAjax(id, response){
                  $('#'+id).html(unescape(response));
                  $('#'+id).fadeIn();
                }
           ";

        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Kelurahan";
        $this->template->build('kelurahan_edit', $this->session_info);
    }

    public function kabupaten_pemohon() {
        $data['kabupaten_id'] = 'kabupaten_pemohon';
        $data['kecamatan_id'] = 'kecamatan_pemohon';

        $this->load->vars($data);
        $this->load->view('kabupaten_load', $data);
    }

    public function kecamatan_pemohon() {
        $data['kecamatan_id'] = 'kecamatan_pemohon';
        $data['kelurahan_id'] = 'kelurahan_pemohon';

        $this->load->vars($data);
        $this->load->view('kecamatan_load', $data);
    }

    function _funcwilayah() {


        $data['list_propinsi'] = $this->propinsi->order_by('n_propinsi', 'ASC')->get();
        $data['list_kabupaten'] = $this->kabupaten->order_by('n_kabupaten', 'ASC')->get();
        $data['list_kecamatan'] = $this->kecamatan->order_by('n_kecamatan', 'ASC')->get();
        return $data;
    }

    public function edit($id_edit = NULL) {
        $data = $this->_funcwilayah();
       $kelurahan =  $this->kelurahan->get_by_id($id_edit);
        $js = "
                $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();

                } );

                $(document).ready(function() {
                       $('#propinsi_pemohon_id').change(function(){
                                        $.post('".base_url()."pelayanan/pendaftaran/kabupaten_pemohon', { propinsi_id: $('#propinsi_pemohon_id').val() },
                                                       function(data) {
                                                         $('#show_kabupaten_pemohon').html(data);
                                                         $('#show_kecamatan_pemohon').html('Data Tidak tersedia');
                                                         $('#show_kelurahan_pemohon').html('Data Tidak tersedia');
                                                       });
                                         });
                       

                });

                function finishAjax(id, response){
                  $('#'+id).html(unescape(response));
                  $('#'+id).fadeIn();
                }
           ";
        
        $this->template->set_metadata_javascript($js);

        $data['kecamatan'] = $kelurahan->trkecamatan->id;
        $data['nama'] = $kelurahan->n_kelurahan;
        $data['kode_daerah'] = $kelurahan->kode_daerah;
        $data['kabupaten']= $kelurahan->trkecamatan->trkabupaten->id;
        $data['propinsi']= $kelurahan->trkecamatan->trkabupaten->trpropinsi->id;
        $data['save_method'] = "update";
        $data['id'] = $kelurahan->id;

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Kelurahan";
        $this->template->build('kelurahan_edit', $this->session_info);
    }

    public function save() {
//        $this->propinsi->n_propinsi = $this->input->post('nama');
//      $this->kegiatan->keterangan = $this->input->post('keterangan');

//        if(! $this->propinsi->save()) {
//            echo '<p>' . $this->propinsi->error->string . '</p>';
//        } else {
//            redirect('wilayah');
//        }
        
        $kecamatan_i = $this->input->post('kecamatan_pemohon');
        $kelurahan_i = $this->input->post('nama');
        $kode_daerah = $this->input->post('kode_daerah');
        $kelurahan = new trkelurahan();
        $kelurahan->n_kelurahan = $kelurahan_i;
        $kelurahan->kode_daerah = $kode_daerah;
        $kecamatan = new trkecamatan();
        $kecamatan->id = $kecamatan_i;
        $kelurahan->save($kecamatan);
//
//        $sql1 ="insert into trkelurahan (n_kelurahan) values ('".$kelurahan."')";
//        $query1 = $this->db->query($sql1);
//
//        $coba = $this->kelurahan->where('n_kelurahan',$kelurahan)->get();
//        $id = $coba->id;
//        $sql2 ="insert into trkecamatan_trkelurahan (trkecamatan_id,trkelurahan_id)
//            values ('".$kecamatan."','".$id."')";
//        $query2 = $this->db->query($sql2);
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Wilayah','Insert kelurahan ".$kelurahan_i."','".$tgl."','".$u_ser."')");

        redirect('wilayah/kelurahan');

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
        $kecamatan = $this->input->post('kecamatan_pemohon');
        $kelurahan = $this->input->post('nama');
        $kode_daerah = $this->input->post('kode_daerah');
        $id = $this->input->post('id');
        $sql ="update trkelurahan set n_kelurahan='".$kelurahan."', kode_daerah='{$kode_daerah}' where id='".$id."'";
        $query = $this->db->query($sql);


        $sql2="update trkecamatan_trkelurahan set trkecamatan_id = '".$kecamatan."'
              where trkelurahan_id = '".$id."'";
        $query = $this->db->query($sql2);

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Wilayah','Update kelurahan ".$kelurahan."','".$tgl."','".$u_ser."')");
         redirect('wilayah/kelurahan');
    }

     public function delete($id = NULL) {
        $this->kelurahan->where('id',$id)->get();
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Wilayah','Delete kelurahan ".$this->kelurahan->n_kelurahan."','".$tgl."','".$u_ser."')");

         $sql = "delete from trkelurahan where id='".$id."'";
        $query = $this->db->query($sql);

        $sql2 = "delete from trkecamatan_trkelurahan where trkelurahan_id='".$id."'";
        $query2 = $this->db->query($sql2);

        if ($query)
        {
            redirect('wilayah/kelurahan');
        }

    }


    public function sql()
    {
        $query = "select a.id,a.n_kelurahan,b.n_kecamatan,d.n_kabupaten,f.n_propinsi from trkelurahan as a
                  inner join trkecamatan_trkelurahan as c on c.trkelurahan_id=a.id
                  inner join trkecamatan as b on c.trkecamatan_id=b.id
                  inner join trkabupaten_trkecamatan as e on e.trkecamatan_id = b.id
                  inner join trkabupaten as d on e.trkabupaten_id=d.id
                  inner join trkabupaten_trpropinsi as g on g.trkabupaten_id=d.id
                  inner join trpropinsi as f on g.trpropinsi_id = f.id";
        $hasil = $this->db->query($query);
        return $hasil->result();

    }

    public function getKelurahanDatatables()
    {
        $obj = new trkelurahan();
        $obj->start_cache();
        $columns = array('id', 'n_kelurahan', 'kode_daerah');
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
                echo $this->buildAction($obj->order_by('id','n_kelurahan','kode_daerah')->get());
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
            $action .= anchor(site_url('wilayah/kelurahan/edit') . '/' . $list->id, img($img_edit)) . "&nbsp;";

            $relasi =
                (
                    $list->tmpemohon->id ||
                    $list->tmperusahaan->id ||
                    $list->tmpemohon_sementara->id ||
                    $list->tmperusahaan_sementara->id
                ) ? true : false;

            if (!$relasi)
                $action .= anchor(site_url('wilayah/kelurahan/delete') . '/' . $list->id, img($img_delete)) . "&nbsp;";

            $aaData[] = array(
                $i,
                $list->n_kelurahan,
                $list->trkecamatan->n_kecamatan,
                $list->trkecamatan->trkabupaten->n_kabupaten,
                $list->trkecamatan->trkabupaten->trpropinsi->n_propinsi,
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
