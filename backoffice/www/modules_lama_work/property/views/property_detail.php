
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
       <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1"><b>Data Property</b></a></li>
                </ul>
            <div id="tabs-1">
            <?php
            $attr = array('id' => 'form');
            echo form_open('property/master/propertyedit/' . $save_method, $attr);
            echo form_hidden('id', $id);
            ?>
            <label >Nama Property</label>
            <?php
            $description_input = array(
                'name' => 'n_property',
                'value' => $n_property,
                'class' => 'input-wrc required'
            );
            echo form_input($description_input);
            ?>
            <br style="clear: both;" />
            <label >Status Property</label>
            <?php
                $status = "<input type=\"radio\" name=\"c_type\" ";
                $status_0 = "value=\"2\"";
                $status_1 = "value=\"0\"";
                if ($status_cont === '2') {
                    echo $status . $status_0 . "checked=\"checked\" />";
                    echo "Property Parent ";
                    if($save_method === "update"){
                        echo $status . $status_1 . " />";
                        echo "Property Anak";
                    }
                } else {
                    if($save_method === "update"){
                        echo $status . $status_0 . " />";
                        echo "Property Parent ";
                        echo $status . $status_1 . "checked=\"checked\" />";
                        echo "Property Anak";
                    }else{
                        echo $status . $status_0 . "checked=\"checked\" />";
                        echo "Property Parent ";
                    }
                }
            ?>
            <br style="clear: both;" />
            <label>&nbsp;</label>
            <div class="spacer"></div>
            </div>

            
        </div>
           <br>
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
                'onclick' => 'parent.location=\''. site_url('property/master/propertieslist') . '\''
            );
            echo form_button($cancel_role);
            echo form_close();
            ?>
    </div>
    </div>  
    </div>
    <br style="clear: both;" />
</div>
