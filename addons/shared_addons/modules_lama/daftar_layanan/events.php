<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * daftar_layanan Events Class
 *
 * @author      Indra
 * @website     http://indra.com
 * @package     
 * @subpackage  
 * @copyright   MIT
 */
class Events_daftar_layanan {

    protected $ci;

    public function __construct()
    {
        $this->ci =& get_instance();

        //register the public_controller event
        Events::register('public_controller', array($this, 'run'));

		//register a second event that can be called any time.
		// To execute the "run" method below you would use: Events::trigger('daftar_layanan_event');
		// in any php file within PyroCMS, even another module.
		Events::register('daftar_layanan_event', array($this, 'run'));
    }

    public function run()
    {
        $this->ci->load->model('daftar_layanan/daftar_layanan_m');

        // we're fetching this data on each front-end load. You'd probably want to do something with it IRL
        $this->ci->daftar_layanan_m->limit(5)->get_all();
    }

}
/* End of file events.php */