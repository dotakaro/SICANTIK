<?php

/**
 * Description of Permohonan Pengajuan Rekomendasi
 *
 * @author agusnur
 * Created : 02 Sep 2010
 */
class Rekomendasi extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->daftar = new tmpermohonan();
        $this->surat = new tmsurat_permohonan();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '3') {
                $enabled = TRUE;
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    /*
     * edit is a method to show page for updating data
     */
    public function edit($id_daftar = NULL, $id_link = NULL) {
        $daftar = $this->daftar->get_by_id($id_daftar);
        $surat = $daftar->tmsurat_permohonan->get();

        if($surat->id){
            $save = "update";
            $id_surat = $surat->id;
            $no_surat = $surat->no_surat;
            $tgl_surat = $surat->tgl_surat;
            $lampiran = $surat->lampiran;
            $keterangan = $surat->keterangan;
        }else{
            $save = "save";
            $id_surat = "";
            $no_surat = "";
            $tgl_surat = "";
            $lampiran = "";
            $keterangan = "";
        }
        $data['save_method'] = $save;
        $data['daftar'] = $daftar;
        $data['id_link'] = $id_link;
        $data['id_daftar'] = $id_daftar;
        $data['id_surat'] = $id_surat;
        $data['no_surat'] = $no_surat;
        $data['tgl_surat'] = $tgl_surat;
        $data['lampiran'] = $lampiran;
        $data['keterangan'] = $keterangan;

        $js =  "
                var base_url = '". base_url() ."';
                $(document).ready(function() {
                    $(\"#tabs\").tabs();
                    $(\"#inputTanggal1\").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'yy-mm-dd',
                        closeText: 'X'
                    });
                    $(\"#inputTanggal1\").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'yy-mm-dd',
                        closeText: 'X'
                    });
                    $('#form').validate();


                } );
            ";

        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Surat Permohonan Rekomendasi";
        $this->template->build('rekomendasi_edit', $this->session_info);
        
    }

    /*
     * Save and update for manipulating data.
     */
    public function save() {
        /* Input Data */
        $this->surat->no_surat = $this->input->post('no_surat');
        $this->surat->tgl_surat = $this->input->post('tgl_surat');
        $this->surat->lampiran = $this->input->post('lampiran');
        $this->surat->keterangan = $this->input->post('keterangan');

        /* Input Relasi Tabel*/
        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($this->input->post('id_daftar'));

        /* Input Data Tracking Progress */
        $sts_izin = new trstspermohonan();
        $sts_izin->get_by_id('2'); //Menerima dan Memeriksa Berkas [Lihat Tabel trstspermohonan()]
        $data_status = new tmtrackingperizinan_trstspermohonan();
        $list_tracking = $permohonan->tmtrackingperizinan->get();
        if($list_tracking){
            foreach ($list_tracking as $data_track){
                $tracking_id = 0;
                $data_status = new tmtrackingperizinan_trstspermohonan();
                $data_status->where('tmtrackingperizinan_id', $data_track->id)
                ->where('trstspermohonan_id', $sts_izin->id)->get();
                if($data_status->tmtrackingperizinan_id){
                    $tracking_id = $data_status->tmtrackingperizinan_id;
                }
            }
        }
        $tracking_izin = new tmtrackingperizinan();
        $tracking_izin->get_by_id($tracking_id);
        //$tracking_izin->pendaftaran_id = $permohonan->pendaftaran_id;
        $tracking_izin->status = 'Update';
        $tracking_izin->d_entry = $this->lib_date->get_date_now();
        $tracking_izin->save();
//            $tracking_izin->save($sts_izin);
//            $tracking_izin->save($permohonan);

        if(! $this->surat->save(array($permohonan))) {
            echo '<p>' . $this->surat->error->string . '</p>';
        } else {
            redirect('pelayanan/rekomendasi/edit/'.$this->input->post('id_daftar'));
        }
    }

    public function update() {
        $surat = new tmsurat_permohonan();
        $surat->get_by_id($this->input->post('id_surat'));
        $surat->no_surat = $this->input->post('no_surat');
        $surat->tgl_surat = $this->input->post('tgl_surat');
        $surat->lampiran = $this->input->post('lampiran');
        $surat->keterangan = $this->input->post('keterangan');

        /* Input Relasi Tabel*/
        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($this->input->post('id_daftar'));

        /* Input Data Tracking Progress */
        $sts_izin = new trstspermohonan();
        $sts_izin->get_by_id('2'); //Menerima dan Memeriksa Berkas [Lihat Tabel trstspermohonan()]
        $data_status = new tmtrackingperizinan_trstspermohonan();
        $list_tracking = $permohonan->tmtrackingperizinan->get();
        if($list_tracking){
            foreach ($list_tracking as $data_track){
                $tracking_id = 0;
                $data_status = new tmtrackingperizinan_trstspermohonan();
                $data_status->where('tmtrackingperizinan_id', $data_track->id)
                ->where('trstspermohonan_id', $sts_izin->id)->get();
                if($data_status->tmtrackingperizinan_id){
                    $tracking_id = $data_status->tmtrackingperizinan_id;
                }
            }
        }
        $tracking_izin = new tmtrackingperizinan();
        $tracking_izin->get_by_id($tracking_id);
        //$tracking_izin->pendaftaran_id = $permohonan->pendaftaran_id;
        $tracking_izin->status = 'Update';
        $tracking_izin->d_entry = $this->lib_date->get_date_now();
        $tracking_izin->save();
//            $tracking_izin->save($sts_izin);
//            $tracking_izin->save($permohonan);
        
        $update = $surat->save();
        if($update) {
            redirect('pelayanan/rekomendasi/edit/'.$this->input->post('id_daftar'));
        }
    }

    public function cetak($id_daftar = NULL) {
        $nama_surat = "cetak_rekomendasi";
        $this->settings = new settings();
        $this->settings->where('name','app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name','app_city')->get();

        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($id_daftar);
        $pemohon = $permohonan->tmpemohon->get();
        $perusahaan = $permohonan->tmperusahaan->get();
        $surat = $permohonan->tmsurat_permohonan->get();
        $jenis_izin = $permohonan->trperizinan->get();
        $dinas = $permohonan->trperizinan->trunitkerja->get();

        //path of the template file
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/'.$nama_surat.'.odt');
        //$odf->setImage('header', 'assets/css/'.$app_folder.'/images/dinas_1.jpg', '17.5', '3.5');
         //logo
        $this->tr_instansi = new Tr_instansi();
        $logo = $this->tr_instansi->get_by_id(14);
          if($logo->value!=="")
            {
                $odf->setImage('logo', 'uploads/logo/' . $logo->value, '1.7', '1.7');
            }
        else
            {
                 $odf->setVars('logo', ' ');
            }
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
        $alamat = $this->tr_instansi->get_by_id(12);
        $app_city = $this->sql();
         if (isset($app_city->n_kabupaten))
            {
              $gede_kota=strtoupper($app_city->n_kabupaten);
              $kecil_kota=ucwords(strtolower($app_city->n_kabupaten));
              $odf->setVars('kota4', $gede_kota);
              $odf->setVars('kota', $app_city->ibukota);
                if (isset($alamat->value))
                {
                    $odf->setVars('alamat', ucwords(strtolower($alamat->value)).' - '.$kecil_kota);
                }
                else
                {
                    $odf->setVars('alamat','--------');  
                }
             }
           
         else
            {
              $odf->setVars('kota4','---------');
              $odf->setVars('kota','--------');
              $odf->setVars('alamat','--------');  
            }
        //fill the template with the variables
        $petugas = 1; //1 -> Jabatan Penandatangan
        $pegawai = new tmpegawai();
        $pegawai->where('status', $petugas)->get();
        $odf->setVars('no_surat', $surat->no_surat);
        $odf->setVars('lampiran', $surat->lampiran);
        if($surat->tgl_surat)
        $odf->setVars('tgl_rekomendasi', $this->lib_date->get_day($surat->tgl_surat) . ', tanggal ' . $this->lib_date->mysql_to_human($surat->tgl_surat));
        else
        $odf->setVars ('tgl_rekomendasi', '');
        $odf->setVars('dinas_teknis', $dinas->n_unitkerja);
        $odf->setVars('nama_izin', $jenis_izin->n_perizinan);
        $odf->setVars('nama_pemohon', $pemohon->n_pemohon);
        $odf->setVars('tgl_daftar', $this->lib_date->mysql_to_human($permohonan->d_terima_berkas));
        $odf->setVars('jabatan', $pegawai->n_jabatan);
        $odf->setVars('nama_pejabat', strtoupper($pegawai->n_pegawai));
        $odf->setVars('nip_pejabat', $pegawai->nip);
        /*$wilayah = new trkabupaten();
        if($app_city->value !== '0'){
            $wilayah->get_by_id($app_city->value);
            $odf->setVars('kota', ucwords(strtolower($wilayah->n_kabupaten)));
        }else{
            $odf->setVars('kota', '...........');
        }
        $odf->setVars('kota', '');*/
        $odf->setVars('tglskr', $this->lib_date->mysql_to_human($this->lib_date->get_date_now()));

        if($dinas->id == 20)
        $usaha = "Fas.Yan.Kes";
        else
        $usaha = "Perusahaan";

        $listeArticles = array(
                array(	'property' => 'Nama Pemohon',
                        'content' => $pemohon->n_pemohon,
                ),
                array(	'property' => 'Alamat',
                        'content' => $pemohon->a_pemohon,
                ),
                array(	'property' => 'Nama '.$usaha,
                        'content' => $perusahaan->n_perusahaan,
                ),
                array(	'property' => 'Alamat',
                        'content' => $perusahaan->a_perusahaan,
                ),
        );
        $article = $odf->setSegment('articles');
        foreach($listeArticles AS $element) {
                $article->titreArticle($element['property']);
                $article->texteArticle($element['content']);
                $article->merge();
        }
        $odf->mergeSegment($article);

        //export the file
        $no_daftar = str_replace('/', '', $permohonan->pendaftaran_id);
        $odf->exportAsAttachedFile($nama_surat.'_'.$no_daftar.'.odt');
    }
    
    public function sql()
    {
        $query = "select a.n_kabupaten, a.ibukota from trkabupaten a where
            a.id = (select value from settings where name='app_city')";

        $sql = $this->db->query($query);
        return $sql->row();

    }

}
