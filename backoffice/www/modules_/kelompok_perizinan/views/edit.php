<?php echo $this->load->view('edit_script');?>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name ?></h2>
        </div>
        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1"><b>Data Kelompok</b></a></li>
                    <li><a href="#tabs-2"><b>Alur Perizinan</b></a></li>
                </ul>
                <div id="tabs-1">
                    <?php
                    $attr = array('id' => 'form');
                    echo form_open('kelompok_perizinan/' . $save_method, $attr);
                    $hiddenId = array(
                        'name' => 'id',
                        'id' => 'api_id',
                        'value' => $data->id,
                        'type'=>'hidden'
                    );
                    echo form_input($hiddenId);
                    ?>
                    <label>Nama Kelompok Perizinan</label>
                    <?php
                    $txtNamaKelompok = array(
                        'name' => 'n_kelompok',
                        'id' => 'n_kelompok',
                        'class' => 'input-wrc required',
                        'value' => $data->n_kelompok,
                        'style'=>'width:600px'
                    );
                    echo form_input($txtNamaKelompok);?>
                    <br style="clear: both" />
                </div>
                <div id="tabs-2">
                    <?php
                    echo '<table>';
                        echo '<thead>';
                            echo '<td>&nbsp;</td>';
                            echo '<td>Nama Status</td>';
                        echo '</thead>';
                        echo '<tbody>';
                        if($status_permohonan->id){
                            foreach($status_permohonan as $indexStatus=>$status){//Looping setiap langkah perizinan
                                echo '<tr>';
                                    echo '<td>';
                                        $checked = false;
                                        if(in_array($status->id, $listStsPermohonanId)){//Jika ada di list Status Permohonan Langkah
                                            $checked = true;
                                        }

                                        $additional = '';
                                        //Jika ada di List Mandatory, disabled checkbox dan masukkan valuenya ke hidden input
                                        if(in_array($status->id, $listMandatoryStatus)){
                                            $additional = 'disabled';
                                            $checked = true;
                                            echo form_hidden(
                                                'langkah_perizinan['.$indexStatus.'][trstspermohonan_id]',
                                                $status->id
                                            );
                                        }

                                        echo form_checkbox(
                                            'langkah_perizinan['.$indexStatus.'][trstspermohonan_id]',
                                            $status->id,
                                            $checked,
                                            $additional
                                        );

                                    echo '</td>';
                                    echo '<td>';
                                        echo $status->n_sts_permohonan;
                                    echo '</td>';
                                echo '</tr>';
                            }
                        }
                        echo '</tbody>';
                    echo '</table>';
                    ?>
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
                'onclick' => 'parent.location=\''. site_url('kelompok_perizinan') . '\''
            );
            echo form_button($btnCancel);
            echo form_close();
            ?>
        </div>
    </div>
</div>

