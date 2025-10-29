<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Modul untuk Upload Gallery
 *
 * @author        Indra Halim
 * @website        http://indra.com
 * @package
 * @subpackage
 * @copyright    MIT
 */
class gallery_m extends MY_Model
{

    private $folder;

    public function __construct()
    {
        parent::__construct();
        $this->_table = 'gallery';
        $this->load->model('files/file_folders_m');
        $this->load->library('files/files');
        $this->folder = $this->file_folders_m->get_by('name', 'gallery');
    }

    //create a new item
    public function create($input)
    {
        $fileinput = Files::upload($this->folder->id, FALSE, 'gallery_file', false, false, false, 'jpg|jpeg|png|gif');
        $to_insert = array(
            'gallery_desc' => $input['gallery_desc'],
            'published' => $input['published'],
            'created' => date('Y-m-d H:i:s')
        );
        if ($fileinput['status']) {
            $to_insert['gallery_file'] = $fileinput['data']['id'];
        } else {
            $this->session->set_flashdata('notice', $fileinput['message']);
            return false;
        }

        return $this->db->insert('gallery', $to_insert);
    }

    //edit a new item
    public function edit($id = 0, $input)
    {
        $this->data = $this->get($id);//Ambil Data sebelumnya

        $to_insert = array(
            'gallery_desc' => $input['gallery_desc'],
            'published' => $input['published'],
            'updated' => date('Y-m-d H:i:s')
        );

        if (!empty($_FILES['gallery_file']['name'])) {

            if (Files::get_file($this->data->gallery_file)) {
                Files::delete_file($this->data->gallery_file);
            }

            $fileinput = Files::upload($this->folder->id, FALSE, 'gallery_file', false, false, false, 'jpg|jpeg|png|gif');
            if ($fileinput['status']) {

                $to_insert['gallery_file'] = $fileinput['data']['id'];
            } else {
                $this->session->set_flashdata('notice', $fileinput['message']);
                return false;
            }
        }

        return $this->db->where('id', $id)->update('gallery', $to_insert);
    }
}
