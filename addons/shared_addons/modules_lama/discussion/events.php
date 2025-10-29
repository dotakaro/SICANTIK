<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * discussion Events Class
 *
 * @author      Indra Halm
 * @website     http://indra.com
 * @package     com.indra.pyro.discussion
 * @subpackage  
 * @copyright   MIT
 */
class Events_discussion {

    protected $ci;

    public function __construct()
    {
        $this->ci =& get_instance();

        //register the public_controller event
        Events::register('public_controller', array($this, 'run'));

		//register a second event that can be called any time.
		// To execute the "run" method below you would use: Events::trigger('discussion_event');
		// in any php file within PyroCMS, even another module.
		Events::register('discussion_event', array($this, 'run'));
    }

    public function run()
    {
        $this->ci->load->model('discussion/discussion_m');

        // we're fetching this data on each front-end load. You'd probably want to do something with it IRL
        $this->ci->discussion_m->limit(5)->get_all();
    }

}
/* End of file events.php */