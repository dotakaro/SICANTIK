<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk memanage File-file yang dapat didownload oleh Visitor Website
 *
 * @author      Indra
 * @website     http://indra.com
 * @package     
 * @subpackage  
 * @copyright   MIT
 */
class dasar_hukum extends Public_Controller
{

    /**
     * The constructor
     * @access public
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
      $this->lang->load('dasar_hukum');
      $this->load->model('dasar_hukum_m');
      $this->template->append_css('module::dasar_hukum.css');
    }
     /**
     * List all dasar_hukums
     *
     *
     * @access  public
     * @return  void
     */
     public function index()
     {
      // bind the information to a key
      $data['dasar_hukum'] = (array)$this->dasar_hukum_m->get_all();
      // Build the page
      $this->template->title($this->module_details['name'])
      ->build('index', $data);
    }

  }

/* End of file dasar_hukum.php */