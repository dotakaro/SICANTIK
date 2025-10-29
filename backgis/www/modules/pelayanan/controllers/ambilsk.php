<?php

/**
 * Description of Pengambilan SK
 *
 * @author agusnur
 * Created : 22 Sep 2010
 */
class AmbilSK extends WRC_AdminCont {

    private $_status_penyerahan = 14;

    public function __construct() {
        parent::__construct();
        $this->sk = new tmsk();

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

    public function list_index($tgla = NULL, $tglb = NULL) {
//        $daftar = new tmpermohonan();
//        $query = $daftar
//                ->where('c_pendaftaran', 1) //Pendaftaran selesai
//                ->where('c_izin_selesai', 0) //SK Belum diserahkan
//                ->where('c_izin_dicabut', 0) //Permohonan tidak dicabut
//                ->order_by('id', 'DESC')->get();
//        $tgla = $this->input->post('tgla');
//        $tglb = $this->input->post('tglb');
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
        $status_penyerahan = 14;//Penyerahan Izin

        $username = new user();
        $username->where('username', $this->session->userdata('username'))->get();
        $query = "SELECT
            A.id, A.pendaftaran_id, A.c_status_bayar, A.d_terima_berkas,
            A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
            C.id idizin, C.n_perizinan, E.n_pemohon,
            G.id idjenis, G.n_permohonan,
            I.status_bap, K.tgl_surat, K.no_surat, K.c_cetak,
            L.trkelompok_perizinan_id idkelompok,
            E.telp_pemohon
        FROM tmpermohonan as A
            INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
            INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
            INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
            INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
            INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
            INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
            LEFT JOIN tmbap_tmpermohonan H ON A.id = H.tmpermohonan_id
            LEFT JOIN tmbap I ON H.tmbap_id = I.id
            LEFT JOIN tmpermohonan_tmsk J ON A.id = J.tmpermohonan_id
            LEFT JOIN tmsk K ON J.tmsk_id = K.id
            INNER JOIN trkelompok_perizinan_trperizinan L ON L.trperizinan_id = C.id
        /* INNER JOIN trperizinan_user AS M ON M.trperizinan_id = C.id */
        WHERE A.c_pendaftaran = 1
        AND A.c_izin_dicabut = 0
        AND A.c_izin_selesai = 0
        /* AND M.user_id = '" . $username->id . "' */
        AND A.d_terima_berkas between '$tgla' and '$tglb'
        AND A.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})
        AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
        AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = {$status_penyerahan})>0
        ORDER BY A.id DESC";
        $data['list'] = $query;
        $this->load->vars($data);

        $js = "
                function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }

                $(document).ready(function() {
                        oTable = $('#penyerahan').dataTable({
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
        $this->session_info['page_name'] = "Penyerahan Izin";
        $this->template->build('ambilsk_list', $this->session_info);
    }
    public function sendSMSGateway()
    {
        $this->load->library('form_validation');

		$this->form_validation->set_rules('txtno','No Tujuan','trim|required|numeric');
		$this->form_validation->set_rules('txtisi','Isi Pesan','trim|required|max_length[160]|min_length[4]|htmlspecialchars|xss_clean');

		if($this->form_validation->run()==FALSE)
		{
			echo validation_errors();
			exit;
		}
		else
		{
			$no = $this->input->post('txtno',TRUE);
			$pesan = $this->input->post('txtisi',TRUE);
			if($this->_sembunyiInsert($no,$pesan))
			{
				redirect('pelayanan/ambilsk');
			}
			else
			{
				echo "Terjadi Kesalahan, Silahkan kirim kembali";
				exit;
			}

		}
    }
	function _sembunyiInsert($no,$pesan)
	{
		$this->load->model('m_insert','MInsert');
		return $this->MInsert->inserData($no,$pesan);
	}

    public function index($sALL=0, $tgla = null, $tglb = null) {
//        $daftar = new tmpermohonan();
//        $query = $daftar
//                ->where('c_pendaftaran', 1) //Pendaftaran selesai
//                ->where('c_izin_selesai', 0) //SK Belum diserahkan
//                ->where('c_izin_dicabut', 0) //Permohonan tidak dicabut
//                ->order_by('id', 'DESC')->get();
		##Update 13 Feb 2014##
		if(is_null($tgla)):
			$tgla = $this->input->post('tgla');
        endif;
		if(is_null($tglb)):
			$tglb = $this->input->post('tglb');
        endif;
		######################
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
        $status_penyerahan = $this->_status_penyerahan;//Lihat di tabel trstspermohonan

        $query = "SELECT DISTINCT A.id, A.pendaftaran_id, A.c_penetapan, A.c_status_bayar, A.d_terima_berkas,
            A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
            C.id idizin, C.n_perizinan, E.n_pemohon,
            G.id idjenis, G.n_permohonan,
            I.status_bap, K.tgl_surat, K.no_surat, K.c_cetak,
            L.trkelompok_perizinan_id idkelompok,
            E.telp_pemohon,  A.c_izin_selesai
        FROM tmpermohonan as A
        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
        INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
        INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
        LEFT JOIN tmbap_tmpermohonan H ON A.id = H.tmpermohonan_id
        LEFT JOIN tmbap I ON H.tmbap_id = I.id
        LEFT JOIN tmpermohonan_tmsk J ON A.id = J.tmpermohonan_id
        LEFT JOIN tmsk K ON J.tmsk_id = K.id
        INNER JOIN trkelompok_perizinan_trperizinan L ON L.trperizinan_id = C.id
        /* INNER JOIN trperizinan_user AS M ON M.trperizinan_id = C.id */
        WHERE A.c_pendaftaran = 1
        AND A.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})
        AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
        AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = {$status_penyerahan})>0
        AND A.c_izin_dicabut = 0";
		if($sALL==1){
			$query.=" AND A.c_izin_selesai = 1 AND A.d_terima_berkas between '$tgla' and '$tglb' ";
		}else{
			$query.=" AND A.c_izin_selesai = 0 AND A.d_terima_berkas between '$tgla' and '$tglb' ";
		}
		
        $query.="order by A.id DESC";
		$data['list'] = $query;
		$data['sALL'] = $sALL;
        $data['list_izin_bertarif'] = $this->__get_izin_dengan_tarif();
        $this->load->vars($data);
        $js = "
                function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }

                $(document).ready(function() {
                        oTable = $('#penyerahan').dataTable({
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
                $('.kirimsms').click(function(){
                    $('#smsdialog').dialog({modal: true,title:'Konfirmasi SMS',autoOpen: false,height: 150,width:250,draggable:false,resizable:false});
                    $('#smsdialog').dialog('open');
                    return false;
                });
				$('#tblreset').click(function(){
					$('#smsdialog').dialog('close');
				});

                });

                function isino(data,isisms)
                {
                    $('#txtno').val(data.toString());
	  $('#spanno').text(data.toString());
                    $('#txtisi').val(isisms.toString());
                    //var url=$(this).attr('href');
                }
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Penyerahan Izin";
        $this->template->build('ambilsk_list', $this->session_info);
    }

    public function diambil($id_daftar = NULL, $tgla = NULL, $tglb = NULL, $sALL) {
        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($id_daftar);
        $permohonan->d_ambil_izin = $this->lib_date->get_date_now();
        $permohonan->c_izin_selesai = '1';
        $update = $permohonan->save();
        $pemohon = $permohonan->tmpemohon->get();

        $status_izin = $permohonan->trstspermohonan->get();

        $status_skr = "14"; //Penyerahan Izin [Lihat Tabel trstspermohonan()]
        $id_status = "15"; //Arsip [Lihat Tabel trstspermohonan()]
        if ($status_izin->id == $status_skr) {
            /* Input Data Tracking Progress */
            /*$sts_izin = new trstspermohonan();
            $sts_izin->get_by_id($status_skr);
            $data_status = new tmtrackingperizinan_trstspermohonan();
            $list_tracking = $permohonan->tmtrackingperizinan->get();
            if ($list_tracking) {
                $tracking_id = 0;
                foreach ($list_tracking as $data_track) {
                    $data_status = new tmtrackingperizinan_trstspermohonan();
                    $data_status->where('tmtrackingperizinan_id', $data_track->id)
                            ->where('trstspermohonan_id', $sts_izin->id)->get();
                    if ($data_status->tmtrackingperizinan_id) {
                        $tracking_id = $data_status->tmtrackingperizinan_id;
                    }
                }
            }
            $tracking_izin = new tmtrackingperizinan();
            $tracking_izin->get_by_id($tracking_id);
            //$tracking_izin->pendaftaran_id = $permohonan->pendaftaran_id;
            $tracking_izin->status = 'Update';
            $tracking_izin->d_entry = $this->lib_date->get_date_now();
            $tracking_izin->save();*/

            /* [Lihat Tabel trstspermohonan()] */
            /*$tracking_izin2 = new tmtrackingperizinan();
            $tracking_izin2->pendaftaran_id = $permohonan->pendaftaran_id;
            $tracking_izin2->status = 'Insert';
            $tracking_izin2->d_entry_awal = $this->lib_date->get_date_now();
            $tracking_izin2->d_entry = $this->lib_date->get_date_now();
            $sts_izin2 = new trstspermohonan();
            $sts_izin2->get_by_id($id_status); //[Lihat Tabel trstspermohonan()]
            $sts_izin2->save($permohonan);
            $tracking_izin2->save($permohonan);
            $tracking_izin2->save($sts_izin2);*/

            $this->__input_tracking_progress($id_daftar, $status_skr, $id_status);
        }

        if (!$update) {
            echo '<p>' . $update->error->string . '</p>';
        } else {
            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $g = $this->sql2($u_ser);
            $p = $this->db->query("call log ('Penyerahan Izin','Penyerahan izin ".$permohonan->pendaftaran_id."','".$tgl."','".$u_ser."')");
            redirect('pelayanan/ambilsk/index/'.$sALL. '/' . $tgla . '/' . $tglb);
        }
    }

     public function sql2($u_ser)
    {
        $query = "select a.description
	from user_auth as a
	inner join user_user_auth as  x on a.id = x.user_auth_id
	inner join user as b on b.id = x.user_id
        where b.id = (select id from user where username='".$u_ser."')";
        $hasil = $this->db->query($query);
        return $hasil->row();
    }

}

// This is the end of role class
