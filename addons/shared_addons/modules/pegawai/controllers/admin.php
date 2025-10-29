<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk mengisi data Pegawai
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
		$this->load->model('pegawai_m');
		$this->load->library('form_validation');
		$this->lang->load('pegawai');

		$this->load->library('files/files');
		$this->load->model('files/file_folders_m');

		// Set the validation rules
		$this->item_validation_rules = array(
			array(
					'field' => 'nama_pegawai',
					'label' => 'Nama_pegawai',
					'rules' => 'required|trim|xss_clean',
				),
array(
					'field' => 'nip',
					'label' => 'Nip',
					'rules' => 'required|trim|xss_clean',
				),
array(
					'field' => 'jabatan',
					'label' => 'Jabatan',
					'rules' => 'required|trim|xss_clean',
				),
array(
					'field' => 'alamat',
					'label' => 'Alamat',
					'rules' => 'trim|xss_clean',
				),
array(
					'field' => 'tempat_lahir',
					'label' => 'Tempat_lahir',
					'rules' => 'trim|xss_clean',
				),
array(
					'field' => 'tgl_lahir',
					'label' => 'Tgl_lahir',
					'rules' => '',
				),
array(
					'field' => 'no_telp',
					'label' => 'No_telp',
					'rules' => 'trim|xss_clean',
				),
array(
					'field' => 'pendidikan',
					'label' => 'Pendidikan',
					'rules' => 'trim|xss_clean',
				),
array(
					'field' => 'foto',
					'label' => 'Foto',
					'rules' => '',
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
		$pegawai = $this->pegawai_m->order_by('order')->get_all();
			$this->template
		->title($this->module_details['name'])
		->set('pegawai', $pegawai)
		->build('admin/index');
	}

	public function create()
	{
		$pegawai = new StdClass();
		 $folder = $this->file_folders_m->get_by('name', 'pegawai');
		 $this->data->files = Files::folder_contents($folder->id);
		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->item_validation_rules);

		// check if the form validation passed
		if($this->form_validation->run())
		{
			// See if the model can create the record
			if($this->pegawai_m->create($this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('pegawai.success'));
				redirect('admin/pegawai');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('pegawai.error'));
				redirect('admin/pegawai/create');
			}
		}
		$pegawai->data = new StdClass();
		foreach ($this->item_validation_rules AS $rule)
		{
			$pegawai->data->{$rule['field']} = $this->input->post($rule['field']);
		}
		$this->_form_data();
		// Build the view using sample/views/admin/form.php
		$this->template->title($this->module_details['name'], lang('pegawai.new_item'))
						->build('admin/form', $pegawai->data);
	}

	public function edit($id = 0)
	{
		$this->data = $this->pegawai_m->get($id);

		 $this->load->model('files/file_folders_m');
		 $folder = $this->file_folders_m->get_by('name', 'pegawai');
		 $this->data->files = Files::folder_contents($folder->id);

		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->item_validation_rules);

		// check if the form validation passed
		if($this->form_validation->run())
		{
			// get rid of the btnAction item that tells us which button was clicked.
			// If we don't unset it MY_Model will try to insert it
			unset($_POST['btnAction']);

			// See if the model can create the record
			if($this->pegawai_m->edit($id, $this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('pegawai.success'));
				redirect('admin/pegawai');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('pegawai.error'));
				redirect('admin/pegawai/create');
			}
		}
		// starting point for file uploads
		// $this->data->fileinput = json_decode($this->data->fileinput);
		$this->_form_data();
		// Build the view using sample/views/admin/form.php
		$this->template->title($this->module_details['name'], lang('pegawai.edit'))
						->build('admin/form', $this->data);
	}

	public function _form_data()
	{
		// $this->load->model('pages/page_m');
		// $this->template->pages = array_for_select($this->page_m->get_page_tree(), 'id', 'title');
	}

	public function delete($id = 0)
	{
        $this->Files = new Files();
		// make sure the button was clicked and that there is an array of ids
		if (isset($_POST['btnAction']) AND is_array($_POST['action_to']))
		{
            foreach($this->input->post('action_to') as $id){
                $deleted_file = $this->pegawai_m->get($id);
                $this->Files->delete_file($deleted_file->foto);
            }
			// pass the ids and let MY_Model delete the items
			$this->pegawai_m->delete_many($this->input->post('action_to'));
		}
		elseif (is_numeric($id))
		{
            $deleted_file = $this->pegawai_m->get($id);
            $this->Files->delete_file($deleted_file->foto);
			// they just clicked the link so we'll delete that one
			$this->pegawai_m->delete($id);
		}
		redirect('admin/pegawai');
	}
	public function order() {
		$items = $this->input->post('items');
		$i = 0;
		foreach($items as $item) {
			$item = substr($item, 5);
			$this->pegawai_m->update($item, array('order' => $i));
			$i++;
		}
	}
}
