<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk memanage File-file yang dapat didownload oleh Visitor Website
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
    private $_jenis_file = array(
        'Dasar Hukum'=>'Dasar Hukum',
        'Formulir'=>'Formulir'
    );

	public function __construct()
	{
		parent::__construct();

		// Load all the required classes
		$this->load->model('daftar_layanan_m');
		$this->load->library('form_validation');
		$this->lang->load('daftar_layanan');

		 $this->load->library('files/files');
		 $this->load->model('files/file_folders_m');

		// Set the validation rules
		$this->item_validation_rules = array(
			array(
					'field' => 'file_download',
					'label' => 'File_download',
					'rules' => '',
				),
array(
					'field' => 'file_desc',
					'label' => 'File_desc',
					'rules' => 'required|trim|xss_clean',
				),
array(
					'field' => 'published',
					'label' => 'Published',
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
		$daftar_layanan = $this->daftar_layanan_m->order_by('order')->get_all();
			$this->template
		->title($this->module_details['name'])
		->set('daftar_layanan', $daftar_layanan)
		->build('admin/index');
	}

	public function create()
	{
		$daftar_layanan = new StdClass();
		 $folder = $this->file_folders_m->get_by('name', 'daftar_layanan');
		 $this->data->files = Files::folder_contents($folder->id);
		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->item_validation_rules);

		// check if the form validation passed
		if($this->form_validation->run())
		{
			// See if the model can create the record
			if($this->daftar_layanan_m->create($this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('daftar_layanan.success'));
				redirect('admin/daftar_layanan');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('daftar_layanan.error'));
				redirect('admin/daftar_layanan/create');
			}
		}
		$daftar_layanan->data = new StdClass();
		foreach ($this->item_validation_rules AS $rule)
		{
			$daftar_layanan->data->{$rule['field']} = $this->input->post($rule['field']);
		}
		$this->_form_data();
		// Build the view using sample/views/admin/form.php
		$this->template->title($this->module_details['name'], lang('daftar_layanan.new_item'))
						->build('admin/form', $daftar_layanan->data);
	}

	public function edit($id = 0)
	{
		$this->data = $this->daftar_layanan_m->get($id);

		// $this->load->model('files/file_folders_m');
		// $folder = $this->file_folders_m->get_by('name', 'daftar_layanan');
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
			if($this->daftar_layanan_m->edit($id, $this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('daftar_layanan.success'));
				redirect('admin/daftar_layanan');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('daftar_layanan.error'));
				redirect('admin/daftar_layanan/create');
			}
		}
		// starting point for file uploads
		// $this->data->fileinput = json_decode($this->data->fileinput);
		$this->_form_data();
		// Build the view using sample/views/admin/form.php
		$this->template->title($this->module_details['name'], lang('daftar_layanan.edit'))
						->build('admin/form', $this->data);
	}

	public function _form_data()
	{
		### Ambil data dari webservice CURL Backoffice##
        $this->load->library('curl');
        $this->load->library('xml_parsing_win');
        
        $base_url_websevices = Settings::get('daftar_layanan_webservice');
        
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
        $this->template->list_izin =  $item_array;
		##############################################

        $this->template->list_jenis = $this->_jenis_file;
		
		$this->template->list_published = array('1'=>'Yes','0'=>'No');
	}

	public function delete($id = 0)
	{
        $this->Files = new Files();

		// make sure the button was clicked and that there is an array of ids
		if (isset($_POST['btnAction']) AND is_array($_POST['action_to']))
		{
            foreach($this->input->post('action_to') as $id){
                $deleted_file = $this->daftar_layanan_m->get($id);
                $this->Files->delete_file($deleted_file->file_download);
            }
			// pass the ids and let MY_Model delete the items
			$this->daftar_layanan_m->delete_many($this->input->post('action_to'));
		}
		elseif (is_numeric($id))
		{
			// they just clicked the link so we'll delete that one
            $deleted_file = $this->daftar_layanan_m->get($id);
            $this->Files->delete_file($deleted_file->file_download);
            $this->daftar_layanan_m->delete($id);
		}
		redirect('admin/daftar_layanan');
	}
	public function order() {
		$items = $this->input->post('items');
		$i = 0;
		foreach($items as $item) {
			$item = substr($item, 5);
			$this->daftar_layanan_m->update($item, array('order' => $i));
			$i++;
		}
	}
}
