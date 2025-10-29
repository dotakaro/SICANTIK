<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * pengaduan_online Events Class
 *
 * @author      Indra
 * @website     http://indra.com
 * @package     
 * @subpackage  
 * @copyright   MIT
 */
class Events_pengaduan_online {

    protected $ci;

    public function __construct()
    {
        $this->ci =& get_instance();

        //register the public_controller event
        Events::register('public_controller', array($this, 'run'));

		//register a second event that can be called any time.
		// To execute the "run" method below you would use: Events::trigger('pengaduan_online_event');
		// in any php file within PyroCMS, even another module.
		Events::register('pengaduan_online_event', array($this, 'run'));
    }

    public function run()
    {
        $this->ci->load->model('pengaduan_online/pengaduan_online_m');

        // we're fetching this data on each front-end load. You'd probably want to do something with it IRL
        $this->ci->pengaduan_online_m->limit(5)->get_all();
    }

}
/* End of file events.php */