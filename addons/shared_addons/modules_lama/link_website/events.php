<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * link_website Events Class
 *
 * @author      Indra
 * @website     http://indra.com
 * @package     
 * @subpackage  
 * @copyright   MIT
 */
class Events_link_website {

    protected $ci;

    public function __construct()
    {
        $this->ci =& get_instance();

        //register the public_controller event
        Events::register('public_controller', array($this, 'run'));

		//register a second event that can be called any time.
		// To execute the "run" method below you would use: Events::trigger('link_website_event');
		// in any php file within PyroCMS, even another module.
		Events::register('link_website_event', array($this, 'run'));
    }

    public function run()
    {
        $this->ci->load->model('link_website/link_website_m');

        // we're fetching this data on each front-end load. You'd probably want to do something with it IRL
        $this->ci->link_website_m->limit(5)->get_all();
    }

}
/* End of file events.php */