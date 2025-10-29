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
class Kecamatan extends WRC_AdminCont {

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

	/**
	* @ModifiedAuthor Indra
	* Modified Date: 2013-04-26
	* @ModifiedComment Penambahan inisialisasi object kelurahan
	*/
    public function __construct() {
        parent::__construct();
        $this->kecamatan = new trkecamatan();
        $this->propinsi = new trpropinsi();
        $this->kabupaten = new trkabupaten();
		$this->kelurahan = new trkelurahan();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if ($list_auth->id_role === '5') {
                $enabled = TRUE;
            }
        }
		
        if (!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
//        $data['list_kecamatan'] = $this->sql();
//        $data['list_kecamatan'] = $this->kecamatan->get();
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

        $this->session_info['page_name'] = "Data Kecamatan";
//        $this->template->build('kecamatan_list', $this->session_info);
        $this->template->build('kecamatan_list_ajax', $this->session_info);
    }

    public function create() {

        $data['list_propinsi'] = $this->propinsi->order_by('n_propinsi', 'ASC')->get();
        $data['list_kabupaten'] = $this->kabupaten->order_by('n_kabupaten', 'ASC')->get();

        $data['nama'] = "";
        $data['kode_daerah'] = "";
        $data['keterangan'] = "";
        $data['save_method'] = "save";
        $data['id'] = "";
        
        //edited 12-14-2013
        //by mucktar
        $js = "
                $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();

                } );
     
                $(document).ready(function() {
                    $('#propinsi_pemohon_id').change(function(){

                        $.ajax({
                            url : '".site_url('wilayah/kecamatan/kabupaten_pemohon')."',
                            type: 'POST',
                            data:{
                                propinsi_id: $('#propinsi_pemohon_id').val()
                            },
                            success:function(data){
                                $('#show_kabupaten').html(data);
                            }
                        });".
                        /*$.post('" . base_url() . "wilayah/kecamatan/kabupaten_pemohon', {
                            propinsi_id: $('#propinsi_pemohon_id').val()
                        },function(data) {
                            $('#show_kabupaten').html(data);
                        },
                        function(response){
                            setTimeout(\"finishAjax('show_kabupaten_pemohon', '\"+escape(response)+\"')\", 400);
                        });
                            return false;
                        });*/
                        "
                    });

                    function finishAjax(id, response){
                      $('#'+id).html(unescape(response));
                      $('#'+id).fadeIn();
                    }

                });
           ";

        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Kecamatan";
        $this->template->build('kecamatan_edit', $this->session_info);
    }

    public function kabupaten_pemohon() {
        $data['kabupaten_id'] = 'kabupaten_pemohon';
        $data['kecamatan_id'] = 'kecamatan_pemohon';

        $this->load->vars($data);
        $this->load->view('kabupaten_load', $data);
    }

    public function edit($id_edit = NULL) {

        $data['list_propinsi'] = $this->propinsi->order_by('n_propinsi', 'ASC')->get();
        $data['list_kabupaten'] = $this->kabupaten->order_by('n_kabupaten', 'ASC')->get();

        $kecamatan = $this->kecamatan->get_by_id($id_edit);

        $kabupaten = $kecamatan->trkabupaten->id;
        $propinsi = $kecamatan->trkabupaten->trpropinsi->get();
        $js = "
                $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();
      
                } );


                $(document).ready(function() {
                    $('#propinsi_pemohon_id').change(function(){
                        $.post('" . base_url() . "wilayah/kecamatan/kabupaten_pemohon', {
                            propinsi_id: $('#propinsi_pemohon_id').val()
                        },function(data) {
                            $('#show_kabupaten').html(data);
                        },
                        function(response){
                            setTimeout(\"finishAjax('show_kabupaten_pemohon', '\"+escape(response)+\"')\", 400);
                        });
                            return false;
                        });      
                });

                function finishAjax(id, response){
                  $('#'+id).html(unescape(response));
                  $('#'+id).fadeIn();
                }

             
 
            ";
        $this->template->set_metadata_javascript($js);

        $data['nama'] = $kecamatan->n_kecamatan;
        $data['kode_daerah'] = $kecamatan->kode_daerah;
        $data['save_method'] = "update";
        $data['id'] = $kecamatan->id;
        $data['kabupaten'] = $kabupaten;
		
        $data['propinsi'] = $propinsi->id;

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Kecamatan";
        $this->template->build('kecamatan_edit', $this->session_info);
    }

    public function save() {

//        $this->propinsi->n_propinsi = $this->input->post('nama');
//      $this->kegiatan->keterangan = $this->input->post('keterangan');
//        if(! $this->propinsi->save()) {
//            echo '<p>' . $this->propinsi->error->string . '</p>';
//        } else {
//            redirect('wilayah');
//        }


        $propinsi = $this->input->post('propinsi');
        $kabupaten = $this->input->post('kabupaten');
        $kecamatan = $this->input->post('nama');
        $kode_daerah = $this->input->post('kode_daerah');
        // editedd 12-04-2013
        //by mucktar
        if (!empty($propinsi) && !empty($kabupaten)) {
            $sql1 = "insert into trkecamatan (n_kecamatan, kode_daerah) values ('" . $kecamatan . "', '{$kode_daerah}')";
            $query1 = $this->db->query($sql1);

            $coba = $this->kecamatan->where('n_kecamatan', $kecamatan)->get();
            $id = $coba->id;
            $sql2 = "insert into trkabupaten_trkecamatan (trkabupaten_id,trkecamatan_id)
                values ('" . $kabupaten . "','" . $id . "')";
            $query2 = $this->db->query($sql2);

            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $p = $this->db->query("call log ('Setting Wilayah','Insert Kecamatan " . $kecamatan . "','" . $tgl . "','" . $u_ser . "')");
        }

        redirect('wilayah/kecamatan');
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
        $propinsi = $this->input->post('propinsi');
        $kabupaten = $this->input->post('kabupaten');
        $kecamatan = $this->input->post('nama');
        $kode_daerah = $this->input->post('kode_daerah');
        $id = $this->input->post('id');

        $sql = "update trkecamatan set n_kecamatan='" . $kecamatan . "', kode_daerah = '{$kode_daerah}' where id='" . $id . "'";
        $query = $this->db->query($sql);

        $sql2 = "update trkabupaten_trkecamatan set trkabupaten_id = '" . $kabupaten . "'
            where trkecamatan_id = '" . $id . "'";
        $query = $this->db->query($sql2);

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Wilayah','Update Kecamatan " . $kecamatan . "','" . $tgl . "','" . $u_ser . "')");
        redirect('wilayah/kecamatan');
    }
	/*
	* @ModifiedAuthor Indra
	* modified 2013-04-26
	* @ModifiedComment Penambahan fungsi penghapusan seluruh kelurahan pada kecamatan yang dihapus
	*/ 
    public function delete($id = NULL) {
        
        $this->kecamatan->where('id', $id)->get();
		
		$tgl = date("Y-m-d H:i:s");
        $user = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Wilayah','Delete kecamatan " . $this->kecamatan->n_kecamatan . "','" . $tgl . "','" . $user . "')");
        
		$sql = "delete from trkecamatan where id='" . $id . "'";
        $query = $this->db->query($sql);

        $sql2 = "delete from trkabupaten_trkecamatan where trkecamatan_id='" . $id . "'";
        $query2 = $this->db->query($sql2);
		
		/*Modified By Indra 2013-04-26*/
		$this->kelurahan->where_related_trkecamatan('id', $id)->get();
		$this->kelurahan->delete_all();//Menghapus semua kelurahan pada kecamatan yang didelete;
		/***********/
        
		if ($query) {
            redirect('wilayah/kecamatan');
        }
    }

    public function sql() {
        $query = "select a.id,a.n_kecamatan,b.n_kabupaten, d.n_propinsi from trkecamatan as a
                  inner join trkabupaten_trkecamatan as c on c.trkecamatan_id = a.id
                  inner join trkabupaten as b on c.trkabupaten_id=b.id
                  inner join trkabupaten_trpropinsi as e on e.trkabupaten_id = b.id
                  inner join trpropinsi as d on e.trpropinsi_id=d.id";
        $hasil = $this->db->query($query);
        return $hasil->result();
    }

    public function getKecamatanDatatables()
    {
        $obj = new trkecamatan();
        $obj->start_cache();
        $columns = array('id', 'n_kecamatan', 'kode_daerah');
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
                echo $this->buildAction($obj->order_by('id','n_kecamatan', 'kode_daerah')->get());
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
            $action .= anchor(site_url('wilayah/kecamatan/edit') . '/' . $list->id, img($img_edit)) . "&nbsp;";

            $relasi =
                (
                    $list->trkelurahan->tmpemohon->id ||
                    $list->trkelurahan->tmperusahaan->id ||
                    $list->trkelurahan->tmpemohon_sementara->id ||
                    $list->trkelurahan->tmperusahaan_sementara->id
                ) ? true : false;

            if (!$relasi)
                $action .= anchor(site_url('wilayah/kecamatan/delete') . '/' . $list->id, img($img_delete)) . "&nbsp;";

            $aaData[] = array(
                $i,
                $list->n_kecamatan,
                $list->trkabupaten->n_kabupaten,
                $list->trkabupaten->trpropinsi->n_propinsi,
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
