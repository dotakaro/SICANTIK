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
class portal_theme extends Public_Controller
{

    /**
     * The constructor
     * @access public
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
      $this->lang->load('portal_theme');
      $this->load->model('portal_theme_m');
      $this->template->append_css('module::portal_theme.css');
    }
     /**
     * List all portal_themes
     *
     *
     * @access  public
     * @return  void
     */
     public function index()
     {
      // bind the information to a key
      $data['portal_theme'] = (array)$this->portal_theme_m->order_by('order','ASC')->get_all();
      // Build the page
      $this->template->title($this->module_details['name'])
        ->build('index', $data);
    }

    public function view($portal_theme_id)
    {

        $data['portal_theme'] = $this->portal_theme_m->get($portal_theme_id);

        $this->load->model('files/file_folders_m');
        $folder = $this->file_folders_m->get_by('name', 'portal_theme');
        $this->data->files = Files::folder_contents($folder->id);
        $this->load->view('view_detail',$data);

    }
  }

/* End of file portal_theme.php */