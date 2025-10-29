<?php

class Perubahan extends WRC_AdminCont {

    var $obj;

    /*
     * Variable for generating JSON.
     */
    var $iTotalRecords;
    var $iTotalDisplayRecords;

    /*
     * Variable that taken form input.
     */
    var $iDisplayStart;
    var $iDisplayLength;
    var $iSortingCols;
    var $sSearch;
    var $sEcho;

    private $_status_pendaftaran = 1;//Pendaftaran Sementara
    private $_status_penerimaan = 2;//Menerima dan Memeriksa Berkas

    public function __construct() {
        parent::__construct();
        $this->username = new user();
        $this->pendaftaran = new tmpermohonan();
        $this->perizinan = new trperizinan();
        $this->kelompok_izin = new trkelompok_perizinan();
        $this->jenispermohonan = new trjenis_permohonan();
        $this->propinsi = new trpropinsi();
        $this->kabupaten = new trkabupaten();
        $this->kecamatan = new trkecamatan();
        $this->kelurahan = new trkelurahan();
        $this->pemohon = new tmpemohon();
        $this->perusahaan = new tmperusahaan();
        $this->kegiatan = new trkegiatan();
        $this->investasi = new trinvestasi();
        $this->settings = new settings();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
		
        foreach ($list_auths as $list_auth) {
            if ($list_auth->id_role === '9' or $list_auth->id_role === '11') {
                $enabled = TRUE;
            }
        }

        if (!$enabled) {
            redirect('dashboard');
        }*/

        $this->jenis_id = "1"; // Izin Baru
    }

    public function permohonan()
    {
		
		$search = $this->input->post('no_pendaftaran');		
		if($search == '')
		{
			$query = "SELECT A.id, A.pendaftaran_id, A.c_paralel, A.d_terima_berkas,
                A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
                C.id idizin, C.n_perizinan, E.n_pemohon,E.no_referensi,
                G.id idjenis, G.n_permohonan, A.c_pendaftaran, J.n_unitkerja
                FROM tmpermohonan as A
                INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
                INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
                INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
                INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
                INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
                INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
                INNER JOIN trunitkerja J ON J.id = A.trunitkerja_id order by A.d_terima_berkas desc limit 0, 20
                ";
		}
		else
		{
			$query = "SELECT A.id, A.pendaftaran_id, A.c_paralel, A.d_terima_berkas,
                A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
                C.id idizin, C.n_perizinan, E.n_pemohon,E.no_referensi,
                G.id idjenis, G.n_permohonan, A.c_pendaftaran, J.n_unitkerja
                FROM tmpermohonan as A
                INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
                INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
                INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
                INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
                INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
                INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
                INNER JOIN trunitkerja J ON J.id = A.trunitkerja_id where A.pendaftaran_id like '%$search%' order by A.d_terima_berkas desc
                ";
		}
        
//      }
        
        
        $data['list'] = $query;
        $data['jenis_perubahan'] = 'permohonan';

        $this->load->vars($data);

        $js = "
                $(document).ready(function() {                    
                    
                    oTable = $('#pendaftaran').dataTable({
                            \"bJQueryUI\": true,
                            \"sPaginationType\": \"full_numbers\"
                    });
                } );
        ";
        
        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Perubahan Data Permohonan";
        $this->template->build('perubahan_list', $this->session_info);
    }

    public function pendataan()
    {
        $search = $this->input->post('no_pendaftaran');		
		if($search == '')
		{
			$query = "SELECT A.id, A.pendaftaran_id, A.c_paralel, A.d_terima_berkas,
                A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
                C.id idizin, C.n_perizinan, E.n_pemohon,E.no_referensi,
                G.id idjenis, G.n_permohonan, A.c_pendaftaran, J.n_unitkerja
                FROM tmpermohonan as A
                INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
                INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
                INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
                INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
                INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
                INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
                INNER JOIN trunitkerja J ON J.id = A.trunitkerja_id order by A.d_terima_berkas desc limit 0, 20
                ";
		}
		else
		{
			$query = "SELECT A.id, A.pendaftaran_id, A.c_paralel, A.d_terima_berkas,
                A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
                C.id idizin, C.n_perizinan, E.n_pemohon,E.no_referensi,
                G.id idjenis, G.n_permohonan, A.c_pendaftaran, J.n_unitkerja
                FROM tmpermohonan as A
                INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
                INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
                INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
                INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
                INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
                INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
                INNER JOIN trunitkerja J ON J.id = A.trunitkerja_id where A.pendaftaran_id like '%$search%' order by A.d_terima_berkas desc
                ";
		}
        
        
        $data['list'] = $query;
        $data['jenis_perubahan'] = 'pendataan';

        $this->load->vars($data);

        $js = "
                $(document).ready(function() {                    
                    
                    oTable = $('#pendaftaran').dataTable({
                            \"bJQueryUI\": true,
                            \"sPaginationType\": \"full_numbers\"
                    });
                } );
        ";
        
        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Perubahan Data Pendataan";
        $this->template->build('perubahan_list', $this->session_info);
    }

    public function bap()
    {
        $search = $this->input->post('no_pendaftaran');		
		if($search == '')
		{
			$query = "SELECT A.id, A.pendaftaran_id, A.c_paralel, A.d_terima_berkas,
                A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
                C.id idizin, C.n_perizinan, E.n_pemohon,E.no_referensi,
                G.id idjenis, G.n_permohonan, A.c_pendaftaran, J.n_unitkerja
                FROM tmpermohonan as A
                INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
                INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
                INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
                INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
                INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
                INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
                INNER JOIN trunitkerja J ON J.id = A.trunitkerja_id order by A.d_terima_berkas desc limit 0, 20
                ";
		}
		else
		{
			$query = "SELECT A.id, A.pendaftaran_id, A.c_paralel, A.d_terima_berkas,
                A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
                C.id idizin, C.n_perizinan, E.n_pemohon,E.no_referensi,
                G.id idjenis, G.n_permohonan, A.c_pendaftaran, J.n_unitkerja
                FROM tmpermohonan as A
                INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
                INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
                INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
                INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
                INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
                INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
                INNER JOIN trunitkerja J ON J.id = A.trunitkerja_id where A.pendaftaran_id like '%$search%' order by A.d_terima_berkas desc
                ";
		}
        
        
        $data['list'] = $query;
        $data['jenis_perubahan'] = 'bap';

        $this->load->vars($data);

        $js = "
                $(document).ready(function() {                    
                    
                    oTable = $('#pendaftaran').dataTable({
                            \"bJQueryUI\": true,
                            \"sPaginationType\": \"full_numbers\"
                    });
                } );
        ";
        
        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Perubahan Data BAP";
        $this->template->build('perubahan_list', $this->session_info);
    }

    public function tinjauan()
    {
        $search = $this->input->post('no_pendaftaran');		
		if($search == '')
		{
			$query = "SELECT A.id, A.pendaftaran_id, A.c_paralel, A.d_terima_berkas,
                A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
                C.id idizin, C.n_perizinan, E.n_pemohon,E.no_referensi,
                G.id idjenis, G.n_permohonan, A.c_pendaftaran, J.n_unitkerja
                FROM tmpermohonan as A
                INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
                INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
                INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
                INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
                INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
                INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
                INNER JOIN trunitkerja J ON J.id = A.trunitkerja_id order by A.d_terima_berkas desc limit 0, 20
                ";
		}
		else
		{
			$query = "SELECT A.id, A.pendaftaran_id, A.c_paralel, A.d_terima_berkas,
                A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
                C.id idizin, C.n_perizinan, E.n_pemohon,E.no_referensi,
                G.id idjenis, G.n_permohonan, A.c_pendaftaran, J.n_unitkerja
                FROM tmpermohonan as A
                INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
                INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
                INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
                INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
                INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
                INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
                INNER JOIN trunitkerja J ON J.id = A.trunitkerja_id where A.pendaftaran_id like '%$search%' order by A.d_terima_berkas desc
                ";
		}
        
        
        $data['list'] = $query;
        $data['jenis_perubahan'] = 'tinjauan';

        $this->load->vars($data);

        $js = "
                $(document).ready(function() {                    
                    
                    oTable = $('#pendaftaran').dataTable({
                            \"bJQueryUI\": true,
                            \"sPaginationType\": \"full_numbers\"
                    });
                } );
        ";
        
        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Perubahan Data Pendataan";
        $this->template->build('perubahan_list', $this->session_info);
    }

    public function retribusi()
    {

    }
}

?>