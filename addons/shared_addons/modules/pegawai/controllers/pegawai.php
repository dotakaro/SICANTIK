<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk mengisi data Pegawai
 *
 * @author      Indra
 * @website     http://indra.com
 * @package     
 * @subpackage  
 * @copyright   MIT
 */
class pegawai extends Public_Controller
{

    /**
     * The constructor
     * @access public
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
      $this->lang->load('pegawai');
      $this->load->model('pegawai_m');
      $this->template->append_css('module::pegawai.css');
    }
     /**
     * List all pegawais
     *
     *
     * @access  public
     * @return  void
     */
     public function index()
     {
      // bind the information to a key
      $data['pegawai'] = (array)$this->pegawai_m->order_by('order','ASC')->get_all();
      // Build the page
      $this->template->title($this->module_details['name'])
        ->build('index', $data);
    }

    public function view($pegawai_id)
    {

        $data['pegawai'] = $this->pegawai_m->get($pegawai_id);

        $this->load->model('files/file_folders_m');
        $folder = $this->file_folders_m->get_by('name', 'pegawai');
        $this->data->files = Files::folder_contents($folder->id);
        $this->load->view('view_detail',$data);

    }
  }

/* End of file pegawai.php */