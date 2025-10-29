<script type="text/javascript">
    $(document).ready(function() {
        $('#selector').change(function(){
            var value = $('#selector').val();
            oTable = $('#monitoring').dataTable({
                "bProcessing": true,
                "bServerSide": true,
                "bJQueryUI"  : true,
                "bDestroy": true,
                "bFilter" : false,
                "sAjaxSource": "<?php echo site_url('monitoring/selector') . "/" ?>" + value,
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

<div class="contentForm" id="show_<?php echo $cetak_m; ?>">
    <?php
    if($_REQUEST)
    {
       /*  $propertyizin = new trperizinan_trproperty();
         $perizinan    = new trperizinan();
         $property     = new trproperty();

        $perizinan->get_by_id($_REQUEST['izin_id']);

         $propertyizin->where('c_retribusi_id', '1')->get();

         $list_property = $property->where_related($perizinan)->get();
         //
         	foreach ($list_property as $row){
            if($row->c_type != '2'){
            $opsi_property[$row->id] = $row->n_property;

            }
            echo form_hidden('izin_id',$perizinan->id);
        }
        if(empty($opsi_property)){
            $opsi_property = array('0'=>'');
        }

        echo form_dropdown('jenis_property', $opsi_property, '',
             'class = "input-select-wrc" id="selector"');

       ?>
    <input type="hidden" name="id_izin" value="<?php echo $perizinan->id;?>" id="selector">
    <?php

        echo $perizinan->id;



*/

         //
          $cetak = array(
                            'name' => 'button',
                            'class' => 'button-wrc',
                            'content' => 'Cetak',
                            'onclick' => 'parent.location=\'' . site_url('permohonan/bap') . '\''
                        );
                        echo form_button($cetak);
                      }
       ?>
    
</div>