<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Kecamatan class
 *
 * @author agusnur
 * Created : 10 Aug 2010
 * @ModifiedAuthor Indra
 * Modified: 26 April 2013
 * @ModifiedComment Penambahan trkelurahan di $has_many
 */

class trkecamatan extends DataMapper {

    var $table = 'trkecamatan';
    var $has_one = array('trkabupaten');
    var $has_many = array('trkelurahan', 'tmpemohon', 'tmperusahaan');

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of user class
