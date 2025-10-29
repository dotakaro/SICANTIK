<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Perizinan Online
 *
 * @author 		Indra
 * @website		http://indra.com
 * @package 	
 * @subpackage 	
 * @copyright 	MIT
 */
class Admin extends Admin_Controller
{
	protected $section = 'items';

	public function __construct()
	{
		parent::__construct();

		// Load all the required classes
		$this->load->model('perizinan_online_m');
		$this->load->library('form_validation');
		$this->lang->load('perizinan_online');
		// $this->load->library('files/files');
		// $this->load->model('files/file_folders_m');

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
                                'rules' => 'required',
                        ),
array(
                                'field' => 'kabupaten_perusahaan',
                                'label' => 'lang:perizinan_online:kabupaten_perusahaan',
                                'rules' => 'required',
                        ),
array(
                                'field' => 'kecamatan_perusahaan',
                                'label' => 'lang:perizinan_online:kecamatan_perusahaan',
                                'rules' => 'required',
                        ),
array(
                                'field' => 'kelurahan_perusahaan',
                                'label' => 'lang:perizinan_online:kelurahan_perusahaan',
                                'rules' => 'required',
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
                        ),

        );

		// We'll set the partials and metadata here since they're used everywhere
		$this->template->append_js('module::admin.js')
						->append_css('module::admin.css');
	}

	/**
	 * List all items
	 */
	public function index()
	{
		$perizinan_online = $this->perizinan_online_m->order_by('order')->get_all();
			$this->template
		->title($this->module_details['name'])
		->set('perizinan_online', $perizinan_online)
		->build('admin/index');
	}

	public function create()
	{
		$perizinan_online = new StdClass();
		// $folder = $this->file_folders_m->get_by('name', 'perizinan_online');
		// $this->data->files = Files::folder_contents($folder->id);
		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->item_validation_rules);

		// check if the form validation passed
		if($this->form_validation->run())
		{
			// See if the model can create the record
			if($this->perizinan_online_m->create($this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('perizinan_online.success'));
				redirect('admin/perizinan_online');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('perizinan_online.error'));
				redirect('admin/perizinan_online/create');
			}
		}
		$perizinan_online->data = new StdClass();
		foreach ($this->item_validation_rules AS $rule)
		{
			$perizinan_online->data->{$rule['field']} = $this->input->post($rule['field']);
		}
		$this->_form_data();
		// Build the view using sample/views/admin/form.php
		$this->template->title($this->module_details['name'], lang('perizinan_online.new_item'))
						->build('admin/form', $perizinan_online->data);
	}

	public function edit($id = 0)
	{
		$this->data = $this->perizinan_online_m->get($id);

		// $this->load->model('files/file_folders_m');
		// $folder = $this->file_folders_m->get_by('name', 'perizinan_online');
		// $this->data->files = Files::folder_contents($folder->id);

		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->item_validation_rules);

		// check if the form validation passed
		if($this->form_validation->run())
		{
			// get rid of the btnAction item that tells us which button was clicked.
			// If we don't unset it MY_Model will try to insert it
			unset($_POST['btnAction']);

			// See if the model can create the record
			if($this->perizinan_online_m->edit($id, $this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('perizinan_online.success'));
				redirect('admin/perizinan_online');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('perizinan_online.error'));
				redirect('admin/perizinan_online/create');
			}
		}
		// starting point for file uploads
		// $this->data->fileinput = json_decode($this->data->fileinput);
		$this->_form_data();
		// Build the view using sample/views/admin/form.php
		$this->template->title($this->module_details['name'], lang('perizinan_online.view'))
						->build('admin/form', $this->data);
	}

    public function view($id = 0)
    {
        $this->data = $this->perizinan_online_m->get($id);

        // $this->load->model('files/file_folders_m');
        // $folder = $this->file_folders_m->get_by('name', 'perizinan_online');
        // $this->data->files = Files::folder_contents($folder->id);

        // Set the validation rules from the array above
        /*$this->form_validation->set_rules($this->item_validation_rules);

        // check if the form validation passed
        if($this->form_validation->run())
        {
            // get rid of the btnAction item that tells us which button was clicked.
            // If we don't unset it MY_Model will try to insert it
            unset($_POST['btnAction']);

            // See if the model can create the record
            if($this->perizinan_online_m->edit($id, $this->input->post()))
            {
                // All good...
                $this->session->set_flashdata('success', lang('perizinan_online.success'));
                redirect('admin/perizinan_online');
            }
            // Something went wrong. Show them an error
            else
            {
                $this->session->set_flashdata('error', lang('perizinan_online.error'));
                redirect('admin/perizinan_online/create');
            }
        }*/
        // starting point for file uploads
        // $this->data->fileinput = json_decode($this->data->fileinput);
        //$this->_form_data();
        // Build the view using sample/views/admin/form.php
        $this->template->title($this->module_details['name'], lang('perizinan_online.edit'))
            ->build('admin/view', $this->data);
    }

	public function _form_data()
	{
            ### Ambil data dari webservice CURL Backoffice##
            $this->load->library('curl');
            $this->load->library('xml_parsing_win');
            
            $base_url_websevices = Settings::get('perizinan_online_webservice');
            
            //List Perizinan
            $url = $this->curl->simple_get("$base_url_websevices/api/jenisperizinanlist");
            $news_items = $this->xml_parsing_win->element_set('item', $url);
            $item_array = array();
            $item_array[] = "Pilih Izin :";
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
            $list_prop[] = "Pilih Provinsi :";
            foreach ($dt_prop as $item2) {
                $id_prop = $this->xml_parsing_win->value_in('id', $item2);
                $nama_propinsi = $this->xml_parsing_win->value_in('nama_propinsi', $item2);

                $list_prop[$id_prop] = $nama_propinsi;
            }
            $this->template->list_provinsi = $list_prop;
            
            $this->template->list_kabupaten = array(
              'Pilih Kabupaten :'  
            );
            
            $this->template->list_kecamatan = array(
              'Pilih Kecamatan :'  
            );
            
            $this->template->list_kelurahan = array(
              'Pilih Kelurahan :'  
            );
            ################################################
            
            $this->template->list_jenis_identitas = array(
                ''=>'Pilih Identitas :',
                'KTP'=>'KTP',
                'SIM'=>'SIM',
                'PASSPORT'=>'PASSPORT'
            );
            
            
		// $this->load->model('pages/page_m');
		// $this->template->pages = array_for_select($this->page_m->get_page_tree(), 'id', 'title');
	}

	public function delete($id = 0)
	{
		// make sure the button was clicked and that there is an array of ids
		if (isset($_POST['btnAction']) AND is_array($_POST['action_to']))
		{
			// pass the ids and let MY_Model delete the items
			$this->perizinan_online_m->delete_many($this->input->post('action_to'));
		}
		elseif (is_numeric($id))
		{
			// they just clicked the link so we'll delete that one
			$this->perizinan_online_m->delete($id);
		}
		redirect('admin/perizinan_online');
	}
	public function order() {
		$items = $this->input->post('items');
		$i = 0;
		foreach($items as $item) {
			$item = substr($item, 5);
			$this->perizinan_online_m->update($item, array('order' => $i));
			$i++;
		}
	}
}
