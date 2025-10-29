<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of pararel class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */
class Paralel extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->paralel = new trparalel();
        $this->perizinan = new trperizinan();

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
        $data['list'] = $this->paralel->get();
        $this->load->vars($data);
        $js = "
                function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                        oTable = $('#paralel').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Perizinan Paralel";
        $this->template->build('list_paralel', $this->session_info);
    }

    public function add() {
        $js = "
             $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();
                } );
                $(document).ready(function() {
                    $('#listizin').multiselect().multiselectfilter({
                       show:'blind',
                       hide:'blind',
                       selectedText:'# of # selected'
                    });
					
					
				$('#form').submit(function()
				{					
					var myselect=document.getElementById('listizin');
					var jumlahcek=0;
					for (var i=0; i<myselect.options.length; i++)
					{
						 if (myselect.options[i].selected==true)
						 {
						  jumlahcek++;
						 }
					}
					if(jumlahcek == 0)
					{
						alert('Silahkan Pilih Izin terkait Minimal 1 pilihan.');
						return false;
					}
					else
					{
						return true;
					}				
				})	
					
					
                });";

        $this->template->set_metadata_javascript($js);
        $data['list'] = $this->perizinan->get();

        $data['list_izin_paralel'] = "";
        $data['izin_paralel'] = "";
        $data['id_paralel'] = "";
        $data['save_method'] = "save";
        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit/Tambah Perizinan Paralel";
        $this->template->build('edit_paralel', $this->session_info);
    }

    public function save() {

        $this->paralel->n_paralel = $this->input->post('izin_paralel');
        $this->paralel->save();

        $listizin = $this->input->post('listizin');

        $this->paralel->where('n_paralel', $this->input->post('izin_paralel'))->get();
        $id_pararel = $this->paralel->id;

        foreach ($listizin as $list) {
            $this->paralel->where('n_paralel', $this->input->post('izin_paralel'))->get();
            $this->perizinan->where('id', $list)->get();

            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $p = $this->db->query("call log ('Setting Perizinan','Insert izin pararel " . $this->input->post('izin_paralel') . "','" . $tgl . "','" . $u_ser . "')");

            $this->paralel->save($this->perizinan);
        }

        redirect('perizinan/paralel/');
    }

    public function delete($id_paralel = NULL) {

        $pararel = $this->getPararel($id_paralel);
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Perizinan','Delete izin pararel " . $pararel->n_paralel . "','" . $tgl . "','" . $u_ser . "')");

        $this->paralel->where('id', $id_paralel)->get();
        $this->paralel->delete();

//        $jmlh = $this->paralel->where('id', $id_paralel)->count();
//        if ($jmlh > 0) {
//            $this->session->set_flashdata('pesan', '<font color=red>' . "Data tidak bisa dihapus karena telah dipakai di modul lain" . '!</font><br/>');
//            redirect('perizinan/paralel');
//        } else {
//            redirect('perizinan/paralel');
//        }
           redirect('perizinan/paralel');
    }

    public function detail($id_paralel = NULL) {
        $this->paralel->where('id', $id_paralel)->get();
        $data['list'] = $this->paralel->trperizinan->get();
        $data['id_paralel'] = $id_paralel;
        $this->load->vars($data);
        $js = "
                  function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                        oTable = $('#paralel').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Detail Perizinan Paralel";
        $this->template->build('list_paralel_detail', $this->session_info);
    }

    public function deletedetail($id_paralel = NULL, $id_izin = NULL) {

/*        $pararel = $this->getPararel($id_paralel);
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Perizinan','Delete detail izin pararel " . $pararel->n_paralel . "','" . $tgl . "','" . $u_ser . "')");
*/

       $trparalel_trperizinan = new trparalel_trperizinan();
        $trparalel_trperizinan
                ->where('trparalel_id', $id_paralel)
                ->where('trperizinan_id', $id_izin)
                ->delete();

		
		//echo $trparalel_trperizinan; die();
        //$trparalel_trperizinan->delete();

        redirect('perizinan/paralel/detail/' . $id_paralel);
    }

    public function adddetail($id_paralel = NULL) {
        $js = "
                $(document).ready(function(){
                    $(\"#tabs\").tabs();
                });
                $(document).ready(function() {
                    $('#listizin').multiselect().multiselectfilter({
                       show:'blind',
                       hide:'blind',
                       selectedText:'# of # selected'
                    });
                });";

        $this->template->set_metadata_javascript($js);
        $data['list'] = $this->perizinan->get();

        $paralel = $this->paralel->where('id', $id_paralel)->get();
        $perizinan = new trperizinan();
        $data['list_izin_paralel'] = $perizinan->where_related($paralel)->get();
        $this->paralel->where('id', $id_paralel)->get();
        $data['izin_paralel'] = $this->paralel->n_paralel;
        $data['id_paralel'] = $id_paralel;
        $data['save_method'] = "update";
        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit/Tambah Perizinan Paralel";
        $this->template->build('edit_paralel', $this->session_info);
    }

    public function update() {
        $id_pararel = $this->input->post('id_paralel');
        $listizin = $this->input->post('listizin');

        $pararel = $this->getPararel($id_pararel);
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Perizinan','Update izin pararel " . $pararel->n_paralel . "','" . $tgl . "','" . $u_ser . "')");


        $paralel = new trparalel();
        $paralel->get_by_id($id_pararel);
        foreach ($listizin as $list) {


            $this->perizinan->where('id', $list)->get();
            $paralel->save($this->perizinan);
        }

        redirect('perizinan/paralel/');
//        redirect('perizinan/paralel/detail/'.$id_paralel);
    }

    public function getPararel($id) {
        $query = "select n_paralel from trparalel where id='" . $id . "'";
        $hasil = $this->db->query($query);
        return $hasil->row();
    }

}

// This is the end of pararel class
