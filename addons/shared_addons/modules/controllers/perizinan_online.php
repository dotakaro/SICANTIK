<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Perizinan Online
 *
 * @author      Indra
 * @website     http://indra.com
 * @package     
 * @subpackage  
 * @copyright   MIT
 */
class perizinan_online extends Public_Controller
{

    /**
     * The constructor
     * @access public
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
      $this->lang->load('perizinan_online');
      $this->load->model('perizinan_online_m');
      $this->load->library('form_validation');
      $this->lang->load(array('perizinan_online'));
      $this->template->append_css('module::perizinan_online.css');
      
      // Set the validation rules
        $this->item_validation_rules = array(
            array(
                'field' => 'id_pemohon',
                'label' => 'Id_pemohon',
                'rules' => 'required|trim|xss_clean',
            ),
            array(
                'field' => 'jenis_identitas',
                'label' => 'lang:perizinan_online:jenis_identitas',
                'rules' => 'required',
            ),
            array(
                'field' => 'nama_pemohon',
                'label' => 'lang:perizinan_online:nama_pemohon',
                'rules' => 'required',
            ),
            array(
                    'field' => 'telp_pemohon',
                    'label' => 'lang:perizinan_online:telp_pemohon',
                    'rules' => 'required|trim|xss_clean',
            ),
            array(
                    'field' => 'alamat_pemohon',
                    'label' => 'lang:perizinan_online:alamat_pemohon',
                    'rules' => 'required|trim|xss_clean',
            ),
            array(
                    'field' => 'provinsi_pemohon',
                    'label' => 'lang:perizinan_online:provinsi_pemohon',
                    'rules' => 'required',
            ),
            array(
                    'field' => 'kabupaten_pemohon',
                    'label' => 'lang:perizinan_online:kabupaten_pemohon',
                    'rules' => 'required',
            ),
            array(
                    'field' => 'kecamatan_pemohon',
                    'label' => 'lang:perizinan_online:kecamatan_pemohon',
                    'rules' => 'required',
            ),
            array(
                    'field' => 'kelurahan_pemohon',
                    'label' => 'lang:perizinan_online:kelurahan_pemohon',
                    'rules' => 'required',
            ),
            array(
                    'field' => 'npwp_perusahaan',
                    'label' => 'lang:perizinan_online:npwp_perusahaan',
                    'rules' => 'trim|xss_clean',
            ),
            array(
                    'field' => 'no_register_perusahaan',
                    'label' => 'lang:perizinan_online:no_register_perusahaan',
                    'rules' => 'trim|xss_clean',
            ),
            array(
                    'field' => 'nama_perusahaan',
                    'label' => 'lang:perizinan_online:nama_perusahaan',
                    'rules' => 'trim|xss_clean',
            ),
            array(
                    'field' => 'alamat_perusahaan',
                    'label' => 'lang:perizinan_online:alamat_perusahaan',
                    'rules' => 'trim|xss_clean',
            ),
            array(
                    'field' => 'telepon_perusahaan',
                    'label' => 'lang:perizinan_online:telepon_perusahaan',
                    'rules' => 'trim|xss_clean',
            ),
            array(
                    'field' => 'provinsi_perusahaan',
                    'label' => 'lang:perizinan_online:provinsi_perusahaan',
//                    'rules' => 'required',
            ),
            array(
                    'field' => 'kabupaten_perusahaan',
                    'label' => 'lang:perizinan_online:kabupaten_perusahaan',
//                    'rules' => 'required',
            ),
            array(
                    'field' => 'kecamatan_perusahaan',
                    'label' => 'lang:perizinan_online:kecamatan_perusahaan',
//                    'rules' => 'required',
            ),
            array(
                    'field' => 'kelurahan_perusahaan',
                    'label' => 'lang:perizinan_online:kelurahan_perusahaan',
//                    'rules' => 'required',
            ),
            array(
                    'field' => 'lampiran',
                    'label' => 'lang:perizinan_online:lampiran',
                    'rules' => '',
            ),
            array(
                    'field' => 'jenis_izin',
                    'label' => 'lang:perizinan_online:jenis_izin',
                    'rules' => 'required',
            )
        );
    }
     /**
     * List all perizinan_onlines
     *
     *
     * @access  public
     * @return  void
     */
     /*public function index()
     {
      // bind the information to a key
      $data['perizinan_online'] = (array)$this->perizinan_online_m->get_all();
      // Build the page
      $this->template->title($this->module_details['name'])
      ->build('index', $data);
    }*/
    
    public function index()
    {
		$perizinan_online = new StdClass();
		// $folder = $this->file_folders_m->get_by('name', 'perizinan_online');
		// $this->data->files = Files::folder_contents($folder->id);

        if(!empty($_POST)){
            // Set the validation rules from the array above
            $this->form_validation->set_rules($this->item_validation_rules);

            // check if the form validation passed
            if($this->form_validation->run())
            {
                $curl_success = $this->post_to_backoffice();
                if($curl_success){
                    // See if the model can create the record
                    if($this->perizinan_online_m->create($this->input->post()))
                    {
                        // All good...
                        $this->session->set_flashdata('success', lang('perizinan_online.register_success'));
                        redirect('perizinan_online/register_success');
                    }
                    // Something went wrong. Show them an error
                    else
                    {
                        $this->session->set_flashdata('error', lang('perizinan_online.register_failed'));
                        redirect('perizinan_online/create');
                    }
                }else{
                        $this->session->set_flashdata('error', lang('perizinan_online.register_failed'));
                        redirect('perizinan_online/create');
                }
            }else{
                exit(json_encode(array('status' => false, 'message' => validation_errors())));
            }

            $perizinan_online->data = new StdClass();
            foreach ($this->item_validation_rules AS $rule)
            {
                $perizinan_online->data->{$rule['field']} = $this->input->post($rule['field']);
            }
        }
		$this->_form_data();
		// Build the view using sample/views/admin/form.php
		$this->template->title($this->module_details['name'], lang('perizinan_online.new_item'))
				->set_breadcrumb( lang('perizinan_online:layanan_online'), '#')  
				->set_breadcrumb( lang('perizinan_online:pendaftaran_online'))
				->build('form', $perizinan_online->data);
    }
    
	public function konfirmasi_noizin(){
		$this->load->library('curl');
        $this->load->library('xml_parsing_win');
        
        $base_url_websevices = Settings::get('perizinan_online_webservice');
		$no_izin = "";
		if(isset($_GET['noizin'])) {
			// id index exists
			$no_izin = $this->input->get('noizin');
		} else {
			$no_izin =$this->input->get('no_izin');
		}
        
        //Cari ke backoffice untuk
        $url = $this->curl->simple_get("$base_url_websevices/api/cekperizinan/no_izin/$no_izin");

        $news_items = $this->xml_parsing_win->element_set('item', $url);

        if ($news_items == NULL) {
            $item_array = array();
        } else {
            foreach ($news_items as $item) {
                $no_izin = $this->xml_parsing_win->value_in('no_surat', $item);
                $nama_pemohon = $this->xml_parsing_win->value_in('n_pemohon', $item);
                $alamat_pemohon = $this->xml_parsing_win->value_in('a_pemohon', $item);
                $telp_pemohon = $this->xml_parsing_win->value_in('telp_pemohon', $item);
                $nama_perizinan = $this->xml_parsing_win->value_in('n_perizinan', $item);
                $berlaku_izin = $this->xml_parsing_win->value_in('d_berlaku_izin', $item);
				$tanggal_surat = $this->xml_parsing_win->value_in('tgl_surat', $item);
				$n_perusahaan = $this->xml_parsing_win->value_in('n_perusahaan', $item);
				$npwp = $this->xml_parsing_win->value_in('npwp', $item);
				$a_perusahaan = $this->xml_parsing_win->value_in('a_perusahaan', $item);
				$i_telp_perusahaan= $this->xml_parsing_win->value_in('i_telp_perusahaan', $item);
				
				
                
                $item_array[] = array(
                    'no_izin' => $no_izin,
                    'nama_pemohon' => $nama_pemohon,
                    'alamat_pemohon' => $alamat_pemohon,
                    'telp_pemohon' => $telp_pemohon,
                    'nama_perizinan' => $nama_perizinan,
					'd_berlaku_izin' => $berlaku_izin,
					'tgl_surat' => $tanggal_surat,
					'n_perusahaan' => $n_perusahaan,
					'npwp' => $npwp,
					'a_perusahaan' => $a_perusahaan,
					'i_telp_perusahaan' => $i_telp_perusahaan
                );
            }
        }
        //echo "<pre>";print_r($item_array);exit();
        $data['list'] = $item_array;
        $this->template->no_pendaftaran = $no_izin;
        
        // Build the view using sample/views/admin/form.php
        $this->template->title($this->module_details['name'], lang('perizinan_online.success'))
            ->set_breadcrumb( lang('perizinan_online:layanan_online'), '#')  
            ->set_breadcrumb( lang('perizinan_online:pendaftaran_online'), 'pendaftaran_online')
            ->set_breadcrumb( lang('perizinan_online:tracking_perizinan'))    
            ->build('konfirmasi_view', $data);
    }
	
	
    public function tracking_perizinan(){
        $this->load->library('curl');
        $this->load->library('xml_parsing_win');
        
        $base_url_websevices = Settings::get('perizinan_online_webservice');
        $no_pendaftaran = $this->input->get('no_pendaftaran');
        
        //Cari ke backoffice untuk
        $url = $this->curl->simple_get("$base_url_websevices/api/permohonan2/pendaftaran/$no_pendaftaran");

        $news_items = $this->xml_parsing_win->element_set('item', $url);

        if ($news_items == NULL) {
            $item_array = array();
        } else {
            foreach ($news_items as $item) {
                $no_pendaftaran = $this->xml_parsing_win->value_in('pendaftaran_id', $item);
                $nama_pemohon = $this->xml_parsing_win->value_in('n_pemohon', $item);
                $alamat_pemohon = $this->xml_parsing_win->value_in('a_pemohon', $item);
                $telp_pemohon = $this->xml_parsing_win->value_in('telp_pemohon', $item);
                $sts_permohonan = $this->xml_parsing_win->value_in('n_sts_permohonan', $item);
                $nama_perizinan = $this->xml_parsing_win->value_in('n_perizinan', $item);
                $current = $this->xml_parsing_win->value_in('current',$item);
                
                $item_array[] = array(
                    'no_pendaftaran' => $no_pendaftaran,
                    'nama_pemohon' => $nama_pemohon,
                    'alamat_pemohon' => $alamat_pemohon,
                    'telp_pemohon' => $telp_pemohon,
                    'sts_permohonan' => $sts_permohonan,
                    'nama_perizinan' => $nama_perizinan,
                    'current' =>$current
                );
            }
        }
        //echo "<pre>";print_r($item_array);exit();
        $data['list'] = $item_array;
        $this->template->no_pendaftaran = $no_pendaftaran;
        
        // Build the view using sample/views/admin/form.php
        $this->template->title($this->module_details['name'], lang('perizinan_online.success'))
            ->set_breadcrumb( lang('perizinan_online:layanan_online'), '#')  
            ->set_breadcrumb( lang('perizinan_online:pendaftaran_online'), 'pendaftaran_online')
            ->set_breadcrumb( lang('perizinan_online:tracking_perizinan'))    
            ->build('view_tracking', $data);
    }
	
	public function verifikasi_izin(){
		$this->load->library('curl');
        $this->load->library('xml_parsing_win');
        
        $base_url_websevices = Settings::get('perizinan_online_webservice');
        $no_izin = base64_encode($this->input->post('no_izin'));
        
        //Cari ke backoffice untuk
        $url = $this->curl->simple_get("$base_url_websevices/api/cekperizinanizin/no_izin/$no_izin");

        $news_items = $this->xml_parsing_win->element_set('item', $url);

        if ($news_items == NULL) {
            $item_array = array();
        } else {
            foreach ($news_items as $item) {
				$no_izins = base64_decode($no_izin);
                $no_izins = $this->xml_parsing_win->value_in('no_surat', $item);
                $nama_pemohon = $this->xml_parsing_win->value_in('n_pemohon', $item);
                $alamat_pemohon = $this->xml_parsing_win->value_in('a_pemohon', $item);
                $telp_pemohon = $this->xml_parsing_win->value_in('telp_pemohon', $item);
                $nama_perizinan = $this->xml_parsing_win->value_in('n_perizinan', $item);
                $berlaku_izin = $this->xml_parsing_win->value_in('d_berlaku_izin', $item);
				$tanggal_surat = $this->xml_parsing_win->value_in('tgl_surat', $item);
				$n_perusahaan = $this->xml_parsing_win->value_in('n_perusahaan', $item);
				$npwp = $this->xml_parsing_win->value_in('npwp', $item);
				$a_perusahaan = $this->xml_parsing_win->value_in('a_perusahaan', $item);
				$i_telp_perusahaan= $this->xml_parsing_win->value_in('i_telp_perusahaan', $item);
				
				
                
                $item_array[] = array(
                    'no_izin' => $no_izins,
                    'nama_pemohon' => $nama_pemohon,
                    'alamat_pemohon' => $alamat_pemohon,
                    'telp_pemohon' => $telp_pemohon,
                    'nama_perizinan' => $nama_perizinan,
					'd_berlaku_izin' => $berlaku_izin,
					'tgl_surat' => $tanggal_surat,
					'n_perusahaan' => $n_perusahaan,
					'npwp' => $npwp,
					'a_perusahaan' => $a_perusahaan,
					'i_telp_perusahaan' => $i_telp_perusahaan
                );
            }
        }
        //echo "<pre>";print_r($item_array);exit();
        $data['list'] = $item_array;
        $this->template->no_pendaftaran = $no_izin;
        
        // Build the view using sample/views/admin/form.php
        $this->template->title($this->module_details['name'], lang('perizinan_online.success'))
            ->set_breadcrumb( lang('perizinan_online:layanan_online'), '#')  
            ->set_breadcrumb( lang('perizinan_online:pendaftaran_online'), 'pendaftaran_online')
            ->set_breadcrumb( lang('perizinan_online:tracking_perizinan'))    
            ->build('konfirmasi_view', $data);
    }
    
    public function register_success(){
        $id_uniq = 0;
        $no_pendaftaran = '';
        $nama_pemohon = '';
        $nama_perizinan = '';
        
        $id_uniq = $this->session->userdata('no_uniq');
        /*if(!$id_uniq){
            redirect('perizinan_online');
        }*/
        $data = $this->db->where("urut",$id_uniq)->get('default_perizinan_online')->result_array();
        if(!empty($data)){
            $no_pendaftaran = $data[0]['no_pendaftaran'];
            $nama_pemohon =  $data[0]['nama_pemohon'];
            $nama_perizinan = $data[0]['nama_perizinan'];
        }

        $this->template->no_pendaftaran = $no_pendaftaran;
        $this->template->nama_pemohon = $nama_pemohon;
        $this->template->nama_perizinan = $nama_perizinan;
        
        // Build the view using sample/views/admin/form.php
        $this->template->title($this->module_details['name'], lang('perizinan_online.register_success'))
                        ->build('register_success', $data);
    }

    /**
     * Fungsi untuk mempersiapkan variable2 pendukung sebelum render ke User
     */
    private function _form_data()
	{
        ### Ambil data dari webservice CURL Backoffice##
        $this->load->library('curl');
        $this->load->library('xml_parsing_win');

        $base_url_websevices = Settings::get('perizinan_online_webservice');

        //List Perizinan
        $url = $this->curl->simple_get("$base_url_websevices/api/jenisperizinanlist");
        $news_items = $this->xml_parsing_win->element_set('item', $url);
        $item_array = array();
        $item_array[null] = "Pilih Izin :";
        foreach ($news_items as $item) {
            $id = $this->xml_parsing_win->value_in('id', $item);
            $jenis = $this->xml_parsing_win->value_in('jenis_perizinan', $item);

            $item_array[$id] = $jenis;
        }
        //echo "<pre>";print_r($item_array);exit();
        $this->template->list_izin =  $item_array;

        //List Provinsi
        $url_my_prop = $this->curl->simple_get("$base_url_websevices/api/propinsi");
        $dt_prop = $this->xml_parsing_win->element_set('item', $url_my_prop);
        $list_prop = array();
        $list_prop[null] = "Pilih Provinsi :";
        foreach ($dt_prop as $item2) {
            $id_prop = $this->xml_parsing_win->value_in('id', $item2);
            $nama_propinsi = $this->xml_parsing_win->value_in('nama_propinsi', $item2);

            $list_prop[$id_prop] = $nama_propinsi;
        }
        $this->template->list_provinsi = $list_prop;

        //List Kabupaten
        $url_my_kab = $this->curl->simple_get("$base_url_websevices/api/kabupaten/id_prop/3"); //3 =  Propinsi Sumatera Barat
        $dt_kab = $this->xml_parsing_win->element_set('item', $url_my_kab);
        $list_kab = array();
        $list_kab[null] = "Pilih Kabupaten :";
        foreach ($dt_kab as $item2) {
            $id_kab = $this->xml_parsing_win->value_in('id', $item2);
            $nama_kabupaten = $this->xml_parsing_win->value_in('nama_kabupaten', $item2);

            $list_kab[$id_kab] = $nama_kabupaten;
        }
        $this->template->list_kabupaten = $list_kab;

        //List Kecamatan
        $url_my_kec = $this->curl->simple_get("$base_url_websevices/api/kecamatan/id_kab/43"); //43 =  Kabupaten Agam
        $dt_kec = $this->xml_parsing_win->element_set('item', $url_my_kec);
        $list_kec = array();
        $list_kec[null] = "Pilih Kecamatan :";
        foreach ($dt_kec as $item2) {
            $id_kec = $this->xml_parsing_win->value_in('id', $item2);
            $nama_kecamatan = $this->xml_parsing_win->value_in('nama_kecamatan', $item2);

            $list_kec[$id_kec] = $nama_kecamatan;
        }
        $this->template->list_kecamatan = $list_kec;

        //List Unit Kerja
        $this->template->list_unit_kerja = array(
            null=>'Pilih Unit Kerja :'
        );

        /*$this->template->list_kabupaten = array(
          'Pilih Kabupaten :'
        );*/

        /*$this->template->list_kecamatan = array(
          'Pilih Kecamatan :'
        );*/

        $this->template->list_kelurahan = array(
            null=>'Pilih Kelurahan :'
        );
        ################################################

        $this->template->list_jenis_identitas = array(
            null=>'Pilih Identitas :',
            'KTP'=>'KTP',
            'SIM'=>'SIM',
            'PASSPORT'=>'PASSPORT'
        );
            
		// $this->load->model('pages/page_m');
		// $this->template->pages = array_for_select($this->page_m->get_page_tree(), 'id', 'title');
	}

    function list_daerah($list, $id) {
        $data['jenis_list'] = $list;
        $this->load->library('curl');
        $this->load->library('xml_parsing_win');

        $base_url_websevices = Settings::get('perizinan_online_webservice');

        if ($list == 1 || $list == 10) {
            $url = $this->curl->simple_get("$base_url_websevices/api/kabupaten/id_prop/$id");
            $news_items = $this->xml_parsing_win->element_set('item', $url);
            foreach ($news_items as $item) {
                $id = $this->xml_parsing_win->value_in('id', $item);
                $nama = $this->xml_parsing_win->value_in('nama_kabupaten', $item);

                $item_array[] = array(
                    'id' => $id,
                    'n_kabupaten' => $nama,
                );
            }
            $data['list'] = $item_array;
            //$data['list'] = $this->tr_kabupaten->get_result($id);
        } elseif ($list == 2 || $list == 20) {
            $url = $this->curl->simple_get("$base_url_websevices/api/kecamatan/id_kab/$id");
            $news_items = $this->xml_parsing_win->element_set('item', $url);
            foreach ($news_items as $item) {
                $id = $this->xml_parsing_win->value_in('id', $item);
                $nama = $this->xml_parsing_win->value_in('nama_kecamatan', $item);

                $item_array[] = array(
                    'id' => $id,
                    'nama' => $nama,
                );
            }
            $data['list'] = $item_array;

            //$data['list'] = $this->tr_kecamatan->get_result($id);
        } elseif ($list == 3 || $list == 30) {
            $url = $this->curl->simple_get("$base_url_websevices/api/kelurahan/id_kec//$id");
            $news_items = $this->xml_parsing_win->element_set('item', $url);
            foreach ($news_items as $item) {
                $id = $this->xml_parsing_win->value_in('id', $item);
                $nama = $this->xml_parsing_win->value_in('nama_kelurahan', $item);

                $item_array[] = array(
                    'id' => $id,
                    'nama' => $nama,
                );
            }
            $data['list'] = $item_array;

            //$data['list'] = $this->tr_keluarahan->get_result($id);
        }
        $this->load->view('list_daerah_web_services', $data);
        //$this->load->view('list_daerah', $data);
    }
        
    private function post_to_backoffice(){
        $base_url_websevices = Settings::get('perizinan_online_webservice');
        //$max_file_upload = 1000;
        $this->load->library('curl');
        $this->load->library('xml_parsing_win');
        
        //$uploads = $_FILES['lampiran']['name'];
        //if (!empty($uploads)) {
            /*$config = array();
            $config['overwrite'] = FALSE;
            $config['upload_path'] = realpath('./uploads/default/perizinan_online');
            $config['allowed_types'] = 'pdf|png|jpg|jpeg';
            $config['max_size'] = $max_file_upload;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $field = 'lampiran';
            if (!$this->upload->do_upload($field)) {
                $x = array('<p>', '</p>');
                $error = str_replace($x, '', $this->upload->display_errors());
                $data['error'] = $error;
                return false;
            } else {*/
                $no_urut = $this->get_latest_urut();
                $no_urut = $no_urut->urut;
                if ($no_urut != NULL) {
                    $no_uniq = $no_urut + 1;
                } else {
                    $no_uniq = 1;
                }
                //$upload_data = $this->upload->data();
                //$uploaded_filename  = $upload_data['file_name'];
                
                $data2 = array();
                $data2['izin']= $this->input->post('jenis_izin');
                $data2['urut'] = $no_uniq;
                $data2['referensi'] = $this->input->post('id_pemohon');
                $data2['cmbsource'] = $this->input->post('jenis_identitas');
                $data2['namaPemohon'] = $this->input->post('nama_pemohon');
                $data2['telpPemohon'] = $this->input->post('telp_pemohon');
                $data2['almtPemohon'] = $this->input->post('alamat_pemohon');
                $data2['propinsi1'] = $this->input->post('provinsi_pemohon');
                $data2['kabupaten1'] = $this->input->post('kabupaten_pemohon');
                $data2['kecamatan1'] = $this->input->post('kecamatan_pemohon');
                $data2['kelurahan1'] = $this->input->post('kelurahan_pemohon');
                $data2['npwpPerusahaan'] = $this->input->post('npwp_perusahaan');
                $data2['regPerusahaan'] = $this->input->post('no_register_perusahaan');
                $data2['namaPerusahaan'] = $this->input->post('nama_perusahaan');
                $data2['almtPerusahaan'] = $this->input->post('alamat_perusahaan');
                $data2['telpPerusahaan'] = $this->input->post('telepon_perusahaan');
                $data2['tglPermohonan'] = $this->input->post('tglPermohonan');
                $data2['propinsi2'] = $this->input->post('provinsi_perusahaan');
                $data2['kabupaten2'] = $this->input->post('kabupaten_perusahaan');
                $data2['kecamatan2'] = $this->input->post('kecamatan_perusahaan');
                $data2['kelurahan2'] = $this->input->post('kelurahan_perusahaan');
                $data2['unit_kerja_id'] = $this->input->post('unit_kerja_id');
                //$data2['lampiran'] = $uploaded_filename;
                $this->session->set_userdata('no_uniq', $no_uniq);
                
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, "$base_url_websevices/api/pendaftaran/");
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "jenis_izin_id=" . $data2['izin'] .
                        "&alamat_pemohon=" . $data2['almtPemohon'] .
                        "&no_refer=" . $data2['referensi'] .
                        "&nama_pemohon=" . $this->db->escape_str($data2['namaPemohon']) .
                        "&nama_perusahaan=" . $this->db->escape_str($data2['namaPerusahaan']) .
                        "&npwp=" . $this->db->escape_str($data2['npwpPerusahaan']) .
                        "&alamat_usaha=" . $data2['almtPerusahaan'] .
                        "&no_telp=" . $data2['telpPemohon'] .
                        "&jenis_permohonan_id=1" .
                        "&file=" . $data2['lampiran'] .
                        "&kelurahan_pemohon=" . $data2['kelurahan1']
                        . "&kelurahan_usaha=" . $data2['kelurahan2'] .
                        "&telpPerusahaan=" . $data2['telpPerusahaan'] .
                        "&no_registrasi=" . $data2['regPerusahaan'] .
                        "&cmbsource=" . $data2['cmbsource'] .
                        "&unit_kerja_id=".$data2['unit_kerja_id']
                );
                $_POST['urut'] = $no_uniq;
                //$_POST['lampiran'] = $uploaded_filename;
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                $result = curl_exec($ch);
                $xml = $this->xml_parsing_win->element_set('xml', $result);
                foreach ($xml as $item) {
                    $noPendaf = $this->xml_parsing_win->value_in('no_pendaftaran', $item);
                    $_POST['no_pendaftaran'] = $noPendaf;
                }
                curl_close($ch);
                return true;
            //}
        //}
        
    }
    
    private function get_latest_urut(){
        $sql="SELECT MAX(urut) as urut FROM default_perizinan_online";
        $query=$this->db->query($sql);
        return $query->row();
    }


    /**
     * Fungsi untuk mendapatkan item retribusi untuk digunakan oleh widget Simulasi Tarif
     */
    public function get_item_retribusi(){
        //$formula_retribusi = "formula_total = 0;\n";

        ### Ambil data dari webservice CURL Backoffice##
        $this->load->library('curl');
        $this->load->library('xml_parsing_win');

        $base_url_websevices = Settings::get('perizinan_online_webservice');
        $trperizinan_id = $this->input->post('trperizinan_id');

        //List Perizinan
        $url = $this->curl->simple_get("$base_url_websevices/api/itemretribusi/trperizinan_id/{$trperizinan_id}.json");
        $data['list_item'] = json_decode($url);

        $xml_formula = $this->curl->simple_get("$base_url_websevices/api/formularetribusi/trperizinan_id/{$trperizinan_id}.xml");
        $formula_items = $this->xml_parsing_win->element_set('item', $xml_formula);

        foreach($formula_items as $formula){
            $formula_retribusi = $this->xml_parsing_win->value_in('formula', $formula);
        }

        $data['formula_retribusi'] = $formula_retribusi;

        $this->load->view('form_simulasi',$data);
    }

    function get_capcha() {
        $capcha = $this->captcha();
        $data['img'] = $capcha['img'];
        $data['word'] = $capcha['word'];
        $this->load->view('isi_capcha', $data);
    }

    function get_list_unit($trperizinanId){
        $listUnitJson = array();
        ### Ambil data dari webservice Backoffice##
        $this->load->library('curl');
        $base_url_websevices = Settings::get('perizinan_online_webservice');

        if($trperizinanId){
            $listUnitJson = $this->curl->simple_get("$base_url_websevices/api/list_unit/trperizinan_id/{$trperizinanId}.json");
        }
        echo $listUnitJson;exit();
    }

    function captcha() {
        $this->load->helper('captcha');
        $str = 'ABCDEFGHJKLMNOPQRSTUVWXYZ';
        $random_word = str_shuffle($str);
        $random_word = substr($random_word, 0, 5);
        $vals = array(
            'word' => $random_word,
            'img_path' => 'captcha/',
            'img_url' => base_url() . '/captcha/',
            'img_width' => '200',
            'img_height' => 50,
            'expiration' => 7200
        );

        $cap = create_captcha($vals);


        $data = array(
            'captcha_time' => $cap['time'],
            'ip_address' => $this->input->ip_address(),
            'word' => $cap['word']
        );
//        $query = $this->db->insert_string('captcha', $data);
//        $this->db->query($query);


        $cap_conf = array(
            'img' => $cap['image'],
            'word' => $cap['word']
        );
        return $cap_conf;
    }

    public function daftar_proses($offset=0){
        $limit = 20;
        // bind the information to a key
        $query_data = (array)$this->perizinan_online_m->get_all_proses($limit,$offset);

        $data['daftar_permohonan'] = $query_data['rows'];
        $data['num_results'] = $query_data['num_rows'];

        //pagination
        $this->load->library('pagination');
        $config=array();
        $config['base_url']=site_url('perizinan_online/daftar_proses');
        $config['total_rows']= $data['num_results'];
        $config['per_page']=$limit;
        $config['uri_segment']=3;//mengeset tempat offset pada setiap link yang digenerate
        $this->pagination->initialize($config);
        $data['pagination']=$this->pagination->create_links();

        // Build the page
        $this->template->title($this->module_details['name'])
            ->build('daftar_proses', $data);
    }

    public function daftar_terbit($offset=0){
        $limit = 20;
        // bind the information to a key
        $query_data = (array)$this->perizinan_online_m->get_all_proses_terbit($limit,$offset);
		

        $data['daftar_permohonan'] = $query_data['rows'];
        $data['num_results'] = $query_data['num_rows'];
		

        //pagination
        $this->load->library('pagination');
        $config=array();
        $config['base_url']=site_url('perizinan_online/daftar_terbit');
        $config['total_rows']= $data['num_results'];
        $config['per_page']=$limit;
        $config['uri_segment']=3;//mengeset tempat offset pada setiap link yang digenerate
        $this->pagination->initialize($config);
        $data['pagination']=$this->pagination->create_links();

        // Build the page
        $this->template->title($this->module_details['name'])
            ->build('daftar_terbit', $data);
    }

    public function daftar_ambil($offset=0){
        $limit = 20;
        // bind the information to a key
        $query_data = (array)$this->perizinan_online_m->get_all_proses_ambil($limit,$offset);

        $data['daftar_permohonan'] = $query_data['rows'];
        $data['num_results'] = $query_data['num_rows'];

        //pagination
        $this->load->library('pagination');
        $config=array();
        $config['base_url']=site_url('perizinan_online/daftar_ambil');
        $config['total_rows']= $data['num_results'];
        $config['per_page']=$limit;
        $config['uri_segment']=3;//mengeset tempat offset pada setiap link yang digenerate
        $this->pagination->initialize($config);
        $data['pagination']=$this->pagination->create_links();

        // Build the page
        $this->template->title($this->module_details['name'])
            ->build('daftar_ambil', $data);
    }

    public function save_pdf(){
        $this->load->helper('download');

        $no_pendaftaran = $this->input->post('regis_no');
        $nama_pemohon = $this->input->post('regis_name');
        $nama_perizinan = $this->input->post('regis_izin');

        $data['no_pendaftaran'] = $no_pendaftaran;
        $data['nama_pemohon'] = $nama_pemohon;
        $data['nama_perizinan'] = $nama_perizinan;

        //$this->load->view('save_pdf',$data);
//        ini_set('memory_limit','32M'); // boost the memory limit if it's low <img src="http://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $html = $this->load->view('save_pdf', $data, true); // render the view into HTML

        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $pdf->SetFooter($_SERVER['HTTP_HOST'].'|{PAGENO}|'.date(DATE_RFC822)); // Add a footer for good measure <img src="http://davidsimpson.me/wp-includes/images/smilies/icon_wink.gif" alt=";)" class="wp-smiley">

        ### External CSS ###
        $stylesheet1 = file_get_contents(site_url('addons/shared_addons/themes/portal_agam/css/main-stylesheet.css')); // external css
        $stylesheet2 = file_get_contents(site_url('addons/shared_addons/themes/portal_agam/css/fonts.css')); // external css
        $stylesheet3 = file_get_contents(site_url('addons/shared_addons/themes/portal_agam/css/colors.css')); // external css
        $stylesheet4 = file_get_contents(site_url('addons/shared_addons/themes/portal_agam/css/custom.css')); // external css
        $pdf->WriteHTML($stylesheet1,1);
        $pdf->WriteHTML($stylesheet2,1);
        $pdf->WriteHTML($stylesheet3,1);
        $pdf->WriteHTML($stylesheet4,1);
        ####################

        $pdf->WriteHTML($html); // write the HTML into the PDF
        //$pdf->Output($pdfFilePath, 'F'); // save to file because we can
        $generated_pdf = $pdf->Output('','S');
        force_download($no_pendaftaran.".pdf", $generated_pdf);
    }

  }

/* End of file perizinan_online.php */