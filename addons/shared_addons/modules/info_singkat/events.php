<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * info_singkat Events Class
 *
 * @author      Info Singkat
 * @website     
 * @package     
 * @subpackage  
 * @copyright   MIT
 */
class Events_info_singkat {

    protected $ci;

    public function __construct()
    {
        $this->ci =& get_instance();

        //register the public_controller event
        Events::register('public_controller', array($this, 'run'));

		//register a second event that can be called any time.
		// To execute the "run" method below you would use: Events::trigger('info_singkat_event');
		// in any php file within PyroCMS, even another module.
		Events::register('info_singkat_event', array($this, 'run'));
    }

    public function run()
    {
        $this->ci->load->model('info_singkat/info_singkat_m');

        // we're fetching this data on each front-end load. You'd probably want to do something with it IRL
        $this->ci->info_singkat_m->limit(5)->get_all();
    }

}
/* End of file events.php */