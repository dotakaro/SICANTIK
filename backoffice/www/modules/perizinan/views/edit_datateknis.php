<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            echo form_open('perijinan/datateknis/' . $save_method);
            echo form_hidden('id', $id);
            ?>
            <label class="label-wrc">Nama Property</label>
            <?php
            $n_property_input = array(
                'name' => 'n_property',
                'value' => $n_property,
                'class' => 'input-wrc'
            );
            echo form_input($n_property_input);
            ?><br />
            <label class="label-wrc">ID Perijinan</label>
            <?php
            $izin_id_input = array(
                'name' => 'perijinan_id',
                'value' => $perijinan_id,
                'class' => 'input-wrc'
            );
            echo form_input($izin_id_input);
            ?><br />
            <label class="label-wrc">Kode Retribusi</label>
            <?php
            $c_retr_input = array(
                'name' => 'c_retribusi',
                'value' => $c_retribusi,
                'class' => 'input-wrc'
            );
            echo form_input($c_retr_input);
            ?><br />
            <?php
            $add_alur = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($add_alur);
            echo "<span></span>";
            $cancel_alur = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('perijinan/datateknis') . '\''
            );
            echo form_button($cancel_alur);
            echo form_close();
            ?>
        </div>
    </div>
    <br style="clear: both;" />
</div>