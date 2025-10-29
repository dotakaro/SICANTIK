<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk memanage File-file yang dapat didownload oleh Visitor Website
 *
 * @author      Indra
 * @website     http://indra.com
 * @package     
 * @subpackage  
 * @copyright   MIT
 */
class daftar_layanan extends Public_Controller
{

    /**
     * The constructor
     * @access public
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
      $this->lang->load('daftar_layanan');
      $this->load->model('daftar_layanan_m');
      $this->template->append_css('module::daftar_layanan.css');
    }
     /**
     * List all daftar_layanans
     *
     *
     * @access  public
     * @return  void
     */
     public function index()
     {
	 	### Ambil data dari webservice CURL Backoffice##
        $this->load->library('curl');
        $this->load->library('xml_parsing_win');
        
        $base_url_websevices = Settings::get('daftar_layanan_webservice');
        
        //List Perizinan
        $url = $this->curl->simple_get("$base_url_websevices/api/jenisperizinanlist");
        $news_items = $this->xml_parsing_win->element_set('item', $url);
        $item_array = array();
        foreach ($news_items as $item) {
            $id = $this->xml_parsing_win->value_in('id', $item);
            $jenis = $this->xml_parsing_win->value_in('jenis_perizinan', $item);
            $v_hari = $this->xml_parsing_win->value_in('v_hari', $item);

            $item_array[] = array(
                'id' => $id,
                'jenis_perizinan' => $jenis,
                'v_hari' => $v_hari
            );
        }
        $this->template->list_izin =  $item_array;
//		echo "<pre>";print_r($item_array);exit();
        $data['list'] = $item_array;
		##############################################
		
      
      // Build the page
      $this->template->title($this->module_details['name'])
      ->build('index', $data);
    }
	
	
    function syarat($id = NULL) {

        $this->load->library('curl');
        $this->load->library('xml_parsing_win');
        $id_jenis_izin = $id;
        $base_url_websevices = Settings::get('daftar_layanan_webservice');
        $url_nama = $this->curl->simple_get("$base_url_websevices/api/jenisnama/id/$id");
        $nama_jenisperizinan = $this->xml_parsing_win->element_set('item', $url_nama);
        foreach ($nama_jenisperizinan as $item) {
            $nama = $this->xml_parsing_win->value_in('naam', $item);
        }

        $data['nama_jenis'] = $nama;

        $url = $this->curl->simple_get("$base_url_websevices/api/syaratPerizinan/perizinan/$id");

        $syaratxml = $this->xml_parsing_win->element_set('item', $url);
        if ($syaratxml == NULL) {
            $array[] = array(
                'id' => '',
                'syarat_perizinan' => 'Belum Tersedia Syarat Perizinan',
            );
        } else {
            foreach ($syaratxml as $item) {

                $id = $this->xml_parsing_win->value_in('id', $item);
                $syarat = $this->xml_parsing_win->value_in('syarat_perizinan', $item);

                $array[] = array(
                    'id' => $id,
                    'syarat_perizinan' => $syarat,
                );
            }
        }
        
        //Ambil Daftar Download jika ada
        $data['downloads'] = $this->daftar_layanan_m->get_download_list($id_jenis_izin);
        
        $data['list'] = $array;
        $data['isi'] = 'isi_jenis_perizinan_syarat';
        $data['title'] = 'Daftar Jenis Perizinan';
		$this->template->title($this->module_details['name'])
	      ->build('syarat', $data);
    }
	
	public function daftar_download($offset=0){
        $limit = 20;
        // bind the information to a key
        $query_data = (array)$this->daftar_layanan_m->get_all_dasar_hukum($limit,$offset);

        $data['daftar_layanan'] = $query_data['rows'];
        $data['num_results'] = $query_data['num_rows'];

        //pagination
        $this->load->library('pagination');
        $config=array();
        $config['base_url']=site_url('daftar_layanan/daftar_download');
        $config['total_rows']= $data['num_results'];
        $config['per_page']=$limit;
        $config['uri_segment']=3;//mengeset tempat offset pada setiap link yang digenerate
        $this->pagination->initialize($config);
        $data['pagination']=$this->pagination->create_links();

        // Build the page
        $this->template->title($this->module_details['name'])
            ->build('daftar_download', $data);
	}

    public function daftar_formulir($offset=0){
        $limit = 20;
        // bind the information to a key
        $query_data = (array)$this->daftar_layanan_m->get_all_formulir($limit,$offset);

        $data['daftar_layanan'] = $query_data['rows'];
        $data['num_results'] = $query_data['num_rows'];

        //pagination
        $this->load->library('pagination');
        $config=array();
        $config['base_url']=site_url('daftar_layanan/daftar_formulir');
        $config['total_rows']= $data['num_results'];
        $config['per_page']=$limit;
        $config['uri_segment']=3;//mengeset tempat offset pada setiap link yang digenerate
        $this->pagination->initialize($config);
        $data['pagination']=$this->pagination->create_links();

        // Build the page
        $this->template->title($this->module_details['name'])
            ->build('daftar_formulir', $data);
    }

  }

/* End of file daftar_layanan.php */