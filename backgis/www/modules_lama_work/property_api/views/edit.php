<?php echo $this->load->view('edit_script');?>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name ?></h2>
        </div>
        <div class="entry">
            <?php
            $attr = array('id' => 'formAPI');
            echo form_open('property_api/' . $save_method, $attr);
            ?>
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1"><b>Data API</b></a></li>
                    <li><a href="#tabs-2"><b>Struktur Data</b></a></li>
                    <li><a href="#tabs-3"><b>Mapping Data</b></a></li>
                </ul>
                <div id="tabs-1">
                    <?php
                    $hiddenId = array(
                        'name' => 'id',
                        'id' => 'api_id',
                        'value' => $data->id,
                        'type'=>'hidden'
                    );
                    echo form_input($hiddenId);
                    ?>
                    <label>URL API</label>
                    <?php
                    $txtApiUrl = array(
                        'name' => 'api_url',
                        'id' => 'api_url',
                        'class' => 'input-wrc required',
                        'value' => $data->api_url,
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
                        'value' => $data->short_desc,
                        'style'=>'width:600px'
                    );
                    echo form_input($txtShortDesc);?>
                    <br style="clear: both" />

                    <label>Tipe Data</label>
                    <?php
                    echo form_dropdown('data_type', $list_type, $data->data_type, 'class="required-option input-select-wrc" id="data_type"');
                    ?>
                    <br style="clear: both" />

                    <label>Level Struktur Dasar</label>
                    <?php
                    $txtRootLevel = array(
                        'name' => 'root_level',
                        'id' => 'root_level',
                        'class' => 'input-wrc required',
                        'style'=>'width:600px',
                        'value'=>$data->root_level,
                        'min'=>1
                    );
                    echo form_input($txtRootLevel);?>
                    <br style="clear: both" />
                </div>
                <div id="tabs-2">
                    <label>Struktur Data</label>
                    <?php
                    $btnLoad = array(
                        'name' => 'btn_load',
                        'id' => 'btnLoad',
                        'value' => 'true',
                        'type' => 'button',
                        'class' => 'button-wrc',
                        'content' => 'Ambil Struktur'
                    );
                    $hiddenStructureLoaded = array(
                        'name' => 'structure_loaded',
                        'id' => 'structure_loaded',
                        'class' => 'required-hidden',
                        'value'=> ($dataPropertyHierarchy->id) ? 1 : '',
                        'type' => 'hidden'
                    );
                    echo form_button($btnLoad);
                    echo form_input($hiddenStructureLoaded);
                    ?>
                    <div id="tree"></div>
                    <br style="clear: both" />
                </div>
                <div id="tabs-3">
                    <button type="button" id="btnAddRowMapping">Tambah Mapping</button>
                    <table cellpadding="0" cellspacing="0" border="0" cl    ass="display" id="listMapping" style="width:500px;">
                        <thead>
                            <tr>
                                <th>Nama Tabel</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--<tr>
                                <td>
                                    <input type="text" id="mapping_0_table_name" class="combogrid-all-table">
                                </td>
                                <td>
                                    <input type="button" class="button-wrc btn-detail-mapping" value="Detail">
                                    <div class="dialog-detail">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <td>
                                                        Field Tabel
                                                    </td>
                                                    <td>
                                                        Field API
                                                    </td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="text" id="mapping_0_detail_0_field_table" class="combogrid-table-field">
                                                    </td>
                                                    <td>
                                                        <input type="text" id="mapping_0_detail_0_field_api" class="combogrid-table-field">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>-->
                        </tbody>
                    </table>
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

