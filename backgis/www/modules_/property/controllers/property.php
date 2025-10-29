<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of property
 *
 * @author Eva
 */
class property extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();

        $this->property = new trproperty();
        $this->perizinan = new trperizinan();
        $this->koefesien = new trkoefesientarifretribusi();
        $this->retribusi = new trretribusi();
        $this->izin_property = new trperizinan_trproperty();
        $this->koefesienproperty = new trkoefesientarifretribusi_trproperty();
    }

    public function index() {
        $data['jenis_izin'] = "";
        $data['list'] =  $this->property->get();
        $data['list_izin'] = $this->perizinan->where('c_tarif',1)->get();
        $data['temporer']='1';
        $data['id']='1';
        $retribusi = new trretribusi();
        

        $data['list_retribusi'] = $retribusi->get();
        $data['kategori'] = "1";
        $data['index_k'] = "1";
        $data['retribusi'] = "1";
        $data['tot'] = $data['retribusi']*$data['index_k'];
        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#property').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                        $('.page-help').each(function() {
                                var \$link = $(this);
                                var \$dialog = $('<div></div>')
                                        .load(\$link.attr('href') + ' #content')
                                        .dialog({
                                                autoOpen: false,
                                                modal: true,
                                                show:'blind',
                                                hide:'blind',
                                                title: \$link.attr('title'),
                                                width: 500,
                                                height: 300,
                                                buttons: {
                                                    'Tutup': function() {
                                                        $(this).dialog('close');
                                                    }
                                                }
                                        });

                                \$link.click(function() {
                                        \$dialog.dialog('open');
                                        return false;
                                });
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Simulasi Tarif Retribusi Perizinan";
        $this->template->build('list', $this->session_info);
    }

    public function simulasi() {

         $propertyizin = new trperizinan_trproperty();
         $perizinan = new trperizinan();
      
         $perizinan->where('id',$this->input->post('jenis_izin'))->get();
         $retribusi = new trretribusi();
                  
          
         $data['list_retribusi'] = $retribusi->get();
         $data['jenis_izin'] =$this->input->post('jenis_izin');
         $data['kategori'] = $this->input->post('kategori');//$property->trkoefesientarifretribusi->kategori;
         $data['index_k'] =$this->koefesien->index_kategori;
         $data['retribusi'] =$this->input->post('retribusi_terhitung');// $property->trretribusi->v_retribusi;
         $data['tot'] = $data['retribusi']*$data['index_k'];
         $data['id'] =$this->input->post('id');
         $data['temporer'] ='1';
         $data['list'] = $perizinan->trproperty->where('c_retribusi_id', '1')->get();
         $data['list_izin'] = $perizinan->where('c_tarif',1)->get();


        
         $this->load->vars($data);

         $js =  "
                $(document).ready(function() {
                        oTable = $('#property').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                        $('.page-help').each(function() {
                                var \$link = $(this);
                                var \$dialog = $('<div></div>')
                                        .load(\$link.attr('href') + ' #content')
                                        .dialog({
                                                autoOpen: false,
                                                modal: true,
                                                show:'blind',
                                                hide:'blind',
                                                title: \$link.attr('title'),
                                                width: 500,
                                                height: 300,
                                                buttons: {
                                                    'Tutup': function() {
                                                        $(this).dialog('close');
                                                    }
                                                }
                                        });

                                \$link.click(function() {
                                        \$dialog.dialog('open');
                                        return false;
                                });
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Simulasi Tarif Retribusi Perizinan".$this->input->post('jenis_izin');
        $this->template->build('list', $this->session_info);
    }

    public function panel1($id = NULL, $jenis_izin = NULL) {

        $property = new trproperty();

        $property->where('id',$id);

        $property->trkoefesientarifretribusi_trproperty->where('trproperty_id',$id);
        $property->trkoefesientarifretribusi_trproperty->where('property_id',$jenis_izin);
        $property->trkoefesientarifretribusi_trproperty->get();
                   
        $property->trperizinan_trproperty->where('id',$jenis_izin)->get();
        $property->trkoefesientarifretribusi->where('property_id',$id)->get();
        $property->trperizinan->get();
        $property->get();
        
        $data['retribusi'] =$this->input->post('retribusi_terhitung');
        $data['id'] = $id;
        
        $data['jenis_izin'] = $jenis_izin;
       
        $data['namaproperty']=$property->n_property;
        $data['list_kategori'] = $property->trkoefesientarifretribusi->get();
      
        $js =  "
                $(document).ready(function() {
                        oTable = $('#property').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                        $('.page-help').each(function() {
                                var \$link = $(this);
                                var \$dialog = $('<div></div>')
                                        .load(\$link.attr('href') + ' #content')
                                        .dialog({
                                                autoOpen: false,
                                                modal: true,
                                                show:'blind',
                                                hide:'blind',
                                                title: \$link.attr('title'),
                                                width: 500,
                                                height: 300,
                                                buttons: {
                                                    'Tutup': function() {
                                                        $(this).dialog('close');
                                                    },
                                                    'Simpan' : function() {
                                                        $(this).dialog('close');
                                                    }
                                                }
                                        });

                                \$link.click(function() {
                                        \$dialog.dialog('open');
                                        return false;
                                });
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);

            
        $this->load->vars($data);
        $this->session_info['page_name'] = "Kategori ".$this->property->n_property;

        $this->template->build('panel1', $this->session_info);
        
    }

    public function hitung() {
         $propertyizin = new trperizinan_trproperty();
         $permohonan   = new tmpermohonan();
         $perizinan    = new trperizinan();

         $permohonan->where('id',$this->input->post('jenis_izin'))->get();
         $permohonan->trperizinan->where('id',$this->input->post('jenis_izin'))->get();

         $koefesien = new trkoefesientarifretribusi();
         $koefesien->where('kategori',$this->input->post('kategori'));
         $koefesien->where('property_id',$this->input->post('id'));
         $koefesien->get();

         $retribusi = new trretribusi();
         $data['temporer'] ='1';
         $data['list_retribusi'] = $retribusi->get();
         $data['jenis_izin'] =$this->input->post('jenis_izin');
         $data['kategori'] = $this->input->post('kategori');//$property->trkoefesientarifretribusi->kategori;
         $data['index_k'] =$koefesien->index_kategori;
         $data['retribusi'] =$this->input->post('retribusi_terhitung');// $property->trretribusi->v_retribusi;
         $data['tot'] = $data['retribusi']*$data['index_k'];
         $data['id'] =$this->input->post('id');
       //  $xx=$this->input->post('kategori').$this->input->post('id');
        // $data['target']=$koefesien->kategori->where('kategori',$this->input->post($xx));
        
         $data['list'] = $permohonan->trperizinan->trproperty->where('c_retribusi_id', '1')->get();
         $data['list_izin'] = $permohonan->trperizinan->where('c_tarif',1)->get();
        
         $this->load->vars($data);

         $js =  "
                $(document).ready(function() {
                        oTable = $('#property').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                        $('.page-help').each(function() {
                                var \$link = $(this);
                                var \$dialog = $('<div></div>')
                                        .load(\$link.attr('href') + ' #content')
                                        .dialog({
                                                autoOpen: false,
                                                modal: true,
                                                show:'blind',
                                                hide:'blind',
                                                title: \$link.attr('title'),
                                                width: 500,
                                                height: 300,
                                                buttons: {
                                                    'Tutup': function() {
                                                        $(this).dialog('close');
                                                    }
                                                }
                                        });

                                \$link.click(function() {
                                        \$dialog.dialog('open');
                                        return false;
                                });
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Simulasi Tarif Retribusi Perizinan".$this->input->post('jenis_izin');
        $this->template->build('list', $this->session_info);
    }

    /**
     * Not yet in use, because of some limit of dataTables
     */
    public function datalist() {

        $this->property->get();
        $this->property->set_json_content_type();
        echo $this->property->json_for_data_table();
    }

    /*
     * Save and update for manipulating data.
     */

    public function save() {


        $property = new trproperty();
        $property->perizinan_id = $this->input->post('perizinan_id');

        $koefesien = new trkoefesientarifretribusi();
        $koefesien->where('perizinan_id', $this->input->post('perizinan_id'))->get();

        if ($property->save(array($koefesien))) {
            redirect('property/property/simulasi');
        }
    }

    public function update() {

    }

    public function delete($perizinan_id = NULL) {
        $this->property->where('id', $perizinan_id)->get();
        if ($this->property->delete()) {
            redirect('property/property');
        }
    }

}
