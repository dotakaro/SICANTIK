<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Pengaduan Online
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
            $this->load->model('pengaduan_online_m');
            $this->load->library('form_validation');
            $this->lang->load('pengaduan_online');
            // $this->load->library('files/files');
            // $this->load->model('files/file_folders_m');

            // Set the validation rules
            $this->item_validation_rules = array(
                array(
                    'field' => 'nama',
                    'label' => 'lang:pengaduan_online:nama',
                    'rules' => 'required|trim|xss_clean',
                ),
                array(
                    'field' => 'alamat',
                    'label' => 'lang:pengaduan_online:alamat',
                    'rules' => 'required|trim|xss_clean',
                ),
                array(
                    'field' => 'provinsi',
                    'label' => 'lang:pengaduan_online:provinsi',
                    'rules' => 'required',
                ),
                array(
                    'field' => 'kabupaten',
                    'label' => 'lang:pengaduan_online:kabupaten',
                    'rules' => 'required',
                ),
                array(
                    'field' => 'kecamatan',
                    'label' => 'lang:pengaduan_online:kecamatan',
                    'rules' => 'required',
                ),
                array(
                    'field' => 'kelurahan',
                    'label' => 'lang:pengaduan_online:kelurahan',
                    'rules' => 'required',
                ),
                array(
                    'field' => 'deskripsi_pengaduan',
                    'label' => 'lang:pengaduan_online:deskripsi_pengaduan',
                    'rules' => 'trim|xss_clean',
                )
            );

            // We'll set the partials and metadata here since they're used everywhere
            $this->template->append_js('module::admin.js')->append_css('module::admin.css');
	}

	/**
	 * List all items
	 */
	public function index()
	{
		$pengaduan_online = $this->pengaduan_online_m->order_by('order')->get_all();
			$this->template
		->title($this->module_details['name'])
		->set('pengaduan_online', $pengaduan_online)
		->build('admin/index');
	}

	public function edit($id = 0)
	{
		$this->data = $this->pengaduan_online_m->get($id);

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
			if($this->pengaduan_online_m->edit($id, $this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('pengaduan_online.success'));
				redirect('admin/pengaduan_online');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('pengaduan_online.error'));
				redirect('admin/pengaduan_online/create');
			}
		}
		// starting point for file uploads
		// $this->data->fileinput = json_decode($this->data->fileinput);
		$this->_form_data();
		// Build the view using sample/views/admin/form.php
		$this->template->title($this->module_details['name'], lang('pengaduan_online.edit'))
						->build('admin/form', $this->data);
	}

	public function _form_data()
	{
            ### Ambil data dari webservice CURL Backoffice##
            $this->load->library('curl');
            $this->load->library('xml_parsing_win');
            
            $base_url_websevices = Settings::get('pengaduan_online_webservice');
            
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
	}

	public function delete($id = 0)
	{
            // make sure the button was clicked and that there is an array of ids
            if (isset($_POST['btnAction']) AND is_array($_POST['action_to']))
            {
                    // pass the ids and let MY_Model delete the items
                    $this->pengaduan_online_m->delete_many($this->input->post('action_to'));
            }
            elseif (is_numeric($id))
            {
                    // they just clicked the link so we'll delete that one
                    $this->pengaduan_online_m->delete($id);
            }
            redirect('admin/pengaduan_online');
	}
	public function order() {
		$items = $this->input->post('items');
		$i = 0;
		foreach($items as $item) {
			$item = substr($item, 5);
			$this->pengaduan_online_m->update($item, array('order' => $i));
			$i++;
		}
	}
}
