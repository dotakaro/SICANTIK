<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            $attr = array('id' => 'form');
            if($save_method === 'save') {
                echo form_open('pengguna/'. $save_method, $attr);
                echo "<label class='label-wrc'>Nama Asli</label>";
                $realname_input = array(
                    'name' => 'real_name',
                    'class' => 'input-wrc'
                );
                echo form_input($realname_input);
                echo "<br style='clear:both' />";
                echo "<label class='label-wrc'>Username</label>";
                $username_input = array(
                    'name' => 'user_name_',
                    'class' => 'input-wrc'
                );
                echo form_input($username_input);
                echo "<br style='clear:both' />";
                echo "<label class='label-wrc'>Password</label>";
                $password_input = array(
                    'name' => 'password',
                    'class' => 'input-wrc',
					'id'=>'password'
                );
                echo form_password($password_input);
                echo "<br style='clear:both' />";
                echo "<label class='label-wrc'>Confirm Password</label>";
                $confirm_password = array(
                    'name' => 'confirm_password',
                    'class' => 'input-wrc',
					'id'=>'confirm_password'
                );
				echo form_password($confirm_password);
                echo "<br style='clear:both' />";
                $save_form = array(
                    'name' => 'submit',
                    'class' => 'submit-wrc',
                    'content' => 'Simpan',
                    'type' => 'submit',
                    'value' => 'Simpan'
                );
                echo form_submit($save_form);
                echo "<span></span>";
                $cancel = array(
                    'name' => 'button',
                    'class' => 'button-wrc',
                    'content' => 'Batal',
                    'onclick' => 'parent.location=\''. site_url('pengguna') . '\''
                );
                echo form_button($cancel);
                echo form_close();
            } else {

                ?>
                <label class="label-wrc">Username</label>
                <?php

                    echo $user_name;
                ?><br /><br />

                <div id="tabs">
                    <ul>
                        <li><a href="#tabs-1">Ganti Nama</a></li>
                        <li><a href="#tabs-2">Ganti Password</a></li>
						<li><a href="#tabs-3">List Peran</a></li>
                        <li><a href="#tabs-4">List Izin</a></li>
						<?php if($is_admin){?>
						<li><a href="#tabs-5">Reset Password</a></li>
						<?php }?>
						<li><a href="#tabs-6">Hak Akses</a></li>
                    </ul>

                    <div id="tabs-1">
                        <label class="label-wrc">Nama Asli</label>
                        <?php
                           $attr = array('id' => 'form');
                            echo form_open('pengguna/' . $save_method . "/editName",$attr);
                            echo form_hidden('id', $id);
                            $realname_input = array(
                                'name' => 'real_name',
                                'value' => $real_name,
                                'class' => 'input-wrc required'
                            );
                            echo form_input($realname_input);
                            echo "<br />";
                            $edit_realname = array(
                                'name' => 'submit',
                                'class' => 'submit-wrc',
                                'content' => 'Simpan',
                                'type' => 'submit',
                                'value' => 'Simpan Nama'
                            );
                            echo form_submit($edit_realname);
                            echo "<span></span>";
                            $cancel = array(
                                'name' => 'button',
                                'class' => 'button-wrc',
                                'content' => 'Batal',
                                'onclick' => 'parent.location=\''. site_url('pengguna') . '\''
                            );
                            echo form_button($cancel);
                            echo form_close();
                        ?><br />
                    </div>
                    <div id="tabs-2">
                        <!-- edited 12-04-2013 -->
                        <?php
                        $attr = array('id' => 'form_password');
                            echo form_open('pengguna/' . $save_method . "/editPassword",$attr);
                            echo form_hidden('id', $id);
                            
							$password_lbl = array(
                                 'class' => 'label-wrc',
                            );
							
							echo form_label('Old Password','',$password_lbl);
                            $old_password = array(
                                'name' => 'old_password',
                                'class' => 'input-wrc required'
                            );
                            echo form_password($old_password)."<br>";
                            
							
                            echo form_label('New Password','',$password_lbl);
                            $password_input = array(
                                'name' => 'password1',
								'id'=>'password1',
                                'class' => 'input-wrc required'
                            );
                            echo form_password($password_input)."<br>";
                  			
							$confirm_password_lbl = array(
                                 'class' => 'label-wrc',
                            );
                            echo form_label('Confirm Password','',$password_lbl);
                            $confirm_password = array(
			                    'name' => 'confirm_password',
			                    'class' => 'input-wrc',
								'id'=>'confirm_password'
			                );          
                            echo form_password($confirm_password)."<br>";
                  			
                            $edit_password = array(
                                'name' => 'submit',
                                'class' => 'submit-wrc',
                                'content' => 'Simpan',
                                'type' => 'submit',
                                'value' => 'Simpan Password'
                            );
                            echo form_submit($edit_password);
                            echo "<span></span>";
                            $cancel = array(
                                'name' => 'button',
                                'class' => 'button-wrc',
                                'content' => 'Batal',
                                'onclick' => 'parent.location=\''. site_url('pengguna') . '\''
                            );
                            echo form_button($cancel);
                            echo form_close();
                        ?><br />
                        <!-- End edit -->
                    </div>
                    <div id="tabs-3">
                        <?php
                            echo form_open(site_url('pengguna/roles'));
                            echo form_hidden('id', $id);
                            $set_all = array(
                                'name' => 'cek_all',
                                'value' => 'yes',
                                'checked' => 'TRUE'
                            );
                            echo form_submit('button','Tambah Peran','class="button-wrc"');
                           // echo form_checkbox($set_all)."&nbsp;Cek Semua Izin&nbsp;";
                            echo form_close();
//                            $add_role = array(
//                                'name' => 'button',
//                                'class' => 'button-wrc',
//                                'content' => 'Tambah Peran',
//                                'onclick' => 'parent.location=\''. site_url('pengguna/roles') . '/' . $id . '\''
//                            );
//                            echo form_button($add_role);
                        ?>
                        <br />
                        <table cellpadding="0" cellspacing="0" border="0" class="display" id="peran_list">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Peran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i = 0;
                                    foreach ($peran_list as $peran) {
                                        $i++;
                                        ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $peran->description; ?></td>
                                    <td><center>
                                    <?php
                                        /*$delete = array(
                                            'name' => 'button',
                                            'content' => 'Hapus',
                                            'class' => 'button-wrc',
                                            'onclick' => 'parent.location=\''. site_url('pengguna/deleterole') .'/'. $id .'/'. $peran->id . '\''
                                        );
                                        echo form_button($delete);*/
                                        $img_delete = array(
                                            'src' => 'assets/images/icon/cross.png',
                                            'alt' => 'Delete',
                                            'title' => 'Delete',
                                            'border' => '0'
                                        );
                                        echo "<a href='". site_url('pengguna/deleterole') .'/'. $id .'/'. $peran->id."' onClick='return confirm(\"Apakah Anda yakin akan menghapusnya?\");'>".img($img_delete)."</a>";
                                    ?></center>
                                    </td>
                                </tr>
                                        <?php
                                        
                                    }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Property</th>
                                    <th>Aksi</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div id="tabs-4">
                        <?php
                            echo form_open(site_url('pengguna/roles'));
                            echo form_hidden('id', $id);
                            $set_all = array(
                                'name' => 'cek_all',
                                'value' => 'yes',
                                'checked' => 'TRUE'
                            );
                            echo form_submit('button','Tambah Izin','class="button-wrc"');
                           // echo form_checkbox($set_all)."&nbsp;Cek Semua Izin&nbsp;";
                            echo form_close();
//                            $add_role = array(
//                                'name' => 'button',
//                                'class' => 'button-wrc',
//                                'content' => 'Tambah Peran',
//                                'onclick' => 'parent.location=\''. site_url('pengguna/roles') . '/' . $id . '\''
//                            );
//                            echo form_button($add_role);
                        ?>
                        <br />
                        <table cellpadding="0" cellspacing="0" border="0" class="display" id="izin_list">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Izin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i = 0;
                                    foreach ($izin_list as $izin) {
                                        $i++;
                                        ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $izin->n_perizinan; ?></td>
                                    <td><center>
                                    <?php
                                        /*$delete = array(
                                            'name' => 'button',
                                            'content' => 'Hapus',
                                            'class' => 'button-wrc',
                                            'onclick' => 'parent.location=\''. site_url('pengguna/deleteizin') .'/'. $id .'/'. $izin->id . '\''
                                        );
                                        echo form_button($delete);*/
                                    $img_delete = array(
                                            'src' => 'assets/images/icon/cross.png',
                                            'alt' => 'Delete',
                                            'title' => 'Delete',
                                            'border' => '0'
                                        );
                                        echo "<a href='". site_url('pengguna/deleteizin') .'/'. $id .'/'. $izin->id."' onClick='return confirm(\"Apakah Anda yakin akan menghapusnya?\");'>".img($img_delete)."</a>";
                                    ?></center>
                                    </td>
                                </tr>
                                        <?php

                                    }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Property</th>
                                    <th>Aksi</th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
					
					<?php if($is_admin){?>
					<div id="tabs-5">
                        <!-- Added by Indra 28-07-2013 -->
                        <?php
                        
                        
                        $attr = array('id' => 'form_reset_password');
                            echo form_open('pengguna/' . $save_method . "/resetPassword",$attr);
                            echo form_hidden('id', $id);
                            
                            $password_lbl = array(
                                 'class' => 'label-wrc',
                            );
                            echo form_label('New Password','',$password_lbl);
                            $password_input = array(
                                'name' => 'new_reset_password',
                                'class' => 'input-wrc',
								'id'=>'new_reset_password'
                            );
                            echo form_password($password_input)."<br>";
                            
							echo form_label('Confirm New Password','',$password_lbl);
                            $confirm_password = array(
                                'name' => 'new_confirm_password',
                                'class' => 'input-wrc',
								'id'=>'new_confirm_password'
                            );
                            echo form_password($confirm_password)."<br>";
                            
							$edit_password = array(
                                'name' => 'submit',
                                'class' => 'submit-wrc',
                                'content' => 'Simpan',
                                'type' => 'submit',
                                'value' => 'Reset Password'
                            );
                            echo form_submit($edit_password);
                            echo "<span></span>";
                            $cancel = array(
                                'name' => 'button',
                                'class' => 'button-wrc',
                                'content' => 'Batal',
                                'onclick' => 'parent.location=\''. site_url('pengguna') . '\''
                            );
                            echo form_button($cancel);
                            echo form_close();
                        ?><br />
                        <!-- End edit -->
                    </div>
					<?php }?>
                    <div id="tabs-6">
                        <?php
                        $attr = array('id' => 'form');
                        echo form_open('pengguna/' . $save_method . "/editHakAkses",$attr);
                        echo form_hidden('id', $id);
                        ?>
                        User ini boleh melihat dan membuat data Unit :
                        <?php
                        /*if($getUnit->id){
                            echo '<br>';
                            foreach($getUnit as $indexUnit =>$optUnitKerja){
                                $checked = false;
                                if(in_array($optUnitKerja->id, $listHakAkses)){
                                    $checked = true;
                                }
                                echo form_checkbox('unit_akses['.$indexUnit.']', $optUnitKerja->id, $checked, $extra = 'id="unit_akses_'.$indexUnit.'"');
                                echo $optUnitKerja->n_unitkerja;
                                echo '<br>';
                            }
                        }*/
                        //Multiselect kiri kanan
                        if($getUnit->id){
                            echo '<select id="unit_akses" class="michaelMultiselect" multiple="multiple" name="unit_akses[]">';
                            foreach($getUnit as $indexUnit =>$optUnitKerja){
                                $selected = '';
                                if(in_array($optUnitKerja->id, $listHakAkses)){
                                    $selected = 'selected="selected"';
                                }
                                echo '<option value="'.$optUnitKerja->id.'" '.$selected.'>'.$optUnitKerja->n_unitkerja.'</option>';
                            }
                            echo '</select>';
                        }

                        $edit_hak_akses = array(
                            'name' => 'submit',
                            'class' => 'submit-wrc',
                            'content' => 'Simpan',
                            'type' => 'submit',
                            'value' => 'Simpan Hak Akses'
                        );
                        echo "<br>";
                        echo form_submit($edit_hak_akses);
                        echo "<span></span>";
                        $cancel = array(
                            'name' => 'button',
                            'class' => 'button-wrc',
                            'content' => 'Batal',
                            'onclick' => 'parent.location=\''. site_url('pengguna') . '\''
                        );
                        echo form_button($cancel);
                        echo form_close();
                        ?>
                    </div>
                </div>

                <label>&nbsp;</label>
                <div class="spacer"></div>
                <?php

            }
            
            ?>
        </div>
    </div>
    <br style="clear: both;" />
</div>
