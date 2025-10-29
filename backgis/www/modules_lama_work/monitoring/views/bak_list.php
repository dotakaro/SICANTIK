<script type="text/javascript">
    $(document).ready(function() {
        $('#selector').change(function(){
            var value = $('#selector').val();
            oTable = $('#monitoring').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "bJQueryUI" : true,
                "bDestroy": true,
                "bFilter" : false,
                "sAjaxSource": "<?php echo site_url('monitoring/selector') . "/" ?>" + value,
                "fnServerData": function ( sSource, aoData, fnCallback ) {
                    //  var cek = document.getElementById('cek_cetak').value;
                    //     aoDAta.push({"name":"cek","value":$('cek').val()});
                    $.ajax( {
                        "dataType": 'json',
                        "type": "POST",
                        "url": sSource,
                        "data": aoData,
                        "success": fnCallback
                    } );
                }

            });
        });
    });
</script>

<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <fieldset id="half">
            <legend>Filter Per Jenis Perijinan</legend>
            <?php 
                echo form_open("monitoring/cetak_monitoring_jenisizin/");
                echo form_label('Jenis Izin', 'label_izin');
            ?>
            <div id="rightMainRail">
            <select class="input-select-wrc" id="selector" name="jenis_izin">
                <?php
                foreach ($list_ijin as $row) {
                    echo "<option value=" . $row->id . ">" . $row->n_perizinan . "</option>";
                }
                ?>
            </select>
            </div>

            <?php
                echo form_label('Periode Awal', 'd_tahun');
            ?>
            <div id="rightMainRail">
            <?php
                $periodeawal_input = array(
                    'name' => 'tgla',
                    'value' => '',
                    'class' => 'input-wrc',
                    'class' => 'monbulan'
                );
                echo form_input($periodeawal_input);
            ?>
            </div>
            <?php
                echo form_label('Periode Akhir', 'd_tahun');
            ?>
            <div id="rightMaintRail">
                <?php
                $periodeakhir_input = array(
                    'name' => 'tglb',
                    'value' => '',
                    'class' => 'input-wrc',
                    'class' => 'monbulan'
                );
                echo form_input($periodeakhir_input);
                ?>
            </div>
            <?php
                $cetak = array(
                    'name' => 'submit',
                    'class' => 'button-wrc',
                    'content' => 'Cetak',
                    'type' => 'submit',
                        //  'onclick' => 'parent.location=\'' . site_url('monitoring/cetak_monitoring_jenisizin') .'/'.'2'. '\''
                );
                //      if($cek === "1"){
                echo "&nbsp;" . form_button($cetak);
                //     }
                echo form_close();
?>

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
                    <tr>
                        <td colspan="6" class="dataTables_empty">Loading data from server</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Nama Perizinan</th>
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
