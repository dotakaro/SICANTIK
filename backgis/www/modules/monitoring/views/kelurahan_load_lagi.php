<div class="contentForm" id="show_<?php echo $kelurahan_id; ?>">
    <?php
    if($_REQUEST)
    {
        $kecamatan = new trkecamatan();
        $kecamatan->get_by_id($_REQUEST['kecamatan_id']);
        $kelurahan = new trkelurahan();
        $list_kelurahan = $kelurahan->where_related($kecamatan)->order_by('n_kelurahan','ASC')->get();
	foreach ($list_kelurahan as $row){
            $opsi_kelurahan['0'] = "------Pilih salah satu------";
            $opsi_kelurahan[$row->id] = $row->n_kelurahan;
        }
        if(empty($opsi_kelurahan)){
            $opsi_kelurahan = array('0'=>'------Pilih salah satu------');
        }

        echo form_label('Kelurahan');
        echo form_dropdown($kelurahan_id, $opsi_kelurahan, '0',
                                     'class = "input-select-wrc" id="kelurahan_id"');
    }
    ?>
</div>