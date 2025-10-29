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
                "sAjaxSource": "<?php echo site_url('monitoring/monitoringstspermohonan/selector') . "/" ?>" + value,
                "fnServerData": function ( sSource, aoData, fnCallback ) {
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
            <legend>Filter Per Status Permohonan</legend>
            <?php echo form_open('monitoring/monitoringstspermohonan/cetak_stspermohonan');?>
               <select class="input-select-wrc" id="selector" name="statusmohon" style="width: 410px">
                <?php

                    foreach ($liststspermohonan as $row){
                        echo "<option value=".$row->id.">".$row->n_sts_permohonan."</option>";
                    }
                ?>
                  </select>
                  <?php $cetak = array(
                      'name' =>'submit',
                      'type' => 'submit',
                      'class' => 'button-wrc',
                      'content' => 'Cetak',
                  );echo form_button($cetak);
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
