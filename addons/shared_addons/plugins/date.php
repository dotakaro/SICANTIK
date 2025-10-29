<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Date Plugin
 *
 * Quick plugin to calculate current date and post "created_on"
 *
 * @author      Bakti Aditya
 * @copyright   Copyright (c) 2013, BVAP
 */
class Plugin_Date extends Plugin
{
    public $version = '1.0.0';

    public $name = array(
        'en'    => 'Date',
        'id'    => 'Tanggal',
    );

    public $description = array(
        'en'    => 'Plugin to calculate current date and post "created_on".',
        'id'    => 'Plugin untuk mengkalkulasi tanggal sekarang dan "created_on" di post',
    );

    public function _self_doc()
    {
        $info = array(
            'get_diff' => array(// the name of the method you are documenting
                'description' => array(// a single sentence to explain the purpose of this method
                    'en' => 'Get the difference time between now and assigned parameter.'
                ),
                'single' => true,// will it work as a single tag?
                'double' => false,// how about as a double tag?
                'variables' => '',// list all variables available inside the double tag. Separate them|like|this
                'attributes' => array(
                    'param' => array(// this is the order-dir="asc" attribute
                        'type' => 'number',// Can be: slug, number, flag, text, array, any.
                        'flags' => '',// flags are predefined values like this.
                        'default' => '',// attribute defaults to this if no value is given
                        'required' => true,// is this attribute required?
                    ),
                ),
            ),// end lang method
        );
        return $info;
    }

    public function get_diff(){

        $param = $this->attribute('param');

        $now = date("Y-m-d h:i:s");
        $past = date("Y-m-d h:i:s", $param);

        $date1 = new DateTime($now);
        $date2 = new DateTime($past);
        $interval = $date1->diff($date2);

        return $interval->d;
    }
}