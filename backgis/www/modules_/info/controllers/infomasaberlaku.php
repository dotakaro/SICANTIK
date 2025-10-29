<?php

/**
 * Description of Masa Berlaku Izin
 *
 * @author agusnur @update yogi
 * Created : 17 Sep 2010
 */
class InfoMasaBerlaku extends WRC_AdminCont {

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

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if ($list_auth->id_role === '17') {
                $enabled = TRUE;
            }
        }
		
        if (!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
        $data_tahun = date("Y") - 1;
        $tgl_skr = $this->lib_date->get_datetime_now();
        $tgl_next = $this->lib_date->set_date($tgl_skr, 60);
        $data['list'] = $this->daftar
                        ->where('c_izin_selesai', '1')
                        ->where('d_berlaku_izin !=', '1974-12-31')
                        ->where('d_berlaku_izin >=', $tgl_skr)
                        ->where('d_berlaku_izin <', $tgl_next)
                        ->order_by('d_berlaku_izin', 'ASC')->get();
        $this->load->vars($data);

        $js = "function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                        oTable = $('#masaberlakuinfo').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);

        $this->session_info['page_name'] = "Informasi Masa Berlaku Izin";
        $this->template->build('infomasaberlaku_list', $this->session_info);
    }

    public function sms_confirm($id = NULL) {
        $daftar = $this->daftar->get_by_id($id);
        $pemohon = $daftar->tmpemohon->get();
        $perizinan = $daftar->trperizinan->get();

        $surat = $daftar->tmsk->get();
        $nama_izin = $perizinan->n_perizinan;
        $no_surat = $surat->no_surat;

        //Kirim SMS masa berlaku
        $text = "Surat " . $nama_izin . " dgn no surat " . $no_surat . " 1 bulan lg akan habis masa berlakunya. Silahkan untuk diperpanjang.";

        if (strlen($text) > 160) {
            $text = NULL;
            $text = "Surat Anda dgn no surat " . $no_surat . " 1 bulan lg akan habis masa berlakunya. Silahkan untuk diperpanjang.";
        }

        $number = $pemohon->telp_pemohon;
//        if($number) {
        $outbox = new outbox();
        $outbox->TextDecoded = $text;
        $outbox->DestinationNumber = $number;
        $outbox->DeliveryReport = 'yes';
        $update = $outbox->save();
//        }

        if (!$update) {
            echo '<p>' . $daftar->error->string . '</p>';
        } else {
            redirect('info/infomasaberlaku');
        }
    }

    public function list_data() {
        $tgl_skr = $this->lib_date->get_date_now();
        $tgl_next = $this->lib_date->set_date($tgl_skr, 60);

        $obj = new tmpermohonan();
//        $obj->start_cache();
        $obj->where('c_izin_selesai', 1)
                ->where('c_izin_dicabut', 0)
//        ->where('d_berlaku_izin >=', $tgl_skr)
                ->where('d_berlaku_izin <', $tgl_next)
                ->where_in_related('trperizinan/user','id',$this->session->userdata('id_auth'))
//                ->where_in_related('trperizinan/trunitkerja','id',$this->__get_current_unitakses())
                ->order_by('d_berlaku_izin', 'ASC');

        $this->iTotalRecords = $obj->count();
        $this->sEcho = $this->input->post('sEcho');
        for ($i = 0; $i < 2; $i++) {
            if ($this->input->post('sSearch')) {
                $obj->like('pendaftaran_id', $this->input->post('sSearch'));
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
//                $obj->stop_cache();
                echo $this->list_data_output($obj->get());
            }
        }
    }

    private function list_data_output($obj) {
        $aaData = array();
        $i = $this->iDisplayStart;
        foreach ($obj as $list) {
            $i++;
            $tgl_skr = $this->lib_date->get_date_now();
            $action = NULL;
            $tgl_berlaku = NULL;

            $exp = NULL;
            if ($list->d_berlaku_izin >= $tgl_skr || $list->d_berlaku_izin === NULL ) {
                $exp = "-";
            }  else {
                $exp ="Kadaluarsa";
            }


            $list->tmpemohon->get();
            $list->trperizinan->get();
            $list->trjenis_permohonan->get();
            if ($list->d_berlaku_izin) {
                if ($list->d_berlaku_izin != '0000-00-00') {
                    $tgl_berlaku = $this->lib_date->mysql_to_human($list->d_berlaku_izin);
                }
                else
                    $tgl_berlaku = "SK Belum dibuat";
            }else
                $tgl_berlaku = "SK Belum dibuat";
            $aaData[] = array(
                $i,
                $list->pendaftaran_id,
                $list->trperizinan->n_perizinan,
                $list->tmpemohon->n_pemohon,
                $list->trjenis_permohonan->n_permohonan,
                $tgl_berlaku,
                strval($exp),
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
