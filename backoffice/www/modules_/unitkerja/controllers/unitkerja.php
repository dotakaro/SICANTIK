<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of unitkerja class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class Unitkerja extends WRC_AdminCont {
    
    public function __construct() {
        parent::__construct();
        $this->unitkerja = new trunitkerja();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->unitkerja = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '4') {
                $enabled = TRUE;
                $this->unitkerja = new trunitkerja();
            }
        }
		
        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
//        $data['list'] = $this->unitkerja->get();
        $queryUnitKerja = "
            SELECT u.*, DAERAH.* FROM trunitkerja u
              LEFT JOIN (
                SELECT * FROM (
                    SELECT kode_daerah, n_propinsi as n_daerah FROM trpropinsi
                    UNION ALL
                    SELECT kode_daerah, n_kabupaten as n_daerah FROM trkabupaten
                    UNION ALL
                    SELECT kode_daerah, n_kecamatan as n_daerah FROM trkecamatan
                    UNION ALL
                    SELECT kode_daerah, n_kelurahan as n_daerah FROM trkelurahan
                )DAERAH_UNION
              )DAERAH ON DAERAH.kode_daerah = u.kode_daerah
        ";

        $getData = $this->db->query($queryUnitKerja)->result();
        $data['list'] = $getData;
        $this->load->vars($data);

        $js =  "
             function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                        oTable = $('#unitkerja').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
          8      } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Unit Kerja";
        $this->template->build('list', $this->session_info);
    }

    public function create() {
        ### BEGIN - Ambil List Kode Daerah ###
        /*$queryKodeDaerah = "
            SELECT kode_daerah, n_propinsi as n_daerah FROM trpropinsi
            UNION ALL
            SELECT kode_daerah, n_kabupaten as n_daerah FROM trkabupaten
            UNION ALL
            SELECT kode_daerah, n_kecamatan as n_daerah FROM trkecamatan
            UNION ALL
            SELECT kode_daerah, n_kelurahan as n_daerah FROM trkelurahan
        ";
        $getDaerah = $this->db->query($queryKodeDaerah)->result();
        if(!empty($getDaerah)){
            foreach($getDaerah as $dataDaerah){
                $listKodeDaerah[$dataDaerah->kode_daerah] = $dataDaerah->kode_daerah.' - '.$dataDaerah->n_daerah;
            }
        }*/
        ### END - Ambil List Kode Daerah ###

        $data['n_unitkerja']  = "";
        $data['kode_daerah']  = "";
        $data['kode_daerah_text']  = "";
        $data['save_method'] = "save";
        $data['id'] = "";
//        $data['listKodeDaerah'] = $listKodeDaerah;
//        $data['flag_institusi_daerah'] = "";
         /*$js =  "
                $(document).ready(function() {
                     $('#form').validate();
                     $('#kode_daerah').multiselect({
                       show:'blind',
                       hide:'blind',
                       multiple: false,
                       header: 'Pilih salah satu',
                       noneSelectedText: 'Pilih salah satu',
                       selectedList: 1
                    }).multiselectfilter();
                });
                ";*/
        $js = "";
        $this->template->set_metadata_javascript($js);
        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Unit Kerja";
        $this->template->build('edit', $this->session_info);
    }

    public function edit($id = NULL) {
        ### BEGIN - Ambil List Kode Daerah ###
        /*$queryKodeDaerah = "
            SELECT kode_daerah, n_propinsi as n_daerah FROM trpropinsi
            UNION ALL
            SELECT kode_daerah, n_kabupaten as n_daerah FROM trkabupaten
            UNION ALL
            SELECT kode_daerah, n_kecamatan as n_daerah FROM trkecamatan
            UNION ALL
            SELECT kode_daerah, n_kelurahan as n_daerah FROM trkelurahan
        ";
        $getDaerah = $this->db->query($queryKodeDaerah)->result();
        if(!empty($getDaerah)){
            foreach($getDaerah as $dataDaerah){
                $listKodeDaerah[$dataDaerah->kode_daerah] = $dataDaerah->kode_daerah.' - '.$dataDaerah->n_daerah;
            }
        }*/
        ### END - Ambil List Kode Daerah ###

//        $this->unitkerja->get_by_id($id);

        $queryUnitKerja = "
            SELECT u.*, DAERAH.* FROM trunitkerja u
              LEFT JOIN (
                SELECT * FROM (
                    SELECT kode_daerah, n_propinsi as n_daerah FROM trpropinsi
                    UNION ALL
                    SELECT kode_daerah, n_kabupaten as n_daerah FROM trkabupaten
                    UNION ALL
                    SELECT kode_daerah, n_kecamatan as n_daerah FROM trkecamatan
                    UNION ALL
                    SELECT kode_daerah, n_kelurahan as n_daerah FROM trkelurahan
                )DAERAH_UNION
              )DAERAH ON DAERAH.kode_daerah = u.kode_daerah
            WHERE u.id = $id
        ";
        $query = $this->db->query($queryUnitKerja);
        if($query->num_rows() == 0){
            redirect('unitkerja');
        }
        $getData = $query->result();
        $dataUnitKerja = $getData[0];

        $data['n_unitkerja']  = $dataUnitKerja->n_unitkerja;
        $data['kode_daerah']  = $dataUnitKerja->kode_daerah;
        $data['kode_daerah_text']  = $dataUnitKerja->n_daerah;
        $data['save_method'] = "update";
        $data['id'] = $dataUnitKerja->id;
//        $data['listKodeDaerah'] = $listKodeDaerah;
//        $data['flag_institusi_daerah'] = $dataUnitKerja->flag_institusi_daerah;
        /*$js =  "
                $(document).ready(function() {
                     $('#form').validate();
                     $('#kode_daerah').multiselect({
                       show:'blind',
                       hide:'blind',
                       multiple: false,
                       header: 'Pilih salah satu',
                       noneSelectedText: 'Pilih salah satu',
                       selectedList: 1
                     }).multiselectfilter();
                });
                ";
        */

        $js = "";
        $this->template->set_metadata_javascript($js);
        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Unit Kerja";
        $this->template->build('edit', $this->session_info);
    }

    public function delete($id = NULL) {
        $this->unitkerja->where('id', $id)->get();
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting User','Delete unit kerja ".$this->unitkerja->n_unitkerja."','".$tgl."','".$u_ser."')");


        if($this->unitkerja->delete()) {
            redirect('unitkerja');
        }
    }

    public function save() {
        $this->unitkerja->n_unitkerja = $this->input->post('n_unitkerja');
//        $this->unitkerja->flag_institusi_daerah = $this->input->post('flag_institusi_daerah');
        $this->unitkerja->kode_daerah = $this->input->post('kode_daerah');
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting User','Insert unit kerja ".$this->input->post('n_unitkerja')."','".$tgl."','".$u_ser."')");

        if($this->unitkerja->save()) {
            redirect('unitkerja');
        }
    }

    public function update() {
        $update = $this->unitkerja
                ->where('id', $this->input->post('id'))
                ->update(array(
                    'n_unitkerja' => $this->input->post('n_unitkerja'),
//                    'flag_institusi_daerah' => $this->input->post('flag_institusi_daerah'),
                    'kode_daerah' => $this->input->post('kode_daerah'),
                ));
        if($update) {
              $tgl = date("Y-m-d H:i:s");
              $u_ser = $this->session->userdata('username');
              $p = $this->db->query("call log ('Setting User','Update unit kerja ".$this->input->post('n_unitkerja')."','".$tgl."','".$u_ser."')");

            redirect('unitkerja');
        }
    }

    public function combogrid_daerah(){
        $this->load->library('MY_Input');
        $total_pages=0;
        $count = 0;
        $page=$this->input->get('page');
        $limit =$this->input->get('rows'); // get how many rows we want to have into the grid
        $sidx =$this->input->get('sidx'); // get index row - i.e. user click to sort
        $sord =$this->input->get('sord'); // get the direction

        if($this->input->get('searchTerm')){
            $searchTerm = $this->input->get('searchTerm');
        }else{
            $searchTerm = "";
        }
        if(!$sidx) $sidx ='report_code';
        if ($searchTerm=="") {
            $searchTerm="%";
        } else {
            $searchTerm = "%" . $searchTerm . "%";
        }

        $queryKodeDaerah = "
            SELECT COUNT(*) as TOTAL FROM(
                SELECT * FROM (
                    SELECT kode_daerah, n_propinsi as n_daerah FROM trpropinsi
                    UNION ALL
                    SELECT kode_daerah, n_kabupaten as n_daerah FROM trkabupaten
                    UNION ALL
                    SELECT kode_daerah, n_kecamatan as n_daerah FROM trkecamatan
                    UNION ALL
                    SELECT kode_daerah, n_kelurahan as n_daerah FROM trkelurahan
                )DAERAH WHERE DAERAH.kode_daerah LIKE '$searchTerm' OR DAERAH.n_daerah LIKE '$searchTerm'
            )RESULTS
        ";
        $getCountDaerah = $this->db->query($queryKodeDaerah)->result_array();
        if(!empty($getCountDaerah)){
            $count = $getCountDaerah[0]['TOTAL'];
        }
//        $count=$this->db->count_all_results();

        if( $count > 0 ) {
            $total_pages = ceil($count/$limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit; // do not put $limit*($page - 1)

        if($total_pages!=0){
            $queryKodeDaerah = "
                SELECT * FROM (
                    SELECT kode_daerah, n_propinsi as n_daerah FROM trpropinsi
                    UNION ALL
                    SELECT kode_daerah, n_kabupaten as n_daerah FROM trkabupaten
                    UNION ALL
                    SELECT kode_daerah, n_kecamatan as n_daerah FROM trkecamatan
                    UNION ALL
                    SELECT kode_daerah, n_kelurahan as n_daerah FROM trkelurahan
                )DAERAH WHERE DAERAH.kode_daerah LIKE '$searchTerm' OR DAERAH.n_daerah LIKE '$searchTerm'
                LIMIT $start, $limit
            ";
            $query = $this->db->query($queryKodeDaerah);
        }

        $response=new stdClass();
        $response->page = $page;
        $response->total = $total_pages;
        $response->records = $count;
        $i=0;

        if(count($query->result())>0){
            foreach ($query->result() as $result)
            {
                $response->rows[$i]['kode_daerah']=$result->kode_daerah;
                $response->rows[$i]['n_daerah']=$result->n_daerah;
                $i++;
            }
        }else{
            $response->rows[$i]['kode_daerah']="";
            $response->rows[$i]['n_daerah']="";
        }
        echo json_encode($response);
        exit();
    }

    /**
     * Fungsi untuk mengecek apakah NPWP sudah pernah digunakan
     */
    public function register_kodedaerah_exist(){
        $kode_daerah = mysql_real_escape_string($_POST['kode_daerah']);
        $id = mysql_real_escape_string($_POST['id']);
        if($id){
            $sql = "SELECT kode_daerah FROM trunitkerja WHERE kode_daerah = '$kode_daerah' and id<>$id";
        }else{
            $sql = "SELECT kode_daerah FROM trunitkerja WHERE kode_daerah = '$kode_daerah'";
        }
//        echo $sql;
        $hasil = $this->db->query($sql);
        $result = $hasil->row();
        if($result) {
            $output = true;
        } else {
            $output = false;
        }

        echo json_encode($output);
    }
}

// This is the end of unitkerja class
