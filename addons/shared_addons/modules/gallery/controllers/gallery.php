<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Upload Gallery
 *
 * @author      Indra Halim
 * @website     http://indra.com
 * @package     
 * @subpackage  
 * @copyright   MIT
 */
class gallery extends Public_Controller
{

    /**
     * The constructor
     * @access public
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
      $this->lang->load('gallery');
      $this->load->model('gallery_m');
      $this->template->append_css('module::gallery.css');
    }
     /**
     * List all gallerys
     *
     *
     * @access  public
     * @return  void
     */
     public function index()
     {
      // bind the information to a key
      $data['gallery'] = (array)$this->gallery_m->get_all();
      // Build the page
      $this->template->title($this->module_details['name'])
      ->build('index', $data);
    }

  }

/* End of file gallery.php */