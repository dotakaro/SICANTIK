<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Diskusi di Backend
 *
 * @author 		Indra Halm
 * @website		http://indra.com
 * @package 	com.indra.pyro.discussion
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
		$this->load->model('discussion_m');
        $this->load->model('discussion_comment_m');
        $this->load->library('form_validation');
		$this->lang->load('discussion');

		// $this->load->library('files/files');
		// $this->load->model('files/file_folders_m');

		// Set the validation rules
		$this->item_validation_rules = array(
			array(
                'field' => 'topic',
                'label' => 'Topic',
                'rules' => 'required|trim|xss_clean',
            ),
            array(
                'field' => 'message_to',
                'label' => 'Message_to',
                'rules' => 'required',
            ),
            array(
                'field' => 'created_by',
                'label' => 'Created_by',
                'rules' => 'required',
            ),
		);

        $this->comment_validation_rules = array(
            array(
                'field' => 'comment',
                'label' => 'Topic',
                'rules' => 'required|trim|xss_clean',
            ),
            array(
                'field' => 'discussion_id',
                'label' => 'Message_to',
                'rules' => 'required',
            ),
            array(
                'field' => 'created_by',
                'label' => 'Created_by',
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
        $this->load->model('users/user_m');
        $list_users = array();
        $all_users = $this->user_m->get_all();
        if(!empty($all_users)){
            foreach($all_users as $key=>$val){
                $list_users[$val->id] = $val->username;
            }
        }
        $this->template->list_users = $list_users;

		$discussion = $this->discussion_m->order_by('order')
            ->where('created_by', $this->current_user->id)
            ->or_where('message_to', $this->current_user->id)
            ->get_all();
        $this->template->current_user_id = $this->current_user->id;

		$this->template
            ->title($this->module_details['name'])
            ->set('discussion', $discussion)
            ->build('admin/index');
	}

	public function create()
	{
		$discussion = new StdClass();
		// $folder = $this->file_folders_m->get_by('name', 'discussion');
		// $this->data->files = Files::folder_contents($folder->id);
		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->item_validation_rules);

		// check if the form validation passed
		if($this->form_validation->run())
		{
			// See if the model can create the record
			if($this->discussion_m->create($this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('discussion.success'));
				redirect('admin/discussion');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('discussion.error'));
				redirect('admin/discussion/create');
			}
		}
		$discussion->data = new StdClass();
		foreach ($this->item_validation_rules AS $rule)
		{
			$discussion->data->{$rule['field']} = $this->input->post($rule['field']);
		}
		$this->_form_data();
		// Build the view using sample/views/admin/form.php
		$this->template->title($this->module_details['name'], lang('discussion.new_item'))
						->build('admin/form', $discussion->data);
	}

	public function edit($id = 0)
	{
		$this->data = $this->discussion_m->get($id);

		// $this->load->model('files/file_folders_m');
		// $folder = $this->file_folders_m->get_by('name', 'discussion');
		// $this->data->files = Files::folder_contents($folder->id);

		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->comment_validation_rules);

		// check if the form validation passed
		if($this->form_validation->run())
		{
			// get rid of the btnAction item that tells us which button was clicked.
			// If we don't unset it MY_Model will try to insert it
			unset($_POST['btnAction']);

			// See if the model can create the record
			if($this->discussion_m->edit($id, $this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('discussion.success'));
				redirect('admin/discussion');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('discussion.error'));
				redirect('admin/discussion/create');
			}
		}
		// starting point for file uploads
		// $this->data->fileinput = json_decode($this->data->fileinput);
		$this->_form_data();
		// Build the view using sample/views/admin/form.php
		$this->template->title($this->module_details['name'], lang('discussion.edit'))
						->build('admin/form', $this->data);
	}

    public function view($id = 0)
    {
        $this->data = $this->discussion_m->get($id);

        $comments = $this->discussion_comment_m->order_by('created','ASC')->where('discussion_id',$id)->get_all();

        // Set the validation rules from the array above
        $this->form_validation->set_rules($this->comment_validation_rules);

        // check if the form validation passed
        if($this->form_validation->run())
        {
            // get rid of the btnAction item that tells us which button was clicked.
            // If we don't unset it MY_Model will try to insert it
            unset($_POST['btnAction']);

            // See if the model can create the record
            if($this->discussion_comment_m->create($this->input->post()))
            {
                // All good...
                $this->session->set_flashdata('success', lang('discussion.success'));
                redirect('admin/discussion/view/'.$id);
            }
            // Something went wrong. Show them an error
            else
            {
                $this->session->set_flashdata('error', lang('discussion.error'));
                redirect('admin/discussion/view/'.$id);
            }
        }
        // starting point for file uploads
        // $this->data->fileinput = json_decode($this->data->fileinput);
        $this->_form_data();
        $this->template->discussion_id = $id;
        $this->template->comments = $comments;

        // Build the view using sample/views/admin/form.php
        $this->template->title($this->module_details['name'], lang('discussion.edit'))
            ->build('admin/view', $this->data);
    }

	public function _form_data()
	{
        $this->load->model('users/user_m');
        $list_users = array();
        $all_users = $this->user_m->get_all();
        $list_users[]='Please Select';
        if(!empty($all_users)){
            foreach($all_users as $key=>$val){
                $list_users[$val->id] = $val->username;
            }
        }

        $this->template->current_user_id = $this->current_user->id;
        $this->template->list_users = $list_users;
		// $this->template->pages = array_for_select($this->page_m->get_page_tree(), 'id', 'title');
	}

	public function delete($id = 0)
	{

		// make sure the button was clicked and that there is an array of ids
		if (isset($_POST['btnAction']) AND is_array($_POST['action_to']))
		{
			// pass the ids and let MY_Model delete the items
			foreach($_POST['action_to'] as $id){
                $this->discussion_m->delete_comment($id);
            }
            $this->discussion_m->delete_many($this->input->post('action_to'));

		}
		elseif (is_numeric($id))
		{
			// they just clicked the link so we'll delete that one
            $this->discussion_m->delete_comment($id);
			$this->discussion_m->delete($id);
		}
		redirect('admin/discussion');
	}
	public function order() {
		$items = $this->input->post('items');
		$i = 0;
		foreach($items as $item) {
			$item = substr($item, 5);
			$this->discussion_m->update($item, array('order' => $i));
			$i++;
		}
	}
}
