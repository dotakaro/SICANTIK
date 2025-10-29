<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Info Singkat
 *
 * @author      Info Singkat
 * @website     
 * @package     
 * @subpackage  
 * @copyright   MIT
 */
class info_singkat extends Public_Controller
{

    /**
     * The constructor
     * @access public
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
      $this->lang->load('info_singkat');
      $this->load->model('info_singkat_m');
      $this->template->append_css('module::info_singkat.css');
    }
     /**
     * List all info_singkats
     *
     *
     * @access  public
     * @return  void
     */
     public function index()
     {
      // bind the information to a key
      $data['info_singkat'] = (array)$this->info_singkat_m->get_all();
      // Build the page
      $this->template->title($this->module_details['name'])
      ->build('index', $data);
    }

  }

/* End of file info_singkat.php */