
<script type="text/javascript">
    // < ![CDATA[
    var base_url = "<?php echo base_url(); ?>";
    var kabupaten_id = "<?php echo $kabupaten_id; ?>";
    var kecamatan_id = "<?php echo $kecamatan_id; ?>";
    //]] >
    $(document).ready(function() {
        $('#'+kabupaten_id+'_id').change(function(){
             
                $.post(base_url+'monitoring/monitoringkecamatan/'+kecamatan_id, {
                    kabupaten_id: $('#'+kabupaten_id+'_id').val()
                }, function(response){
                    setTimeout("finishAjax('show_"+kecamatan_id+"', '"+escape(response)+"')", 400);
                });
                return false;
        });
    } );

    function finishAjax(id, response){
      $('#'+id).html(unescape(response));
      $('#'+id).fadeIn();
    }
</script>
<div class="contentForm" id="show_<?php echo $kabupaten_id; ?>">
    <?php
    if($_REQUEST)
    {
        $propinsi = new trpropinsi();
        $propinsi->get_by_id($_REQUEST['propinsi_id']);
        $kabupaten = new trkabupaten();
        $list_kabupaten = $kabupaten->where_related($propinsi)->order_by('n_kabupaten','ASC')->get();
	foreach ($list_kabupaten as $row){
             $opsi_kabupaten['0'] = "------Pilih salah satu------";
            $opsi_kabupaten[$row->id] = $row->n_kabupaten;
        }
        if(empty($opsi_kabupaten)){
            $opsi_kabupaten = array('0'=>'------Pilih salah satu------');
        }

        echo form_label('Kabupaten');
        echo form_dropdown($kabupaten_id, $opsi_kabupaten, '0',
             'class = "input-select-wrc" id="'.$kabupaten_id.'_id"');
    }
    ?>
</div>