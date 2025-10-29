<?php echo $this->load->view('add_script');?>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name ?></h2>
        </div>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1"><b>Data API</b></a></li>
                </ul>
                <div id="tabs-1">
                    <?php
                    $attr = array('id' => 'form');
                    echo form_open('property_api/' . $save_method, $attr);
                    ?>
                    <label>URL API</label>
                    <?php
                    $txtApiUrl = array(
                        'name' => 'api_url',
                        'id' => 'api_url',
                        'class' => 'input-wrc required',
                        'style'=>'width:600px'
                    );
                    echo form_input($txtApiUrl);?>
                    <br style="clear: both" />

                    <label>Deskripsi Singkat</label>
                    <?php
                    $txtShortDesc = array(
                        'name' => 'short_desc',
                        'id' => 'short_desc',
                        'class' => 'input-wrc required',
                        'style'=>'width:600px'
                    );
                    echo form_input($txtShortDesc);?>
                    <br style="clear: both" />

                    <label>Tipe Data</label>
                    <?php
                    echo form_dropdown('data_type', $list_type, null, 'class="required-option input-select-wrc" id="data_type"');
                    ?>
                    <br style="clear: both" />

                    <label>Level Struktur Dasar</label>
                    <?php
                    $txtRootLevel = array(
                        'name' => 'root_level',
                        'id' => 'root_level',
                        'class' => 'input-wrc required',
                        'style'=>'width:600px',
                        'min'=>1
                    );
                    echo form_input($txtRootLevel);?>
                    <br style="clear: both" />
                </div>
            </div>
            <br style="clear: both;" />
            <?php
            $btnSubmit = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($btnSubmit);

            echo "<span></span>";
            $btnCancel = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('property_api') . '\''
            );
            echo form_button($btnCancel);
            echo form_close();
            ?>
        </div>
    </div>
</div>

