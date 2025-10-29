<script type="text/javascript">
    // < ![CDATA[
    var base_url = "<?php echo base_url(); ?>";
    var kecamatan_id = "<?php echo $kecamatan_id; ?>";
    var kelurahan_id = "<?php echo $kelurahan_id; ?>";
    //]] >
    $(document).ready(function() {
        $('#'+kecamatan_id+'_id').change(function(){
              
                $.post(base_url+'monitoring/monitoringkecamatan/'+kelurahan_id, {
                    kecamatan_id: $('#'+kecamatan_id+'_id').val()
                }, function(response){
                    setTimeout("finishAjax('show_"+kelurahan_id+"', '"+escape(response)+"')", 400);
                });
                return false;
        });
    } );

    function finishAjax(id, response){
      $('#'+id).html(unescape(response));
      $('#'+id).fadeIn();
    }
</script>
<div class="contentForm" id="show_<?php echo $kecamatan_id; ?>">
    <?php
    if($_REQUEST)
    {
        $kabupaten = new trkabupaten();
        $kabupaten->get_by_id($_REQUEST['kabupaten_id']);
        $kecamatan = new trkecamatan();
        $list_kecamatan = $kecamatan->where_related($kabupaten)->order_by('n_kecamatan','ASC')->get();
	foreach ($list_kecamatan as $row){
             $opsi_kecamatan['0'] = "------Pilih salah satu------";
            $opsi_kecamatan[$row->id] = $row->n_kecamatan;
        }
        if(empty($opsi_kecamatan)){
            $opsi_kecamatan = array('0'=>'------Pilih salah satu------');
        }

        echo form_label('Kecamatan');
        echo form_dropdown($kecamatan_id, $opsi_kecamatan, '0',
             'class = "input-select-wrc" id="'.$kecamatan_id.'_id"');
    }
    ?>
</div>