<script>
    function warning()
    {
        alert('Data telah lebih dari 10 hari');
    }
</script>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <?php
        echo "<font color='red'><b>" . $this->session->flashdata('warning') . "</b></font>";
        if ($ket_syarat) {
            echo "<div class='entry' align=center><b style='color: #FF0000;'>Persyaratan tidak lengkap !!</b></div>";
        }
        ?>
        <div class="entry">
        <?php 
	        $settings = new settings();
	        $app_web_service = $settings->where('name', 'web_service_penduduk')->get();
	        $url = $app_web_service->value;

        ?>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="pendaftaran">
                <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th width="8%">No Pendaftaran</th>
                        <th>Id Pemohon</th>
                        <th width="15%">Pemohon</th>
                        <th width="15%">Jenis Izin</th>
                        <th width="10%">Unit Kerja</th>
                        <th width="18%">Alamat</th>
                        <th width="10%">Status</th>
                        <th width="10%">Tanggal Permohonan</th>
                        <th width="5%">Aksi</th>
                        <th width="8%">Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($list as $data) {

                        $i++;
                        $data->trperizinan->get();
                        $izin_kelompok = $data->trperizinan->trkelompok_perizinan->get();
                        $data->tmpemohon->get();
                        $pecah = explode(' ', $data->d_entry);
                        //  cek data perizinan yang sama
                        $perizinan = new trperizinan();
                        $permohonan = new tmpermohonan();
                        $permohonan->get_by_id($data->id);
                        $pemohon_sementara = new tmpemohon_sementara();
                        $pemohon = new tmpemohon();
                        $pemohon->where('no_referensi', $permohonan->tmpemohon_sementara->no_referensi)->get();
                        $cekDulu = $pemohon->tmpermohonan->$perizinan->count();
                        $getUnitKerja = $objUnitKerja->get_by_id($data->trunitkerja_id);
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $data->pendaftaran_id; ?></td>
                            <td><?php echo $data->tmpemohon_sementara->no_referensi; ?></td>
                            <td><?php echo strip_slashes($data->tmpemohon_sementara->n_pemohon); ?></td>
                            <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                            <td><?php echo ($getUnitKerja->id) ? $getUnitKerja->n_unitkerja : '';?></td>
                            <td><?php echo $data->tmpemohon_sementara->a_pemohon; ?></td>
                            <td>
                                <?php
                                if ($data->c_paralel == 0)
                                    $status = "Satu Izin";
                                else
                                    $status = "Izin Paralel";
                                echo $status;
                                ?>
                            </td>
                            <td><?php echo $pecah[0]; ?></td>
                            <td>
                                <?php
                                $d_entry = explode('-', $pecah[0]);
                                $date2 = date('d');
                                $month2 = date('m');
                                $year2 = date('Y');

                                $jd1 = GregorianToJD($d_entry[1], $d_entry[2], $d_entry[0]);
                                $jd2 = GregorianToJD($month2, $date2, $year2);
                                $selisih = $jd2 - $jd1;

                                $img_duplikat_data_pemohon = array(
                                    'src' => base_url() . 'assets/images/icon/Dialog-warning-icon.png',
                                    'border' => '0',
                                    'title' => 'Duplicate ID data pemohon',
                                    'style' => 'cursor:pointer;',
                                    'width' => '17',
                                    'height' => 'inherit'
                                );

                                $img_pemohon = array(
                                    'src' => base_url() . 'assets/images/icon/users_warning.png',
                                    'border' => '0',
                                    'title' => 'Duplicate data pemohon',
                                    'style' => 'cursor:pointer;',
                                    'width' => '17',
                                    'height' => 'inherit'
                                );

                                $img_perusahaan = array(
                                    'src' => base_url() . 'assets/images/icon/warningPemohon.png',
                                    'border' => '0',
                                    'title' => 'Duplicate data perusahaan',
                                    'style' => 'cursor:pointer;',
                                    'width' => '17',
                                    'height' => 'inherit'
                                );

                                $img_alert = array(
                                    'src' => base_url() . 'assets/images/icon/warning.png',
                                    'border' => '0',
                                    'onclick' => 'return warning()',
                                    'style' => 'cursor:pointer;',
                                    'width' => '20',
                                    'height' => 'inherit'
                                );

                                $img_edit = array(
                                    'src' => base_url() . 'assets/images/icon/property.png',
                                    'alt' => 'Edit',
                                    'title' => 'Edit',
                                    'border' => '0',
                                );
                                $confirm_text = 'Apakah Anda yakin akan menghapusnya?';
                                $img_delete = array(
                                    'src' => base_url() . 'assets/images/icon/cross.png',
                                    'alt' => 'Delete',
                                    'title' => 'Delete',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\'' . $confirm_text . '\')',
                                );
                                $queryCekPemohon = "select * from tmpemohon where no_referensi='" . $data->tmpemohon_sementara->no_referensi . "' 
                                                    AND n_pemohon <> '" . $data->tmpemohon_sementara->n_pemohon . "'";
                                $hasil = $this->db->query($queryCekPemohon);
                                $qPemohon = $hasil->num_rows();

                                //Added by Indra
                                //Jika tidak ada data perusahaan, tidak perlu cek
                                if($data->tmperusahaan_sementara->npwp && $data->tmperusahaan_sementara->n_perusahaan){
                                    $queryCekPerusahaan = "select * from tmperusahaan where npwp='" . $data->tmperusahaan_sementara->npwp . "'
                                        AND n_perusahaan <> '" . $data->tmperusahaan_sementara->n_perusahaan . "' ";
                                    $hasil2 = $this->db->query($queryCekPerusahaan);
                                    $qPeusahaan = $hasil2->num_rows();
                                }else{
                                    $qPeusahaan = 0;
                                }
                                /* if ($permohonan->$perizinan->id !== $pemohon->tmpermohonan->$perizinan->id)
                                  {
                                  echo anchor(site_url('pelayanan/sementara/edit') . '/' . $data->id, img($img_edit)) . "&nbsp;";
                                  } */
                                if ($qPemohon >= '1' || $qPeusahaan >= '1')
                                    echo "";
                                else
                                    echo anchor(site_url('pelayanan/sementara/edit') . '/' . $data->id, img($img_edit)) . "&nbsp;";
                                echo anchor(site_url('pelayanan/sementara/delete') . '/' . $data->id, img($img_delete));
                                if ($selisih >= 10) {
                                    echo img($img_alert);
                                }
//                                echo $qPemohon.$qPeusahaan
                                // cek no referensi di tmpemohon
                                ?>
                            </td>
                            <?php
                            echo "<td>";
                            if ($qPemohon >= '1' && $permohonan->$perizinan->id !== $pemohon->tmpermohonan->$perizinan->id && $qPeusahaan < '1' || $qPemohon >= '1') {
                                echo anchor(site_url('pelayanan/sementara/validasiPemohon') . '/' . $data->id, img($img_duplikat_data_pemohon)) . "&nbsp;";
                            } else if ($qPeusahaan >= '1' && $qPemohon < '1') {
                                echo anchor(site_url('pelayanan/sementara/validasiPerusahaan') . '/' . $data->id, img($img_perusahaan)) . "&nbsp;";
                            } else if ($qPemohon >= '1' && $qPeusahaan >= '1') {
                                echo anchor(site_url('pelayanan/sementara/validasiPemohon') . '/' . $data->id, img($img_duplikat_data_pemohon)) . "&nbsp;";
                                echo anchor(site_url('pelayanan/sementara/validasiPerusahaan') . '/' . $data->id, img($img_perusahaan)) . "&nbsp;";
                            }
                            echo "</td>";
                            ?>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
