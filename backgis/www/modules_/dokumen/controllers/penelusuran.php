<?php

/**
 * Description of Penelusuran Dokumen
 *
 * @author agusnur
 * Created : 29 Sep 2010
 */

class Penelusuran extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->permohonan = new tmpermohonan();
        $this->sk = new tmsk();
        $this->perizinan = new trperizinan();
        $this->archive = new tmarchive();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '4') {
                $enabled = TRUE;
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function save_ttd() {
        $id_daftar = $this->input->post('id_daftar');
        $file_ttd = $_FILES['file_ttd'];

        /* (pretty self-explanatory) */
        $nama_gambar = 'ttd_'.$id_daftar.".png";
        $pathfile = "assets/upload/ttd/";

        if($file_ttd['size'] > 0){
            copy($file_ttd['tmp_name'], $pathfile.$nama_gambar);

            $daftar = new tmpermohonan();
            $daftar->get_by_id($id_daftar);
            $daftar->file_ttd = $nama_gambar;

            $update = $daftar->save();
        }

        if(!$update) {
            echo '<p>' . $daftar->error->string . '</p>';
        } else {
            redirect('dokumen/penelusuran/detail/'.$id_daftar);
        }
    }

    public function save_syarat() {
        $id_daftar = $this->input->post('id_daftar');
        $id_syarat = $this->input->post('id_syarat');
        $file_ttd = $_FILES['file_syarat'];

        /* (pretty self-explanatory) */
        if(substr($file_ttd['type'], 0, 5) === 'image')
        $nama_file = 'file_'.$id_daftar."_".$id_syarat.".jpg";
        else
        $nama_file = 'file_'.$id_daftar."_".$id_syarat.".doc";
        $pathfile = "assets/upload/syarat/";

        if($file_ttd['size'] > 0){
            copy($file_ttd['tmp_name'], $pathfile.$nama_file);

            $archive_syarat = new tmarchive_syarat();
            $archive_syarat
            ->where('tmpermohonan_id', $id_daftar)
            ->where('trsyarat_perizinan_id', $id_syarat)
            ->get();
            if($archive_syarat->id) $archive_syarat->get_by_id($archive_syarat->id);
            $archive_syarat->tmpermohonan_id = $id_daftar;
            $archive_syarat->trsyarat_perizinan_id = $id_syarat;
            $archive_syarat->file_doc = $nama_file;

            $update = $archive_syarat->save();
        }

        if(!$update) {
            echo '<p>' . $daftar->error->string . '</p>';
        } else {
            redirect('dokumen/penelusuran/detail/'.$id_daftar);
        }
    }

    public function save_skrd() {
        $id_daftar = $this->input->post('id_daftar');
        $id_surat = $this->input->post('id_surat');
        $file_ttd = $_FILES['file_doc'];

        /* (pretty self-explanatory) */
        $nama_gambar = 'skrd_'.$id_daftar.".jpg";
        $pathfile = "assets/upload/syarat/";

        if($file_ttd['size'] > 0){
            copy($file_ttd['tmp_name'], $pathfile.$nama_gambar);

            $daftar = new tmbap();
            $daftar->get_by_id($id_surat);
            $daftar->file_doc = $nama_gambar;

            $update = $daftar->save();
        }

        if(!$update) {
            echo '<p>' . $daftar->error->string . '</p>';
        } else {
            redirect('dokumen/penelusuran/detail/'.$id_daftar);
        }
    }

    public function save_izin() {
        $id_daftar = $this->input->post('id_daftar');
        $id_surat = $this->input->post('id_surat');
        $file_ttd = $_FILES['file_doc'];

        /* (pretty self-explanatory) */
        $nama_gambar = 'izin_'.$id_daftar.".jpg";
        $pathfile = "assets/upload/syarat/";

        if($file_ttd['size'] > 0){
            copy($file_ttd['tmp_name'], $pathfile.$nama_gambar);

            $daftar = new tmsk();
            $daftar->get_by_id($id_surat);
            $daftar->file_doc = $nama_gambar;

            $update = $daftar->save();
        }

        if(!$update) {
            echo '<p>' . $daftar->error->string . '</p>';
        } else {
            redirect('dokumen/penelusuran/detail/'.$id_daftar);
        }
    }

    public function index() {
        $daftar = new tmpermohonan();
        $jenis_izin = $this->input->post('jenis_izin');
        $jenis_kegiatan = $this->input->post('jenis_kegiatan');
        $jenis_investasi = $this->input->post('jenis_investasi');
        $year_id = $this->input->post('year_id');
        $cek_izin = $this->input->post('cek_izin');
        $cek_kegiatan = $this->input->post('cek_kegiatan');
        $cek_investasi = $this->input->post('cek_investasi');
        
        $data['jenis_izin'] = $jenis_izin;
        $data['jenis_kegiatan'] = $jenis_kegiatan;
        $data['jenis_investasi'] = $jenis_investasi;
        $data['year_id'] = $year_id;
//        if($cek_izin) $data['cek_izin'] = $cek_izin;
//        else $data['cek_izin'] = "";
        if($cek_kegiatan) $data['cek_kegiatan'] = $cek_kegiatan;
        else $data['cek_kegiatan'] = "";
        if($cek_investasi) $data['cek_investasi'] = $cek_investasi;
        else $data['cek_investasi'] = "";
        $perizinan = $this->perizinan->order_by('id','ASC')->get();
        $data['list_izin'] = $perizinan;
        $data_izin = new trperizinan();
        $data_izin->get_by_id($jenis_izin);
        $data_kegiatan = new trkegiatan();
        $data_kegiatan->get_by_id($jenis_kegiatan);
        $data_investasi = new trinvestasi();
        $data_investasi->get_by_id($jenis_investasi);

        if($cek_investasi && $cek_kegiatan){
            $data_usaha = new tmperusahaan();
            $data_usaha->where_related($data_kegiatan)
            ->where_related($data_investasi)->get();
            $data_list = $daftar
            ->where_related($data_usaha)
            ->where_related($data_izin)
            ->where('c_izin_selesai', 1) // 1 -> izin telah diserahkan
            ->where('LEFT(d_terima_berkas, 4) =', $year_id)
            ->order_by('d_terima_berkas', 'DESC')->get();
        }else if($cek_kegiatan){
            $data_usaha = new tmperusahaan();
            $data_usaha->where_related($data_kegiatan)->get();
            $data_list = $daftar
            ->where_related($data_usaha)
            ->where_related($data_izin)
            ->where('c_izin_selesai', 1) // 1 -> izin telah diserahkan
            ->where('LEFT(d_terima_berkas, 4) =', $year_id)
            ->order_by('d_terima_berkas', 'DESC')->get();
        }else if($cek_investasi){
            $data_usaha = new tmperusahaan();
            $data_usaha->where_related($data_investasi)->get();
            $data_list = $daftar
            ->where_related($data_usaha)
            ->where_related($data_izin)
            ->where('c_izin_selesai', 1) // 1 -> izin telah diserahkan
            ->where('LEFT(d_terima_berkas, 4) =', $year_id)
            ->order_by('d_terima_berkas', 'DESC')->get();
        }else{
            if($this->input->post('jenis_izin')){
                $data_list = $daftar
                ->where_related($data_izin)
                ->where('c_izin_selesai', 1) // 1 -> izin telah diserahkan
                ->where('LEFT(d_terima_berkas, 4) =', $year_id)
                ->order_by('d_terima_berkas', 'DESC')
                ->get();
            }else{
                $data_list = $daftar
                ->where_related($data_izin)
                ->where('c_izin_selesai', 1) // 1 -> izin telah diserahkan
                ->order_by('d_terima_berkas', 'DESC')
                ->limit(0)->get();
            }
        }
        
        $data['list'] = $data_list;
        $kegiatan = new trkegiatan();
        $data['list_kegiatan'] = $kegiatan->order_by('n_kegiatan','ASC')->get();
        $investasi = new trinvestasi();
        $data['list_investasi'] = $investasi->order_by('n_investasi','ASC')->get();
        $data['c_bap'] = "1";
        $this->load->vars($data);

        $js =  "$(document).ready(function() {
                        oTable = $('#penyerahan').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );

                $(function() {
                    $(\"#inputTanggal\").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'yy-mm-dd',
                        closeText: 'X'
                    });
                });
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Penelusuran Dokumen";
        $this->template->build('penelusuran_list', $this->session_info);
    }

    public function detail($id_daftar = NULL) {
        $permohonan = $this->permohonan->get_by_id($id_daftar);
        $data_izin = $permohonan->trperizinan->get();
        $pemohon = $permohonan->tmpemohon->get();
        $dokumen = $pemohon->tmarchive->get();

        $data['pathfile'] = "assets/upload/syarat/";
        $data['pathttd'] = "assets/upload/ttd/";
        $data['id_daftar'] = $id_daftar;
        $data['index_dokumen'] = $dokumen;
        $data['data_permohonan'] = $permohonan;
        $data['syarat_izin'] = $data_izin->trsyarat_perizinan->get();

        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                    $(\"#tabs\").tabs();
                    $(\"#tabs2\").tabs();
                } );
                
                $(document).ready(function() {
                        oTable = $('#dokumen').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);

        $this->session_info['page_name'] = "Pengelolaan Dokumen";
        $this->template->build('penelusuran_detail', $this->session_info);
    }

    public function detail_index($id_index = NULL) {
        $dokumen = $this->archive->get_by_id($id_index);
        $pemohon = $dokumen->tmpemohon->get();

        $data['index_dokumen'] = $dokumen->i_archive;
        $data['data_pemohon'] = $pemohon;
        $bap = new tmbap();
        $data['list_bap'] = $bap->get();
        $data['c_bap'] = "1";

        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                    $(\"#tabs\").tabs();
                    $(\"#tabs2\").tabs();
                } );

                $(document).ready(function() {
                        oTable = $('#dokumen').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);

        $this->session_info['page_name'] = "Pengelolaan Dokumen";
        $this->template->build('penelusuran_index', $this->session_info);
    }
}

// This is the end of role class
