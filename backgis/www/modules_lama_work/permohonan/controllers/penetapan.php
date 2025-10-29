<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of penjadwalan
 *
 * @author Eva
 */
class Penetapan extends WRC_AdminCont {

    private $_status_penetapan = 7;

    public function __construct() {
		parent::__construct();
		
        $this->sk = new tmpermohonan();
        $this->bap = new tmbap();
        $this->propertyizin = new tmproperty_jenisperizinan();
        $this->koefisien = new trkoefesientarifretribusi();
        $this->perizinan = new trperizinan();
        $this->pemohon = new tmpemohon();
        $this->retribusi = new user_auth();

		/*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->retribusi = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '13') {
                $enabled = TRUE;
                $this->retribusi = new user_auth();
            }
        }
		
        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index($sALL=0) {
//        $permohonan = new tmpermohonan();
//        $user = new user();
//        $izin = new trperizinan();
//
//        $user->where('username', $this->session->userdata('username'))->get();
//        $data['list_izin'] = $izin->where_related($user)->get();
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
        
        $username = new user();
        $username->where('username', $this->session->userdata('username'))->get();
        
		if($this->__is_administrator()){
			$query_filter_user="";
			$query_join_user="";
		}else{
			$query_join_user=" INNER JOIN trperizinan_user AS J ON J.trperizinan_id = C.id ";
			$query_filter_user=" AND J.user_id = '".$username->id."' ";
		}

        $status_penetapan = $this->_status_penetapan;

		if($sALL==1){
			$query = "SELECT DISTINCT A.id, A.pendaftaran_id, A.d_terima_berkas, A.d_survey,
		        A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
		        C.id idizin, C.n_perizinan, E.n_pemohon,
		        G.id idjenis, G.n_permohonan,
		        A.c_penetapan, I.status_bap, A.c_izin_selesai,
		        (
                    SELECT COUNT(tim_teknis.id)
                    FROM tmbap_tmpermohonan
                    INNER JOIN tmbap ON tmbap_tmpermohonan.tmbap_id = tmbap.id
                    INNER JOIN tim_teknis ON tim_teknis.id = tmbap.tim_teknis_id
                    WHERE tmpermohonan_id = A.id
                    AND (tim_teknis.rekomendasi IS NULL OR tim_teknis.rekomendasi IN ('Tidak','Catatan','Pembahasan'))
                ) AS tidak_direkomendasi
		        FROM tmpermohonan as A
		        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
		        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
		        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
		        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
		        INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
		        INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
			LEFT JOIN tmbap_tmpermohonan H ON A.id = H.tmpermohonan_id
			LEFT JOIN tmbap I ON H.tmbap_id = I.id
		        ".$query_join_user."
				WHERE A.c_pendaftaran = 1
		        AND A.c_izin_dicabut = 0
		        AND A.c_izin_selesai = 1".$query_filter_user."
		        AND A.d_terima_berkas between '$tgla' and '$tglb'
		        AND A.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})
                AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
		        AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                    INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                    WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = {$status_penetapan})>0
		        order by A.id DESC";
		}else{
			$query = "SELECT DISTINCT A.id, A.pendaftaran_id, A.d_terima_berkas, A.d_survey,
		        A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
		        C.id idizin, C.n_perizinan, E.n_pemohon,
		        G.id idjenis, G.n_permohonan,
		        A.c_penetapan, I.status_bap, A.c_izin_selesai,
		        (
                    SELECT COUNT(tim_teknis.id)
                    FROM tmbap_tmpermohonan
                    INNER JOIN tmbap ON tmbap_tmpermohonan.tmbap_id = tmbap.id
                    INNER JOIN tim_teknis ON tim_teknis.id = tmbap.tim_teknis_id
                    WHERE tmpermohonan_id = A.id
                    AND (tim_teknis.rekomendasi IS NULL OR tim_teknis.rekomendasi IN ('Tidak','Catatan','Pembahasan'))
                ) AS tidak_direkomendasi
		        FROM tmpermohonan as A
		        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
		        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
		        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
		        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
		        INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
		        INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
			LEFT JOIN tmbap_tmpermohonan H ON A.id = H.tmpermohonan_id
			LEFT JOIN tmbap I ON H.tmbap_id = I.id
		        ".$query_join_user."
				WHERE A.c_pendaftaran = 1
		        AND A.c_izin_dicabut = 0
		        AND A.c_izin_selesai = 0".$query_filter_user."
		        AND A.d_terima_berkas between '$tgla' and '$tglb'
		        AND A.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})
                AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
		        AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                    INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                    WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = {$status_penetapan})>0
		        order by A.id DESC";
        }

        $data['list'] = $query;
		$data['sALL']=$sALL;
        $data['jenis_izin'] = "";
        $data['jenis_permohonan'] = "";
        $data['namapemohon'] = "";
        $data['tanggalpermohonan'] = "";
        $data['save_method'] = "save";
        $data['id'] = "";

        $this->load->vars($data);

        $js = "
                $(document).ready(function() {
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
        $this->session_info['page_name'] = "Data Penetapan Izin";
        $this->template->build('penetapan_list', $this->session_info);
    }

    public function viewSK($id=NULL, $idizin=NULL) {
        $permohonan = new tmpermohonan();
        $perizinan = new trperizinan();
        $property = new trproperty();
        $jenisproperty = new tmproperty_jenisperizinan();
        $koefesientarifretribusi = new trkoefesientarifretribusi();
        $bap = new tmbap();
        $retribusi = new trretribusi();

        $permohonan->where('id', $id)->get();

        $permohonan->trperizinan->get();
        $permohonan->tmpemohon->get();
        $permohonan->tmperusahaan->get();
        $bap = $permohonan->tmbap->get();
        $p_pemohon = $permohonan->tmpemohon->get();
        $p_kelurahan = $p_pemohon->trkelurahan->get();
        $p_kecamatan = $p_kelurahan->trkecamatan->get();
        $p_kabupaten = $p_kecamatan->trkabupaten->get();
        $p_prov = $p_kabupaten->trpropinsi->get();

        $permohonan->$perizinan->where('id', $idizin)->get();
        $permohonan->$perizinan->$retribusi->get();//where('perizinan_id', $idizin)->get();

        $k_property = $permohonan->$perizinan->$property->$jenisproperty->k_property;
        $koefesientarifretribusi->where('id', $k_property)->get();

        $data['list'] = $permohonan->$perizinan->trproperty->order_by('c_parent_order asc, c_order asc')->get();
        $data['list_daftar'] = $permohonan->tmproperty_jenisperizinan->where('entry_flag',3)->get();
        $data['waktu_awal'] = $this->lib_date->get_date_now();
        $data['id'] = $permohonan->id;
        $data['idpemohon'] = $permohonan->tmpemohon->id;
        $data['idjenis'] = $permohonan->trperizinan->id;
        $data['jenislayanan'] = $permohonan->trperizinan->n_perizinan;
        $data['nopendaftaran'] = $permohonan->pendaftaran_id;
        $data['namapemohon'] = $permohonan->tmpemohon->n_pemohon;
        $data['alamatpemohon'] = $p_pemohon->a_pemohon . ', ' . $p_kelurahan->n_kelurahan . ', ' . $p_kecamatan->n_kecamatan . ', ' .
                                $p_kabupaten->n_kabupaten . ', ' . $p_prov->n_propinsi;
        $data['namaperusahaan'] = $permohonan->tmperusahaan->n_perusahaan;
        $data['m_hitung'] = $permohonan->trperizinan->$retribusi->m_perhitungan;
        $data['hitManualRet'] = $this->sqlRet($permohonan->pendaftaran_id);

        $data['tglperiksa'] = $permohonan->d_survey;
        $data['id_bap'] = $bap->id;
        $data['nosk'] = $bap->bap_id;
        $data['pesan'] = $bap->c_pesan;
        $data['status'] = $bap->status_bap;
//        $data['ditetapkan'] = $bap->c_penetapan;
        $data['ditetapkan'] = $permohonan->c_penetapan;

         $data['retribusi'] = $bap->nilai_retribusi;

        //$data['retribusi'] = $permohonan->trperizinan->$retribusi->v_retribusi;

        //cek data
        
        $index = $koefesientarifretribusi->index_kategori;
//        $data['retribusi'] = $permohonan->$perizinan->$retribusi->v_retribusi;

        $data['indexcba'] = $index;
        $data['xx'] = $idizin;
        $data['yy'] = $k_property;
        //

        $js_date = "
            $(function() {
                $(\"#bap\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
            ";
        $this->template->set_metadata_javascript($js_date);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Penetapan Izin";
        $this->template->build('penetapan_edit', $this->session_info);
    }

    public function viewSK2($id=NULL, $idizin=NULL) {
        $permohonan = new tmpermohonan();
        $perizinan = new trperizinan();
        $property = new trproperty();
        $jenisproperty = new tmproperty_jenisperizinan();
        $koefesientarifretribusi = new trkoefesientarifretribusi();
        $bap = new tmbap();
        $retribusi = new trretribusi();

        $permohonan->where('id', $id)->get();

        $permohonan->trperizinan->get();
        $permohonan->tmpemohon->get();
        $permohonan->tmperusahaan->get();
        $bap = $permohonan->tmbap->get();

        $permohonan->$perizinan->where('id', $idizin)->get();
        $permohonan->$perizinan->$retribusi->get();//where('perizinan_id', $idizin)->get();

        $k_property = $permohonan->$perizinan->$property->$jenisproperty->k_property;
        $koefesientarifretribusi->where('id', $k_property)->get();

        $data['list'] = $permohonan->$perizinan->trproperty->order_by('c_parent_order asc, c_order asc')->get();
        $data['list_daftar'] = $permohonan->tmproperty_jenisperizinan->get();
        $data['list_klasifikasi'] = $permohonan->tmproperty_klasifikasi->get();
        $data['list_prasarana'] = $permohonan->tmproperty_prasarana->get();

        $data['waktu_awal'] = $this->lib_date->get_date_now();
        $data['id'] = $permohonan->id;
        $data['idpemohon'] = $permohonan->tmpemohon->id;
        $data['idjenis'] = $permohonan->trperizinan->id;
        $data['jenislayanan'] = $permohonan->trperizinan->n_perizinan;
        $data['nopendaftaran'] = $permohonan->pendaftaran_id;
        $data['namapemohon'] = $permohonan->tmpemohon->n_pemohon;
        $data['alamatpemohon'] = $permohonan->tmpemohon->a_pemohon;
        $data['namaperusahaan'] = $permohonan->tmperusahaan->n_perusahaan;

        $data['tglperiksa'] = $permohonan->d_survey;
        $data['id_bap'] = $bap->id;
        $data['nosk'] = $bap->bap_id;
        $data['pesan'] = $bap->c_pesan;
        $data['status'] = $bap->status_bap;
        $data['ditetapkan'] = $bap->c_penetapan;
        //$data['nilai_retribusi'] = $bap->nilai_retribusi;
        $data['m_hitung'] = $permohonan->trperizinan->$retribusi->m_perhitungan;
        $data['hitManualRet'] = $this->sqlRet($permohonan->pendaftaran_id);

        //cek data

        $index = $koefesientarifretribusi->index_kategori;
        $data['retribusi'] = $permohonan->$perizinan->$retribusi->v_retribusi;
        

        $data['indexcba'] = $index;
        $data['xx'] = $idizin;
        $data['yy'] = $k_property;
        //

        $js_date = "
            $(function() {
                $(\"#bap\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
            ";
        $this->template->set_metadata_javascript($js_date);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Penetapan Izin";
        $this->template->build('penetapan_edit2', $this->session_info);
    }

    public function view($idjenisizin=NULL, $nopendaftaran=NULL, $idpemohon=NULL) {
        $permohonan = new tmpermohonan();
        $pemohon = new tmpemohon();

        $data['list'] = $permohonan->where('pendaftaran_id', $this->input->post('nomorpendaftaran'))->get();

        $data['idjenisizin'] = $idjenisizin;
        $data['nopendaftaran'] = $nopendaftaran;
        $data['idpemohon'] = $idpemohon;

        $this->load->vars($data);

        $js = "
                $(document).ready(function() {
                        oTable = $('#sk').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Data Penetapan Izin";
        $this->template->build('bap', $this->session_info);
    }

    public function edit() {
        $data['list'] = $this->sk->getPerizinan();
        $this->sk->where('id_pemohon', $id_pemohon);
        $this->sk->getPerizinan();
        $data['save_method'] = "save";
        $data['view'] = "index";
        $this->load->vars($data);
        $this->session_info['page_name'] = "Entry Pendataan";
        $this->template->build('bapbap', $this->session_info);
    }

    /**
     * Not yet in use, because of some limit of dataTables
     */
    public function datalist() {

        $this->sk->get();
        $this->sk->set_json_content_type();
        echo $this->sk->json_for_data_table();
    }

    /*
     * Save and update for manipulating data.
     */

    public function save() {
	
        $permohonan = new tmpermohonan();
        $id_permohonan = $this->input->post('id');
        $permohonan->get_by_id($id_permohonan);
        $perizinan = $permohonan->trperizinan->get();
        $nama_izin = $perizinan->n_perizinan;
        $id_kelompok_izin = $perizinan->trkelompok_perizinan->id;
        $cek = $this->input->post('id_bap');

        $bap = new tmbap();
        $bap->get_by_id($cek);
        $status_bap = $this->input->post('status');
        $kelompok = $perizinan->trkelompok_perizinan->get();
        $no_pendaftaran = $permohonan->pendaftaran_id;
        $pemohon = $permohonan->tmpemohon->get();
        $tgl_skr = $this->lib_date->get_date_now();
        $status_skr = $this->_status_penetapan; //Penetapan Izin [Lihat Tabel trstspermohonan()]
        
        //if($bap->c_penetapan !== "1"){
        //if($permohonan->c_penetapan != 1 && $status_bap==1){
        switch($permohonan->c_penetapan){
            case 0://Jika belum ditetapkan atau ditolak sebelumnya
                switch($status_bap){
                    case 1://Jika Diizinkan
//                        if($permohonan->c_penetapan != 1){
                            $permohonan->c_penetapan = 1;//Diizinkan
                            $permohonan->save();

                            $tgl_awal = $this->input->post('waktu_awal');
                            $bap->status_bap = $status_bap;
                            $status_izin = $permohonan->trstspermohonan->get();

                            /***Edited by Indra***/

                            //$status_pembuatan_izin = 17;//[Lihat Tabel trstspermohonan]
                            //$status_skrd = 10;//Menetapkan retribusi dan mencetak SKRD

                            //if($status_bap === "1") $id_status = "8"; //Surat Diizinkan
                            /*if($status_bap === "1"){
                                if(in_array($id_kelompok_izin, $this->__get_izin_dengan_tarif())){ //Jika termasuk izin bertarif
                                    $id_status = $status_skrd;
                                }else{
                                    $id_status = $status_pembuatan_izin;
                                }
                            }else {
                                $id_status = "9";
                            }*/

                            $this->load->model('permohonan/trlangkah_perizinan');
                            $langkah_perizinan = new trlangkah_perizinan();
                            $id_status = $langkah_perizinan->nextStep($id_kelompok_izin, $status_izin->id);

                            $cek_status_untuk_generate_nomor=10;//Jika $id_status tidak sama dengan ini maka nomor tidak digenerate
                            /********************/

                            if($status_izin->id == $status_skr){
                                /* Input Data Tracking Progress */
                                /*$sts_izin = new trstspermohonan();
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

                                $this->__input_tracking_progress($id_permohonan, $status_skr, $id_status);

                                $petugas = 1; //1 -> Jabatan Penandatangan
                                $tgl_skr = $this->lib_date->get_date_now();
                                $data_tahun = date("Y");

                                //if($id_status == $cek_status_untuk_generate_nomor){
                                /* Input Data */
                                $data_id = new tmsk();

                                $data_id->select_max('id')->get();
                                $data_id->get_by_id($data_id->id);

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

                                /*START Ambil Setting Report Component*/
                                $trperizinan_id=$perizinan->id;
                                $this->load->model('report_component/Report_component_model');
                                $this->report_component_model=new Report_component_model();
                                $setting_component_izin=$this->report_component_model->get_report_component($this->report_component_model->kode_izin,$trperizinan_id, $id_permohonan);

                                /*END Ambil Setting Report Component*/

                                $data_sk = "DP";
                                /*START Ambil nomor Surat Izin dari setting jika ada*/
                                if(isset($setting_component_izin['format_nomor']) &&
                                    $setting_component_izin['format_nomor']!=''){
                                    $no_surat=$setting_component_izin['format_nomor'];
                                }else{//Jika tidak ada, maka gunakan penomoran yang lama
                                    $no_surat = $data_urut."/"
                                        .$data_sk."/".$data_izin."/"
                                        .$data_bulan."/".$data_tahun;
                                }
                                /*END Ambil nomor Surat Izin dari setting jika ada*/

                                $surat_sk = new tmsk();
                                $surat_sk->c_status = 1;
                                $surat_sk->i_urut = $data_urut;
                                $surat_sk->no_surat = $no_surat;
                                $surat_sk->tgl_surat = $tgl_skr;

                                /* Input Relasi Tabel*/
                                $pegawai = new tmpegawai();
                                $pegawai->where('status', $petugas)->get();
//                    $perizinan = $permohonan->trperizinan->get();
//                    $permohonan->d_berlaku_izin = $this->lib_date->set_date($tgl_skr, $perizinan->v_berlaku_tahun * 365);

                                /**Edited by Indra**/
                                //$permohonan->d_berlaku_izin = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")+$perizinan->v_berlaku_tahun));
                                if($perizinan->v_berlaku_tahun !=''&&$perizinan->v_berlaku_tahun !=NULL&&$perizinan->v_berlaku_tahun!=0){//Jika ada masa berlakunya
                                    $akhir_masa_berlaku=$this->lib_date->modDate(date("Y-m-d"),"+".$perizinan->v_berlaku_tahun,$perizinan->v_berlaku_satuan);
                                    //$akhir_masa_berlaku=$this->lib_date->modDate($akhir_masa_berlaku,"-1","hari");
                                    $permohonan->d_berlaku_izin=$akhir_masa_berlaku;//Masa berlaku izin dihitung dari tanggal penetapannya
                                }
                                /*******************/

                                $permohonan->nip_ttd = $pegawai->nip;
                                $permohonan->nama_ttd = strtoupper($pegawai->n_pegawai);
//                    $permohonan->d_berlaku_keputusan = $this->lib_date->set_date($tgl_skr, 365); //per tahun
                                $permohonan->d_berlaku_keputusan = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-1, date("Y")+1));
                                $permohonan->save();

                                $surat_sk->save(array($permohonan, $pegawai));

                                /* Input Data */
                                $data_id2 = new tmsurat_keputusan();

                                $data_id2->select_max('id')->get();
                                $data_id2->get_by_id($data_id2->id);

                                //Per Tahun Auto Restart NoUrut
                                if($permohonan->d_tahun == $data_tahun)
                                    $data_urut = $data_id2->i_urut + 1;
                                else $data_urut = 1;

                                /*START Ambil Report Component*/
                                $trperizinan_id=$perizinan->id;
                                $this->load->model('report_component/Report_component_model');
                                $this->report_component_model=new Report_component_model();
                                $setting_component_sk=$this->report_component_model->get_report_component($this->report_component_model->kode_sk,$trperizinan_id, $id_permohonan);
                                /*END Ambil Report Component*/

                                $data_keputusan = "DP";
                                /*START Ambil Setting No Surat untuk Surat Keputusan jika ada*/
                                if(isset($setting_component_sk['format_nomor']) &&
                                    $setting_component_sk['format_nomor']!=''){
                                    $no_surat2=$setting_component_sk['format_nomor'];
                                }else{//Jika tidak ada, maka gunakan penomoran lama
                                    $no_surat2 = $data_urut."/"
                                        .$data_keputusan."/".$data_izin."/"
                                        .$data_bulan."/".$data_tahun;
                                }
                                /*END Ambil Setting No Surat untuk Surat Keputusan jika ada*/

                                $surat_keputusan = new tmsurat_keputusan();
                                $surat_keputusan->c_status = 1;
                                $surat_keputusan->i_urut = $data_urut;
                                $surat_keputusan->no_surat = $no_surat2;
                                $surat_keputusan->tgl_surat = $tgl_skr;

                                $perusahaan = $permohonan->tmperusahaan->get();
                                $surat_keputusan->ket1 = "Pemohon";
                                $surat_keputusan->nama1 = $pemohon->n_pemohon;
                                $surat_keputusan->alamat1 = $pemohon->a_pemohon;
                                $surat_keputusan->ket2 = "Perusahaan";
                                $surat_keputusan->nama2 = $perusahaan->n_perusahaan;
                                $surat_keputusan->alamat2 = $perusahaan->a_perusahaan;
                                $surat_keputusan->content1 = $nama_izin.' ini berlaku sejak ditetapkan sampai dengan tanggal '.$this->lib_date->mysql_to_human($this->lib_date->set_date($tgl_skr, 365)).' dan izin pembaharuan diajukan kepada Kepada Dinas Perijinan Kabupaten selambat-lambatnya 3 (tiga) bulan sebelum habis masa berlakunya keputusan ini.';
                                $surat_keputusan->content2 = $nama_izin.' ini dapat dicabut untuk selama-lamanya bila pelaksanaannya tidak sesuai dengan ketentuan peraturan perundang-undangan yang berlaku;';
                                $surat_keputusan->content3 = 'Keputusan ini berlaku sejak tanggal ditetapkan.';

                                $surat_keputusan->save(array($permohonan, $pegawai));

                                //Kirim SMS Izin sudah selesai
                                if($kelompok->id == 2 || $kelompok->id == 4){
                                    $retribusi = $this->_get_ret($permohonan->id);
                                    $keringanan = $permohonan->tmkeringananretribusi->get();
                                    if ($keringanan->id)
                                        $nilai_ret = ($keringanan->v_prosentase_retribusi * 0.01) * $retribusi;
                                    else
                                        $nilai_ret = $retribusi;
                                    if($nilai_ret) $nilair = $this->terbilang->nominal($nilai_ret, 2);
                                    else $nilair = "0";
//                        if(empty($retribusi)) $retribusi = 0;
                                    $text = "Surat " . $nama_izin . " dgn no daftar " . $no_pendaftaran . " sdh selesai. Dgn biaya Rp. " . $nilair . ". Mohon segera diambil.";
                                }else{
                                    $text = "Surat " . $nama_izin . " dgn no daftar " . $no_pendaftaran . " sdh selesai. Mohon segera diambil.";

                                }
//                    $date = $this->_get_day_to_send();
//                    $text .= $date;

                                if(strlen($text) > 160) {
                                    $text = NULL;
                                    if($kelompok->id == 2 || $kelompok->id == 4){
                                        $retribusi = $this->_get_ret($permohonan->id);
                                        if(empty($retribusi)) $retribusi = 0;
                                        $text = "Surat Anda dgn no daftar " . $no_pendaftaran . ". Dgn biaya Rp. " . $this->terbilang->nominal($retribusi, 2) . ". Mohon segera diambil.";
                                    }else{
                                        $text = "Surat Anda dgn no daftar " . $no_pendaftaran . ". Mohon segera diambil.";
                                    }
                                }
                                //}

                                $number = $pemohon->telp_pemohon;
                                /* if($number) {
                                     $outbox = new outbox();
                                     $outbox->TextDecoded = $text;
                                     $outbox->DestinationNumber = $number ;
                                     $outbox->DeliveryReport = 'yes';
                                     $outbox->save();
                                 }*/
                            }
                            $bap->c_penetapan = 1;

                            /*Update 31 Mar 2014*/
                            $all_bap = new tmbap();
                            $all_bap->where_related('tmpermohonan','id',$id_permohonan)->select('id')->get();
                            $all_bap->update_all('status_bap',$status_bap);
                            $all_bap->update_all('c_penetapan',1);
//                        }
                        break;
                    case 2://Jika ditolak

                        /*Update 16 Agustus 2015*/
                        $all_bap = new tmbap();
                        $all_bap->where_related('tmpermohonan','id',$id_permohonan)->select('id')->get();
                        $all_bap->update_all('status_bap',$status_bap);
                        $all_bap->update_all('c_penetapan',2);

                        $this->__rejectPermohonan($id_permohonan, $status_skr);

                        /*$permohonan->c_penetapan = 2;//Ditolak
                        $permohonan->save();

                        $data_id = new tmsk();

                        $data_id->select_max('id')->get();
                        $data_id->get_by_id($data_id->id);

                        //Per Tahun Auto Restart NoUrut
                        $data_tahun = date("Y");
                        if($permohonan->d_tahun == $data_tahun)
                            $data_urut = $data_id->i_urut;
                        else $data_urut = 1;

                        $surat_sk = new tmsk();
                        $surat_sk->i_urut = $data_urut;
                        $surat_sk->no_surat = "Ditolak";
                        $surat_sk->c_status = 1;
                        $surat_sk->tgl_surat = $tgl_skr;*/

                        /* Input Relasi Tabel*/
                        /*$petugas = 1; //1 -> Jabatan Penandatangan
                        $pegawai = new tmpegawai();
                        $pegawai->where('status', $petugas)->get();

                        $surat_sk->save(array($permohonan, $pegawai));

                        //Kirim SMS Izin ditolak
                        $text = "Surat " . $nama_izin . " dgn no daftar " . $no_pendaftaran ." telah ditolak. Dgn alasan " . $bap->c_pesan;

                        if(strlen($text) > 160) {
                            $text = NULL;
                            $text = "Surat Anda dgn no daftar " . $no_pendaftaran . " telah ditolak. ";
                        }

                        $id_status = $this->__status_ditolak;
                        $this->__input_tracking_progress($id_permohonan, $status_skr, $id_status);*/
                        break;
                }
                break;
            default:
                break;
        }

       $tgl = date("Y-m-d H:i:s");
       $u_ser = $this->session->userdata('username');
       $g = $this->sql($u_ser);
//     $jam = date("H:i:s A");
       $p = $this->db->query("call log ('Penetapan Izin','Penetapan izin ".$permohonan->pendaftaran_id."','".$tgl."','".$u_ser."')");

        $update = $bap->save();

        if (!$update) {
            echo '<p>' . $update->error->string . '</p>';
        } else {
//            $this->index();
            redirect('permohonan/penetapan');
        }
    }

    public function _number_safe() {
        $is_safe = TRUE;
        return $is_safe;
    }

    public function _get_ret($id = NULL) {
        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($id);
        $permohonan->tmbap->get();
        $ret = $permohonan->tmbap->nilai_retribusi;
        return $ret;
    }

    public function _get_day_to_send() {
        /*
         * Cek apakah besok libur?
         */

        $date = now();
        $date = substr(unix_to_human($date, FALSE), 0, 10);
        $day = intval(substr($date, 8, 2));
        $month = intval(substr($date, 5, 2));
        $year = intval(substr($date, 0, 4));
        $day = $day + 1;
        $holiday = FALSE;
        do {

            if ($month === 1 || $month === 3 || $month === 5 || $month === 7 ||
                    $month === 8 || $month === 10 || $month === 12) {
                $day_length = 31;
            } else if ($month === 2) {
                if ($year % 4 === 0) {
                    $day_length = 29;
                } else {
                    $day_length = 28;
                }
            } else {
                $day_length = 30;
            }

            $day = $day + 1;
            if ($day > $day_length) {
                $day = $day - $day_length;
                $month = $month + 1;
                if ($month > 12) {
                    $month = $month - 12;
                    $year = $year + 1;
                }
            }

            $parse_date = $year . "-" . $month . "-" . $day;
            $holiday = new tmholiday();
            $is_holiday = $holiday->where('date', $parse_date)->count();

            if ($is_holiday === 0) {
                $holiday = FALSE;
            }
        } while ($holiday);

        return $day . "-" . $month . "-" . $year;
    }

    public function update() {
        $update = $this->bap
                        ->where('id', $this->input->post('id_bap'))
                        ->update('pendaftaran_id', $this->input->post('nopendaftaran'))
                        ->update('bap_id', $this->input->post('nobap'))
                        ->update('c_pesan', $this->input->post('pesankomentar'))
                        ->update('status_bap', $this->input->post('status'))
                        ->update('nilai_retribusi', $this->input->post('nilai_retribusi'));

        if ($update) {
            $this->index();
        }
    }

    public function delete($id = NULL) {
        $this->perizinan->where('id_pemohon', $id)->get();
        if ($this->perizinan->delete()) {
            redirect('perizinan');
        }
    }

    public function cetakBAP($id=NULL, $idjenis=NULL) {

        $this->settings = new settings();
        $this->settings->where('name', 'app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name', 'app_city')->get();

        $permohonan = new tmpermohonan();
        $perizinan = new trperizinan();
        $property = new trproperty();
        $koefesien = new trkoefesientarifretribusi();
        $jenisproperty = new tmproperty_jenisperizinan();

        $permohonan->where('id', $id)->get();
        $permohonan->$perizinan->where('id', $idjenis)->get();
        $permohonan->$tanggal_survey->get();

        $pemohon = $permohonan->tmpemohon->get();
        $perusahaan = $permohonan->tmperusahaan->get();
        $bap = $permohonan->tmbap->get();

        $daftar = $permohonan->pendaftaran_id;

        $perizinan->trperizinan_trproperty->where('c_retribusi_id', 1);
        $perizinan->trperizinan_trproperty->where('trperizinan_id', $this->input->post('idjenis'));
        $perizinan->trperizinan_trproperty->get();

        $listform = $permohonan->$perizinan->trproperty->order_by('c_parent asc, c_order asc')->get();
        $list_daftar = $permohonan->tmproperty_jenisperizinan->get();
        $permohonan->$perizinan->$property->$jenisproperty->where('pendaftaran_id', $daftar)->get();
        $k_property = $permohonan->$perizinan->$property->$jenisproperty->k_property;
        $permohonan->$perizinan->$property->$koefesien->where('id', $k_property)->get();


        //path of the template file
        $nama_surat = "cetak_BAP";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/' . $nama_surat . '.odt');
        $odf->setImage('header', 'assets/css/' . $app_folder . '/images/dinas_1.jpg', '17.5', '4.5');


        /* $wilayah = new trkabupaten();
          if($app_city->value !== '0'){
          $alamat = $pemohon->a_pemohon.' '.$p_kelurahan->n_kelurahan.', '.
          $p_kecamatan->n_kecamatan.', '.ucwords(strtolower($p_kabupaten->n_kabupaten));
          $wilayah->get_by_id($app_city->value);
          $odf->setVars('kabupaten', ucwords(strtolower($wilayah->n_kabupaten)));
          $odf->setVars('kota', ucwords(strtolower($wilayah->n_kabupaten)));
          }else{
          $alamat = $pemohon->a_pemohon;
          $odf->setVars('kabupaten', 'setempat');
          $odf->setVars('kota', '...........');
          } */
        $odf->setVars('title', 'Berita Acara Pemeriksaan');
        $odf->setVars('kota', '....');
        $odf->setVars('tanggal', date('d/m/Y'));

        if ($permohonan->$tanggal_survey->date) {
            if ($permohonan->$tanggal_survey->date != '0000-00-00')
                $tgl_periksa = $this->lib_date->mysql_to_human($permohonan->$tanggal_survey->date);
            else
                $tgl_periksa = "";
        }else
            $tgl_periksa = "";

        if ($bap->status_bap) {
            if ($bap->status_bap == "1")
                $status = "Ya";
            else
                $status = "Tidak";
        }else
            $status = "Tidak";

        $listeArticles = array(
            array('property' => 'Nomor pendaftaran',
                'content' => $permohonan->pendaftaran_id,
            ),
            array('property' => 'Jenis Layanan',
                'content' => $permohonan->$perizinan->n_perizinan,
            ),
            array('property' => 'Nama Pemohon',
                'content' => $pemohon->n_pemohon,
            ),
            array('property' => 'Alamat pemohon',
                'content' => $pemohon->a_pemohon,
            ),
            array('property' => 'Nama Perusahaan',
                'content' => $perusahaan->n_perusahaan,
            ),
            array('property' => 'Tanggal Pemeriksaan',
                'content' => $tgl_periksa,
            ),
            array('property' => 'No SK BAP',
                'content' => $bap->bap_id,
            ),
            array('property' => 'Pesan Komentar',
                'content' => $bap->c_pesan,
            ),
            array('property' => 'Sesuai',
                'content' => $status
            ,
            ),
        );
        $article = $odf->setSegment('articles');
        foreach ($listeArticles AS $element) {
            $article->titreArticle($element['property']);
            $article->texteArticle($element['content']);
            $article->merge();
        }
        $odf->mergeSegment($article);

        //break
        foreach ($listform as $data) {

            if ($list_daftar->id) {
                foreach ($list_daftar as $data_daftar) {
                    $entry_property = new tmproperty_jenisperizinan_trproperty();
                    $entry_property->where('tmproperty_jenisperizinan_id', $data_daftar->id)
                            ->where('trproperty_id', $data->id)->get();
                    if ($entry_property->tmproperty_jenisperizinan_id) {
                        $entry_daftar = new tmproperty_jenisperizinan();
                        $entry_daftar->get_by_id($entry_property->tmproperty_jenisperizinan_id);

                        $entry_id = $entry_daftar->id;
                        $data_entry = $entry_daftar->v_property;
                        $data_koefisien = $entry_daftar->k_property;
                        $data_entryt = $entry_daftar->v_tinjauan;
                        $data_koefisient = $entry_daftar->k_tinjauan;
                    }
                }
            } else {
                $entry_id = '';
                $data_entry = '';
                $data_koefisien = 0;
            }
            $data_koefisien2 = new trkoefesientarifretribusi();
            $data_koefisien2->get_by_id($data_koefisien);

            if ($entry_daftar->v_tinjauan) {
                if ($entry_daftar->v_tinjauan == "0")
                    $hasil = " ";
                else
                    $hasil = $entry_daftar->v_tinjauan;
            }else
                $hasil = " ";

            if ($data->c_type) {
                if ($data->c_type == "2")
                    $prop = "<b>" . $data->n_property . "</b>";
                else
                    $prop = $data->n_property;
            }else
                $prop = $data->n_property;

            $listeArticles3 = array(
                array('property3' => $prop,
                    'content3' => $data_koefisien2->kategori,
                    'content33' => $hasil,
                ),
            );

            $article3 = $odf->setSegment('articles3');
            foreach ($listeArticles3 AS $element3) {
                $article3->titreArticle3($element3['property3']);
                $article3->texteArticle3($element3['content3']);
                $article3->texteArticle4($element3['content33']);
                $article3->merge();
            }
        }
        $odf->mergeSegment($article3);

        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat . '_' . $no_daftar . '.odt');
    }
    
    public function sqlRet($id)
    {
        $query = "select v_tinjauan from tmproperty_jenisperizinan as a 
                inner join tmpermohonan as b on b.pendaftaran_id=a.pendaftaran_id
                inner join tmproperty_jenisperizinan_trproperty as c on c.tmproperty_jenisperizinan_id=a.id
                inner join trproperty as d on d.id=c.trproperty_id
                where b.pendaftaran_id='".$id."' and d.id='45'";
        
        $hasil = $this->db->query($query);
        return $hasil->row();
        
    }

     public function sql($u_ser)
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
