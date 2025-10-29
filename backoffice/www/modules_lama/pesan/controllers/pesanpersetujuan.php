<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of monitoring class
 *
 * @author  Yogi Cahyana
 * @since   1988
 *
 */
class pesanpersetujuan extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();

        $this->pengaduan = new tmpesan();
        $this->stspesan  = new trstspesan();
        $this->pesanpersetujuan = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->pesanpersetujuan = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '14') {
                $enabled = TRUE;
                $this->pesanpersetujuan = new user_auth();
            }
        }
		
        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
        $data['liststspesan'] = $this->stspesan->get();
        $data['list'] = $this->pengaduan->where('c_tindak_lanjut', 'Ya')
                                        ->where('c_sts_setuju','Tidak')->order_by('id', 'ASC')->get();

        $this->load->vars($data);

        $js = "
                $(document).ready(function() {
                        oTable = $('#pesan').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Persetujuan Respon Pengaduan";
        $this->template->build('listpersetujuan', $this->session_info);
    }

    public function edit($id_pesan = NULL) {

        $this->pengaduan->where('id', $id_pesan);
        $this->pengaduan->get();

        $stspesan = new trstspesan();
        $unitkerja = new trunitkerja();
        $data['list_unit'] = $unitkerja->order_by('id', 'DESC')->get();
        $data['list_pesan'] = $stspesan->order_by('id','DESC')->get()->all;
        $statuspesan = new trstspesan();

        $this->pengaduan->trstspesan->get();

        $data['id'] = $this->pengaduan->id;
        $data['e_pesan'] = $this->pengaduan->e_pesan;
        $data['e_pesan_koreksi'] = $this->pengaduan->e_pesan_koreksi;
        $data['nama'] = $this->pengaduan->nama;
        $data['alamat'] = $this->pengaduan->alamat;
        $data['kelurahan'] = $this->pengaduan->kelurahan;
        $data['kecamatan'] = $this->pengaduan->kecamatan;
        $data['sts_pengajuan'] = $this->pengaduan->c_sts_setuju;
        $data['c_skpd_tindaklanjut'] = $this->pengaduan->c_skpd_tindaklanjut;

         $js_date = "
            $(document).ready(function() {
                    $(\"#tabs\").tabs();
                } );

            $(function() {
                $(\"#pesan\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });
            ";
        $this->template->set_metadata_javascript($js_date);
        $data['jenis_status'] = $this->pengaduan->c_tindak_lanjut;
        $data['d_entry'] = $this->pengaduan->d_entry;
        $data['save_method'] = "update";

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Pesan";
        $this->template->build('edit_persetujuan', $this->session_info);
    }

    public function update() {
        $update = $this->pengaduan
                ->where('id', $this->input->post('id'))
                ->update(array(
                    'c_skpd_tindaklanjut' => $this->input->post('c_skpd_tindaklanjut'),
                    'c_sts_setuju' => $this->input->post('RbPersetujuan')));
        if($update) {

            $pesan = new tmpesan();
            $pesan->get_by_id($this->input->post('id'));
            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $g = $this->sql2($u_ser);
            $p = $this->db->query("call log ('Persetujuan Respon Pengaduan','Update ".$pesan->nama."','".$tgl."','".$u_ser."')");

            redirect('pesan/pesanpersetujuan');
        }
    }

     public function sql2($u_ser)
    {
        $query = "select a.description
	from user_auth as a
	inner join user_user_auth as  x on a.id = x.user_auth_id
	inner join user as b on b.id = x.user_id
        where b.id = (select id from user where username='".$u_ser."')";
        $hasil = $this->db->query($query);
        return $hasil->row();
    }

}

// This is the end of monitoring class
