<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            $dataproperti=array('name'=>'frmperan','id'=>'frmperan');
            echo form_open('pengguna/flush',$dataproperti);
            echo form_hidden('id', $id);
            echo form_hidden('backto', $backto);
            $checked = FALSE;
            ?>

            <ul id="data_list">
                <li><label>Nama Asli</label></li>
                <li><label><b><?php echo $real_name; ?></b></label></li>
            </ul>
            <br style="clear: both;"/>
            <br/>
            <ul id="data_list">
                <li><label>Username</label></li>
                <li><label><b><?php echo $user_name; ?></b></label></li>
            </ul>
            <br/><br/><br/>
            <div id="tabs">
                <ul>
                    <?php
                    if($this->input->post("button")==="Tambah Peran")
                    {
                        echo "<li><a href='#tabs-1'>Peran Per Hak Akses</a></li>";
                    }
                    else
                    {
                        echo "<li><a href='#tabs-2'>Peran Per Perizinan</a></li>";
                    }
                    echo "</ul>";
                    if($this->input->post("button")==="Tambah Peran")
                    {
                    ?>                
                <div id="tabs-1">
                    <ul id="data_list">
                        <li><a href='#' style="color: #00C632;font-size: 12px;font-style: italic;" onClick='check_uncheckAll(document.frmperan.peran,true);return false;'>Check All</a>&nbsp;&nbsp;&nbsp;<a href='#' style="color: #00C632;font-size: 12px;font-style: italic;"  onClick='check_uncheckAll(document.frmperan.peran,false);return false;'>Uncheck All</a></li>
                    <?php
                        foreach ($list as $data) {
                            $showed = TRUE;
                            ?>
                        <li>
                        <?php
                            foreach ($user_role as $role) {
                                if($role->id === $data->id) {
                                    $showed = FALSE;
                                    break;
                                }
                            }

                            if($showed) {
                                $set = array(
                                    'name' => 'peran[]','id' =>'peran','value' => $data->id
                                );
                                echo form_checkbox($set);
                                echo $data->description;
                            }

                        ?>
                        </li>
                        <?php
                        }
                    ?>
                    </ul>
                </div>
                <?php
                    }
                    else
                    {                        
                    ?>
                <div id="tabs-2">
                    <ul id="data_list">
                    <li><a href='#' style="color: #00C632;font-size: 12px;font-style: italic;" onClick='check_uncheckAll(document.frmperan.izin,true);return false;'>Check All</a>&nbsp;&nbsp;&nbsp;<a href='#' style="color: #00C632;font-size: 12px;font-style: italic;" onClick='check_uncheckAll(document.frmperan.izin,false);return false;'>Uncheck All</a></li>
                    <?php
                        if($cek_all == "yes") $checked = TRUE;
                        else $checked = FALSE;
                        
                        foreach ($list_izin as $data_izin) {
                            $showed = TRUE;
                            ?>
                        <li>
                        <?php
                            foreach ($izin_role as $izin) {
                                if($izin->id === $data_izin->id) {
                                    $showed = FALSE;
                                    break;
                                }
                            }

                            if($showed) {
                                $set = array(
                                    'name' => 'izin[]',
                                    'id' => 'izin',
                                    'value' => $data_izin->id,
                                    'checked' => $checked
                                );
                                echo form_checkbox($set);
                                echo $data_izin->n_perizinan;
                            }
                            
                        ?>
                        </li>
                        <?php
                        }
                    ?>
                    </ul>
                </div>
                <?php
                    }//dfdfgdfg
             ?>
            </div>            
            <div style="clear:both;"/>
            <div class="spacer"></div>
            <br />
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
            $alamaturl="";
            if($this->uri->segment(4)=="yes")
            {
                $url=site_url('petugas');
            }
            else
            {
                $url=site_url('pengguna/edit/'.$id);
            }
            $cancel = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                 'onclick' => 'parent.location=\'' . $url . '\''
            );
            echo form_button($cancel);
            echo form_close();
            ?>
        </div>
    </div>
    <br style="clear: both;" />
</div>
