<?php

/**
 * Description of format kode penomoran
 *
 * @author Sani
 */
class formatpenomoran extends WRC_AdminCont {
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('trformatpenomoran');
		$this->format = new trformatpenomoran();
		$this->perizinan = new trperizinan();
		
		$this->jenis_permohonan = array(
									1 => 'Permohonan Baru',
									2 => 'Permohonan Perubahan',
									3 => 'Permohonan Perpanjangan'
								);
	}
	
	public function index()
	{		
		$data["list"] = $this->format->get();
		$data['ket_exist'] = NULL;
		$data["perizinan"] = $this->perizinan->get();
		$data["jenis_permohonan"] = $this->jenis_permohonan;
		$this->load->vars($data);
        $js =  "
            function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                        oTable = $('#user').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                });";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Kode Format Penomoran";
        $this->template->build('formatpenomoran', $this->session_info);
	}
	
	public function create()
	{
		$data["perizinan"] = $this->perizinan->get();
		$data["jenis_permohonan"] = $this->jenis_permohonan;
		$data['save_method'] = "save";
		$data['id'] = "";
		$this->load->vars($data);
		
		$this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Tambah Kode Format Penomoran";
        $this->template->build('create_formatpenomoran', $this->session_info);
	}
	
	public function save()
	{
		$perizinan = $this->input->post('perizinan');
		$permohonan = $this->input->post('permohonan');
		$format = $this->input->post('format');
		
		$this->format->where("id_perizinan", $perizinan)->where("id_jenis",$permohonan)->get();
		
		if($this->format->id)
		{
			$this->session->set_flashdata('flash_message', array('message' => 'Maaf, Kode Format sudah ada','class' => 'error'));
            redirect('penomoran/formatpenomoran');
		}
		else
		{
			$this->format->id_perizinan = $perizinan;
			$this->format->id_jenis = $permohonan;
			$this->format->format = $format;
			
			$this->format->save();
			
			$this->session->set_flashdata('flash_message', array('message' => 'Data berhasil disimpan','class' => 'success'));
             redirect('penomoran/formatpenomoran');
		}
	}
	
	public function delete($id = NULL) {
        $this->format->where('id', $id)->get();
        
        if($this->format->delete()) {
            $this->session->set_flashdata('flash_message', array('message' => 'Data berhasil dihapus','class' => 'success'));
            redirect('penomoran/formatpenomoran');
        }else{
			$this->session->set_flashdata('flash_message', array('message' => 'Data gagal dihapus','class' => 'error'));
            redirect('penomoran/formatpenomoran');
		}
    }
	
	public function edit($id = NULL)
	{
		if($id == NULL)
		{
			redirect('penomoran/formatpenomoran');
		}
		else
		{
			$data["perizinan"] = $this->perizinan->get();
			$data["jenis_permohonan"] = $this->jenis_permohonan;
			$data['save_method'] = "update";
			$data['id'] = $id;
			$data['list'] = $this->format->where('id', $id)->get();
			$this->load->vars($data);
			
			$this->template->set_metadata_javascript($js);
			$this->session_info['page_name'] = "Edit Kode Format Penomoran";
			$this->template->build('edit_formatpenomoran', $this->session_info);
		}
	}
	
	public function update()
	{
		$perizinan = $this->input->post('perizinan');
		$permohonan = $this->input->post('permohonan');
		$format = $this->input->post('format');
		$id = $this->input->post('id');
		
		$data = $this->format
					->where('id', $id)
					->update(array(
						"id_perizinan" => $perizinan,
						"id_jenis" => $permohonan,
						"format" => $format
					));
					
		if($data){
            $this->session->set_flashdata('flash_message', array('message' => 'Data berhasil diubah','class' => 'success'));
            redirect('penomoran/formatpenomoran');
        }else{
			$this->session->set_flashdata('flash_message', array('message' => 'Data gagal diubah','class' => 'error'));
            redirect('penomoran/formatpenomoran');
		}
	}
}