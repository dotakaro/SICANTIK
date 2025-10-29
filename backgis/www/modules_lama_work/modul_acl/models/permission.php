<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * model untuk table permission
 * @author  Indra
 *
 */

class permission extends DataMapper{
    var $table = 'permissions';

    public function __construct() {
        parent::__construct();
    }
}
?>