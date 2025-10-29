    <?php
    if($_REQUEST)
    {
        $kecamatan = new trkecamatan();
        $kecamatan->get_by_id($_REQUEST['kecamatan_id']);
        $kelurahan = new trkelurahan();
        $list_kelurahan = $kelurahan->where_related($kecamatan)->order_by('n_kelurahan','ASC')->get();
        $opsi_kelurahan = array('0'=>'-------Pilih data-------');
        foreach ($list_kelurahan as $row)
        {
            $opsi_kelurahan[$row->id] = $row->n_kelurahan;
        }
        if(!empty($row))
        {
        echo form_dropdown("kelurahan_pemohon", $opsi_kelurahan, '0','class = "input-select-wrc notSelect" id="kelurahan_pemohon_id"');
        }
        else 
        { 
        $opsi_kelurahan = array('0'=>'-------Tidak ada data-------');
        echo form_dropdown("kelurahan_pemohon", $opsi_kelurahan, '0','class = "input-select-wrc notSelect" id="kelurahan_pemohon_id"');
        }
    }
    ?>