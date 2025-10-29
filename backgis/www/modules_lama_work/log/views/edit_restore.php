<style>
    .input-upload-wrc{
        margin-right: -5px;
    }
    a{
        text-decoration: none;
    }
</style>


<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name ?></h2>
        </div>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1"><b>Restore Database</b></a></li>
                </ul>
                <div id="tabs-1">
                    <?php
                    $flashmessage = $this->session->flashdata('pesan');
                    echo!empty($flashmessage) ? '<font color="red" id="pesan"> Pesan : ' . $flashmessage . '</font><hr/><br/>' : '';
                    ?>
                    <?php
                    $attr = array('id' => 'form');
                    echo form_open_multipart('log/log_backup/' . $save_method, $attr);
                    ?>
                   

                    <label>File</label>
                    <?php
                    $file = array(
                        'name' => 'file',
                        'value' => ''
                    );
                    echo form_upload($file) . ' &nbsp; format (.zip), size kurang dari 1 Mb';
                     ?>

                    <br />
            
                    <br style="clear: both" />
                </div>

            </div>
            <br>
            <?php
            $upload = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($upload);
            echo "<span></span>";
             $cancel = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\'' . base_url() . '\''
            );
            echo form_button($cancel);
            echo form_close();
            ?>

        </div>
    </div>
    <br style="clear: both;" />
</div>
