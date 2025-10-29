 <?php
        $this->tr_instansi = new Tr_instansi();
        
        $logo = $this->tr_instansi->get_by_id(14);
        $img = array('src'=>'uploads/logo/' . $logo->value,
                     
                     'width'=>'70',
                     'height'=>'70');
   ?>
<div id="header">
    <div class="instansi">
    <?php echo img($img); ?>
        <p>
        <?php
            $folder = $this->tr_instansi->get_by_id(9);
            echo $folder->value;
            $app_city = $this->tr_instansi->get_by_id(4);
            $wilayah = new trkabupaten();
            $wilayah->get_by_id($app_city->value);
            echo br(1)."<font size='3'>".$wilayah->n_kabupaten."</font>";
            ?>
        </p>
    </div>
</div>