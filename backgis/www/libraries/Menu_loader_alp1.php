<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Menu_loader class
 * Use this class to applicated your module here.
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */
class Menu_loader {

    public function __construct() {
        $this->ci = & get_instance();
    }

    public function set_menu($module_name = NULL, $title = NULL, $css_class = NULL) {
        $structure = NULL;
        if ($module_name !== NULL || $module_name !== '')
            if ($css_class === NULL) {
                $structure = "<li><a href='" . site_url($module_name) . "'>" . $title . "</a></li>";
            } else {
                $structure = "<li class='" . $css_class . "'><a href='" . site_url($module_name) . "'>" . $title . "</a></li>";
            }

        return $structure;
    }

    public function install() {
        $menu = NULL;
        $menu .= "<li class='dir'>";
        $menu .= "Master";
        $menu .= "<ul>";
        $menu .= $this->set_menu('role', 'Setting Peran');
        $menu .= $this->set_menu('pengguna', 'Setting Pengguna');
        $menu .= $this->set_menu('install', 'Konfigurasi', 'last');
        $menu .= "</ul>";
        $menu .= "</li>";
        return $menu;
    }

    public function create_menu($list_role = NULL) {
        $menu = NULL;
        $penetapan = FALSE;
        $pendataan = FALSE;
        $tim_teknis = FALSE;
        $customer_service = FALSE;
        $pelayanan = FALSE;

        foreach ($list_role as $list) {
            switch ($list->id_role) {
                case '1' :
                    $menu .= "<li class='dir'>";
                    $menu .= "Konfigurasi";
                    $menu .= "<ul>";
                    $menu .= $this->set_menu('perizinan', 'Setting Jenis Perizinan');
//                    $menu .= $this->set_menu('', 'Setting Sistem Prosedur');
//                    $menu .= $this->set_menu('', 'Setting Tugas Per Blok Perizinan');
//                    $menu .= $this->set_menu('', 'Setting Tugas Per Blok Prosedur');
                    $menu .= $this->set_menu('perizinan/paralel', 'Setting Perizinan Paralel');
//                    $menu .= $this->set_menu('perizinan/alurizin', 'Setting Alur Izin Berisi');
                    $menu .= $this->set_menu('perizinan/persyaratanizin', 'Setting Persyaratan Izin');
                    $menu .= $this->set_menu('holiday', 'Setting Hari Libur');
                    $menu .= $this->set_menu('property/master', 'Setting Property Pendataan');
                    $menu .= $this->set_menu('dasarhukum', 'Setting Dasar Hukum Surat');
                    $menu .= $this->set_menu('ketetapan', 'Setting Ketentuan Surat');
                    $menu .= $this->set_menu('menimbang', 'Setting Menimbang SK');
                    $menu .= $this->set_menu('mengingat', 'Setting Mengingat SK');
                    $menu .= $this->set_menu('retribusi', 'Setting Nilai Retribusi');
//                    $menu .= $this->set_menu('permohonan/sk/aktifsk', 'Aktivasi Cetak Surat');
                    $menu .= $this->set_menu('perizinan/koefisientarif', 'Setting Koefisien Tarif');
                    $menu .= $this->set_menu('perusahaan/kegiatan', 'Setting Jenis Kegiatan');
                    $menu .= $this->set_menu('perusahaan/investasi', 'Setting Jenis Investasi');
                    $menu .= $this->set_menu('settings/satuan', 'Setting Satuan');
                    $menu .= $this->set_menu('settings/webservice', 'Setting Web Service');
                    $menu .= $this->set_menu('', '<hr>');
                    $menu .= $this->set_menu('petugas', 'Setting Pegawai');
//						$menu .= $this->set_menu('role', 'Setting Peran');
                    $menu .= $this->set_menu('unitkerja', 'Setting Unit Kerja');
                    $menu .= $this->set_menu('pengguna', 'Setting Pengguna', 'last');
                    $menu .= "</ul>";
                    $menu .= "</li>";
                    break;
                case '2' :
                    $menu .= "<li class='dir'>";
                    $menu .= "Monitoring";
                    $menu .= "<ul>";
                    $menu .= $this->set_menu('monitoring', 'Per Jenis Perizinan', 'first');
                    $menu .= $this->set_menu('monitoring/perwaktu', 'Per Bulan Masuk');
                    $menu .= $this->set_menu('monitoring/kecamatan', 'Per Desa Dan Kecamatan');
                    $menu .= $this->set_menu('monitoring/state', 'Perizinan Belum/Sudah Jadi Dan Kadaluarsa');
                    $menu .= $this->set_menu('monitoring/status', 'Per Status Perizinan');
                    $menu .= $this->set_menu('monitoring/pemohon', 'Per Nama Pemohon');
                    $menu .= $this->set_menu('monitoring/perusahaan', 'Per Nama Perusahaan');
                    $menu .= $this->set_menu('monitoring/pengambilan', 'Per Bulan Pengambilan Izin', 'last');
                    $menu .= "</ul>";
                    $menu .= "</li>";
                    break;
                case '3' :
                    $pelayanan = TRUE;
                    break;
//                case '4' :
//                    $menu .= "<li class='dir'>";
//                    $menu .= "Pengelolaan Dokumen";
//                    $menu .= "<ul>";
//                    $menu .= $this->set_menu('dokumen/penelusuran', 'Penelusuran Dokumen', 'first');
//                    $menu .= "        <li class='dir'>Permohonan Salinan Dokumen";
//                    $menu .= "            <ul>";
//                    $menu .= $this->set_menu('dokumen/persetujuan', 'Persetujuan Salinan Dokumen', 'first');
//                    $menu .= "<li class='last'></li>";
//                    $menu .= "            </ul>";
//                    $menu .= "        </li>";
//                    $menu .= "<li class='last'></li>";
//                    //$menu .= $this->set_menu('dokumen/informasi', 'Informasi Status Dokumen', 'last');
//                    $menu .= "</ul>";
//                    $menu .= "</li>";
//                    break;
//                case '5' :
//                    $menu .= "<li class='dir'>";
//                    $menu .= "Pengaduan";
//                    $menu .= "<ul>";
//                    $menu .= $this->set_menu('pesan', 'Daftar Pengaduan / Saran', 'first');
//                    $menu .= $this->set_menu('pesan/pesanpersetujuan', 'Persetujuan Respon Pengaduan');
//                    $menu .= $this->set_menu('pesan/pesanpengiriman', 'Pengiriman Respon Pengaduan');
//                    $menu .= $this->set_menu('pesan/pesanbalasan', 'Daftar Balasan', 'last');
//                    $menu .= "</ul>";
//                    $menu .= "</li>";
//                    break;
                case '6':
                    $menu .= "<li class='dir'>";
                    $menu .= "Reporting";
                    $menu .= "<ul>";
                    $menu .= $this->set_menu('rekapitulasi/realisasi', 'Realisasi Penerimaan', 'first');
                    $menu .= $this->set_menu('rekapitulasi', 'Rekapitulasi Pendaftaran');
                    $menu .= $this->set_menu('rekapitulasi/izin', 'Rekapitulasi Perizinan');
                   // $menu .= $this->set_menu('rekapitulasi/pergi', 'Rekapitulasi Pengambilan izin pergi/tolak');
                    $menu .= $this->set_menu('rekapitulasi/retribusi', 'Rekapitulasi Retribusi');
                   // $menu .= $this->set_menu('rekapitulasi/pergi', 'Rekapitulasi Pengambilan Izin');
                    $menu .= $this->set_menu('rekapitulasi/ceklap', 'Rekapitulasi Tinjauan Lapangan');
                    $menu .= $this->set_menu('rekapitulasi/back_lap', 'Rekapitulasi Berkas Kembali');
                    $menu .= $this->set_menu('rekapitulasi/lap_izin', 'Rekapitulasi Izin Tercetak','last');

                    $menu .= "</ul>";
                    $menu .= "</li>";
                    break;
                case '7':
                    $pendataan = TRUE;
                    break;
                case '8':
                    $tim_teknis = TRUE;
                    break;
                case '9':
                    $penetapan = TRUE;
                    break;
                case '10':
                    $menu .= "<li class='dir'>";
                    $menu .= "Wasdal";
                    $menu .= "<ul>";
                    $menu .= $this->set_menu('pelayanan/ambilsk', 'Penyerahan Izin', 'first');
                    $menu .= $this->set_menu('dokumen/pengajuan', 'Pengajuan Salinan');
                    $menu .= $this->set_menu('dokumen/penyerahan', 'Penyerahan Salinan', 'last');
                    $menu .= "</ul>";
                    $menu .= "</li>";
                    break;
                case '11':
                    $menu .= "<li class='dir'>";
                    $menu .= "API Maintainer";
                    $menu .= "<ul>";
                    $menu .= $this->set_menu('api/maintainer', 'API', 'last');
                    $menu .= "</ul>";
                    $menu .= "</li>";
                    break;
                case '12':
                    $menu .= "<li class='dir'>";
                    $menu .= "Kasir";
                    $menu .= "<ul>";
                    $menu .= $this->set_menu('kasir', 'Pembayaran Retribusi', 'last');
                    $menu .= "</ul>";
                    $menu .= "</li>";
                    break;
                case '13':
                    $customer_service = TRUE;
                    break;
                case '100':
                    $menu .= "<li class='dir'>";
                    $menu .= "Master";
                    $menu .= "<ul>";
                    $menu .= $this->set_menu('role', 'Setting Peran');
                    $menu .= $this->set_menu('pengguna', 'Setting Pengguna', 'last');
                    $menu .= "</ul>";
                    $menu .= "</li>";
                    break;
            }
        }

        if($pelayanan || $customer_service) {
            $menu .= "<li class='dir'>";
            $menu .= "Pelayanan";
            $menu .= "<ul>";
            if($pelayanan) {
                $menu .= "  <li class='dir'>Pendaftaran";
                $menu .= "   <ul>";
                $menu .= $this->set_menu('pelayanan/sementara', 'Permohonan Sementara', 'first');
                $menu .= $this->set_menu('pelayanan/pendaftaran', 'Permohonan Izin Baru');
//                $menu .= $this->set_menu('pendaftaran/perubahan', 'Perubahan Izin');
//                $menu .= $this->set_menu('pendaftaran/perpanjangan', 'Perpanjangan Izin');
//                $menu .= $this->set_menu('pendaftaran/daftarulang', 'Daftar Ulang');
                $menu .= $this->set_menu('pendaftaran/index/2', 'Perubahan Izin');
                $menu .= $this->set_menu('pendaftaran/index/3', 'Perpanjangan Izin');
                $menu .= $this->set_menu('pendaftaran/index/4', 'Daftar Ulang Izin');
                $menu .= $this->set_menu('', '<hr>');
                $menu .= $this->set_menu('pemohon', 'Data Pemohon');
                $menu .= $this->set_menu('perusahaan', 'Data Perusahaan', 'last');
                $menu .= "   </ul>";
                $menu .= "  </li>";
            }

            if($customer_service) {
                $menu .= "  <li class='dir'>Customer Service";
                $menu .= "   <ul>";
                $menu .= $this->set_menu('info/infotracking', 'Informasi Tracking', 'first');
                $menu .= $this->set_menu('info/infomasaberlaku', 'Informasi Masa Berlaku');
                $menu .= $this->set_menu('info/infoperizinan', 'Informasi Perizinan');
                $menu .= $this->set_menu('property/simulasi', 'Simulasi Tarif Retribusi','last');
                $menu .= "   </ul>";
                $menu .= "  </li>";
            }

            $menu .= "<li class='last'></li>";
            $menu .= "</ul>";
            $menu .= "</li>";
        }

        if ($pendataan || $penetapan || $tim_teknis) {
            $menu .= "<li class='dir'>";
            $menu .= "Back Office";
            $menu .= "<ul>";

            if ($pendataan) {
                $menu .= "<li class='dir'>Pendataan";
                $menu .= "<ul>";
                $menu .= $this->set_menu('pendataan', 'Entry Data Perizinan', 'first');
                $menu .= $this->set_menu('survey', 'Penjadwalan Tinjauan');
                $menu .= "<li class='last'></li>";
                $menu .= "</ul>";
                $menu .= "</li>";
            }

            if ($tim_teknis) {
                $menu .= "<li class='dir'>Tim Teknis";
                $menu .= "<ul>";
                $menu .= $this->set_menu('survey/result', 'Entry Hasil Tinjauan', 'first');
                $menu .= $this->set_menu('permohonan/bap', 'Pembuatan BAP', 'last');
                $menu .= "</ul>";
                $menu .= "</li>";
            }

            if ($penetapan) {
                $menu .= "<li class='dir'>Penetapan";
                $menu .= "<ul>";
                $menu .= $this->set_menu('permohonan/penetapan', 'Penetapan Izin', 'first');
                $menu .= $this->set_menu('permohonan/skrd', 'Pembuatan SKRD');
                $menu .= $this->set_menu('permohonan/sk', 'Pembuatan Izin');
                $menu .= $this->set_menu('permohonan/skditolak', 'Layanan Ditolak');
                $menu .= $this->set_menu('pendaftaran/cabutizin', 'Pencabutan Izin', 'last');
                $menu .= "</ul>";
                $menu .= "</li>";
            }

            $menu .= "<li class='last'></li>";
            $menu .= "</ul>";
            $menu .= "</li>";
        }

        return $menu;
    }

}

// This is the end of Menu_loader class
