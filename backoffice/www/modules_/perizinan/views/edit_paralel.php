<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1"><b>Jenis Perizinan Pararel</b></a></li>
                </ul>
            <div id="tabs-1">
        <?php
            $attr = array('id' => 'form');
            echo form_open('perizinan/paralel/' . $save_method, $attr);
            echo form_hidden('id_paralel', $id_paralel);
        ?>
        <label>Nama Izin Paralel</label>
        <?php
            $izin_paralel_input = NULL;
            if ($save_method === 'update') {
                $izin_paralel_input = array(
                    'name' => 'izin_paralel',
                    'value' => $izin_paralel,
                    'class' => 'input-wrc',
                    'disabled' => 'disabled'
                );
            } else {
                $izin_paralel_input = array(
                    'name' => 'izin_paralel',
                    'value' => $izin_paralel,
                    'class' => 'input-wrc required'
                );
            }


            echo form_input($izin_paralel_input);
        ?>&nbsp;&nbsp;&nbsp;<br />
        <label>Izin yang Terkait</label>
        <select id="listizin" name="listizin[]" multiple="multiple">
            <?php
            if($save_method === "update") {
                foreach ($list as $data) {
                    $showed = TRUE;
                    foreach ($list_izin_paralel as $izin) {
                        if($izin->id === $data->id) {
                            $showed = FALSE;
                            break;
                        }
                    }

                    if($showed) {
                    echo "<option value='".$data->id."'>".$data->n_perizinan."</option>";
                    }
                }
            } else {
                foreach ($list as $data) {
                    echo "<option value='".$data->id."'>".$data->n_perizinan."</option>";
                }
            }
            
            ?>
        </select>
        <br style="clear: both;" />
                </div>
                </div>
            <br>
        <?php
            $add_paralel = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($add_paralel);
            echo "<span></span>";
            if ($save_method === 'update') $site_url = site_url('perizinan/paralel/detail/'.$id_paralel);
            else $site_url = site_url('perizinan/paralel');
            $cancel_paralel = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. $site_url . '\''
            );
            echo form_button($cancel_paralel);
        ?>
        </div>
    </div>
</div>
    <br style="clear: both;" />
</div>
