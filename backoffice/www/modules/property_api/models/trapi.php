<?php
/**
 * Created by PhpStorm.
 * Class untuk Tabel trapi
 * User: core
 * Date: 3/3/2015
 * Time: 7:55 AM
 */

class trapi extends DataMapper{
    var $table = 'trapi';
    var $has_many = array('property_hierarchy', 'mapping');
}