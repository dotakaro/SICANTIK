<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            echo form_open('perizinan/alurizin/' . $save_method);
            echo form_hidden('id', $id);
            ?>
            <label class="label-wrc">Jenis perizinan</label>
            <select name="opsi_izin" class="input-wrc">
                <?php
                    $selected = NULL;
                    foreach ($list_izin as $dtizin) {
                        if ($dtizin->id == $perizinan_id) {
                            $selected = ' selected="selected" ';
                        } else {
                            $selected = NULL;
                        }

                        echo "<option value=\"" . $dtizin->id . "\"" . $selected . ">"
                            . $dtizin->n_perizinan . "</option>\n" ;
                    }
                ?>
            </select>
            <br>
            <label class="label-wrc">Peran</label>
            <select name="opsi_peran" class="input-wrc">
                <?php
                    $selected = NULL;
                    foreach ($list_peran as $dtperan) {
                        if ($dtperan->id == $perizinan_id) {
                            $selected = ' selected="selected" ';
                        } else {
                            $selected = NULL;
                        }

                        echo "<option value=\"" . $dtperan->id . "\"" . $selected . ">"
                            . $dtperan->description . "</option>\n" ;
                    }
                ?>
            </select>
            <br>

            <?php
            $add_ijin = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($add_ijin);
            echo "<span></span>";
            $cancel_ijin = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('perizinan/alurizin') . '\''
            );
            echo form_button($cancel_ijin);
            echo form_close();
            ?>
        </div>
    </div>
    <br style="clear: both;" />
</div>