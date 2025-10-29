<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Archive Dokumen class
 *
 * @author agusnur
 * Created : 10 Okt 2010
 *
 */

class tmarchive extends DataMapper {

    var $table = 'tmarchive';
    var $has_one = array('tmpemohon');

    public function __construct() {
        parent::__construct();
    }

}
