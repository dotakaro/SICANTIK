<?php

/**
 * Description of Perusahaan
 *
 * @author agusnur
 * Created : 16 Aug 2010
 */
class Perusahaan extends WRC_AdminCont {

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

    public function __construct() {
        parent::__construct();
        $this->perusahaan = new tmperusahaan();
        $this->propinsi = new trpropinsi();
        $this->kabupaten = new trkabupaten();
        $this->kecamatan = new trkecamatan();
        $this->kelurahan = new trkelurahan();
        $this->kegiatan = new trkegiatan();
        $this->investasi = new trinvestasi();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if ($list_auth->id_role === '9') {
                $enabled = TRUE;
            }
        }
		
        if (!$enabled) {
            redirect('dashboard');
        }*/
    }

    function _funcwilayah() {
        $data['list_propinsi'] = $this->propinsi->order_by('n_propinsi', 'ASC')->get();
        $data['list_kabupaten'] = $this->kabupaten->order_by('n_kabupaten', 'ASC')->get();
//        $data['list_kecamatan'] = $this->kecamatan->order_by('n_kecamatan', 'ASC')->get();
//        $data['list_kelurahan'] = $this->kelurahan->order_by('n_kelurahan', 'ASC')->get();

        //Data Pendukung Perusahaan
        $data['list_kegiatan'] = $this->kegiatan->order_by('n_kegiatan', 'ASC')->get();
//        $data['list_investasi'] = $this->investasi->order_by('n_investasi', 'ASC')->get();
        $data['list_investasi'] = new stdClass();

        return $data;
    }

    public function index() {
        $data['list'] = $this->perusahaan->get();
        $this->load->vars($data);

        $js = "
                function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Data Perusahaan";
        $this->template->build('perusahaan_list', $this->session_info);
    }

    public function getDataTables() {
        $obj = new tmperusahaan();
        $obj->start_cache();
        $columns = array('n_perusahaan', 'npwp', 'a_perusahaan');
        $this->iTotalRecords = $obj->count();
        $this->sEcho = $this->input->post('sEcho');
        for ($i = 0; $i < 2; $i++) {
            /**
             * Filtering
             */
            if ($this->input->post('sSearch')) {
                foreach ($columns as $position => $column) {
                    if ($position == 0) {
                        $obj->like($column, $this->input->post('sSearch'));
                    } else {
                        $obj->or_like($column, $this->input->post('sSearch'));
                    }
                }
            }

            /**
             * Ordering
             */
//            if ($this->input->post("iSortCol_0") != null && $this->input->post("iSortCol_0") != "") {
//                for ($i = 0; $i < intval($this->input->post("iSortingCols")); $i++) {
//                    $obj->order_by($columns[intval($this->input->post("iSortCol_" . $i))], $this->input->post("sSortDir_" . $i));
//                }
//            }

            if ($i === 0) {
                $this->iTotalDisplayRecords = $obj->count();
            } else if ($i === 1) {
                if ($this->input->post("iDisplayStart") && $this->input->post("iDisplayLength") != "-1") {
                    $this->iDisplayStart = $this->input->post("iDisplayStart");
                    $this->iDisplayLength = $this->input->post("iDisplayLength");

                    $obj->limit($this->iDisplayLength, $this->iDisplayStart);
                } else {
                    $this->iDisplayLength = $this->input->post("iDisplayLength");

                    if (empty($this->iDisplayLength)) {
                        $this->iDisplayLength = 10;
                        $obj->limit($this->iDisplayLength);
                    }
                    else
                        $obj->limit($this->iDisplayLength);
                }
                $peru = new tmperusahaan;
                $a = $peru->select('n_perusahaan,npwp,a_perusahaan')->distinct()->get();
                $obj->stop_cache();
                echo $this->getDataTablesOutput($obj->get());
//                $obj->stop_cache();
//                echo $this->test($obj->get());
            }
        }
    }

    private function getDataTablesOutput($obj) {

        $aaData = array();

        $i = $this->iDisplayStart;

        foreach ($obj as $list) {
            $i++;

            /*
             * Adding new a column for taking data action
             */
            // ----------
            $action = NULL;
            $img_edit = array(
                'src' => base_url() . 'assets/images/icon/property.png',
                'alt' => 'Edit',
                'title' => 'Edit',
                'border' => '0',
            );
            $confirm_text = 'Apakah Anda yakin akan menghapusnya?';
            $img_delete = array(
                'src' => base_url() . 'assets/images/icon/minus.png',
                'alt' => 'Delete',
                'title' => 'Delete',
                'border' => '0',
                'onClick' => 'return confirm_link(\'' . $confirm_text . '\')',
            );
            $action .= anchor(site_url('perusahaan/edit') . '/' . $list->id, img($img_edit)) . "&nbsp;";
            $relasi = new tmpermohonan_tmperusahaan();
            $relasi->where('tmperusahaan_id', $list->id)->get();
            if (!$relasi->id)
                $action .= anchor(site_url('perusahaan/delete') . '/' . $list->id, img($img_delete)) . "&nbsp;";
            // ----------

            $aaData[] = array(
                $i,
                $list->n_perusahaan,
                $list->npwp,
                $list->a_perusahaan,
                $action
            );
        }

        $sOutput = array
            (
            "sEcho" => intval($this->sEcho),
            "iTotalRecords" => $this->iTotalRecords,
            "iTotalDisplayRecords" => $this->iTotalDisplayRecords,
            "aaData" => $aaData
        );

        return json_encode($sOutput);
    }

    /*
     * create is a method to show page for creating data
     */

    public function create() {
        $data = $this->_funcwilayah();
        $data['save_method'] = "save";
        $data['id_perusahaan'] = "";
        $data['nama_perusahaan'] = "";
        $data['reg_perusahaan'] = "";
        $data['npwp'] = "";
        $data['telp_perusahaan'] = "";
        $data['alamat_usaha'] = "";
        $data['propinsi_usaha'] = NULL;
        $data['kabupaten_usaha'] = NULL;
        $data['kecamatan_usaha'] = NULL;
        $data['kelurahan_usaha'] = NULL;
        $data['jenis_kegiatan'] = "ok";
        $data['jenis_investasi'] = "ok";

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
                });

     
                $(document).ready(function() {
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
                           header: 'Pilih Jenis Kegiatan',
                           noneSelectedText: 'Pilih Jenis Kegiatan',
                           selectedList: 1
                        }).multiselectfilter();

                        $('#jenis_investasi').multiselect({
                           show:'blind',
                           hide:'blind',
                           multiple: true,
                           header: 'Pilih Jenis Investasi',
                           noneSelectedText: 'Pilih Jenis Investasi',
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
                });

                function finishAjax(id, response){
                  $('#'+id).html(unescape(response));
                  $('#'+id).fadeIn();
                }
            ";

        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Data Perusahaan";
        $this->template->build('perusahaan_edit', $this->session_info);
    }

    /*
     * edit is a method to show page for updating data
     */

    public function edit($id_perusahaan = NULL) {
        $existingJenisKegiatan = array();
        $existingJenisInvestasi = array();

        $u_perusahaan = $this->perusahaan->get_by_id($id_perusahaan);
        $u_kelurahan = $this->perusahaan->trkelurahan->get();
        $u_kecamatan = $this->perusahaan->trkelurahan->trkecamatan->get();
        $u_kabupaten = $this->perusahaan->trkelurahan->trkecamatan->trkabupaten->get();
        $u_propinsi = $this->perusahaan->trkelurahan->trkecamatan->trkabupaten->trpropinsi->get();

        $u_kegiatan = $this->perusahaan->trkegiatan->get();
        $u_investasi = $this->perusahaan->trinvestasi->get();

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

        $data = $this->_funcwilayah();

        //Mengambil Jenis Investasi berdasarkan Jenis Kegiatan yang dipilih
        //list_investasi ini akan override list_investasi di _funcWilayah()
        $data['list_investasi'] = $this->investasi->where_in('trkegiatan_id',$existingJenisKegiatan)->order_by('n_investasi', 'ASC')->get();

        $data['save_method'] = "update";
        $data['id_perusahaan'] = $id_perusahaan;
        $data['nama_perusahaan'] = $u_perusahaan->n_perusahaan;
        $data['reg_perusahaan'] = $u_perusahaan->no_reg_perusahaan;
        $data['npwp'] = $u_perusahaan->npwp;
        $data['telp_perusahaan'] = $u_perusahaan->i_telp_perusahaan;
        $data['alamat_usaha'] = $u_perusahaan->a_perusahaan;
        $data['propinsi_usaha'] = $u_propinsi->id;
        $data['kabupaten_usaha'] = $u_kabupaten->id;
        $data['kecamatan_usaha'] = $u_kecamatan->id;
        $data['kelurahan_usaha'] = $u_kelurahan->id;
        $data['nama_kecamatan'] = $u_kecamatan->n_kecamatan;
        $data['nama_kelurahan'] = $u_kelurahan->n_kelurahan;
//        $data['jenis_kegiatan'] = $u_kegiatan->id;
        $data['jenis_kegiatan'] = $existingJenisKegiatan;
//        $data['jenis_investasi'] = $u_investasi->id;
        $data['jenis_investasi'] = $existingJenisInvestasi;

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
                });

                $(document).ready(function() {
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
//                           header: 'Pilih Jenis Kegiatan',
                           noneSelectedText: 'Pilih Jenis Kegiatan',
                           selectedList: 1,
                        }).multiselectfilter();

                        $('#jenis_investasi').multiselect({
                           show:'blind',
                           hide:'blind',
                           multiple: true,
//                           header: 'Pilih Jenis Investasi',
                           noneSelectedText: 'Pilih Jenis Investasi',
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
                });

                function finishAjax(id, response){
                  $('#'+id).html(unescape(response));
                  $('#'+id).fadeIn();
                }
            ";

        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Data Perusahaan";
        $this->template->build('perusahaan_edit', $this->session_info);
    }

    /*
     * Save and update for manipulating data.
     */

    public function save() {
        $arrKegiatanObj = array();

        $perusahaan = new tmperusahaan();
        $perusahaan->get_by_id($this->input->post('id_perusahaan'));
        $perusahaan->no_reg_perusahaan = $this->input->post('reg_perusahaan');
        $perusahaan->n_perusahaan = $this->input->post('nama_perusahaan');
        $perusahaan->npwp = $this->input->post('npwp');
        $perusahaan->i_telp_perusahaan = $this->input->post('telp_perusahaan');
        $perusahaan->a_perusahaan = $this->input->post('alamat_usaha');
        $kelurahan_u = new trkelurahan();
        $kelurahan_u->get_by_id($this->input->post('kelurahan_usaha'));

//        $kegiatan = new trkegiatan();
//        $kegiatan->get_by_id($this->input->post('jenis_kegiatan'));
        $jenisKegiatan = $this->input->post('jenis_kegiatan');

//        $investasi = new trinvestasi();
//        $investasi->get_by_id($this->input->post('jenis_investasi'));
        $jenisInvestasi = $this->input->post('jenis_investasi');

//        if (!$perusahaan->save(array($kelurahan_u, $kegiatan, $investasi))) {
        if (!$perusahaan->save(array($kelurahan_u))) {
            echo '<p>' . $perusahaan->error->string . '</p>';
        } else {

            //Fixed by Indra - Save setiap Kegiatan
            if(is_array($jenisKegiatan) && !empty($jenisKegiatan)){
                foreach($jenisKegiatan as $keyKegiatan=>$kegiatanId){
                    $perusahaan_kegiatan = new tmperusahaan_trkegiatan();
                    $perusahaan_kegiatan->tmperusahaan_id = $perusahaan->id;
                    $perusahaan_kegiatan->trkegiatan_id = $kegiatanId;
                    $perusahaan_kegiatan->save();
                }
            }

            //Fixed by Indra - Save setiap investasi
            if(is_array($jenisInvestasi) && !empty($jenisInvestasi)){
                foreach($jenisInvestasi as $keyInvestasi=>$investasiId){
                    $perusahaan_investasi = new tmperusahaan_trinvestasi();
                    $perusahaan_investasi->tmperusahaan_id = $perusahaan->id;
                    $perusahaan_investasi->trinvestasi_id = $investasiId;
                    $perusahaan_investasi->save();
                }
            }

            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $g = $this->sql($u_ser);
//         $jam = date("H:i:s A");
            $p = $this->db->query("call log ('Perusahaan','Input data perusahaan','" . $tgl . "','" . $u_ser . "')");

            redirect('perusahaan');
        }
    }

    public function update() {
        $perusahaan = new tmperusahaan();
        $perusahaan->get_by_id($this->input->post('id_perusahaan'));
        $perusahaan->n_perusahaan = $this->input->post('nama_perusahaan');
        $perusahaan->no_reg_perusahaan = $this->input->post('reg_perusahaan');
        $perusahaan->npwp = $this->input->post('npwp');
        $perusahaan->i_telp_perusahaan = $this->input->post('telp_perusahaan');
        $perusahaan->a_perusahaan = $this->input->post('alamat_usaha');
        $perusahaan->trkelurahan->get();
        $perusahaan->save();

        if($perusahaan->trkelurahan->id){//Fixed by Indra - Jika ada data kelurahannya, update
            $perusahaan_lurah = new tmperusahaan_trkelurahan();
            $perusahaan_lurah->where('tmperusahaan_id', $this->input->post('id_perusahaan'))
                ->update(array('trkelurahan_id' => $this->input->post('kelurahan_usaha')));
        }else{
            $perusahaan_lurah = new tmperusahaan_trkelurahan();
            $perusahaan_lurah->tmperusahaan_id = $this->input->post('id_perusahaan');
            $perusahaan_lurah->trkelurahan_id = $this->input->post('kelurahan_usaha');
            $perusahaan_lurah->save();
        }

        $perusahaan_kegiatan = new tmperusahaan_trkegiatan();
        $jenisKegiatan = $this->input->post('jenis_kegiatan');
        $jenisInvestasi = $this->input->post('jenis_investasi');
        $idPerusahaan = $this->input->post('id_perusahaan');

        //Added by Indra - Jika ada data sebelumnya, delete
        if($perusahaan->trkegiatan->id){
            $perusahaan_kegiatan->where('tmperusahaan_id', $idPerusahaan)->get()->delete_all();
//            $perusahaan_kegiatan = new tmperusahaan_trkegiatan();
//            $perusahaan_kegiatan->where('tmperusahaan_id', $this->input->post('id_perusahaan'))
//                ->update(array('trkegiatan_id' => $this->input->post('jenis_kegiatan')));
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
//            $perusahaan_investasi->where('tmperusahaan_id', $this->input->post('id_perusahaan'))
//                    ->update(array('trinvestasi_id' => $this->input->post('jenis_investasi')));
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

        $update = $perusahaan->save();
        if ($update) {
            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $g = $this->sql($u_ser);
//      $jam = date("H:i:s A");
            $p = $this->db->query("call log ('Perusahaan','Edit data perusahaan','" . $tgl . "','" . $u_ser . "')");

            redirect('perusahaan');
        }
    }

    public function delete($uid = NULL) {
        $kelurahan = new tmperusahaan_trkelurahan();
        $kelurahan->where('tmperusahaan_id', $uid);
        $kelurahan->delete();
        $investasi = new tmperusahaan_trinvestasi();
        $investasi->where('tmperusahaan_id', $uid);
        $investasi->delete();
        $kegiatan = new tmperusahaan_trkegiatan();
        $kegiatan->where('tmperusahaan_id', $uid);
        $kegiatan->delete();

        $perusahaan = new tmperusahaan();
        $perusahaan->get_by_id($uid);
        $perusahaan->delete();

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $g = $this->sql($u_ser);
//      $jam = date("H:i:s A");
        $p = $this->db->query("call log ('Perusahaan','Hapus data perusahaan','" . $tgl . "','" . $u_ser . "')");

        redirect('perusahaan');
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

    //Fungsi untuk mencari id pemohon apakah sudah ada atau tidak
    public function npwp_exist($npwp) {
        //$this->db->debug=1;
        $this->db->where('npwp', $npwp);
        $query = $this->db->get('tmperusahaan');
        if ($query->num_rows() > 0) {
            echo "1";
            }
        else
        {
            echo "0";
        }
        }

    /**
     * Fungsi untuk mengecek apakah NPWP sudah pernah digunakan
     */
    public function register_npwp_exist(){
        $npwp = mysql_real_escape_string($_POST['npwp']);
        
        $sql = "SELECT npwp FROM tmperusahaan WHERE npwp = '$npwp'";
        $hasil = $this->db->query($sql);
        $result = $hasil->row();
        if($result) {
            $output = false;
        } else {
            $output = true;
        }
        
        echo json_encode($output);
    }

}
