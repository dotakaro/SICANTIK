<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Year class
 *
 * @author agusnur
 * Created : 13 Feb 2010
 *
 */

class year extends DataMapper {

    var $table = 'year';

    public function __construct() {
        parent::__construct();
    }

}
