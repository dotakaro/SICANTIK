<?php

/**
 * Description of Pencabutan Izin
 *
 * @author agusnur
 * Created : 30 Sep 2010
 */
class CabutIzin extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->pendaftaran = new tmpermohonan();
        $this->perizinan = new trperizinan();
        $this->kelompok_izin = new trkelompok_perizinan();
        $this->jenispermohonan = new trjenis_permohonan();
        $this->propinsi = new trpropinsi();
        $this->kabupaten = new trkabupaten();
        $this->kecamatan = new trkecamatan();
        $this->kelurahan = new trkelurahan();
        $this->kegiatan = new trkegiatan();
        $this->investasi = new trinvestasi();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '13') {
                $enabled = TRUE;
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
        $this->cabut_id = "1"; // Pencabutan Izin
    }

    /*
     * Function
     */
    function _funcwilayah(){
        $data['list_propinsi'] = $this->propinsi->order_by('n_propinsi','ASC')->get();
        $data['list_kabupaten'] = $this->kabupaten->order_by('n_kabupaten','ASC')->get();
        $data['list_kecamatan'] = $this->kecamatan->order_by('n_kecamatan','ASC')->get();
        $data['list_kelurahan'] = $this->kelurahan->order_by('n_kelurahan','ASC')->get();

        //Data Pendukung Perusahaan
        $data['list_kegiatan'] = $this->kegiatan->order_by('n_kegiatan','ASC')->get();
        $data['list_investasi'] = $this->investasi->order_by('n_investasi','ASC')->get();

        return $data;
    }

    public function daftar_izin_proses() {
        //$data['jenis_id'] = $this->jenis_id;

        //$this->load->vars($data);
        $this->load->view('daftar_izin_proses_load');
    }

    public function daftar_izin_proses2($jenis_id = NULL) {
        $data['jenis_id'] = $jenis_id;

        $this->load->vars($data);
        $this->load->view('daftar_izin_proses_load2', $data);
    }

    public function index() {        
        $data['list'] = $this->pendaftaran
                ->where('c_izin_dicabut', $this->cabut_id) //Permohonan telah dicabut
                ->order_by('id', 'DESC')->get();
        $data['list_izin'] = $this->perizinan->order_by('id','ASC')->get();
        $data['jenis_izin_id'] = $this->perizinan->id;
        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                    $('#no_daftar').multiselect({
                       show:'blind',
                       hide:'blind',
                       multiple: false,
                       header: 'Pilih salah satu',
                       noneSelectedText: 'Pilih salah satu',
                       selectedList: 1
                    }).multiselectfilter();

                    oTable = $('#pendaftaran').dataTable({
                            \"bJQueryUI\": true,
                            \"sPaginationType\": \"full_numbers\"
                    });
                } );

                $(document).ready(function() {
                    $('#izin_id').change(function(){
                            $('#show_daftar_izin').fadeOut();
                            $.post('". base_url() ."pendaftaran/cabutizin/daftar_izin_proses', {
                                jenis_izin_id: $('#izin_id').val()
                            }, function(response){
                                setTimeout(\"finishAjax('show_daftar_izin', '\"+escape(response)+\"')\", 400);
                            });
                            return false;
                    });
                });
                
                $(document).ready(function() {
                    $('#year_id').change(function(){
                            $('#show_list_izin').fadeOut();
                            $.post('". base_url() ."pendaftaran/cabutizin/daftar_izin_proses2/".$this->perizinan->id."', {
                                tahun_id: $('#year_id').val()
                            }, function(response){
                                setTimeout(\"finishAjax('show_list_izin', '\"+escape(response)+\"')\", 400);
                            });
                            return false;
                    });
                });

                function finishAjax(id, response){
                  $('#'+id).html(unescape(response));
                  $('#'+id).fadeIn();
                }
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Data Pencabutan Izin";
        $this->template->build('cabutizin_list', $this->session_info);
    }

    /*
     * edit is a method to show page for updating data
     */
    public function edit($no_daftar = NULL, $id_link = NULL) {
        if($this->input->post('no_daftar')) $no_daftar = $this->input->post('no_daftar');
        if($this->input->post('id_link')) $id_link = $this->input->post('id_link');
        $no_surat = $this->input->post('no_surat');
    if($no_surat){
        $tmsk = new tmsk();
        $tmsk->where('no_surat', $no_surat)->get();
        if($tmsk->id){
        $u_daftar = $tmsk->tmpermohonan->get();
            if($u_daftar->c_izin_selesai == "1"){
//        $u_daftar = $this->pendaftaran->get_by_id($no_daftar);
        $p_pemohon = $u_daftar->tmpemohon->get();
        $p_kelurahan = $p_pemohon->trkelurahan->get();
        $p_kecamatan = $p_pemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $p_pemohon->trkelurahan->trkecamatan->trkabupaten->get();
        $p_propinsi = $p_pemohon->trkelurahan->trkecamatan->trkabupaten->trpropinsi->get();
        $u_perusahaan = $u_daftar->tmperusahaan->get();
        $u_kelurahan = $u_perusahaan->trkelurahan->get();
        $u_kecamatan = $u_perusahaan->trkelurahan->trkecamatan->get();
        $u_kabupaten = $u_perusahaan->trkelurahan->trkecamatan->trkabupaten->get();
        $u_propinsi = $u_perusahaan->trkelurahan->trkecamatan->trkabupaten->trpropinsi->get();
        $u_kegiatan = $u_daftar->tmperusahaan->trkegiatan->get();
        $u_investasi = $u_daftar->tmperusahaan->trinvestasi->get();
        $d_izin = $u_daftar->trperizinan->get();
        $d_sk = $u_daftar->tmsk->get();


        $data = $this->_funcwilayah();
        $data['save_method'] = "update";
        $data['list_daftar'] = $u_daftar;
        $data['id_daftar'] = $u_daftar->id;
        $data['id_link'] = $id_link;
        $data['tgl_dicabut'] = $u_daftar->d_izin_dicabut;
        $data['no_akta'] = $u_daftar->no_akta;
        $data['d_akta'] = $u_daftar->d_akta;
        $data['notaris'] = $u_daftar->notaris;
        $data['d_ajuan_cabut'] = $u_daftar->d_ajuan_cabut;
        $data['ket_cabut'] = $u_daftar->ket_cabut;
        $paralel_jenis = new trparalel();
        $data['jenis_paralel'] = $paralel_jenis->get_by_id($u_daftar->c_paralel);
        $data['no_refer'] = $p_pemohon->no_referensi;
        $data['nama_pemohon'] = $p_pemohon->n_pemohon;
        $data['no_telp'] = $p_pemohon->telp_pemohon;
        $data['propinsi_pemohon'] = $p_propinsi->id;
        $data['kabupaten_pemohon'] = $p_kabupaten->id;
        $data['kecamatan_pemohon'] = $p_kecamatan->id;
        $data['kelurahan_pemohon'] = $p_kelurahan->id;
        $data['tgl_daftar'] = $u_daftar->d_terima_berkas;
        $data['tgl_survey'] = $u_daftar->d_survey;
        $data['lokasi_izin'] = $u_daftar->a_izin;
        $data['tgl_survey'] = $u_daftar->d_survey;
        $data['lokasi_izin'] = $u_daftar->a_izin;
        $data['alamat_pemohon']  = $p_pemohon->a_pemohon;
        $data['alamat_pemohon_luar']  = $p_pemohon->a_pemohon_luar;
        $data['nama_perusahaan'] = $u_perusahaan->n_perusahaan;
        $data['npwp'] = $u_perusahaan->npwp;
        $data['telp_perusahaan'] = $u_perusahaan->i_telp_perusahaan;
        $data['alamat_usaha']  = $u_perusahaan->a_perusahaan;
        $data['propinsi_usaha'] = $u_propinsi->id;
        $data['kabupaten_usaha'] = $u_kabupaten->id;
        $data['kecamatan_usaha'] = $u_kecamatan->id;
        $data['kelurahan_usaha'] = $u_kelurahan->id;
        $data['jenis_kegiatan'] = $u_kegiatan->id;
        $data['jenis_investasi'] = $u_investasi->id;
        $data['jenis_izin'] = $this->perizinan->get_by_id($d_izin->id);
        $data['jenis_permohonan'] = $u_daftar->trjenis_permohonan->get();

        $syarat_perizinan = new trsyarat_perizinan();
        $data['syarat_izin'] = $syarat_perizinan->where_related($this->perizinan)->order_by('status', 'ASC')->get();
        $data['no_pendaftaran_baru'] = $u_daftar->pendaftaran_id;
        $data['no_sk'] = $d_sk->no_surat;
        $daftar_lama = new tmpermohonan();
        if($u_daftar->id_lama){
            $daftar_lama->get_by_id($u_daftar->id_lama);
            $data['no_pendaftaran'] = $daftar_lama->pendaftaran_id;
        }else{
            $data['no_pendaftaran'] = '-';
        }

            }else{
                $data['id_daftar'] = "yyy";
                $data['id_link'] = "";
            }
        }else{
            $data['id_daftar'] = "xxx";
            $data['id_link'] = "";
        }

    }else{
        $data['id_daftar'] = "";
        $data['id_link'] = "";
    }
        $js =  "
                $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();
                } );

                $(function() {
                    $(\"#inputTanggal1\").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'yy-mm-dd',
                        closeText: 'X'
                    });
                    $(\"#inputTanggal2\").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'yy-mm-dd',
                        closeText: 'X'
                    });
                    $(\"#inputTanggal3\").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'yy-mm-dd',
                        closeText: 'X'
                    });
                });

                $(document).ready(function() {
                        $('#propinsi_pemohon_id').change(function(){
                                $('#show_kabupaten_pemohon').fadeOut();
                                $.post('". base_url() ."pendaftaran/pendaftaran/kabupaten_pemohon', {
                                    propinsi_id: $('#propinsi_pemohon_id').val()
                                }, function(response){
                                    setTimeout(\"finishAjax('show_kabupaten_pemohon', '\"+escape(response)+\"')\", 400);
                                });
                                return false;
                        });
                        $('#kabupaten_pemohon_id').change(function(){
                                $('#show_kecamatan_pemohon').fadeOut();
                                $.post('". base_url() ."pendaftaran/pendaftaran/kecamatan_pemohon', {
                                    kabupaten_id: $('#kabupaten_pemohon_id').val()
                                }, function(response){
                                    setTimeout(\"finishAjax('show_kecamatan_pemohon', '\"+escape(response)+\"')\", 400);
                                });
                                return false;
                        });
                        $('#kecamatan_pemohon_id').change(function(){
                                $('#show_kelurahan_pemohon').fadeOut();
                                $.post('". base_url() ."pendaftaran/pendaftaran/kelurahan_pemohon', {
                                    kecamatan_id: $('#kecamatan_pemohon_id').val()
                                }, function(response){
                                    setTimeout(\"finishAjax('show_kelurahan_pemohon', '\"+escape(response)+\"')\", 400);
                                });
                                return false;
                        });
                        $('#propinsi_usaha_id').change(function(){
                                $('#show_kabupaten_usaha').fadeOut();
                                $.post('". base_url() ."pendaftaran/pendaftaran/kabupaten_usaha', {
                                    propinsi_id: $('#propinsi_usaha_id').val()
                                }, function(response){
                                    setTimeout(\"finishAjax('show_kabupaten_usaha', '\"+escape(response)+\"')\", 400);
                                });
                                return false;
                        });
                        $('#kabupaten_usaha_id').change(function(){
                                $('#show_kecamatan_usaha').fadeOut();
                                $.post('". base_url() ."pendaftaran/pendaftaran/kecamatan_usaha', {
                                    kabupaten_id: $('#kabupaten_usaha_id').val()
                                }, function(response){
                                    setTimeout(\"finishAjax('show_kecamatan_usaha', '\"+escape(response)+\"')\", 400);
                                });
                                return false;
                        });
                        $('#kecamatan_usaha_id').change(function(){
                                $('#show_kelurahan_usaha').fadeOut();
                                $.post('". base_url() ."pendaftaran/pendaftaran/kelurahan_usaha', {
                                    kecamatan_id: $('#kecamatan_usaha_id').val()
                                }, function(response){
                                    setTimeout(\"finishAjax('show_kelurahan_usaha', '\"+escape(response)+\"')\", 400);
                                });
                                return false;
                        });
                });

                function finishAjax(id, response){
                  $('#'+id).html(unescape(response));
                  $('#'+id).fadeIn();
                }
            ";

        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Pencabutan Izin";
        $this->template->build('cabutizin_edit', $this->session_info);

    }
    
    
    public function edit2($no_daftar = NULL, $id_link = NULL) {
        if($this->input->post('no_daftar')) $no_daftar = $this->input->post('no_daftar');
        if($this->input->post('id_link')) $id_link = $this->input->post('id_link');
        $permohonan = $this->pendaftaran->where('id',$no_daftar)->get();
        $no_surat = $permohonan->tmsk->no_surat;
    if($no_surat){
        $tmsk = new tmsk();
        $tmsk->where('no_surat', $no_surat)->get();
        if($tmsk->id){
        $u_daftar = $tmsk->tmpermohonan->get();
            if($u_daftar->c_izin_selesai == "1"){
//        $u_daftar = $this->pendaftaran->get_by_id($no_daftar);
        $p_pemohon = $u_daftar->tmpemohon->get();
        $p_kelurahan = $p_pemohon->trkelurahan->get();
        $p_kecamatan = $p_pemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $p_pemohon->trkelurahan->trkecamatan->trkabupaten->get();
        $p_propinsi = $p_pemohon->trkelurahan->trkecamatan->trkabupaten->trpropinsi->get();
        $u_perusahaan = $u_daftar->tmperusahaan->get();
        $u_kelurahan = $u_perusahaan->trkelurahan->get();
        $u_kecamatan = $u_perusahaan->trkelurahan->trkecamatan->get();
        $u_kabupaten = $u_perusahaan->trkelurahan->trkecamatan->trkabupaten->get();
        $u_propinsi = $u_perusahaan->trkelurahan->trkecamatan->trkabupaten->trpropinsi->get();
        $u_kegiatan = $u_daftar->tmperusahaan->trkegiatan->get();
        $u_investasi = $u_daftar->tmperusahaan->trinvestasi->get();
        $d_izin = $u_daftar->trperizinan->get();
        $d_sk = $u_daftar->tmsk->get();


        $data = $this->_funcwilayah();
        $data['link'] = "edit2";
        $data['save_method'] = "update";
        $data['list_daftar'] = $u_daftar;
        $data['id_daftar'] = $u_daftar->id;
        $data['id_link'] = $id_link;
        $data['tgl_dicabut'] = $u_daftar->d_izin_dicabut;
        $data['no_akta'] = $u_daftar->no_akta;
        $data['d_akta'] = $u_daftar->d_akta;
        $data['notaris'] = $u_daftar->notaris;
        $data['d_ajuan_cabut'] = $u_daftar->d_ajuan_cabut;
        $data['ket_cabut'] = $u_daftar->ket_cabut;
        $paralel_jenis = new trparalel();
        $data['jenis_paralel'] = $paralel_jenis->get_by_id($u_daftar->c_paralel);
        $data['no_refer'] = $p_pemohon->no_referensi;
        $data['nama_pemohon'] = $p_pemohon->n_pemohon;
        $data['no_telp'] = $p_pemohon->telp_pemohon;
        $data['propinsi_pemohon'] = $p_propinsi->id;
        $data['kabupaten_pemohon'] = $p_kabupaten->id;
        $data['kecamatan_pemohon'] = $p_kecamatan->id;
        $data['kelurahan_pemohon'] = $p_kelurahan->id;
        $data['tgl_daftar'] = $u_daftar->d_terima_berkas;
        $data['tgl_survey'] = $u_daftar->d_survey;
        $data['lokasi_izin'] = $u_daftar->a_izin;
        $data['tgl_survey'] = $u_daftar->d_survey;
        $data['lokasi_izin'] = $u_daftar->a_izin;
        $data['alamat_pemohon']  = $p_pemohon->a_pemohon;
        $data['alamat_pemohon_luar']  = $p_pemohon->a_pemohon_luar;
        $data['nama_perusahaan'] = $u_perusahaan->n_perusahaan;
        $data['npwp'] = $u_perusahaan->npwp;
        $data['telp_perusahaan'] = $u_perusahaan->i_telp_perusahaan;
        $data['alamat_usaha']  = $u_perusahaan->a_perusahaan;
        $data['propinsi_usaha'] = $u_propinsi->id;
        $data['kabupaten_usaha'] = $u_kabupaten->id;
        $data['kecamatan_usaha'] = $u_kecamatan->id;
        $data['kelurahan_usaha'] = $u_kelurahan->id;
        $data['jenis_kegiatan'] = $u_kegiatan->id;
        $data['jenis_investasi'] = $u_investasi->id;
        $data['jenis_izin'] = $this->perizinan->get_by_id($d_izin->id);
        $data['jenis_permohonan'] = $u_daftar->trjenis_permohonan->get();

        $syarat_perizinan = new trsyarat_perizinan();
        $data['syarat_izin'] = $syarat_perizinan->where_related($this->perizinan)->order_by('status', 'ASC')->get();
        $data['no_pendaftaran_baru'] = $u_daftar->pendaftaran_id;
        $data['no_sk'] = $d_sk->no_surat;
        $daftar_lama = new tmpermohonan();
        if($u_daftar->id_lama){
            $daftar_lama->get_by_id($u_daftar->id_lama);
            $data['no_pendaftaran'] = $daftar_lama->pendaftaran_id;
        }else{
            $data['no_pendaftaran'] = '-';
        }

            }else{
                $data['id_daftar'] = "yyy";
                $data['id_link'] = "";
            }
        }else{
            $data['id_daftar'] = "xxx";
            $data['id_link'] = "";
        }

    }else{
        $data['id_daftar'] = "";
        $data['id_link'] = "";
    }
        $js =  "
                $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();
                } );

                $(function() {
                    $(\"#inputTanggal1\").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'yy-mm-dd',
                        closeText: 'X'
                    });
                    $(\"#inputTanggal2\").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'yy-mm-dd',
                        closeText: 'X'
                    });
                    $(\"#inputTanggal3\").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'yy-mm-dd',
                        closeText: 'X'
                    });
                });

                $(document).ready(function() {
                        $('#propinsi_pemohon_id').change(function(){
                                $('#show_kabupaten_pemohon').fadeOut();
                                $.post('". base_url() ."pendaftaran/pendaftaran/kabupaten_pemohon', {
                                    propinsi_id: $('#propinsi_pemohon_id').val()
                                }, function(response){
                                    setTimeout(\"finishAjax('show_kabupaten_pemohon', '\"+escape(response)+\"')\", 400);
                                });
                                return false;
                        });
                        $('#kabupaten_pemohon_id').change(function(){
                                $('#show_kecamatan_pemohon').fadeOut();
                                $.post('". base_url() ."pendaftaran/pendaftaran/kecamatan_pemohon', {
                                    kabupaten_id: $('#kabupaten_pemohon_id').val()
                                }, function(response){
                                    setTimeout(\"finishAjax('show_kecamatan_pemohon', '\"+escape(response)+\"')\", 400);
                                });
                                return false;
                        });
                        $('#kecamatan_pemohon_id').change(function(){
                                $('#show_kelurahan_pemohon').fadeOut();
                                $.post('". base_url() ."pendaftaran/pendaftaran/kelurahan_pemohon', {
                                    kecamatan_id: $('#kecamatan_pemohon_id').val()
                                }, function(response){
                                    setTimeout(\"finishAjax('show_kelurahan_pemohon', '\"+escape(response)+\"')\", 400);
                                });
                                return false;
                        });
                        $('#propinsi_usaha_id').change(function(){
                                $('#show_kabupaten_usaha').fadeOut();
                                $.post('". base_url() ."pendaftaran/pendaftaran/kabupaten_usaha', {
                                    propinsi_id: $('#propinsi_usaha_id').val()
                                }, function(response){
                                    setTimeout(\"finishAjax('show_kabupaten_usaha', '\"+escape(response)+\"')\", 400);
                                });
                                return false;
                        });
                        $('#kabupaten_usaha_id').change(function(){
                                $('#show_kecamatan_usaha').fadeOut();
                                $.post('". base_url() ."pendaftaran/pendaftaran/kecamatan_usaha', {
                                    kabupaten_id: $('#kabupaten_usaha_id').val()
                                }, function(response){
                                    setTimeout(\"finishAjax('show_kecamatan_usaha', '\"+escape(response)+\"')\", 400);
                                });
                                return false;
                        });
                        $('#kecamatan_usaha_id').change(function(){
                                $('#show_kelurahan_usaha').fadeOut();
                                $.post('". base_url() ."pendaftaran/pendaftaran/kelurahan_usaha', {
                                    kecamatan_id: $('#kecamatan_usaha_id').val()
                                }, function(response){
                                    setTimeout(\"finishAjax('show_kelurahan_usaha', '\"+escape(response)+\"')\", 400);
                                });
                                return false;
                        });
                });

                function finishAjax(id, response){
                  $('#'+id).html(unescape(response));
                  $('#'+id).fadeIn();
                }
            ";

        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Pencabutan Izin";
       $this->template->build('cabutizin_edit', $this->session_info);

    }
    
    
    
    public function update() {
        $id_daftar = $this->input->post('id_daftar');
        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($id_daftar);
        $permohonan->c_izin_dicabut = $this->cabut_id;
        $permohonan->d_izin_dicabut = $this->input->post('tgl_dicabut');
        $permohonan->no_akta = $this->input->post('no_akta');
        $permohonan->d_akta = $this->input->post('d_akta');
        $permohonan->notaris = $this->input->post('notaris');
        $permohonan->d_ajuan_cabut = $this->input->post('d_ajuan_cabut');
        $permohonan->ket_cabut = $this->input->post('ket_cabut');

        $status_izin = $permohonan->trstspermohonan->get();

        $status_skr = "15"; //Arsip [Lihat Tabel trstspermohonan()]
        $id_status = "16"; //Izin Dicabut [Lihat Tabel trstspermohonan()]
        if($status_izin->id == $status_skr){
        /* Input Data Tracking Progress */
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

        /* [Lihat Tabel trstspermohonan()] */
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
        }
        
        $update = $permohonan->save();
        if($update) {
            redirect('pendaftaran/cabutizin');
        }
    }

    public function cetak_bukti($id_daftar = NULL) {
        $nama_surat = "cetak_cabut_izin";
        $this->settings = new settings();
        $this->settings->where('name','app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name','app_city')->get();

        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($id_daftar);
        $pemohon = $permohonan->tmpemohon->get();
        $perizinan = $permohonan->trperizinan->get();
        $p_kelurahan = $pemohon->trkelurahan->get();
        $p_kecamatan = $pemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $pemohon->trkelurahan->trkecamatan->trkabupaten->get();
        $usaha = $permohonan->tmperusahaan->get();

        //Tampilkan Jenis Izin
        $paralel_jenis = new trparalel();
        $paralel_id = $permohonan->c_paralel;
        $paralel_jenis->get_by_id($paralel_id);
        $perizinan = new trperizinan();
        if($paralel_id == 0) $jenis_izin = $perizinan->where_related($permohonan)->get();
        else $jenis_izin = $perizinan->where_related($paralel_jenis)->get();
        $list_izin = NULL;
        $x = 1;
        foreach($jenis_izin as $row){
            if($x == 1) $data_izin = $row->n_perizinan;
            else $data_izin = $data_izin.", ".$row->n_perizinan;
            $x++;
        }

        //Tampilkan Tgl Akta
        if($permohonan->d_akta){
            if($permohonan->d_akta != '0000-00-00') $tgl_akta = $this->lib_date->mysql_to_human($permohonan->d_akta);
            else $tgl_akta = "";
        }else $tgl_akta = "";

        //Tampilkan Tgl Ajuan
        if($permohonan->d_ajuan_cabut){
            if($permohonan->d_ajuan_cabut != '0000-00-00') $tgl_ajuan = $this->lib_date->mysql_to_human($permohonan->d_ajuan_cabut);
            else $tgl_ajuan = "";
        }else $tgl_ajuan = "";

        //Tampilkan Tgl Cabut
        if($permohonan->d_izin_dicabut){
            if($permohonan->d_izin_dicabut != '0000-00-00') $tgl_cabut = $this->lib_date->mysql_to_human($permohonan->d_izin_dicabut);
            else $tgl_cabut = "";
        }else $tgl_cabut = "";

        //path of the template file
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/'.$nama_surat.'.odt');
//        $odf->setImage('header', 'assets/css/' . $app_folder . '/images/dinas_1.jpg', '17.5', '3.5');

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
        $username = new user();
        $username->where('username', $this->session->userdata('username'))->get();
        if($username->id) $user = $username->realname;
        else $user = "................................";
//        $odf->setVars('user', $user);
        $wilayah = new trkabupaten();
        if($app_city->value !== '0'){
            $alamat = $pemohon->a_pemohon.' '.$p_kelurahan->n_kelurahan.', '.
                      $p_kecamatan->n_kecamatan.', '.ucwords(strtolower($p_kabupaten->n_kabupaten));
            $wilayah->get_by_id($app_city->value);
//            $odf->setVars('kabupaten', ucwords(strtolower($wilayah->n_kabupaten)));
            $odf->setVars('kota', ucwords(strtolower($wilayah->ibukota)));
        }else{
            $alamat = $pemohon->a_pemohon;
            $odf->setVars('kabupaten', 'setempat');
            $odf->setVars('kota', '...........');
        }
        
        $gede_kota=strtoupper($wilayah->n_kabupaten);
        $kecil_kota=ucwords(strtolower($wilayah->n_kabupaten));
        $odf->setVars('kota4', $gede_kota);

        //alamat
        $this->tr_instansi = new Tr_instansi();
        $alamat = $this->tr_instansi->get_by_id(12);
        $odf->setVars('alamat', ucwords(strtolower($alamat->value)).' - '.$kecil_kota);

        
        $surat = $permohonan->tmsk->get();
        $odf->setVars('tgl_akta', $tgl_akta );
        $odf->setVars('tgl_ajuan', $tgl_ajuan);
        $odf->setVars('tgl_cabut', $tgl_cabut);
        $odf->setVars('keterangan', $permohonan->ket_cabut);
        $odf->setVars('notaris', $permohonan->notaris);
//        $odf->setVars('tglskrng', $this->lib_date->mysql_to_human (date('d/m/Y')));
        $odf->setVars('izin', $perizinan->n_perizinan);
        $odf->setVars('nomor', "C/".$surat->no_surat);
        $odf->setVars('no_izin', $surat->no_surat);
        $odf->setVars('nama', $pemohon->n_pemohon);
        $odf->setVars('alamatPerusahaan', $usaha->a_perusahaan);
        $odf->setVars('perusahaan', $usaha->n_perusahaan);
        $petugas = 1; //1 -> Jabatan Penandatangan
        $pegawai = new tmpegawai();
        $pegawai->where('status', $petugas)->get();
        $odf->setVars('jabatan', strtoupper($pegawai->n_jabatan));
        $odf->setVars('nama_pejabat', strtoupper($pegawai->n_pegawai));
        $odf->setVars('nip_pejabat', $pegawai->nip);

        // dasar Hukum
        $i = 1;
        $izin_hukum = new trdasar_hukum_trperizinan();
        $list_hukum = $izin_hukum->where('type', 1)
                        ->where('trperizinan_id', $perizinan->id)->get(); //17 2
        if ($list_hukum->id) {
            foreach ($list_hukum as $hukum) {
                $dasar_hukum = new trdasar_hukum();
                if ($hukum->id) {
                    $data6 = $dasar_hukum->where('id', $hukum->trdasar_hukum_id)->get();
                    $desk = $data6->deskripsi;
                } else {
                    $desk = ' ';
//                      $i=' ';
                }


                $listeArticles2 = array(
                    array('property' => $i . '.',
                        'content' => $desk,
                    ),
                );

                $article2 = $odf->setSegment('articles2');
                foreach ($listeArticles2 AS $element2) {
                    $article2->titreArticle2($element2['property']);
                    $article2->texteArticle2($element2['content']);
                    $article2->merge();
                }
                $i++;
            }
        } else {
            $desk = '';
            $listeArticles2 = array(
                array('property' => '',
                    'content' => $desk,
                ),
            );

            $article2 = $odf->setSegment('articles2');
            foreach ($listeArticles2 AS $element2) {
                $article2->titreArticle2($element2['property']);
                $article2->texteArticle2($element2['content']);
                $article2->merge();
            }
        }
        $odf->mergeSegment($article2);

        //export the file
        $no_daftar = str_replace('/', '', $permohonan->perubahan_id);
        $odf->exportAsAttachedFile($nama_surat.'_'.$no_daftar.'.odt');
    }

}
