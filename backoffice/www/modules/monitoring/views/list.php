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
<script>
    function warning()
    {
        alert('Permohonan hampir/melewati batas SOP Pengerjaan');
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
            echo form_open("monitoring", $attr);

            if ($list_ijin) {
                $opsi_ijin[NULL] = "------Pilih salah satu------";
                foreach ($list_ijin as $row) {
                    $opsi_ijin[$row->id] = $row->n_perizinan;
                }
            } else {
                $opsi_ijin[0] = "";
            }
            $periodeawal_input = array(
                'name' => 'first_date',
                'class' => 'monbulan',
                'id' => 'firstDateInput',
                'readOnly' => TRUE,
                'value' => $first
            );

            $periodeakhir_input = array(
                'name' => 'second_date',
                'class' => 'monbulan',
                'id' => 'secondDateInput',
                'readOnly' => TRUE,
                'value' => $second
            );
            $cari = array(
                'name' => 'submit',
                'class' => 'button-wrc',
                'content' => 'Cari',
                'value'=>'Cari',
                'type' => 'submit',
                'onclick' => 'return validasi()'
            );
            ?>
            <table id="t_cari" width="100%">
                <tr>
                    <td width="15%"><?php echo form_label('Jenis Izin', 'label_izin');
            echo form_hidden('mark', 'tanda'); ?></td>
                    <td width="85%">
                        <?php
                        if ($mark == "tanda") {
                            echo form_dropdown('jenis_izin', $opsi_ijin, $jenis, 'class = "input-select-wrc" id="selector"');
                        } else {
                            echo form_dropdown('jenis_izin', $opsi_ijin, '0', 'class = "input-select-wrc" id="selector"');
                        }
                        ?>                </td>
                </tr>
                <tr>
                    <td> <?php echo form_label('Periode Awal', 'd_tahun'); ?> </td>
                    <td> <?php echo form_input($periodeawal_input); ?>  </td>
                </tr>
                <tr>
                    <td>  <?php echo form_label('Periode Akhir', 'd_tahun'); ?> </td>
                    <td>  <?php echo form_input($periodeakhir_input); ?> </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td><?php echo form_button($cari);
                        echo form_close(); ?></td>
                    <?php
                        $attr = array(
                            'class' => 'searchForm',
                            'id' => 'searchForm',
                        );
                        echo form_open('monitoring/monitoringpengizin/cetak_monitoring_ambil', $attr);
                        echo form_hidden('ambilizin', $jenis);
                        echo form_hidden('first_date', $first);
                        echo form_hidden('second_date', $second);

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
            <div id="rightMainRail">

    <!--                <select class="input-select-wrc" id="selector" name="jenis_izin">-->
                <?php
//                    foreach ($list_ijin as $row) {
//                        echo "<option value=" . $row->id . ">" . $row->n_perizinan . "</option>";
//                    }
                ?>
                <!--                </select>-->
            </div>

            <div id="rightMainRail">

            </div>

            <div id="rightMainRail">

            </div>

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
                        <th>Alamat Lokasi Izin</th>
                        <th>Status</th>
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
<style type="text/css">
#selector{
	width:270px;
}
ul.ui-multiselect-checkboxes label{
	width:240px;

</style>
<script type="text/javascript">
$("#selector").multiselect({
	multiple: false,
	header: " ",
   	noneSelectedText: "Select an Option"
}).multiselectfilter();
</script>
