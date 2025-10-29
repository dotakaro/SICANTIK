
<div class="contentForm" id="show_<?php echo $izin_id; ?>">
    <?php
    if ($_REQUEST) {
        $propertyizin = new trperizinan_trproperty();
        $perizinan = new trperizinan();
        $retribusi = new trretribusi();

        $perizinan->get_by_id($_REQUEST['izin_id']);

        //$propertyizin->where('c_retribusi_id', '1')->get();

        $list_retribusi = $perizinan->$retribusi->get();//$retribusi->where('perizinan_id', $_REQUEST['izin_id'])->get();

        foreach ($list_retribusi as $row) {
            $opsi_retribusi[$row->id] = $row->v_retribusi;
            $retribusi = $row->v_retribusi;
        }
        if (empty($opsi_retribusi)) {
            $opsi_retribusi = array('0' => '');
            $retribusi = '0';
        }
        //echo form_dropdown('jenis_retribusi', $opsi_retribusi, '',

          //   'class = "input-select-wrc"  id="selector" ');
        ?>
    <input type="text" name="jenis_retribusi" value="<?php echo number_format($retribusi,2,",",".");?>" id="selector" readonly>
   
<?php
    }
?>
</div>