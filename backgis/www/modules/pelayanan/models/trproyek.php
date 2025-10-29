<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: indra
 * Date: 8/8/2015
 * Time: 7:57 AM
 */
class trproyek extends DataMapper
{
    var $table = 'trproyek';
    var $has_one = array('tmjenisusaha');
    var $has_many = array('tmpermohonan');
}