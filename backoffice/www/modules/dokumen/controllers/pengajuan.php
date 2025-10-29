<?php

/**
 * Description of Pengajuan Salinan Dokumen
 *
 * @author agusnur
 * Created : 29 Sep 2010
 */
class Pengajuan extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->sk = new tmsk();
        $this->perizinan = new trperizinan();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if ($list_auth->id_role === '15') {
                $enabled = TRUE;
            }
        }
		
        if (!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
//        $daftar = new tmpermohonan();
//        $jenis_izin = $this->input->post('jenis_izin');
//        $year_id = $this->input->post('year_id');
//        $data_izin = new trperizinan();
//        $data_izin->get_by_id($jenis_izin);
//
//        if($jenis_izin && $year_id){
//            $data_list = $daftar
//            ->where_related($data_izin)
//            ->where('c_pendaftaran', 1) //1->Pendaftaran Belum selesai
//            ->where('c_izin_selesai', 1) //1->SK diserahkan
//            ->where('c_izin_dicabut', 0) //Permohonan tidak dicabut
//            ->where('LEFT(d_terima_berkas, 4) =', $year_id)
//            ->order_by('d_terima_berkas', 'DESC')
//            ->get();
//        }else{
//            $data_list = $daftar
//            ->where('c_pendaftaran', 1) //1->Pendaftaran Belum selesai
//            ->where('c_izin_selesai', 1) //1->SK diserahkan
//            ->where('c_izin_dicabut', 0) //Permohonan tidak dicabut
//            ->order_by('d_terima_berkas', 'DESC')
//            ->limit(0)->get();
//        }
//
//        $perizinan = $this->perizinan->order_by('id','ASC')->get();
//        $data['list_izin'] = $perizinan;
//        $data['jenis_izin'] = $jenis_izin;
//        $data['year_id'] = $year_id;
        $tgla = $this->input->post('tgla');
        $tglb = $this->input->post('tglb');
        $now = $this->lib_date->get_date_now();
        $tgl_before = $this->lib_date->set_date($now, -2);
        $tgl_now = $this->lib_date->set_date($now, 0);

        if ($tgla && $tglb) {
            $data['tgla'] = $tgla;
            $data['tglb'] = $tglb;
        } else {
            $tgla = $tgl_before;
            $tglb = $tgl_now;
            $data['tgla'] = $tgla;
            $data['tglb'] = $tglb;
        }
        $username = new user();
        $username->where('username', $this->session->userdata('username'))->get();
        $data_list = "SELECT A.id, A.pendaftaran_id, A.d_terima_berkas,
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
        INNER JOIN trperizinan_user AS L ON L.trperizinan_id = C.id
        WHERE A.c_pendaftaran = 1
        AND A.c_izin_dicabut = 0
        AND A.c_izin_selesai = 1
        AND K.c_is_requested = 0
        AND I.status_bap = 1
        AND L.user_id = '".$username->id."'
        AND A.d_terima_berkas between '$tgla' and '$tglb'
        AND A.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})
        AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
        order by A.id DESC";
        $data['list'] = $data_list;
        $data['c_bap'] = "1";

        $this->load->vars($data);

        $js = "
                function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }

                $(document).ready(function() {
                        oTable = $('#pengajuan').dataTable({
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
        $this->session_info['page_name'] = "Pengajuan Salinan Surat Izin";
        $this->template->build('pengajuan_list', $this->session_info);
    }

    public function baru($id = NULL) {
        $this->sk->where('id', $id)
                ->update(array(
                    'c_is_requested' => 1,
                    'c_status_salinan' => 1
                ));

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql2($u_ser);
        $pendaftaran = $this->get_pendaftaran($id);
        $p = $this->db->query("call log ('Pengajuan Salinan','Pengajuan ".$pendaftaran->pendaftaran_id."','".$tgl."','".$u_ser."')");

        redirect('dokumen/pengajuan');
    }

    public function sql2($u_ser)
    {
        $query = "select a.description from user_auth as a
                  inner join user_user_auth as  x on a.id = x.user_auth_id
                  inner join user as b on b.id = x.user_id
                  where b.id = (select id from user where username='".$u_ser."')";
        $hasil = $this->db->query($query);
        return $hasil->row();
    }

    public function get_pendaftaran($id)
    {
        $query="select a.pendaftaran_id from tmpermohonan as a
                inner join tmpermohonan_tmsk as b on a.id=b.tmpermohonan_id
                inner join tmsk as c on b.tmsk_id=c.id
                where c.id = '".$id."';";

        $hasil = $this->db->query($query);
        return $hasil->row();
    }

}

// This is the end of role class
