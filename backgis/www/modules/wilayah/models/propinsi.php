<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Propinsi class
 *
 * @author agusnur
 * Created : 10 Aug 2010
 *
 */

class Propinsi extends DataMapper {

    var $table = 'trpropinsi';

    public function __construct() {
        parent::__construct();
    }

}