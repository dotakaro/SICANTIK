<script type="text/javascript">
    $(document).ready(function() {
                $('#kabupaten_pemohon_id').change(function(){
                        $.post('<?php echo  base_url() ;?>pelayanan/pendaftaran/kecamatan_pemohon', { kabupaten_id: $('#kabupaten_pemohon_id').val() },
                                                               function(data) {
                                                                 $('#show_kecamatan_pemohon').html(data);
                                                                 $('#show_kelurahan_pemohon').html('Data Tidak tersedia');
                                                               });

                });
    });
</script>
    <?php
    if($_REQUEST)
    {
        $propinsi = new trpropinsi();
        $propinsi->get_by_id($_REQUEST['propinsi_id']);
        $kabupaten = new trkabupaten();
        $list_kabupaten = $kabupaten->where_related($propinsi)->order_by('n_kabupaten','ASC')->get();
        $opsi_kabupaten = array('0'=>'-------Pilih data-------');
        foreach ($list_kabupaten as $row)
        {
            $opsi_kabupaten[$row->id] = $row->n_kabupaten;
         }
         if(!empty($row))
         {
            echo form_dropdown("kabupaten_pemohon", $opsi_kabupaten, '0','class = "input-select-wrc notSelect" id="kabupaten_pemohon_id"');
         }
         else 
         {
            $opsi_kabupaten = array('0'=>'-------Tidak ada data-------');
            echo form_dropdown("kabupaten_pemohon", $opsi_kabupaten, '0','class = "input-select-wrc notSelect" id="kabupaten_pemohon_id"'); 
         }
        
    }
    ?>