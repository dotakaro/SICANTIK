<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of penjadwalan
 *
 * @author Eva
 */
class Permohonan extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->permohonan = new tmpermohonan();
        $this->perizinan = new trperizinan();
        $this->pemohon = new tmpemohon();
        $this->perizinanproperty = new trperizinan_trproperty();
        $this->jenispermohonan = new trjenis_permohonan();
        $this->perusahaan = new tmperusahaan();
        $this->propertyjenis = new tmproperty_jenisperizinan();
    }

    public function index() {
        $permohonan = new tmpermohonan();
        $permohonan->tmpemohon->get();
        
        $permohonan->trperizinan->group_by('n_perizinan','ASC')->get();
 
        $data['nopendaftaran']  = "";
        $data['namapemohon']  = "";
        $data['maksudpemohon']  = "";
        $data['save_method'] = "save";
        $data['id'] = "";
       
        $data['list'] = $permohonan->get();
        $data['list_izin'] = $permohonan->get();
        $data['list_jenispermohonan'] = $permohonan->get();
       
        $data['list_pemohon'] = $permohonan->get();
        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#permohonan').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Data Perizinan";
        $this->template->build('list', $this->session_info);
    }

    public function view() {
        $permohonan = new tmpermohonan();
        
       
        $permohonan->trperizinan->where('id',$this->input->post( 'jenis_izin'))->get();
        
        $this->propertyjenis->where('pendaftaran_id','nopendaftaran')->get();
        $permohonan->tmpemohon->get();
        
        
        $data['id'] = $this->permohonan->id_pemohon;
        $data['jenislayanan']  = $this->perizinan->n_perizinan;
        $data['nopendaftaran']  = $this->propertyjenis->pendaftaran_id;
        $data['namapemohon']  = $this->pemohon->n_pemohon;
        $data['alamatpemohon'] = $this->pemohon->a_pemohon;
        $data['maksudpemohon']  = $this->permohonan->n_permohonan;
        $data['v_property']=$this->propertyjenis->v_property;

        $data['list'] = $permohonan->where('pendaftaran_id',$this->input->post('nopendaftaran'))->get();
        $data['list_izin'] = $permohonan->get();
        $data['list_jenispermohonan'] = $permohonan->get();

        $data['list_pemohon'] = $permohonan->get();
        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#permohonan').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Data Izin ".$this->perizinan->n_perizinan;
        $this->template->build('list', $this->session_info);
    }

    public function create() {
        $data['namapemilik']  = "";
        $data['alamatpemilik']  = "";
        $data['luastanah']  = "";
        $data['lokasitanah']  = "";
        $data['luasbangunan']  = "";
        $data['fungsibangunan']  = "";
        $data['strukturbangunan']  = "";
        $data['save_method'] = "save";
        $data['id'] = "";

        $this->load->vars($data);
        $this->session_info['page_name'] = "Entry Pendataan";
        $this->template->build('edit', $this->session_info);
    }
    

    /*
     * edit is a method to show page for updating data
     */
  

     public function detail2($idjenisizin = NULL, $idpemohon=NULL, $idpendaftaran=NULL) {
        $permohonan = new tmpermohonan();

        $permohonan->where('id',$idjenisizin)->get();

        $permohonan->trperizinan->where('id', $idjenisizin)->get();
        $permohonan->tmpemohon->where('id', $idpemohon)->get();
        $permohonan->tmperusahaan->where('id', $idpemohon)->get();
        
        $perizinan = new trperizinan();
        $perizinan->trperizinan_trproperty->where('c_retribusi_id', 1);
        $perizinan->trperizinan_trproperty->where('trperizinan_id',$idjenisizin);
        $perizinan->trperizinan_trproperty->get();
        $perizinan->trproperty->get();

        

        $data['list_form'] = $permohonan->trperizinan->trproperty->get();
        $data['id'] = $permohonan->id;
        $data['jenislayanan']  = $permohonan->trperizinan->n_perizinan;
        $data['nopendaftaran'] = $permohonan->pendaftaran_id;
        $data['namapemohon']   = $permohonan->tmpemohon->n_pemohon;
        $data['alamatpemohon'] = $permohonan->tmpemohon->a_pemohon;
        $data['maksudpemohon'] = $permohonan->n_permohonan;
        $data['namaperusahaan']= $permohonan->tmperusahaan->n_perusahaan;


        $this->load->vars($data);
        $this->session_info['page_name'] = "Entry Pendataan";
        $this->template->build('entry', $this->session_info);

    }
    /**
     * Not yet in use, because of some limit of dataTables
     */
    public function datalist() {

        $this->permohonan->get();
        $this->permohonan->set_json_content_type();
        echo $this->permohonan->json_for_data_table();

    }
   
    /*
     * Save and update for manipulating data.
     */
    public function save() {

        $permohonan = new tmpermohonan();
        $propertyjenis = new tmproperty_jenisperizinan();
        $permohonan->tmproperty_jenisperizinan->get();
        
        $permohonan->where('id',$this->input->post('id'))->get();
        $pendaftaran_id=$propertyjenis->pendaftaran_id;
        
   
         
        if( $permohonan->save('$propertyjenis')) {
              redirect('permohonan');
        } else {
            redirect('permohonan/detail2');

        }
    }

    public function update() {
         $perizinanproperty = new trperizinan_trproperty();
          
         $perizinanproperty->where('trperizinan_id',$this->input->post('id'))->get();

         $jumlahproperty = $perizinanproperty->trproperty_id;
         $jumlah_len = count($jumlahproperty);

         for($i=0;$i <$jumlah_len;$i++){
                 $update = $this->propertyjenis
                    
                    ->where('pendaftaran_id', $this->input->post('nopendaftaran'))
                    ->where('property_id',1)
                    ->update('v_property',$this->input->post('property'));
                    }
 
       if($update)
            redirect('permohonan');
    }
 public function delete($id = NULL) {
        $this->permohonan->where('id_pemohon', $id)->get();
        if($this->permohonan->delete()) {
            redirect('perizinan');
        }
 }
    /*
     * Method for validating
     */

}

// This is the end of role class
