<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of monitoring class
 *
 * @author  Yogi Cahyana & zulfah
 * @since   1988
 *
 */
class Monitoring extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->monitoring_bi= new monitoring_bi();
        $this->pemohon = new tmpemohon();
        $this->permohonan = new tmpermohonan();
        $this->perizinan = new trperizinan();
        $this->propinsi = new trpropinsi();
        $this->kabupaten = new trkabupaten();
        $this->kecamatan = new trkecamatan();
        $this->kelurahan = new trkelurahan();
        $this->list_wilayah = new list_wilayah();
        $this->monitoring = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->monitoring = NULL;

        foreach ($list_auths as $list_auth) {
            if ($list_auth->id_role === '8') {
                $enabled = TRUE;
                $this->monitoring = new user_auth();
            }
        }

        if (!$enabled) {
            redirect('dashboard');
        }*/
    }

	/**
	* @ModifiedAuthor Indra
	* Modified 27-04-2013
	* @ModifiedComment List Ijin yang sebelumnya disort by id diganti menjadi sort by n_perizinan, penambahan column untuk grid ajax (aoColumns)
	*/
    public function index() {

        $jenis_izin = $this->input->post('jenis_izin');
        $first_date = $this->input->post('first_date') == null ? $this->lib_date->set_date(date('Y-m-d'), -2) : $this->input->post('first_date');
        $second_date = $this->input->post('second_date') == null ? $this->lib_date->set_date(date('Y-m-d'), 0) : $this->input->post('second_date');

        $mark = $this->input->post('mark');
       $data['jumlah']=$this->monitoring_bi->get_total_perizinan($cari=NULL,$first_date, $second_date, $jenis_izin);
       
        $stspermohonan = new trstspermohonan();
        $data['submit'] = $this->input->post('submit');
        $data['jenis'] = $jenis_izin;
        $data['first'] = $first_date;
        $data['second'] = $second_date;
        $data['mark'] = $mark;
        $data['list_ijin'] = $this->perizinan
            ->where_related('user','id',$this->session->userdata('id_auth'))
            ->order_by('n_perizinan', 'ASC')->get();

        $this->load->vars($data);

        $js = "
            $(document).ready(function() {
               oTable= $('#monitoring').dataTable
                ({
					
                    'bJQueryUI'      : true,
                    'sPaginationType': 'full_numbers',
                    'bServerSide'    : true,
                    'bAutoWidth'     : false,
                    'bSort'          : false,
                    'sAjaxSource'    : '" . base_url() . "monitoring/datatables/list_Monitoring_Per_Perizinan',
                    'aoColumns'      :
                        [
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
						null
                    ],
                    'fnServerData': function(sSource, aoData, fnCallback)
                    {
                        aoData.push({ 'name': 'jenis_izin', 'value': $('#selector option:selected').val() });
                        aoData.push({ 'name': 'first_date', 'value': $('#firstDateInput').val() });
                        aoData.push({ 'name': 'second_date', 'value': $('#secondDateInput').val() });
                        //aoData.push({ 'name': 'n_pemohon', 'value': $('#monitoring_filter input').val() });
                        $.ajax
                        ({
                            'dataType': 'json',
                            'type'    : 'POST',
                            'url'     : sSource,
                            'data'    : aoData,
                            'success' : fnCallback
                        });
                    }
                });
            });
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
        $this->session_info['page_name'] = "Monitoring Per Perizinan";
        $this->template->build('list', $this->session_info);
    }

    public function perwaktu() {
        $first_date = $this->input->post('first_date') == null ? $this->lib_date->set_date(date('Y-m-d'), -2) : $this->input->post('first_date');
        $second_date = $this->input->post('second_date') == null ? $this->lib_date->set_date(date('Y-m-d'), 0) : $this->input->post('second_date');
        $stspermohonan = new trstspermohonan();

        $data['jumlah']=$this->monitoring_bi->get_total_jangka_waktu($cari=NULL, $first_date, $second_date);

        $data['first_date'] = $first_date;
        $data['second_date'] = $second_date;
        $data['submit'] = $this->input->post('submit');
        $this->load->vars($data);

        $js = "
            $(document).ready(function() {
                $('#monitoring').dataTable
                ({
                    'bJQueryUI'      : true,
                    'sPaginationType': 'full_numbers',
                    'bServerSide'    : true,
                     'bSort': false,
                    'bAutoWidth'     : false,
                    'sAjaxSource'    : '" . base_url() . "monitoring/datatables/list_Monitoring_Per_Jangka_Waktu',
                    'aoColumns'      :
                        [
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null
                    ],

                    'fnServerData': function(sSource, aoData, fnCallback)
                    {
                      
                        aoData.push({ 'name': 'first_date', 'value': $('#firstDateInput').val() });
                        aoData.push({ 'name': 'second_date', 'value': $('#secondDateInput').val() });
                        $.ajax
                        ({
                            'dataType': 'json',
                            'type'    : 'POST',
                            'url'     : sSource,
                            'data'    : aoData,
                            'success' : fnCallback
                        });
                    }
                });
            });

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
        $this->session_info['page_name'] = "Monitoring Per Jangka Waktu";
        $this->template->build('list_perbulan', $this->session_info);
    }

    public function kecamatan() {


        $propinsi_id = $this->input->post('propinsi_pemohon');
        $kabupaten_id = $this->input->post('kabupaten_pemohon');
        $kecamatan_id = $this->input->post('kecamatan_pemohon');
        $kelurahan_id = $this->input->post('kelurahan_pemohon');

        $submit = $this->input->post('submit');

        if ($propinsi_id == NULL) {
            $propinsi_id = 0;
        }
        if ($kabupaten_id == NULL) {
            $kabupaten_id = 0;
        }
        if ($kecamatan_id == NULL) {
            $kecamatan_id = 0;
        }
        if ($kelurahan_id == NULL) {
            $kelurahan_id = 0;
        }




        $data['propinsi_id'] = $propinsi_id;
        $data['kabupaten_id'] = $kabupaten_id;
        $data['kecamatan_id'] = $kecamatan_id;
        $data['kelurahan_id'] = $kelurahan_id;
        $data['submit'] = $submit;

        $first_date = $this->input->post('first_date') == null ? $this->lib_date->set_date(date('Y-m-d'), -2) : $this->input->post('first_date');
        $second_date = $this->input->post('second_date') == null ? $this->lib_date->set_date(date('Y-m-d'), 0) : $this->input->post('second_date');
        $stspermohonan = new trstspermohonan();

        $data['first_date'] = $first_date;
        $data['second_date'] = $second_date;
        $this->load->vars($data);


        $data['propinsi_id'] = $propinsi_id;
        $data['kabupaten_id'] = $kabupaten_id;
        $data['kecamatan_id'] = $kecamatan_id;
        $data['kelurahan_id'] = $kelurahan_id;
        $list_kabupaten = NULL;
        $list_kelurahan = NULL;
        $list_kecamatan = NULL;
        if ($submit) {
            $list_kabupaten = $this->list_wilayah->get_result_kabupaten($propinsi_id);
            $list_kecamatan = $this->list_wilayah->get_result_kecamatan($kabupaten_id);
            $list_kelurahan = $this->list_wilayah->get_result_kelurahan($kecamatan_id);
        }

        $data['list_propinsi'] = $this->propinsi->order_by('n_propinsi', 'ASC')->get();

        $data['list_kabupaten'] = $list_kabupaten; //$this->kabupaten->order_by('n_kabupaten', 'ASC')->get();
        $data['list_kecamatan'] = $list_kecamatan; //$this->kecamatan->order_by('n_kecamatan', 'ASC')->get();
        $data['list_kelurahan'] = $list_kelurahan; //$this->kelurahan->order_by('n_kelurahan', 'ASC')->get();

         $data['jumlah']=$this->monitoring_bi->get_total_kecamatan($cari=NULL, $first_date, $second_date, $kelurahan_id);
        $this->load->vars($data);
        $js = "
            $(document).ready(function() {
                $('#listdata').dataTable
                ({
                    'bJQueryUI'      : true,
                    'sPaginationType': 'full_numbers',
                    'bServerSide'    : true,
                     'bSort': false,
                    'bAutoWidth'     : false,
                    'sAjaxSource'    : '" . base_url() . "monitoring/datatables/list_kecamatan',
                    'aoColumns'      :
                        [
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null
                    ],
                    'fnServerData': function(sSource, aoData, fnCallback)
                    {
                        aoData.push({ 'name': 'kelurahan_id', 'value': '" . $data['kelurahan_id'] . "' });
                        aoData.push({ 'name': 'first_date', 'value': '" . $first_date . "' });
                        aoData.push({ 'name': 'second_date', 'value': '" . $second_date . "' });
                        $.ajax
                        ({
                            'dataType': 'json',
                            'type'    : 'POST',
                            'url'     : sSource,
                            'data'    : aoData,
                            'success' : fnCallback
                        });
                    }
                });        
                
$('.monbulan').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });                   
                 $('#propinsi_pemohon_id').change(function(){
                                        $.post('" . base_url() . "pelayanan/pendaftaran/kabupaten_pemohon', { propinsi_id: $('#propinsi_pemohon_id').val() },
                                                       function(data) {
                                                         $('#show_kabupaten_pemohon').html(data);
                                                         $('#show_kecamatan_pemohon').html('Data Tidak tersedia');
                                                         $('#show_kelurahan_pemohon').html('Data Tidak tersedia');
                                                       });
                                         }); 

    }); ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Monitoring Per Wilayah";
        $this->template->build('list_kecamatan', $this->session_info);
    }

    public function kabupaten_pemohon() {
        $data['kabupaten_id'] = 'kabupaten_pemohon';
        $data['kecamatan_id'] = 'kecamatan_pemohon';

        $this->load->vars($data);
        $this->load->view('kabupaten_load_lagi', $data);
    }

    public function kecamatan_pemohon() {
        $data['kecamatan_id'] = 'kecamatan_pemohon';
        $data['kelurahan_id'] = 'kelurahan_pemohon';

        $this->load->vars($data);
        $this->load->view('kecamatan_load_lagi', $data);
    }

    public function kelurahan_pemohon() {
        $data['kelurahan_id'] = 'kelurahan_pemohon';

        $this->load->vars($data);
        $this->load->view('kelurahan_load_lagi', $data);
    }

    public function state() {
        $today = date('Y-m-d');
        $first_date = $this->input->post('first_date') == null ? $this->lib_date->set_date(date('Y-m-d'), -2) : $this->input->post('first_date');
        $second_date = $this->input->post('second_date') == null ? $this->lib_date->set_date(date('Y-m-d'), 0) : $this->input->post('second_date');
        $list_state = $this->input->post('list_state');
        $mark = $this->input->post('mark');
        $stspermohonan = new trstspermohonan();
        $data['list_state'] = $list_state;
        $data['first_date'] = $first_date;
        $data['second_date'] = $second_date;
        $data['mark'] = $mark;
        $obj = $this->permohonan;
        //$obj->where_related('trstspermohonan', 'id', "NOT IN 1");
        $data['listpermohonan'] = $obj->where_related("trstspermohonan", 'id <> 1')->where("d_terima_berkas >= '$first_date' AND d_terima_berkas <= '$second_date'")->get();
        
        if ($list_state === '1') {
            $data['listpermohonan'] = $obj->where_related("trstspermohonan", 'id <> 1')->where('c_izin_selesai', 1)->where("d_terima_berkas >= '$first_date' AND d_terima_berkas <= '$second_date'")->get();
            $obj = $this->permohonan;
            $data['jumlah'] = $obj->where_related("trstspermohonan", 'id <> 1')->where('c_izin_selesai', 1)->where("d_terima_berkas >= '$first_date' AND d_terima_berkas <= '$second_date'")->count();
        } elseif ($list_state === '0') {
            $data['listpermohonan'] = $obj->where_related("trstspermohonan", 'id <> 1')->where('c_izin_selesai', 0)->where("d_terima_berkas >= '$first_date' AND d_terima_berkas <= '$second_date'")->get();
            $obj = $this->permohonan;
            $data['jumlah'] = $obj->where_related("trstspermohonan", 'id <> 1')->where('c_izin_selesai', 0)->where("d_terima_berkas >= '$first_date' AND d_terima_berkas <= '$second_date'")->count();
        } else {
           
            $data['listpermohonan'] = $obj->where_related("trstspermohonan", 'id <> 1')->where("d_berlaku_izin < $today")->get();
            $obj = $this->permohonan;
            $data['jumlah'] = $obj->where_related("trstspermohonan", 'id <> 1')->where("d_berlaku_izin < $today")->count();
        }

        $obj->where_related('trperizinan/user','id',$this->session->userdata('id_auth'));
        $obj->where_in('trunitkerja_id',$this->__get_current_unitakses());

        $this->load->vars($data);

        $js = "
            $(document).ready(function() {
                oTable = $('#monitoring').dataTable({
                                \"bJQueryUI\": true,
                                    \"sPaginationType\": \"full_numbers\"
                        });
            });

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
        $this->session_info['page_name'] = "Monitoring Per Perizinan Belum/Sudah Jadi dan Kadaluarsa";
        $this->template->build('list_state', $this->session_info);
    }

    public function status() {
        $list_status = $this->input->post('list_status');
        $first_date = $this->input->post('first_date') == null ? $this->lib_date->set_date(date('Y-m-d'), -2) : $this->input->post('first_date');
        $second_date = $this->input->post('second_date') == null ? $this->lib_date->set_date(date('Y-m-d'), 0) : $this->input->post('second_date');
        $mark = $this->input->post('mark');
        $stspermohonan = new trstspermohonan();
        $data['list_status2'] = $list_status;
        $data['first_date'] = $first_date;
        $data['second_date'] = $second_date;
        $data['mark'] = $mark;

         $data['jumlah']=$this->monitoring_bi->get_total_perstatus($cari=NULL, $first_date, $second_date, $list_status);
        $data['list_status'] = $stspermohonan->order_by('id', 'ASC')->get();
        $this->load->vars($data);

        $js = "
            $(document).ready(function() {
                $('#listdata').dataTable
                ({
                    'bJQueryUI'      : true,
                    'sPaginationType': 'full_numbers',
                    'bServerSide'    : true,
                     'bSort': false,
                    'bAutoWidth'     : false,
                    'sAjaxSource'    : '" . base_url() . "monitoring/datatables/list_Monitoring_Per_Status',
                    'aoColumns'      :
                        [
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null
                    ],

                    'fnServerData': function(sSource, aoData, fnCallback)
                    {
                        aoData.push({ 'name': 'list_status', 'value': $('#selector option:selected').val() });
                        aoData.push({ 'name': 'first_date', 'value': $('#firstDateInput').val() });
                        aoData.push({ 'name': 'second_date', 'value': $('#secondDateInput').val() });
                        $.ajax
                        ({
                            'dataType': 'json',
                            'type'    : 'POST',
                            'url'     : sSource,
                            'data'    : aoData,
                            'success' : fnCallback
                        });
                    }
                });
            });

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
        $this->session_info['page_name'] = "Monitoring Per Status";
        $this->template->build('list_status', $this->session_info);
    }

    public function pemohon() {

        $nama = $this->input->post('nama');
        $first_date = $this->input->post('first_date') == null ? $this->lib_date->set_date(date('Y-m-d'), -2) : $this->input->post('first_date');
        $second_date = $this->input->post('second_date') == null ? $this->lib_date->set_date(date('Y-m-d'), 0) : $this->input->post('second_date');
        $stspermohonan = new trstspermohonan();
        $data['nama'] = $nama;
        $data['first_date'] = $first_date;
        $data['second_date'] = $second_date;
        $data['jumlah']=$this->monitoring_bi->get_total_pemohon($cari=NULL, $first_date, $second_date, $nama);
        $this->load->vars($data);

        $js = "
            $(document).ready(function() {
                $('#monitoring').dataTable
                ({
                    'bJQueryUI'      : true,
                    'sPaginationType': 'full_numbers',
                    'bServerSide'    : true,
                     'bSort': false,
                    'bAutoWidth'     : false,
                    'sAjaxSource'    : '" . base_url() . "monitoring/datatables/list_Monitoring_Per_Nama_Pemohon',
                    'aoColumns'      :
                        [
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null
                    ],

                    'fnServerData': function(sSource, aoData, fnCallback)
                    {
                        aoData.push({ 'name': 'nama', 'value': $('#nama').val() });
                        aoData.push({ 'name': 'first_date', 'value': $('#firstDateInput').val() });
                        aoData.push({ 'name': 'second_date', 'value': $('#secondDateInput').val() });
                        $.ajax
                        ({
                            'dataType': 'json',
                            'type'    : 'POST',
                            'url'     : sSource,
                            'data'    : aoData,
                            'success' : fnCallback
                        });
                    }
                });
            });

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
        $this->session_info['page_name'] = "Monitoring Per Nama Pemohon";
        $this->template->build('list_pemohon', $this->session_info);
    }

    public function perusahaan() {

        $nama_perusahaan = $this->input->post('nama_perusahaan');
        $first_date = $this->input->post('first_date') == null ? $this->lib_date->set_date(date('Y-m-d'), -2) : $this->input->post('first_date');
        $second_date = $this->input->post('second_date') == null ? $this->lib_date->set_date(date('Y-m-d'), 0) : $this->input->post('second_date');
        $stspermohonan = new trstspermohonan();
        $data['nama_perusahaan'] = $nama_perusahaan;
        $data['first_date'] = $first_date;
        $data['second_date'] = $second_date;
        $data['jumlah']=$this->monitoring_bi->get_total_perusahaan($cari=NULL, $first_date, $second_date, $nama_perusahaan);
        $this->load->vars($data);

        $js = "
            $(document).ready(function() {
                $('#monitoring').dataTable
                ({
                    'bJQueryUI'      : true,
                    'sPaginationType': 'full_numbers',
                    'bServerSide'    : true,
                     'bSort': false,
                    'bAutoWidth'     : false,
                    'sAjaxSource'    : '" . base_url() . "monitoring/datatables/list_Monitoring_Per_Nama_Perusahaan',
                    'aoColumns'      :
                        [
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null
                    ],

                    'fnServerData': function(sSource, aoData, fnCallback)
                    {
                        aoData.push({ 'name': 'nama_perusahaan', 'value': $('#nama_perusahaan').val() });
                        aoData.push({ 'name': 'first_date', 'value': $('#firstDateInput').val() });
                        aoData.push({ 'name': 'second_date', 'value': $('#secondDateInput').val() });
                        $.ajax
                        ({
                            'dataType': 'json',
                            'type'    : 'POST',
                            'url'     : sSource,
                            'data'    : aoData,
                            'success' : fnCallback
                        });
                    }
                });
            });

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
        $this->session_info['page_name'] = "Monitoring Per Nama Perusahaan";
        $this->template->build('list_perusahaan', $this->session_info);
    }

    public function pengambilan() {
        $jenis_izin = $this->input->post('jenis_izin');
        $first_date_taken = $this->input->post('first_date_taken') == null ? $this->lib_date->set_date(date('Y-m-d'), -2) : $this->input->post('first_date_taken');
        $second_date_taken = $this->input->post('second_date_taken') == null ? $this->lib_date->set_date(date('Y-m-d'), 0) : $this->input->post('second_date_taken');
        $mark = $this->input->post('mark');
        $stspermohonan = new trstspermohonan();
        $data['jenis_izin'] = $jenis_izin;
        $data['first_date_taken'] = $first_date_taken;
        $data['second_date_taken'] = $second_date_taken;
        $data['mark'] = $mark;
        $data['list_ijin'] = $this->perizinan->order_by('id', 'ASC')->get();

        $data['jumlah']=$this->monitoring_bi->get_total_Per_Bulan_Pengambilan_Izin($cari=NULL, $first_date_taken, $second_date_taken, $jenis_izin);
        
        $this->load->vars($data);

        $js = "
            $(document).ready(function() {
                $('#listdata').dataTable
                ({
                    'bJQueryUI'      : true,
                    'sPaginationType': 'full_numbers',
                    'bServerSide'    : true,
                    'bAutoWidth'     : false,
                     'bSort': false,
                    'sAjaxSource'    : '" . base_url() . "monitoring/datatables/list_Monitoring_Per_Bulan_Pengambilan_Izin',
                    'aoColumns'      :
                        [
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null,
                        null
                    ],

                    'fnServerData': function(sSource, aoData, fnCallback)
                    {
                        aoData.push({ 'name': 'jenis_izin', 'value': $('#selector option:selected').val() });
                        aoData.push({ 'name': 'first_date_taken', 'value': $('#firstDateInput').val() });
                        aoData.push({ 'name': 'second_date_taken', 'value': $('#secondDateInput').val() });
                        $.ajax
                        ({
                            'dataType': 'json',
                            'type'    : 'POST',
                            'url'     : sSource,
                            'data'    : aoData,
                            'success' : fnCallback
                        });
                    }
                });
            });

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
        $this->session_info['page_name'] = "Monitoring Per Bulan Pengambilan Izin";
        $this->template->build('list_ambilizin', $this->session_info);
    }

}

// This is the end of monitoring class
