<?php

/**
 * Description of Persetujuan Salinan Dokumen
 *
 * @author agusnur
 * Created : 29 Sep 2010
 */

class Persetujuan extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->sk = new tmsk();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '4') {
                $enabled = TRUE;
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
//        $daftar = new tmpermohonan();
//        $query = $daftar
//                ->where('c_pendaftaran', 1) //1->Pendaftaran Belum selesai
//                ->where('c_izin_selesai', 1) //1->SK diserahkan
//                ->where('c_izin_dicabut', 0) //Permohonan tidak dicabut
//                ->order_by('d_terima_berkas', 'DESC')->limit(1500)->get();
        $tgla = $this->input->post('tgla');
        $tglb = $this->input->post('tglb');
        $now = $this->lib_date->get_date_now();
        $tgl_before = $this->lib_date->set_date($now, -2);
        $tgl_now = $this->lib_date->set_date($now, 0);

        if($tgla && $tglb){
            $data['tgla'] = $tgla;
            $data['tglb'] = $tglb;
        }else{
            $tgla = $tgl_before;
            $tglb = $tgl_now;
            $data['tgla'] = $tgla;
            $data['tglb'] = $tglb;
        }
        $query = "SELECT A.id, A.pendaftaran_id, A.d_terima_berkas,
        A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
        C.n_perizinan, E.n_pemohon, G.id idjenis,
        K.id idsk, K.tgl_surat, K.no_surat
        FROM tmpermohonan as A
        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
        INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
        INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
	INNER JOIN tmbap_tmpermohonan H ON A.id = H.tmpermohonan_id
	INNER JOIN tmbap I ON H.tmbap_id = I.id
	INNER JOIN tmpermohonan_tmsk J ON A.id = J.tmpermohonan_id
	INNER JOIN tmsk K ON J.tmsk_id = K.id
        WHERE A.c_pendaftaran = 1
        AND A.c_izin_dicabut = 0
        AND A.c_izin_selesai = 1
        AND K.c_is_requested = 1
        AND K.c_status_salinan = 0
        AND I.status_bap = 1
        AND A.d_terima_berkas between '$tgla' and '$tglb'
        order by A.id DESC";
        $data['list'] = $query;
        $data['c_bap'] = "1";
        $this->load->vars($data);

        $js =  "
                function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }

                $(document).ready(function() {
                        oTable = $('#persetujuan').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                $(function() {
                    $(\".monbulan\").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'yy-mm-dd',
                        closeText: 'X'
                    });
                });
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Persetujuan Salinan Dokumen";
        $this->template->build('persetujuan_list', $this->session_info);
    }

    public function status($id = NULL, $status = NULL) {
        $status_salinan = NULL;
        $this->sk->where('id', $id)->get();
        $count = intval($this->sk->c_status_salinan_order);
        if($status === '1') {
            $status_salinan = 1;
        } else if($status === '0') {
            $status_salinan = 2;
        }
        $this->sk->where('id',$id)
                ->update(array(
            'c_status_salinan' => $status_salinan,
            'c_status_salinan_order' => $count+1,
        ));

        redirect('dokumen/persetujuan');
    }
}

// This is the end of role class
