<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of survey class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */
class Survey extends WRC_AdminCont {
    private $_status_entry_tinjauan = 19;//Entry Hasil Tinjauan;
    private $_status_penjadwalan = 4; //Penjadwalan Tinjauan

    public function __construct() {
        parent::__construct();
        $this->permohonan = new tmpermohonan();
        $this->pegawai = new tmpegawai();
        $this->survey = new trtanggal_survey();

        /*$this->permohonan = NULL;
        $this->pegawai = NULL;
        $this->survey = NULL;
        $enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if ($list_auth->id_role === '11' || $list_auth->id_role === '12') {
                $enabled = TRUE;
                $this->permohonan = new tmpermohonan();
                $this->pegawai = new tmpegawai();
                $this->survey = new trtanggal_survey();
            }
        }

        if (!$enabled) {
            redirect('dashboard');
        }*/
    }

    /**
     * Update 9 April 2014
     * Filter untuk user berdasarkan keterlibatannya dalam tinjauan lapangan
     * Jika tidak termasuk unit kerja yang dijadwalkan dalam tinjauan lapangan, tidak dapat melihat data
     */
    public function result() {

//        $username = new user();
//        $perizinan = new trperizinan();
//        $pendaftaran = new tmpermohonan();
//
//        $username
//            ->where('username', $this->session->userdata('username'))
//            ->get();
//
//        $data['list_izin'] = $perizinan
//            ->where_related($username)
//            ->get();
//
//        $query = $pendaftaran
//                ->where('c_pendaftaran', 1) //Pendaftaran selesai
//                ->where('c_izin_selesai', 0) //SK Belum diserahkan
//                ->where('c_izin_dicabut', 0) //Permohonan tidak dicabut
//                ->order_by('id', 'DESC')->get();
        $tgla = $this->input->post('tgla');
        $tglb = $this->input->post('tglb');
        $now = $this->lib_date->get_datetime_now();
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



        if($this->__is_administrator()){
            $query = "SELECT DISTINCT A.id, A.pendaftaran_id, A.c_tinjauan, A.d_terima_berkas, A.d_survey,
                A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
	        C.id idizin, C.n_perizinan, E.n_pemohon,
	        G.id idjenis, G.n_permohonan, K.n_unitkerja, J.id AS tim_teknis_id, J.status_tinjauan
	        FROM tmpermohonan as A
                INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
                INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
                INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
                INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
                INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
                INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
                INNER JOIN tmpermohonan_trtanggal_survey H ON H.tmpermohonan_id = A.id
                INNER JOIN trtanggal_survey I ON I.id = H.trtanggal_survey_id
                INNER JOIN tim_teknis J ON J.trtanggal_survey_id=I.id
                INNER JOIN trunitkerja K ON K.id=J.trunitkerja_id
	        WHERE A.c_pendaftaran = 1
	        AND A.c_izin_dicabut = 0
	        AND A.c_izin_selesai = 0
	        AND A.d_terima_berkas between '$tgla' and '$tglb'
	        AND A.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})
            AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
	        AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                            INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                            WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = {$this->_status_entry_tinjauan})>0
	        order by A.id DESC";
        }else{
            $current_unitkerja = $this->__get_current_unitkerja();
            $filter_unit_kerja = ($current_unitkerja->id!='') ? "AND J.trunitkerja_id ={$current_unitkerja->id}":"AND J.trunitkerja_id =''";

            $query = "SELECT A.id, A.pendaftaran_id, A.c_tinjauan, A.d_terima_berkas, A.d_survey,
                A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
                C.id idizin, C.n_perizinan, E.n_pemohon,
                G.id idjenis, G.n_permohonan, K.n_unitkerja, J.id AS tim_teknis_id, J.status_tinjauan
                FROM tmpermohonan as A
                INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
                INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
                INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
                INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
                INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
                INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
                INNER JOIN tmpermohonan_trtanggal_survey H ON H.tmpermohonan_id = A.id
                INNER JOIN trtanggal_survey I ON I.id = H.trtanggal_survey_id
                INNER JOIN tim_teknis J ON J.trtanggal_survey_id=I.id
                INNER JOIN trunitkerja K ON K.id=J.trunitkerja_id ".
                    //INNER JOIN trperizinan_user AS N ON N.trperizinan_id = C.id
                "WHERE A.c_pendaftaran = 1
                AND A.c_izin_dicabut = 0
                AND A.c_izin_selesai = 0 ".
                //AND N.user_id = '" . $username->id . "'
                "AND A.d_terima_berkas between '$tgla' and '$tglb'
                /*AND A.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})*/
                AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
                AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                            INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                            WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = {$this->_status_entry_tinjauan})>0 ".
                $filter_unit_kerja.
                " ORDER BY A.id DESC";
        }

		$data['list'] = $query;
		$data['arr_izin_tinjauan']=$this->__get_izin_dengan_tinjauan();
        $this->load->vars($data);

        $property = new trproperty();
        $lists = $property->get();

        $str = NULL;
        foreach ($lists as $list) {
            $str .= "\nvar " . $list->short_name . " = " . "$('#" . $list->short_name . "').val();";
        }
        $str .= "\n";

        $js = "
                $(document).ready(function() {

                        oTable = $('#survey').dataTable({
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
        $this->session_info['page_name'] = "Data Entry Hasil Tinjauan";
        $this->template->build('list_result', $this->session_info);
    }

    //public function resultUpdate($id_daftar = NULL) {
    public function resultUpdate($tim_teknis_id = NULL) {

        $current_unitkerja = null;
        $mapping_unitkerja = array();

        $this->tim_teknis = new tim_teknis();
        $this->trtanggal_survey = new trtanggal_survey();
        $p_tim_teknis = $this->tim_teknis->get_by_id($tim_teknis_id);
        if(count($p_tim_teknis)>0):
            $trtanggal_survey_id = $p_tim_teknis->trtanggal_survey_id;
            //$p_daftar = $this->permohonan->get_by_id($id_daftar);
            $p_daftar = $this->permohonan->where_related_trtanggal_survey('id',$trtanggal_survey_id)->get();
            $permohonanUnitKerjaId = $p_daftar->trunitkerja_id;

            $id_daftar = $p_daftar->id;
            $p_pemohon = $p_daftar->tmpemohon->get();
            $p_jenis = $p_daftar->trjenis_permohonan->get();
            $p_izin = $p_daftar->trperizinan->get();
            
            $p_kelompok = $p_daftar->trperizinan->trkelompok_perizinan->get();
            $p_kelurahan = $p_pemohon->trkelurahan->get();
            $p_kecamatan = $p_kelurahan->trkecamatan->get();
            $p_kabupaten = $p_kecamatan->trkabupaten->get();
            $p_prov = $p_kabupaten->trpropinsi->get();

            $trperizinan_id = $p_izin->id;

            $this->permohonan->tmsurat_rekomendasi->get();
            $data['tim_teknis_id'] = $tim_teknis_id;
            $data['trunitkerja_id'] = $p_tim_teknis->trunitkerja_id;

            $data['id_daftar'] = $id_daftar;
            $data['permohonan'] = $p_daftar;
            $data['waktu_awal'] = $this->lib_date->get_datetime_now();
            $data['no_daftar'] = $p_daftar->pendaftaran_id;
            $data['nama_pemohon'] = $p_pemohon->n_pemohon;
            $data['alamat_pemohon'] = $p_pemohon->a_pemohon . ', ' . $p_kelurahan->n_kelurahan . ', ' . $p_kecamatan->n_kecamatan . ', ' .
                                    $p_kabupaten->n_kabupaten . ', ' . $p_prov->n_propinsi;
            $data['jenis_izin'] = $p_izin->n_perizinan;
            $data['nama_jenis'] = $p_jenis->n_permohonan;
            $data['nama_kelompok'] = $p_kelompok->n_kelompok;
            $data['list'] = $p_izin->trproperty->order_by('c_parent_order asc, c_order asc')->get();
            
            $data['list_daftar'] = $p_daftar->tmproperty_jenisperizinan
                    ->where('tim_teknis_id',$tim_teknis_id)
                    ->where_in('entry_flag',array(2,3))->get();

            $data['list_klasifikasi'] = $p_daftar->tmproperty_klasifikasi->get();
            $data['list_prasarana'] = $p_daftar->tmproperty_prasarana->get();
                    $data['no_surat'] = $this->permohonan->tmsurat_rekomendasi->no_surat;
            $data['tgl_surat'] = $this->permohonan->tmsurat_rekomendasi->tgl_surat;
            $data['deskripsi'] = $this->permohonan->tmsurat_rekomendasi->deskripsi;
                    $data['arr_izin_rekomendasi']=$this->__get_izin_dengan_rekomendasi();
            $js = "
                    $(document).ready(function() {
                        $(\"#tabs\").tabs();
                        $(\"#tgl_surat\").datepicker({
                            changeMonth: true,
                            changeYear: true,
                            dateFormat: 'yy-mm-dd',
                            closeText: 'X'
                        });
                    } );
                ";

            $this->template->set_metadata_javascript($js);
            $data['from'] = 'survey/result';
            $data['id'] = $id_daftar;
            $entry = new tmproperty_jenisperizinan();
            $entry->where_related($p_daftar)->get();
            //if($entry->id) $save = 'updateresult';
            $save = 'saveresult';
            $data['save_method'] = $save;


            //if(!$this->__is_administrator()){
                #### Ambil Unit Kerja dari user yang login ####
                $this->load->model('pengguna/user');

                $this->user = new user();
                $user_id = $this->session_info['id_auth'];
                $current_user = $this->user->get_by_id($user_id);
                $current_unitkerja = $current_user->tmpegawai->trunitkerja->get();
                ################################################

                ### Ambil Setting Property Tim Teknis ###
                $this->load->model('property_tim_teknis/property_teknis_header');
                $this->property_teknis_header = new property_teknis_header();
                $property_header = $this->property_teknis_header
                    ->where('trperizinan_id', $trperizinan_id)
                    ->where('trunitkerja_id', $permohonanUnitKerjaId)
                    ->get();
                foreach($property_header->property_teknis_detail as $key=>$detail){
                    $mapping_unitkerja[$detail->trproperty_id] = $detail->trunitkerja_id;
                }
                #########################################
            //}
            
            $data['current_unitkerja'] = $current_unitkerja;
            $data['mapping_unitkerja'] = $mapping_unitkerja;

            $this->load->vars($data);

            $this->session_info['page_name'] = "Data Entry Hasil Tinjauan";

            /*foreach($data['list'] as $list){
                echo $list->n_property.'-'.$list->c_type.'<br>';
            }*/
            $this->template->build('update_survey', $this->session_info);
        endif;
    }

    public function saveresult() {
        $permohonan = new tmpermohonan();
        $tim_teknis = new tim_teknis();
        $daftar_id = $this->input->post('id_daftar');
        $tim_teknis_id = $this->input->post('tim_teknis_id');
        $permohonan->get_by_id($daftar_id);
        $perizinan = $permohonan->trperizinan->get();
        $kelompok = $perizinan->trkelompok_perizinan->get();
        $id_kelompok_izin = $kelompok->id;
        $tim_teknis->get_by_id($tim_teknis_id);
        
        $entry_id = $this->input->post('entry_id');
        $property_id = $this->input->post('property_id');
        $entry = $this->input->post('property_value');
        $koefisien_id = $this->input->post('koefisien_id');
        $entry2 = $this->input->post('property_value2');
        $koefisien_id2 = $this->input->post('koefisien_id2');
        $entry_len = count($property_id);

        $is_array = NULL;
        
        ####Menghapus Data Property yang diinput pertama kali###
        /*for ($i = 0; $i < $entry_len; $i++) {
            if ($is_array !== $property_id[$i]) {
                $entry_awal = new tmproperty_jenisperizinan();
                $entry_awal->where($permohonan)->where('tim_teknis_id')->get();
                
                $property_awal = new tmproperty_jenisperizinan_trproperty(); 
                $property_awal->where('tmproperty_jenisperizinan_id', $entry_awal->id)->get();
                $property_awal->delete();
                
                $daftar_awal = new tmpermohonan_tmproperty_jenisperizinan();
                $daftar_awal->where('tmproperty_jenisperizinan_id', $entry_awal->id)->get(); 
                $daftar_awal->delete();
                
                $entry_awal->delete();
            }
            $is_array = $property_id[$i];
        }*/
        
        //Update 9 Februari 2014
        $tmproperty_jenisperizinan = new tmproperty_jenisperizinan();
        $entry_property = $tmproperty_jenisperizinan->where($permohonan)
                ->where('tim_teknis_id', $tim_teknis_id)->get();
        if(in_array($id_kelompok_izin, $this->__get_izin_dengan_bap())){//[Update 27-5-2014]
            foreach ($entry_property as $entry_awal) {
                //if ($is_array !== $property_id[$i]) {
                    $property_awal = new tmproperty_jenisperizinan_trproperty();
                    $property_awal->where('tmproperty_jenisperizinan_id', $entry_awal->id)->get();
                    $property_awal->delete();

                    $daftar_awal = new tmpermohonan_tmproperty_jenisperizinan();
                    $daftar_awal->where('tmproperty_jenisperizinan_id', $entry_awal->id)->get();
                    $daftar_awal->delete();

                    $entry_awal->delete();
                //}
                //$is_array = $property_id[$i];
            }
        }
        #######################################################
        $indeks_koefisien = 1;
        
        ###### Mengisi data propery baru ke tabel tmproperty_jenisperizinan####
        if(in_array($id_kelompok_izin, $this->__get_izin_dengan_bap())){//[Update 27-5-2014]
            for ($i = 0; $i < $entry_len; $i++) {
                if ($is_array !== $property_id[$i]) {
                    $relasi_entry = new trproperty();
                    $relasi_entry->get_by_id($property_id[$i]);
                    $entry_data = new tmproperty_jenisperizinan();
                    $entry_data->pendaftaran_id = $permohonan->pendaftaran_id;
                    $entry_data->v_property = $entry2[$i];
                    $entry_data->k_property = $koefisien_id2[$i];
                    $entry_data->v_tinjauan = $entry[$i];
                    $entry_data->k_tinjauan = $koefisien_id[$i];
                    $entry_data->tim_teknis_id = $tim_teknis_id;
                    $entry_data->entry_flag = 3;
                    if ($koefisien_id[$i]) {
                        $koef = new trkoefesientarifretribusi();
                        $koef->get_by_id($koefisien_id[$i]);
                        $indeks_koefisien = $indeks_koefisien * $koef->index_kategori;
                    }
                    /* Save tmproperty_jenisperizinan() & tmproperty_jenisperizinan_trproperty() */
                    $entry_data->save($relasi_entry);
                    $entry_data_id = new tmproperty_jenisperizinan();
                    $entry_data_id->select_max('id')->get();
                    /* Save tmpermohonan_tmproperty_jenisperizinan() */
                    $entry_data_id->save($permohonan);

                    //Luas Bangunan
                    if ($relasi_entry->id == '10')
                        $luas = $entry[$i];
                    if (empty($luas))
                        $luas = 1;

                    ## Awal Index Terintegrasi Bangunan Gedung
                    //Fungsi
                    if ($relasi_entry->id == '11') {
                        $koef_fungsi = new trkoefesientarifretribusi();
                        $koef_fungsi->get_by_id($koefisien_id[$i]);
                        $fungsi = $koef_fungsi->index_kategori;
                    }
                    if (empty($fungsi))
                        $fungsi = 1;

                    //Lingkup Pembangunan
                    if ($relasi_entry->id == '14') {
                        $koef_lingkup = new trkoefesientarifretribusi();
                        $koef_lingkup->get_by_id($koefisien_id[$i]);
                        $lingkup = $koef_lingkup->index_kategori;
                    }
                    if (empty($lingkup))
                        $lingkup = 1;

                    //Waktu Penggunaan
                    if ($relasi_entry->id == '13') {
                        $koef_waktu = new trkoefesientarifretribusi();
                        $koef_waktu->get_by_id($koefisien_id[$i]);
                        $waktu = $koef_waktu->index_kategori;
                    }
                    if (empty($waktu))
                        $waktu = 1;

                    $retribusi_imb_awal = new tmretribusi_rinci_imb();
                    $retribusi_imb_awal->where_related($permohonan)->get();
                    $retribusi_imb_awal->delete();

                    if ($relasi_entry->id == '12') { //Hanya untuk KLASIFIKASI
                        $klasifikasi_id = $this->input->post('klasifikasi_id');
                        $retribusi_id = $this->input->post('retribusi_id');
                        $koef_value = $this->input->post('koef_value');
                        $koef_id = $this->input->post('koef_id');
                        $koef_value2 = $this->input->post('koef_value2');
                        $koef_id2 = $this->input->post('koef_id2');
                        $klasifikasi_len = count($retribusi_id);
                        $is_array_klasifikasi = NULL;

                        for ($z = 0; $z < $klasifikasi_len; $z++) {
                            if ($is_array_klasifikasi !== $retribusi_id[$z]) {
                                $klasifikasi_awal = new tmproperty_klasifikasi();
                                $klasifikasi_awal->where_related($permohonan)->get();
                                $klasifikasi_awal->delete();
                                $retribusi_awal = new tmproperty_klasifikasi_trkoefesientarifretribusi();
                                $retribusi_awal->where('tmproperty_klasifikasi_id', $klasifikasi_id[$z])->get();
                                $retribusi_awal->delete();
                            }
                            $is_array_klasifikasi = $retribusi_id[$z];
                        }
                        $daftar_klasifikasi = new tmpermohonan_tmproperty_klasifikasi();
                        $daftar_klasifikasi->where('tmpermohonan_id', $daftar_id)->get();
                        $daftar_klasifikasi->delete();

                        $indeks = 0;
                        for ($z = 0; $z < $klasifikasi_len; $z++) {
                            if ($is_array_klasifikasi !== $retribusi_id[$z]) {
                                $relasi_klasifikasi = new trkoefesientarifretribusi();
                                $relasi_klasifikasi->get_by_id($retribusi_id[$z]);
                                $koef_parent = $relasi_klasifikasi->index_kategori;
                                $koef_child = new trkoefisienretribusilev1();
                                $koef_child->get_by_id($koef_id[$z]);
                                $koef_child = $koef_child->index_kategori;
                                $klasifikasi_data = new tmproperty_klasifikasi();
                                $klasifikasi_data->pendaftaran_id = $permohonan->pendaftaran_id;
                                $klasifikasi_data->v_klasifikasi = $koef_value2[$z];
                                $klasifikasi_data->k_klasifikasi = $koef_id2[$z];
                                $klasifikasi_data->v_tinjauan = $koef_value[$z];
                                $klasifikasi_data->k_tinjauan = $koef_id[$z];
                                /* Save tmproperty_klasifikasi() & tmproperty_klasifikasi_trkoefesientarifretribusi() */
                                $klasifikasi_data->save($relasi_klasifikasi);
                                $klasifikasi_data_id = new tmproperty_klasifikasi();
                                $klasifikasi_data_id->select_max('id')->get();
                                /* Save tmpermohonan_tmproperty_jenisperizinan() */
                                $klasifikasi_data_id->save($permohonan);
                                $indeks = $indeks + ($koef_parent * $koef_child);
    //                            if($relasi_klasifikasi->id == '104') $indeks = $koef_parent * $koef_child;
                            }
                            $is_array_klasifikasi = $retribusi_id[$z];
                        }
                    } else if ($relasi_entry->id == '29') { //Hanya untuk PRASARANA
                        $prasarana_id = $this->input->post('prasarana_id');
                        $retribusi_id3 = $this->input->post('retribusi_id3');
                        $koef_value3 = $this->input->post('koef_value3');
                        $koef_id3 = $this->input->post('koef_id3');
                        $koef_value4 = $this->input->post('koef_value4');
                        $koef_id4 = $this->input->post('koef_id4');
                        $prasarana_len = count($retribusi_id3);
                        $is_array_prasarana = NULL;

                        for ($x = 0; $x < $prasarana_len; $x++) {
                            if ($is_array_prasarana !== $retribusi_id3[$x]) {
                                $prasarana_awal = new tmproperty_prasarana();
                                $prasarana_awal->where_related($permohonan)->get();
                                $prasarana_awal->delete();
                                $retribusi_awal = new tmproperty_prasarana_trkoefesientarifretribusi();
                                $retribusi_awal->where('tmproperty_prasarana_id', $prasarana_id[$x])->get();
                                $retribusi_awal->delete();
                            }
                            $is_array_prasarana = $retribusi_id3[$x];
                        }
                        $daftar_prasarana = new tmpermohonan_tmproperty_prasarana();
                        $daftar_prasarana->where('tmpermohonan_id', $daftar_id)->get();
                        $daftar_prasarana->delete();

                        $nilai_prasarana = 0;
                        for ($x = 0; $x < $prasarana_len; $x++) {
                            if ($is_array_prasarana !== $retribusi_id3[$x]) {
                                $relasi_prasarana = new trkoefesientarifretribusi();
                                $relasi_prasarana->get_by_id($retribusi_id3[$x]);
                                $koef_child2 = new trkoefisienretribusilev1();
                                $koef_child2->get_by_id($koef_id3[$x]);
                                $prasarana_data = new tmproperty_prasarana();
                                $prasarana_data->pendaftaran_id = $permohonan->pendaftaran_id;
                                $prasarana_data->v_prasarana = $koef_value4[$x];
                                $prasarana_data->k_prasarana = $koef_id4[$x];
                                $prasarana_data->v_tinjauan = $koef_value3[$x];
                                $prasarana_data->k_tinjauan = $koef_id3[$x];
                                /* Save tmproperty_prasarana() & tmproperty_prasarana_trkoefesientarifretribusi() */
                                $prasarana_data->save($relasi_prasarana);
                                $prasarana_data_id = new tmproperty_prasarana();
                                $prasarana_data_id->select_max('id')->get();
                                /* Save tmpermohonan_tmproperty_jenisperizinan() */
                                $prasarana_data_id->save($permohonan);

                                if ($koef_value3[$x]) {
                                    $imb_prasarana = $koef_value3[$x] * $koef_child2->index_kategori * $koef_child2->v_index_kategori;
                                    $retribusi_prasarana = new tmretribusi_rinci_imb();
                                    $retribusi_prasarana->e_parameter = $koef_value3[$x] . " x " . $koef_child2->index_kategori . " x Rp. " . $this->terbilang->nominal($koef_child2->v_index_kategori, 2);
                                    $retribusi_prasarana->e_parameter_parent = $koef_child2->kategori;
                                    $retribusi_prasarana->v_retribusi = $imb_prasarana;
                                    $retribusi_prasarana->c_imb = 2;
                                    $retribusi_prasarana->save($permohonan);
                                    $nilai_prasarana = $nilai_prasarana + $imb_prasarana;
                                }
                            }
                            $is_array_prasarana = $retribusi_id3[$x];
                        }
                    }
                }
                $is_array = $property_id[$i];
            }
        }
        
        $tim_teknis->status_tinjauan = 1;
        $tim_teknis->save();
        
        $retribusi_parent = new trretribusi();
        $retribusi_parent->where_related($perizinan)->get();
        $nilai_retribusi = $retribusi_parent->v_retribusi;
        if ($perizinan->id == '2' || $perizinan->id == '3') {
            $nilai_imb = $nilai_retribusi;
            $nilai_formulir = $retribusi_parent->v_denda;

            $nama_parameter = "BANGUNAN GEDUNG";
            $it = $fungsi * $lingkup * $indeks * $waktu;
            $nilai_retribusi = $luas * $it * 1.00 * $nilai_imb;
            $retribusi_imb = new tmretribusi_rinci_imb();
            $retribusi_imb->e_parameter = $luas . " m2 x " . $it . " x 1.00 x Rp. " . $this->terbilang->nominal($nilai_imb, 2);
            $retribusi_imb->e_parameter_parent = $nama_parameter;
            $retribusi_imb->v_retribusi = $nilai_retribusi;
            $retribusi_imb->c_imb = 1;
            $retribusi_imb->save($permohonan);

            $nilai_total = $nilai_retribusi + $nilai_prasarana + $nilai_formulir;
        } else {
            $nilai_total = $nilai_retribusi * $indeks_koefisien;
        }
        $bap_awal = $permohonan->tmbap->where('tim_teknis_id',$tim_teknis_id)->get();
        if ($bap_awal->id) {
            //echo "<pre>";print_r($bap_awal);exit();
            //$bap = new tmbap();
            //$bap->get_by_id($bap_awal->id);
            $bap_awal->nilai_retribusi = $nilai_total;
            $update = $bap_awal->save($permohonan);
        } else {
            /* Input Data */
            //echo "<pre>";echo 'test';exit();
            $data_id = new tmbap();
            
            $data_id->select_max('id')->get();
            $data_id->get_by_id($data_id->id);

            $data_tahun = date("Y");
            //Per Tahun Auto Restart NoUrut
            if ($permohonan->d_tahun === $data_tahun)
                $data_urut = $data_id->i_urut + 1;
            else
                $data_urut = 1;

            $i_urut = strlen($data_urut);
            for ($i = 4; $i > $i_urut; $i--) {
                $data_urut = "0" . $data_urut;
            }

            $data_izin = $perizinan->id;
            $i_izin = strlen($data_izin);
            for ($i = 3; $i > $i_izin; $i--) {
                $data_izin = "0" . $data_izin;
            }

            $data_bulan = $this->lib_date->set_month_roman(date("n"));

			/*START Setting BAP dan SKRD dari Report Component*/
			/*$trperizinan_id=$perizinan->id;
			$this->load->model('report_component/Report_component_model');
			$this->report_component_model=new Report_component_model();
			$setting_component_bap=$this->report_component_model->get_report_component($this->report_component_model->kode_bap,$trperizinan_id);
			$setting_component_skrd=$this->report_component_model->get_report_component($this->report_component_model->kode_skrd,$trperizinan_id);
			/*END Setting BAP dan SKRD dari Report Component*/
          	
			$data_bap = "BAP";
            $data_skrd = "SKRD";
			/*START Ambil nomor dari setting jika ada*/
			/*if(isset($setting_component_bap['format_nomor']) && 
				$setting_component_bap['format_nomor']!=''){
				$no_bap=$setting_component_bap['format_nomor'];
			}else{//Jika tidak ada, maka gunakan format penomoran yang lama
				$no_bap = $data_urut."/"
                    .$data_bap."/".$data_izin."/"
                    .$data_bulan."/".$data_tahun;
			}  
			
            if(isset($setting_component_skrd['format_nomor']) && 
				$setting_component_skrd['format_nomor']!=''){
				$no_skrd = $setting_component_skrd['format_nomor'];
			}else{//Jika tidak ada, maka gunakan format penomoran yang lama
				$no_skrd = $data_urut."/"
                    .$data_skrd."/".$data_izin."/"
                    .$data_bulan."/".$data_tahun;
            }
			/*END Ambil nomor dari setting jika ada*/
            $bap2 = new tmbap();
            $bap2->pendaftaran_id = $permohonan->pendaftaran_id;
            $bap2->nilai_retribusi = $nilai_total;
            $bap2->tim_teknis_id = $tim_teknis_id;
            //$bap2->bap_id = $no_bap;
            //$bap2->no_skrd = $no_skrd;
            $bap2->i_urut = $data_urut;
            $update = $bap2->save($permohonan);
        }

        $updated = FALSE;
        if ($permohonan->c_tinjauan === "1")
            $updated = TRUE;

        $status_izin = $permohonan->trstspermohonan->get();

        $status_skr = $this->_status_entry_tinjauan; //Entry Hasil Tinjauan[Lihat Tabel trstspermohonan()]
        ## Ambil Status berikutnya ##
        $this->load->model('permohonan/trlangkah_perizinan');
        $langkah_perizinan = new trlangkah_perizinan();
        $id_status = $langkah_perizinan->nextStep($permohonan->trperizinan->trkelompok_perizinan->id, $status_izin->id);
        ###################################
		
        if ($status_izin->id == $status_skr) {
            $this->__input_tracking_progress($permohonan->id, $status_skr, $id_status);

            /* Input Data Tracking Progress */
            $sts_izin = new trstspermohonan();
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
            $tracking_izin->d_entry = $this->lib_date->get_datetime_now();
            $tracking_izin->save();

            /* [Lihat Tabel trstspermohonan()] */
            $tracking_izin2 = new tmtrackingperizinan();
            $tracking_izin2->pendaftaran_id = $permohonan->pendaftaran_id;
            $tracking_izin2->status = 'Insert';
            $tracking_izin2->d_entry_awal = $this->lib_date->get_datetime_now();
            $tracking_izin2->d_entry = $this->lib_date->get_datetime_now();
            $sts_izin2 = new trstspermohonan();
            $sts_izin2->get_by_id($id_status); //[Lihat Tabel trstspermohonan()]
            $sts_izin2->save($permohonan);
            $tracking_izin2->save($permohonan);
            $tracking_izin2->save($sts_izin2);
        }

        $from = $this->input->post('from');

        $permohonan->tmsurat_rekomendasi->get();
        $surat_rekomendasi = new tmsurat_rekomendasi();
        if ($permohonan->tmsurat_rekomendasi->count() > 0) {
            $id = $permohonan->tmsurat_rekomendasi->id;
            $surat_rekomendasi->where('id', $id)
                    ->update(array(
                        'no_surat' => $this->input->post('no_surat'),
                        'tgl_surat' => $this->input->post('tgl_surat'),
                        'deskripsi' => $this->input->post('deskripsi')
                    ));
        } else {
            $surat_rekomendasi->no_surat = $this->input->post('no_surat');
            $surat_rekomendasi->tgl_surat = $this->input->post('tgl_surat');
            $surat_rekomendasi->deskripsi = $this->input->post('deskripsi');
            $permohonan->where('id', $this->input->post('id_daftar'))->get();
            $surat_rekomendasi->save($permohonan);
        }

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql2($u_ser);
//     $jam = date("H:i:s A");
        $p = $this->db->query("call log ('Entry Tinjauan','Update " . $permohonan->pendaftaran_id . "','" . $tgl . "','" . $u_ser . "')");

        $permohonan->where('id', $daftar_id)
                ->update(array('c_tinjauan' => 1));

        if ($update) {
            if ($from !== NULL &&
                    $from !== "")
                redirect($from);
            else
                redirect('pendataan');
        }
    }

    public function delete() {
        redirect('survey/survey');
    }

    public function index() {
//        $pendaftaran = new tmpermohonan();
//        $username = new user();
//        $perizinan = new trperizinan();
//        $query = $pendaftaran
//                ->where('c_pendaftaran', 1) //Pendaftaran selesai
//                ->where('c_izin_selesai', 0) //SK Belum diserahkan
//                ->where('c_izin_dicabut', 0) //Permohonan tidak dicabut
//                ->order_by('id', 'DESC')->get();
//        $username
//            ->where('username', $this->session->userdata('username'))
//            ->get();
        $tgla = $this->input->post('tgla');
        $tglb = $this->input->post('tglb');
        $now = $this->lib_date->get_datetime_now();
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

       	/*if($this->__is_administrator()){
		    $query = "SELECT A.id, A.pendaftaran_id, A.d_terima_berkas, A.d_survey,
		        A.d_perubahan, A.d_perpanjangan, A.d_daftarulang, A.a_izin,
		        C.id idizin, C.n_perizinan, E.n_pemohon,
		        G.id idjenis, G.n_permohonan
		        FROM tmpermohonan as A
		        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
		        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
		        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
		        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
		        INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
		        INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
		        WHERE A.c_pendaftaran = 1
		        AND A.c_izin_dicabut = 0
		        AND A.c_izin_selesai = 0
		        AND A.d_terima_berkas between '$tgla' and '$tglb'
		        AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
		        AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                            INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                            WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = {$this->_status_penjadwalan})>0
		        order by A.id DESC";
		}else{*/
			$query = "SELECT DISTINCT A.id, A.pendaftaran_id, A.d_terima_berkas, A.d_survey,
		        A.d_perubahan, A.d_perpanjangan, A.d_daftarulang, A.a_izin,
		        C.id idizin, C.n_perizinan, E.n_pemohon,
		        G.id idjenis, G.n_permohonan
		        FROM tmpermohonan as A
		        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
		        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
		        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
		        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
		        INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
		        INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
		        INNER JOIN trperizinan_user AS J ON J.trperizinan_id = C.id
		        WHERE A.c_pendaftaran = 1
		        AND A.c_izin_dicabut = 0
		        AND A.c_izin_selesai = 0
		        AND A.d_terima_berkas between '$tgla' and '$tglb'
		        AND A.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})
		        AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
		        AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                            INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                            WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = {$this->_status_penjadwalan})>0
		        order by A.id DESC";
//		}

        $data['list'] = $query;
	    $data['arr_izin_tinjauan']=$this->__get_izin_dengan_tinjauan();
//        $data['list_izin'] = $perizinan
//            ->where_related($username)
//            ->get();

        $this->load->vars($data);

        $js = "
                $(document).ready(function() {
                        oTable = $('#survey').dataTable({
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
        $this->session_info['page_name'] = "Penjadwalan Tinjauan Lapangan";
        $this->template->build('list', $this->session_info);
    }

    public function edit($id = NULL, $method = 'save') {
        $pendaftaran = new tmpermohonan();
        $dataPermohonan = $pendaftaran->where('id', $id)->get();
        $permohonanUnitKerjaId = $dataPermohonan->trunitkerja_id;

        $pendaftaran->tmpemohon->get();
        $perizinan = $pendaftaran->trperizinan->get();

        #### Added 30 April 2014 ####
        $configured_unitkerja = array(); //Daftar unit kerja yang sudah dikonfigurasi di Setting Property Tim Teknis
        $property_teknis_header = $perizinan->property_teknis_header
            ->where('trunitkerja_id',$permohonanUnitKerjaId)
            ->where('trperizinan_id',$dataPermohonan->trperizinan->id)
            ->get();//Modified 16 August 2015

        $property_teknis_detail = $property_teknis_header->property_teknis_detail
//            ->where('trunitkerja_id > 0')
            ->get();

        if(!empty($property_teknis_detail)){
            foreach($property_teknis_detail as $detail){
                $configured_unitkerja[] = $detail->trunitkerja_id;
            }
        }

        #############################

        $pendaftaran->trtanggal_survey->get();

        $js_date = "
            $(function() {
                $(\"#survey\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
            ";
        $js_date .= "
                $(document).ready(function() {
                    $('#listizin').multiselect().multiselectfilter({
                       show:'blind',
                       hide:'blind',
                       selectedText:'# dari # terpilih',
                    });

                    $('#list_unit_kerja').multiselect().multiselectfilter({
                       show:'blind',
                       hide:'blind',
                       selectedText:'# dari # terpilih'
                    });

                    $('#list_unit_kerja').change(function(){
                        var selectedUnit = $(this).val();
                        //ambil unit melalui ajax
                        $.ajax({
                            url:'".site_url('survey/survey/ajax_get_pegawai')."',
                            type:'POST',
                            dataType:'json',
                            data:{trunitkerja_id : selectedUnit},
                            success:function(r){
                                var selectOption = '';
                                $.each(r,function(key,val){
                                    selectOption += '<option value=\"'+val.id+'\">'+val.n_pegawai+' | '+val.nip+' | '+val.n_unitkerja+'</option>';
                                });
                                $('#listizin').html(selectOption);
                                $('#listizin').multiselect('refresh');
                            }
                        });
                    });

                    $('#petugas').multiselect({
                           show:'blind',
                           hide:'blind',
                           multiple: false,
                           header: 'Pilih Penandatangan',
                           noneSelectedText: 'Pilih Penandatangan',
                           selectedList: 1
                        }).multiselectfilter();
                });";

        $permohonan = new tmpermohonan();
        $permohonan->where('id', $id)->get();
        $permohonan->tmpemohon->get();

        //BEGIN - Ambil Daftar Pegawai yang akan melakukan tinjauan
        $tanggal_tinjauan = $permohonan->trtanggal_survey->id;
        /*$pegawai_lists = new tmpegawai_trtanggal_survey();
        $pegawai_survey = $pegawai_lists
            ->where('trtanggal_survey_id', $tanggal_tinjauan)
            ->where('type', 2)->get();*/
        //END - Ambil Daftar Pegawai yang akan melakukan tinjauan

        $data['idp'] = $this->ambilPegawai($tanggal_tinjauan);

        /*if ($pegawai_survey->id) {//Disabled karena ada filter Unit
            foreach ($pegawai_survey as $list_pegawai) {
                $pegawai_tinjauan_lapangan = new tmpegawai();
                $pegawai_tinjauan_lapangan->where('id', $list_pegawai->tmpegawai_id)->get();


                $pegawai_tinjauan_lapangan->trunitkerja->get();
                $data['nama'] = $pegawai_tinjauan_lapangan->n_pegawai;
                $data['idpa'] = $pegawai_tinjauan_lapangan->id;

                $list_tim_pegawai[] = array(
                    'nama' => $pegawai_tinjauan_lapangan->n_pegawai,
                    'nip' => $pegawai_tinjauan_lapangan->nip,
                    'dinas' => $pegawai_tinjauan_lapangan->trunitkerja->n_unitkerja
                );
            }
        } else {
            $list_tim_pegawai[] = array(
                'nama' => '',
                'nip' => '',
                'dinas' => ''
            );
        }*/

        ###Update 7 Feb 2014###
        $unit_kerja = new trunitkerja();
        $getUnitKerja = $unit_kerja->where_in('id',$configured_unitkerja)->order_by('n_unitkerja','ASC')->get();//Ambil data semua unit kerja
        $list_unit_kerja = $getUnitKerja->all;
        $data['list_unit_kerja'] = $list_unit_kerja;

        //Ambil tim yang sudah ditentukan sebelumnya
        $scheduled_unit = new tim_teknis();
        $getScheduledUnit = $scheduled_unit
            ->where('trtanggal_survey_id', $tanggal_tinjauan)
            ->get();
        $list_scheduled_unit = $getScheduledUnit->all;
        $data['list_scheduled_unit'] = $list_scheduled_unit;
        #######################


//        $data['list_tim_pegawai']=$list_tim_pegawai;

        $this->template->set_metadata_javascript($js_date);

        $data['id_survey'] = $pendaftaran->trtanggal_survey->id;
        $data['save_method'] = $method;
        $data['pendaftaran_id'] = $pendaftaran->pendaftaran_id;
        $data['nama_pendaftar'] = $pendaftaran->tmpemohon->n_pemohon;
        $data['nama_perizinan'] = $pendaftaran->trperizinan->n_perizinan;
        $data['survey'] = $pendaftaran->d_survey;
        $data['berkas'] = $pendaftaran->d_terima_berkas;
        $data['id'] = $pendaftaran->id;

		/*START Ambil No dari Report Component*/
		$izin=$pendaftaran->trperizinan->get();
		$trperizinan_id=$izin->id;
		$this->load->model('report_component/Report_component_model');
		$this->report_component_model=new Report_component_model();
		$setting_component_sptl=$this->report_component_model->get_report_component($this->report_component_model->kode_sptl,$trperizinan_id, $id);
		/*END Ambil No dari Report Component*/

		/*START Ambil nomor dari setting jika ada*/
		if(isset($setting_component_sptl['format_nomor']) && 
			$setting_component_sptl['format_nomor']!=''){
			$data['no_surat']=$setting_component_sptl['format_nomor'];
		}else{//Jika tidak ada, maka gunakan format penomoran yang lama		
			$no_surat = new LibNoSurat();
	        $no = $no_surat->getNumber('survey', $pendaftaran->id);
	        if ($pendaftaran->trtanggal_survey->no_surat === NULL ||
	                $pendaftaran->trtanggal_survey->no_surat === "-") {
	            $data['no_surat'] = $no;
	        } else {
	            $data['no_surat'] = $pendaftaran->trtanggal_survey->no_surat;
	        }
		}
		/*END Ambil nomor dari setting jika ada*/

        $survey_date = new trtanggal_survey();
        $survey_date->where('id', $pendaftaran->trtanggal_survey->id)->get();
        $survey_date->tmpegawai->get();

        //BEGIN - Ambil Daftar Pegawai yang akan melakukan Penandatanganan
        $tanggal_tinjauan = $permohonan->trtanggal_survey->id;
        $pegawai_lists = new tmpegawai_trtanggal_survey();
        $pegawaiTandaTangan = $pegawai_lists
            ->where('trtanggal_survey_id', $tanggal_tinjauan)
            ->where('type', 1)->get();
        $listSelectedPenandatangan = array();
        if($pegawaiTandaTangan->id){//List Petugas yang sudah dipilih untuk tanda tangan
            foreach($pegawaiTandaTangan as $tandaTangan){
                $listSelectedPenandatangan[] = $tandaTangan->tmpegawai_id;
            }
        };
//        $data['petugas_id'] = $survey_date->tmpegawai->id;
        $data['listSelectedPenandatangan'] = $listSelectedPenandatangan;
        //END - Ambil Daftar Pegawai yang akan melakukan Penandatanganan

        ## BEGIN - Ambil list opsi penandatangan ##
        $petugas = new tmpegawai();
        $data['petugas'] = $petugas
            ->where('status', 1)
            ->where_in_related('trunitkerja','id',$permohonanUnitKerjaId)
            ->get();//List petugas yang berhak melakukan tanda tangan
        ## END - Ambil list opsi penandatangan ##

        ## BEGIN - Penambahan filter pegawai jika ada unit kerja yang dipilih
        $petugas = new tmpegawai();
        if(count($list_scheduled_unit)>0){//Jika ada unit kerja yang dipilih, filter pegawai sesuai unit kerja yang dipilih
            foreach($list_scheduled_unit as $scheduled_unit){
                $listUnitKerjaIds[] = $scheduled_unit->trunitkerja_id;
            }
            $data['list'] = $petugas
                ->where_in_related('trunitkerja','id',$listUnitKerjaIds)
                ->where_not_in('status',array(1))
                ->get();
        }else{
            /*$data['list'] = $petugas
                ->where_not_in('status',array(1))
                ->get();*/
            $data['list'] = array();
        }
        ## END - Penambahan filter pegawai jika ada unit kerja yang dipilih

        $this->load->vars($data);
        $this->session_info['page_name'] = "Entry Tinjauan Lapangan";
        $this->template->build('edit', $this->session_info);
    }

    public function save() {
        $no_urut = intval(substr($this->input->post('no_surat'), 0, 4));
        $survey = new trtanggal_survey();
        $survey->no_surat = $this->input->post('no_surat');
        $survey->i_urut = $no_urut;
        $permohonan = new tmpermohonan();
        $permohonan->where('id', $this->input->post('id_permohonan'))->get();

        $petugas_data = new tmpegawai();
        $petugas_data->where('id', $this->input->post('petugas'))->get();

        if ($survey->save(array($permohonan, $petugas_data))) {
            $survey->where('no_surat', $this->input->post('no_surat'))->get();

            $id = $survey->id;
            $this->permohonan->where('id', $this->input->post('id_permohonan'))
                    ->update(array(
                        'd_survey' => $survey->date = $this->input->post('survey')
                    ));
            
            ###Simpan Nama Penandatangan###
            /*$rel = new tmpegawai_trtanggal_survey();
            $rel->where('tmpegawai_id', $this->input->post('petugas'));
            $rel->where('trtanggal_survey_id', $id)->get();
            $rel->type = intval(1);
            $rel->save();*/
            ###############################

            //BEGIN - Simpan Penandatangan
            $penandatangan = $this->input->post('petugas');
            if(is_array($penandatangan) && !empty($penandatangan)){
                foreach($penandatangan as $tandaTangan){
                    $rel = new tmpegawai_trtanggal_survey();
                    $rel->tmpegawai_id = $tandaTangan;
                    $rel->trtanggal_survey_id = $id;
                    $rel->type = intval(1);
                    $rel->save();
                }
            }else{
                $rel = new tmpegawai_trtanggal_survey();
                $rel->tmpegawai_id = $this->input->post('petugas');
                $rel->trtanggal_survey_id = $this->input->post('id_survey');
                $rel->type = intval(1);
                $rel->save();
            }
            //END - Simpan Penandatangan
            
            ###Update 7 Feb 2014###
            $listpegawai = $this->input->post('listizin');
            foreach ($listpegawai as $list) {
                $rel = new tmpegawai_trtanggal_survey();

                $rel->tmpegawai_id = $list;
                $rel->trtanggal_survey_id = $id;
                $rel->type = intval(2);
                $rel->save();
            }
            ########################
			
			##Update 9 Februari 2014##
			$permohonan_id = $this->input->post('id_permohonan');
			$tampung_property = array();
			
			$permohonan = $this->permohonan->where('id', $permohonan_id)->get();
			
			$daftar_awal = new tmpermohonan_tmproperty_jenisperizinan();
			$property_permohonan = $daftar_awal->where('tmpermohonan_id', $permohonan_id)
					->get()->all;

            //ambil kelompok izin dari permohonan yang diajukan
            $id_kelompok_izin = $permohonan->trperizinan->trkelompok_perizinan->id;

            if(in_array($id_kelompok_izin, $this->__get_izin_dengan_bap())){//[Update 27-5-2014]
                foreach($property_permohonan as $key => $property):
                    $property_perizinan = new tmproperty_jenisperizinan();
                    $property_perizinan->where('id', $property->tmproperty_jenisperizinan_id)
                            ->get();
                    $relasi_property = new tmproperty_jenisperizinan_trproperty();
                    $relasi_property->where('tmproperty_jenisperizinan_id', $property->tmproperty_jenisperizinan_id)
                            ->get();
                    if($property_perizinan->entry_flag == 1): //Jika flagnya 1 (dari Entry Data), lakukan duplikasi untuk tiap tim
                        $tampung_property[$key]['pendaftaran_id'] = $property_perizinan->pendaftaran_id;
                        $tampung_property[$key]['v_property'] = $property_perizinan->v_property;
                        $tampung_property[$key]['k_property'] = $property_perizinan->k_property;
                        $tampung_property[$key]['v_tinjauan'] = $property_perizinan->v_tinjauan;
                        $tampung_property[$key]['k_tinjauan'] = $property_perizinan->k_tinjauan;
                        $tampung_property[$key]['trproperty_id'] = $relasi_property->trproperty_id;
                    else:
                        //Jika flagnya bukan 1, lakukan proses delete
                        $relasi_property->delete();
                        $property->delete();
                        $property_perizinan->delete();
                    endif;
                endforeach;
            }
			########################
			//echo "<pre>";print_r($tampung_property);exit();
				
			$list_unit_kerja = $this->input->post('list_unit_kerja');
			foreach ($list_unit_kerja as $id_unit_kerja) {
				$rel = new tim_teknis();
				$rel->trunitkerja_id = $id_unit_kerja;
				$rel->trtanggal_survey_id = $id;
				$rel->save();
				
				##### Looping setiap komponen dan simpan data relasinya ####
                if(in_array($id_kelompok_izin, $this->__get_izin_dengan_bap())){//[Update 27-5-2014]
                    foreach($tampung_property as $tampung):
                        $property_perizinan = new tmproperty_jenisperizinan();
                        $property_perizinan->pendaftaran_id = $tampung['pendaftaran_id'];
                        $property_perizinan->v_property = $tampung['v_property'];
                        $property_perizinan->k_property = $tampung['k_property'];
                        $property_perizinan->v_tinjauan = $tampung['v_tinjauan'];
                        $property_perizinan->k_tinjauan = $tampung['k_tinjauan'];
                        $property_perizinan->tim_teknis_id = $rel->id;
                        $property_perizinan->entry_flag = 2;//Menandakan bahwa diisi di Penjadwalan Tinjauan
                        $property_perizinan->save();

                        $relasi_property = new tmproperty_jenisperizinan_trproperty();
                        $relasi_property->tmproperty_jenisperizinan_id = $property_perizinan->id;
                        $relasi_property->trproperty_id = $tampung['trproperty_id'];
                        $relasi_property->save();

                        $property_permohonan = new tmpermohonan_tmproperty_jenisperizinan();
                        $property_permohonan->tmproperty_jenisperizinan_id = $property_perizinan->id;
                        $property_permohonan->tmpermohonan_id = $permohonan_id;
                        $property_permohonan->save();

                    endforeach;
                }
				###########################################################
			}
			########################

            /* Input Data Tracking Progress */
            $status_izin = $permohonan->trstspermohonan->get();
            $status_skr = $this->_status_penjadwalan; //Entry Hasil Tinjauan [Lihat Tabel trstspermohonan()]

            #### Update 27-02-2014 ###
            /*if(in_array($id_kelompok_izin, $this->__get_izin_dengan_bap())){
                $status_baru = 19;//Entry Hasil Tinjauan
            }elseif(
                in_array($id_kelompok_izin, $this->__get_izin_dengan_tarif()) && //Izin Bertarif
                !in_array($id_kelompok_izin, $this->__get_izin_dengan_tinjauan())//Izin Tanpa Tinjauan
            ){
                $status_baru = 18;//Perhitungan Retribusi
            }elseif(
                !in_array($id_kelompok_izin, $this->__get_izin_dengan_tarif()) && //Izin Tidak Bertarif
                in_array($id_kelompok_izin, $this->__get_izin_dengan_rekomendasi())// Izin dengan rekomendasi
            ){
                $status_baru = 5;//Rekomendasi
            }else{
                $status_baru = 19;//Agar Tidak Error
            }*/
            ################

            ## Ambil Status berikutnya ##
            $this->load->model('permohonan/trlangkah_perizinan');
            $langkah_perizinan = new trlangkah_perizinan();
            $status_baru = $langkah_perizinan->nextStep($id_kelompok_izin, $status_izin->id);
            ###################################

            if ($status_izin->id == $status_skr) {
                $this->__input_tracking_progress($permohonan_id, $status_skr, $status_baru);
                /*$sts_izin = new trstspermohonan();
                $sts_izin->get_by_id($status_skr);
                $data_status = new tmtrackingperizinan_trstspermohonan();
                $list_tracking = $permohonan->tmtrackingperizinan->get();
                if ($list_tracking) {
                    foreach ($list_tracking as $data_track) {
                        $tracking_id = 0;
                        $data_status = new tmtrackingperizinan_trstspermohonan();
                        $data_status->where('tmtrackingperizinan_id', $data_track->id)
                                ->where('trstspermohonan_id', $sts_izin->id)->get();
                        if ($data_status->tmtrackingperizinan_id) {
                            $tracking_id = $data_status->tmtrackingperizinan_id;
                        }
                    }
                }
                */
                /*$tracking_izin = new tmtrackingperizinan();
                $tracking_izin->get_by_id($tracking_id);
                //$tracking_izin->pendaftaran_id = $permohonan->pendaftaran_id;
                $tracking_izin->status = 'Update';
                $tracking_izin->d_entry = $this->lib_date->get_datetime_now();
                $tracking_izin->save();*/

                $tgl = date("Y-m-d H:i:s");
                $u_ser = $this->session->userdata('username');
                $g = $this->sql2($u_ser);
//              $jam = date("H:i:s A");
                $p = $this->db->query("call log ('Penjadwalan Tinjauan','Update " . $permohonan->pendaftaran_id . "','" . $tgl . "','" . $u_ser . "')");
            }

            redirect('survey/survey');
        }
    }

    public function update() {
        $survey = new trtanggal_survey();

        /**
         * Just use trtanggal_survey, because we just need it.
         * so it simple to update record
         */
        $update = $survey->where('id', $this->input->post('id_survey'))
                ->update(array(
                    'no_surat' => $survey->no_surat = $this->input->post('no_surat')
                ));
        $query = "DELETE FROM tmpegawai_trtanggal_survey
            where trtanggal_survey_id = " . $this->input->post('id_survey');
        $results = mysql_query($query);

        //BEGIN - Simpan Penandatangan
        $penandatangan = $this->input->post('petugas');
        if(is_array($penandatangan) && !empty($penandatangan)){
            foreach($penandatangan as $tandaTangan){
                $rel = new tmpegawai_trtanggal_survey();
                $rel->tmpegawai_id = $tandaTangan;
                $rel->trtanggal_survey_id = $this->input->post('id_survey');
                $rel->type = intval(1);
                $rel->save();
            }
        }else{
            $rel = new tmpegawai_trtanggal_survey();
            $rel->tmpegawai_id = $this->input->post('petugas');
            $rel->trtanggal_survey_id = $this->input->post('id_survey');
            $rel->type = intval(1);
            $rel->save();
        }
        //END - Simpan Penandatangan

        ###Update 7 Feb 2014###
        $listpegawai = $this->input->post('listizin');
        foreach ($listpegawai as $list) {
            $rel = new tmpegawai_trtanggal_survey();

            $rel->tmpegawai_id = $list;
            $rel->trtanggal_survey_id = $this->input->post('id_survey');
            $rel->type = intval(2);
            $rel->save();
        }
        $query = "DELETE FROM tim_teknis
            where trtanggal_survey_id = " . $this->input->post('id_survey');
        $results = mysql_query($query);
        
        ##Update 9 Februari 2014##
        $permohonan_id = $this->input->post('id_permohonan');
        $tampung_property = array();
        
        $permohonan = $this->permohonan->where('id', $permohonan_id)->get();
        
        $daftar_awal = new tmpermohonan_tmproperty_jenisperizinan();
        $property_permohonan = $daftar_awal->where('tmpermohonan_id', $permohonan_id)
                ->get()->all;
        foreach($property_permohonan as $key => $property):
            $property_perizinan = new tmproperty_jenisperizinan();
            $property_perizinan->where('id', $property->tmproperty_jenisperizinan_id)
                    ->get();
            $relasi_property = new tmproperty_jenisperizinan_trproperty();
            $relasi_property->where('tmproperty_jenisperizinan_id', $property->tmproperty_jenisperizinan_id)
                    ->get();
            if($property_perizinan->entry_flag == 1): //Jika flagnya 1 (dari Entry Data), lakukan duplikasi untuk tiap tim
                $tampung_property[$key]['pendaftaran_id'] = $property_perizinan->pendaftaran_id;
                $tampung_property[$key]['v_property'] = $property_perizinan->v_property;
                $tampung_property[$key]['k_property'] = $property_perizinan->k_property;
                $tampung_property[$key]['v_tinjauan'] = $property_perizinan->v_tinjauan;
                $tampung_property[$key]['k_tinjauan'] = $property_perizinan->k_tinjauan;
                $tampung_property[$key]['trproperty_id'] = $relasi_property->trproperty_id;        
            else:
                //Jika flagnya bukan 1, lakukan proses delete
                $relasi_property->delete();
                $property->delete();
                $property_perizinan->delete();
            endif;
        endforeach;
        ########################
        //echo "<pre>";print_r($tampung_property);exit();
            
        $list_unit_kerja = $this->input->post('list_unit_kerja');
        foreach ($list_unit_kerja as $id_unit_kerja) {
            $rel = new tim_teknis();
            $rel->trunitkerja_id = $id_unit_kerja;
            $rel->trtanggal_survey_id = $this->input->post('id_survey');
            $rel->save();
            
            ##### Looping setiap komponen dan simpan data relasinya ####
            foreach($tampung_property as $tampung):
                $property_perizinan = new tmproperty_jenisperizinan();
                $property_perizinan->pendaftaran_id = $tampung['pendaftaran_id']; 
                $property_perizinan->v_property = $tampung['v_property'];
                $property_perizinan->k_property = $tampung['k_property'];
                $property_perizinan->v_tinjauan = $tampung['v_tinjauan'];
                $property_perizinan->k_tinjauan = $tampung['k_tinjauan'];
                $property_perizinan->tim_teknis_id = $rel->id;
                $property_perizinan->entry_flag = 2;//Menandakan bahwa diisi di Penjadwalan Tinjauan
                $property_perizinan->save();

                $relasi_property = new tmproperty_jenisperizinan_trproperty();
                $relasi_property->tmproperty_jenisperizinan_id = $property_perizinan->id;
                $relasi_property->trproperty_id = $tampung['trproperty_id'];
                $relasi_property->save();

                $property_permohonan = new tmpermohonan_tmproperty_jenisperizinan();
                $property_permohonan->tmproperty_jenisperizinan_id = $property_perizinan->id;
                $property_permohonan->tmpermohonan_id = $permohonan_id;
                $property_permohonan->save();
                
            endforeach;
            ###########################################################
        }
        ########################
        


//        $rel_survey = new tmpegawai_trtanggal_survey();
//        $rel_survey->where('trtanggal_survey_id', $this->input->post('id_survey'))
//                ->where('type', 1)
//                ->update(array(
//                    'tmpegawai_id' => $this->input->post('petugas')
//                ));

        if ($update)
            $permohonan->update(array(
                'd_survey' => $survey->date = $this->input->post('survey')
            ));

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql2($u_ser);
        $nomor = $this->permohonan->get_by_id($this->input->post('id_permohonan'));
        $p = $this->db->query("call log ('Penjadwalan Tinjauan','Update " . $nomor->pendaftaran_id . "','" . $tgl . "','" . $u_ser . "')");

        redirect('survey/survey');
    }

    public function cetakBAP($id = NULL, $pointer = NULL) {

        $nama_surat = NULL;
        $this->settings = new settings();
        $this->settings->where('name', 'app_folder')->get();
        $app_folder = $this->settings->value . "/";
//        $app_city = $this->settings->where('name', 'app_city')->get();
        $app_city = $this->sql();
        $app_kan = $this->settings->where('name', 'app_kantor')->get();

//        if ($pointer !== '2') {
            $nama_surat = "cetak_bap_survey";
//        } else {
//            $nama_surat = "cetak_bap_survey_imb";
//        }

        $this->load->plugin('odf');
        $odf = new odf('assets/odt/' . $nama_surat . '.odt');
//        $odf->setImage('header', 'assets/css/' . $app_folder . '/images/dinas_1.jpg', '17.5', '3.5');
        //logo
        $this->tr_instansi = new Tr_instansi();
        $logo = $this->tr_instansi->get_by_id(14);
        if ($logo->value !== "") {
            $odf->setImage('logo', 'uploads/logo/' . $logo->value, '1.7', '1.7');
        } else {
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

        $permohonan = new tmpermohonan();
        $permohonan->where('id', $id)->get();
        $permohonan->tmpemohon->get();
        $permohonan->trtanggal_survey->get();
        $tanggal_tinjauan = $permohonan->trtanggal_survey->id;
        $permohonan->trperizinan->get();
        $permohonan->tmperusahaan->get();
        $pegawai = new tmpegawai();
        $pegawai->where('status', 1)->get();
        $odf->setVars('nama_izin', $permohonan->trperizinan->n_perizinan);
        $odf->setVars('nomor_bap', $permohonan->trtanggal_survey->no_surat);
        $odf->setVars('no_pendaftaran', $permohonan->pendaftaran_id);
        $odf->setVars('nama_pemohon', $permohonan->tmpemohon->n_pemohon);
        $odf->setVars('no_telp', $permohonan->tmpemohon->telp_pemohon);
        $odf->setVars('lokasi_usaha', $permohonan->a_izin);
        $date = new Lib_date();
        $odf->setVars('tanggal_bap', "\t\t\t" . $date->mysql_to_human($permohonan->d_survey));
        $odf->setVars('hari', $date->get_day($permohonan->d_survey));
        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($this->lib_date->get_datetime_now()));
        $pegawai = new tmpegawai();
        $pegawai->where('status', 1)->get();
        $odf->setVars('jabatan', $pegawai->n_jabatan);
        $odf->setVars('nama_pejabat', $pegawai->n_pegawai);
        $odf->setVars('nip_pejabat', $pegawai->nip);
        $odf->setVars('kantor', $app_kan->value);

        $this->tr_instansi = new Tr_instansi();
        $alamat = $this->tr_instansi->get_by_id(12);
        if (isset($app_city->n_kabupaten)) {
            $gede_kota = strtoupper($app_city->n_kabupaten);
            $kecil_kota = ucwords(strtolower($app_city->n_kabupaten));
            $odf->setVars('kota4', $gede_kota);
            $odf->setVars('kota', $app_city->ibukota);
            if (isset($alamat->value)) {
                $odf->setVars('alamat', ucwords(strtolower($alamat->value)) . ' - ' . $kecil_kota);
            } else {
                $odf->setVars('alamat', '--------');
            }
        } else {
            $odf->setVars('kota4', '---------');
            $odf->setVars('kota', '--------');
            $odf->setVars('alamat', '--------');
        }
        //alamat




        $wilayah = new trkabupaten();
        $kelurahan = $permohonan->tmpemohon->trkelurahan->get();
        $kelurahan->trkecamatan->get();
        $kelurahan->trkecamatan->trkabupaten->get();
        $alamat = NULL;
//        if ($app_city->value !== '0') {
//            $alamat = $permohonan->tmpemohon->a_pemohon . ' ' . $kelurahan->n_kelurahan . ', ' .
//                    $kelurahan->trkecamatan->n_kecamatan . ', ' . ucwords(strtolower($kelurahan->trkecamatan->trkabupaten->n_kabupaten));
//            $wilayah->get_by_id($app_city->value);
//            $odf->setVars('kota', strtoupper($wilayah->n_kabupaten));
//        } else {
//            $alamat = $pemohon->a_pemohon;
//            $odf->setVars('kota', '...........');
//        }

        $odf->setVars('alamat_pemohon', $permohonan->tmpemohon->a_pemohon);
        $pegawai_lists = new tmpegawai_trtanggal_survey();
        $pegawai_survey = $pegawai_lists
                        ->where('trtanggal_survey_id', $tanggal_tinjauan)
                        ->where('type', 2)->get();

        foreach ($pegawai_survey as $list_pegawai) {
            $pegawai_tinjauan_lapangan = new tmpegawai();
            $pegawai_tinjauan_lapangan->where('id', $list_pegawai->tmpegawai_id)->get();

            $pegawai_tinjauan_lapangan->trunitkerja->get();
            $list_tim_pegawai[] = array(
                'nama' => $pegawai_tinjauan_lapangan->n_pegawai,
                'nip' => $pegawai_tinjauan_lapangan->nip,
                'dinas' => $pegawai_tinjauan_lapangan->trunitkerja->n_unitkerja
            );
        }

        $i = 0;
        $articles3 = $odf->setSegment('petugas');
        foreach ($list_tim_pegawai as $element3) {
            $i++;
            $articles3->no($i . ".");
            $articles3->nama($element3['nama']);
            $articles3->dinas($element3['dinas']);
            $articles3->merge();
        }
        $odf->mergeSegment($articles3);

//        if ($pointer !== '2') {
            $perizinan = new trperizinan();
            $perizinan->get_by_id($pointer);

            $lists = $perizinan->trproperty->include_join_fields()->where('c_type', 2)->order_by('c_parent_order', "asc")->get();
            
            $property = $odf->setSegment('property');
            foreach ($lists as $list) {
            
                $property->nama($list->n_property);
                $children = $perizinan->trproperty->where_join_field($perizinan, 'c_parent', $list->id)->include_join_fields()->order_by('c_order', "asc")->get();
                
                foreach ($children as $child_) {
                    if ($list->id !== $child_->id) {
                        $property->child->child($child_->n_property);
                        if ($child_->join_c_retribusi_id === '1') {
                            $property->child->indeks(" ");
                        } else {
                            $property->child->indeks(" ");
                        }
                        $property->child->merge();
                    }
                }
                $property->merge();
            }
            $odf->mergeSegment($property);
//        }

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql2($u_ser);
//      $jam = date("H:i:s A");
        $p = $this->db->query("call log ('Penjadwalan Tinjauan','Cetak Berita Acara Pemeriksaan " . $permohonan->pendaftaran_id . "','" . $tgl . "','" . $u_ser . "')");


        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat . '_' . $no_daftar . '.odt');
    }

    public function cetak($id = NULL) {
        $this->settings = new settings();
        $this->settings->where('name', 'app_folder')->get();
        $app_folder = $this->settings->value . "/";
//        $app_city = $this->settings->where('name', 'app_city')->get();

        $app_kan = $this->settings->where('name', 'app_kantor')->get();

        $nama_surat = "cetak_tl";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/' . $nama_surat . '.odt');
//        $odf->setImage('header', 'assets/css/' . $app_folder . '/images/dinas_1.jpg', '17.5', '3.5');
        //logo
        $this->tr_instansi = new Tr_instansi();
        $logo = $this->tr_instansi->get_by_id(14);
        if ($logo->value !== "") {
            $odf->setImage('logo', 'uploads/logo/' . $logo->value, '1.7', '1.7');
        } else {
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

        $permohonan = new tmpermohonan();
        $permohonan->where('id', $id)->get();
        $permohonan->tmpemohon->get();
        $permohonan->trtanggal_survey->get();
        $tanggal_tinjauan = $permohonan->trtanggal_survey->id;
        $permohonan->trperizinan->get();
        $permohonan->tmperusahaan->get();
        $pegawai = new tmpegawai();
        $pegawai->where('status', 1)->get();
//        $odf->setVars('jabatan', strtoupper($pegawai->n_jabatan));
        $odf->setVars('nama_pejabat', strtoupper($pegawai->n_pegawai));
        $odf->setVars('nip_pejabat', $pegawai->nip);
        $odf->setVars('title', strtoupper('Surat Perintah Tinjauan Lapangan'));
        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($this->lib_date->get_datetime_now()));
        $odf->setVars('nomor', $permohonan->trtanggal_survey->no_surat);
        $odf->setVars('kantor', $app_kan->value);
        $this->tr_instansi = new Tr_instansi();
        $alamat = $this->tr_instansi->get_by_id(12);
        $app_city = $this->sql();
        if (isset($app_city->n_kabupaten)) {
            $gede_kota = strtoupper($app_city->n_kabupaten);
            $kecil_kota = ucwords(strtolower($app_city->n_kabupaten));
            $odf->setVars('kota4', $gede_kota);
            $odf->setVars('kota', $app_city->ibukota);
            if (isset($alamat->value)) {
                $odf->setVars('alamat', ucwords(strtolower($alamat->value)) . ' - ' . $kecil_kota);
            } else {
                $odf->setVars('alamat', '--------');
            }
        } else {
            $odf->setVars('kota4', '---------');
            $odf->setVars('kota', '--------');
            $odf->setVars('alamat', '--------');
        }
        //alamat
        // if (isset($kecil_kota))
//        {
//            $odf->setVars('alamat', ucwords(strtolower($alamat->value)).' - '.$kecil_kota);
//        }
//        else
//        {
//            $odf->setVars('alamat', ucwords(strtolower($alamat->value)));
//            
//        }

        $wilayah = new trkabupaten();
        $kelurahan = $permohonan->tmpemohon->trkelurahan->get();
        $kelurahan->trkecamatan->get();
        $kelurahan->trkecamatan->trkabupaten->get();
        $alamat = NULL;
//        if ($app_city->value !== '0') {
//            $alamat = $permohonan->tmpemohon->a_pemohon . ' ' . $kelurahan->n_kelurahan . ', ' .
//                    $kelurahan->trkecamatan->n_kecamatan . ', ' . ucwords(strtolower($kelurahan->trkecamatan->trkabupaten->n_kabupaten));
//            $wilayah->get_by_id($app_city->value);
//            $odf->setVars('kota', strtoupper($wilayah->n_kabupaten));
//        } else {
//            $alamat = $pemohon->a_pemohon;
//            $odf->setVars('kota', '...........');
//        }


        $date = new Lib_date();

        $listeArticles = array(
            array('property' => 'Nomor Pendaftaran',
                'content' => $permohonan->pendaftaran_id,
            ),
            array('property' => 'Jenis Layanan',
                'content' => $permohonan->trperizinan->n_perizinan,
            ),
            array('property' => 'Nama Pemohon',
                'content' => $permohonan->tmpemohon->n_pemohon,
            ),
            array('property' => 'No Telp/HP Pemohon',
                'content' => $permohonan->tmpemohon->telp_pemohon,
            ),
            array('property' => 'Alamat Pemohon',
                'content' => $permohonan->tmpemohon->a_pemohon,
            ),
            array('property' => 'Nama Perusahaan',
                'content' => $permohonan->tmperusahaan->n_perusahaan,
            ),
            array('property' => 'Alamat Perusahaan',
                'content' => $permohonan->tmperusahaan->a_perusahaan,
            ),
            array('property' => 'Tanggal Peninjauan',
                'content' => $date->mysql_to_human($permohonan->d_survey),
            )
        );

        $article = $odf->setSegment('articles');
        foreach ($listeArticles AS $element) {
            $article->titreArticle($element['property']);
            $article->texteArticle($element['content']);
            $article->merge();
        }
        $odf->mergeSegment($article);

        $pegawai_lists = new tmpegawai_trtanggal_survey();
        $pegawai_survey = $pegawai_lists
                        ->where('trtanggal_survey_id', $tanggal_tinjauan)
                        ->where('type', 2)->get();

        $article3 = NULL;
        if ($pegawai_survey->id) {
            foreach ($pegawai_survey as $list_pegawai) {
                $pegawai_tinjauan_lapangan = new tmpegawai();
                $pegawai_tinjauan_lapangan->where('id', $list_pegawai->tmpegawai_id)->get();

                $pegawai_tinjauan_lapangan->trunitkerja->get();
                $list_tim_pegawai[] = array(
                    'nama' => $pegawai_tinjauan_lapangan->n_pegawai,
                    'nip' => $pegawai_tinjauan_lapangan->nip,
                    'dinas' => $pegawai_tinjauan_lapangan->trunitkerja->n_unitkerja
                );
            }
        } else {
            $list_tim_pegawai[] = array(
                'nama' => '',
                'nip' => '',
                'dinas' => ''
            );
        }

        $article3 = $odf->setSegment('articles3');
        foreach ($list_tim_pegawai as $element3) {
            $article3->nama($element3['nama']);
            $article3->nip($element3['nip']);
            $article3->dinas($element3['dinas']);
            $article3->merge();
        }
        $odf->mergeSegment($article3);

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql2($u_ser);
//      $jam = date("H:i:s A");
        $p = $this->db->query("call log ('Penjadwalan Tinjauan','Cetak tl " . $permohonan->pendaftaran_id . "','" . $tgl . "','" . $u_ser . "')");


        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat . '_' . $no_daftar . '.odt');
    }

    public function sql() {
        $query = "select a.n_kabupaten, a.ibukota from trkabupaten a where
            a.id = (select value from settings where name='app_city')";

        $sql = $this->db->query($query);
        return $sql->row();
    }

    public function sql2($u_ser) {
        $query = "select a.description
	from user_auth as a
	inner join user_user_auth as  x on a.id = x.user_auth_id
	inner join user as b on b.id = x.user_id
        where b.id = (select id from user where username='" . $u_ser . "')";
        $hasil = $this->db->query($query);
        return $hasil->row();
    }

    public function ambilPegawai($tanggal) {
        $query = "select a.id from tmpegawai as a
        inner join tmpegawai_trtanggal_survey as b on b.tmpegawai_id = a.id
        where b.trtanggal_survey_id = '" . $tanggal . "' and b.type = '2'";

        $sql = $this->db->query($query);
        return $sql->result();
    }

    /**
     * @author Indra Halim
     * Fungsi untuk generate json untuk filtering penentuan pegawai tinjauan berdasarkan unit kerja yang dipilih
     */
    public function ajax_get_pegawai(){
        $return = array();
        $unitKerjaIds = $this->input->post('trunitkerja_id');
        $this->load->model('petugas/tmpegawai');
        $this->tmpegawai = new tmpegawai();
        if(!empty($unitKerjaIds) && !is_null($unitKerjaIds)){
            $getPegawai = $this->tmpegawai->where_not_in('status',array(1))->where_in_related('trunitkerja','id',$unitKerjaIds)->get();
        }else{
            $getPegawai = $this->tmpegawai->where_not_in('status',array(1))->get();
        }
        if($getPegawai->id){
            foreach($getPegawai as $key=>$row){
                $return[$key]['id'] = $row->id;
                $return[$key]['n_pegawai'] = $row->n_pegawai;
                $return[$key]['nip'] = $row->nip;
                $return[$key]['n_unitkerja'] = $row->trunitkerja->n_unitkerja;
            }
        }
        echo json_encode($return);
    }

}

// This is the end of survey class
