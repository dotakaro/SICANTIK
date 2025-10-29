<?php
class perubahan_permohonan extends WRC_AdminCont {

    var $obj;

    /*
     * Variable for generating JSON.
     */
    var $iTotalRecords;
    var $iTotalDisplayRecords;

    /*
     * Variable that taken form input.
     */
    var $iDisplayStart;
    var $iDisplayLength;
    var $iSortingCols;
    var $sSearch;
    var $sEcho;

    private $_status_pendaftaran = 1;//Pendaftaran Sementara
    private $_status_penerimaan = 2;//Menerima dan Memeriksa Berkas

    public function __construct() {
        parent::__construct();
        $this->username = new user();
        $this->pendaftaran = new tmpermohonan();
        $this->perizinan = new trperizinan();
        $this->kelompok_izin = new trkelompok_perizinan();
        $this->jenispermohonan = new trjenis_permohonan();
        $this->propinsi = new trpropinsi();
        $this->kabupaten = new trkabupaten();
        $this->kecamatan = new trkecamatan();
        $this->kelurahan = new trkelurahan();
        $this->pemohon = new tmpemohon();
        $this->perusahaan = new tmperusahaan();
        $this->kegiatan = new trkegiatan();
        $this->investasi = new trinvestasi();
        $this->settings = new settings();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
		
        foreach ($list_auths as $list_auth) {
            if ($list_auth->id_role === '9' or $list_auth->id_role === '11') {
                $enabled = TRUE;
            }
        }

        if (!$enabled) {
            redirect('dashboard');
        }*/

        $this->jenis_id = "1"; // Izin Baru
    }

    public function edit($id_daftar = NULL, $a = NULL, $b = NULL)
    {
        $existingJenisKegiatan = array();
        $existingJenisInvestasi = array();
        $existingSyaratPerizinan = array();
        $existingSyaratTambahan = array();
        $existingSyaratTambahan = array();

        $u_daftar = $this->pendaftaran->get_by_id($id_daftar);
        if(!$u_daftar->id){
            redirect('pelayanan/pendaftaran');
        }

        $data = $this->_preparePendaftaranForm();


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

        $getSyarat = new tmpermohonan_trsyarat_perizinan();
        $savedSyaratPerizinan = $getSyarat->where('tmpermohonan_id', $id_daftar)->get();
        if($savedSyaratPerizinan->id){
            foreach($savedSyaratPerizinan as $keySyarat=>$valSyarat){
                $existingSyaratPerizinan[$valSyarat->trsyarat_perizinan_id] = array(
                    'trsyarat_perizinan_id'=>$valSyarat->trsyarat_perizinan_id,
                    'no_dokumen'=>$valSyarat->no_dokumen,
                    'tgl_awal_berlaku'=>$valSyarat->tgl_awal_berlaku,
                    'tgl_akhir_berlaku'=>$valSyarat->tgl_akhir_berlaku,
                );
            }
        }

        $getSyaratTambahan = new trsyarat_tambahan();
        $savedSyaratTambahan = $getSyaratTambahan->where_related('tmpermohonan', 'id', $id_daftar)->get();
        if($savedSyaratTambahan->id){
            foreach($savedSyaratTambahan as $keySyarat=>$valSyarat){
                $existingSyaratTambahan[] = array(
                    'v_syarat'=>$valSyarat->v_syarat,
                    'no_dokumen'=>$valSyarat->no_dokumen,
                    'tgl_awal_berlaku'=>$valSyarat->tgl_awal_berlaku,
                    'tgl_akhir_berlaku'=>$valSyarat->tgl_akhir_berlaku,
                );
            }
        }

        //Membuat array berisi Jenis Kegiatan Perusahaan
        if($u_kegiatan->id){
            foreach($u_kegiatan as $indexKegiatan=>$kegiatan){
                $existingJenisKegiatan[] = $kegiatan->id;
            }
        }

        //Membuat array berisi Jenis Investasi Perusahaan
        if($u_investasi->id){
            foreach($u_investasi as $indexInvestasi=>$investasi){
                $existingJenisInvestasi[] = $investasi->id;
            }
        }

        $d_izin = $u_daftar->trperizinan->get();
        $d_kelompok = $d_izin->trkelompok_perizinan->get();
        $d_jenis = $u_daftar->trjenis_permohonan->get();

        $data = array_merge($data, $this->_funcwilayah());

        //Mengambil Jenis Investasi berdasarkan Jenis Kegiatan yang dipilih
        //list_investasi ini akan override list_investasi di _funcWilayah()
        $data['list_investasi'] = $this->investasi->where_in('trkegiatan_id',$existingJenisKegiatan)->order_by('n_investasi', 'ASC')->get();

        $jml = $this->get_jml_syarat($d_izin->id, 'seri');
        $data['jml_syarat'] = $jml->jml;

        $data['izin'] = "";
        $data['eror'] = "";
        $data['save_method'] = "update";
        $data['id_daftar'] = $id_daftar;
        $data['paralel'] = "no";
        $paralel_jenis = new trparalel();
        $data['mohon'] = "";
        $data['jenis_paralel'] = $paralel_jenis->get_by_id($u_daftar->c_paralel);
        $data['id_link'] = $id_link;
        $data['waktu_awal'] = $this->lib_date->get_date_now();
        $data['no_refer'] = $p_pemohon->no_referensi;
        $data['nama_pemohon'] = strip_slashes($p_pemohon->n_pemohon);
        $data['no_telp'] = $p_pemohon->telp_pemohon;
        $data['email_pemohon'] = $p_pemohon->email_pemohon;
        $data['cmbsource'] = $p_pemohon->source;
        $data['check_ctr'] = $p_pemohon->cek_prop;
        $data['propinsi_pemohon'] = $p_propinsi->id;
        $data['kabupaten_pemohon'] = $p_kabupaten->id;
        $data['nama_kabupaten_pemohon'] = $p_kabupaten->n_kabupaten;
        $data['kecamatan_pemohon'] = $p_kecamatan->id;
        $data['nama_kecamatan_pemohon'] = $p_kecamatan->n_kecamatan;
        $data['kelurahan_pemohon'] = $p_kelurahan->id;
        $data['nama_kelurahan_pemohon'] = $p_kelurahan->n_kelurahan;

        $data['trunitkerja_id'] = $u_daftar->trunitkerja_id;
//        $data['jenis_kegiatan'] = $u_kegiatan->id;
        $data['jenis_kegiatan'] = $existingJenisKegiatan;
//        $data['jenis_investasi'] = $u_investasi->id;
        $data['jenis_investasi'] = $existingJenisInvestasi;
        $data['existingSyaratPerizinan'] = $existingSyaratPerizinan;
        $data['existingSyaratTambahan'] = $existingSyaratTambahan;

        $data['propinsi_usaha'] = $u_propinsi->id;
        $data['kabupaten_usaha'] = $u_kabupaten->id;
        $data['kecamatan_usaha'] = $u_kecamatan->id;
        $data['kelurahan_usaha'] = $u_kelurahan->id;

        $data['tgl_daftar'] = $u_daftar->d_terima_berkas;
        $data['tgl_survey'] = $u_daftar->d_survey;
        $data['lokasi_izin'] = $u_daftar->a_izin;
        $data['jenis'] = $u_daftar->id_jenis;
        $data['keterangan'] = $u_daftar->keterangan;
        $data['alamat_pemohon'] = $p_pemohon->a_pemohon;
        $data['alamat_pemohon_luar'] = $p_pemohon->a_pemohon_luar;

        $data['id_perusahaan'] = $u_perusahaan->id;
        $data['nama_perusahaan'] = strip_slashes($u_perusahaan->n_perusahaan);
        $data['npwp'] = strip_slashes($u_perusahaan->npwp);
        $data['telp_perusahaan'] = $u_perusahaan->i_telp_perusahaan;
        $data['alamat_usaha'] = $u_perusahaan->a_perusahaan;
        $data['nama_kabupaten_perusahaan'] = $u_kabupaten->n_kabupaten;
        $data['nama_kecamatan_perusahaan'] = $u_kecamatan->n_kecamatan;
        $data['nama_kelurahan_perusahaan'] = $u_kelurahan->n_kelurahan;

        $data['nodaftar'] = $u_perusahaan->no_reg_perusahaan;
        $data['fax'] = $u_perusahaan->fax;
        $data['email'] = $u_perusahaan->email;
        $data['rt'] = $u_perusahaan->rt;
        $data['rw'] = $u_perusahaan->rw;
        $data['jenis_izin'] = $this->perizinan->get_by_id($d_izin->id);
        $data['kelompok_izin'] = $this->kelompok_izin->get_by_id($d_kelompok->id);
        $data['jenis_permohonan'] = $this->jenispermohonan->get_by_id($d_jenis->id);

        $syarat_perizinan = new trsyarat_perizinan();
        $data['syarat_izin'] = $syarat_perizinan->where_related($this->perizinan)->order_by('status', 'asc')->get();
        $data['list_daftar'] = $u_daftar;
        //cek Online
//        $this->settings->where('name', 'app_web_service')->get();
//        $statusOnline = $this->settings->status;
        $data['statusOnline'] = 0;

        $data['disable']=$disable;//Added by Indra untuk disable tombol submit

        ## BEGIN - Data untuk Tab Data Proyek ##
        $data['jenis_usaha'] = $u_daftar->trproyek->tmjenisusaha->id;
        $data['target_pad'] = $u_daftar->trproyek->target_pad;
        $data['nilai_investasi'] = $u_daftar->trproyek->nilai_investasi;
        $data['jumlah_tenaga_kerja'] = $u_daftar->trproyek->jumlah_tenaga_kerja;
        ## END - Data untuk Tab Data Proyek ##

        //cek Online penduduk
//        $this->settings->where('name', 'web_service_penduduk')->get();
//        $statusOnline2 = $this->settings->status;
        $data['statusOnline2'] = 0;

        $js = "
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

                    /* global setting */
                    var datepickersOpt = {
                        dateFormat: 'yy-mm-dd',
                        changeMonth: true,
                        changeYear: true,
                        closeText: 'X'
                    }

                    $(\".tgl-awal-berlaku\").datepicker($.extend({
                        onSelect: function() {
                            var minDate = $(this).datepicker('getDate');
                            minDate.setDate(minDate.getDate()+1); //add one day
                            $(this).parent().find(\".tgl-akhir-berlaku\").datepicker( \"option\", \"minDate\", minDate);
                        }
                    },datepickersOpt));

                    $(\".tgl-akhir-berlaku\").datepicker($.extend({
                    onSelect: function() {
                        var maxDate = $(this).datepicker('getDate');
                        maxDate.setDate(maxDate.getDate()-2);
                        $(this).parent().find(\".tgl-awal-berlaku\").datepicker( \"option\", \"maxDate\", maxDate);
                    }
                    },datepickersOpt));

                });

                 $(document).ready(function() {
                     $('#propinsi_pemohon_id').change(function(){
                        $.post('" . base_url() . "pelayanan/pendaftaran/kabupaten_pemohon', { propinsi_id: $('#propinsi_pemohon_id').val() },
                           function(data) {
                             $('#show_kabupaten_pemohon').html(data);
                             $('#show_kecamatan_pemohon').html('Data Tidak tersedia');
                             $('#show_kelurahan_pemohon').html('Data Tidak tersedia');
                           });
                         });

                     $('#propinsi_usaha_id').change(function(){
                        $.post('" . base_url() . "pelayanan/pendaftaran/kabupaten_usaha', { propinsi_id: $('#propinsi_usaha_id').val() },
                            function(data) {
                                $('#show_kabupaten_usaha').html(data);
                                $('#show_kecamatan_usaha').html('Data Tidak tersedia');
                                $('#show_kelurahan_usaha').html('Data Tidak tersedia');
                            });
                        });

                    $('#jenis_kegiatan').multiselect({
                       show:'blind',
                       hide:'blind',
                       multiple: true,
                       header: 'Pilih Kode Bidang Usaha',
                       noneSelectedText: 'Pilih Kode Bidang Usaha',
                       selectedList: 1
                    }).multiselectfilter();

                    $('#jenis_investasi').multiselect({
                       show:'blind',
                       hide:'blind',
                       multiple: true,
                       header: 'Pilih Jenis Produksi/Jasa',
                       noneSelectedText: 'Pilih Jenis Produksi/Jasa',
                       selectedList: 1
                    }).multiselectfilter();

                    $('#jenis_kegiatan').change(function(){
                        var selectedKegiatan = $(this).val();
                        //ambil unit melalui ajax
                        $.ajax({
                            url:'".site_url('pelayanan/pendaftaran/ajax_get_jenis_investasi')."',
                            type:'POST',
                            dataType:'json',
                            data:{trkegiatan_id : selectedKegiatan},
                            success:function(r){
                                var selectOption = '';
                                $.each(r,function(key,val){
                                    selectOption += '<option value=\"'+val.id+'\">'+val.n_investasi+'-'+val.keterangan+'</option>';
                                });
                                $('#jenis_investasi').html(selectOption);
                                $('#jenis_investasi').multiselect('refresh');
                            }
                        });
                    });

                    $('.check-terpenuhi').change(function(){
                        var checked = $(this).attr('checked');
                        if(checked != 'checked'){
                            //Disable No Referensi, Tanggal Awal, dan Tanggal Akhir Berlaku
                            $(this).parent().parent().find('.no-dokumen').attr('disabled','disabled').val('');
                            $(this).parent().parent().find('.tgl-awal-berlaku, .tgl-akhir-berlaku').attr('disabled','disabled').val('');
                        }else{
                            //Enable No Referensi, Tanggal Awal, dan Tanggal Akhir Berlaku
                            $(this).parent().parent().find('.no-dokumen').removeAttr('disabled');
                            $(this).parent().parent().find('.tgl-awal-berlaku, .tgl-akhir-berlaku').removeAttr('disabled');
                        }
                    });
                });


                function finishAjax(id, response){
                  $('#'+id).html(unescape(response));
                  $('#'+id).fadeIn();
                }

                function Check(){
                    if(document.form.Check_ctr.checked == true){
                        document.form.propinsi_pemohon.disabled = false ;
                        document.form.kabupaten_pemohon.disabled = false ;
                        document.form.kecamatan_pemohon.disabled = false ;
                        document.form.kelurahan_pemohon.disabled = false ;
                    }else{
                        document.form.propinsi_pemohon.disabled = true ;
                        document.form.kabupaten_pemohon.disabled = true ;
                        document.form.kecamatan_pemohon.disabled = true ;
                        document.form.kelurahan_pemohon.disabled = true ;
                    }
                }
            ";

        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        if ($id_link == "1") {
            $this->session_info['page_name'] = "Entry data edit";
        } else {
            $this->session_info['page_name'] = "Edit Permohonan";
        }
        $this->template->build('perubahan_permohonan_edit', $this->session_info);
    }


    function update()
    {
        $perizinan = new trperizinan();
        $perizinan->get_by_id($this->input->post('jenis_izin_id'));
        $permohonanId = $this->input->post('id_daftar');

        /*
         * Cek Persyaratan Izin
         */
        $syarat_perizinan = new trsyarat_perizinan();
        $izin_len = $syarat_perizinan->where_related($perizinan)->where('status', 1)->count();
        $syarat_izin = new trsyarat_perizinan();
        $list_syarat = $syarat_izin->where_related($perizinan)->where('status', 1)->get();

        $syarat = $this->input->post('pemohon_syarat');
        $noDokumen = $this->input->post('no_dokumen');
        $tglAwalBerlaku = $this->input->post('tgl_awal_berlaku');
        $tglAkhirBerlaku = $this->input->post('tgl_akhir_berlaku');
        $syarat_len = count($syarat);

        $wajib_len = 0;
        foreach ($list_syarat as $data) {
            $is_array = NULL;
            for ($i = 0; $i < $syarat_len; $i++) {
                if ($is_array !== $syarat[$i]) {
                    if ($data->id == $syarat[$i])
                        $wajib_len++;
                }
                $is_array = $syarat[$i];
            }
        }


        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($this->input->post('id_daftar'));
        $permohonan->d_terima_berkas = $this->input->post('tgl_daftar');
        $permohonan->d_survey = $this->input->post('tgl_survey');
        $permohonan->a_izin = $this->input->post('lokasi_izin');
        $permohonan->keterangan = $this->input->post('keterangan');
        $tgl_entry = $this->input->post('tgl_daftar');
        $tgl_durasi = $this->lib_date->set_date($tgl_entry, $perizinan->v_hari);
        $libur = new tmholiday();
        $hari_libur = $libur->where('date >=', $tgl_entry)->where('date <=', $tgl_durasi)->count();
        $hari_durasi = $perizinan->v_hari + $hari_libur;
        $permohonan->d_selesai_proses = $this->lib_date->set_date($tgl_entry, $hari_durasi);
        $permohonan->tmpemohon->get();
        $permohonan->tmperusahaan->get();

        /* Input Data Pemohon */
        $pemohon = new tmpemohon();
        $pemohon->get_by_id($permohonan->tmpemohon->id);
        $pemohon->no_referensi = $this->input->post('no_refer');
        $pemohon->source = $this->input->post('cmbsource');
        $pemohon->n_pemohon = $this->db->escape_str($this->input->post('nama_pemohon'));
        $pemohon->telp_pemohon = $this->input->post('no_telp');
        $pemohon->a_pemohon = $this->input->post('alamat_pemohon');
        $pemohon->a_pemohon_luar = $this->input->post('alamat_pemohon_luar');
        $pemohon->trkelurahan->get();
        $pemohon_lurah = new tmpemohon_trkelurahan();
        $pemohon_lurah->where('tmpemohon_id', $permohonan->tmpemohon->id)->get();
        $pemohon_lurah->delete();
//        if ($this->input->post('Check_ctr')) {
        $pemohon->cek_prop = "0";
        $kelurahan_p = new trkelurahan();
        $kelurahan_p->get_by_id($this->input->post('kelurahan_pemohon'));
        $pemohon->save(array($kelurahan_p));
//        } else {
//            $pemohon->cek_prop = "1";
//            $pemohon->save();
//        }

        /* Input Data Perusahaan */
        if ($permohonan->tmperusahaan->id) {//Jika sebelumnya sudah input data perusahaan
            $perusahaan = new tmperusahaan();
            $perusahaan->get_by_id($permohonan->tmperusahaan->id);
            $perusahaan->n_perusahaan = $this->db->escape_str($this->input->post('nama_perusahaan'));
            $perusahaan->npwp = $this->db->escape_str($this->input->post('npwp'));
            $perusahaan->no_reg_perusahaan = $this->input->post('nodaftar');
            $perusahaan->rt = $this->input->post('rt');
            $perusahaan->rw = $this->input->post('rw');
            $perusahaan->fax = $this->input->post('fax');
            $perusahaan->email = $this->input->post('email');
            $perusahaan->i_telp_perusahaan = $this->input->post('telp_perusahaan');
            $perusahaan->a_perusahaan = $this->input->post('alamat_usaha');
            $perusahaan->trkelurahan->get();
            $perusahaan->save();
            $perusahaan_lurah = new tmperusahaan_trkelurahan();
            $perusahaan_lurah->where('tmperusahaan_id', $permohonan->tmperusahaan->id)
                    ->update(array('trkelurahan_id' => $this->input->post('kelurahan_usaha')));

            $perusahaan_kegiatan = new tmperusahaan_trkegiatan();
//            $perusahaan_kegiatan->where('tmperusahaan_id', $this->input->post('id_perusahaan'))
//                    ->update(array('trkegiatan_id' => $this->input->post('jenis_kegiatan')));
            $perusahaan_investasi = new tmperusahaan_trinvestasi();
//            $perusahaan_investasi->where('tmperusahaan_id', $this->input->post('id_perusahaan'))
//                    ->update(array('trinvestasi_id' => $this->input->post('jenis_investasi')));

            $jenisKegiatan = $this->input->post('jenis_kegiatan');
            $jenisInvestasi = $this->input->post('jenis_investasi');
            $idPerusahaan = $permohonan->tmperusahaan->id;

            //Added by Indra - Jika ada data sebelumnya, delete
            if($perusahaan->trkegiatan->id){
                $perusahaan_kegiatan->where('tmperusahaan_id', $idPerusahaan)->get()->delete_all();
            }

            //Added by Indra - Save setiap Kegiatan
            if(is_array($jenisKegiatan) && !empty($jenisKegiatan)){
                foreach($jenisKegiatan as $keyKegiatan=>$kegiatanId){
                    $perusahaan_kegiatan = new tmperusahaan_trkegiatan();
                    $perusahaan_kegiatan->tmperusahaan_id = $idPerusahaan;
                    $perusahaan_kegiatan->trkegiatan_id = $kegiatanId;
                    $perusahaan_kegiatan->save();
                }
            }

            //Added by Indra - Jika ada data sebelumnya, delete
            if($perusahaan->trinvestasi->id){
                $perusahaan_investasi = new tmperusahaan_trinvestasi();
                $perusahaan_investasi->where('tmperusahaan_id', $idPerusahaan)->get()->delete_all();
            }

            //Added by Indra - Save setiap Investasi
            if(is_array($jenisInvestasi) && !empty($jenisInvestasi)){
                foreach($jenisInvestasi as $keyInvestasi=>$investasiId){
                    $perusahaan_investasi = new tmperusahaan_trinvestasi();
                    $perusahaan_investasi->tmperusahaan_id = $idPerusahaan;
                    $perusahaan_investasi->trinvestasi_id = $investasiId;
                    $perusahaan_investasi->save();
                }
            }

        } else {
            if ($this->input->post('nama_perusahaan')) {
                $perusahaan = new tmperusahaan();
                $perusahaan->n_perusahaan = $this->db->escape_str($this->input->post('nama_perusahaan'));
                $perusahaan->npwp = $this->db->escape_str($this->input->post('npwp'));
                $perusahaan->no_reg_perusahaan = $this->input->post('nodaftar');
                $perusahaan->rt = $this->input->post('rt');
                $perusahaan->rw = $this->input->post('rw');
                $perusahaan->i_telp_perusahaan = $this->input->post('telp_perusahaan');
                $perusahaan->a_perusahaan = $this->input->post('alamat_usaha');
                $kelurahan_u = new trkelurahan();
                $kelurahan_u->get_by_id($this->input->post('kelurahan_usaha'));
                $kegiatan = new trkegiatan();
                $kegiatan->get_by_id($this->input->post('jenis_kegiatan'));
                $investasi = new trinvestasi();
                $investasi->get_by_id($this->input->post('jenis_investasi'));

                $jenisKegiatan = $this->input->post('jenis_kegiatan');
                $jenisInvestasi = $this->input->post('jenis_investasi');
                $idPerusahaan = $this->input->post('id_perusahaan');

//                $perusahaan->save(array($permohonan, $kelurahan_u, $kegiatan, $investasi));
                $perusahaan->save(array($permohonan, $kelurahan_u));

                //Added by Indra - Save Multiple Kegiatan
                if(is_array($jenisKegiatan) && !empty($jenisKegiatan)){
                    foreach($jenisKegiatan as $keyKegiatan=>$kegiatanId){
                        $perusahaan_kegiatan = new tmperusahaan_trkegiatan();
                        $perusahaan_kegiatan->tmperusahaan_id = $perusahaan->id;
                        $perusahaan_kegiatan->trkegiatan_id = $kegiatanId;
                        $perusahaan_kegiatan->save();
                    }
                }

                //Added by Indra - Save Multiple Investasi
                if(is_array($jenisInvestasi) && !empty($jenisInvestasi)){
                    foreach($jenisInvestasi as $keyInvestasi=>$investasiId){
                        $perusahaan_investasi = new tmperusahaan_trinvestasi();
                        $perusahaan_investasi->tmperusahaan_id = $perusahaan->id;
                        $perusahaan_investasi->trinvestasi_id = $investasiId;
                        $perusahaan_investasi->save();
                    }
                }


            }
        }

        ##BEGIN - simpan data proyek ##
        $proyek = new trproyek();
        if($permohonan->trproyek->id){//Jika ada data sebelumnya, load
            $proyek->get_by_id($permohonan->trproyek_id);
        }
        $proyek->target_pad = $this->input->post('target_pad');
        $proyek->nilai_investasi = $this->input->post('nilai_investasi');
        $proyek->jumlah_tenaga_kerja = $this->input->post('jumlah_tenaga_kerja');
        $jenisUsahaId = $this->input->post('jenis_usaha_id');

        if($jenisUsahaId!= '' && $jenisUsahaId!=0){
            $tmjenisusaha = new tmjenisusaha();
            $jenisUsaha = $tmjenisusaha->get_by_id($jenisUsahaId);
            if($jenisUsaha->id){
                $proyek->save(array($permohonan, $jenisUsaha));
            }
        }else{
            $proyek->save(array($permohonan));
        }
        ##END - simpan data proyek ##

        /* Input Data Syarat Perizinan */
        $syarat_pendaftaran = new tmpermohonan_trsyarat_perizinan();
        $syarat_pendaftaran->where('tmpermohonan_id', $this->input->post('id_daftar'))->get();
        $syarat_pendaftaran->delete();

        $syarat = $this->input->post('pemohon_syarat');
        $syarat_len = count($syarat);

        $is_array = NULL;
        for ($i = 0; $i < $syarat_len; $i++) {
            if ($is_array !== $syarat[$i]) {
                $syarat_daftar = new tmpermohonan_trsyarat_perizinan();
                $syarat_daftar->tmpermohonan_id = $this->input->post('id_daftar');
                $syarat_daftar->trsyarat_perizinan_id = $syarat[$i];
                if($noDokumen[$i] != '' && !is_null($noDokumen[$i])){
                    $syarat_daftar->no_dokumen = $noDokumen[$i];
                }
                if($tglAwalBerlaku[$i] != '' && !is_null($tglAwalBerlaku[$i])) {
                    $syarat_daftar->tgl_awal_berlaku = $tglAwalBerlaku[$i];
                }
                if($tglAkhirBerlaku[$i] != '' && !is_null($tglAkhirBerlaku[$i])) {
                    $syarat_daftar->tgl_akhir_berlaku = $tglAkhirBerlaku[$i];
                }

                $syarat_daftar->save();
            }
            $is_array = $syarat[$i];
        }

        ### BEGIN - Input Data Syarat Tambahan ###
        $syaratTambahan = new trsyarat_tambahan();
        $syaratTambahan->where_related('tmpermohonan', 'id', $permohonanId)->get();
        $syaratTambahan->delete_all();

        $dataSyaratTambahan = $this->input->post('syarat_tambahan');
        if(!empty($dataSyaratTambahan)){
            foreach($dataSyaratTambahan as $idxTambahan=>$tambahan){
                $syaratTambahan = new trsyarat_tambahan();
                $syaratTambahan->v_syarat = $tambahan['v_syarat'];
                $syaratTambahan->no_dokumen = $tambahan['no_dokumen'];
                $syaratTambahan->tgl_awal_berlaku = $tambahan['tgl_awal_berlaku'];
                $syaratTambahan->tgl_akhir_berlaku = $tambahan['tgl_akhir_berlaku'];
                $syaratTambahan->save($permohonan);
            }
        }
        ### END - Input Data Syarat Tambahan ###

        /* Input Data Tracking Progress */
        $id_link = $this->input->post('id_link');
        if (!$id_link == '1') {
            $status_izin = $permohonan->trstspermohonan->get();
            $status_skr = "2"; //Menerima dan Memeriksa Berkas [Lihat Tabel trstspermohonan()]
            if ($status_izin->id == $status_skr) {
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
                $tracking_izin->d_entry = $this->lib_date->get_date_now();
                $tracking_izin->save();
            }
        }


//        if ($update) {
//            if ($id_link == '1')
//                redirect('pendataan');
//            else
//                redirect('pelayanan/pendaftaran');
//        }
//$this->load->library('form_validation');
//         //validasi pemohon
//        $this->form_validation->set_rules('nama_pemohon','Nama', 'required');
//        
//        $this->form_validation->set_rules('no_telp','Nomor', 'required');
//        $this->form_validation->set_rules('alamat_pemohon','Alamat', 'required');
//        $this->form_validation->set_rules('propinsi_pemohon','propinsi', 'required');
//        $this->form_validation->set_rules('kabupaten_pemohon','kabupaten', 'required');
//        $this->form_validation->set_rules('kecamatan_pemohon','kecamatan', 'required');
//        $this->form_validation->set_rules('kelurahan_pemohon','kelurahan', 'required');
//        $this->form_validation->set_rules('no_refer','peninjauan', 'required');
//        $this->form_validation->set_rules('tgl_daftar','Tanggal', 'required');
//       
//
//   //validasi perusahaan
//        $this->form_validation->set_rules('npwp','Npwp', 'required');
//        $this->form_validation->set_rules('nodaftar','No Daftar', 'required');
//        $this->form_validation->set_rules('nama_perusahaan','Nama Perusahaan', 'required');
//        $this->form_validation->set_rules('telp_perusahaan','Telp', 'required');
//        $this->form_validation->set_rules('propinsi_usaha','propinsi', 'required');
//        $this->form_validation->set_rules('kabupaten_usaha','kabupaten', 'required');
//        $this->form_validation->set_rules('kecamatan_usaha','kecamatan', 'required');
//        $this->form_validation->set_rules('kelurahan_usaha','kelurahan', 'required');
//        $this->form_validation->set_rules('alamat_usaha','a_usaha', 'required');
//        $this->form_validation->set_rules('jenis_kegiatan','j_kegiatan', 'required');
//        $this->form_validation->set_rules('jenis_investasi','j_investasi', 'required');
//
        //if ($izin_len == $wajib_len)
        //{
        $update = $permohonan->save();
        /*$tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql2($u_ser);
//      $jam = date("H:i:s A");
        $p = $this->db->query("call log ('Pendaftaran','Update " . $permohonan->pendaftaran_id . "','" . $tgl . "','" . $u_ser . "')");
*/
        if ($id_link == '1') {
            redirect('pendataan');
        } else {
            redirect('pelayanan/perubahan/permohonan');
        }

        // }
//             else
//        {
//
//           $this->edit2();
//        }
    }

    private function _preparePendaftaranForm(){
        $data = array();
        ### BEGIN - Ambil Master Data Jenis Usaha ##
        $listJenisUsaha = array();
        $this->tmjenisusaha = new tmjenisusaha();
        $getJenisUsaha = $this->tmjenisusaha->get();
        $listJenisUsaha[0] = '-------Pilih data-------';
        if($getJenisUsaha->id){
            foreach($getJenisUsaha as $dataJenisUsaha){
                $listJenisUsaha[$dataJenisUsaha->id] = $dataJenisUsaha->n_jenis_usaha;
            }
        }
        ### END - Ambil Master Data Jenis Usaha ##
        $data['listJenisUsaha'] = $listJenisUsaha;
        return $data;
    }

    function _funcwilayah() {

        $data['list_propinsi'] = $this->propinsi->order_by('n_propinsi', 'ASC')->get();
        $data['list_kabupaten'] = $this->kabupaten->order_by('n_kabupaten', 'ASC')->get();
//        $data['list_kecamatan'] = $this->kecamatan->order_by('n_kecamatan', 'ASC')->get();
//        $data['list_kelurahan'] = $this->kelurahan->order_by('n_kelurahan', 'ASC')->get();
        $data['list_kecamatan'] = new stdClass();
        $data['list_kelurahan'] = new stdClass();

        $data['list_kegiatan'] = $this->kegiatan->order_by('n_kegiatan', 'ASC')->get();
//        $data['list_investasi'] = $this->investasi->order_by('n_investasi', 'ASC')->get();
        $data['list_investasi'] = new stdClass();

        return $data;
    }

    function get_jml_syarat($id, $jenis) {
        if ($id == NULL) {
            redirect('pelayanan/pendaftaran');
        } else {
            $dum = $this->get_perizinan_baru($id, $jenis);
            if ($jenis == 'paralel') {
                $query = "SELECT COUNT(DISTINCT trsyarat_perizinan.id) as jml FROM
                trperizinan_trsyarat_perizinan
                INNER JOIN
                trsyarat_perizinan ON trsyarat_perizinan.id = trperizinan_trsyarat_perizinan.trsyarat_perizinan_id
                INNER JOIN
                trperizinan ON trperizinan.id = trperizinan_trsyarat_perizinan.trperizinan_id
                WHERE trsyarat_perizinan.`status` = '1' and trperizinan.id IN ($id) and c_show_type IN ('" . implode("','", $dum) . "')";
            } else {
                $query = "SELECT COUNT(*) as jml FROM
                trperizinan_trsyarat_perizinan
                INNER JOIN
                trsyarat_perizinan ON trsyarat_perizinan.id = trperizinan_trsyarat_perizinan.trsyarat_perizinan_id
                INNER JOIN
                trperizinan ON trperizinan.id = trperizinan_trsyarat_perizinan.trperizinan_id
                WHERE trsyarat_perizinan.`status` = '1' and trperizinan.id = " . $id . " and c_show_type IN ('" . implode("','", $dum) . "')
                GROUP BY n_perizinan";
            }
            $hasil = $this->db->query($query);
            return $hasil->row();
        }
    }

    function get_perizinan_baru($id, $jenis) {
        if ($jenis == 'paralel') {
            $sql = "SELECT DISTINCT c_show_type FROM trperizinan_trsyarat_perizinan WHERE trperizinan_id IN ($id)";
        } else {
            $sql = "SELECT DISTINCT c_show_type FROM trperizinan_trsyarat_perizinan WHERE trperizinan_id = '$id'";
        }
        $hasil = $this->db->query($sql);
        $result = $hasil->result();
        $arr = array();
        foreach ($result as $row) {

            $var = $row->c_show_type;
            $rule = strval(decbin($var));
            if (strlen($rule) < 4) {
                $len = 4 - strlen($rule);
                $rule = str_repeat("0", $len) . $rule;
            }
            $arr_rule = str_split($rule);
            $c_baru = $arr_rule[1];

            if ($arr_rule[1] == '1') {
                $arr[] = $var;
            }
        }
        return $arr;
        //var_dump($arr);
    }
}
?>