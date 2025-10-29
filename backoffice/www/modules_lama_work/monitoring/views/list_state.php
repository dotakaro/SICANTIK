<script type="text/javascript">
    function validasi()
    {
        var	first=document.forms[0].first_date.value;
        var	second=document.forms[0].second_date.value;
			
        if(first.length==0)
        {
            document.forms[0].first_date.focus();
            alert("Periode awal mohon diisi");
            return false;
        }
		
        else if(second.length==0)
        {
            document.forms[0].second_date.focus();
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
            echo form_open("monitoring/state", $attr);
            $options = array('3' => '------Pilih salah satu------',
                '0' => 'Belum Jadi',
                '1' => 'Sudah Jadi',
                '2' => 'Kadaluarsa');

            $periodeawal_input = array(
                'name' => 'first_date',
                'class' => 'monbulan',
                'id' => 'firstDateInput',
                'readOnly' => TRUE,
                'value' => $first_date
            );

            $periodeakhir_input = array(
                'name' => 'second_date',
                'class' => 'monbulan',
                'id' => 'secondDateInput',
                'readOnly' => TRUE,
                'value' => $second_date
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
                    <td> <?php echo form_label('Jenis Status', 'label_izin');
            echo form_hidden('mark', 'tanda'); ?> </td>
                    <td> <?php
            if ($mark == "tanda") {
                echo form_dropdown('list_state', $options, $list_state, 'class = "input-select-wrc" id="selector"');
            } else {
                echo form_dropdown('list_state', $options, '3', 'class = "input-select-wrc" id="selector"');
            }
            ?>
                    </td>
                </tr>
                <tr>
                    <td> <?php echo form_label('Periode Awal', 'd_tahun'); ?> </td>
                    <td>  <?php echo form_input($periodeawal_input); ?></td>
                </tr>
                <tr>
                    <td> <?php echo form_label('Periode Akhir', 'd_tahun'); ?> </td>
                    <td>   <?php echo form_input($periodeakhir_input); ?> </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td><?php echo form_button($cari);
            echo form_close(); ?></td>
                    <?php
                    echo form_open('monitoring/monitoringbsjk/cetak_kadaluarsa/' . $list_state);
                    echo form_hidden('first_date', $first_date);
                    echo form_hidden('second_date', $second_date);

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
                if($jumlah > 0){
                    echo form_button($cetak);
                }
                    
                    echo form_close();
                ?>
                    </td>
                </tr>
            </table>
        </fieldset>

        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="monitoring">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Nama Perizinan</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Nama Pemohon</th>
                        <th>Status Permohonan</th>
                        <th>Alamat Pemohon</th>
                        <th>Kelurahan</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = NULL;
                    foreach ($listpermohonan as $data) {

                        $i++;
                        $data->tmpemohon->get();
                        $data->trperizinan->get();
                        $data->trstspermohonan->get();
                        $data->tmpemohon->trkelurahan->get();
                    ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $data->pendaftaran_id; ?></td>
                            <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                            <td><?php echo $this->lib_date->mysql_to_human($data->d_terima_berkas) ?></td>
                            <td><?php echo $data->tmpemohon->n_pemohon; ?></td>
                            <td><?php echo $data->trstspermohonan->n_sts_permohonan; ?></td>
                            <td><?php echo $data->tmpemohon->a_pemohon; ?></td>
                            <td><?php echo $data->tmpemohon->trkelurahan->n_kelurahan; ?></td>


                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Nama Perizinan</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Nama Pemohon</th>
                        <th>Status Permohonan</th>
                        <th>Alamat Pemohon</th>
                        <th>Kelurahan</th>
                    </tr>
                </tfoot>
            </table>        
        </div>
    </div>
    <br style="clear: both;" />
</div>
