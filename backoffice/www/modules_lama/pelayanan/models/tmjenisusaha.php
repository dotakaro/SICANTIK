<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: indra
 * Date: 8/8/2015
 * Time: 7:57 AM
 */
class tmjenisusaha extends DataMapper
{
    var $table = 'tmjenisusaha';
    var $has_one = array('trproyek');
}