<div class="contentForm" id="show_<?php echo $property_id; ?>">
    <?php
    if($_REQUEST)
    {
         $propertyizin = new trperizinan_trproperty();
         $perizinan    = new trperizinan();
         $property     = new trproperty();

         $perizinan->get_by_id($_REQUEST['property_id']);

         $propertyizin->where('c_retribusi_id', '1')->get();

         $list_property = $property->where_related($perizinan)->get();

	foreach ($list_property as $row){
            $opsi_property[$row->id] = $row->n_property;
        }
        if(empty($opsi_property)){
            $opsi_property = array('0'=>'');
        }


        echo form_dropdown($izin_id, $opsi_property, '',
             'class = "input-select-wrc" id="'.$izin_id.'_id"');
    }
    ?>
</div>