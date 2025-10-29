<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * gallery Events Class
 *
 * @author      Indra Halim
 * @website     http://indra.com
 * @package     
 * @subpackage  
 * @copyright   MIT
 */
class Events_gallery {

    protected $ci;

    public function __construct()
    {
        $this->ci =& get_instance();

        //register the public_controller event
        Events::register('public_controller', array($this, 'run'));

		//register a second event that can be called any time.
		// To execute the "run" method below you would use: Events::trigger('gallery_event');
		// in any php file within PyroCMS, even another module.
		Events::register('gallery_event', array($this, 'run'));
    }

    public function run()
    {
        $this->ci->load->model('gallery/gallery_m');

        // we're fetching this data on each front-end load. You'd probably want to do something with it IRL
        $this->ci->gallery_m->limit(5)->get_all();
    }

}
/* End of file events.php */