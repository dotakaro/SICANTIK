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
class simulasi extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->permohonan = new tmpermohonan();
        $this->property = new trproperty();
        $this->perizinan = new trperizinan();
        $this->koefesien = new trkoefesientarifretribusi();
        $this->retribusi = new trretribusi();
        $this->izin_property = new trperizinan_trproperty();
        $this->propjenis = new tmproperty_jenisperizinan();
        $this->koefesienproperty = new trkoefesientarifretribusi_trproperty();
        $this->klasifikasi = new tmproperty_klasifikasi();
        $this->prasarana = new tmproperty_prasarana();
        $this->simulasi = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->simulasi = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '17') {
                $enabled = TRUE;
                $this->simulasi = new user_auth();
            }
        }
		
        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
       
        $data = $this->_koefesien();
         $query = "select a.id,a.n_perizinan from trperizinan as a
                inner join trperizinan_trretribusi as b on b.trperizinan_id=a.id
                inner join trretribusi as c on c.id=b.trretribusi_id
                where c.m_perhitungan='0' and 
                CURDATE() between c.d_sk_terbit and c.d_sk_berakhir order by a.id ASC";
        $hasil = $this->db->query($query)->result();
        $data['list_izin2'] = $hasil;
        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                         oTable = $('Showing#property').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                        $('#izin_jenis').change(function(){
                                $('#show_retribusi').fadeOut();
                                $.post('". base_url() ."property/simulasi/izin_jenis', {
                                   izin_id : $('#izin_jenis').val()

                                }, function(response){
                                    setTimeout(\"finishAjax('show_retribusi', '\"+escape(response)+\"')\", 400);
                        Syafinatul        });
                                return false;
                        });
                          $('#retribusi').change(function(){
                                $('#show_koefesien').fadeOut();
                                $.post('". base_url() ."property/simulasi/retribusi', {
                                   retribusi_id : $('#retribusi').val()
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
        $this->session_info['page_name'] = "Simulasi Tarif Retribusi Perizinan";
        $this->template->build('simulasi_list2', $this->session_info);
    }

    public function edit() {
        if($this->input->post('jenis_izin')==='2' || $this->input->post('jenis_izin')==='3'){
            redirect('property/simulasi/edit2/'.$this->input->post('jenis_izin'));
        }
        else{
        $propertyizin = new trperizinan_trproperty();
        $perizinan = new trperizinan();
        $retribusi = new trretribusi();

        $izin = $this->perizinan->get_by_id($this->input->post('jenis_izin'));
        $tarif = $izin->$retribusi->get();//$retribusi->where('perizinan_id',$this->input->post('jenis_izin'))->get();


        $koefisien = new trkoefesientarifretribusi();

        $data['jenis_izin'] = $izin;
        $data['retribusix'] = $tarif;
        $data['list'] = $izin->trproperty->order_by('c_parent asc, c_order asc')->get();
        
        $data['list_izin'] = $this->propjenis->get();

        $data['save_method'] = 'save';


        $this->load->vars($data);
        $js =  "
                  $(document).ready(function() {
                    $(\"#tabs\").tabs();
                } );
                $(document).ready(function() {
                         oTable = $('#property').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                        $('#izin_jenis').change(function(){
                                $('#show_retribusi').fadeOut();
                                $.post('". base_url() ."property/simulasi/izin_jenis', {
                                   izin_id : $('#izin_jenis').val()

                                }, function(response){
                                    setTimeout(\"finishAjax('show_retribusi', '\"+escape(response)+\"')\", 400);
                        Syafinatul        });
                                return false;
                        });
                          $('#retribusi').change(function(){
                                $('#show_koefesien').fadeOut();
                                $.post('". base_url() ."property/simulasi/retribusi', {
                                   retribusi_id : $('#retribusi').val()
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


        $this->session_info['page_name'] = "Simulasi Tarif Retribusi Perizinan";
       $this->template->build('simulasi_edit2', $this->session_info);
    
        }
    }

     public function edit2($jenisizin = NULL) {
        $propertyizin = new trperizinan_trproperty();
        $perizinan = new trperizinan();
        $retribusi = new trretribusi();

        $izin = $this->perizinan->get_by_id($jenisizin);
        $tarif = $izin->$retribusi->get();//$retribusi->where('perizinan_id',$this->input->post('jenis_izin'))->get();


        $koefisien = new trkoefesientarifretribusi();
        
        $data['entry_id'] = "";
        $data['data_koefisien'] = "";
        $data['jenis_izin'] = $izin;
        $data['retribusix'] = $tarif;
        $data['list'] = $izin->trproperty->order_by('c_parent asc, c_order asc')->get();
        $data['list_izin'] = $this->propjenis->get();
        $data['list_klasifikasi']= $this->klasifikasi->get();
        $data['list_prasarana'] = $this->prasarana->get();

        $data['save_method'] = 'save';

        $this->load->vars($data);
        $js =  "
                  $(document).ready(function() {
                    $(\"#tabs\").tabs();
                } );
                $(document).ready(function() {
                         oTable = $('#property').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                        $('#izin_jenis').change(function(){
                                $('#show_retribusi').fadeOut();
                                $.post('". base_url() ."property/simulasi/izin_jenis', {
                                   izin_id : $('#izin_jenis').val()

                                }, function(response){
                                    setTimeout(\"finishAjax('show_retribusi', '\"+escape(response)+\"')\", 400);
                        Syafinatul        });
                                return false;
                        });
                          $('#retribusi').change(function(){
                                $('#show_koefesien').fadeOut();
                                $.post('". base_url() ."property/simulasi/retribusi', {
                                   retribusi_id : $('#retribusi').val()
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


        $this->session_info['page_name'] = "Simulasi Tarif Retribusi Perizinan";
        $this->template->build('simulasi_edit33', $this->session_info);
    }

    public function hitung() {
        $propertyizin = new trperizinan_trproperty();
        $perizinan    = new trperizinan();

        $retribusi = new trretribusi();
        $koefesien = new trkoefesientarifretribusi();
        $relasiretribusi = new trperizinan_trretribusi();

        $izin = $this->perizinan->get_by_id($this->input->post('izin_id'));
        
        $tarif = $retribusi->where('id',2)->get();
        
        $data['jenis_izin'] = $izin;
        $data['retribusix'] = $tarif;
        $data['list'] = $izin->trproperty->order_by('c_parent asc, c_order asc')->get();
        $data['list_izin'] = $this->propjenis->get();


        $data['property_id']  = $this->input->post('property_id');//id property
        $data['koef_id1'] = $this->input->post('koef_id1'); //id koefisien
        $data['entry_id'] = $this->input->post('entry_id');//id tmproperty_jenisperizinan
        $data['retribusi_id'] = $this->input->post('retribusi_id');
        $data['koef_id2'] = $this->input->post('koef_id2');
        $data['retribusi_id2'] = $this->input->post('retribusi_id2');
        $data['koef_value3']   = $this->input->post('koef_value3');
        $data['koef_id3']      = $this->input->post('koef_id3');
      
        $data['luas'] = $this->input->post('luasbangunan');

        $property_id  = $this->input->post('property_id');//id property
        $koef_id1 = $this->input->post('koef_id1'); //id koefisien
        $entry_id = $this->input->post('entry_id');//id tmproperty_jenisperizinan
        $retribusi_id = $this->input->post('retribusi_id');
        $koef_id2 = $this->input->post('koef_id2');
        $retribusi_id2 = $this->input->post('retribusi_id2');
        $koef_value3   = $this->input->post('koef_value3');
        $koef_id3      = $this->input->post('koef_id3');

        $luas = $this->input->post('luasbangunan');
        $harga = $this->input->post('harga');

        //test nilai cumulative
        $entry_len = count($property_id);
        $entry_lena = count($koef_id1);
        $is_array = NULL;
        $updated = FALSE;
        $bar=0;$no=0;
        
        for($i=0;$i < $entry_len;$i++) {

            if($is_array !== $property_id[$i]) {
                $relasi_entry = new trproperty();
                $relasi_entry->get_by_id($property_id[$i]);
                $property_type = $relasi_entry->c_type;
                $relasi_kontribusi = new trkoefesientarifretribusi();
                $relasi_kontribusi->get_by_id($koef_id1[$i]);

                $a = $relasi_kontribusi->kategori;
                $b = $relasi_kontribusi->index_kategori;

                $flwl = 1;
                if ($property_type == '1') {
                    $no++;
                            
                    $entry_data = new tmproperty_jenisperizinan();
                    if($relasi_entry->id == '12'){ //Hanya untuk KLASIFIKASI

                    $klasifikasi_len = count($retribusi_id);
                    $is_array_klasifikasi = NULL;
                    $no2=0;
                    $tambah = 0;
                    for($z=0;$z < $klasifikasi_len;$z++) {
                        $no2++;
                        if($is_array_klasifikasi !== $retribusi_id[$z]) {
                            $relasi_klasifikasi = new trkoefesientarifretribusi();
                            $relasi_klasifikasi->get_by_id($retribusi_id[$z]);
                            $relasi_level = new trkoefesientarifretribusi_trkoefisienretribusilev1();
                            $relasi_level->where('trkoefesientarifretribusi_id',$retribusi_id[$z])->get();

                            $relasi_level1 = new trkoefisienretribusilev1();
                            //$relasi_level1->get_by_id($relasi_level->trkoefisienretribusilev1_id);
                            $relasi_level1->get_by_id($koef_id2[$z]);
                            $index2a = $relasi_klasifikasi->index_kategori;
                            $index2b = $relasi_level1->index_kategori;
                                                    
                            $tambah   = $tambah + ($index2a * $index2b);
                        }
                        $is_array_klasifikasi = $retribusi_id[$z];

                        
                    }
                    
                    $jumlah2 = $no2 + 1;
                 
                }else if($relasi_entry->id == '29'){ //Hanya untuk PRASARANA

                    $prasarana_len = count($retribusi_id2);
                    $is_array_prasarana = NULL;

                    $no3=0;
                    $tambahh = 0;
                    for($x=0;$x < $prasarana_len;$x++) {
                        $no3++;
                        if($is_array_prasarana !== $retribusi_id2[$x]) {
                            $relasi_prasarana = new trkoefesientarifretribusi();
                            $relasi_prasarana->get_by_id($retribusi_id2[$x]);
                            $relasi_level = new trkoefesientarifretribusi_trkoefisienretribusilev1();
                            $relasi_level->where('trkoefesientarifretribusi_id',$retribusi_id2[$x])->get();
                            $relasi_level1 = new trkoefisienretribusilev1();
                            $relasi_level1->get_by_id($koef_id3[$x]);
                            $koefbb = $relasi_prasarana->index_kategori;
                            $koefcc = $relasi_level1->index_kategori;
                            $koefbbb= $relasi_level1->v_index_kategori;
                            

                            $tambahh = $tambahh + ($koef_value3[$x] * $koefbb * $koefcc * $koefbbb);

                            }
                        $is_array_prasarana = $retribusi_id2[$x];
                    }

                    $jumlah3 = $no3 + 1;
                }else{
                 $b;
                 $flwl = $flwl * $b;
                }
              }

            }
            $is_array = $property_id[$i];
        }
                            $jumlahh = $no - 1;
        //coba hitung
       // $layer1 = $luas * $koeff * $tambah * $harga;
        $data['total'] = (($luas * $flwl * $tambah * $harga)+$tambahh);


        //
        $this->load->vars($data);
        $js = "
                $(document).ready(function() {
                    $(\"#tabs\").tabs();
                } );
            ";

        $this->template->set_metadata_javascript($js);
        
        $this->session_info['page_name'] = "Simulasi Tarif Retribusi Perizinan";
       // $this->template->build('simulasi_editlanjutan', $this->session_info);
    }

     function _koefesien(){

        $data['list_izin'] =  $this->perizinan->where('c_tarif',1)->order_by('id DESC')->get();
        $data['list_retribusi'] =  $this->retribusi->order_by('id desc')->get();
        $data['list'] = $this->property->get();
        $data['cek_izin']=" ";
        $retribusi = new trretribusi();
        $relasiretribusi = new trperizinan_trretribusi();

        
        return $data;
    }

    public function izin_jenis() {
        $data['izin_id'] = 'izin_jenis';
        $data['list'] = $this->property->get();

        $this->load->vars($data);
        $this->load->view('retribusi_jenis_load', $data);
    }

    public function cetak_simulasi_imb() {

        $this->settings = new settings();
        $this->settings->where('name','app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name','app_city')->get();

        $permohonan = new tmpermohonan();
        $retribusi = new trretribusi();

        $p_kelurahan = $permohonan->tmpemohon->trkelurahan->get();
        $p_kecamatan = $permohonan->tmpemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $permohonan->tmpemohon->trkelurahan->trkecamatan->trkabupaten->get();

        //path of the template file
        $nama_surat = "cetak_simulasi_retribusi_imb";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/'.$nama_surat.'.odt');
        //$odf->setImage('header', 'assets/css/' . $app_folder . '/images/dinas_1.jpg', '17.5', '3.5');
        
         //logo
        $this->tr_instansi = new Tr_instansi();
        $logo = $this->tr_instansi->get_by_id(14);
        $odf->setImage('logo', 'uploads/logo/' . $logo->value, '1.7', '1.7');

        //badan
        $this->tr_instansi = new Tr_instansi();
        $nama_bdan = $this->tr_instansi->get_by_id(9);
        $odf->setVars('badan', strtoupper($nama_bdan->value));

        //telpon
        $this->tr_instansi = new Tr_instansi();
        $tlp = $this->tr_instansi->get_by_id(10);
        $odf->setVars('tlp', $tlp->value);

        //fax
        $this->tr_instansi = new Tr_instansi();
        $tlp = $this->tr_instansi->get_by_id(13);
        $odf->setVars('fax', $tlp->value);

        $this->tr_instansi = new Tr_instansi();
        $alamat1 = $this->tr_instansi->get_by_id(12);
        
        //membuat kota
        $wilayah = new trkabupaten();
        if (isset($app_city->value)) {
            $alamat = $permohonan->tmpemohon->a_pemohon . ' ' . $p_kelurahan->n_kelurahan . ', ' .
                    $p_kecamatan->n_kecamatan . ', ' . ucwords(strtolower($p_kabupaten->n_kabupaten));
            $wilayah->get_by_id($app_city->value);
            $gede_kota = strtoupper($wilayah->n_kabupaten);
            $kecil_kota = ucwords(strtolower($wilayah->n_kabupaten));
            $odf->setVars('kota4', $gede_kota);
            $odf->setVars('kota', ucwords(strtolower($wilayah->n_kabupaten)));
             $odf->setVars('alamat', ucwords(strtolower($alamat1->value)) . ' - ' . $kecil_kota);
            
        } else {
            $alamat = $permohonan->tmpemohon->a_pemohon;
            $odf->setVars('kota', '...........');
            $odf->setVars('kota4', '...........');
             $odf->setVars('alamat','...........');
        }


        $odf->setVars('tanggal', $this->lib_date->mysql_to_human(date('YYYY/mm/dd')));

        $property_id  = $this->input->post('property_id');//id property
        $koef_id1 = $this->input->post('koef_id1'); //id koefisien
        $entry_id = $this->input->post('entry_id');//id tmproperty_jenisperizinan
        $retribusi_id = $this->input->post('retribusi_id');
        $koef_id2 = $this->input->post('koef_id2');
        $retribusi_id2 = $this->input->post('retribusi_id2');
        $koef_value3   = $this->input->post('koef_value3');
        $koef_id3      = $this->input->post('koef_id3');

        $luas  = $this->input->post('luas');
        $harga = $this->input->post('harga');
        $hargaharusbayar = $this->input->post('hargaharusbayar');
        $odf->setVars('luas_bangunan', $luas);
        $odf->setVars('total', "Rp.".$hargaharusbayar.",00");

        //test nilai cumulative
        $entry_len = count($property_id);
        $entry_lena = count($koef_id1);
        $is_array = NULL;
        $updated = FALSE;
        $bar=0;$no=0;

        for($i=0;$i < $entry_len;$i++) {

            if($is_array !== $property_id[$i]) {
                $relasi_entry = new trproperty();
                $relasi_entry->get_by_id($property_id[$i]);
                $property_type = $relasi_entry->c_type;
                $relasi_kontribusi = new trkoefesientarifretribusi();
                $relasi_kontribusi->get_by_id($koef_id1[$i]);

                $b = $relasi_kontribusi->index_kategori;

                 
                 if ($property_type == '1') {
                    $no++;
              
                    $entry_data = new tmproperty_jenisperizinan();
                    if($relasi_entry->id == '12'){ //Hanya untuk KLASIFIKASI

                    $klasifikasi_len = count($retribusi_id);
                    $is_array_klasifikasi = NULL;
                    $no2=0;
                    $tambah = 0;
                    for($z=0;$z < $klasifikasi_len;$z++) {
                        
                         if ($no2 == 0)$property = $relasi_entry->n_property;
                                else  $property = "";
                         $no2++;
                         if($is_array_klasifikasi !== $retribusi_id[$z]) {
                            $relasi_klasifikasi = new trkoefesientarifretribusi();
                            $relasi_klasifikasi->get_by_id($retribusi_id[$z]);
                            $relasi_level = new trkoefesientarifretribusi_trkoefisienretribusilev1();
                            $relasi_level->where('trkoefesientarifretribusi_id',$retribusi_id[$z])->get();

                            $relasi_level1 = new trkoefisienretribusilev1();
                            //$relasi_level1->get_by_id($relasi_level->trkoefisienretribusilev1_id);
                            $relasi_level1->get_by_id($koef_id2[$z]);
                            $index2a[$z] = $relasi_klasifikasi->index_kategori;
                            $index2b[$z] = $relasi_level1->index_kategori;
                            $index2aa = $index2a[$z];
                            $index2bb = $index2b[$z];


                            
                                    $listeArticles3 = array(
                                    array(  'content1' => $property,
                                            'content2' => $relasi_klasifikasi->kategori,
                                            'content3' => $relasi_level1->kategori,
                                            'content4' => $relasi_level1->index_kategori,
                                            'content5' => '',
                                        ),
                                    );

                                    $article3 = $odf->setSegment('articles3');
                                    foreach($listeArticles3 AS $element) {
                                    $article3->titreArticle3($element['content1']);
                                    $article3->texteArticle3($element['content2']);
                                    $article3->texteArticle4($element['content3']);
                                    $article3->texteArticle5($element['content4']);
                                    $article3->texteArticle6($element['content5']);
                                    $article3->merge();
                                }
                        }
                        $is_array_klasifikasi = $retribusi_id[$z];


                    }
                    $tambah   = $tambah + ($index2aa * $index2bb);
                    $jumlah2 = $no2 + 1;

                }else if($relasi_entry->id == '29'){ //Hanya untuk PRASARANA

                    $prasarana_len = count($retribusi_id2);
                    $is_array_prasarana = NULL;

                    $no3=0;

                    for($x=0;$x < $prasarana_len;$x++) {
                        
                         if ($no3 == 0)$property = $relasi_entry->n_property;
                                else  $property = "";
                        $no3++;
                        if($is_array_prasarana !== $retribusi_id2[$x]) {
                            $relasi_prasarana = new trkoefesientarifretribusi();
                            $relasi_prasarana->get_by_id($retribusi_id2[$x]);
                            $relasi_level = new trkoefesientarifretribusi_trkoefisienretribusilev1();
                            $relasi_level->where('trkoefesientarifretribusi_id',$retribusi_id2[$x])->get();
                            $relasi_level1 = new trkoefisienretribusilev1();
                            $relasi_level1->get_by_id($koef_id3[$x]);
                            $index3a[$x] = $relasi_prasarana->index_kategori;
                            $index3b[$x] = $relasi_level1->index_kategori;
                            $index3bb[$x]= $relasi_level1->v_index_kategori;
                            $index3c[$x] = $koef_value3[$x];

                            $index3aa  = $index3a[$x];
                            $index3bs  = $index3b[$x];
                            $index3bbb = $index3bb[$x];
                            $index3cc  = $index3c[$x];

                            $index2 = $koef_value3[$x];
                           
                                    $listeArticles3 = array(
                                    array(  'content1' => $property,
                                            'content2' => $relasi_prasarana->kategori,
                                            'content3' => $relasi_level1->kategori,
                                            'content4' => $relasi_level1->index_kategori,
                                            'content5' => $index2,
                                        ),
                                    );

                                    $article3 = $odf->setSegment('articles3');
                                    foreach($listeArticles3 AS $element) {
                                    $article3->titreArticle3($element['content1']);
                                    $article3->texteArticle3($element['content2']);
                                    $article3->texteArticle4($element['content3']);
                                    $article3->texteArticle5($element['content4']);
                                    $article3->texteArticle6($element['content5']);
                                    $article3->merge();
                                }
                        }
                        $is_array_prasarana = $retribusi_id2[$x];
                    }

                    $jumlah3 = $no3 + 1;
                }else{
             
          
                    
                    $cetak_kategori2 = $relasi_kontribusi->kategori;
                    $cetak_kategori3 = '';
                    $index1 = '';
                    $index2 = '';

                     $listeArticles3 = array(
                            array(  'content1' => $relasi_entry->n_property,
                                    'content2' => $relasi_kontribusi->kategori,
                                    'content3' => $cetak_kategori3,
                                    'content4' => $index1,
                                    'content5' => $index2,
                                ),
                            );

                            $article3 = $odf->setSegment('articles3');
                            foreach($listeArticles3 AS $element) {
                            $article3->titreArticle3($element['content1']);
                            $article3->texteArticle3($element['content2']);
                            $article3->texteArticle4($element['content3']);
                            $article3->texteArticle5($element['content4']);
                            $article3->texteArticle6($element['content5']);
                            $article3->merge();
                        }
                
                
                }
              
                 }
            }
            $is_array = $property_id[$i];
        
        
        }
            
        $odf->mergeSegment($article3);

        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat.'_'.$no_daftar.'.odt');
    }

}
