<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Perizinan Online
 *
 * @author      Indra
 * @website     http://indra.com
 * @package     
 * @subpackage  
 * @copyright   MIT
 */
class pengaduan_online extends Public_Controller
{

    /**
     * The constructor
     * @access public
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
      $this->lang->load('pengaduan_online');
      $this->load->model('pengaduan_online_m');
      $this->load->library('form_validation');
      $this->lang->load(array('pengaduan_online'));
      $this->template->append_css('module::pengaduan_online.css');	  	  
      
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
     /**
     * List all pengaduan_onlines
     *
     *
     * @access  public
     * @return  void
     */
     /*public function index()
     {
      // bind the information to a key
      $data['perizinan_online'] = (array)$this->perizinan_online_m->get_all();
      // Build the page
      $this->template->title($this->module_details['name'])
      ->build('index', $data);
    }*/
    
    public function index()
    {            
            $pengaduan_online = new StdClass();
            // $folder = $this->file_folders_m->get_by('name', 'perizinan_online');
            // $this->data->files = Files::folder_contents($folder->id);
            // Set the validation rules from the array above
            $this->form_validation->set_rules($this->item_validation_rules);

            // check if the form validation passed
            if($this->form_validation->run())
            {
                $no_urut = $this->get_latest_urut();
                $no_urut = $no_urut->urut;
                if ($no_urut != NULL) {
                    $no_uniq = $no_urut + 1;
                } else {
                    $no_uniq = 1;
                }
                $_POST['urut'] = $no_uniq;
                $this->session->set_userdata('no_uniq', $no_uniq);
                
                // See if the model can create the record
                if($this->pengaduan_online_m->create($this->input->post()))
                {
                        // All good...
						$this->post_to_backoffice();
                        $this->session->set_flashdata('success', lang('pengaduan_online.success'));
                        redirect('pengaduan_online/success');
                }
                // Something went wrong. Show them an error
                else
                {
                        $this->session->set_flashdata('error', lang('pengaduan_online.error'));
                        redirect('pengaduan_online/create');
                }       
            }
            $pengaduan_online->data = new StdClass();
            foreach ($this->item_validation_rules AS $rule)
            {
                    $pengaduan_online->data->{$rule['field']} = $this->input->post($rule['field']);
            }
					
            $this->_form_data();			
            // Build the view using sample/views/admin/form.php
            $this->template->title($this->module_details['name'], lang('pengaduan_online.new_item'))
                ->set_breadcrumb( lang('pengaduan_online:layanan_online'), '#')  
                ->set_breadcrumb( lang('pengaduan_online:pengaduan_online'))    
                ->build('form', $pengaduan_online->data);
    }
	
	private function post_to_backoffice(){
        $base_url_websevices = Settings::get('pengaduan_online_webservice');
        //$max_file_upload = 1000;
        $this->load->library('curl');
        $this->load->library('xml_parsing_win');
        

                
                $data2 = array();
                $data2['nama']= $this->input->post('nama');                
                $data2['alamat'] = $this->input->post('alamat');
                $data2['kelurahan'] = $this->input->post('kelurahan');
                $data2['kecamatan'] = $this->input->post('kecamatan');
                $data2['e_pesan'] = $this->input->post('deskripsi_pengaduan');
                $data2['d_entry'] = date("Y-m-d");
                
                
                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, "$base_url_websevices/api/pengaduan/");
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "nama=" . $data2['nama'] .
                        "&alamat=" . $data2['alamat'] .
                        "&kelurahan=" . $data2['kelurahan'] .
                        "&kecamatan=" . $this->db->escape_str($data2['kecamatan']) .
                        "&e_pesan=" . $this->db->escape_str($data2['e_pesan']) .
                        "&d_entry=" . $this->db->escape_str($data2['d_entry'])                        
                );
                $_POST['urut'] = $no_uniq;
                //$_POST['lampiran'] = $uploaded_filename;
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                $result = curl_exec($ch);
				
                //$xml = $this->xml_parsing_win->element_set('xml', $result);
                /*foreach ($xml as $item) {
                    $noPendaf = $this->xml_parsing_win->value_in('no_pendaftaran', $item);
                    $_POST['no_pendaftaran'] = $noPendaf;
                }*/
                curl_close($ch);
                return true;
            //}
        //}
        
    }
    
    public function success(){
        $id_uniq = 0;
        
        $nama = '';
        $alamat = '';
        $tanggal ='';
        $deskripsi_pengaduan ='';
        
        $id_uniq = $this->session->userdata('no_uniq');
        if(!$id_uniq){
            redirect('pengaduan_online');
        }
        $data = $this->db->where("urut",$id_uniq)->get('default_pengaduan_online')->result_array();
        if(!empty($data)){
            $nama = $data[0]['nama'];
            $alamat =  $data[0]['alamat'];
            $tanggal = $data[0]['tanggal'];
            $deskripsi_pengaduan = $data[0]['deskripsi_pengaduan'];
        }
        $this->template->nama = $nama;
        $this->template->alamat = $alamat;
        $this->template->tanggal = $tanggal;
        $this->template->deskripsi_pengaduan = $deskripsi_pengaduan;
        
        // Build the view using sample/views/admin/form.php
        $this->template->title($this->module_details['name'], lang('pengaduan_online.success'))
                        ->build('success', $data);
    }
    
    public function _form_data()
    {
        ### Ambil data dari webservice CURL Backoffice##
        $this->load->library('curl');
        $this->load->library('xml_parsing_win');

        $base_url_websevices = Settings::get('pengaduan_online_webservice');

        //List Provinsi
        $url_my_prop = $this->curl->simple_get("$base_url_websevices/api/propinsi");
        $dt_prop = $this->xml_parsing_win->element_set('item', $url_my_prop);
        $list_prop = array();
        $list_prop[] = "Pilih Provinsi :";
        foreach ($dt_prop as $item2) {
            $id_prop = $this->xml_parsing_win->value_in('id', $item2);
            $nama_propinsi = $this->xml_parsing_win->value_in('nama_propinsi', $item2);
            
            $list_prop[$id_prop] = $nama_propinsi;
        }
        $this->template->list_provinsi = $list_prop;

        $this->template->list_kabupaten = array(
            'Pilih Kabupaten :'  
        );

        $this->template->list_kecamatan = array(
            'Pilih Kecamatan :'  
        );

        $this->template->list_kelurahan = array(
            'Pilih Kelurahan :'  
        );
        ################################################

            // $this->load->model('pages/page_m');
            // $this->template->pages = array_for_select($this->page_m->get_page_tree(), 'id', 'title');
    }

    function list_daerah($list, $id) {
        $data['jenis_list'] = $list;
        $this->load->library('curl');
        $this->load->library('xml_parsing_win');

        $base_url_websevices = Settings::get('pengaduan_online_webservice');

        if ($list == 1 || $list == 10) {
            $url = $this->curl->simple_get("$base_url_websevices/api/kabupaten/id_prop/$id");
            $news_items = $this->xml_parsing_win->element_set('item', $url);
            foreach ($news_items as $item) {
                $id = $this->xml_parsing_win->value_in('id', $item);
                $nama = $this->xml_parsing_win->value_in('nama_kabupaten', $item);

                $item_array[] = array(
                    'id' => $id,
                    'n_kabupaten' => $nama,
                );
            }
            $data['list'] = $item_array;
            //$data['list'] = $this->tr_kabupaten->get_result($id);
        } elseif ($list == 2 || $list == 20) {
            $url = $this->curl->simple_get("$base_url_websevices/api/kecamatan/id_kab/$id");
            $news_items = $this->xml_parsing_win->element_set('item', $url);
            foreach ($news_items as $item) {
                $id = $this->xml_parsing_win->value_in('id', $item);
                $nama = $this->xml_parsing_win->value_in('nama_kecamatan', $item);

                $item_array[] = array(
                    'id' => $id,
                    'nama' => $nama,
                );
            }
            $data['list'] = $item_array;

            //$data['list'] = $this->tr_kecamatan->get_result($id);
        } elseif ($list == 3 || $list == 30) {
            $url = $this->curl->simple_get("$base_url_websevices/api/kelurahan/id_kec//$id");
            $news_items = $this->xml_parsing_win->element_set('item', $url);
            foreach ($news_items as $item) {
                $id = $this->xml_parsing_win->value_in('id', $item);
                $nama = $this->xml_parsing_win->value_in('nama_kelurahan', $item);

                $item_array[] = array(
                    'id' => $id,
                    'nama' => $nama,
                );
            }
            $data['list'] = $item_array;

            //$data['list'] = $this->tr_keluarahan->get_result($id);
        }
        $this->load->view('list_daerah_web_services', $data);
        //$this->load->view('list_daerah', $data);
    }
    
    private function get_latest_urut(){
        $sql="SELECT MAX(urut) as urut FROM default_pengaduan_online";
        $query=$this->db->query($sql);
        return $query->row();
    }
    
  }

/* End of file perizinan_online.php */