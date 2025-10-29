<?php
/**
 * Created by PhpStorm.
 * User: core
 * Date: 12/25/14
 * Time: 6:59 PM
 */
class pengaduan_api extends REST_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('pengaduan_online_m');
        $this->load->library('form_validation');

        // Set the validation rules
        $this->item_validation_rules = array(
            array(
                'field' => 'nama',
                'label' => 'lang:pengaduan_online:nama',
                'rules' => 'required|trim|xss_clean',
            ),
            array(
                'field' => 'alamat',
                'label' => 'lang:pengaduan_online:alamat',
                'rules' => 'required|trim|xss_clean',
            ),
            array(
                'field' => 'provinsi',
                'label' => 'lang:pengaduan_online:provinsi',
                'rules' => 'required',
            ),
            array(
                'field' => 'kabupaten',
                'label' => 'lang:pengaduan_online:kabupaten',
                'rules' => 'required',
            ),
            array(
                'field' => 'kecamatan',
                'label' => 'lang:pengaduan_online:kecamatan',
                'rules' => 'required',
            ),
            array(
                'field' => 'kelurahan',
                'label' => 'lang:pengaduan_online:kelurahan',
                'rules' => 'required',
            ),
            array(
                'field' => 'deskripsi_pengaduan',
                'label' => 'lang:pengaduan_online:deskripsi_pengaduan',
                'rules' => 'trim|xss_clean',
            )
        );
    }

    public function jenisPerizinanList_get() {
        $data = array(
            array(
                'id'=>1,
                'n_perizinan'=>'Izin 1',
            ),array(
                'id'=>2,
                'n_perizinan'=>'Izin 2',
            ),array(
                'id'=>3,
                'n_perizinan'=>'Izin 3',
            ),array(
                'id'=>4,
                'n_perizinan'=>'Izin 4',
            )
        );

        if($data) {
            $this->response($data, 200);
        } else {
            $this->response(NULL, 404);
        }
    }

    public function pengaduan_post(){
        $response = array();
        $success = false;
        $message = '';

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $_POST['nama']= ($request->nama) ? $request->nama : null;
        $_POST['alamat']= ($request->alamat) ? $request->alamat : null;
        $_POST['provinsi']= ($request->provinsi) ? $request->provinsi : null;
        $_POST['kabupaten']= ($request->kabupaten) ? $request->kabupaten : null;
        $_POST['kecamatan']= ($request->kecamatan) ? $request->kecamatan : null;
        $_POST['kelurahan']= ($request->kelurahan) ? $request->kelurahan : null;
        $_POST['deskripsi_pengaduan']= ($request->deskripsi_pengaduan) ? $request->deskripsi_pengaduan : null;
        $_POST['tanggal']=date('Y-m-d');
        $this->form_validation->set_rules($this->item_validation_rules);

        // check if the form validation passed
        if($this->form_validation->run())
        {
            ## BEGIN - Ambil data Wilayah dari webservice Backoffice##
            $this->load->library('curl');
            $base_url_websevices = Settings::get('pengaduan_online_webservice');
            $url_my_prop = $this->curl->simple_get("$base_url_websevices/api/detailwilayah/provinsi_id/{$this->input->post('provinsi')}/kabupaten_id/{$this->input->post('kabupaten')}/kecamatan_id/{$this->input->post('kecamatan')}/kelurahan_id/{$this->input->post('kelurahan')}/format/json");
            if(!empty($url_my_prop)){
                $dataWilayah = json_decode($url_my_prop);
                $_POST['provinsi_text'] = $dataWilayah->nama_provinsi;
                $_POST['kabupaten_text'] = $dataWilayah->nama_kabupaten;
                $_POST['kecamatan_text'] = $dataWilayah->nama_kecamatan;
                $_POST['kelurahan_text'] = $dataWilayah->nama_kelurahan;
            }
            ## END - Ambil data Wilayah dari webservice Backoffice##

            // See if the model can create the record
            if($this->pengaduan_online_m->create($this->input->post()))
            {
                $success = true;
                $response['success'] = $success;
                $response['message'] = $message;
                $this->response($response, 201);//send an HTTP 201 Created
            }
            // Something went wrong. Show them an error
            else
            {
                $message = lang('pengaduan_online.error');
                $response['success'] = $success;
                $response['message'] = $message;
                $this->response($response, 200);
            }
        }else{
            $message = validation_errors();
            $response['success'] = $success;
            $response['message'] = $message;
            $this->response($response, 200);
        }
    }
} 