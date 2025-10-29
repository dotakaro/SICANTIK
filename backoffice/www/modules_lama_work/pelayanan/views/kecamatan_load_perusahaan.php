<script type="text/javascript">
    $(document).ready(function() {
        $('#kecamatan_usaha_id').change(function(){
                        $.post('<?php echo  base_url() ;?>pelayanan/pendaftaran/kelurahan_usaha', { kecamatan_id: $('#kecamatan_usaha_id').val() },
                                                               function(data) {
                                                                 $('#show_kelurahan_usaha').html(data);
                                                               });

                });
    });
</script>
    <?php
    if($_REQUEST)
    {
        $kabupaten = new trkabupaten();
        $kabupaten->get_by_id($_REQUEST['kabupaten_id']);
        $kecamatan = new trkecamatan();
        $list_kecamatan = $kecamatan->where_related($kabupaten)->order_by('n_kecamatan','ASC')->get();
        $opsi_kecamatan = array('0'=>'-------Pilih data-------');
        foreach ($list_kecamatan as $row)
        {
            $opsi_kecamatan[$row->id] = $row->n_kecamatan;
        }
        if(!empty($row))
        {
            echo form_dropdown("kecamatan_usaha", $opsi_kecamatan, '0','class = "input-select-wrc notSelect" id="kecamatan_usaha_id"');
        }else 
        { 
            $opsi_kecamatan = array('0'=>'-------Tidak ada data-------');
            echo form_dropdown("kecamatan_usaha", $opsi_kecamatan, '0','class = "input-select-wrc notSelect" id="kecamatan_usaha_id"');
        }
    }
    ?>
