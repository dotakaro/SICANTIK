<?php

/**
 * Description of Pendaftaran Daftar Ulang
 *
 * @author agusnur
 * Created : 25 Sep 2010
 */
class DaftarUlang extends WRC_AdminCont {

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
            if($list_auth->id_role === '3') {
                $enabled = TRUE;
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
        $this->jenis_id = "4"; // Daftar Ulang
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

    public function daftar_izin() {
        $data['jenis_id'] = $this->jenis_id;

        $this->load->vars($data);
        $this->load->view('daftar_izin_load', $data);
    }

    public function index() {
        $jenis_p = $this->jenispermohonan->get_by_id($this->jenis_id);
        $data['list'] = $this->pendaftaran->where_related($jenis_p)->order_by('id', 'DESC')->get();
        $data['list_izin'] = $this->perizinan->order_by('id','ASC')->get();
        $data['jenis_izin_id'] = $this->perizinan->id;
        $data['jenis_daftar_id'] = $this->jenis_id;
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
                            $.post('". base_url() ."pendaftaran/daftarulang/daftar_izin', {
                                jenis_izin_id: $('#izin_id').val()
                            }, function(response){
                                setTimeout(\"finishAjax('show_daftar_izin', '\"+escape(response)+\"')\", 400);
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
        $this->session_info['page_name'] = "Data Permohonan Daftar Ulang Izin";
        $this->template->build('daftarulang_list', $this->session_info);
    }

    /*
     * edit is a method to show page for updating data
     */
    public function create() {
        $u_daftar = $this->pendaftaran->get_by_id($this->input->post('no_daftar'));
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
        $data['save_method'] = "save";
        $data['id_daftar'] = $u_daftar->id;
        $data['id_link'] = '';
        $data['tgl_daftarulang'] = '';
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
        $data['jenis_permohonan'] = $this->jenispermohonan->get_by_id($this->jenis_id);

        $syarat_perizinan = new trsyarat_perizinan();
        $data['syarat_izin'] = $syarat_perizinan->where_related($this->perizinan)->get();
        $data['no_pendaftaran'] = $u_daftar->pendaftaran_id;
        $data['no_sk'] = $d_sk->no_surat;
        $data['no_pendaftaran_baru'] = '';

        $js =  "
                $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();
                } );

                $(function() {
                    $(\"#inputTanggal_\").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'yy-mm-dd',
                        closeText: 'X'
                    });
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
        $this->session_info['page_name'] = "Entry Data Permohonan Daftar Ulang Izin";
        $this->template->build('daftarulang_edit', $this->session_info);

    }

    /*
     * edit is a method to show page for updating data
     */
    public function edit($no_daftar = NULL, $id_link = NULL) {
        $u_daftar = $this->pendaftaran->get_by_id($no_daftar);
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
        $data['tgl_daftarulang'] = $u_daftar->d_daftarulang;
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
        $data['jenis_permohonan'] = $this->jenispermohonan->get_by_id($this->jenis_id);

        $syarat_perizinan = new trsyarat_perizinan();
        $data['syarat_izin'] = $syarat_perizinan->where_related($this->perizinan)->get();
        $data['no_pendaftaran_baru'] = $u_daftar->pendaftaran_id;
        $data['no_sk'] = $d_sk->no_surat;
        $daftar_lama = new tmpermohonan();
        if($u_daftar->id_lama){
            $daftar_lama->get_by_id($u_daftar->id_lama);
            $data['no_pendaftaran'] = $daftar_lama->pendaftaran_id;
        }else{
            $data['no_pendaftaran'] = '-';
        }

        $js =  "
                $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();
                } );

                $(function() {
                    $(\"#inputTanggal_\").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'yy-mm-dd',
                        closeText: 'X'
                    });
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
        $this->session_info['page_name'] = "Edit Permohonan Daftar Ulang Izin";
        $this->template->build('daftarulang_edit', $this->session_info);

    }

    /*
     * Save and update for manipulating data.
     */
    public function save() {
        $perizinan = new trperizinan();
        $perizinan->get_by_id($this->input->post('jenis_izin_id'));

        $jenis_permohonan = new trjenis_permohonan();
        $jenis_permohonan->get_by_id($this->input->post('jenis_permohonan_id'));

        /* Penomoran Pendaftaran
         * Awal
         */
        $data_id = new tmpermohonan();

        $data_id->select_max('id')->get();
        $data_id->get_by_id($data_id->id);

        $data_tahun = date("Y");
        //Per Tahun Auto Restart NoUrut
        if($data_id->d_tahun === $data_tahun)
        $data_urut = $data_id->i_urut + 1;
        else $data_urut = 1;

        $i_urut = strlen($data_urut);
        for($i=5;$i>$i_urut;$i--){
            $data_urut = "0".$data_urut;
        }

        $data_izin = $perizinan->id;
        $i_izin = strlen($data_izin);
        for($i=2;$i>$i_izin;$i--){
            $data_izin = "0".$data_izin;
        }

        $data_jenis = $jenis_permohonan->id;
        $i_izin = strlen($data_jenis);
        for($i=2;$i>$i_izin;$i--){
            $data_jenis = "0".$data_jenis;
        }

        $data_bulan = date("n");
        $i_bulan = strlen($data_bulan);
        for($i=2;$i>$i_bulan;$i--){
            $data_bulan = "0".$data_bulan;
        }

        $data_lama = new tmpermohonan();
        $data_lama->get_by_id($this->input->post('id_daftar'));
        $permohonan = new tmpermohonan();
        $permohonan->i_urut = $data_urut;
        $permohonan->d_tahun = $data_tahun;
        $nomor_pendaftaran = $data_urut."/"
                .$data_izin."/".$data_jenis."/"
                .$data_bulan."/".$data_tahun;
        $permohonan->pendaftaran_id = $nomor_pendaftaran;
        $permohonan->d_terima_berkas = $this->input->post('tgl_daftar');
        $permohonan->d_survey = $this->input->post('tgl_survey');
        $permohonan->a_izin = $this->input->post('lokasi_izin');
        $permohonan->id_lama = $data_lama->id;
        $permohonan->d_daftarulang = $this->input->post('tgl_daftarulang');

        $tgl_skr = $this->lib_date->get_date_now();
        $permohonan->d_entry = $tgl_skr;
        $permohonan->d_selesai_proses = $this->lib_date->set_date($tgl_skr, $perizinan->v_hari);
        $permohonan->save($perizinan);
        /* Penomoran Pendaftaran
         * Akhir
         */

        $permohonan_akhir = new tmpermohonan();
        $permohonan_akhir->select_max('id')->get();

        /* Input Data Pemohon */
        $pemohon = new tmpemohon();
        $data_lama->tmpemohon->get();
        $pemohon->get_by_id($data_lama->tmpemohon->id);
        $pemohon->no_referensi = $this->input->post('no_refer');
        $pemohon->n_pemohon = $this->input->post('nama_pemohon');
        $pemohon->telp_pemohon = $this->input->post('no_telp');
        $pemohon->a_pemohon = $this->input->post('alamat_pemohon');
        $pemohon->a_pemohon_luar = $this->input->post('alamat_pemohon_luar');
        $kelurahan_p = new trkelurahan();
        $kelurahan_p->get_by_id($this->input->post('kelurahan_pemohon'));
        $pemohon->save(array($permohonan_akhir, $kelurahan_p));

        /* Input Data Perusahaan */
        $perusahaan = new tmperusahaan();
        $data_lama->tmperusahaan->get();
        $perusahaan->get_by_id($data_lama->tmperusahaan->id);
        $perusahaan->n_perusahaan = $this->input->post('nama_perusahaan');
        $perusahaan->npwp = $this->input->post('npwp');
        $perusahaan->i_telp_perusahaan = $this->input->post('telp_perusahaan');
        $perusahaan->a_perusahaan = $this->input->post('alamat_usaha');
        $kelurahan_u = new trkelurahan();
        $kelurahan_u->get_by_id($this->input->post('kelurahan_usaha'));
        $perusahaan->save(array($permohonan_akhir, $kelurahan_u));

        /* Input Data Syarat Perizinan */
        $syarat_pendaftaran = new tmpermohonan_trsyarat_perizinan();
        $syarat_pendaftaran->where('tmpermohonan_id', $permohonan_akhir->id)->get();
        $syarat_pendaftaran->delete();

        $syarat = $this->input->post('pemohon_syarat');
        $syarat_len = count($syarat);

        $is_array = NULL;
        for($i=0;$i < $syarat_len;$i++) {
            if($is_array !== $syarat[$i]) {
                $syarat_daftar = new tmpermohonan_trsyarat_perizinan();
                $syarat_daftar->tmpermohonan_id = $permohonan_akhir->id;
                $syarat_daftar->trsyarat_perizinan_id = $syarat[$i];
                $syarat_daftar->save();
            }
            $is_array = $syarat[$i];
        }

        /* Input Data Tracking Progress */
        $tracking_izin = new tmtrackingperizinan();
        $tracking_izin->pendaftaran_id = $nomor_pendaftaran;
        $tracking_izin->status = 'Insert';
        $tracking_izin->d_entry = $this->lib_date->get_date_now();
        $sts_izin = new trstspermohonan();
        $sts_izin->get_by_id('2'); //Menerima dan Memeriksa Berkas [Lihat Tabel trstspermohonan()]
        $sts_izin->save($permohonan_akhir);
        $tracking_izin->save($sts_izin);
        $tracking_izin->save($permohonan_akhir);

        if(! $permohonan_akhir->save($jenis_permohonan)) {
            echo '<p>' . $permohonan_akhir->error->string . '</p>';
        } else {
            redirect('pendaftaran/daftarulang');
        }
    }

    public function update() {
        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($this->input->post('id_daftar'));
        $permohonan->d_terima_berkas = $this->input->post('tgl_daftar');
        $permohonan->d_survey = $this->input->post('tgl_survey');
        $permohonan->a_izin = $this->input->post('lokasi_izin');
        $permohonan->d_daftarulang = $this->input->post('tgl_daftarulang');
        $permohonan->tmpemohon->get();
        $permohonan->tmperusahaan->get();

        /* Input Data Pemohon */
        $pemohon = new tmpemohon();
        $pemohon->get_by_id($permohonan->tmpemohon->id);
        $pemohon->no_referensi = $this->input->post('no_refer');
        $pemohon->n_pemohon = $this->input->post('nama_pemohon');
        $pemohon->telp_pemohon = $this->input->post('no_telp');
        $pemohon->a_pemohon = $this->input->post('alamat_pemohon');
        $pemohon->a_pemohon_luar = $this->input->post('alamat_pemohon_luar');
        $pemohon->trkelurahan->get();
        $pemohon->save();
        $pemohon_lurah = new tmpemohon_trkelurahan();
        $pemohon_lurah->where('tmpemohon_id', $permohonan->tmpemohon->id)
        ->update(array('trkelurahan_id' => $this->input->post('kelurahan_pemohon')));

        /* Input Data Perusahaan */
        $perusahaan = new tmperusahaan();
        $perusahaan->get_by_id($permohonan->tmperusahaan->id);
        $perusahaan->n_perusahaan = $this->input->post('nama_perusahaan');
        $perusahaan->npwp = $this->input->post('npwp');
        $perusahaan->i_telp_perusahaan = $this->input->post('telp_perusahaan');
        $perusahaan->a_perusahaan = $this->input->post('alamat_usaha');
        $perusahaan->trkelurahan->get();
        $perusahaan->save();
        $perusahaan_lurah = new tmperusahaan_trkelurahan();
        $perusahaan_lurah->where('tmperusahaan_id', $permohonan->tmperusahaan->id)
        ->update(array('trkelurahan_id' => $this->input->post('kelurahan_usaha')));

        /* Input Data Syarat Perizinan */
        $syarat_pendaftaran = new tmpermohonan_trsyarat_perizinan();
        $syarat_pendaftaran->where('tmpermohonan_id', $this->input->post('id_daftar'))->get();
        $syarat_pendaftaran->delete();

        $syarat = $this->input->post('pemohon_syarat');
        $syarat_len = count($syarat);

        $is_array = NULL;
        for($i=0;$i < $syarat_len;$i++) {
            if($is_array !== $syarat[$i]) {
                $syarat_daftar = new tmpermohonan_trsyarat_perizinan();
                $syarat_daftar->tmpermohonan_id = $this->input->post('id_daftar');
                $syarat_daftar->trsyarat_perizinan_id = $syarat[$i];
                $syarat_daftar->save();
            }
            $is_array = $syarat[$i];
        }

        /* Input Data Tracking Progress */
        $tracking_izin = new tmtrackingperizinan();
        $tracking_izin->pendaftaran_id = $permohonan->pendaftaran_id;
        $tracking_izin->status = 'Update';
        $tracking_izin->d_entry = $this->lib_date->get_date_now();
        $sts_izin = new trstspermohonan();
        $sts_izin->get_by_id('2'); //Menerima dan Memeriksa Berkas [Lihat Tabel trstspermohonan()]
        $tracking_izin->save($sts_izin);
        $tracking_izin->save($permohonan);

        $update = $permohonan->save();
        if($update) {
            $id_link = $this->input->post('id_link');
            if($id_link=='1') redirect('pendataan');
            else redirect('pendaftaran/daftarulang');
        }
    }

    public function cetak_bukti($id_daftar = NULL) {
        $nama_surat = "cetak_bukti";
        $this->settings = new settings();
        $this->settings->where('name','app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name','app_city')->get();

        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($id_daftar);
        $pemohon = $permohonan->tmpemohon->get();
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

        //Tampilkan Tgl Daftar
        if($permohonan->d_entry){
            if($permohonan->d_entry != '0000-00-00') $tgl_daftar = $this->lib_date->mysql_to_human($permohonan->d_entry);
            else $tgl_daftar = "";
        }else $tgl_daftar = "";

        //Tampilkan Tgl Peninjauan
        if($permohonan->d_survey){
            if($permohonan->d_survey != '0000-00-00') $tgl_survey = $this->lib_date->mysql_to_human($permohonan->d_survey);
            else $tgl_survey = "";
        }else $tgl_survey = "";

        //Tampilkan Tgl Selesai
        if($permohonan->d_selesai_proses){
            if($permohonan->d_selesai_proses != '0000-00-00') $tgl_selesai = $this->lib_date->mysql_to_human($permohonan->d_selesai_proses);
            else $tgl_selesai = "";
        }else $tgl_selesai = "";

        //path of the template file
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/'.$nama_surat.'.odt');
        $odf->setImage('header', 'assets/css/'.$app_folder.'/images/dinas_1.jpg', '17.5', '4.5');

        //fill the template with the variables
        $username = new user();
        $username->where('username', $this->session->userdata('username'))->get();
        if($username->id) $user = $username->realname;
        else $user = "................................";
        $odf->setVars('user', $user);
        $wilayah = new trkabupaten();
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
        }
        $odf->setVars('tglskr', date('d/m/Y'));

        $listeArticles = array(
                array(	'property' => 'Nomor',
                        'content' => $permohonan->perubahan_id,
                ),
                array(	'property' => 'Nama',
                        'content' => $pemohon->n_pemohon.' / '.$usaha->n_perusahaan,
                ),
                array(	'property' => 'Alamat',
                        'content' => $alamat,
                ),
                array(	'property' => 'No. Telp/HP',
                        'content' => $pemohon->telp_pemohon,
                ),
                array(	'property' => 'Jenis Izin',
                        'content' => $data_izin,
                ),
                array(	'property' => 'Lokasi',
                        'content' => $permohonan->a_izin,
                ),
                array(	'property' => 'Tgl Daftar',
                        'content' => $tgl_daftar,
                ),
                array(	'property' => 'Tgl Peninjauan',
                        'content' => $tgl_survey,
                ),
                array(	'property' => 'Tgl Selesai',
                        'content' => $tgl_selesai.' *)',
                ),
        );
        $article = $odf->setSegment('articles');
        foreach($listeArticles AS $element) {
                $article->titreArticle($element['property']);
                $article->texteArticle($element['content']);
                $article->merge();
        }
        $odf->mergeSegment($article);

        //export the file
        $no_daftar = str_replace('/', '', $permohonan->perubahan_id);
        $odf->exportAsAttachedFile($nama_surat.'_'.$no_daftar.'.odt');
    }

}
