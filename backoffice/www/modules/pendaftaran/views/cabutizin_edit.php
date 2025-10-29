<style>
    .readonly{
        background: #99ffff;
}

</style>
<script>
    $('input[readOnly=*]').addClass("readonly");
</script>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
                    if($id_daftar == "yyy"){
                ?>
            <fieldset id="half">
                <legend>Keterangan</legend>
                <div class="ContentForm" style="font-weight: bold;text-align: center">
                    No Surat Izin masih dalam proses (belum diserahkan)
                <div style="float: right">
                <?php
                    $kembali = array(
                        'name' => 'button',
                        'class' => 'button-wrc',
                        'content' => '&laquo; back',
                        'onclick' => 'parent.location=\''. site_url('pendaftaran/cabutizin') . '\''
                    );
                    echo form_button($kembali);
                ?>
                </div>
                </div>
            </fieldset>
                <?php
                    }else if($id_daftar == "xxx"){
                ?>
            <fieldset id="half">
                <legend>Keterangan</legend>
                <div class="ContentForm" style="font-weight: bold;text-align: center">
                    No Surat Izin yang dicari tidak ada
                <div style="float: right">
                <?php
                    $kembali = array(
                        'name' => 'button',
                        'class' => 'button-wrc',
                        'content' => '&laquo; back',
                        'onclick' => 'parent.location=\''. site_url('pendaftaran/cabutizin') . '\''
                    );
                    echo form_button($kembali);
                ?>
                </div>
                </div>
            </fieldset>
                <?php
                    }else if($id_daftar){
            ?>
            <fieldset>
                <legend>Data Perizinan</legend>
                    <?php
                        $attr = array('id' => 'form');
                        echo form_open('pendaftaran/cabutizin/' . $save_method, $attr);
                        echo form_hidden('jenis_izin_id', $jenis_izin->id);
                        echo form_hidden('jenis_permohonan_id', $jenis_permohonan->id);
                        echo form_hidden('id_daftar', $id_daftar);
                        echo form_hidden('id_link', $id_link);
                    ?>
                    <div id="statusRail">
                      <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('Nama Izin');
                        ?>
                      </div>
                      <div id="rightRail" class="bg-grid">
                        <?php
                            echo $jenis_izin->n_perizinan;
                        ?>
                      </div>
                    </div>
                    <div id="statusRail">
                      <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('Jenis Permohonan');
                        ?>
                      </div>
                      <div id="rightRail" class="bg-grid">
                        <?php
                            echo $jenis_permohonan->n_permohonan;
                        ?>
                      </div>
                    </div>
                    <div id="statusRail">
                      <div id="leftRail">
                        <?php
                            echo form_label('Kelompok Izin');
                        ?>
                      </div>
                      <div id="rightRail">
                        <?php
                            $jenis_izin->trkelompok_perizinan->get();
                            echo $jenis_izin->trkelompok_perizinan->n_kelompok;
                        ?>
                      </div>
                    </div>
                    <div id="statusRail">
                      <div id="leftRail">
                        <?php
                            echo form_label('Tanggal Pencabutan Izin');
                        ?>
                      </div>
                      <div id="rightRail">
                        <?php
                        if (isset($link))
                        {
                                  $tglcabut_input = array(
                                    'name' => 'tgl_dicabut',
                                    'value' => $tgl_dicabut,
                                    'class' => 'input-all required',
                                   
                                    'readOnly' => TRUE
                                );                                
                        }else
                        {
                                $tglcabut_input = array(
                                    'name' => 'tgl_dicabut',
                                    'value' => $tgl_dicabut,
                                    'class' => 'input-all required',
                                    'id' => 'inputTanggal1'
                                );
                        }
                                echo form_input($tglcabut_input);
                        ?>
                      </div>
                    </div>
                    <?php
                        if($save_method == "update"){
                    ?>
                    <div id="statusRail" style="font-weight: bold">
                      <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('No Pendaftaran');
                        ?>
                      </div>
                      <div id="rightRail" class="bg-grid">
                        <?php
                            echo $no_pendaftaran_baru;
                        ?>
                      </div>
                    </div>
                    <?php
                            }
                    ?>
                    <div id="statusRail">
                      <div id="leftRail" class="bg-grid">
                        <?php
                            echo form_label('No Surat Izin');
                        ?>
                      </div>
                      <div id="rightRail" class="bg-grid">
                        <?php
                            echo $no_sk;
                        ?>
                      </div>
                    </div>
                    <br style="clear:both" />
                    <div id="statusRail">
                      <div id="leftRail">
                        <?php
                            echo form_label('No Akta');
                        ?>
                      </div>
                      <div id="rightRail">
                        <?php
                        if (isset($link))
                        {
                                $no_akta_input = array(
                                    'name' => 'no_akta',
                                    'value' => $no_akta,
                                    'class' => 'input-wrc required',
                                    'readOnly' => TRUE
                                );
                            
                        } else
                        {
                                $no_akta_input = array(
                                    'name' => 'no_akta',
                                    'value' => $no_akta,
                                    'class' => 'input-wrc required'
                                );
                        }
                                echo form_input($no_akta_input);
                        ?>
                      </div>
                    </div>
                    <div id="statusRail">
                      <div id="leftRail">
                        <?php
                            echo form_label('Tanggal Akta');
                        ?>
                      </div>
                      <div id="rightRail">
                        <?php
                        if (isset($link))
                        {
                                $tglakta_input = array(
                                    'name' => 'd_akta',
                                    'value' => $d_akta,
                                    'class' => 'input-all required',
                                    
                                    'readOnly' => true
                                );
                        }else
                        {
                                $tglakta_input = array(
                                    'name' => 'd_akta',
                                    'value' => $d_akta,
                                    'class' => 'input-all required',
                                    'id' => 'inputTanggal2'
                                );
                        }
                                echo form_input($tglakta_input);
                        ?>
                      </div>
                    </div>
                    <br style="clear:both" />
                    <div id="statusRail">
                      <div id="leftRail">
                        <?php
                                echo form_label('Notaris Akta');
                        ?>
                      </div>
                      <div id="rightRail">
                        <?php
                        if (isset($link))
                        {
                            $notaris_input = array(
                                    'name' => 'notaris',
                                    'value' => $notaris,
                                    'class' => 'input-wrc required',
                                    'readOnly' => true
                                );
                        } else
                        {
                                $notaris_input = array(
                                    'name' => 'notaris',
                                    'value' => $notaris,
                                    'class' => 'input-wrc required'
                                );
                        }
                                echo form_input($notaris_input);
                        ?>
                      </div>
                    </div>
                    <div id="statusRail">
                      <div id="leftRail">
                        <?php
                            echo form_label('Tanggal Permohonan Tutup Izin');
                        ?>
                      </div>
                      <div id="rightRail">
                        <?php
                        
                        if (isset($link))
                        {
                            $tglajuan_input = array(
                                    'name' => 'd_ajuan_cabut',
                                    'value' => $d_ajuan_cabut,
                                    'class' => 'input-all required',
                                    'readOnly' => true
                                );
                               echo form_input($tglajuan_input);
                        }
                        else
                        {
                            $tglajuan_input = array(
                                    'name' => 'd_ajuan_cabut',
                                    'value' => $d_ajuan_cabut,
                                    'class' => 'input-all required',
                                    'id' => 'inputTanggal3'
                                );
                                echo form_input($tglajuan_input);
                        }
                        ?>
                      </div>
                    </div>
                    <br style="clear:both" />
                    <div id="statusRail">
                      <div id="leftRail">
                        <?php
                            echo form_label('Keterangan Izin Dicabut');
                        ?>
                      </div>
                      <div id="rightRail">
                        <?php
                        if (isset($link))
                        {
                            $ket_cabut_input = array(
                                    'name' => 'ket_cabut',
                                    'value' => $ket_cabut,
                                    'class' => 'input-area-wrc required',
                                    'readOnly' => true
                                );
                        } else
                        {
                                $ket_cabut_input = array(
                                    'name' => 'ket_cabut',
                                    'value' => $ket_cabut,
                                    'class' => 'input-area-wrc required'
                                );
                               
                        }
                         echo form_textarea($ket_cabut_input);
                        ?>
                      </div>
                    </div>
                    <div id="statusRail">
                      <div id="leftRail">
                      </div>
                      <div id="rightRail">
                      </div>
                    </div>
            </fieldset>
                <?php
                    }else{
                ?>
            <fieldset id="half">
                <legend>Keterangan</legend>
                <div class="ContentForm" style="font-weight: bold;text-align: center">
                    No Surat Izin kosong
                <div style="float: right">
                <?php
                    $kembali = array(
                        'name' => 'button',
                        'class' => 'button-wrc',
                        'content' => '&laquo; back',
                        'onclick' => 'parent.location=\''. site_url('pendaftaran/cabutizin') . '\''
                    );
                    echo form_button($kembali);
                ?>
                </div>
                </div>
            </fieldset>
                <?php
                    }
                ?>
        </div>
        <div class="entry" style="text-align: center;">
            <?php
            if (isset($link))
            {
                $link = site_url('pendaftaran/cabutizin');
                $cancel_daftar = array(
                    'name' => 'button',
                    'class' => 'button-wrc',
                    'content' => 'Kembali',
                    'onclick' => 'parent.location=\''. $link . '\''
                     );
                 echo form_button($cancel_daftar);
            }
            else
            {
            $add_daftar = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Dicabut'
            );
            if($id_link == "1") $link = site_url('pendataan');
            else $link = site_url('pendaftaran/cabutizin');
            $cancel_daftar = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. $link . '\''
            );
            if($id_daftar){
                if($id_daftar !== "xxx" && $id_daftar !== "yyy"){
            echo form_submit($add_daftar);
            echo "<span></span>";
            echo form_button($cancel_daftar);
                }
            }
            }
            echo form_close();
            ?>
        </div>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Data Pemohon</a></li>
                    <li><a href="#tabs-2">Data Perusahaan</a></li>
                    <li><a href="#tabs-3">Persyaratan</a></li>
                </ul>
                <?php
                if($id_daftar){
                if($id_daftar !== "xxx" && $id_daftar !== "yyy"){
                ?>
                <div id="tabs-1">
                    <div id="contentleft">
                        <div class="contentForm">
                            <?php
                                $norefer_input = array(
                                    'name' => 'no_refer',
                                    'value' => $no_refer,
                                    'class' => 'input-wrc', 'disabled' => TRUE
                                );
                                echo form_label('ID (SIM/KTP/Passport) ');
                                echo form_input($norefer_input);
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                                $namapemohon_input = array(
                                    'name' => 'nama_pemohon',
                                    'value' => $nama_pemohon,
                                    'class' => 'input-wrc', 'disabled' => TRUE
                                );
                                echo form_label('Nama Pemohon ');
                                echo form_input($namapemohon_input);
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                                $notelp_input = array(
                                    'name' => 'no_telp',
                                    'value' => $no_telp,
                                    'class' => 'input-wrc', 'disabled' => TRUE
                                );
                                echo form_label('No Telp/HP ');
                                echo form_input($notelp_input);
                            ?>
                        </div>
                        <div class="contentForm">
                                <?php
                                $tgldaftar_input = array(
                                    'name' => 'tgl_daftar',
                                    'value' => $tgl_daftar,
                                    'class' => 'input-all', 'disabled' => TRUE
                                );
                                echo form_label('Tgl Terima Berkas ');
                                echo form_input($tgldaftar_input);
                            ?>
                        </div>
                        <br style="clear: both;" />
                        <div class="contentForm">
                                <?php
                                $tglsurvey_input = array(
                                    'name' => 'tgl_survey',
                                    'value' => $tgl_survey,
                                    'class' => 'input-wrc', 'disabled' => TRUE
                                );
                                echo form_label('Tgl Survey');
                                echo form_input($tglsurvey_input);
                            ?>
                        </div>
                        <div class="contentForm">
                                <?php
                                $lokasi_input = array(
                                    'name' => 'lokasi_izin',
                                    'value' => $lokasi_izin,
                                    'class' => 'input-area-wrc', 'disabled' => TRUE
                                );
                                echo form_label('Lokasi Izin');
                                echo form_textarea($lokasi_input);
                            ?>
                        </div>
                    </div>
                    <div id="contentright">
                        <div class="contentForm">
                            <?php
                                foreach ($list_propinsi as $row){
                                    $opsi_propinsi[$row->id] = $row->n_propinsi;
                                }

                                echo form_label('Propinsi ');
                                echo form_dropdown('propinsi_pemohon', $opsi_propinsi, $propinsi_pemohon,
                                     'class = "input-select-wrc" disabled = "TRUE" id="propinsi_pemohon_id"');
                            ?>
                        </div>
                        <div class="contentForm" id="show_kabupaten_pemohon">
                            <?php
                                foreach ($list_kabupaten as $row){
                                    $opsi_kabupaten[$row->id] = $row->n_kabupaten;
                                }

                                echo form_label('Kabupaten ');
                                echo form_dropdown('kabupaten_pemohon', $opsi_kabupaten, $kabupaten_pemohon,
                                     'class = "input-select-wrc" disabled = "TRUE" id="kabupaten_pemohon_id"');
                            ?>
                        </div>
                        <div class="contentForm" id="show_kecamatan_pemohon">
                            <?php
                                foreach ($list_kecamatan as $row){
                                    $opsi_kecamatan[$row->id] = $row->n_kecamatan;
                                }

                                echo form_label('Kecamatan ');
                                echo form_dropdown('kecamatan_pemohon', $opsi_kecamatan, $kecamatan_pemohon,
                                     'class = "input-select-wrc" disabled = "TRUE" id="kecamatan_pemohon_id"');
                            ?>
                        </div>
                        <div class="contentForm" id="show_kelurahan_pemohon">
                            <?php
                                foreach ($list_kelurahan as $row){
                                    $opsi_kelurahan[$row->id] = $row->n_kelurahan;
                                }

                                echo form_label('Kelurahan ');
                                echo form_dropdown('kelurahan_pemohon', $opsi_kelurahan, $kelurahan_pemohon,
                                     'class = "input-select-wrc" disabled = "TRUE" id="kelurahan_pemohon_id"');
                            ?>
                        </div>
                        <div class="contentForm">
                                <?php
                                $alamatdata_input = array(
                                    'name' => 'alamat_pemohon',
                                    'value' => $alamat_pemohon,
                                    'class' => 'input-area-wrc', 'disabled' => TRUE
                                );
                                echo form_label('Alamat Pemohon ');
                                echo form_textarea($alamatdata_input);
                            ?>
                        </div>
                        <div class="contentForm">
                                <?php
                                $alamatdataluar_input = array(
                                    'name' => 'alamat_pemohon_luar',
                                    'value' => $alamat_pemohon_luar,
                                    'class' => 'input-area-wrc', 'disabled' => TRUE
                                );
                                echo form_label('Alamat Pemohon<br />di Luar Negeri<br />(isikan jika ada)');
                                echo form_textarea($alamatdataluar_input);
                            ?>
                        </div>
                    </div>
                    <br style="clear: both;" />
                </div>
                <div id="tabs-2">
                    <div id="contentleft">
                        <div class="contentForm">
                            <?php
                                $namaperusahaan_input = array(
                                    'name' => 'nama_perusahaan',
                                    'value' => $nama_perusahaan,
                                    'class' => 'input-wrc', 'disabled' => TRUE
                                );
                                echo form_label('Nama Perusahaan ');
                                echo form_input($namaperusahaan_input);
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                                $npwp_input = array(
                                    'name' => 'npwp',
                                    'value' => $npwp,
                                    'class' => 'input-wrc', 'disabled' => TRUE
                                );
                                echo form_label('NPWP ');
                                echo form_input($npwp_input);
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                                $telp_input = array(
                                    'name' => 'telp_perusahaan',
                                    'value' => $telp_perusahaan,
                                    'class' => 'input-wrc', 'disabled' => TRUE
                                );
                                echo form_label('Telp Perusahaan ');
                                echo form_input($telp_input);
                            ?>
                        </div>
                        <div class="contentForm">
                                <?php
                                $alamatusaha_input = array(
                                    'name' => 'alamat_usaha',
                                    'value' => $alamat_usaha,
                                    'class' => 'input-area-wrc', 'disabled' => TRUE
                                );
                                echo form_label('Alamat Perusahaan ');
                                echo form_textarea($alamatusaha_input);
                            ?>
                        </div>
                    </div>
                    <div id="contentright">
                        <div class="contentForm" id="show_propinsi_usaha">
                            <?php
                                foreach ($list_propinsi as $row){
                                    $opsi_propinsi[$row->id] = $row->n_propinsi;
                                }

                                echo form_label('Propinsi ');
                                echo form_dropdown('propinsi_usaha', $opsi_propinsi, $propinsi_usaha,
                                     'class = "input-select-wrc" disabled = "TRUE" id="propinsi_usaha_id"');
                            ?>
                        </div>
                        <div class="contentForm" id="show_kabupaten_usaha">
                            <?php
                                foreach ($list_kabupaten as $row){
                                    $opsi_kabupaten[$row->id] = $row->n_kabupaten;
                                }

                                echo form_label('Kabupaten ');
                                echo form_dropdown('kabupaten_usaha', $opsi_kabupaten, $kabupaten_usaha,
                                     'class = "input-select-wrc" disabled = "TRUE" id="kabupaten_usaha_id"');
                            ?>
                        </div>
                        <div class="contentForm" id="show_kecamatan_usaha">
                            <?php
                                foreach ($list_kecamatan as $row){
                                    $opsi_kecamatan[$row->id] = $row->n_kecamatan;
                                }

                                echo form_label('Kecamatan ');
                                echo form_dropdown('kecamatan_usaha', $opsi_kecamatan, $kecamatan_usaha,
                                     'class = "input-select-wrc" disabled = "TRUE" id="kecamatan_usaha_id"');
                            ?>
                        </div>
                        <div class="contentForm" id="show_kelurahan_usaha">
                            <?php
                                foreach ($list_kelurahan as $row){
                                    $opsi_kelurahan[$row->id] = $row->n_kelurahan;
                                }

                                echo form_label('Kelurahan ');
                                echo form_dropdown('kelurahan_usaha', $opsi_kelurahan, $kelurahan_usaha,
                                     'class = "input-select-wrc" disabled = "TRUE" id="kelurahan_usaha_id"');
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                                foreach ($list_kegiatan as $row){
                                    $opsi_kegiatan[$row->id] = $row->n_kegiatan;
                                }

                                echo form_label('Jenis Kegiatan ');
                                echo form_dropdown('jenis_kegiatan', $opsi_kegiatan, $jenis_kegiatan,
                                     'class = "input-select-wrc" disabled = "TRUE"');
                            ?>
                        </div>
                        <div class="contentForm">
                            <?php
                                foreach ($list_investasi as $row){
                                    $opsi_investasi[$row->id] = $row->n_investasi;
                                }

                                echo form_label('Jenis Investasi ');
                                echo form_dropdown('jenis_investasi', $opsi_investasi, $jenis_investasi,
                                     'class = "input-select-wrc" disabled = "TRUE"');
                            ?>
                        </div>
                    </div>
                    <br style="clear: both;" />
                </div>
                <div id="tabs-3">
                    <table cellpadding="0" cellspacing="0" border="1" class="display">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="70%">Syarat</th>
                                <th width="10%">Terpenuhi</th>
                                <th width="15%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 0;
                            foreach ($syarat_izin as $data){
                                $i++;
                        ?>
                            <tr>
                                <td align="center"><?php echo $i; ?></td>
                                <td><?php echo $data->v_syarat; ?></td>
                                <td align="center">
                                <?php
                                if ($save_method === 'update'){
                                    $checked = FALSE;
                                    if($list_daftar){
                                        foreach ($list_daftar as $data_daftar){
                                            $data_syarat = new tmpermohonan_trsyarat_perizinan();
                                            $data_syarat->where('tmpermohonan_id', $data_daftar->id)
                                            ->where('trsyarat_perizinan_id', $data->id)->get();
                                            if($data_syarat->trsyarat_perizinan_id){
                                                $checked = TRUE;
                                                break;
                                            }
                                        }
                                    }else{
                                        $checked = FALSE;
                                        break;
                                    }

                                    $set = array(
                                        'name' => 'pemohon_syarat[]',
                                        'value' => $data->id,
                                        'checked' => $checked, 'disabled' => TRUE
                                    );
                                    echo form_checkbox($set);                              
                                }else{
                                    $set = array(
                                        'name' => 'pemohon_syarat[]',
                                        'value' => $data->id, 'disabled' => TRUE
                                    );
                                    echo form_checkbox($set);
                                }
                                ?></td>
                                <td align="center">
                                    <?php
                                        if($data->status == "1") $status_data = "Wajib";
                                        else $status_data = "Tidak Wajib";
                                        echo form_label($status_data);
                                    ?>
                                </td>
                            </tr>
                        <?php
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
                }
                }else{
                ?>
                <div id="tabs-1"></div>
                <div id="tabs-2"></div>
                <div id="tabs-3"></div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <br style="clear: both;" />
</div>
