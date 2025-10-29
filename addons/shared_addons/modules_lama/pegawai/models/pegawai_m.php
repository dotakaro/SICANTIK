<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Modul untuk mengisi data Pegawai
 *
 * @author        Indra
 * @website        http://indra.com
 * @package
 * @subpackage
 * @copyright    MIT
 */
class pegawai_m extends MY_Model
{

    private $folder;

    public function __construct()
    {
        parent::__construct();
        $this->_table = 'pegawai';
        $this->load->model('files/file_folders_m');
        $this->load->library('files/files');
        $this->folder = $this->file_folders_m->get_by('name', 'pegawai');
    }

    //create a new item
    public function create($input)
    {
        $fileinput = Files::upload($this->folder->id, FALSE, 'foto', false, false, false, 'jpg|jpeg|png|gif');
        $to_insert = array(
            // 'fileinput' => json_encode($fileinput);
            'nama_pegawai' => $input['nama_pegawai'],
            'nip' => $input['nip'],
            'jabatan' => $input['jabatan'],
            'alamat' => $input['alamat'],
            'tempat_lahir' => $input['tempat_lahir'],
            'tgl_lahir' => $input['tgl_lahir'],
            'no_telp' => $input['no_telp'],
            'pendidikan' => $input['pendidikan'],
            'order' => $input['order'],
        );

        if ($fileinput['status']) {
            $to_insert['foto'] = $fileinput['data']['id'];
        } else {
            $this->session->set_flashdata('notice', $fileinput['message']);
            return false;
        }

        return $this->db->insert('pegawai', $to_insert);
    }

    //edit a new item
    public function edit($id = 0, $input)
    {
        $this->data = $this->get($id);//Ambil Data sebelumnya

        $to_insert = array(
            'nama_pegawai' => $input['nama_pegawai'],
            'nip' => $input['nip'],
            'jabatan' => $input['jabatan'],
            'alamat' => $input['alamat'],
            'tempat_lahir' => $input['tempat_lahir'],
            'tgl_lahir' => $input['tgl_lahir'],
            'no_telp' => $input['no_telp'],
            'pendidikan' => $input['pendidikan'],
            'order' => $input['order'],
        );

        if (!empty($_FILES['foto']['name'])) {
            if (Files::get_file($this->data->foto)) {
                Files::delete_file($this->data->foto);
            }

            $fileinput = Files::upload($this->folder->id, FALSE, 'foto', false, false, false, 'jpg|jpeg|png|gif');

            if ($fileinput['status']) {
                $to_insert['foto'] = $fileinput['data']['id'];
            } else {
                $this->session->set_flashdata('notice', $fileinput['message']);
                return false;
            }
        }

        return $this->db->where('id', $id)->update('pegawai', $to_insert);
    }
}
