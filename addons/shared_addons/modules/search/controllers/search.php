<?php defined('BASEPATH') or exit('No direct script access allowed');

class Search extends Public_Controller
{
    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();

        $this->lang->load('search');
    }

    public function index()
    {
        $this->template
            ->title(lang('search:results'))
            ->build('index');
    }
}