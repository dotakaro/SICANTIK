<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Mengisi Link Website
 *
 * @author      Indra
 * @website     http://indra.com
 * @package     
 * @subpackage  
 * @copyright   MIT
 */
class link_website extends Public_Controller
{

    /**
     * The constructor
     * @access public
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
      $this->lang->load('link_website');
      $this->load->model('link_website_m');
      $this->template->append_css('module::link_website.css');
    }
     /**
     * List all link_websites
     *
     *
     * @access  public
     * @return  void
     */
     public function index()
     {
      // bind the information to a key
      $data['link_website'] = (array)$this->link_website_m->get_all();
      // Build the page
      $this->template->title($this->module_details['name'])
      ->build('index', $data);
    }

  }

/* End of file link_website.php */