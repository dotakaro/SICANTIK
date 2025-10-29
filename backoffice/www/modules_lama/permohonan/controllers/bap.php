<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of penjadwalan
 *
 * @author Eva, Dichi Al Faridi
 */
class Bap extends WRC_AdminCont {

    private $_status_pembuatan_bap = 6;

    public function __construct() {
        parent::__construct();
        $this->sk = new tmpermohonan();
        $this->bapp = new tmbap();
        $this->propertyizin = new tmproperty_jenisperizinan();
        $this->koefisien = new trkoefesientarifretribusi();
        $this->perizinan = new trperizinan();
        $this->pemohon = new tmpemohon();
        $this->trkabupaten = new trkabupaten();
        $this->bap = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->bap = NULL;

        foreach ($list_auths as $list_auth) {
            if ($list_auth->id_role === '12') {
                $enabled = TRUE;
                $this->bap = new user_auth();
            }
        }
		
        if (!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index($sALL=0) {
//        $daftar = new tmpermohonan();
//        $user = new user();
//        $izin = new trperizinan();
//
//        $user->where('username', $this->session->userdata('username'))->get();
//        $data['list_izin'] = $izin->where_related($user)->get();
//        $query = $daftar
//                ->where('c_pendaftaran', 1) //Pendaftaran selesai
//                ->where('c_izin_selesai', 0) //SK Belum diserahkan
//                ->where('c_izin_dicabut', 0) //Permohonan tidak dicabut
//                ->order_by('id', 'DESC')->get();
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
        
		if($this->__is_administrator()){
			//$query_filter_user=" ";
			//$query_join_perizinan_user= " ";
            $query_filter_unit = "";
		}else{
			//$query_filter_user=" AND P.user_id = '" . $username->id . "' ";
			//$query_join_perizinan_user= " INNER JOIN trperizinan_user AS P ON  P.trperizinan_id = C.id ";
            $current_unitkerja = $this->__get_current_unitkerja();
            $query_filter_unit = " AND L.trunitkerja_id ={$current_unitkerja->id} ";
		}
		if($sALL==1){
            $query = "SELECT DISTINCT A.id, A.pendaftaran_id, A.c_tinjauan, A.d_terima_berkas, A.d_survey,
                    A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
                    C.id idizin, C.n_perizinan, E.n_pemohon,
                    G.id idjenis, G.n_permohonan, A.c_izin_selesai, L.id AS tim_teknis_id, L.status_tinjauan,
                    I.c_pesan, I.id as bap_id, M.n_unitkerja
                    FROM tmpermohonan as A
                    INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
                    INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
                    INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
                    INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
                    INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
                    INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
                    INNER JOIN tmbap_tmpermohonan as H ON H.tmpermohonan_id = A.id
                    INNER JOIN tmbap as I ON H.tmbap_id = I.id".
                    //        $query_join_perizinan_user.
                    " INNER JOIN tmpermohonan_trtanggal_survey J ON J.tmpermohonan_id = A.id
                    INNER JOIN trtanggal_survey K ON K.id = J.trtanggal_survey_id
                    INNER JOIN tim_teknis L ON (L.trtanggal_survey_id=K.id AND L.id=I.tim_teknis_id)
                    INNER JOIN trunitkerja M ON M.id=L.trunitkerja_id ".
                    "WHERE A.c_pendaftaran = 1
                    AND A.c_izin_dicabut = 0
                    AND A.c_izin_selesai = 1".
                    //$query_filter_user.
                    "AND A.d_terima_berkas between '$tgla' and '$tglb'
                    AND A.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})
                    AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
                    AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                            INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                            WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = {$this->_status_pembuatan_bap})>0
                    $query_filter_unit
                    order by A.id DESC";
		}else{
            $query = "SELECT DISTINCT A.id, A.pendaftaran_id, A.c_tinjauan, A.d_terima_berkas, A.d_survey,
                    A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
                    C.id idizin, C.n_perizinan, E.n_pemohon,
                    G.id idjenis, G.n_permohonan, A.c_izin_selesai, L.id AS tim_teknis_id, L.status_tinjauan,
                    I.c_pesan, I.id as bap_id, M.n_unitkerja
                    FROM tmpermohonan as A
                    INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
                    INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
                    INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
                    INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
                    INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
                    INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
                    INNER JOIN tmbap_tmpermohonan as H ON H.tmpermohonan_id = A.id
                    INNER JOIN tmbap as I ON H.tmbap_id = I.id".
                            //$query_join_perizinan_user.
                    " INNER JOIN tmpermohonan_trtanggal_survey J ON J.tmpermohonan_id = A.id
                    INNER JOIN trtanggal_survey K ON K.id = J.trtanggal_survey_id
                    INNER JOIN tim_teknis L ON (L.trtanggal_survey_id=K.id AND L.id=I.tim_teknis_id)
                    INNER JOIN trunitkerja M ON M.id=L.trunitkerja_id ".
                    "WHERE A.c_pendaftaran = 1
                    AND A.c_izin_dicabut = 0
                    AND A.c_izin_selesai = 0
                    AND A.d_terima_berkas between '$tgla' and '$tglb'
                    AND A.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})
                    AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
                    AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                            INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                            WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = {$this->_status_pembuatan_bap})>0
                    $query_filter_unit
                    order by A.id DESC";
        }
		$data['list'] = $query;
		
        $data['jenis_izin'] = "";
        $data['jenis_permohonan'] = "";
        $data['namapemohon'] = "";
        $data['tanggalpermohonan'] = "";
        $data['save_method'] = "save";
        $data['id'] = "";
		$data['sALL'] = $sALL;
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
        $this->session_info['page_name'] = "Data Pembuatan Berita Acara Pemeriksaan";
        $this->template->build('bap_list', $this->session_info);
    }

    public function viewBAP($id=NULL, $idizin=NULL, $tim_teknis_id = NULL) {
        $permohonan = new tmpermohonan();
        $perizinan = new trperizinan();
        $property = new trproperty();
        $jenisproperty = new tmproperty_jenisperizinan();
        $koefesientarifretribusi = new trkoefesientarifretribusi();

        $retribusi = new trretribusi();

        $permohonan->where('id', $id)->get();

        $permohonan->trperizinan->get();
        $permohonan->tmpemohon->get();
        $permohonan->tmperusahaan->get();
        $bap = $permohonan->tmbap->where('tim_teknis_id',$tim_teknis_id)->get();
        $p_pemohon = $permohonan->tmpemohon->get();
        $p_kelurahan = $p_pemohon->trkelurahan->get();
        $p_kecamatan = $p_kelurahan->trkecamatan->get();
        $p_kabupaten = $p_kecamatan->trkabupaten->get();
        $p_prov = $p_kabupaten->trpropinsi->get();


        $permohonan->$perizinan->where('id', $idizin)->get();
        $permohonan->$perizinan->$retribusi->get();  //where('perizinan_id', $idizin)->get();

        /*$k_property = $permohonan->$perizinan->$property->$jenisproperty->k_property;
        $koefesientarifretribusi->where('id', $k_property)->get();*///diremark karena membuat error

        $data['list'] = $permohonan->$perizinan->trproperty->order_by('c_parent_order asc, c_order asc')->get();
        $data['list_daftar'] = $permohonan->tmproperty_jenisperizinan->where('tim_teknis_id',$tim_teknis_id)->get();
        $data['tim_teknis_id'] = $tim_teknis_id;
        $data['n_manual'] = $this->getTinjauan($permohonan->id, '45');
        $data['m_hitung'] = $permohonan->$perizinan->$retribusi->m_perhitungan;
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

        $data['tglperiksa'] = $permohonan->d_survey;
        $data['id_bap'] = $bap->id;
        $data['no_bap'] = $bap->bap_id;
        $data['pesan'] = $bap->c_pesan;
        $data['status'] = $bap->status_bap;

        //cek data

        $index = $koefesientarifretribusi->index_kategori;
        $permohonan->trperizinan->get();
        if ($permohonan->trperizinan->id == '2')
            $nilai_retribusi = $bap->nilai_retribusi;
        else
            $nilai_retribusi = $permohonan->$perizinan->$retribusi->v_retribusi;
        $data['retribusi'] = $nilai_retribusi;

        $data['indexcba'] = $index;
        $data['xx'] = $idizin;
//        $data['yy'] = $k_property;


        $js_date = "
            $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();
                } );
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
        $this->session_info['page_name'] = "Berita Acara Pemeriksaan";
        $this->template->build('bap_edit', $this->session_info);
    }

    public function viewBAP2($id=NULL, $idizin=NULL) {
        $permohonan = new tmpermohonan();
        $perizinan = new trperizinan();
        $property = new trproperty();
        $jenisproperty = new tmproperty_jenisperizinan();
        $koefesientarifretribusi = new trkoefesientarifretribusi();
        //$bap = new tmbap();
        $retribusi = new trretribusi();

        $permohonan->where('id', $id)->get();

        $permohonan->trperizinan->get();
        $permohonan->tmpemohon->get();
        $permohonan->tmperusahaan->get();
        $bap = $permohonan->tmbap->get();

        $permohonan->$perizinan->where('id', $idizin)->get();
        $permohonan->$perizinan->$retribusi->get();  //where('perizinan_id', $idizin)->get();


        $k_property = $permohonan->$perizinan->$property->$jenisproperty->k_property;
        $koefesientarifretribusi->where('id', $k_property)->get();

        $data['n_manual'] = $this->getTinjauan($permohonan->id, '45');
        $data['m_hitung'] = $permohonan->$perizinan->$retribusi->m_perhitungan;
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
        $data['no_bap'] = $bap->bap_id;
        $data['pesan'] = $bap->c_pesan;
        $data['status'] = $bap->status_bap;

        //cek data

        $index = $koefesientarifretribusi->index_kategori;
        $permohonan->trperizinan->get();
        if ($permohonan->trperizinan->id == '2')
            $nilai_retribusi = $bap->nilai_retribusi;
        else
            $nilai_retribusi = $permohonan->$perizinan->$retribusi->v_retribusi;
        $data['retribusi'] = $nilai_retribusi;

        $data['indexcba'] = $index;
        $data['xx'] = $idizin;
        $data['yy'] = $k_property;

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
        $this->session_info['page_name'] = "Berita Acara Pemeriksaan";
        $this->template->build('bap_edit2', $this->session_info);
    }
	
	/**
	* Action untuk melihat data BAP tanpa menyimpan
	* @param undefined $id
	* @param undefined $idizin
	* 
*/
	public function viewBAPData($id=NULL, $idizin=NULL) {
        $permohonan = new tmpermohonan();
        $perizinan = new trperizinan();
        $property = new trproperty();
        $jenisproperty = new tmproperty_jenisperizinan();
        $koefesientarifretribusi = new trkoefesientarifretribusi();

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
        $permohonan->$perizinan->$retribusi->get();  //where('perizinan_id', $idizin)->get();

        $k_property = $permohonan->$perizinan->$property->$jenisproperty->k_property;
        $koefesientarifretribusi->where('id', $k_property)->get();

        $data['list'] = $permohonan->$perizinan->trproperty->order_by('c_parent_order asc, c_order asc')->get();
        $data['list_daftar'] = $permohonan->tmproperty_jenisperizinan->get();

        $data['n_manual'] = $this->getTinjauan($permohonan->id, '45');
        $data['m_hitung'] = $permohonan->$perizinan->$retribusi->m_perhitungan;
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

        $data['tglperiksa'] = $permohonan->d_survey;
        $data['id_bap'] = $bap->id;
        $data['no_bap'] = $bap->bap_id;
        $data['pesan'] = $bap->c_pesan;
        $data['status'] = $bap->status_bap;

        //cek data

        $index = $koefesientarifretribusi->index_kategori;
        $permohonan->trperizinan->get();
        if ($permohonan->trperizinan->id == '2')
            $nilai_retribusi = $bap->nilai_retribusi;
        else
            $nilai_retribusi = $permohonan->$perizinan->$retribusi->v_retribusi;
        $data['retribusi'] = $nilai_retribusi;

        $data['indexcba'] = $index;
        $data['xx'] = $idizin;
        $data['yy'] = $k_property;


        $js_date = "
            $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();
            });
            ";
        $this->template->set_metadata_javascript($js_date);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Berita Acara Pemeriksaan";
        $this->template->build('view_bap_data', $this->session_info);
    }

    public function view($idjenisizin=NULL, $nopendaftaran=NULL, $idpemohon=NULL) {
        $bap = new tmpermohonan();
        $pemohon = new tmpemohon();

        $data['list'] = $bap->where('pendaftaran_id', $this->input->post('nomorpendaftaran'))->get();

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
        $this->session_info['page_name'] = "Data Pembuatan Berita Acara Pemeriksaan";
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
        $this->template->build('bap_edit', $this->session_info);
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

    public function popUp($a = Null, $b = Null) {
        ?>
        <script language="JavaScript" onclick="link(keuangan)" >
            alert("Catatan Harus Di Isi");
        </script>
        <?php

        $data = $this->viewBAP($a, $b);
    }

    public function save() {
        $no_pendaftaran = NULL;
        $nama_izin = NULL;
        $id_permohonan = $this->input->post('id');

        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($id_permohonan);
        $perizinan = $permohonan->trperizinan->get();

        if ($this->input->post('pesankomentar') == "." || $this->input->post('pesankomentar') == "" || $this->input->post('pesankomentar') == "'")
//        redirect('permohonan/bap/viewBAP/'.$permohonan->id.'/'.$perizinan->id);
            redirect('permohonan/bap/popUp/' . $permohonan->id . '/' . $perizinan->id);
        $cek = $this->input->post('id_bap');
        $bap = new tmbap();
        $bap->get_by_id($cek);
        if ($bap->id) {
            $bap->pendaftaran_id = $this->input->post('nopendaftaran');
            $bap->c_pesan = $this->input->post('pesankomentar');
            $bap->tim_teknis_id = $this->input->post('tim_teknis_id');
            $status = $this->input->post('status');
            $bap->nilai_retribusi = $this->input->post('nilai_retribusi');
            $bap->nilai_bap_awal = $this->input->post('nilai_retribusi');
            $no_pendaftaran = $this->input->post('nopendaftaran');
            $nama_izin = $this->input->post('jenislayanan');

                /*START Setting BAP dan SKRD dari Report Component*/
                $trperizinan_id=$perizinan->id;
                $this->load->model('report_component/Report_component_model');
                $this->report_component_model=new Report_component_model();

                if(!$bap->bap_id){
                    $data_bap = "BAP";
                    $setting_component_bap=$this->report_component_model->get_report_component($this->report_component_model->kode_bap,$trperizinan_id, $id_permohonan);
                    if(isset($setting_component_bap['format_nomor']) &&
                            $setting_component_bap['format_nomor']!=''){
                            $no_bap=$setting_component_bap['format_nomor'];
                    }else{//Jika tidak ada, maka gunakan format penomoran yang lama
                            $no_bap = $data_urut."/"
                            .$data_bap."/".$data_izin."/"
                            .$data_bulan."/".$data_tahun;
                    }
                    $bap->bap_id = $no_bap;
                }

                if(!$bap->no_skrd){
                    $data_skrd = "SKRD";
                    $setting_component_skrd=$this->report_component_model->get_report_component($this->report_component_model->kode_skrd,$trperizinan_id, $id_permohonan);
                    if(isset($setting_component_skrd['format_nomor']) &&
                        $setting_component_skrd['format_nomor']!=''){
                        $no_skrd = $setting_component_skrd['format_nomor'];
                    }else{//Jika tidak ada, maka gunakan format penomoran yang lama
                        $no_skrd = $data_urut."/"
                            .$data_skrd."/".$data_izin."/"
                            .$data_bulan."/".$data_tahun;
                    }
                    $bap->no_skrd = $no_skrd;
			    }
			/*END Setting BAP dan SKRD dari Report Component*/

            $update = $bap->save($permohonan);
        } else {
            /* Input Data */
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
			$trperizinan_id=$perizinan->id;
			$this->load->model('report_component/Report_component_model');
			$this->report_component_model=new Report_component_model();
			$setting_component_bap=$this->report_component_model->get_report_component($this->report_component_model->kode_bap,$trperizinan_id, $id_permohonan);
			$setting_component_skrd=$this->report_component_model->get_report_component($this->report_component_model->kode_skrd,$trperizinan_id, $id_permohonan);
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

            $this->bapp->bap_id = $no_bap;
            $this->bapp->no_skrd = $no_skrd;
            $this->bapp->i_urut = $data_urut;
            $this->bapp->pendaftaran_id = $this->input->post('nopendaftaran');
            $this->bapp->c_pesan = $this->input->post('pesankomentar');
//            $this->bapp->status_bap = "1"; //Default 1 -> Diizinkan
            $status = $this->input->post('status');
            $this->bapp->nilai_retribusi = $this->input->post('nilai_retribusi');
            $this->bapp->nilai_bap_awal = $this->input->post('nilai_retribusi');
            $no_pendaftaran = $this->input->post('nopendaftaran');
            $nama_izin = $this->input->post('jenislayanan');

            $update = $this->bapp->save($permohonan);
        }

        /* Input Data Tracking Progress */
        $status_izin = $permohonan->trstspermohonan->get();

        $status_skr = $this->_status_pembuatan_bap; //Pembuatan BAP [Lihat Tabel trstspermohonan()]

        if($status_izin->id == $status_skr){
            $this->load->model('permohonan/trlangkah_perizinan');
            $langkah_perizinan = new trlangkah_perizinan();
            $status_izin = $permohonan->trstspermohonan->get();
            $next_status = $langkah_perizinan->nextStep($permohonan->trperizinan->trkelompok_perizinan->id, $status_izin->id);
            $this->__input_tracking_progress($id_permohonan, $status_skr, $next_status);
        }
        /*$status_data = "3"; //Entri data [Lihat Tabel trstspermohonan()]
        $status_rekom = "5"; //Rekomendasi [Lihat Tabel trstspermohonan()]
        $id_status = "7"; //Penetapan Izin [Lihat Tabel trstspermohonan()]
        $status_retribusi = 18; //Perhitungan Retribusi [Lihat Tabel trstspermohonan()]
        if(
            in_array($perizinan->trkelompok_perizinan->id,$this->__get_izin_dengan_tarif())
            && $status_izin->id == $status_skr
        ){ //Jika ada tarif dan status sekarang adalah "Pembuatan BAP"
            $this->__input_tracking_progress($id_permohonan, $status_skr, $status_retribusi);
        }else if (
            !in_array($perizinan->trkelompok_perizinan->id,$this->__get_izin_dengan_tarif())
            && $status_izin->id == $status_skr
        ) {
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
            /*$this->__input_tracking_progress($id_permohonan, $status_skr, $status_rekom);
        } /*else if ($status_izin->id == $status_rekom) {
            $sts_izin = new trstspermohonan();
            $sts_izin->get_by_id($status_data);
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
            /*$tracking_izin = new tmtrackingperizinan();
            $tracking_izin->pendaftaran_id = $permohonan->pendaftaran_id;
            $tracking_izin->status = 'Insert';
            $tracking_izin->d_entry_awal = $this->lib_date->get_date_now();
            $tracking_izin->d_entry = $this->lib_date->get_date_now();
            $sts_izin = new trstspermohonan();
            $sts_izin->get_by_id($status_skr); //[Lihat Tabel trstspermohonan()]
            $sts_izin->save($permohonan);
            $tracking_izin->save($permohonan);
            $tracking_izin->save($sts_izin);

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
        }*/

        if (!$update) {
            echo '<p>' . $update->error->string . '</p>';
        } else {
//            if ($status === "1") {
//                $text = "SK " . $nama_izin . " dgn no pendaftaran " . $no_pendaftaran;
//                $text .= " tlh selesai. Silahkan ambil SK pd tgl ";
//                $date = $this->_get_day_to_send();
//                $text .= $date;
//                $text .= " dgn biaya ret ";
//                $text .= $this->_get_ret($no_pendaftaran);
//                $text .= ".";
//
//                if(strlen($text) > 160) {
//                    $text = NULL;
//                    $text = "SK dgn no pendaftaran " . $no_pendaftaran;
//                    $text .= " tlh selesai. Silahkan ambil SK pd tgl ";
//                    $text .= $this->_get_day_to_send();
//                    $text .= " dgn biaya ret ";
//                    $text .= $this->_get_ret($no_pendaftaran);
//                    $text .= ".";
//                }
//
//                $permohonan = new tmpermohonan();
//                $permohonan->get_by_id($this->input->post('id'));
//                $permohonan->tmpemohon->get();
//                $number = $permohonan->tmpemohon->telp_pemohon;
//
//                if($this->_number_safe($number)) {
//                    $outbox = new outbox();
//                    $outbox->TextDecoded = $text;
//                    $outbox->DestinationNumber = $number ;
//                    $outbox->DeliveryReport = 'yes';
//                    $outbox->save();
//                }
//            }
        }
        $daftar = new tmpermohonan();
        $daftar->get_by_id($this->input->post('id'));
        $daftar->d_survey = $this->input->post('tglperiksa');
        $daftar->save();

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql($u_ser);
//     $jam = date("H:i:s A");
        $p = $this->db->query("call log ('Pembuatan BAP','Update " . $permohonan->pendaftaran_id . "','" . $tgl . "','" . $u_ser . "')");

        redirect('permohonan/bap');
    }

    public function _number_safe() {
        $is_safe = TRUE;
        return $is_safe;
    }

    public function _get_ret($pendaftaran_id = NULL) {
        $permohonan = new tmpermohonan();
        $permohonan->where('pendaftaran_id', $pendaftaran_id)->get();
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
        $update = $this->bapp
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
        //$permohonan->$tanggal_survey->get();

        $perusahaan = $permohonan->tmperusahaan->get();
        $bap = $permohonan->tmbap->get();
        $pemohon = $permohonan->tmpemohon->get();

        $p_kelurahan = $pemohon->trkelurahan->get();
        $p_kecamatan = $pemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $pemohon->trkelurahan->trkecamatan->trkabupaten->get();

        $daftar = $permohonan->pendaftaran_id;


        $listform = $permohonan->$perizinan->trproperty->order_by('c_parent_order asc, c_order asc')->get();
        $list_daftar = $permohonan->tmproperty_jenisperizinan->get();
        $permohonan->$perizinan->$property->$jenisproperty->where('pendaftaran_id', $daftar)->get();
        $k_property = $permohonan->$perizinan->$property->$jenisproperty->k_property;
        $permohonan->$perizinan->$property->$koefesien->where('id', $k_property)->get();


        //path of the template file
        $nama_surat = "cetak_bap";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/' . $nama_surat . '.odt');
        $odf->setVars('ttd', '');

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

        $petugas = 1;
        $pegawai = new tmpegawai();
//        $odf->setVars('kantor', $app_kantor->value);
        $pegawai->where('status', $petugas)->get();
        $odf->setVars('jabatan', $pegawai->n_jabatan);
        $odf->setVars('nama_pejabat', $pegawai->n_pegawai);
        $odf->setVars('nip_pejabat', $pegawai->nip);


        $wilayah = new trkabupaten();
        if ($app_city->value !== '0') {
            $alamat = $pemohon->a_pemohon . ' ' . $p_kelurahan->n_kelurahan . ', ' .
                    $p_kecamatan->n_kecamatan . ', ' . ucwords(strtolower($p_kabupaten->n_kabupaten));
            $wilayah->get_by_id($app_city->value);
            // $odf->setVars('kabupaten', ucwords(strtolower($wilayah->n_kabupaten)));
            //$odf->setVars('kota', strtoupper($wilayah->n_kabupaten));
        } else {
            $alamat = $pemohon->a_pemohon;
            // $odf->setVars('kabupaten', 'setempat');
            //$odf->setVars('kota', '...........');
        }

        $gede_kota = strtoupper($wilayah->n_kabupaten);
        $kecil_kota = ucwords(strtolower($wilayah->n_kabupaten));
        $odf->setVars('kota4', $gede_kota);

        //alamat
        $this->tr_instansi = new Tr_instansi();
        $alamat = $this->tr_instansi->get_by_id(12);
        $odf->setVars('alamat', ucwords(strtolower($alamat->value)) . ' - ' . $kecil_kota);

        $app_kan = $this->settings->where('name', 'app_kantor')->get();
        $odf->setVars('kantor', $app_kan->value);
//        $odf->setVars('title', strtoupper('Berita Acara Pemeriksaan'));

        $tgl_skr = $this->lib_date->get_date_now();
        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($tgl_skr));
        $odf->setVars('nomor', $bap->bap_id);

        if ($permohonan->d_survey) {
            if ($permohonan->d_survey != '0000-00-00')
                $tgl_periksa = $this->lib_date->mysql_to_human($permohonan->d_survey);
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
            array('property' => 'Nomor Pendaftaran',
                'content' => $permohonan->pendaftaran_id,
            ),
            array('property' => 'Jenis Layanan',
                'content' => $permohonan->$perizinan->n_perizinan,
            ),
            array('property' => 'Nama Pemohon',
                'content' => $pemohon->n_pemohon,
            ),
            array('property' => 'No Telp/HP Pemohon',
                'content' => $pemohon->telp_pemohon,
            ),
            array('property' => 'Alamat Pemohon',
                'content' => $pemohon->a_pemohon,
            ),
            array('property' => 'Nama Perusahaan',
                'content' => $perusahaan->n_perusahaan,
            ),
            array('property' => 'Alamat Perusahaan',
                'content' => $perusahaan->a_perusahaan,
            ),
            array('property' => 'Tanggal Peninjauan',
                'content' => $tgl_periksa,
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
        $entry_id = '';
        $data_entry = '';
        $data_koefisien = 0;
        $data_entryt = '';
        $data_koefisient = 0;
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
                $data_entryt = '';
                $data_koefisient = 0;
            }
            $izin = new trperizinan();
            $izin->get_by_id($idjenis);
            $kelompok = $izin->trkelompok_perizinan->get();
            if ($kelompok->id == "2" || $kelompok->id == "4") {
                $id_koef = $data_koefisient;
                $nilai_entry = $data_entryt;
            } else {
                $id_koef = $data_koefisien;
                $nilai_entry = $data_entry;
            }
            if ($id_koef) {
                $data_koefisien2 = new trkoefesientarifretribusi();
                $data_koefisien2->get_by_id($id_koef);

                if ($nilai_entry) {
                    if ($nilai_entry == " ")
                        $hasil = $data_koefisien2->kategori;
                    else if (strval($nilai_entry) === "0")
                        $hasil = $data_koefisien2->kategori;
                    else
                        $hasil = $nilai_entry;
                }
                else
                    $hasil = $data_koefisien2->kategori;
            }else
                $hasil = $nilai_entry;

            $prop = $data->n_property;

            $izin_property = new trperizinan_trproperty();
            $izin_property->where('trperizinan_id', $idjenis)
                    ->where('trproperty_id', $data->id)->get();
            $id_tl = $izin_property->c_tl_id;
            if ($id_tl == '1') {
                if ($izin_property->c_parent !== $izin_property->trproperty_id) {
                    $listeArticles3 = array(
                        array('property3' => $prop,
                            'content33' => $hasil,
                        ),
                    );

                    $article3 = $odf->setSegment('articles3');
                    foreach ($listeArticles3 AS $element3) {
                        $article3->titreArticle3($element3['property3']);

                        $article3->texteArticle4($element3['content33']);
                        $article3->merge();
                    }
                }
            }
        }
        $listeArticles3 = array(
            array('property3' => 'Catatan Hasil Tinjauan',
                'content33' => $bap->c_pesan,
            ),
        );

        $article3 = $odf->setSegment('articles3');
        foreach ($listeArticles3 AS $element3) {
            $article3->titreArticle3($element3['property3']);

            $article3->texteArticle4($element3['content33']);
            $article3->merge();
        }
        $odf->mergeSegment($article3);

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql($u_ser);
//      $jam = date("H:i:s A");
        $p = $this->db->query("call log ('Pembuatan BAP','Cetak BAP " . $permohonan->pendaftaran_id . "','" . $tgl . "','" . $u_ser . "')");


        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat . '_' . $no_daftar . '.odt');
    }

    public function cetakBAP2($id=NULL, $idjenis=NULL) {
		
    }

    public function cetakBAP_archive($id=NULL, $idjenis=NULL) {

        $this->settings = new settings();
        $this->settings->where('name', 'app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name', 'app_city')->get();
        $app_kantor = $this->settings->where('name', 'app_kantor')->get();

        $permohonan = new tmpermohonan();
        $perizinan = new trperizinan();
        $property = new trproperty();
        $koefesien = new trkoefesientarifretribusi();
        $jenisproperty = new tmproperty_jenisperizinan();

        $permohonan->where('id', $id)->get();
        $permohonan->$perizinan->where('id', $idjenis)->get();

        $perusahaan = $permohonan->tmperusahaan->get();
        $bap = $permohonan->tmbap->get();
        $pemohon = $permohonan->tmpemohon->get();

        $p_kelurahan = $pemohon->trkelurahan->get();
        $p_kecamatan = $pemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $pemohon->trkelurahan->trkecamatan->trkabupaten->get();

        $daftar = $permohonan->pendaftaran_id;


        $listform = $permohonan->$perizinan->trproperty->order_by('c_parent asc, c_order asc')->get();
        $list_daftar = $permohonan->tmproperty_jenisperizinan->get();
        $permohonan->$perizinan->$property->$jenisproperty->where('pendaftaran_id', $daftar)->get();
        $k_property = $permohonan->$perizinan->$property->$jenisproperty->k_property;
        $permohonan->$perizinan->$property->$koefesien->where('id', $k_property)->get();


        //path of the template file
        $nama_surat = "cetak_bap";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/' . $nama_surat . '.odt');
        $odf->setImage('header', 'assets/css/' . $app_folder . '/images/dinas_1.jpg', '17.5', '3.5');
        if ($permohonan->file_ttd)
            $odf->setImage('ttd', 'assets/upload/ttd/' . $permohonan->file_ttd, '2.5', '2.5');
        else
            $odf->setVars('ttd', '');

        $petugas = 1;
        $pegawai = new tmpegawai();
        $pegawai->where('status', $petugas)->get();
        $odf->setVars('kantor', $app_kantor->value);
        $odf->setVars('jabatan', strtoupper($pegawai->n_jabatan));
        $odf->setVars('nama_pejabat', $permohonan->nama_ttd);
        $odf->setVars('nip_pejabat', $permohonan->nip_ttd);

        $wilayah = new trkabupaten();
        if ($app_city->value !== '0') {
            $alamat = $pemohon->a_pemohon . ' ' . $p_kelurahan->n_kelurahan . ', ' .
                    $p_kecamatan->n_kecamatan . ', ' . ucwords(strtolower($p_kabupaten->n_kabupaten));
            $wilayah->get_by_id($app_city->value);
            // $odf->setVars('kabupaten', ucwords(strtolower($wilayah->n_kabupaten)));
            $odf->setVars('kota', strtoupper($wilayah->n_kabupaten));
        } else {
            $alamat = $pemohon->a_pemohon;
            // $odf->setVars('kabupaten', 'setempat');
            $odf->setVars('kota', '...........');
        }


//        $odf->setVars('title', 'Berita Acara Pemeriksaan');

        $tgl_skr = $this->lib_date->get_date_now();
        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($tgl_skr));

        if ($permohonan->d_survey) {
            if ($permohonan->d_survey != '0000-00-00')
                $tgl_periksa = $this->lib_date->mysql_to_human($permohonan->d_survey);
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
            array('property' => 'No BAP',
                'content' => $bap->bap_id,
            ),
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
            array('property' => 'Pesan Komentar',
                'content' => $bap->c_pesan,
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
                if ($entry_daftar->v_tinjauan == " ")
                    $hasil = $data_koefisien2->kategori;
                else if (strval($entry_daftar->v_tinjauan) === "0")
                    $hasil = $data_koefisien2->kategori;
                else
                    $hasil = $entry_daftar->v_tinjauan;
            }
            else
                $hasil = $data_koefisien2->kategori;


            $prop = $data->n_property;

            $listeArticles3 = array(
                array('property3' => $prop,
                    'content33' => $hasil,
                ),
            );

            $article3 = $odf->setSegment('articles3');
            foreach ($listeArticles3 AS $element3) {
                $article3->titreArticle3($element3['property3']);

                $article3->texteArticle4($element3['content33']);
                $article3->merge();
            }
        }
        $odf->mergeSegment($article3);

        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat . '_' . $no_daftar . '.odt');
    }

    public function cetakBAP2_archive($id=NULL, $idjenis=NULL) {

        $this->settings = new settings();
        $this->settings->where('name', 'app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name', 'app_city')->get();
        $app_kantor = $this->settings->where('name', 'app_kantor')->get();

        $permohonan = new tmpermohonan();
        $perizinan = new trperizinan();
        $property = new trproperty();
        $koefesien = new trkoefesientarifretribusi();
        $jenisproperty = new tmproperty_jenisperizinan();

        $permohonan->where('id', $id)->get();
        $permohonan->$perizinan->where('id', $idjenis)->get();

        $perusahaan = $permohonan->tmperusahaan->get();
        $bap = $permohonan->tmbap->get();
        $pemohon = $permohonan->tmpemohon->get();

        $p_kelurahan = $pemohon->trkelurahan->get();
        $p_kecamatan = $pemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $pemohon->trkelurahan->trkecamatan->trkabupaten->get();

        $daftar = $permohonan->pendaftaran_id;

        $listform = $permohonan->$perizinan->trproperty->order_by('c_parent asc, c_order asc')->get();
        $list_daftar = $permohonan->tmproperty_jenisperizinan->get();
        $list_klasifikasi = $permohonan->tmproperty_klasifikasi->get();
        $list_prasarana = $permohonan->tmproperty_prasarana->get();
        $permohonan->$perizinan->$property->$jenisproperty->where('pendaftaran_id', $daftar)->get();
        $k_property = $permohonan->$perizinan->$property->$jenisproperty->k_property;
        $permohonan->$perizinan->$property->$koefesien->where('id', $k_property)->get();


        //path of the template file
        $nama_surat = "cetak_bap2";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/' . $nama_surat . '.odt');
        $odf->setImage('header', 'assets/css/' . $app_folder . '/images/dinas_1.jpg', '17.5', '3.5');
        if ($permohonan->file_ttd)
            $odf->setImage('ttd', 'assets/upload/ttd/' . $permohonan->file_ttd, '2.5', '2.5');
        else
            $odf->setVars('ttd', '');

        $petugas = 1;
        $pegawai = new tmpegawai();
        $pegawai->where('status', $petugas)->get();
        $odf->setVars('kantor', $app_kantor->value);
        $odf->setVars('jabatan', strtoupper($pegawai->n_jabatan));
        $odf->setVars('nama_pejabat', $permohonan->nama_ttd);
        $odf->setVars('nip_pejabat', $permohonan->nip_ttd);

        $wilayah = new trkabupaten();
        if ($app_city->value !== '0') {
            $alamat = $pemohon->a_pemohon . ' ' . $p_kelurahan->n_kelurahan . ', ' .
                    $p_kecamatan->n_kecamatan . ', ' . ucwords(strtolower($p_kabupaten->n_kabupaten));
            $wilayah->get_by_id($app_city->value);
            $odf->setVars('kota', strtoupper($wilayah->n_kabupaten));
        } else {
            $alamat = $pemohon->a_pemohon;
            $odf->setVars('kota', '...........');
        }


//        $odf->setVars('title', 'Berita Acara Pemeriksaan');

        $tgl_skr = $this->lib_date->get_date_now();
        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($tgl_skr));

        if ($permohonan->d_survey) {
            if ($permohonan->d_survey != '0000-00-00')
                $tgl_periksa = $this->lib_date->mysql_to_human($permohonan->d_survey);
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
            array('property' => 'No BAP',
                'content' => $bap->bap_id,
            ),
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
            array('property' => 'Pesan Komentar',
                'content' => $bap->c_pesan,
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

            //untuk klasifikasi dan prasarana
            $no = 1;

            if ($data->id == '12') {
                $list_koefisien = new trkoefesientarifretribusi();
                $list_koefisien->where_related($data)->get();
                if ($list_koefisien->id) {
                    $row = 1;
                    $row2 = 1;
                    foreach ($list_koefisien as $row_koef) {
                        $no++;
                        $klasifikasi_id = '';
                        $entry_klasifikasi = '';
                        $koef_klasifikasi = 0;
                        $entry_klasifikasi2 = '';
                        $koef_klasifikasi2 = 0;
                        if ($list_klasifikasi->id) {

                            foreach ($list_klasifikasi as $data_klasifikasi) {
                                $entry_koefisien = new tmproperty_klasifikasi_trkoefesientarifretribusi();
                                $entry_koefisien->where('tmproperty_klasifikasi_id', $data_klasifikasi->id)
                                        ->where('trkoefesientarifretribusi_id', $row_koef->id)->get();
                                if ($entry_koefisien->tmproperty_klasifikasi_id) {
                                    $entry_daftar_klasifikasi = new tmproperty_klasifikasi();
                                    $entry_daftar_klasifikasi->get_by_id($entry_koefisien->tmproperty_klasifikasi_id);

                                    $klasifikasi_id = $entry_daftar_klasifikasi->id;
                                    $entry_klasifikasi = $entry_daftar_klasifikasi->v_tinjauan;
                                    $koef_klasifikasi = $entry_daftar_klasifikasi->k_tinjauan;
                                    $entry_klasifikasi2 = $entry_daftar_klasifikasi->v_klasifikasi;
                                    $koef_klasifikasi2 = $entry_daftar_klasifikasi->k_klasifikasi;
                                    //parameter
                                    $data_retribusi = new trkoefisienretribusilev1();
                                    $data_retribusi->where('id', $koef_klasifikasi)->get();

                                    if ($entry_daftar_klasifikasi->id) {

                                        if ($row == '1') {
                                            $nilai_prop = $data->n_property;
                                            $row2 = 0;
                                        } else {
                                            $nilai_prop = " ";
                                        }
                                        if ($row2 === "0")
                                            $nilai_koef = " ";
                                        else
                                            $nilai_koef = $row_koef->kategori;

                                        $row++;
                                        $row2++;

                                        $prop = $nilai_prop . $nilai_koef;

                                        if ($entry_klasifikasi)
                                            $entry_klasifikasi = ": [" . $entry_klasifikasi . "]";
                                        if ($entry_daftar->v_tinjauan) {
                                            if ($entry_daftar->v_tinjauan == "0")
                                                $hasil = " ";
                                            else
                                                $hasil = $data_retribusi->kategori . $entry_klasifikasi;
                                        }else
                                            $hasil = $data_retribusi->kategori . $entry_klasifikasi;

                                        $listeArticles3 = array(
                                            array('property3' => $prop,
                                                'content33' => $hasil,
                                            ),
                                        );

                                        $article3 = $odf->setSegment('articles3');
                                        foreach ($listeArticles3 AS $element3) {
                                            $article3->titreArticle3($element3['property3']);

                                            $article3->texteArticle4($element3['content33']);
                                            $article3->merge();
                                        }
                                    }
                                }
                            }
                        } else {
                            $klasifikasi_id = '';
                            $entry_klasifikasi = '';
                            $koef_klasifikasi = 0;
                            $entry_klasifikasi2 = '';
                            $koef_klasifikasi2 = 0;
                        }
                    }
                }
            } else if ($data->id == '29') {

                $list_koefisien = new trkoefesientarifretribusi();
                $list_koefisien->where_related($data)->get();
                if ($list_koefisien->id) {
                    $rows = 1;
                    foreach ($list_koefisien as $row_koef) {
                        $no++;
                        $prasarana_id = '';
                        $entry_prasarana = '';
                        $koef_prasarana = 0;
                        $entry_prasarana2 = '';
                        $koef_prasarana2 = 0;
                        if ($list_prasarana->id) {
                            foreach ($list_prasarana as $data_prasarana) {
                                $entry_koefisien = new tmproperty_prasarana_trkoefesientarifretribusi();
                                $entry_koefisien->where('tmproperty_prasarana_id', $data_prasarana->id)
                                        ->where('trkoefesientarifretribusi_id', $row_koef->id)->get();
                                if ($entry_koefisien->tmproperty_prasarana_id) {
                                    $entry_daftar_prasarana = new tmproperty_prasarana();
                                    $entry_daftar_prasarana->get_by_id($entry_koefisien->tmproperty_prasarana_id);

                                    $prasarana_id = $entry_daftar_prasarana->id;
                                    $entry_prasarana = $entry_daftar_prasarana->v_tinjauan;
                                    $koef_prasarana = $entry_daftar_prasarana->k_tinjauan;
                                    $entry_prasarana2 = $entry_daftar_prasarana->v_prasarana;
                                    $koef_prasarana2 = $entry_daftar_prasarana->k_prasarana;

                                    $data_retribusi = new trkoefisienretribusilev1();
                                    $data_retribusi->where('id', $koef_prasarana2)->get();

                                    if ($entry_daftar_prasarana->id) {

                                        if ($rows == '1')
                                            $nilai_prop2 = $data->n_property;
                                        else
                                            $nilai_prop2 = " ";
                                        $rows++;
                                        $prop = $nilai_prop2 . $row_koef->kategori;
                                        if ($entry_prasarana === '0')
                                            $entry_prasarana = "";
                                        if ($entry_prasarana)
                                            $entry_prasarana = ": (" . $entry_prasarana . " " . $row_koef->satuan . ")";
                                        if ($entry_daftar->v_tinjauan) {
                                            if ($entry_daftar->v_tinjauan == "0")
                                                $hasil = " ";
                                            else
                                                $hasil = $data_retribusi->kategori . $entry_prasarana;
                                        }else
                                            $hasil = $data_retribusi->kategori . $entry_prasarana;

                                        $listeArticles3 = array(
                                            array('property3' => $prop,
                                                'content33' => $hasil,
                                            ),
                                        );

                                        $article3 = $odf->setSegment('articles3');
                                        foreach ($listeArticles3 AS $element3) {
                                            $article3->titreArticle3($element3['property3']);

                                            $article3->texteArticle4($element3['content33']);
                                            $article3->merge();
                                        }
                                    }
                                }
                            }
                        } else {
                            $prasarana_id = '';
                            $entry_prasarana = '';
                            $koef_prasarana = 0;
                            $entry_prasarana2 = '';
                            $koef_prasarana2 = 0;
                        }
                    }
                }
            } else {
                $prop = $data->n_property;
                $property_satuan = new trperizinan_trproperty();
                $property_satuan->where('trproperty_id', $data->id)->get();

                //cek nilai kosong
                if ($entry_daftar->v_tinjauan) {
                    if ($entry_daftar->v_tinjauan == "0")
                        $hasil = $data_koefisien2->kategori;
                    else
                        $hasil = $entry_daftar->v_tinjauan . " " . $property_satuan->satuan;
                }else
                    $hasil = $data_koefisien2->kategori;

                $listeArticles3 = array(
                    array('property3' => $prop,
                        'content33' => $hasil,
                    ),
                );

                $article3 = $odf->setSegment('articles3');
                foreach ($listeArticles3 AS $element3) {
                    $article3->titreArticle3($element3['property3']);

                    $article3->texteArticle4($element3['content33']);
                    $article3->merge();
                }
            }
        }
        $odf->mergeSegment($article3);

        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat . '_' . $no_daftar . '.odt');
    }

    public function sql($u_ser) {
        $query = "select a.description
	from user_auth as a
	inner join user_user_auth as  x on a.id = x.user_auth_id
	inner join user as b on b.id = x.user_id
        where b.id = (select id from user where username='" . $u_ser . "')";
        $hasil = $this->db->query($query);
        return $hasil->row();
    }

    public function getTinjauan($daftar, $jnsProp) {
        $query = "select a.id,a.v_property,a.v_tinjauan from tmproperty_jenisperizinan as a
        inner join tmpermohonan_tmproperty_jenisperizinan as b on b.tmproperty_jenisperizinan_id=a.id
        inner join tmpermohonan as c on c.id=b.tmpermohonan_id
        inner join tmproperty_jenisperizinan_trproperty as d on d.tmproperty_jenisperizinan_id=a.id
        inner join trproperty as e on e.id=d.trproperty_id
        where c.id='" . $daftar . "' and e.id='" . $jnsProp . "'";
        $hasil = $this->db->query($query);
        return $hasil->row();
    }
    
    

}

// This is the end of role class
