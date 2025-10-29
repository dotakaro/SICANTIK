<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *  @author  Yogi & Zulva
 *
 */
class Koefisientarif extends WRC_AdminCont {

  public function __construct() {
        parent::__construct();
        $this->koef = new trkoefesientarifretribusi();
        $this->koeflev1 = new trkoefisienretribusilev1();
        $this->koeflev2 = new trkoefisienretribusilev2();
        $this->perizinan = new trperizinan();
        $this->property = new trproperty();
        $this->permohonan = new tmpermohonan();
        $this->perprop = new trperizinan_trproperty();
        $this->retribusi = new trretribusi();
        $this->koefprop = new trkoefesientarifretribusi_trproperty();
        $this->koeflevel1 = new trkoefisienretribusilev1();
        $this->koeflevel2 = new trkoefisienretribusilev2();
        $this->koefisientarif = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->koefisientarif = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '1') {
                $enabled = TRUE;
                $this->koefisientarif = new user_auth();
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
        
      
       $data['list'] = $this->perizinan->where('c_tarif',1)->get();
       // $data['list'] = $this->perizinan->where_related('trperizinan_trretribusi','trperizinan_id')->get();
       $data['list_retribusi'] = $this->retribusi->get();
       $data['list_property'] = $this->property->get();
       $this->load->vars($data);


      $js =  "
                $(document).ready(function() {
                         oTable = $('#koefisientarif').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                        $('#izin_jenis').change(function(){
                                $('#show_property').fadeOut();
                                $.post('". base_url() ."perizinan/koefisientarif/izin_jenis', {
                                   izin_id : $('#izin_jenis').val()
                               
                                }, function(response){
                                    setTimeout(\"finishAjax('show_property', '\"+escape(response)+\"')\", 400);
                        Syafinatul        });
                                return false;
                        });
                          $('#property').change(function(){
                                $('#show_koefesien').fadeOut();
                                $.post('". base_url() ."perizinan/koefisientarif/property', {
                                   property_id : $('#property').val()
                                }, function(response){
                                    setTimeout(\"finishAjax('show_koefesien', '\"+escape(response)+\"')\", 400);
                                });
                                return false;
                        });
                      
                });

                function finishAjax(id, response){
                  $('#'+id).html(unescape(response));
                  $('#'+id).fadeIn();
                }
               
            ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Koefisien Tarif";
        $this->template->build('koefisien_new', $this->session_info);
    }

     public function view($idizin=NULL) {
        $izinprop = new trperizinan_trproperty();
        $izin = $this->perizinan->get_by_id($idizin);
        
        $izinprop->where('trperizinan_id', $idizin);
        $izinprop->where('c_retribusi_id','1');
        $izinprop->order_by('c_parent', 'asc');
        $izinprop->order_by('c_order', 'asc');
       
        $data['list'] = $izinprop->get();
      
        $data['jenis_izin'] = $izin;
     
        $this->load->vars($data);



      $js =  "
                $(document).ready(function() {
                         oTable = $('#koefisientarif').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                        $('#izin_jenis').change(function(){
                                $('#show_property').fadeOut();
                                $.post('". base_url() ."perizinan/koefisientarif/izin_jenis', {
                                   izin_id : $('#izin_jenis').val()

                                }, function(response){
                                    setTimeout(\"finishAjax('show_property', '\"+escape(response)+\"')\", 400);
                        Syafinatul        });
                                return false;
                        });
                          $('#property').change(function(){
                                $('#show_koefesien').fadeOut();
                                $.post('". base_url() ."perizinan/koefisientarif/property', {
                                   property_id : $('#property').val()
                                }, function(response){
                                    setTimeout(\"finishAjax('show_koefesien', '\"+escape(response)+\"')\", 400);
                                });
                                return false;
                        });

                });

                function finishAjax(id, response){
                  $('#'+id).html(unescape(response));
                  $('#'+id).fadeIn();
                }


            ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting  Koefisien Tarif";
        $this->template->build('kproperty_new', $this->session_info);
    }

     public function detail($idizin=NULL,$idproperty=NULL) {
        
        $izin = $this->perizinan->get_by_id($idizin);

        $izin->trproperty->where('id',$idproperty)->get();

        $data['list'] = $izin->trproperty->trkoefesientarifretribusi->get();

        $data['jenis_izin'] = $izin;
        $data['id_property'] = $izin->trproperty->id;
        $data['nama_property'] = $izin->trproperty->n_property;

        $this->load->vars($data);



      $js =  "  function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                         oTable = $('#koefisientarif').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                        $('#izin_jenis').change(function(){
                                $('#show_property').fadeOut();
                                $.post('". base_url() ."perizinan/koefisientarif/izin_jenis', {
                                   izin_id : $('#izin_jenis').val()

                                }, function(response){
                                    setTimeout(\"finishAjax('show_property', '\"+escape(response)+\"')\", 400);
                        Syafinatul        });
                                return false;
                        });
                          $('#property').change(function(){
                                $('#show_koefesien').fadeOut();
                                $.post('". base_url() ."perizinan/koefisientarif/property', {
                                   property_id : $('#property').val()
                                }, function(response){
                                    setTimeout(\"finishAjax('show_koefesien', '\"+escape(response)+\"')\", 400);
                                });
                                return false;
                        });

                });

                function finishAjax(id, response){
                  $('#'+id).html(unescape(response));
                  $('#'+id).fadeIn();
                }


            ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting  Koefisien Tarif";
        $this->template->build('kkoefisien_new', $this->session_info);
    }
    public function create($idizin=NULL, $idproperty=NULL) {
       
        $izin = $this->perizinan->get_by_id($idizin);
        $property = $this->property->get_by_id($idproperty);
       
         $js_date = "
              $(document).ready(function() {
                    $('#form').validate();
                } );
            $(function() {
                $(\".tarif\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
            ";
        $this->template->set_metadata_javascript($js_date);
        $retribusi = new trperizinan_trproperty();
        $retribusi->where('trperizinan_id', $idizin);
        $retribusi->where('trproperty_id', $idproperty);
        $retribusi->get();
        $settings = new settings();
        $settings->where('name', 'app_enum_satuan')->get();
        $satuan = $settings->value;
        $data['satuan_sel'] = $retribusi->satuan;
        $data['satuan'] = unserialize($satuan);
        $data['jenis_izin'] = $izin;
        $data['property']  = $property;
        $data['id'] ="";
        $data['nproperty']  = $property->n_property;
        $data['kategori']  = "";
        $data['index_kategori']   = "";
        $data['d_mulai_efektif']  = "";
        $data['d_selesai']= "";
        $data['i_entry']  = "";
        $data['d_entry']  = "";
        $data['harga']  = "";
        $data['save_method'] = "save";
       

        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Koefisien Tarif";
        $this->template->build('create', $this->session_info);
    }

    public function createlev1($idizin=NULL, $idproperty=NULL, $idkoef=NULL) {
        $izin = $this->perizinan->get_by_id($idizin);
        $izin->trproperty->where('id',$idproperty)->get();

        $property = $this->property->get_by_id($idproperty);
        $data['id_property'] = $izin->trproperty->id;
        $data['dkoef'] = $this->koef->where('id', $idkoef)->get();

         $js_date = "
         $(document).ready(function() {
                $('#form').validate();
            } );
            $(function() {
                $(\".tarif\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
            ";
        $this->template->set_metadata_javascript($js_date);
        $retribusi = new trperizinan_trproperty();
        $retribusi->where('trperizinan_id', $idizin);
        $retribusi->where('trproperty_id', $idproperty);
        $retribusi->get();
        $settings = new settings();
        $settings->where('name', 'app_enum_satuan')->get();
        $satuan = $settings->value;
        $data['satuan_sel'] = $retribusi->satuan;
        $data['satuan'] = unserialize($satuan);
        $data['jenis_izin'] = $izin;
        $data['property']  = $property;
        $data['id'] ="";
        $data['nproperty']  = $property->n_property;
        $data['kategori']  = "";
        $data['index_kategori']   = "";
        $data['v_index_kategori']   = "";
        $data['d_mulai_efektif']  = "";
        $data['d_selesai']= "";
        $data['i_entry']  = "";
        $data['d_entry']  = "";
        $data['save_method'] = "savelev1";


        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Koefisien Tarif";
        $this->template->build('createlev1', $this->session_info);
    }

    public function createlev2($idizin=NULL, $idproperty=NULL, $idkoef=NULL, $idkoeflev1=NULL) {
        $izin = $this->perizinan->get_by_id($idizin);
        $izin->trproperty->where('id',$idproperty)->get();

        $property = $this->property->get_by_id($idproperty);
        $data['id_property'] = $izin->trproperty->id;
        $data['dkoef'] = $this->koef->where('id', $idkoef)->get();
        $data['koeflev1'] = $this->koeflevel1->where('id', $idkoeflev1)->get();

         $js_date = "
               $(document).ready(function() {
                $('#form').validate();
            } );
            $(function() {
                $(\".tarif\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
            ";
        $this->template->set_metadata_javascript($js_date);
         $retribusi = new trperizinan_trproperty();
        $retribusi->where('trperizinan_id', $idizin);
        $retribusi->where('trproperty_id', $idproperty);
        $retribusi->get();
        $settings = new settings();
        $settings->where('name', 'app_enum_satuan')->get();
        $satuan = $settings->value;
        $data['satuan_sel'] = $retribusi->satuan;
         $data['satuan'] = unserialize($satuan);
        $data['jenis_izin'] = $izin;
        $data['property']  = $property;
        $data['id'] ="";
        $data['nproperty']  = $property->n_property;
        $data['kategori']  = "";
        $data['index_kategori']   = "";
        $data['d_mulai_efektif']  = "";
        $data['d_selesai']= "";
        $data['i_entry']  = "";
        $data['d_entry']  = "";
        $data['save_method'] = "savelev2";


        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Koefisien Tarif";
        $this->template->build('createlev2', $this->session_info);
    }

     public function edit($idizin = NULL,$idproperty=NULL,$idkoef=NULL) {
        $izin = $this->perizinan->get_by_id($idizin);
        $izin->trproperty->where('id',$idproperty)->get();
        
        $property = $this->property->get_by_id($idproperty);
        $koeftarif = $this->koef->get_by_id($idkoef);
        $data['id_property'] = $izin->trproperty->id;
        $data['dkoef'] = $this->koef->where('id', $idkoef)->get();
        $propkoefta = new trkoefesientarifretribusi_trproperty();
        $propkoeftalev1 = new trkoefesientarifretribusi_trkoefisienretribusilev1();
        $propkoefta->where('trkoefesientarifretribusi_id', $idkoef);
        $propkoeftalev1->where('trkoefesientarifretribusi_id', $idkoef)->order_by('id', 'ASC');
        $data['koeflev1'] = $this->koeflevel1->get();
        $data['list'] = $propkoeftalev1->get();
    
    
        $this->koef->where('id', $idkoef);
        $this->koef->get();
        $data['jenis_izin'] = $izin;
        $data['property'] = $property;
        $data['id'] = $this->koef->id;
        $data['perizinan_id']  = $this->koef->perizinan_id;
        $data['property_id']  = $idproperty;
        $data['kategori']  = $this->koef->kategori;
        $data['harga']  = $this->koef->harga;
        $data['index_kategori']  = $this->koef->index_kategori;
        $data['d_mulai_efektif']  = $this->koef->d_mulai_efektif;
        $data['d_selesai']  = $this->koef->d_selesai;
        $data['i_entry']  = $this->koef->i_entry;
        $data['d_entry']  = $this->koef->d_entry;
        $data['save_method'] = "update";

         $js_date = "
              function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
         $(document).ready(function() {
                    oTable = $('#koefisientarif').dataTable({
                            \"bJQueryUI\": true,
                            \"sPaginationType\": \"full_numbers\"
                    });
            } );
            $(function() {
                $(\".tarif\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
            ";
        $this->template->set_metadata_javascript($js_date);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Koefisien Tarif";
        $this->template->build('edit_koefisien', $this->session_info);

    }

     public function edit1($idizin = NULL,$idproperty=NULL,$idkoef=NULL,$idkoeflev1=NULL) {
        $izin = $this->perizinan->get_by_id($idizin);
        $property = $this->property->get_by_id($idproperty);

        $izin = $this->perizinan->get_by_id($idizin);
        $izin->trproperty->where('id',$idproperty)->get();
        $data['id_property'] = $izin->trproperty->id;
        $propkoeftalev1 = new trkoefesientarifretribusi_trkoefisienretribusilev1();
        $propkoeftalev1->where('trkoefesientarifretribusi_id', $idkoef)->order_by('id', 'ASC');
        $propkoeftalev2 = new trkoefisienretribusilev1_trkoefisienretribusilev2();
        $propkoeftalev2->where('trkoefisienretribusilev1_id', $idkoeflev1)->order_by('id', 'ASC');
        $data['dkoef'] = $this->koef->where('id', $idkoef)->get();
        $data['koeflev1'] = $this->koeflevel1->where('id', $idkoeflev1)->get();
        $data['koeflev2'] = $this->koeflevel2->get();
        $data['list'] = $propkoeftalev2->get();

        $this->koeflevel1->where('id', $idkoeflev1);
        $this->koeflevel1->get();
        $this->koef->where('id', $idkoef);
        $this->koef->get();
        $data['jenis_izin'] = $izin;
        $data['property'] = $property;
        $data['id'] = $this->koef->id;
        $data['perizinan_id']  = $this->koef->perizinan_id;
        $data['property_id']  = $idproperty;
        $data['kategori']  = $this->koef->kategori;
        $data['index_kategori']  = $this->koef->index_kategori;
        $data['d_mulai_efektif']  = $this->koef->d_mulai_efektif;
        $data['d_selesai']  = $this->koef->d_selesai;
        $data['i_entry']  = $this->koef->i_entry;
        $data['d_entry']  = $this->koef->d_entry;
        $data['save_method'] = "update";

         $js_date = "
              function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
         $(document).ready(function() {
                    oTable = $('#koefisientarif').dataTable({
                            \"bJQueryUI\": true,
                            \"sPaginationType\": \"full_numbers\"
                    });
            } );
            $(function() {
                $(\".tarif\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
            ";
        $this->template->set_metadata_javascript($js_date);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Koefisien Tarif";
        $this->template->build('edit1_koefisien', $this->session_info);

    }

    public function editLev1($idizin = NULL,$idproperty=NULL,$idkoef=NULL) {
        $izin = $this->perizinan->get_by_id($idizin);
        $property = $this->property->get_by_id($idproperty);
        $data['dkoef'] = $this->koef->where('id', $idkoef)->get();

        $this->koef->where('id', $idkoef);
        $this->koef->get();
        $retribusi = new trperizinan_trproperty();
        $retribusi->where('trperizinan_id', $idizin);
        $retribusi->where('trproperty_id', $idproperty);
        $retribusi->get();
        $settings = new settings();
        $settings->where('name', 'app_enum_satuan')->get();
        $satuan = $settings->value;
        $data['satuan_sel'] = $retribusi->satuan;
        $data['satuan_key'] = $this->koef->satuan;
        $data['satuan'] = unserialize($satuan);
        $data['jenis_izin'] = $izin;
        $data['property'] = $property;
        $data['id'] = $this->koef->id;
        $data['perizinan_id']  = $this->koef->perizinan_id;
        $data['property_id']  = $idproperty;
        $data['kategori']  = $this->koef->kategori;
        $data['index_kategori']  = $this->koef->index_kategori;
        $data['d_mulai_efektif']  = $this->koef->d_mulai_efektif;
        $data['d_selesai']  = $this->koef->d_selesai;
        $data['i_entry']  = $this->koef->i_entry;
        $data['d_entry']  = $this->koef->d_entry;
        $data['save_method'] = "update";

         $js_date = "
            $(function() {
             $('#form').validate();
                $(\".tarif\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
            ";
        $this->template->set_metadata_javascript($js_date);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Koefisien Tarif";
        $this->template->build('editlev1', $this->session_info);

    }

    public function editLev2($idizin = NULL,$idproperty=NULL,$idkoef=NULL, $idkoeflev1=NULL) {
        $izin = $this->perizinan->get_by_id($idizin);
        $property = $this->property->get_by_id($idproperty);
        $data['dkoef'] = $this->koef->where('id', $idkoef)->get();
        $data['koeflev1'] = $this->koeflevel1->where('id', $idkoeflev1)->get();


        $this->koef->where('id', $idkoef);
        $this->koef->get();
        $retribusi = new trperizinan_trproperty();
        $retribusi->where('trperizinan_id', $idizin);
        $retribusi->where('trproperty_id', $idproperty);
        $retribusi->get();
        $settings = new settings();
        $settings->where('name', 'app_enum_satuan')->get();
        $satuan = $settings->value;
        $data['satuan_sel'] = $retribusi->satuan;
        $data['satuan_key'] = $this->koeflevel1->satuan;
        $data['satuan'] = unserialize($satuan);
        $data['jenis_izin'] = $izin;
        $data['property'] = $property;
        $data['id'] = $this->koef->id;
        $data['perizinan_id']  = $this->koef->perizinan_id;
        $data['property_id']  = $idproperty;
        $data['kategori']  = $this->koeflevel1->kategori;
        $data['index_kategori']  = $this->koeflevel1->index_kategori;
        $data['v_index_kategori']  = $this->koeflevel1->v_index_kategori;
        $data['i_entry']  = $this->koeflevel1->i_entry;
        $data['d_entry']  = $this->koeflevel1->d_entry;
        $data['save_method'] = "update1";

         $js_date = "
            $(function() {
            $('#form').validate();
                $(\".tarif\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
            ";
        $this->template->set_metadata_javascript($js_date);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Koefisien Tarif";
        $this->template->build('editlev2', $this->session_info);

    }

    public function editLev3($idizin = NULL,$idproperty=NULL,$idkoef=NULL, $idkoeflev1=NULL, $idkoeflev2=NULL) {
        $izin = $this->perizinan->get_by_id($idizin);
        $property = $this->property->get_by_id($idproperty);
        $data['dkoef'] = $this->koef->where('id', $idkoef)->get();
        $data['koeflev1'] = $this->koeflevel1->where('id', $idkoeflev1)->get();
        $data['koeflev2'] = $this->koeflevel2->where('id', $idkoeflev2)->get();


        $this->koef->where('id', $idkoef);
        $this->koef->get();
        $retribusi = new trperizinan_trproperty();
        $retribusi->where('trperizinan_id', $idizin);
        $retribusi->where('trproperty_id', $idproperty);
        $retribusi->get();
        $settings = new settings();
        $settings->where('name', 'app_enum_satuan')->get();
        $satuan = $settings->value;
        $data['satuan_sel'] = $retribusi->satuan;
        $data['satuan_key'] = $this->koeflevel2->satuan;
        $data['satuan'] = unserialize($satuan);
        $data['jenis_izin'] = $izin;
        $data['property'] = $property;
        $data['id'] = $this->koef->id;
        $data['perizinan_id']  = $this->koef->perizinan_id;
        $data['property_id']  = $idproperty;
        $data['kategori']  = $this->koeflevel2->kategori;
        $data['index_kategori']  = $this->koeflevel2->index_kategori;
        $data['i_entry']  = $this->koeflevel2->i_entry;
        $data['d_entry']  = $this->koeflevel2->d_entry;
        $data['save_method'] = "update2";

         $js_date = "
            $(function() {
            $('#form').validate();
                $(\".tarif\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
            ";
        $this->template->set_metadata_javascript($js_date);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Koefisien Tarif";
        $this->template->build('createlastlev', $this->session_info);

    }

      public function save() {
        $property = new trproperty();
        $property->get_by_id($this->input->post('property_id'));

        
        $this->koef->kategori  = $this->input->post('kategori');
        $this->koef->index_kategori  = $this->input->post('idx_kategori');
        $this->koef->d_mulai_efektif  = $this->input->post('d_mulai_efektif');
        $this->koef->d_selesai  = $this->input->post('d_selesai');
        $this->koef->i_entry  = $this->input->post('i_entry');
        $this->koef->d_entry  = $this->input->post('d_entry');
        $this->koef->satuan  = $this->input->post('satuan');
        $this->koef->harga  = "Rp ".$this->input->post('harga');

        if(! $this->koef->save($property)) {
            echo '<p>' . $this->koef->error->string . '</p>';
        } else {
            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $p = $this->db->query("call log ('Setting Perizinan','Insert koefisiensi tarif ".$this->input->post('kategori')."','".$tgl."','".$u_ser."')");

            redirect('perizinan/koefisientarif/detail/'.$this->input->post('izin_id').'/'.$this->input->post('property_id'));
        }

    }

      public function savelev1() {
        $level1 = new trkoefesientarifretribusi();
        $level1->get_by_id($this->input->post('level1_id'));

        $this->koeflev1->kategori  = $this->input->post('kategori');
        $this->koeflev1->index_kategori  = $this->input->post('idx_kategori');
        $this->koeflev1->v_index_kategori  = $this->input->post('v_index_kategori');
        $this->koeflev1->i_entry  = $this->input->post('i_entry');
        $this->koeflev1->d_entry  = $this->input->post('d_entry');
        $this->koeflev1->satuan  = $this->input->post('satuan');
        if(! $this->koeflev1->save($level1)) {
            echo '<p>' . $this->koeflev1->error->string . '</p>';
        } else {
            redirect('perizinan/koefisientarif/edit/'.$this->input->post('izin_id').'/'.$this->input->post('property_id').'/'.$this->input->post('level1_id'));
        }

    }
    
      public function savelev2() {
        $level2 = new trkoefisienretribusilev1();
        $level2->get_by_id($this->input->post('level2_id'));

        $this->koeflev2->kategori  = $this->input->post('kategori');
        $this->koeflev2->index_kategori  = $this->input->post('idx_kategori');
        $this->koeflev2->i_entry  = $this->input->post('i_entry');
        $this->koeflev2->d_entry  = $this->input->post('d_entry');
        $this->koeflev2->satuan  = $this->input->post('satuan');
        if(! $this->koeflev2->save($level2)) {
            echo '<p>' . $this->koeflev2->error->string . '</p>';
        } else {
            redirect('perizinan/koefisientarif/edit1/'.$this->input->post('izin_id').'/'.$this->input->post('property_id').'/'.$this->input->post('level1_id').'/'.$this->input->post('level2_id'));
        }

    }

    public function update() {

         $tgl = date("Y-m-d H:i:s");
         $u_ser = $this->session->userdata('username');
         $p = $this->db->query("call log ('Setting Perizinan','Update koefisiensi tarif ".$this->input->post('kategori')."','".$tgl."','".$u_ser."')");

        $update = $this->koef
                ->where('id', $this->input->post('id'))
                ->update(array
                    (

                    'kategori' => $this->input->post('kategori'),
                    'index_kategori' => $this->input->post('idx_kategori'),
                    'd_mulai_efektif' => $this->input->post('d_mulai_efektif'),
                    'd_selesai' => $this->input->post('d_selesai'),
                    'i_entry' => $this->input->post('i_entry'),
                    'satuan' => $this->input->post('satuan'),
                    'd_entry' => $this->input->post('d_entry')
                    )
                        );
        if($update) {
            redirect('perizinan/koefisientarif/detail/'.$this->input->post('izin_id').'/'.$this->input->post('property_id'));
        }
    }

     public function update1() {
        $update = $this->koeflev1
                ->where('id', $this->input->post('level2_id'))
                ->update(array
                    (

                    'kategori' => $this->input->post('kategori'),
                    'index_kategori' => $this->input->post('idx_kategori'),
                    'i_entry' => $this->input->post('i_entry'),
                    'v_index_kategori' => $this->input->post('v_index_kategori'),
                    'satuan' => $this->input->post('satuan'),
                    'd_entry' => $this->input->post('d_entry')
                    )
                        );
        if($update) {
          redirect('perizinan/koefisientarif/edit1/'.$this->input->post('izin_id').'/'.$this->input->post('property_id').'/'.$this->input->post('level1_id').'/'.$this->input->post('level2_id'));
        }
    }
     public function update2() {
        $update = $this->koeflev2
                ->where('id', $this->input->post('level3_id'))
                ->update(array
                    (

                    'kategori' => $this->input->post('kategori'),
                    'index_kategori' => $this->input->post('idx_kategori'),
                    'i_entry' => $this->input->post('i_entry'),
                    'satuan' => $this->input->post('satuan'),
                    'd_entry' => $this->input->post('d_entry')
                    )
                        );
        if($update) {
          redirect('perizinan/koefisientarif/edit1/'.$this->input->post('izin_id').'/'.$this->input->post('property_id').'/'.$this->input->post('level1_id').'/'.$this->input->post('level2_id'));
        }
    }

    public function delete($idizin = NULL,$idproperty=NULL,$idkoef=NULL) {
            
         $this->koef->get_by_id($idkoef);
         $tgl = date("Y-m-d H:i:s");
         $u_ser = $this->session->userdata('username');
         $p = $this->db->query("call log ('Setting Perizinan','Delete koefisiensi tarif ".$this->koef->kategori."','".$tgl."','".$u_ser."')");
         
         //edited 11-04-2013
         //by mucktar
        /*$relprop = new trkoefesientarifretribusi_trproperty();
        $relprop->where('trkoefesientarifretribusi_id',$idkoef);
        $relprop->delete();*/
        
        $this->db->delete('trkoefesientarifretribusi_trproperty',array('trkoefesientarifretribusi_id'=>$idkoef));
        
        /*$koefisien = new trkoefesientarifretribusi();
        $koefisien->get_by_id($idkoef);
        $koefisien->delete();*/
        $this->db->delete('trkoefesientarifretribusi',array('id'=>$idkoef));
        //end edit
       redirect('perizinan/koefisientarif/detail/'.$idizin.'/'.$idproperty);
      
    }

    public function delete1($idizin=NULL,$idproperty=NULL,$idkoef=NULL,$koef=NULL){
     
        $relkoef1 = new trkoefesientarifretribusi_trkoefisienretribusilev1();
        $relkoef1->where('trkoefisienretribusilev1_id',$koef);
        $relkoef1->delete();

        $koefisienretribusilev1 = new trkoefisienretribusilev1();
        $koefisienretribusilev1->get_by_id($koef);
        $koefisienretribusilev1->delete();

       redirect('perizinan/koefisientarif/edit/'.$idizin.'/'.$idproperty.'/'.$idkoef);
       
    }

    public function delete2($idizin=NULL,$idproperty=NULL,$idkoef=NULL,$koef1=NULL,$koef2=NULL){
     
        $relkoef2 = new trkoefisienretribusilev1_trkoefisienretribusilev2();
        $relkoef2->where('trkoefisienretribusilev2_id',$koef2);
        $relkoef2->delete();

        $koefisienretribusilev2 = new trkoefisienretribusilev2();
        $koefisienretribusilev2->get_by_id($koef2);
        $koefisienretribusilev2->delete();

        redirect('perizinan/koefisientarif/edit1/'.$idizin.'/'.$idproperty.'/'.$idkoef.'/'.$koef1);
    }
    
}
?>
