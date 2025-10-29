<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Upload Gallery
 *
 * @author 		Indra Halim
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
		$this->load->model('gallery_m');
		$this->load->library('form_validation');
		$this->lang->load('gallery');

		 $this->load->library('files/files');
		 $this->load->model('files/file_folders_m');

		// Set the validation rules
		$this->item_validation_rules = array(
			/*array(
					'field' => 'gallery_file',
					'label' => 'Gallery_file',
					'rules' => 'required',
				),*/
			array(
					'field' => 'gallery_desc',
					'label' => 'Gallery_desc',
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
		$gallery = $this->gallery_m->order_by('order')->get_all();
			$this->template
		->title($this->module_details['name'])
		->set('gallery', $gallery)
		->build('admin/index');
	}

	public function create()
	{
		$gallery = new StdClass();
		 $folder = $this->file_folders_m->get_by('name', 'gallery');
		 $this->data->files = Files::folder_contents($folder->id);
		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->item_validation_rules);

		// check if the form validation passed
		if($this->form_validation->run())
		{
			// See if the model can create the record
			if($this->gallery_m->create($this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('gallery.success'));
				redirect('admin/gallery');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('gallery.error'));
				redirect('admin/gallery/create');
			}
		}
		$gallery->data = new StdClass();
		foreach ($this->item_validation_rules AS $rule)
		{
			$gallery->data->{$rule['field']} = $this->input->post($rule['field']);
		}
		$this->_form_data();
		// Build the view using sample/views/admin/form.php
		$this->template->title($this->module_details['name'], lang('gallery.new_item'))
						->build('admin/form', $gallery->data);
	}

	public function edit($id = 0)
	{
		$this->data = $this->gallery_m->get($id);

		 $this->load->model('files/file_folders_m');
		 $folder = $this->file_folders_m->get_by('name', 'gallery');
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
			if($this->gallery_m->edit($id, $this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('gallery.success'));
				redirect('admin/gallery');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('gallery.error'));
				redirect('admin/gallery/create');
			}
		}
		// starting point for file uploads
		 $this->data->fileinput = json_decode($this->data->fileinput);
		$this->_form_data();
		// Build the view using sample/views/admin/form.php
		$this->template->title($this->module_details['name'], lang('gallery.edit'))
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
        $this->Files = new Files();
		// make sure the button was clicked and that there is an array of ids
		if (isset($_POST['btnAction']) AND is_array($_POST['action_to']))
		{
			foreach($this->input->post('action_to') as $key=>$id){
                            $existing_data = $this->gallery_m->get($id);
                            Files::delete_file($existing_data->gallery_file);
                        }
                        // pass the ids and let MY_Model delete the items
			if($this->gallery_m->delete_many($this->input->post('action_to'))){
                            $this->session->set_flashdata('success', $this->lang->line('gallery:mass_delete_success'));
                        }else{
                            $this->session->set_flashdata('error', $this->lang->line('gallery:mass_delete_error'));
                        }
		}
		elseif (is_numeric($id))
		{
			// they just clicked the link so we'll delete that one
                        $existing_data = $this->gallery_m->get($id);
                        Files::delete_file($existing_data->gallery_file);
			if($this->gallery_m->delete($id)){
                            $this->session->set_flashdata('success', $this->lang->line('gallery:delete_success'));
                        }else{
                            $this->session->set_flashdata('error', $this->lang->line('gallery:delete_error'));
                        }
		}
		redirect('admin/gallery');
	}
	public function order() {
		$items = $this->input->post('items');
		$i = 0;
		foreach($items as $item) {
			$item = substr($item, 5);
			$this->gallery_m->update($item, array('order' => $i));
			$i++;
		}
	}
}
