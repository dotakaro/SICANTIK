<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of perizinan class
 *
 * @author  Y
 * @since   1.0
 *
 */

class Perizinan extends WRC_AdminCont {

    private $_relationTypeAccess = 'A';

    public function __construct() {
        parent::__construct();
        $this->perizinan = new trperizinan();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '1') {
                $enabled = TRUE;
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {

        $data['list'] = $this->perizinan->get();
        $data['list_izin'] = $this->perizinan->get_list();


        $this->load->vars($data);

        $js =  "function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                        oTable = $('#perizinan').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Data Jenis Perizinan";
        $this->template->build('list', $this->session_info);
    }

    public function create() {
		/*Added By Indra*/
		$settings = new settings();
        $settings->where('name', 'app_enum_masa_berlaku')->get();
        $list_berlaku_satuan = $settings->value;
		/***************/

        /***BEGIN - Ambil Daftar Unit Kerja****/
        $masterUnit = new trunitkerja();
        $getUnit = $masterUnit->get();
        $listHakAkses = array();
        /***END   - Ambil Daftar Unit Kerja****/
	
        $kabupaten = new trkabupaten();
        $unitkerja = new trunitkerja();
        $kelompok = new trkelompok_perizinan();

        $data['list_kab'] = $kabupaten->order_by('n_kabupaten','ASC')->get()->all;
        $data['list_uk'] = $unitkerja->order_by('n_unitkerja','ASC')->get()->all;
        $data['list_klp'] = $kelompok->order_by('n_kelompok','ASC')->get()->all;
//		echo "<pre>";print_r($data['list_klp']);exit();

        $data['n_perizinan']  = "";
        $data['v_berlaku_tahun'] = "";
		$data['v_berlaku_satuan']="";
        $data['list_berlaku_satuan'] = unserialize($list_berlaku_satuan);		
        $data['v_perizinan'] = "";
        $data['v_hari'] = "";
        $data['is_open'] = "2";
        $data['c_foto'] = "2";
        $data['c_keputusan'] = "2";
        $data['c_berlaku'] = "2";
        $data['kelompok_id']  = "";
        $data['unitkerja_id']  = "";
        $data['save_method'] = "save";
        $data['id'] = "";
        $data['getUnit'] = $getUnit;
        $data['listHakAkses'] = $listHakAkses;
		$data['cek_bpjs'] = "";

        $js =  "
                $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();
                    $(\"#unit_akses\").michaelMultiselect();

					 $('#v_berlaku_satuan').change(function(){
					 	if($(this).val()=='selamanya'){
					 		$('#v_berlaku_tahun').val('').fadeOut(500);
						}else{
							$('#v_berlaku_tahun').fadeIn(500);
						}
					 });
					 
				 $.validator.addMethod(\"required-indra\", 
				    function(value, element) {
						switch( element.nodeName.toLowerCase() ) {
							case 'select':
								// could be an array for select-multiple or a string, both are fine this way
								var val = $(element).val();
								return val && val.length > 0 && val!=-1;
							case 'input':
								if($(element).css('display')!='none'){
									if ( this.checkable(element) )
										return this.getLength(value, element) > 0;
								}else{
									return true;
								}	
							default:
								return $.trim(value).length > 0;
						}	
				    }, 
			    	\"Field ini harus diisi\"
			     );				 
					 					
                } );
            ";
        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Jenis Perizinan";
        $this->template->build('edit', $this->session_info);
    }

    /*
     * edit is a method to show page for updating data
     */
    public function edit($id_jnsizin = NULL) {
		/*Added By Indra*/
		$settings = new settings();
        $settings->where('name', 'app_enum_masa_berlaku')->get();
        $list_berlaku_satuan = $settings->value;
		/***************/

        /***BEGIN - Ambil Daftar Unit Kerja dan Hak Akses ****/
        $masterUnit = new trunitkerja();
        $getUnit = $masterUnit->get();

        $listHakAkses = array();
        $objAkses = new trperizinan_trunitkerja();
        $getHakAkses = $objAkses->where('trperizinan_id', $id_jnsizin)->where('relation_type',$this->_relationTypeAccess)->get();
        if($getHakAkses->id){
            foreach($getHakAkses as $indexHakAkses=>$hakAkses){
                $listHakAkses[] = $hakAkses->trunitkerja_id;
            }
        }
        /***END   - Ambil Daftar Unit Kerja dan Hak Akses****/

        $this->perizinan->where('id', $id_jnsizin);
        $this->perizinan->get();

        $kelompok = new trkelompok_perizinan();
        $unit = new trunitkerja();
        $this->perizinan->trkelompok_perizinan->get();
        $this->perizinan->trunitkerja->get();

        $data['list_uk'] = $unit->get();
        $data['list_klp'] = $kelompok->get();
        $data['id'] = $this->perizinan->id;
        $data['n_perizinan'] = $this->perizinan->n_perizinan;
        $data['kelompok_id'] = $this->perizinan->trkelompok_perizinan->id;
        $data['unitkerja_id'] = $this->perizinan->trunitkerja->id;
        $data['save_method'] = "update";
        $data['v_berlaku_tahun'] = $this->perizinan->v_berlaku_tahun;
		$data['list_berlaku_satuan'] = unserialize($list_berlaku_satuan);
		$data['v_berlaku_satuan'] = $this->perizinan->v_berlaku_satuan;
        $data['v_hari'] = $this->perizinan->v_hari;
        $data['is_open'] = $this->perizinan->is_open;
        $data['c_foto'] = $this->perizinan->c_foto;
        $data['c_upload'] = $this->perizinan->c_upload;
        $data['c_keputusan'] = $this->perizinan->c_keputusan;
        $data['c_berlaku'] = $this->perizinan->c_berlaku;
        $data['v_perizinan'] = $this->perizinan->v_perizinan;
        $data['getUnit'] = $getUnit;
        $data['listHakAkses'] = $listHakAkses;
		$data['cek_bpjs'] = $this->perizinan->cek_bpjs;

        $js = "$(document).ready(function(){
                $(\"#tabs\").tabs();
                 $('#form').validate();
                 $(\"#unit_akses\").michaelMultiselect();

				 $('#v_berlaku_satuan').change(function(){
				 	if($(this).val()=='selamanya'){
				 		$('#v_berlaku_tahun').val('').fadeOut(500);
					}else{
						$('#v_berlaku_tahun').fadeIn(500);
					}
				 });
				 
				 $.validator.addMethod(\"required-indra\", 
				    function(value, element) {
						switch( element.nodeName.toLowerCase() ) {
							case 'select':
								// could be an array for select-multiple or a string, both are fine this way
								var val = $(element).val();
								return val && val.length > 0 && val!=-1;
							case 'input':
								if($(element).css('display')!='none'){
									if ( this.checkable(element) )
										return this.getLength(value, element) > 0;
								}else{
									return true;
								}	
							default:
								return $.trim(value).length > 0;
						}	
				    }, 
			    	\"Field ini harus diisi\"
			     );
				 
              })";
        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Jenis Perizinan";
        $this->template->build('edit', $this->session_info);

    }


    /**
     * Not yet in use, because of some limit of dataTables
     */
    public function datalist() {

        $this->perizinan->get();
        $this->perizinan->set_json_content_type();
        echo $this->perizinan->json_for_data_table();

    }

    /*
     * Save and update for manipulating data.
     */
    public function save() {
        $perizinan = new trperizinan();

        $perizinan->n_perizinan = $this->input->post('n_perizinan');
        $perizinan->unitkerja_id = $this->input->post('opsi_uk');
        $perizinan->is_open = $this->input->post('is_open');
        $perizinan->c_foto = $this->input->post('c_foto');
        $perizinan->c_upload = $this->input->post('c_upload');
        $perizinan->c_keputusan = $this->input->post('c_keputusan');
        $perizinan->c_berlaku = $this->input->post('c_berlaku');
        $perizinan->v_berlaku_tahun = $this->input->post('v_berlaku_tahun');
        $perizinan->v_berlaku_satuan = $this->input->post('v_berlaku_satuan');
		$perizinan->v_hari = $this->input->post('v_hari');
        $perizinan->v_perizinan = $this->input->post('v_perizinan');
        $perizinan->cek_bpjs = $this->input->post('cek_bpjs');
        $perizinan->c_tarif = 0;

        $unitkerja = new trunitkerja();
        $unitkerja->where('id', $this->input->post('opsi_uk'))->get();

        $kelompok = new trkelompok_perizinan();
        $kelompok->where('id', $this->input->post('opsi_klp'))->get();
        $opsi_klp = $this->input->post('opsi_klp');
        
//        if($opsi_klp === 3 || $opsi_klp === 4 || $opsi_klp === 5) {
        //if($opsi_klp == 4) {
        if(in_array($opsi_klp, $this->__get_izin_dengan_tarif())){
            $perizinan->c_tarif = 1;
        } else {
            $perizinan->c_tarif = 0;
        }

        if ($perizinan->save(array($unitkerja,$kelompok))) {
            ## BEGIN - Update Data Unit Akses ##
            $objAkses = new trperizinan_trunitkerja();
            if($this->input->post('unit_akses') && is_array($this->input->post('unit_akses'))){
                foreach($this->input->post('unit_akses') as $indexUnitAkses=>$unitAkses){
                    $objAksesNew = new trperizinan_trunitkerja();
                    $objAksesNew->trunitkerja_id = $unitAkses;
                    $objAksesNew->trperizinan_id = $perizinan->id;
                    $objAksesNew->relation_type = $this->_relationTypeAccess;
                    $objAksesNew->save();
                }
            }
            ## END - Update Data Unit Akses ##

            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $p = $this->db->query("call log ('Setting Perizinan','Insert ".$this->input->post('n_perizinan')."','".$tgl."','".$u_ser."')");
            redirect('perizinan');
        } else {
            echo '<p>' . $this->perizinan->error->string . '</p>';
        }
    }

    public function update() {
        $c_tarif = 0;
        $trperizinanId = $this->input->post('id');

        $opsi_klp = $this->input->post('opsi_klp');
//        if($opsi_klp === '3' || $opsi_klp === '4' || $opsi_klp === '5') {
        //if($opsi_klp == 4) {
        if(in_array($opsi_klp, $this->__get_izin_dengan_tarif())){
            $c_tarif = 1;
        } else {
            $c_tarif = 0;
        }

        $update = $this->perizinan
                ->where('id', $this->input->post('id'))
                ->update(array(
                            'n_perizinan' => $this->input->post('n_perizinan'),
                            'is_open' => $this->input->post('is_open'),
                            'c_foto' => $this->input->post('c_foto'),
                            'c_upload' => $this->input->post('c_upload'),
                            'c_keputusan' => $this->input->post('c_keputusan'),
                            'c_berlaku' => $this->input->post('c_berlaku'),
                            'c_tarif' => $c_tarif,
                            'v_hari' => $this->input->post('v_hari'),
                            'v_perizinan' => $this->input->post('v_perizinan'),
                            'v_berlaku_tahun' => $this->input->post('v_berlaku_tahun'),
                            'cek_bpjs' => $this->input->post('cek_bpjs'),
							'v_berlaku_satuan' => $this->input->post('v_berlaku_satuan')
                        ));

        $kel = new trperizinan();
        $kel->where('id', $this->input->post('id'))->get();

        $kel2 = new trkelompok_perizinan();
        $kel2->where('id', $this->input->post('opsi_klp'))->get();

        $kel->save($kel2);

        $table_gen_2 = new trperizinan_trunitkerja();

        if($table_gen_2->where('trperizinan_id',$this->input->post('id'))->count() < 1) {
            $table_gen_2->trunitkerja_id = $this->input->post('opsi_uk');
            $table_gen_2->trperizinan_id = $this->input->post('id');
            $table_gen_2->save();
        } else {
            $unit = new trunitkerja();
            $unit->where('id', $this->input->post('opsi_uk'))->get();

            $kel->save($unit);
        }

        ## BEGIN - Update Data Unit Akses ##
        $objAkses = new trperizinan_trunitkerja();
        //Hapus semua data Hak Akses untuk Perizinan tersebut
        $getExistingAkses = $objAkses->where('trperizinan_id', $trperizinanId)->where('relation_type',$this->_relationTypeAccess)->get();
        $getExistingAkses->delete_all();

        if($this->input->post('unit_akses') && is_array($this->input->post('unit_akses'))){
            foreach($this->input->post('unit_akses') as $indexUnitAkses=>$unitAkses){
                $objAksesNew = new trperizinan_trunitkerja();
                $objAksesNew->trunitkerja_id = $unitAkses;
                $objAksesNew->trperizinan_id = $trperizinanId;
                $objAksesNew->relation_type = $this->_relationTypeAccess;
                $objAksesNew->save();
            }
        }
        ## END - Update Data Unit Akses ##

        if($update) {
            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $p = $this->db->query("call log ('Setting Perizinan','Update ".$this->input->post('n_perizinan')."','".$tgl."','".$u_ser."')");
            redirect('perizinan');
        }
    }

    public function delete($id = NULL) {
        $izin = new trperizinan();
        $izin->where('id',$id)->get();
        $izin->delete($id);

        $izin2 = new trperizinan();
        $jml=$izin2->where('id',$id)->count();

        $objAkses = new trperizinan_trunitkerja();
        //Hapus semua data Hak Akses untuk Perizinan tersebut
        $getExistingAkses = $objAkses->where('trperizinan_id', $id)->where('relation_type',$this->_relationTypeAccess)->get();
        $getExistingAkses->delete_all();
        
        if($jml > 0){
                $this->session->set_flashdata('pesan', '<font color=red>' . "Data tidak bisa dihapus karena telah dipakai di modul lain". '!</font><br/>');
                redirect('perizinan');
        }else{
            redirect('perizinan');
            $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Perizinan','Delete ".$izin->n_perizinan."','".$tgl."','".$u_ser."')");
            redirect('perizinan');

        }


        
    }

    /*
     * Method for validating
     */

}

// This is the end of perizinan class