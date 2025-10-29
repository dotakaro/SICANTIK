
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            $attr = array('id' => 'form');
            echo form_open('role/' . $save_method, $attr);
            echo form_hidden('id', $id);
            ?>
            <label class="label-wrc">ID Peran</label>
            <?php
            $id_role_input = array(
                'name' => 'id_role',
                'value' => $id_role,
                'class' => 'input-wrc required'
            );
            echo form_input($id_role_input);
            ?><br />
            <label class="label-wrc">Hak Akses</label>
            <?php
            $description_input = array(
                'name' => 'description',
                'value' => $description,
                'class' => 'input-wrc required'
            );
            echo form_input($description_input);
            ?><br />
            <label>&nbsp;</label>
            <div class="spacer"></div>

            <?php
            $add_role = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($add_role);
            echo "<span></span>";
            $cancel_role = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('role') . '\''
            );
            echo form_button($cancel_role);
            echo form_close();
            ?>
        </div>
    </div>
    <br style="clear: both;" />
</div>
