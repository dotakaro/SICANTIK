<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Info Singkat
 *
 * @author 		Info Singkat
 * @website		
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
		$this->load->model('info_singkat_m');
		$this->load->library('form_validation');
		$this->lang->load('info_singkat');

		// $this->load->library('files/files');
		// $this->load->model('files/file_folders_m');
		$this->lang->load('info_singkat');
		// Set the validation rules
		$this->item_validation_rules = array(
			array(
					'field' => 'isi_info',
					'label' => 'Isi_info',
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
		$info_singkat = $this->info_singkat_m->order_by('order')->get_all();
			$this->template
		->title($this->module_details['name'])
		->set('info_singkat', $info_singkat)
		->build('admin/index');
	}

	public function create()
	{
		$info_singkat = new StdClass();
		// $folder = $this->file_folders_m->get_by('name', 'info_singkat');
		// $this->data->files = Files::folder_contents($folder->id);
		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->item_validation_rules);

		// check if the form validation passed
		if($this->form_validation->run())
		{
			// See if the model can create the record
			if($this->info_singkat_m->create($this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('info_singkat.success'));
				redirect('admin/info_singkat');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('info_singkat.error'));
				redirect('admin/info_singkat/create');
			}
		}
		$info_singkat->data = new StdClass();
		foreach ($this->item_validation_rules AS $rule)
		{
			$info_singkat->data->{$rule['field']} = $this->input->post($rule['field']);
		}
		$this->_form_data();
		// Build the view using sample/views/admin/form.php
		$this->template->title($this->module_details['name'], lang('info_singkat.new_item'))
						->build('admin/form', $info_singkat->data);
	}

	public function edit($id = 0)
	{
		$this->data = $this->info_singkat_m->get($id);

		// $this->load->model('files/file_folders_m');
		// $folder = $this->file_folders_m->get_by('name', 'info_singkat');
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
			if($this->info_singkat_m->edit($id, $this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('info_singkat.success'));
				redirect('admin/info_singkat');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('info_singkat.error'));
				redirect('admin/info_singkat/create');
			}
		}
		// starting point for file uploads
		// $this->data->fileinput = json_decode($this->data->fileinput);
		$this->_form_data();
		// Build the view using sample/views/admin/form.php
		$this->template->title($this->module_details['name'], lang('info_singkat.edit'))
						->build('admin/form', $this->data);
	}

	public function _form_data()
	{
		// $this->load->model('pages/page_m');
		$this->template->list_published = array('1'=>'Yes','0'=>'No');
		// $this->template->pages = array_for_select($this->page_m->get_page_tree(), 'id', 'title');
	}

	public function delete($id = 0)
	{
		// make sure the button was clicked and that there is an array of ids
		if (isset($_POST['btnAction']) AND is_array($_POST['action_to']))
		{
			// pass the ids and let MY_Model delete the items
			$this->info_singkat_m->delete_many($this->input->post('action_to'));
		}
		elseif (is_numeric($id))
		{
			// they just clicked the link so we'll delete that one
			$this->info_singkat_m->delete($id);
		}
		redirect('admin/info_singkat');
	}
	public function order() {
		$items = $this->input->post('items');
		$i = 0;
		foreach($items as $item) {
			$item = substr($item, 5);
			$this->info_singkat_m->update($item, array('order' => $i));
			$i++;
		}
	}
}
