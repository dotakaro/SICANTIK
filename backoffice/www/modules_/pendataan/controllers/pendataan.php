<?php

/**
 * Description of Entry Data
 *
 * @author agusnur
 * Created : 23 Aug 2010
 */

class Pendataan extends WRC_AdminCont {

    private $_status_entry_data = 3;//Entry Data

    public function __construct() {
        parent::__construct();
        $this->permohonan = new tmpermohonan();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '11') {
                $enabled = TRUE;
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
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
//        $username = new user();
//        $perizinan = new trperizinan();
//        $query = $this->permohonan
//                ->where('c_pendaftaran', 1) //Pendaftaran selesai
//                ->where('c_izin_selesai', 0) //SK Belum diserahkan
//                ->where('c_izin_dicabut', 0) //Permohonan tidak dicabut
//                ->where("d_entry between '$tgla' and '$tglb'")->get();
//        $data['list'] = $query;
//        $username->where('username', $this->session->userdata('username'))->get();
//        $data['list_izin'] = $perizinan->where_related($username)->get();
        $username = new user();
        $username->where('username', $this->session->userdata('username'))->get();
        /*if($this->__is_administrator()){
	        $query = "SELECT distinct A.id, A.pendaftaran_id, A.id_lama, A.d_terima_berkas,
	        A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
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
	        AND A.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})
	        AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
	        AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                            INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                            WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = {$this->_status_entry_data})>0
	        order by A.id DESC";
		}else{*/
	        $query = "SELECT distinct A.id, A.pendaftaran_id, A.id_lama, A.d_terima_berkas,
	        A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
	        C.id idizin, C.n_perizinan, E.n_pemohon,
	        G.id idjenis, G.n_permohonan
	        FROM tmpermohonan as A
	        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
	        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
	        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
	        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
	        INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
	        INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
	        INNER JOIN trperizinan_user AS H ON  H.trperizinan_id = C.id
	        WHERE A.c_pendaftaran = 1
	        AND A.c_izin_dicabut = 0
	        AND A.c_izin_selesai = 0
			AND H.user_id = '".$username->id."'
	        AND A.d_terima_berkas between '$tgla' and '$tglb'
	        AND A.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})
	        AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
	        AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                            INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                            WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = {$this->_status_entry_data})>0
	        order by A.id DESC";	
//		}
//		echo $query;exit();
        $data['list'] = $query;

        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#pendataan').dataTable({
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
        $this->session_info['page_name'] = "Data Entry Perizinan";
        $this->template->build('entrydata_list', $this->session_info);
    }

    public function index2() {
        $tgla = $this->input->post('tgla');
        $tglb = $this->input->post('tglb');
        $now = $this->lib_date->get_date_now();
        $tgl_before = $this->lib_date->set_date($now, -2);
        $tgl_now = $this->lib_date->set_date($now, 0);

        $data['warning'] = "Izin ini di-set Manual. Tambahkan Property 'Retribusi' dan 'Rumus Perhitungan' kemudian inputkan nilainya";
        if($tgla && $tglb){
            $data['tgla'] = $tgla;
            $data['tglb'] = $tglb;
        }else{
            $tgla = $tgl_before;
            $tglb = $tgl_now;
            $data['tgla'] = $tgla;
            $data['tglb'] = $tglb;
        }
//        $username = new user();
//        $perizinan = new trperizinan();
//        $query = $this->permohonan
//                ->where('c_pendaftaran', 1) //Pendaftaran selesai
//                ->where('c_izin_selesai', 0) //SK Belum diserahkan
//                ->where('c_izin_dicabut', 0) //Permohonan tidak dicabut
//                ->where("d_entry between '$tgla' and '$tglb'")->get();
//        $data['list'] = $query;
//        $username->where('username', $this->session->userdata('username'))->get();
//        $data['list_izin'] = $perizinan->where_related($username)->get();
        $username = new user();
        $username->where('username', $this->session->userdata('username'))->get();

        $query = "SELECT distinct A.id, A.pendaftaran_id, A.id_lama, A.d_terima_berkas,
        A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
        C.id idizin, C.n_perizinan, E.n_pemohon,
        G.id idjenis, G.n_permohonan
        FROM tmpermohonan as A
        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
        INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
        INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
        INNER JOIN trperizinan_user AS H ON  H.trperizinan_id = C.id
        WHERE A.c_pendaftaran = 1
        AND A.c_izin_dicabut = 0
        AND A.c_izin_selesai = 0
        AND H.user_id = '".$username->id."'
        AND A.d_terima_berkas between '$tgla' and '$tglb'
        order by A.id DESC";
        $data['list'] = $query;

        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#pendataan').dataTable({
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
        $this->session_info['page_name'] = "Data Entry Perizinan";
        $this->template->build('entrydata_list', $this->session_info);
    }

    /*
     * create is a method to show page for creating data
     */
    public function edit($id_daftar = NULL) {
        $p_daftar = $this->permohonan->get_by_id($id_daftar);
        $p_pemohon = $p_daftar->tmpemohon->get();
        $p_jenis = $p_daftar->trjenis_permohonan->get();
        $p_izin = $p_daftar->trperizinan->get();
        $p_usaha = $p_daftar->tmperusahaan->get();
        $p_kelompok = $p_daftar->trperizinan->trkelompok_perizinan->get();
        $p_kelurahan = $p_pemohon->trkelurahan->get();
        $p_kecamatan = $p_kelurahan->trkecamatan->get();
        $p_kabupaten = $p_kecamatan->trkabupaten->get();
        $p_prov = $p_kabupaten->trpropinsi->get();

        //if($p_daftar->c_tinjauan == '1') $method = 'update';
        $method = 'save';
        $data['waktu_awal'] = $this->lib_date->get_date_now();
        $data['save_method'] = $method;
        $data['id_daftar'] = $id_daftar;
        $data['permohonan'] = $p_daftar;
        $data['no_daftar'] = $p_daftar->pendaftaran_id;
        $data['nama_pemohon'] = $p_pemohon->n_pemohon;
        $data['nama_usaha'] = $p_usaha->n_perusahaan;
        $data['alamat_usaha'] = $p_usaha->a_perusahaan;
        $data['alamat_pemohon'] = $p_pemohon->a_pemohon.', '.$p_kelurahan->n_kelurahan.', '.$p_kecamatan->n_kecamatan.', '.
                                  $p_kabupaten->n_kabupaten.', '.$p_prov->n_propinsi;
        $data['jenis_izin'] = $p_izin->n_perizinan;
        $data['id_izin'] = $p_izin->id;

        $data['nama_jenis'] = $p_jenis->n_permohonan;
        $data['nama_kelompok'] = $p_kelompok->n_kelompok;
        $data['list'] = $p_izin->trproperty->order_by('c_parent_order asc, c_order asc')->get();
        //----
//        $data_property = new tmpermohonan_tmproperty_jenisperizinan();
//        $data_property->where('tmpermohonan_id', $p_daftar->id)->get();
//        //Cek Data Entry Lama
//        if(!$data_property->id){
//            if($p_daftar->id_lama) $p_daftar = $this->permohonan->get_by_id($p_daftar->id_lama);
//        }
        //----
        $data['list_daftar'] = $p_daftar->tmproperty_jenisperizinan->get();
        $data['list_klasifikasi'] = $p_daftar->tmproperty_klasifikasi->get();
        $data['list_prasarana'] = $p_daftar->tmproperty_prasarana->get();
		
		$js =  "
                $(document).ready(function() {
                    $(\"#tabs\").tabs();
                } );
                
                $(document).ready(function() {
                $('.monbulan').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });

            });

            function finishAjax(id, response){
                $('#'+id).html(unescape(response));
                $('#'+id).fadeIn();
            }
            ";

        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Entry Data Teknis";
        $this->template->build('entrydata_edit', $this->session_info);
    }

    /*
     * Save and update for manipulating data.
     */
    public function save()
    {
        $this->perizinan = new trperizinan();
        $perizinan = $this->perizinan->get_by_id($this->input->post('id_izin'));
        $property = $perizinan->trretribusi->get();

        if ($property->m_perhitungan=="1")
        {
			$list_prop = new trperizinan_trproperty();
            $list_prop->where('trperizinan_id', $this->input->post('id_izin'));
            $hasil = $list_prop->get();
            
			foreach ($hasil as $dt)
            {
                $a = $dt->trproperty_id;
                if ($a!=="45")
                {
                    $this->index2();
                }
                else if ($a=="45")
                {
                    $this->save_true();
                }
            }
        }
        else 
        {
            $this->save_true();
        }


    }

    public function save_true() {
		       
        //mengecek nilai property "retribusi" dari tabel trperizinan_trproperty
//        $this->perizinan = new trperizinan();
//        $perizinan = $this->perizinan->get_by_id($this->input->post('id_izin'));
//        $property = $perizinan->trretribusi->get();
//        if ($property->m_perhitungan=="1")
//        {
//
//
//        $list_prop = new trperizinan_trproperty();
//        $list_prop->where('trperizinan_id', $this->input->post('id_izin'));
//        $hasil = $list_prop->get();
//        foreach ($hasil as $dt)
//        {
//
//        }
//        $a = $dt->trproperty_id;
//        if ($a!="45")
//        {
//          redirect('pendataan/index2');
//        }
//        }
        

        $permohonan = new tmpermohonan();
        $daftar_id = $this->input->post('id_daftar');
        $permohonan->get_by_id($daftar_id);
        
//         $izin = $permohonan->trperizinan->get();
//         $query1 = "delete tmproperty_jenisperizinan_trproperty as f from trperizinan as c
//         inner join trperizinan_trproperty as d on d.trperizinan_id=c.id
//         inner join trproperty as e on d.trproperty_id=e.id
//         inner join tmproperty_jenisperizinan_trproperty as f on f.trproperty_id=e.id
//         inner join tmproperty_jenisperizinan as g on f.tmproperty_jenisperizinan_id=g.id
//         where e.id ='45' and c.id = '".$izin->id."'
//         ";
//         $this->db->query($query1);
//
//         $query2 = "delete tmproperty_jenisperizinan_trproperty as f from trperizinan as c
//         inner join trperizinan_trproperty as d on d.trperizinan_id=c.id
//         inner join trproperty as e on d.trproperty_id=e.id
//         inner join tmproperty_jenisperizinan_trproperty as f on f.trproperty_id=e.id
//         inner join tmproperty_jenisperizinan as g on f.tmproperty_jenisperizinan_id=g.id
//         where e.id ='414' and c.id = '".$izin->id."'
//            ";
//         $this->db->query($query2);


        $entry_id = $this->input->post('entry_id');
        $property_id = $this->input->post('property_id');
        $entry = $this->input->post('property_value');
        $koefisien_id = $this->input->post('koefisien_id');
        $entry2 = $this->input->post('property_value2');
        $koefisien_id2 = $this->input->post('koefisien_id2');
        $entry_len = count($property_id);

        $is_array = NULL;
        $updated = FALSE;
        $daftar_awal = new tmpermohonan_tmproperty_jenisperizinan();
        $daftar_awal->where('tmpermohonan_id', $daftar_id)->get();
        if($daftar_awal->id) $updated = TRUE;

            ##Added by Indra to fix duplicate property data##
            $entry_awal = new tmproperty_jenisperizinan();
            $entry_property = $entry_awal->where('pendaftaran_id',$permohonan->pendaftaran_id)
                    ->get()->all;
            //$entry_awal->delete_all();
            #################################################
	    foreach($entry_property as $entry_awal):	
                
        //for($i=0;$i < $entry_len;$i++) {
            //if($is_array !== $property_id[$i]) {
//        		$entry_awal = new tmproperty_jenisperizinan(); //ini yang buat error 2013-12-04
//				$entry_awal->where_related($permohonan)->get();
//				$entry_awal->delete_all();				
//		if(isset($entry_id[$i])){
                $property_awal = new tmproperty_jenisperizinan_trproperty();
                $property_awal->where('tmproperty_jenisperizinan_id', $entry_awal->id)->get();
                $property_awal->delete();
                
                $daftar_awal = new tmpermohonan_tmproperty_jenisperizinan();
                $daftar_awal->where('tmproperty_jenisperizinan_id', $entry_awal->id)->get();
                $daftar_awal->delete();
//		}
                $entry_awal->delete();
                
                //$is_array = $property_id[$i];
            endforeach;
            //}
            
        //}
//        $daftar_awal = new tmpermohonan_tmproperty_jenisperizinan();
//        $daftar_awal->where('tmpermohonan_id', $daftar_id)->get();
//        $daftar_awal->delete();

        $izin = $permohonan->trperizinan->get();

        $kelompok = $izin->trkelompok_perizinan->get();

        /**
         * Berdasarkan request dari Agam, semua izin masuk ke Penjadwalan Tinjauan
         */
        /*switch($kelompok->id){
            case 1://Izin tidak bertarif dengan rekomendasi
                //$id_status = "5";//Masuk ke Rekomendasi [Lihat Tabel trstspermohonan]
                //break;
            case 3://Izin tidak bertarif dengan kajian Teknis
                //$id_status = "6";//Masuk ke Pembuatan BAP [Lihat Tabel trstspermohonan]
                //break;
            case 2://Izin tidak bertarif dengan tinjauan lapangan
            case 4://Izin bertarif dengan tinjauan lapangan
            case 5://Izin bertarif Tanpa Tinjauan Lapangan
            */
        //$id_status = "4";//Masuk ke Penjadwalan Tinjauan[Lihat Tabel trstspermohonan]
        //    break;
        //}

        $status_izin = $permohonan->trstspermohonan->get();
        ## Penambahan agar langkah perizinan tidak dihardcode ##
        $this->load->model('permohonan/trlangkah_perizinan');
        $langkah_perizinan = new trlangkah_perizinan();
        $id_status = $langkah_perizinan->nextStep($kelompok->id, $status_izin->id);
        ##########################################################################

        for($i=0;$i < $entry_len;$i++) {
            if($is_array !== $property_id[$i]) {
                $relasi_entry = new trproperty();
                $relasi_entry->get_by_id($property_id[$i]);
                
                //Update 9 Februari 2014 looping untuk setiap tim
//                $obj_trtanggal_survey = $permohonan->trtanggal_survey->get();
//                $tim_teknis = new tim_teknis();
//                $semua_tim = $tim_teknis->where('trtanggal_survey_id', $obj_trtanggal_survey->id)->get()->all;
//                foreach($semua_tim as $tim_survey):
                    $entry_data = new tmproperty_jenisperizinan();
                    $entry_data->pendaftaran_id = $permohonan->pendaftaran_id;
                    $entry_data->v_property = $entry[$i];
                    

                    if($updated){
                        if($status_izin->id == $id_status){
                            $entry_data->v_tinjauan = $entry[$i];
                            $entry_data->k_tinjauan = $koefisien_id[$i];
                        }else{
                            $entry_data->v_tinjauan = $entry2[$i];
                            $entry_data->k_tinjauan = $koefisien_id2[$i];
                        }
                    }else{
                        $entry_data->v_tinjauan = $entry[$i];
                        $entry_data->k_tinjauan = $koefisien_id[$i];
                    }
                    $entry_data->v_tinjauan = null;
                    $entry_data->k_tinjauan = null;
                    $entry_data->k_property = $koefisien_id[$i];
                    $entry_data->entry_flag = 1;
//                    $entry_data->tim_teknis_id = $tim_survey->id;

                    /* Save tmproperty_jenisperizinan() & tmproperty_jenisperizinan_trproperty() */
                    $entry_data->save($relasi_entry);
                    $entry_data_id = new tmproperty_jenisperizinan();
                    $entry_data_id->select_max('id')->get();
                    /* Save tmpermohonan_tmproperty_jenisperizinan() */
                    $update = $entry_data_id->save($permohonan);

                    if($relasi_entry->id == '12'){ //Hanya untuk KLASIFIKASI
                        $klasifikasi_id = $this->input->post('klasifikasi_id');
                        $retribusi_id = $this->input->post('retribusi_id');
                        $koef_value = $this->input->post('koef_value');
                        $koef_id = $this->input->post('koef_id');
                        $koef_value2 = $this->input->post('koef_value2');
                        $koef_id2 = $this->input->post('koef_id2');
                        $klasifikasi_len = count($retribusi_id);
                        $is_array_klasifikasi = NULL;

                        for($z=0;$z < $klasifikasi_len;$z++) {
                            if($is_array_klasifikasi !== $retribusi_id[$z]) {
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

                        for($z=0;$z < $klasifikasi_len;$z++) {
                            if($is_array_klasifikasi !== $retribusi_id[$z]) {
                                $relasi_klasifikasi = new trkoefesientarifretribusi();
                                $relasi_klasifikasi->get_by_id($retribusi_id[$z]);
                                $klasifikasi_data = new tmproperty_klasifikasi();
                                $klasifikasi_data->pendaftaran_id = $permohonan->pendaftaran_id;
                                $klasifikasi_data->v_klasifikasi = $koef_value[$z];
                                $klasifikasi_data->k_klasifikasi = $koef_id[$z];
                                if($updated){
                                    $klasifikasi_data->v_tinjauan = $koef_value2[$z];
                                    $klasifikasi_data->k_tinjauan = $koef_id2[$z];
                                }else{
                                    $klasifikasi_data->v_tinjauan = $koef_value[$z];
                                    $klasifikasi_data->k_tinjauan = $koef_id[$z];
                                }
                                /* Save tmproperty_klasifikasi() & tmproperty_klasifikasi_trkoefesientarifretribusi() */
                                $klasifikasi_data->save($relasi_klasifikasi);
                                $klasifikasi_data_id = new tmproperty_klasifikasi();
                                $klasifikasi_data_id->select_max('id')->get();
                                /* Save tmpermohonan_tmproperty_jenisperizinan() */
                                $klasifikasi_data_id->save($permohonan);
                            }
                            $is_array_klasifikasi = $retribusi_id[$z];
                        }
                    }else if($relasi_entry->id == '29'){ //Hanya untuk PRASARANA
                        $prasarana_id = $this->input->post('prasarana_id');
                        $retribusi_id3 = $this->input->post('retribusi_id3');
                        $koef_value3 = $this->input->post('koef_value3');
                        $koef_id3 = $this->input->post('koef_id3');
                        $koef_value4 = $this->input->post('koef_value4');
                        $koef_id4 = $this->input->post('koef_id4');
                        $prasarana_len = count($retribusi_id3);
                        $is_array_prasarana = NULL;

                        for($x=0;$x < $prasarana_len;$x++) {
                            if($is_array_prasarana !== $retribusi_id3[$x]) {
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

                        for($x=0;$x < $prasarana_len;$x++) {
                            if($is_array_prasarana !== $retribusi_id3[$x]) {
                                $relasi_prasarana = new trkoefesientarifretribusi();
                                $relasi_prasarana->get_by_id($retribusi_id3[$x]);
                                $prasarana_data = new tmproperty_prasarana();
                                $prasarana_data->pendaftaran_id = $permohonan->pendaftaran_id;
                                $prasarana_data->v_prasarana = $koef_value3[$x];
                                $prasarana_data->k_prasarana = $koef_id3[$x];
                                if($updated){
                                    $prasarana_data->v_tinjauan = $koef_value4[$x];
                                    $prasarana_data->k_tinjauan = $koef_id4[$x];
                                }else{
                                    $prasarana_data->v_tinjauan = $koef_value3[$x];
                                    $prasarana_data->k_tinjauan = $koef_id3[$x];
                                }
                                /* Save tmproperty_prasarana() & tmproperty_prasarana_trkoefesientarifretribusi() */
                                $prasarana_data->save($relasi_prasarana);
                                $prasarana_data_id = new tmproperty_prasarana();
                                $prasarana_data_id->select_max('id')->get();
                                /* Save tmpermohonan_tmproperty_jenisperizinan() */
                                $prasarana_data_id->save($permohonan);
                            }
                            $is_array_prasarana = $retribusi_id3[$x];
                        }
                    }
//                endforeach;
            }
            $is_array = $property_id[$i];
        }
        $izin = $permohonan->trperizinan->get();
        $kelompok = $izin->trkelompok_perizinan->get();

        /*if($kelompok->id == "1") $id_status = "5"; //Rekomendasi [Lihat Tabel trstspermohonan()]
        else if($kelompok->id == "3") $id_status = "6"; //Pembuatan BAP [Lihat Tabel trstspermohonan()]
        else if($kelompok->id == "2" || $kelompok->id == "4") $id_status = "4"; //Survey Lokasi [Lihat Tabel trstspermohonan()]*/

        //$status_izin = $permohonan->trstspermohonan->get();

        $status_skr = $this->_status_entry_data; //Entry Data [Lihat Tabel trstspermohonan()]

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
            }*/

            $this->__input_tracking_progress($daftar_id, $status_skr, $id_status);

            /*$tracking_izin = new tmtrackingperizinan();
            $tracking_izin->get_by_id($tracking_id);
            //$tracking_izin->pendaftaran_id = $permohonan->pendaftaran_id;
            $tracking_izin->status = 'Update';
            $tracking_izin->d_entry = $this->lib_date->get_date_now();
            $tracking_izin->save();

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

            //Jika termasuk Izin yang mempunyai BAP
            if(in_array($id_status,$this->__get_izin_dengan_bap())){
            //if($id_status == "5" || $id_status == "6"){
                $data_id = new tmbap();
                $data_id->select_max('id')->get();
                $data_id->get_by_id($data_id->id);
                $data_tahun = date("Y");
                //Per Tahun Auto Restart NoUrut
                if($permohonan->d_tahun == $data_tahun)
                $data_urut = $data_id->i_urut + 1;
                else $data_urut = 1;

                $i_urut = strlen($data_urut);
                for($i=4;$i>$i_urut;$i--){
                    $data_urut = "0".$data_urut;
                }

                $data_izin = $izin->id;
                $i_izin = strlen($data_izin);
                for($i=3;$i>$i_izin;$i--){
                    $data_izin = "0".$data_izin;
                }

                $data_bulan = $this->lib_date->set_month_roman(date("n"));

				/*START Setting BAP dan SKRD dari Report Component*/
				$trperizinan_id=$izin->id;
				$this->load->model('report_component/Report_component_model');
				$this->report_component_model=new Report_component_model();
				$setting_component_bap=$this->report_component_model->get_report_component($this->report_component_model->kode_bap,$trperizinan_id, $daftar_id);
				$setting_component_skrd=$this->report_component_model->get_report_component($this->report_component_model->kode_skrd,$trperizinan_id, $daftar_id);
				/*END Setting BAP dan SKRD dari Report Component*/
              	
				$data_bap = "BAP";
                $data_skrd = "SKRD";
				/*START Ambil nomor dari setting jika ada*/
				if(isset($setting_component_bap['format_nomor']) && 
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
                $bap2->bap_id = $no_bap;
                $bap2->no_skrd = $no_skrd;
                $bap2->pendaftaran_id = $permohonan->pendaftaran_id;
                $bap2->i_urut = $data_urut;
                $bap2->save($permohonan);
            }
        }

        if($update) {
            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $g = $this->sql($u_ser);
//          $jam = date("H:i:s A");
            $p = $this->db->query("call log ('Entry Perizinan','Update ".$permohonan->pendaftaran_id."','".$tgl."','".$u_ser."')");

            redirect('pendataan');
        }
        
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
