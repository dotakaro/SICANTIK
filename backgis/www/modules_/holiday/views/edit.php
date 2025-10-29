<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1"><b>Hari Libur</b></a></li>
                </ul>
            <div id="tabs-1">
            <?php
            $attr = array('id' => 'form');
            echo form_open('holiday/' . $save_method, $attr);
            echo form_hidden('id', $id);
            ?>
            <label >Tanggal</label>
            <?php
            $holiday_input = array(
                'name' => 'date',
                'value' => $date,
                'class' => 'input-wrc required date',
                'readOnly'=>TRUE,
                'id' => 'holiday'
            );
            echo form_input($holiday_input);
            ?><br />
            <label >Keterangan</label>
            <?php
            $data = array(
                'name' => 'description',
                'id' => 'description',
                'value' => $description,
                'class' => 'input-area-wrc required',
                'style'=>  'width: 35%'
            );
            echo form_textarea($data);
            ?><br style="clear:both;" />
            <label >Status Hari Libur</label>
            
            <?php
            if ($holiday_type === 'Libur') {
                $check_day = TRUE;
                $check_sunday = FALSE;
            } else {
                $check_day = FALSE;
                $check_sunday = TRUE;
            }

            $data1 = array(
                'name' => 'holiday_type',
                'id' => 'holiday_type',
                'value' => 'Minggu',
                'checked' => $check_sunday
            );
            $data2 = array(
                'name' => 'holiday_type',
                'id' => 'holiday_type',
                'value' => 'Libur',
                'checked' => $check_day
            );
            echo form_radio($data1) ."Hari Minggu  ";
            echo form_radio($data2) ."Hari Libur";
          ?>
           
            <br style='clear: both'/>
 

        </div>
        </div>
            <br>
            <?php
            $add_user = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($add_user);
            echo "<span></span>";
            $cancel = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\'' . site_url('holiday') . '\''
            );
            echo form_button($cancel);
            echo "<span></span>";

            $setting = new settings();
            $setting->where('name','app_year')->get();

            $disabled = 'enabled';
            if($setting->value === date('Y')) {
                $disabled = 'disabled';
            }

            $set = array(
                'name' => 'button',
                'class' => 'button-wrc',
                $disabled => $disabled,
                'content' => 'Isi hari.',
                'onclick' => 'parent.location=\'' . site_url('holiday/get_new') . '\''
            );
            //echo form_button($set);
            echo form_close();
            ?>
        </div>
    </div>
    <br style="clear: both;" />
</div>
