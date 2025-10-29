<script type="text/javascript">
    function validasi()
    {
        var	first=document.forms[0].first_date_taken.value;
        var	second=document.forms[0].second_date_taken.value;
			
        if(first.length==0)
        {
            document.forms[0].first_date_taken.focus();
            alert("Periode awal mohon diisi");
            return false;
        }
		
        else if(second.length==0)
        {
            document.forms[0].second_date_taken.focus();
            alert("Periode akhir mohon diisi");
            return false;
        }
        else
        {
            return true;
        }
		
    }
</script>

<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <fieldset id="half">
            <?php
            $attr = array(
                'class' => 'searchForm',
                'id' => 'searchForm'
            );
            echo form_open("monitoring/pengambilan", $attr);
            if ($list_ijin) {
                $opsi_ijin['0'] = "------Pilih salah satu------";
                foreach ($list_ijin as $row) {
                    $opsi_ijin[$row->id] = $row->n_perizinan;
                }
            }
            if ($first_date_taken == '') {
                $first_date_taken = date('Y-m-d');
            }
            if ($second_date_taken == '') {
                $second_date_taken = date('Y-m-d');
            }
            $periodeawal_input = array(
                'name' => 'first_date_taken',
                'class' => 'monbulan',
                'id' => 'firstDateInput',
                'readOnly' => TRUE,
                'value' => $first_date_taken
            );

            $periodeakhir_input = array(
                'name' => 'second_date_taken',
                'class' => 'monbulan',
                'id' => 'secondDateInput',
                'readOnly' => TRUE,
                'value' => $second_date_taken
            );

            $cari = array(
                'name' => 'submit',
                'class' => 'button-wrc',
                'content' => 'Cari',
                'type' => 'submit',
                'onclick' => 'return validasi()'
            );
            ?>
            <table>
                <tr>
                    <td> <?php echo form_label('Jenis Izin', 'label_izin');
            echo form_hidden('mark', 'tanda'); ?> </td>
                    <td><?php
            if ($mark == "tanda") {
                echo form_dropdown('jenis_izin', $opsi_ijin, $jenis_izin, 'class = "input-select-wrc" id="selector"');
            } else {
                echo form_dropdown('jenis_izin', $opsi_ijin, '0', 'class = "input-select-wrc" id="selector"');
            }
            ?>
                    </td>
                </tr>
                <tr>
                    <td><?php echo form_label('Periode Awal', 'd_tahun'); ?> </td>
                    <td> <?php echo form_input($periodeawal_input); ?> </td>
                </tr>
                <tr>
                    <td><?php echo form_label('Periode Akhir', 'd_tahun'); ?> </td>
                    <td> <?php echo form_input($periodeakhir_input); ?> </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td><?php echo form_button($cari);
                        echo form_close(); ?></td>
<?php
                        echo form_open('monitoring/monitoring_pengambilan/cetak_monitoring_ambil');
                        echo form_hidden('jenis_izin', $jenis_izin);
                        echo form_hidden('first_date', $first_date_taken);
                        echo form_hidden('second_date', $second_date_taken);

                        $cetak = array(
                            'name' => 'cetak',
                            'class' => 'button-wrc',
                            'id' => 'cetak',
                            'content' => 'Cetak',
                            'type' => 'submit',
                            'onclick' => 'return validasi()'
                        );
?>

                        <td>
                    <?php
                        if ($jumlah > 0) {
                            echo form_button($cetak);
                        }

                        echo form_close();
                    ?>
                    </td>
                </tr>
            </table>
        </fieldset>

        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="listdata">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Nama Perizinan</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Nama Pemohon</th>
                        <th>No Surat</th>
                        <th>tgl_surat</th>
                        <th>Alamat Pemohon</th>
                        <th>Kelurahan</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8" class="dataTables_empty">Tidak ada  data..</td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
