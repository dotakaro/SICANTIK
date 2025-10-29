<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of retribusi class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class Retribusi extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->retribusi = new trretribusi();
        $this->perizinan = new trperizinan();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        $this->retribusi = NULL;
        $this->perizinan = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '1') {
                $enabled = TRUE;
                $this->retribusi = new trretribusi();
                $this->perizinan = new trperizinan();
            }
        }
		
        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
        $data['list'] = $this->retribusi->get();
        $this->load->vars($data);
        $js =  "
                function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                        oTable = $('#retribusi').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                });";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Nilai Retribusi";
        $this->template->build('list', $this->session_info);
    }

    public function delete($id = NULL) {

        $perizinan = new trperizinan();
        $perizinan->where_related('trretribusi','id',$id)->get();
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Perizinan','Delete nilai retribusi ".$perizinan->n_perizinan."','".$tgl."','".$u_ser."')");

        $this->retribusi->where('id', $id)->get();
        $this->retribusi->delete();
        $this->index();
    }

    public function create($warning = NULL) {
        $data['save_method'] = 'save';
        $data['id'] = "";
        $data['perizinan_id'] = $this->input->post('perizinan_id');
        $data['v_retribusi'] = $this->input->post('v_retribusi');
        $data['v_denda'] = $this->input->post('v_denda');
        $data['d_sk_berakhir'] = $this->input->post('d_sk_berakhir');
        $data['d_sk_terbit'] = $this->input->post('d_sk_terbit');
        $data['c_account'] = $this->input->post('c_account');
        $data['metode']="";
        
        $data['warning'] = $warning;        

        $js_date = "
            $(document).ready(function(){
                $('#form').validate();
                $(\"#tabs\").tabs();
            });
            $(function() {
                $(\"#d_sk_terbit\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
                $(\"#d_sk_berakhir\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
            ";
        $this->template->set_metadata_javascript($js_date);
        $izin = new trperizinan();
        $data['izin'] = $izin->where('c_tarif',1)->get();
        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Nilai Retribusi";
        $this->template->build('edit', $this->session_info);
    }

    public function save() {
        $perhitungan = $this->input->post('manual');
        if ($perhitungan==="0")
        {
            $retribusi = $this->input->post('v_retribusi');
            if (empty($retribusi))
            {
               $this->session->set_flashdata('warning','<font color="red"> Wajib Diisi</font>');
                redirect('retribusi/create');
            }
        }

        $perizinan = new trperizinan();
        $perizinan->where('id',$this->input->post('perizinan_id'))->get();
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Perizinan','Insert nilai retribusi ".$perizinan->n_perizinan."','".$tgl."','".$u_ser."')");

        $this->retribusi->m_perhitungan = $this->input->post('manual');
        $this->retribusi->id = $this->input->post('id');
        $this->retribusi->v_retribusi = $this->input->post('v_retribusi');
        $this->retribusi->v_denda = $this->input->post('v_denda');
        $this->retribusi->d_sk_berakhir = $this->input->post('d_sk_berakhir');
        $this->retribusi->d_sk_terbit = $this->input->post('d_sk_terbit');
        $this->retribusi->c_account = $this->input->post('c_account');

        $this->perizinan->where('id', $this->input->post('perizinan_id'))->get();
        $cekdulu = $this->cek_retribusi($this->input->post('perizinan_id'),'num_rows');
        if ($cekdulu >= '1')
        {
            $warning = '<font color="red"> Retribusi "'.$perizinan->n_perizinan.'" telah di setting</font>';
            $this->create($warning);            
        }
        else if ($this->input->post('perizinan_id')=='2' && $perhitungan=='0')
        {
            $warning = '<font color="red"> Izin mendirikan bangunan tidak bisa otomatis</font>';
            $this->create($warning);               
        }
        else
        {
            $this->retribusi->save($this->perizinan);
            redirect('retribusi');     
        } 
    }
    
     public function cek_retribusi($id,$mode)
    {
        $query = "select * from trretribusi as a
        inner join trperizinan_trretribusi as b on b.trretribusi_id=a.id
        inner join trperizinan as c on c.id=b.trperizinan_id
         where c.id='".$id."'";
         
        $sql = $this->db->query($query);
        return $sql->$mode();
    }

    public function update() {

        $perhitungan = $this->input->post('manual');
        if ($perhitungan == "0")
        {
            $retribusi = $this->input->post('v_retribusi');
            if (empty($retribusi))
            {
               $this->session->set_flashdata('warning','<font color="red"> Wajib Diisi</font>');
               redirect('retribusi/edit/'.$this->input->post('id'));
            }
        }
        $perizinan = new trperizinan();
        $perizinan->where('id',$this->input->post('perizinan_id'))->get();
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Perizinan','Update nilai retribusi ".$perizinan->n_perizinan."','".$tgl."','".$u_ser."')");
        
//         if ($this->input->post('perizinan_id')=='2' && $perhitungan=='0')
//        {
//            $this->session->set_flashdata('warning','<font color="red">Izin mendirikan bangunan tidak bisa otomatis</font>');
//            redirect('retribusi/edit/'.$this->input->post('id'));            
//        }
        
        $this->retribusi
                ->where('id', $this->input->post('id'))
                ->update(array(
            'v_retribusi' => $this->input->post('v_retribusi'),
            'v_denda' => $this->input->post('v_denda'),
            'd_sk_berakhir' => $this->input->post('d_sk_berakhir'),
            'd_sk_terbit' => $this->input->post('d_sk_terbit'),
            'c_account' => $this->input->post('c_account'),
            'm_perhitungan' => $this->input->post('manual'),

        ));
        
        //$this->retribusi->check_last_query();
        //var_dump($this->retribusi->error);
        
        $ret = new trperizinan_trretribusi();
        $ret
            ->where('trretribusi_id', $this->input->post('id'))
            ->update(array(
            'trperizinan_id' => $this->input->post('perizinan_id'),
            'trretribusi_id' => $this->input->post('id')
        ));

        redirect('retribusi');
    }

    public function edit($id = NULL) {
        $data['save_method'] = 'update';
        $data['id'] = $id;
        $data['warning'] = "";
        $this->retribusi->where('id', $id)->get();
        $this->retribusi->trperizinan->get();
        
        $data['perizinan_id'] = $this->retribusi->trperizinan->id;
        $data['v_retribusi'] = $this->retribusi->v_retribusi;
        $data['v_denda'] = $this->retribusi->v_denda;
        $data['d_sk_berakhir'] = $this->retribusi->d_sk_berakhir;
        $data['d_sk_terbit'] = $this->retribusi->d_sk_terbit;
        $data['c_account'] = $this->retribusi->c_account;
        $data['metode'] = $this->retribusi->m_perhitungan;

        $js_date = "
            $(document).ready(function(){
                $(\"#tabs\").tabs();
                $('#form').validate();
            });
            $(function() {
                $(\"#d_sk_terbit\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
                $(\"#d_sk_berakhir\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
            ";
        $this->template->set_metadata_javascript($js_date);

        $izin = new trperizinan();
        $data['izin'] = $izin->where('c_tarif',1)->get();
        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Nilai Retribusi";
        $this->template->build('edit', $this->session_info);
    }

}

// This is the end of retribusi class
