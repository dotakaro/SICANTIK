<?php

/**
 * Description of Pembuatan SK
 *
 * @author Eva
 * Updated : 04 Sep 2010 (agusnur)
 */

class Sk extends WRC_AdminCont {

    private $_status_pembuatan_izin = 17;

    public function __construct() {
        parent::__construct();
        $this->sk = new tmpermohonan();
        $this->propertyizin = new tmproperty_jenisperizinan();
        $this->perizinan = new trperizinan();
        $this->pemohon = new tmpemohon();
        $this->surat = new tmsk();
        $this->sk = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->sk = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '13') {
                $enabled = TRUE;
                $this->sk = new user_auth();
            }
        }
		
        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
//        $permohonan = new tmpermohonan();
//        $query = $permohonan
//                ->where('c_pendaftaran', 1) //Pendaftaran selesai
//                ->where('c_izin_selesai', 0) //SK Belum diserahkan
//                ->where('c_izin_dicabut', 0) //Permohonan tidak dicabut
//                ->order_by('id', 'DESC')->get();
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
//        $query = "SELECT A.id, A.pendaftaran_id, A.d_terima_berkas, A.d_survey,
//        A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
//        C.id idizin, C.n_perizinan, C.c_keputusan, E.n_pemohon,
//        G.id idjenis, G.n_permohonan
//        FROM tmpermohonan as A
//        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
//        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
//        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
//        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
//        INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
//        INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
//        WHERE A.c_pendaftaran = 1
//        AND A.c_izin_dicabut = 0
//        AND A.c_izin_selesai = 0
//        AND A.d_terima_berkas between '$tgla' and '$tglb'
//        order by A.id DESC";
         $username = new user();
        $username->where('username', $this->session->userdata('username'))->get();
		$status_sk = $this->_status_pembuatan_izin;//Lihat di tabel trstspermohonan;
        $current_unitkerja = $this->__get_current_unitkerja();

		/*if($this->__is_administrator()){
			$query = "SELECT DISTINCT A.id, A.pendaftaran_id, A.d_terima_berkas, A.c_status_bayar,
				        A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
				        C.id idizin, C.n_perizinan, C.c_keputusan, E.n_pemohon,
				        G.id idjenis, K.tgl_surat, K.no_surat, K.c_cetak, M.trkelompok_perizinan_id
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
				        INNER JOIN trkelompok_perizinan_trperizinan AS M ON M.trperizinan_id = C.id
				        WHERE A.c_pendaftaran = 1
				        AND A.c_izin_dicabut = 0
				        AND A.c_izin_selesai = 0
				        AND A.d_terima_berkas between '$tgla' and '$tglb'
				        AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
				        AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                            INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                            WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = {$status_sk})>0
				        order by A.id DESC";	
		}else{	*/
			$query = "SELECT DISTINCT A.id, A.pendaftaran_id, A.d_terima_berkas, A.c_status_bayar,
				        A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
				        C.id idizin, C.n_perizinan, C.c_keputusan, E.n_pemohon,
				        G.id idjenis, G.n_permohonan, K.tgl_surat, K.no_surat, K.c_cetak, M.trkelompok_perizinan_id
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
				        INNER JOIN trperizinan_user AS L ON  L.trperizinan_id = C.id
				        INNER JOIN trkelompok_perizinan_trperizinan AS M ON M.trperizinan_id = C.id
				        WHERE A.c_pendaftaran = 1
				        AND A.c_izin_dicabut = 0
				        /*AND A.c_izin_selesai = 0*/
				        AND L.user_id = '".$username->id."'
				        AND A.d_terima_berkas between '$tgla' and '$tglb'
				        AND A.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})
                        AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
				        /*AND I.status_bap = 1*/
				        AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                            INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                            WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = {$status_sk})>0
				        order by A.id DESC";
//		}

        $data['list'] = $query;
        $data['c_bap'] = "1";
        $this->load->vars($data);

        $js =  "$(document).ready(function() {
                        oTable = $('#sk').dataTable({
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
        $this->session_info['page_name'] = "Data Pembuatan Izin";
        $this->template->build('sk_list', $this->session_info);
    }
    
    public function edit($id_daftar = NULL) {
        $permohonan = new tmpermohonan();
        $permohonan = $permohonan->get_by_id($id_daftar);
        $surat = $permohonan->tmsk->get();
        $pegawai = $permohonan->tmsk->tmpegawai->get();

        if($surat->id){
            $save = "update";
            $id_surat = $surat->id;
            $no_surat = $surat->no_surat;
            $sts_surat = $surat->c_status;
            $tgl_surat = $surat->tgl_surat;
            $petugas = $pegawai->id;
        }else{
            $save = "save";
            $id_surat = "";
            $no_surat = "";
            $sts_surat = "";
            $tgl_surat = "";
            $petugas = "";
        }
        $data['save_method'] = $save;
        $data['daftar'] = $permohonan;
        $data['id_daftar'] = $id_daftar;
        $data['id_surat'] = $id_surat;
        $data['no_surat'] = $no_surat;
        $data['sts_surat'] = $sts_surat;
        $data['tgl_surat'] = $tgl_surat;
        $data['petugas'] = $petugas;
        $petugas = new tmpegawai();
        $data['list_petugas'] = $petugas->order_by('n_pegawai','ASC')->get();

        $js =  "$(function() {
                    $(\"#inputTanggal\").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'yy-mm-dd',
                        closeText: 'X'
                    });
                });
                var base_url = '". base_url() ."';
                $(document).ready(function() {
                    $(\"#tabs\").tabs();
                } );
            ";

        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Data Pembuatan Izin";
        $this->template->build('sk_edit', $this->session_info);
    }

    /*
     * Save and update for manipulating data.
     */
    public function save() {
        $petugas = 1;
        /* Input Data */
        $this->surat->no_surat = $this->input->post('no_surat');
        $this->surat->tgl_surat = $this->input->post('tgl_surat');

        /* Input Relasi Tabel*/
        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($this->input->post('id_daftar'));
        $perizinan = $permohonan->trperizinan->get();
        
		/***Edited by Indra**/
		//$permohonan->d_berlaku_izin = $this->lib_date->set_date($this->input->post('tgl_surat'), $perizinan->v_berlaku_tahun * 365); //per tahun
		if($perizinan->v_berlaku_tahun !=''&&$perizinan->v_berlaku_tahun !=NULL&&$perizinan->v_berlaku_tahun!=0){//Jika ada masa berlakunya
			$akhir_masa_berlaku=$this->lib_date->modDate($this->input->post('tgl_surat'),"+".$perizinan->v_berlaku_tahun,$perizinan->v_berlaku_satuan);
			$permohonan->d_berlaku_izin=$akhir_masa_berlaku;
			$permohonan->save();
		}
		/******************/
		
        $pegawai = new tmpegawai();
        $pegawai->where('status', $pegawai)->get();

        if(! $this->surat->save(array($permohonan, $pegawai))) {
            echo '<p>' . $this->surat->error->string . '</p>';
        } else {
            redirect('permohonan/sk');
        }
    }

    public function aktifsk() {
        $permohonan = new tmpermohonan();
        $data['list'] = $permohonan
                ->where('c_pendaftaran', 1) //Pendaftaran selesai
                ->where('c_izin_selesai', 0) //SK Belum diserahkan
                ->where('c_izin_dicabut', 0) //Permohonan tidak dicabut
                ->order_by('id', 'DESC')->get();
        $bap = new tmbap();
        $data['list_bap'] = $bap->get();
        $data['c_bap'] = "1";
        $this->load->vars($data);

        $js =  "$(document).ready(function() {
                        oTable = $('#sk').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Aktivasi Cetak Izin";
        $this->template->build('aktifsk_list', $this->session_info);
    }

    public function aktivasisk($id_daftar = NULL) {
        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($id_daftar);
        $surat = $permohonan->tmsk->get();
        $surat_sk = new tmsk();
        $update = $surat_sk->where('id', $surat->id)->update(array('c_status' => 2));

        if(! $update) {
            echo '<p>' . $update->error->string . '</p>';
        } else {
            redirect('permohonan/sk/aktifsk');
        }
    }

    public function update() {
        $surat = new tmsk();
        $surat->get_by_id($this->input->post('id_surat'));
        $surat->no_surat = $this->input->post('no_surat');
        $surat->tgl_surat = $this->input->post('tgl_surat');

        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($this->input->post('id_daftar'));
        $perizinan = $permohonan->trperizinan->get();
		
		/**Edited By Indra**/
        //$permohonan->d_berlaku_izin = $this->lib_date->set_date($this->input->post('tgl_surat'), $perizinan->v_berlaku_tahun * 365); //per tahun
        if($perizinan->v_berlaku_tahun !=''&&$perizinan->v_berlaku_tahun !=NULL&&$perizinan->v_berlaku_tahun!=0){//Jika ada masa berlakunya
			$akhir_masa_berlaku=$this->lib_date->modDate($this->input->post('tgl_surat'),"+".$perizinan->v_berlaku_tahun,$perizinan->v_berlaku_satuan);
			$permohonan->d_berlaku_izin=$akhir_masa_berlaku;
			$permohonan->save();
		}
		/*******************/
		
        $update = $surat->save();
        if($update) {
            redirect('permohonan/sk');
        }
    }

    
	/**
	* ModifiedAutor Indra
	* ModifiedDate 25th May 2013
	*/
    public function cetak($id_daftar = NULL,$trperizinan_id=NULL) {
        ####by Indra #####
		/*$nama_surat = "cetak_sk";
        $app_folder = new settings();
        $app_folder->where('name','app_folder')->get();
        $app_folder = $app_folder->value . "/";
        $app_city = new settings();
        $app_city->where('name','app_city')->get();
        $app_city = $app_city->value;
        $app_kan =  $this->settings->where('name', 'app_kantor')->get();*/
		###########
//        $app_sk = new settings();
//        $app_sk->where('name','app_sk')->get();
//        $app_sk = $app_sk->value;

        $petugas = 1; //1 -> Jabatan Penandatangan
        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($id_daftar);
        $perizinan = $permohonan->trperizinan->get();
        $surat_awal = $permohonan->tmsk->get();
        $sts_cetak = 1;
        if($surat_awal->id){
            $surat_sk = new tmsk();
            $surat_sk->get_by_id($surat_awal->id);
            $surat_sk->c_status = $sts_cetak;
            $tgl_skr = $this->lib_date->get_date_now();
//            $surat_sk->tgl_surat = $tgl_skr;
            $surat_sk->save();
            $pegawai = new tmpegawai();
            $pegawai->where('status', $petugas)->get();

            /* Input Relasi Tabel*/
            $perizinan = $permohonan->trperizinan->get();
//            $permohonan->d_berlaku_izin = $this->lib_date->set_date($tgl_skr, $perizinan->v_berlaku_tahun * 365);
            $permohonan->nip_ttd = $pegawai->nip;
            $permohonan->nama_ttd = strtoupper($pegawai->n_pegawai);
            $permohonan->save();
        }else{
            /* Input Data */
            $data_id = new tmsk();

            $data_id->select_max('id')->get();
            $data_id->get_by_id($data_id->id);

            $data_tahun = date("Y");
            //Per Tahun Auto Restart NoUrut
            if($permohonan->d_tahun === $data_tahun)
            $data_urut = $data_id->i_urut + 1;
            else $data_urut = 1;

            $i_urut = strlen($data_urut);
            for($i=4;$i>$i_urut;$i--){
                $data_urut = "0".$data_urut;
            }

            $data_izin = $perizinan->id;
            $i_izin = strlen($data_izin);
            for($i=3;$i>$i_izin;$i--){
                $data_izin = "0".$data_izin;
            }

            $data_bulan = $this->lib_date->set_month_roman(date("n"));

            $data_sk = "DP";
            $no_surat = $data_urut."/"
                    .$data_sk."/".$data_izin."/"
                    .$data_bulan."/".$data_tahun;
            $surat_sk = new tmsk();
            $surat_sk->c_status = $sts_cetak;
            $surat_sk->i_urut = $data_urut;
            $surat_sk->no_surat = $no_surat;
            $tgl_skr = $this->lib_date->get_date_now();
//            $surat_sk->tgl_surat = $tgl_skr;
            $surat_sk->c_cetak = 1;

            /* Input Relasi Tabel*/
            $pegawai = new tmpegawai();
            $pegawai->where('status', $petugas)->get();
            $perizinan = $permohonan->trperizinan->get();
//            $permohonan->d_berlaku_izin = $this->lib_date->set_date($tgl_skr, $perizinan->v_berlaku_tahun * 365); //per tahun
            $permohonan->nip_ttd = $pegawai->nip;
            $permohonan->nama_ttd = strtoupper($pegawai->n_pegawai);
            $permohonan->save();
            
            $surat_sk->save(array($permohonan, $pegawai));
        }

        $status_izin = $permohonan->trstspermohonan->get();

        $status_skr = $this->_status_pembuatan_izin; //Pembuatan Izin [Lihat Tabel trstspermohonan()]
        //$status_skrd = "10"; //SKRD [Lihat Tabel trstspermohonan()]
        //$id_status = "14"; //Mencetak Surat [Lihat Tabel trstspermohonan()]
        //$id_status2 = "13"; //Kasir [Lihat Tabel trstspermohonan()]
        //$id_status3 = "14"; //Penyerahan Izin [Lihat Tabel trstspermohonan()]
        $kelompok = $permohonan->trperizinan->trkelompok_perizinan->get();
        if($status_izin->id == $status_skr){
            $this->load->model('permohonan/trlangkah_perizinan');
            $langkah_perizinan = new trlangkah_perizinan();
            $next_status = $langkah_perizinan->nextStep($permohonan->trperizinan->trkelompok_perizinan->id, $status_izin->id);
            $this->__input_tracking_progress($permohonan->id, $status_skr, $next_status);
        }

        /*if($kelompok->id == 2 || $kelompok->id == 4){
            //if($status_izin->id == $status_skr || $status_izin->id == $status_skrd){
            ## Input Data Tracking Progress
                $sts_izin = new trstspermohonan();
                $sts_izin->get_by_id($status_skr);
                $data_status = new tmtrackingperizinan_trstspermohonan();
                $list_tracking = $permohonan->tmtrackingperizinan->get();
                if($list_tracking){
                    $tracking_id = 0;
                    foreach ($list_tracking as $data_track){
                        $data_status = new tmtrackingperizinan_trstspermohonan();
                        $data_status->where('tmtrackingperizinan_id', $data_track->id)
                        ->where('trstspermohonan_id', $sts_izin->id)->get();
                        if($data_status->tmtrackingperizinan_id){
                            $tracking_id = $data_status->tmtrackingperizinan_id;
                        }
                    }
                }
                $tracking_izin = new tmtrackingperizinan();
                $tracking_izin->get_by_id($tracking_id);
                //$tracking_izin->pendaftaran_id = $permohonan->pendaftaran_id;
                $tracking_izin->status = 'Update';
                $tracking_izin->d_entry = $this->lib_date->get_date_now();
                $tracking_izin->save();

                ##[Lihat Tabel trstspermohonan()]
                $tracking_izin2 = new tmtrackingperizinan();
                $tracking_izin2->pendaftaran_id = $permohonan->pendaftaran_id;
                $tracking_izin2->status = 'Insert';
                $tracking_izin2->d_entry_awal = $this->lib_date->get_date_now();
                $tracking_izin2->d_entry = $this->lib_date->get_date_now();
                $sts_izin2 = new trstspermohonan();
                $sts_izin2->get_by_id($id_status); //[Lihat Tabel trstspermohonan()]
                $sts_izin2->save($permohonan);
                $tracking_izin2->save($permohonan);
                $tracking_izin2->save($sts_izin2);

            //}
        }else{
            //if($status_izin->id == $status_skr){
                ##Input Data Tracking Progress##
                $sts_izin = new trstspermohonan();
                $sts_izin->get_by_id($status_skr);
                $data_status = new tmtrackingperizinan_trstspermohonan();
                $list_tracking = $permohonan->tmtrackingperizinan->get();
                if($list_tracking){
                    $tracking_id = 0;
                    foreach ($list_tracking as $data_track){
                        $data_status = new tmtrackingperizinan_trstspermohonan();
                        $data_status->where('tmtrackingperizinan_id', $data_track->id)
                        ->where('trstspermohonan_id', $sts_izin->id)->get();
                        if($data_status->tmtrackingperizinan_id){
                            $tracking_id = $data_status->tmtrackingperizinan_id;
                        }
                    }
                }
                $tracking_izin = new tmtrackingperizinan();
                $tracking_izin->get_by_id($tracking_id);
                //$tracking_izin->pendaftaran_id = $permohonan->pendaftaran_id;
                $tracking_izin->status = 'Update';
                $tracking_izin->d_entry = $this->lib_date->get_date_now();
                $tracking_izin->save();

                ##[Lihat Tabel trstspermohonan()]
                $tracking_izin2 = new tmtrackingperizinan();
                $tracking_izin2->pendaftaran_id = $permohonan->pendaftaran_id;
                $tracking_izin2->status = 'Insert';
                $tracking_izin2->d_entry_awal = $this->lib_date->get_date_now();
                $tracking_izin2->d_entry = $this->lib_date->get_date_now();
                $sts_izin2 = new trstspermohonan();
                $sts_izin2->get_by_id($id_status); //[Lihat Tabel trstspermohonan()]
                $sts_izin2->save($permohonan);
                $tracking_izin2->save($permohonan);
                $tracking_izin2->save($sts_izin2);

//            /}
        }*/
        //end edit
        //Status cetak SK
        $sk = new tmsk();
        $sk->get_by_id($surat_awal->id);
        $sk->c_cetak = $surat_awal->c_cetak + 1;
        $sk->save();
		
		######Comment by Indra #####
        ########Report diganti dengan Jasper##########
		/*$surat = $permohonan->tmsk->get();
        $pemohon = $permohonan->tmpemohon->get();
        $jenis_izin = $permohonan->trperizinan->get();
        //$petugas = $surat->tmpegawai->get();

        //path of the template file
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/'.$nama_surat.'.odt');
//        $odf->setImage('header', 'assets/css/'.$app_folder.'/images/dinas_1.jpg', '17.5', '3.5');
        $odf->setVars ('ttd', '');

         //logo
        $this->tr_instansi = new Tr_instansi();
        $logo = $this->tr_instansi->get_by_id(14);
        if($logo->value!=="")
        {
            $odf->setImage('logo', 'uploads/logo/' . $logo->value, '1.7', '1.7');
        }
        else
        {
            $odf->setVars('logo', ' ');
        }

        //badan
        $this->tr_instansi = new Tr_instansi();
        $nama_bdan = $this->tr_instansi->get_by_id(9);
        $odf->setVars('badan', strtoupper($nama_bdan->value));

        //telpon
        $this->tr_instansi = new Tr_instansi();
        $tlp = $this->tr_instansi->get_by_id(10);
        $odf->setVars('tlp', $tlp->value);

        //fax
        $this->tr_instansi = new Tr_instansi();
        $tlp = $this->tr_instansi->get_by_id(13);
        $odf->setVars('fax', $tlp->value);


        //fill the template with the variables
        $nama_izin = $jenis_izin->n_perizinan;
        $data_judul = $jenis_izin->c_judul;
        $odf->setVars('nama_izin', strtoupper($nama_izin));
        $odf->setVars('no_surat', $surat->no_surat);
        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($surat->tgl_surat));
        $odf->setVars('jabatan', $pegawai->n_jabatan);
        $odf->setVars('nama_pejabat', $pegawai->n_pegawai);
        $odf->setVars('nip_pejabat', $pegawai->nip);
         $odf->setVars('kantor', $app_kan->value);
        if($jenis_izin->c_foto == 1){
        $odf->setVars('ket_pemohon', "Tanda tangan pemegang");
        $odf->setVars('nama_pemohon', strtoupper($pemohon->n_pemohon));
        }else{
        $odf->setVars('ket_pemohon', "");
        $odf->setVars('nama_pemohon', "");
        }
        $wilayah = new trkabupaten();
        if($app_city !== '0'){
            $wilayah->get_by_id($app_city);
            //$kota = ucwords(strtolower($wilayah->n_kabupaten));
            $kota = $wilayah->ibukota;
            $odf->setVars('kota', $kota);
        }else{
            $kota = "..............";
            $odf->setVars('kota', $kota);
        }

        $gede_kota=strtoupper($wilayah->n_kabupaten);
        $kecil_kota=ucwords(strtolower($wilayah->n_kabupaten));
        $odf->setVars('kota4', $gede_kota);

        //alamat
        $this->tr_instansi = new Tr_instansi();
        $alamat = $this->tr_instansi->get_by_id(12);
        $odf->setVars('alamat', ucwords(strtolower($alamat->value)).' - '.$kecil_kota);


        //Head Ketetapan SK
        $listeArticles = array(
                array(	'property' => '',
                        'content' => 'Berdasarkan :',
                ),
        );
        $article = $odf->setSegment('articles1');
        foreach($listeArticles AS $element) {
                $article->titreArticle($element['property']);
                $article->texteArticle($element['content']);
                $article->merge();
        }
        $odf->mergeSegment($article);

        //Content Ketetapan SK
         $i = 1;
//        $izin_hukum = new trdasar_hukum_trperizinan();
//        $list_hukum = $izin_hukum->where('type', 0)
//                        ->where('trperizinan_id', $data1->id)->order_by('trdasar_hukum_id', 'ASC')->get(); //17 2
          $list_hukum = $this->getDatahukum($permohonan->trperizinan->id);


        if ($list_hukum) {
            foreach ($list_hukum as $hukum) {
                $dasar_hukum = new trdasar_hukum();
                if ($hukum->id) {
                    $data6 = $dasar_hukum->where('id', $hukum->trdasar_hukum_id)->get();
                    $desk = $data6->deskripsi;
                } else {
                    $desk = ' ';
//                      $i=' ';
                }


                $listeArticles = array(
                    array('property' => $i . '.',
                        'content' => $desk,
                    ),
                );

                $article = $odf->setSegment('articles2');
                foreach ($listeArticles AS $element2) {
                    $article->titreArticle($element2['property']);
                    $article->texteArticle($element2['content']);
                    $article->merge();
                }
                $i++;
            }
        } else {
            $desk = ' ';
            $listeArticles = array(
                array('property' => ' ',
                    'content' => $desk,
                ),
            );
            
            $article2 = $odf->setSegment('articles2');
            foreach ($listeArticles AS $element2) {
                $article2->titreArticle($element2['property']);
                $article2->texteArticle($element2['content']);
                $article2->merge();
            }
        }
        $odf->mergeSegment($article);

        //Head Property
        if($data_judul == "1") $head = "Mengizinkan";
        else $head = "Memberikan ".$nama_izin." kepada";
        $listeArticles = array(
                array(	'property' => '',
                        'content' => $head.' :',
                ),
        );
        $article = $odf->setSegment('articles3');
        foreach($listeArticles AS $element) {
                $article->titreArticle($element['property']);
                $article->texteArticle($element['content']);
                $article->merge();
        }
        $odf->mergeSegment($article);

        //Content Property
        
         $perizinan = new trperizinan();
        $perizinan->get_by_id($jenis_izin->id);
        $list_daftar = $permohonan->tmproperty_jenisperizinan->get();
        $lists = $perizinan->trproperty->include_join_fields()->where('c_type', 2)->order_by('c_parent_order', "asc")->get();

        $property = $odf->setSegment('property');
        foreach ($lists as $list) {
            //$property->nama($list->n_property);
            $children = $perizinan->trproperty->where('c_sk_id', 1)->where_join_field($perizinan, 'c_parent', $list->id)->include_join_fields()->order_by('c_order', "asc")->get();
            
            //added 11-04-2013
            //by mucktar
            $child_exist = false;
            //loop cek jika child ditemukan
            foreach($children as $child_){
                if($child_->id && ($list->id!==$child_->id)){
                    $child_exist = true;
                    break;
                }
            }
            
            //jika child ditemukan, set nama parent
            if($child_exist){
                $property->nama($list->n_property);
            }
            
            //end add
            
            foreach ($children as $child_) {
                if ($list->id !== $child_->id) {
                    $property->child->child($child_->n_property);
// ................................ Isi ...........................
                    if ($list_daftar->id) {
                        foreach ($list_daftar as $data_daftar) {
                            $entry_property = new tmproperty_jenisperizinan_trproperty();
                            $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                                    ->where('trproperty_id', $child_->id)->get();
                            $izin_property = new trperizinan_trproperty();
                            $izin_property->where('trperizinan_id', $jenis_izin->id)
                                    ->where('trproperty_id', $child_->id)->get();
                            if ($entry_property->tmproperty_jenisperizinan_id) {
                                $entry_daftar = new tmproperty_jenisperizinan();
                                $koefret = new trkoefesientarifretribusi();
                                $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);
                                $pil = $koefret->get_by_id($entry_daftar->k_tinjauan);
                                $data_koefisient = $entry_daftar->v_tinjauan;
                                $isilow = strtolower($pil->kategori . " " . $data_koefisient . " " . $izin_property->satuan);
                                $isi = ucwords($isilow);
                                $property->child->isi($isi);
                            }
                        }
                    }

                    if ($child_->join_c_retribusi_id === '1') {
                        $property->child->indeks("");
                    } else {
                        $property->child->indeks("");
                    }
                    $property->child->merge();
                }
            }
            $property->merge();
        }
        $odf->mergeSegment($property);

        //Head Property
        $listeArticles = array(
                array(	'property' => '',
                        'content' => 'Dengan ketentuan :',
                ),
        );
        $article = $odf->setSegment('articles5');
        foreach($listeArticles AS $element) {
                $article->titreArticle($element['property']);
                $article->texteArticle($element['content']);
                $article->merge();
        }
        $odf->mergeSegment($article);

        $i = 1;
        $list_ketentuan = $permohonan->trperizinan->trketetapan->get();
        foreach($list_ketentuan as $data){
            $listeArticles = array(
                    array(  'property' => $i.'.',
                            'content' => $data->n_ketetapan,
                    ),
            );
            $article = $odf->setSegment('articles6');
            foreach($listeArticles AS $element) {
                    $article->titreArticle($element['property']);
                    $article->texteArticle($element['content']);
                    $article->merge();
            }
            $i++;
        }
        if($i == '1'){
            $listeArticles = array(
                    array(  'property' => '',
                            'content' => '',
                    ),
            );
            $article = $odf->setSegment('articles6');
            foreach($listeArticles AS $element) {
                    $article->titreArticle($element['property']);
                    $article->texteArticle($element['content']);
                    $article->merge();
            }
        }
        $odf->mergeSegment($article);

        //Content Retribusi
        $data_bap = $permohonan->tmbap->get();
        $retribusi = $data_bap->nilai_bap_awal;
        $keringanan = $permohonan->tmkeringananretribusi->get();
        if ($keringanan->id)
        {
            $nilai_ret1 = ($keringanan->v_prosentase_retribusi * 0.01) * $retribusi;
            $nilai_ret = $retribusi-$nilai_ret1;
        }
         else
         {
            $nilai_ret = $retribusi;
         }
            
        $odf->setVars('nor', $i.'. ');
        if($nilai_ret) $nilair = $this->terbilang->nominal($nilai_ret, 2);
        else $nilair = "0";
        $izin_kelompok = $jenis_izin->trkelompok_perizinan->get();

        //jika metode perhitungan manual
         $izin =$jenis_izin->id ;
        $this->perizinan = new trperizinan();
        $perizinan = $this->perizinan->get_by_id($izin);
        $property = $perizinan->trretribusi->get();

        if ($property->m_perhitungan == "1") {
            $prop = '45';
            $prop_nilai = $this->getTinjauan($id_daftar, $izin, $prop);
            
             if($izin_kelompok->id == 4) {
            if (isset($prop_nilai->v_tinjauan)) {
                if ($keringanan->id)
                {
                    $nilai_retM = ($keringanan->v_prosentase_retribusi * 0.01) * $prop_nilai->v_tinjauan;
                    $tot = $prop_nilai->v_tinjauan-$nilai_retM; 
                }
                else
                {
                    $tot = $prop_nilai->v_tinjauan;
                }
              $ket_retribusi = "Wajib membayar retribusi sebesar Rp. ".$tot;
            } else {
                $ket_retribusi = "Wajib membayar retribusi sebesar Rp. ______";
            }
       
            }
        else {
            $ket_retribusi = "Proses penerbitan izin ini tidak dikenai retribusi";
             }
        

            
        } else {
            if($izin_kelompok->id == 4) {
            $ket_retribusi = "Wajib membayar retribusi sebesar Rp. ".$nilair;
            //$ret = $permohonan->$perizinan->trretribusi->v_retribusi;
//            if ($ret=="")
//            {
//                $ret = "0";
//            }
//            $ket_retribusi = "Wajib membayar retribusi sebesar Rp. ".$ret;
             }
        else {
            $ket_retribusi = "Proses penerbitan izin ini tidak dikenai retribusi";
             }
            }
  $odf->setVars('retribusi', $ket_retribusi);
// -------------------| |----------------------
        
       
        $i++;

        //Content Masa Berlaku
        $berlaku = $permohonan->d_berlaku_izin;
        if($perizinan->c_berlaku == 1){
            $odf->setVars('nob', $i.'. ');
            if($berlaku){
                if($berlaku != '0000-00-00') $nilaib = $this->lib_date->mysql_to_human($berlaku);
                else $nilaib = "..............";
            }
            else $nilaib = "..............";
            if($perizinan->id == 2 || $perizinan->id == 3 || $perizinan->id == 88)
            $masa_berlaku = $perizinan->n_perizinan." ini berlaku sepanjang bangunan, pemilik dan fungsi bangunan tidak mengalami perubahan.";
            else $masa_berlaku = $perizinan->n_perizinan.' ini berlaku sampai dengan '.$nilaib;
        }else{
            $odf->setVars('nob', '');
            $masa_berlaku = '';
        }
        $odf->setVars('masaberlaku', $masa_berlaku);

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql2($u_ser);
//      $jam = date("H:i:s A");
        $p = $this->db->query("call log ('Pembuatan Izin','Cetak Surat Izin ".$permohonan->pendaftaran_id."','".$tgl."','".$u_ser."')");



        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile('surat_izin_'.$no_daftar.'.odt');*/
		###################################################
//		$this->load->helper('url');
		$this->rat->log('Cetak',9,$this->session->userdata('id_auth'),$id_daftar);
		redirect('report_generator/cetak/IZIN/'.$id_daftar.'/'.$trperizinan_id);
    }

    public function cetak_archive($id_daftar = NULL) {
        $nama_surat = "cetak_sk";
        $app_folder = new settings();
        $app_folder->where('name','app_folder')->get();
        $app_folder = $app_folder->value . "/";
        $app_city = new settings();
        $app_city->where('name','app_city')->get();
        $app_city = $app_city->value;

        $petugas = 1; //1 -> Jabatan Penandatangan
        $pegawai = new tmpegawai();
        $pegawai->where('status', $petugas)->get();
        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($id_daftar);
        $perizinan = $permohonan->trperizinan->get();
        $surat_awal = $permohonan->tmsk->get();
        $sts_cetak = 1;

        $surat = $permohonan->tmsk->get();
        $pemohon = $permohonan->tmpemohon->get();
        $jenis_izin = $permohonan->trperizinan->get();

        //path of the template file
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/'.$nama_surat.'.odt');
        $odf->setImage('header', 'assets/css/'.$app_folder.'/images/dinas_1.jpg', '17.5', '3.5');
        if($permohonan->file_ttd)
        $odf->setImage('ttd', 'assets/upload/ttd/'.$permohonan->file_ttd, '2.5', '2.5');
        else $odf->setVars ('ttd', '');

        //fill the template with the variables
        $nama_izin = $jenis_izin->n_perizinan;
        $data_judul = $jenis_izin->c_judul;
        $odf->setVars('nama_izin', strtoupper($nama_izin));
        $odf->setVars('no_surat', $surat->no_surat);
        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($surat->tgl_surat));
        $odf->setVars('jabatan', $pegawai->n_jabatan);
        $odf->setVars('nama_pejabat', $pegawai->n_pegawai);
        $odf->setVars('nip_pejabat', $pegawai->nip);
//        $odf->setVars('jabatan', $pegawai->n_jabatan);
//        $odf->setVars('nama_pejabat', $permohonan->nama_ttd);
//        $odf->setVars('nip_pejabat', $permohonan->nip_ttd);
        if($jenis_izin->c_foto == 1){
        $odf->setVars('ket_pemohon', "Tanda tangan pemegang");
        $odf->setVars('nama_pemohon', strtoupper($pemohon->n_pemohon));
        }else{
        $odf->setVars('ket_pemohon', "");
        $odf->setVars('nama_pemohon', "");
        }
        $wilayah = new trkabupaten();
        if($app_city !== '0'){
            $wilayah->get_by_id($app_city);
            //$kota = ucwords(strtolower($wilayah->n_kabupaten));
            $kota = $wilayah->n_kabupaten;
            $odf->setVars('kota', $kota);
        }else{
            $kota = "..............";
            $odf->setVars('kota', $kota);
        }

        //Head Ketetapan SK
        $listeArticles = array(
                array(	'property' => '',
                        'content' => 'Berdasarkan :',
                ),
        );
        $article = $odf->setSegment('articles1');
        foreach($listeArticles AS $element) {
                $article->titreArticle($element['property']);
                $article->texteArticle($element['content']);
                $article->merge();
        }
        $odf->mergeSegment($article);

        //Content Ketetapan SK
        $list_ketetapan = $permohonan->trperizinan->trdasar_hukum->order_by('id', 'asc')->get();
        $i = 1;
        foreach($list_ketetapan as $data){
            $rel = new trdasar_hukum_trperizinan();
            $rel->where(array(
                'trdasar_hukum_id' => $data->id,
                'trperizinan_id' => $permohonan->trperizinan->id
            ))->get();
            if ($rel->type !== "1") {
            $listeArticles = array(
                    array(  'property' => $i.'.',
                            'content' => $data->deskripsi,
                    ),
            );
            $article = $odf->setSegment('articles2');
            foreach($listeArticles AS $element) {
                    $article->titreArticle($element['property']);
                    $article->texteArticle($element['content']);
                    $article->merge();
            }
            $i++;
            }
        }
        if($i == '1'){
            $listeArticles = array(
                    array(  'property' => '',
                            'content' => '',
                    ),
            );
            $article = $odf->setSegment('articles2');
            foreach($listeArticles AS $element) {
                    $article->titreArticle($element['property']);
                    $article->texteArticle($element['content']);
                    $article->merge();
            }
        }
        $odf->mergeSegment($article);

        //Head Property
        if($data_judul == "1") $head = "Mengizinkan";
        else $head = "Memberikan ".$nama_izin." kepada";
        $listeArticles = array(
                array(	'property' => '',
                        'content' => $head.' :',
                ),
        );
        $article = $odf->setSegment('articles3');
        foreach($listeArticles AS $element) {
                $article->titreArticle($element['property']);
                $article->texteArticle($element['content']);
                $article->merge();
        }
        $odf->mergeSegment($article);

        //Content Property
        $i = 1;
        $list_property = $permohonan->trperizinan->trproperty->order_by('c_parent_order asc, c_order asc')->get();
        $list_content = $permohonan->tmproperty_jenisperizinan->get();
        foreach($list_property as $data){
           $property_satuan = new trperizinan_trproperty();
           $property_satuan->where('trproperty_id', $data->id)->get();
            if($list_content->id){
                foreach ($list_content as $data_daftar){
                    $entry_property = new tmproperty_jenisperizinan_trproperty();
                    $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                    ->where('trproperty_id', $data->id)->get();
                    if($entry_property->tmproperty_jenisperizinan_id){
                        $entry_daftar = new tmproperty_jenisperizinan();
                        $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);

                        $kelompok = $perizinan->trkelompok_perizinan->get();
                        if($kelompok->id == 2 || $kelompok->id == 4){
                            $data_entry = $entry_daftar->v_tinjauan;
                            $id_koefisien = $entry_daftar->k_tinjauan;
                        }else{
                            $data_entry = $entry_daftar->v_property;
                            $id_koefisien = $entry_daftar->k_property;
                        }

                        $izin_property = new trperizinan_trproperty();
                        $izin_property->where('trperizinan_id', $jenis_izin->id)
                        ->where('trproperty_id', $data->id)->get();
                        $id_sk = $izin_property->c_sk_id;
                        if($data_entry){
                        if($id_sk == '1'){
                            if($data->c_type == '1'){
                                $data_koefisien = new trkoefesientarifretribusi();
                                $data_koefisien->get_by_id($id_koefisien);
                                $no = '';
                                $data_property = $data->n_property;
//                                if($data_entry) $all_entry = $data_koefisien->kategori.' ('.$data_entry.')';
//                                else $all_entry = $data_koefisien->kategori;
                                if($data_entry) $all_entry = $data_entry;
                                else $all_entry = '';
                                $titik = ":";
                                $i++;
                            }else if($data->c_type == '2'){
                                $no = '';
                                $data_property = $data->n_property;
                                $titik = "";
                                $all_entry = "";
                            }else{
                                $no = '';
                                $data_property = $data->n_property;
                                $titik = ":";
                                $all_entry = $data_entry." ".$property_satuan->satuan;
                                $i++;
                            }
                            $listeArticles = array(
                                    array(  'no' => '',
                                            'property' => $data_property,
                                            'titik' => $titik,
                                            'content' => $all_entry,
                                    ),
                            );
                            $article = $odf->setSegment('articles4');
                            foreach($listeArticles AS $element) {
                                    $article->titreArticle($element['no']);
                                    $article->texteArticle2($element['property']);
                                    $article->texteArticle3($element['titik']);
                                    $article->texteArticle($element['content']);
                                    $article->merge();
                            }
                        }}
                    }
                }
            }
//            if(empty($data_entry)) $data_entry = '';
//            if(empty($id_koefisien)) $id_koefisien = '';
        }
        if($i == '1'){
            $listeArticles = array(
                    array(  'no' => '',
                            'property' => '',
                            'titik' => '',
                            'content' => '',
                    ),
            );
            $article = $odf->setSegment('articles4');
            foreach($listeArticles AS $element) {
                        $article->titreArticle($element['no']);
                        $article->texteArticle2($element['property']);
                        $article->texteArticle3($element['titik']);
                        $article->texteArticle($element['content']);
                    $article->merge();
            }
        }
        $odf->mergeSegment($article);

        //Head Property
        $listeArticles = array(
                array(	'property' => '',
                        'content' => 'Dengan ketentuan :',
                ),
        );
        $article = $odf->setSegment('articles5');
        foreach($listeArticles AS $element) {
                $article->titreArticle($element['property']);
                $article->texteArticle($element['content']);
                $article->merge();
        }
        $odf->mergeSegment($article);

        $i = 1;
        $list_ketentuan = $permohonan->trperizinan->trketetapan->get();
        foreach($list_ketentuan as $data){
            $listeArticles = array(
                    array(  'property' => $i.'.',
                            'content' => $data->n_ketetapan,
                    ),
            );
            $article = $odf->setSegment('articles6');
            foreach($listeArticles AS $element) {
                    $article->titreArticle($element['property']);
                    $article->texteArticle($element['content']);
                    $article->merge();
            }
            $i++;
        }
        if($i == '1'){
            $listeArticles = array(
                    array(  'property' => '',
                            'content' => '',
                    ),
            );
            $article = $odf->setSegment('articles6');
            foreach($listeArticles AS $element) {
                    $article->titreArticle($element['property']);
                    $article->texteArticle($element['content']);
                    $article->merge();
            }
        }
        $odf->mergeSegment($article);

        //Content Retribusi
        $data_bap = $permohonan->tmbap->get();
        $retribusi = $data_bap->nilai_retribusi;
        $keringanan = $permohonan->tmkeringananretribusi->get();
        if ($keringanan->id)
            $nilai_ret = ($keringanan->v_prosentase_retribusi * 0.01) * $retribusi;
        else
            $nilai_ret = $retribusi;
        $odf->setVars('nor', $i.'. ');
        if($nilai_ret) $nilair = $this->terbilang->nominal($nilai_ret, 2);
        else $nilair = "0";
        $izin_kelompok = $jenis_izin->trkelompok_perizinan->get();
        if($izin_kelompok->id == 4) $ket_retribusi = "Wajib membayar retribusi sebesar Rp. ".$nilair;
        else $ket_retribusi = "Proses penerbitan izin ini tidak dikenai retribusi";
        $odf->setVars('retribusi', $ket_retribusi);
        $i++;

        //Content Masa Berlaku
        $berlaku = $permohonan->d_berlaku_izin;
        if($perizinan->c_berlaku == 1){
            $odf->setVars('nob', $i.'. ');
            if($berlaku){
                if($berlaku != '0000-00-00') $nilaib = $this->lib_date->mysql_to_human($berlaku);
                else $nilaib = "..............";
            }
            else $nilaib = "..............";
            if($perizinan->id == 2 || $perizinan->id == 3 || $perizinan->id == 88)
            $masa_berlaku = $perizinan->n_perizinan." ini berlaku sepanjang bangunan, pemilik dan fungsi bangunan tidak mengalami perubahan.";
            else $masa_berlaku = $perizinan->n_perizinan.' ini berlaku sampai dengan '.$nilaib;
        }else{
            $odf->setVars('nob', '');
            $masa_berlaku = '';
        }
        $odf->setVars('masaberlaku', $masa_berlaku);

        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile('surat_izin_'.$no_daftar.'.odt');
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

     public function getDatahukum($id)
    {
        $query = "select a.id,a.trdasar_hukum_id,a.trperizinan_id
        from trdasar_hukum_trperizinan as a
        inner join trperizinan as b on b.id=a.trperizinan_id
        inner join trdasar_hukum as c on c.id = a.trdasar_hukum_id
        where a.trperizinan_id = '".$id."' and c.type=0
	";
        $hasil = $this->db->query($query);
        return $hasil->result();

    }

      public function getTinjauan($daftar, $izin, $jnsProp) {
        $query = "select a.id,a.v_property,a.v_tinjauan from tmproperty_jenisperizinan as a
        inner join tmpermohonan_tmproperty_jenisperizinan as b on b.tmproperty_jenisperizinan_id=a.id
        inner join tmpermohonan as c on c.id=b.tmpermohonan_id
        inner join tmproperty_jenisperizinan_trproperty as d on d.tmproperty_jenisperizinan_id=a.id
        inner join trproperty as e on e.id=d.trproperty_id
        where c.id='".$daftar."' and e.id='".$jnsProp."'";
        $hasil = $this->db->query($query);
        return $hasil->row();
    }

}
