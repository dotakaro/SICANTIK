<?php

/**
 * Description of cetak
 *
 * @author alfaridi
 * 
 */
class Cetak extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->perizinan = new trperizinan();
    }

    public function index() {
        $userdata = array(
            'jenis_izin' => $this->input->post('jenis_izin'),
            'first_date' => $this->input->post('first_date'),
            'second_date' => $this->input->post('second_date'),
            'first_date_taken' => $this->input->post('first_date_taken'),
            'second_date_taken' => $this->input->post('second_date_taken'),
            'kelurahan_id' => $this->input->post('mon_kelurahan'),
            'list_status' => $this->input->post('list_status'),
            'list_state' => $this->input->post('list_state'),
            'nama' => $this->input->post('nama'),
            'nama_perusahaan' => $this->input->post('nama_perusahaan')
        );

        $this->session->set_userdata($userdata);
    }

    public function type($type = NULL) {
        $this->settings = new settings();
        $this->settings->where('name', 'app_folder')->get();
        $app_folder = $this->settings->value . "/";
        $app_city = $this->settings->where('name', 'app_city')->get();

        $permohonan = new tmpermohonan();
        $list = $this->perizinan->where('id', $this->session->userdata('jenis_izin'))->get();

        $p_kelurahan = $permohonan->tmpemohon->trkelurahan->get();
        $p_kecamatan = $permohonan->tmpemohon->trkelurahan->trkecamatan->get();
        $p_kabupaten = $permohonan->tmpemohon->trkelurahan->trkecamatan->trkabupaten->get();

        //path of the template file
        $nama_surat = "cetak_monitoring_generic";
        $this->load->plugin('odf');
        $odf = new odf('assets/odt/' . $nama_surat . '.odt');
        $odf->setImage('header', 'assets/css/' . $app_folder . '/images/dinas_1.jpg', '17.5', '3.5');

        //membuat kota
        $wilayah = new trkabupaten();
        if ($app_city->value !== '0') {
            $alamat = $permohonan->tmpemohon->a_pemohon . ' ' . $p_kelurahan->n_kelurahan . ', ' .
                    $p_kecamatan->n_kecamatan . ', ' . ucwords(strtolower($p_kabupaten->n_kabupaten));
            $wilayah->get_by_id($app_city->value);
            $odf->setVars('kota', ucwords(strtolower($wilayah->n_kabupaten)));
        } else {
            $alamat = $permohonan->tmpemohon->a_pemohon;
            $odf->setVars('kota', '...........');
        }

        $odf->setVars('tanggal', $this->lib_date->mysql_to_human($this->lib_date->get_date_now()));

        $i = 1;

        $obj = new tmpermohonan();
        $suffix = NULL;
        switch ($type) {
            case 'kecamatan' :
                $odf->setVars('title', 'Monitoring Per Kelurahan');
                $obj->where_related('tmpemohon/trkelurahan', 'id', $this->session->userdata('kelurahan_id'));
                $suffix = '_kelurahan.odt';
                break;
            case 'jenis_izin' :
                $odf->setVars('title', 'Monitoring Per Perizinan');
                $obj->where_related('trperizinan', 'id', $this->session->userdata('jenis_izin'));
                $suffix = '_perizinan.odt';
                break;
            case 'perwaktu' :
                $odf->setVars('title', 'Monitoring Per Waktu');
                $suffix = '_perwaktu.odt';
                break;
            case 'status' :
                $obj->where_related('trstspermohonan', 'id', $this->session->userdata('list_status'));
                $odf->setVars('title', 'Monitoring Per Status Izin');
                $suffix = '_status.odt';
                break;
            case 'state' :
                switch ($this->session->userdata('list_state')) {
                    case '0' :
                        $obj->where_related('tmbap', 'c_penetapan', $this->session->userdata('list_state'));
                        break;
                    case '1' :
                        $obj->where_related('tmbap', 'c_penetapan', $this->session->userdata('list_state'));
                        $obj->where('d_berlaku_izin <= ', $this->lib_date->get_date_now());
                        break;
                    case '2' :
                        $obj->where('d_berlaku_izin > ', $this->lib_date->get_date_now());
                        break;
                }

                $odf->setVars('title', 'Monitoring Per Perizinan Belum/Sudah Jadi dan Kadaluarsa');
                $suffix = '_status.odt';
                break;
            case 'pemohon' :
                $obj->ilike_related('tmpemohon', 'n_pemohon', $this->session->userdata('nama'), 'after');
                $odf->setVars('title', 'Monitoring Per Pemohon');
                $suffix = '_pemohon.odt';
                break;
            case 'perusahaan' :
                $obj->ilike_related('tmperusahaan', 'n_perusahaan', $this->session->userdata('nama_perusahaan'), 'after');
                $odf->setVars('title', 'Monitoring Per Perusahaan');
                $suffix = '_perusahaan.odt';
                break;
            case 'ambil_izin' :
                $odf->setVars('title', 'Monitoring Per Pengambilan Izin');
                $obj->where_related('trperizinan', 'id', $this->session->userdata('jenis_izin'));
                $suffix = '_perpengambilan.odt';
                break;
        }

        if ($type === 'ambil_izin') {
            $obj->where('d_ambil_izin >= ', $this->session->userdata('first_date_taken'));
            $obj->where('d_ambil_izin <= ', $this->session->userdata('second_date_taken'));
        } else {
            $obj->where('d_terima_berkas >= ', $this->session->userdata('first_date'));
            $obj->where('d_terima_berkas <= ', $this->session->userdata('second_date'));
        }

        $article3 = NULL;

        $obj->get();

        foreach ($obj as $list) {

            $list->tmpemohon->get();
            $list->tmperusahaan->get();
            $list->tmpemohon->trkelurahan->get();
            $list->trperizinan->get();
            $list->trstspermohonan->get();
            $list->tmbap->get();

            $article3 = $odf->setSegment('articles3');

            if ($type === 'perusahaan') {
                $article3->titreArticle3($i);
                $article3->texteArticle3($list->pendaftaran_id);
                $article3->texteArticle4($list->trperizinan->n_perizinan);
                $article3->texteArticle5($this->lib_date->mysql_to_human($list->d_terima_berkas));
                $article3->texteArticle6($list->tmperusahaan->n_perusahaan);
                $article3->texteArticle7($list->trstspermohonan->n_sts_permohonan);
                $article3->texteArticle8($list->tmpemohon->a_pemohon);
                $article3->texteArticle9($list->tmpemohon->trkelurahan->n_kelurahan);
                $article3->merge();
            } else if ($type === 'ambil_izin') {
                $article3->titreArticle3($i);
                $article3->texteArticle3($list->pendaftaran_id);
                $article3->texteArticle4($list->trperizinan->n_perizinan);
                $article3->texteArticle5($this->lib_date->mysql_to_human($list->d_ambil_izin));
                $article3->texteArticle6($list->tmpemohon->n_pemohon);
                $article3->texteArticle7($list->trstspermohonan->n_sts_permohonan);
                $article3->texteArticle8($list->tmpemohon->a_pemohon);
                $article3->texteArticle9($list->tmpemohon->trkelurahan->n_kelurahan);
                $article3->merge();
            } else if ($type === 'state') {
                $state = NULL;

                if ($list->d_berlaku_izin > $this->lib_date->get_date_now()) {
                    $state = "Kadaluarsa";
                } else if ($list->tmbap->c_penetapan === '1') {
                    $state = "Sudah Jadi";
                } else if ($list->tmbap->c_penetapan === '0') {
                    $state = "Belum Jadi";
                }

                $article3->titreArticle3($i);
                $article3->texteArticle3($list->pendaftaran_id);
                $article3->texteArticle4($list->trperizinan->n_perizinan);
                $article3->texteArticle5($this->lib_date->mysql_to_human($list->d_terima_berkas));
                $article3->texteArticle6($list->tmpemohon->n_pemohon);
                $article3->texteArticle7($state);
                $article3->texteArticle8($list->tmpemohon->a_pemohon);
                $article3->texteArticle9($list->tmpemohon->trkelurahan->n_kelurahan);
                $article3->merge();
            } else {
                $article3->titreArticle3($i);
                $article3->texteArticle3($list->pendaftaran_id);
                $article3->texteArticle4($list->trperizinan->n_perizinan);
                $article3->texteArticle5($this->lib_date->mysql_to_human($list->d_terima_berkas));
                $article3->texteArticle6($list->tmpemohon->n_pemohon);
                $article3->texteArticle7($list->trstspermohonan->n_sts_permohonan);
                $article3->texteArticle8($list->tmpemohon->a_pemohon);
                $article3->texteArticle9($list->tmpemohon->trkelurahan->n_kelurahan);
                $article3->merge();
            }
            $i++;
        }

        $odf->mergeSegment($article3);
        //export the file
        $odf->exportAsAttachedFile($nama_surat . $suffix);
    }

}