<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of master class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */
class Master extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->property = new trproperty();
        $this->perizinan = new trperizinan();
        $this->perizinan_poperty = new trperizinan_trproperty();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if ($list_auth->id_role === '1') {
                $enabled = TRUE;
            }
        }
		
        if (!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
        $data['list'] = $this->perizinan->get();
        $this->load->vars($data);

        $js = "
                $(document).ready(function() {
                        oTable = $('#property').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);

        $this->session_info['page_name'] = "Setting Property Pendataan";
        $this->template->build('master_property', $this->session_info);
    }

    public function detail($id = NULL, $exist = NULL) {
        $list_prop = new trperizinan_trproperty();
        $list_prop->where('trperizinan_id', $id);
        $data['id_jenis'] = $id;
        $list_prop->order_by('c_parent_order', 'asc');
        $list_prop->order_by('c_order', 'asc');
        $data['list'] = $list_prop->get();

        $this->perizinan->where('id', $id)->get();
        $data['id'] = $this->perizinan->id;
        $data['nama_izin'] = $this->perizinan->n_perizinan;
        $data['ket_exist'] = $exist;

        $this->load->vars($data);
        $js = "
                function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                        oTable = $('#property_list').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Property";
        $this->template->build('master_property_edit', $this->session_info);
    }

    public function purge($id_izin = NULL) {
        $this->property->where('id', $id_izin)->get();
        $this->property->delete();

        redirect('property/master/propertieslist');
    }

    public function delete($id_izin = NULL, $id_property = NULL) {

        $delete = new trperizinan_trproperty();
        $delete->where(array(
            'trperizinan_id' => $id_izin,
            'trproperty_id' => $id_property
        ))->get();
        $parent = $delete->c_parent;
        $delete->delete();

        $count = $delete->where(array(
                    'trperizinan_id' => $id_izin,
                    'c_parent' => $parent
                ))->count();

        if ($count < 2) {
            $delete->where(array(
                'trperizinan_id' => $id_izin,
                'trproperty_id' => $parent
            ))->get();
            $delete->delete();
        }

        //added 08-04-2013
        //$this->property->where(array('id'=> $id_property))->get();
        //$this->property->delete();
        //end
                
        $this->perizinan->where('id', $id_izin)->get();
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Perizinan','Delete property " . $this->perizinan->n_perizinan . "','" . $tgl . "','" . $u_ser . "')");

        redirect('property/master/detail' . "/" . $id_izin);
    }

    public function dataproperty() {
        $x = 0;
        $lists = $this->property->get();
        $arr_res = array();
        foreach ($lists as $list) {
            $data['id'] = $list->id;
            $data['value'] = $list->n_property;
            array_push($arr_res, $data);
        }

        $response = json_encode($arr_res);
        echo $response;
    }

    public function add($id_izin = NULL) {

        $js = "
                $(document).ready(function() {
                 $('#form').validate();
                    $(\"#tabs\").tabs();
                    oTable = $('#property').dataTable({
                            \"bJQueryUI\": true,
                            \"aoColumnDefs\": [
                                                { \"bSearchable\": false,\"aTargets\": [2] },
                                                { \"bSearchable\": false,\"aTargets\": [3] },
                                                { \"bSearchable\": false,\"aTargets\": [4] },
                                                { \"bSearchable\": false,\"aTargets\": [5] },
                                                { \"bSearchable\": false,\"aTargets\": [6] },
                                                { \"bSearchable\": false,\"aTargets\": [7] },
                                                { \"bSearchable\": false,\"aTargets\": [8] }
                                              ],
                            \"sPaginationType\": \"full_numbers\"
                    });
                });

                ";

        $this->perizinan->get_by_id($id_izin);
        $this->template->set_metadata_javascript($js);
        $this->property->order_by('n_property', 'asc');
        $data['property_list'] = $this->property->where('c_type != ', 2)->get();
        $property = new trproperty();
        $property->order_by("n_property", "asc");
        $data['property_list2'] = $property->where('c_type', 2)->get();
        $data['perizinan_property'] = $this->perizinan->trproperty->get();
        $data['n_property'] = "";
        $data['c_order'] = "";
        $data['c_parent_order'] = "";
        $data['save_method'] = "save";
        $data['id_izin'] = $id_izin;
        $data['method'] = "adding";
        $data['id_property'] = "";

        $settings = new settings();
        $settings->where('name', 'app_enum_satuan')->get();
        $satuan = $settings->value;
        $data['satuan'] = unserialize($satuan);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Property";
        $this->template->build('master_edit', $this->session_info);
    }

    public function save() {
        $id_izin = $this->input->post('id_izin');
        $n_property = $this->input->post('n_property');

        $property = new trproperty();
        $property->where('n_property', $n_property)->get();
        if ($property->id) {
            redirect('property/master/detail/' . $id_izin . '/' . str_replace("-", "", strtolower(url_title($n_property))));
        } else {
            $this->property->n_property = $n_property;
            $this->property->short_name = str_replace("-", "", strtolower(url_title($n_property)));
            $this->property->c_type = $this->input->post('c_type');
            $this->perizinan->where('id', $id_izin)->get();

            if (!$this->property->save($this->perizinan)) {
                echo '<p>' . $this->user->error->string . '</p>';
            } else {

                $this->perizinan->where('id', $id_izin)->get();
                $tgl = date("Y-m-d H:i:s");
                $u_ser = $this->session->userdata('username');
                $p = $this->db->query("call log ('Setting Perizinan','Insert property " . $this->perizinan->n_perizinan . "','" . $tgl . "','" . $u_ser . "')");


                $this->property->where('n_property', $this->input->post('n_property'));
                $id_property = $this->property->get();

                $retribusi = new trperizinan_trproperty();
                $retribusi->where('trperizinan_id', $id_izin);
                $retribusi->where('trproperty_id', $id_property->id);
                $retribusi->update(array(
                    'c_retribusi_id' => $this->input->post('c_retribusi'),
                    'c_sk_id' => $this->input->post('c_sk_id'),
                    'c_skrd_id' => $this->input->post('c_skrd_id'),
                    'c_tl_id' => $this->input->post('c_tl_id'),
                    'c_order' => $this->input->post('c_order'),
                    'c_parent_order' => $this->input->post('c_parent_order'),
                    'c_parent' => $this->input->post('c_parent'),
                    'satuan' => $this->input->post('satuan')
                ));
                
                //added 08-04-2013
                $this->property->where('id',  $id_property->id);
                $this->property->update(array(
                    'property_length' => $this->input->post('property_length')==''?0:$this->input->post('property_length'),
                    'n_property' => $this->input->post('n_property'),
                    'c_type' => $this->input->post('c_type')
                ));
                //end
                
                redirect('property/master/detail/' . $id_izin);
            }
        }
    }

    public function savelist() {
        $id_izin = $this->input->post('id_izin');

        $property_list = $this->input->post('property');
        $property_list_len = count($property_list);
        $retribution_list = $this->input->post('retribution');
        $retribution_list_len = count($retribution_list);
        $sk_status_list = $this->input->post('sk_status');
        $sk_status_list_len = count($sk_status_list);
        $sk_tl_list = $this->input->post('tl_status');
        $sk_tl_list_len = count($sk_tl_list);
        $skrd_tl_list = $this->input->post('skrd_status');
        $skrd_tl_list_len = count($skrd_tl_list);

        for ($i = 0; $i < $property_list_len; $i++) {
            $this->perizinan->get_by_id($id_izin);
            $this->property->get_by_id($property_list[$i]);
            $this->property->save($this->perizinan);

            $par = new trperizinan_trproperty();

            $par->where(array(
                'trperizinan_id' => $id_izin,
                'trproperty_id' => $property_list[$i]
            ))->update(array(
                'c_order' => $this->input->post('c_order_new-' . $property_list[$i]),
                'c_parent' => $this->input->post('c_parent-' . $property_list[$i]),
                'c_parent_order' => $this->input->post('parent-' . $property_list[$i]),
                'satuan' => $this->input->post('c_satuan-' . $property_list[$i])
            ));

            // Check if parent property already snap on perizinan
            $trproperty_id = $this->input->post('c_parent-' . $property_list[$i]);
            $c_parent = $trproperty_id;
            $trperizinan_id = $id_izin;

            $check = new trperizinan_trproperty();
            $count = $check->where(array(
                        'c_parent' => $c_parent,
                        'trperizinan_id' => $trperizinan_id,
                        'trproperty_id' => $trproperty_id
                    ))->count();

            if ($count < 1) {
                $check->c_parent = $c_parent;
                $check->trperizinan_id = $trperizinan_id;
                $check->trproperty_id = $trproperty_id;
                $check->save_as_new();    
            }


            // End of checking
//            for($x=0;$x<$retribution_list_len;$x++){
            if ($retribution_list[$i] === $property_list[$i]) {
                $retribusi = new trperizinan_trproperty();
                $retribusi->where('trperizinan_id', $id_izin);
                $retribusi->where('trproperty_id', $property_list[$i]);
                $retribusi->update('c_retribusi_id', '1');
//                    break;
            }
//            }
//            for($y=0;$y<$sk_status_list_len;$y++) {
            if ($sk_status_list[$i] === $property_list[$i]) {
                $retribusi2 = new trperizinan_trproperty();
                $retribusi2->where('trperizinan_id', $id_izin);
                $retribusi2->where('trproperty_id', $property_list[$i]);
                $retribusi2->update('c_sk_id', '1');
//                $retribusi2->update('c_skrd_id', '1');
//                    break;
            }
//            }
//            for($z=0;$z<$sk_tl_list_len;$z++) {
            if ($sk_tl_list[$i] === $property_list[$i]) {
                $retribusi3 = new trperizinan_trproperty();
                $retribusi3->where('trperizinan_id', $id_izin);
                $retribusi3->where('trproperty_id', $property_list[$i]);
                $retribusi3->update('c_tl_id', '1');
//                    break;
            }
//            }

            if ($skrd_tl_list[$i] === $property_list[$i]) {
                $retribusi4 = new trperizinan_trproperty();
                $retribusi4->where('trperizinan_id', $id_izin);
                $retribusi4->where('trproperty_id', $property_list[$i]);
                $retribusi4->update('c_skrd_id', '1');
//                    break;
            }
        }
        redirect('property/master/detail/' . $id_izin);
    }

    public function property($id_izin = NULL, $id_property = NULL) {

        $this->property->where('id', $id_property)->get();

        $data['n_property'] = $this->property->n_property;
        $data['save_method'] = "update";
        $data['id_izin'] = $id_izin;
        $data['id_property'] = $id_property;
        $data['method'] = "editing";
        
        // added 11-04-2013
        $cek_dt = new trproperty();
        $permohonan_property = $cek_dt->get_list_permohonan_property();
        
        $disable_field = false;
        foreach($permohonan_property as $p_property){
            if($p_property['trproperty_id']===$id_property){
                $disable_field = true;
                break;
            }
        }
        
        $data['disable_field'] = $disable_field;
        //end add
        
        $retribusi = new trperizinan_trproperty();
        $retribusi->where('trperizinan_id', $id_izin);
        $retribusi->where('trproperty_id', $id_property);
        $retribusi->get();
        $data['length'] = $this->property->property_length;
        $data['ret_choise'] = $retribusi->c_retribusi_id;
        $data['c_type'] = $this->property->c_type;
        $data['c_order'] = $retribusi->c_order;
        $data['c_parent'] = $retribusi->c_parent;
        $data['c_parent_order'] = $retribusi->c_parent_order;
        $data['c_sk_id'] = $retribusi->c_sk_id;
        $data['c_skrd_id'] = $retribusi->c_skrd_id;
        $data['c_tl_id'] = $retribusi->c_tl_id;
        $data['satuan_key'] = $retribusi->satuan;
        $property = new trproperty();
        $property->order_by("n_property", "asc");
        $data['property_list'] = $property->where('c_type', 2)->get();
        $data['property_list2'] = $property->where('c_type', 2)->get();

        $settings = new settings();
        $settings->where('name', 'app_enum_satuan')->get();
        $satuan = $settings->value;
        $data['satuan'] = unserialize($satuan);
        $js = "$(document).ready(function(){
                                $('#form').validate();
              });";

        $this->template->set_metadata_javascript($js);
        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Property";
        $this->template->build('master_edit', $this->session_info);
    }

	/**
	* @ModifiedAuthor Indra
	* Modified Date: 2013-05-01
	* @ModifiedComment Memperbaiki bug ketika property dipindah parentnya
	* Modified Date : 2014-01-07
	* Menambahkan update short_name berdasarkan nama property
	*/
    public function update() {
        $id_izin = $this->input->post('id_izin');
		$id_property = $this->input->post('id_property');
        
		/*simpan data old parent sebelum row di update*/
        $retribusi = new trperizinan_trproperty();		
        $retribusi->where('trperizinan_id', $id_izin);
        $old_parent = $retribusi->where('trproperty_id', $id_property)->get();
		$short_name = str_replace("-", "", strtolower(url_title($this->input->post('n_property'))));

		
		/*Update Data Property*/
        $update = $this->property
                ->where('id', $this->input->post('id_property'))
                ->update(array(
					'property_length' => $this->input->post('length'),
					'n_property' => $this->input->post('n_property'),
					'short_name' =>$short_name,
					'c_type' => $this->input->post('c_type')
                ));

        $this->perizinan->where('id', $id_izin)->get();
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Perizinan','Update property " . $this->perizinan->n_perizinan . "','" . $tgl . "','" . $u_ser . "')");

//        if($update) {
        $this->property->where('n_property', $this->input->post('n_property'));
//        $id_property = $this->property->get()->id;
        
        $c_baru = $this->input->post('c_baru');
        $c_perpanjangan = $this->input->post('c_perpanjangan');
        $c_ubah = $this->input->post('c_lama');

        $retribusi->where('trperizinan_id', $id_izin);
        $retribusi->where('trproperty_id', $id_property);
        $retribusi->update(array(
            'c_retribusi_id' => $this->input->post('c_retribusi'),
            'c_order' => $this->input->post('c_order'),
            'c_parent' => $this->input->post('c_parent'),
            'c_sk_id' => $this->input->post('c_sk_id'),
            'c_skrd_id' => $this->input->post('c_skrd_id'),
            'c_tl_id' => $this->input->post('c_tl_id'),
            'satuan' => $this->input->post('satuan')
        ));

        $retribusi->where('trperizinan_id', $id_izin);
        $retribusi->where('c_parent', $this->input->post('c_parent'));
        $retribusi->update(array(
            'c_parent_order' => $this->input->post('c_parent_order')
        ));

        // Check if parent property already snap on perizinan
        $trproperty_id = $this->input->post('c_parent');
		
        $c_parent = $trproperty_id;
        $trperizinan_id = $id_izin;

        $check = new trperizinan_trproperty();
        $count = $check->where(array(
                    'c_parent' => $c_parent,
                    'trperizinan_id' => $trperizinan_id,
                    'trproperty_id' => $c_parent,
                ))->count();
		$this->load->model('m_rel_trperizinan_trproperty');

        if ($count < 1) { //Jika belum ada parent property di tabel trperizinan_trproperty, maka parent propertinya ditambahkan
            
            $data_parent = array(
                'c_parent' => (int)$c_parent,
                'trperizinan_id' => (int)$trperizinan_id,
                'trproperty_id'=> (int)$trproperty_id
            );
			
            $this->m_rel_trperizinan_trproperty->add_parent_perizinan_property($data_parent);

            /*$check->c_parent = (int)$c_parent;
            $check->trperizinan_id = (int)$trperizinan_id;
            $check->trproperty_id = (int)$trproperty_id;
            $new_parent = $check->save_as_new();
            */  
        }

        //cek apakah old parent masih mempunyai child
        //jika tidak memiliki child, old_parent di hapus
		if(!is_null($old_parent->c_parent)){
	        $old_parent_child_count = $check->where(array(
	                    'c_parent' => $old_parent->c_parent,
	                    'trperizinan_id' => $trperizinan_id,
						/*Modified by Indra 2013-04-26*/
	//                    'trproperty_id !=' => $old_parent->c_parent
	                    'NOT(trproperty_id)' => $old_parent->c_parent
						/*******************/ 
	                ))->count();
			/*Modified by Indra 2013-04-26*/
	//        if(empty($old_parent_child_count)){
	
	        if($old_parent_child_count<1){
			/******************/
	            $old_parent_data = array(
	                    'c_parent' => (int)$old_parent->c_parent,
	                    'trperizinan_id' => (int)$trperizinan_id,
	                    'trproperty_id' => (int)$old_parent->c_parent
	            );
	            
	            $this->m_rel_trperizinan_trproperty->delete_parent_perizinan_property($old_parent_data);
	            /*$check->where(array(
	                    'c_parent' => $old_parent->c_parent,
	                    'trperizinan_id' => $trperizinan_id,
	                    'trproperty_id' => $old_parent->c_parent
	                ))->get()->delete();
	             */
	        }
		}
        // End of checking
       redirect('property/master/detail/' . $this->input->post('id_izin'));
    }

    public function propertieslist() {
        $js = "
                $(document).ready(function() {
                        oTable = $('#property').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $data['list'] = $this->property->get();
        $this->load->vars($data);
        $this->session_info['page_name'] = "List Status Property";
        $this->template->build('properties_list', $this->session_info);
    }

    public function propertydetail($id = NULL) {
        $this->property->where('id', $id);
        $this->property->get();

        $data['id'] = $this->property->id;
        $data['n_property'] = $this->property->n_property;
        $data['status_cont'] = $this->property->c_type;
        $data['save_method'] = "update";

        $js = "$(document).ready(function(){
                                $('#form').validate();
                    $(\"#tabs\").tabs();
              });";

        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Detail Property";
        $this->template->build('property_detail', $this->session_info);
    }

    public function addmoredetail($id_izin = NULL, $id_property = NULL) {
        $this->property->where('id', $id_property)->get();
        $data['nama_property'] = $this->property->n_property;
        $data['id_izin'] = $id_izin;
        $data['id_property'] = $id_property;

        $rel_perizinan = new trperizinan();
        $rel_perizinan->where('id', $id_izin)->get();

        $rel_perizinan->trproperty->include_join_fields()->get();

        $this->load->vars($data);
//        $this->session_info['page_name'] = "Tambah Detail Suatu Property";
        $this->session_info['page_name'] = $rel_perizinan->trproperty->join_id;
        $this->template->build('master_property_detail_list', $this->session_info);
    }

    public function addpropertydetail() {
        $data['id'] = "";
        $data['n_property'] = "";
        $data['status_cont'] = "";
        $data['save_method'] = "save";

        $js = "
                $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();
                } );
            ";
        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Detail Property";
        $this->template->build('property_detail', $this->session_info);
    }

    public function propertyedit($save_method = NULL) {
        if ($save_method === 'update') {

            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $p = $this->db->query("call log ('Setting Perizinan','Update property " . $this->input->post('n_property') . "','" . $tgl . "','" . $u_ser . "')");

            $id = $this->input->post('id');
            $n_property = $this->input->post('n_property');
            $c_type = $this->input->post('c_type');
            $short_name = str_replace("-", "", strtolower(url_title($n_property)));
            $update = $this->property
                    ->where('id', $id)
                    ->update(array(
                'n_property' => $n_property,
                'short_name' => $short_name,
                'c_type' => $c_type
                    ));

            if ($update) {
                redirect('property/master/propertieslist');
            }
        } else if ($save_method === 'save') {

            $this->property->n_property = $this->input->post('n_property');
            $this->property->c_type = $this->input->post('c_type');
            $short_name = str_replace("-", "", strtolower(url_title($this->input->post('n_property'))));
            $this->property->save();


            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $p = $this->db->query("call log ('Setting Perizinan','Tambah jenis property " . $this->input->post('n_property') . "','" . $tgl . "','" . $u_ser . "')");


            redirect('property/master/propertieslist');
        }
    }

}

// This is the end of master class
