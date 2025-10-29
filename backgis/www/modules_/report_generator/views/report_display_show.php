<div id="content">
    <div class="post">
        <div class="entry">
            <h2 style="text-align: center;"><?php echo $reportTitle;?></h2>
            <?php if(!empty($list)){?>
                <?php
                echo '<table>';
                    echo '<tr>';
                        echo '<td>';
                        echo form_open('report_generator/report_display/show/excel','target="_blank" method="POST"');
                        foreach($hiddenInputs as $inputName=>$inputValue){
                            echo form_hidden($inputName, $inputValue);
                        }
                        echo form_submit('btn_download','Simpan sebagai Excel','class="button-wrc"  ');
                        echo form_close();
                        echo '</td>';
                        echo '<td>';
                        echo form_open('report_generator/report_display/show/pdf','target="_blank" method="POST"');
                        foreach($hiddenInputs as $inputName=>$inputValue){
                            echo form_hidden($inputName, $inputValue);
                        }
                        echo form_submit('btn_download','Simpan sebagai PDF','class="button-wrc"  ');
                        echo form_close();
                        echo '</td>';
                    echo '</tr>';
                echo '<table>';
                ?>
                <table cellpadding="0" cellspacing="0" border="0" class="display" id="tbl_result">
                    <thead>
                    <?php
                    foreach($list[0] as $field=>$value){
                        echo '<th>'.$field.'</th>';
                    }
                    ?>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($list as $index=>$data){
                        echo '<tr>';
                        foreach($data as $field=>$value){
                            echo '<td>'.$value.'</td>';
                        }
                        echo '</tr>';
                    }
                    ?>
                    </tbody>
                </table>
            <?php }?>
        </div>
    </div>
    <br style="clear: both;" />
</div>
