<div class="contentForm" id="show_<?php echo $kelurahan_id; ?>">
    <?php
    if($_REQUEST)
    {
        $propinsi = new trpropinsi();
        $propinsi->get_by_id($_REQUEST['propinsi_id']);
        $kabupaten = new trkabupaten();
        $list_kabupaten = $kabupaten->where_related($propinsi)->order_by('n_kabupaten','DESC')->get();
	foreach ($list_kabupaten as $row){
         // $opsi_kabupaten['0'] = "------Pilih salah satu------";
            
        }
      if (empty($row->id))
      {
        $row->id = " ";
      }
      
      $kabupaten = new trkabupaten();
        $kabupaten->get_by_id($row->id);
        $kecamatan = new trkecamatan();
        $list_kecamatan = $kecamatan->where_related($kabupaten)->order_by('n_kecamatan','DESC')->get();
            foreach ($list_kecamatan as $row_kecamatan){
                
             }
         if (empty($row_kecamatan))
        {
            $row_kecamatan->id = " ";
        }
        //---------------------------------------------
        
        $kecamatan = new trkecamatan();
        $kecamatan->get_by_id($row_kecamatan->id);
        $kelurahan = new trkelurahan();
        $list_kelurahan = $kelurahan->where_related($kecamatan)->order_by('n_kelurahan','ASC')->get();
	foreach ($list_kelurahan as $row){
            $opsi_kelurahan[$row->id] = $row->n_kelurahan;
        }
        if(empty($opsi_kelurahan)){
          $opsi_kelurahan = array(''=>'-------tidak ada data-------');
       }

        echo form_label('Kelurahan');
        echo form_dropdown($kelurahan_id, $opsi_kelurahan, ' ',
             'class = "input-select-wrc notSelect" id="'.$kelurahan_id.'_id"');
    }
    ?>
</div>