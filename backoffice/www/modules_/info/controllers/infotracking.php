<?php

/**
 * Description of Tracking Progress Permohonan
 *
 * @author agusnur
 * Created : 06 Sep 2010
 */
class InfoTracking extends WRC_AdminCont {

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
        $this->daftar = new tmpermohonan();
        $this->tracking = new tmtrackingperizinan();
        $this->status = new trstspermohonan();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '17') {
                $enabled = TRUE;
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
        $data['list'] ='';// $this->daftar->order_by('id', 'DESC')
//                ->where('c_izin_selesai', 0) //SK Belum diserahkan
//                ->where('c_izin_dicabut', 0) //Permohonan tidak dicabut
//                ->get();
        $this->load->vars($data);

        $js =  "$(document).ready(function() {
                        oTable = $('#trackinginfo').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Data Informasi Tracking";
        $this->template->build('infotracking_list', $this->session_info);
    }

    public function detail($no_daftar = NULL) {
        $p_daftar = $this->daftar->get_by_id($no_daftar);
        $dataTracking = $this->tracking
            ->where_in_related('tmpermohonan','id',$p_daftar->id)
            ->include_related('trstspermohonan')
            ->order_by('d_entry_awal', 'ASC')->get();

        $data['daftar'] = $p_daftar;
        $data['list'] = $this->status->get();
//        $data['list_tracking'] = $p_daftar->tmtrackingperizinan->order_by('id', 'ASC')->get();
        $data['list_tracking'] = $dataTracking;
        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#trackingdetail').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        
        $this->session_info['page_name'] = "Informasi Tracking";
        $this->template->build('infotracking_detail', $this->session_info);
    }

    public function list_data() {
        $obj = new tmpermohonan();
        $obj->start_cache();
        $obj->where('c_izin_selesai', 0)
        ->where('c_izin_dicabut', 0)
        ->order_by('id', 'DESC');
        $this->iTotalRecords = $obj->count();
        $this->sEcho = $this->input->post('sEcho');
        for ($i = 0; $i < 2; $i++) {
            if ($this->input->post('sSearch')) {
                $cri=$this->input->post('sSearch');
                //$obj->like('pendaftaran_id', $cri);
                $obj->where_related("tmpemohon", "n_pemohon = '$cri' ");
              //  $obj->like('pendaftaran_id', $this->input->post('sSearch'));
               // $obj->like('pendaftaran_id', $this->input->post('sSearch'));
                //$obj->like('pendaftaran_id', $this->input->post('sSearch'));
            }
            
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
                echo $this->list_data_output($obj->get());
            }
        }
    }

    private function list_data_output($obj) {
        $aaData = array();
        $i = $this->iDisplayStart;
        foreach ($obj as $list) {
            $i++;
            $action = NULL;
            $img_info = array(
                'src' => base_url().'assets/images/icon/information.png',
                'alt' => 'Info Tracking',
                'title' => 'Info Tracking',
                'border' => '0',
            );
            $action .= anchor(site_url('info/infotracking/detail') .'/'. $list->id, img($img_info));

            $list->tmpemohon->get();
            $list->trperizinan->get();
            $list->trjenis_permohonan->get();
            $list->trstspermohonan->get();
            $aaData[] = array(
                $i,
                $list->pendaftaran_id,
                $list->trperizinan->n_perizinan,
                $list->a_izin,
                $list->tmpemohon->n_pemohon,
                $list->trjenis_permohonan->n_permohonan,
                $list->trstspermohonan->n_sts_permohonan,
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


    function sql_info_tracking(){
        $sql="
            SELECT t1.pendaftaran_id, t3.n_perizinan,t1.a_izin, if(t5.n_pemohon IS NULL, b.n_pemohon, t5.n_pemohon) AS n_pemohon, 
            t13.n_permohonan ,t7.n_sts_permohonan, t1.id FROM tmpermohonan as t1
            LEFT JOIN tmpermohonan_trperizinan as t2 on t1.id = t2.tmpermohonan_id
            LEFT JOIN trperizinan as t3 on t3.id = t2.trperizinan_id
            LEFT JOIN tmpemohon_tmpermohonan as t4 on t4.tmpermohonan_id = t1.id
            LEFT JOIN tmpemohon as t5 on t5.id = t4.tmpemohon_id
            LEFT JOIN tmpermohonan_trstspermohonan as t6 on t1.id = t6.tmpermohonan_id
            LEFT JOIN trstspermohonan as t7 on t7.id = t6.trstspermohonan_id
            LEFT JOIN tmpemohon_trkelurahan as t8 ON t8.tmpemohon_id= t5.id
            LEFT JOIN trkelurahan as t9 on t9.id = t8.trkelurahan_id
            LEFT JOIN tmpermohonan_tmperusahaan as t10 on t10.tmpermohonan_id = t1.id
            LEFT JOIN tmperusahaan as t11 on t11.id=t10.tmperusahaan_id
            LEFT JOIN tmpermohonan_trjenis_permohonan as t12 on t1.id = t12.tmpermohonan_id
            LEFT JOIN trjenis_permohonan as t13 on t12.trjenis_permohonan_id = t13.id
            LEFT JOIN tmpemohon_sementara_tmpermohonan AS a ON a.tmpermohonan_id = t1.id
            LEFT JOIN tmpemohon_sementara AS b ON b.id = a.tmpemohon_sementara_id
            LEFT JOIN tmpermohonan_tmperusahaan_sementara AS c ON t1.id = c.tmpermohonan_id
            LEFT JOIN tmperusahaan_sementara AS d ON d.id = c.tmperusahaan_sementara_id
            
            ";
        return $sql;
    }

    function datatables_infotracking(){
        $iDisplayStart=$this->input->post('iDisplayStart');
        $obj=$this->get_list_infotracking();
        $total=$this->get_total_infotracking();
        if ($obj){
           
            $img_info = array(
                'src' => base_url().'assets/images/icon/information.png',
                'alt' => 'Info Tracking',
                'title' => 'Info Tracking',
                'border' => '0',
            );
            $i=$iDisplayStart;
            foreach ($obj as $list) {
                  $action = anchor(site_url('info/infotracking/detail') .'/'. $list->id, img($img_info));
                $i++;
                $aaData[] = array(
                    $i,
                    $list->pendaftaran_id,
                    $list->n_perizinan,
                    $list->a_izin,
                    $list->n_pemohon,
                    $list->n_permohonan,
                    $list->n_sts_permohonan,
                    $action
                );
            }
        }else{
            $aaData=array();
        }
        $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $total,
            "iTotalDisplayRecords" => $total,
            "aaData" => $aaData
        );
        echo json_encode($sOutput);
    }

    function get_list_infotracking(){
        $sSearch=  $this->input->post('sSearch');
        $iDisplayLength= $this->input->post('iDisplayLength');
        $iDisplayStart=$this->input->post('iDisplayStart');
        $sql=$this->sql_info_tracking();
        $sql.=" WHERE t1.c_izin_dicabut =0 and t1.c_izin_selesai =0 ";
        if ($sSearch != NULL){
            $colum=array("t1.pendaftaran_id", "t3.n_perizinan","t1.a_izin", "t5.n_pemohon", "t13.n_permohonan" ,"t7.n_sts_permohonan");
            $sql.=$this->lib_query->add_searching($colum, 'AND', $sSearch);
        }
        $sql.=" ORDER BY t1.id DESC ";
        $sql.=" LIMIT  $iDisplayStart,$iDisplayLength";
        return $this->db->query($sql)->result();
    }
    function get_total_infotracking(){
        $sSearch=  $this->input->post('sSearch');
        $sql=$this->sql_info_tracking();
        $sql.=" WHERE t1.c_izin_dicabut =0 and t1.c_izin_selesai =0 ";
         if ($sSearch != NULL){
            $colum=array("t1.pendaftaran_id", "t3.n_perizinan","t1.a_izin", "t5.n_pemohon", "t13.n_permohonan" ,"t7.n_sts_permohonan");
            $sql.=$this->lib_query->add_searching($colum, 'AND', $sSearch);
        }
        $sql.=" ORDER BY t1.id DESC ";
        return $this->db->query($sql)->num_rows();
    }

}
