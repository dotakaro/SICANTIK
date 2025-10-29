<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            // if($nopendaftaran==NULL){
            echo form_open('permohonan/save'); //}
            //else{echo form_open('permohonan/update');}
            echo form_hidden('id', $id);
            ?>
            <fieldset>
                <legend>Entry Data Perizinan</legend>
                <div id="statusRail">
                    <div id="leftRail">
                        <label>No Pendaftaran  &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp;</label>

                        <?php echo form_hidden('nopendaftaran', $nopendaftaran); ?>

                        <br><br><br>
                    </div>
                    <div id="rightRail">
                        <?php echo $nopendaftaran; ?>
                    </div>
                </div>
                <div id="statusRail">
                    <div id="leftRail" class="bg-grid">
                        <label>Jenis Layanan   &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</label>
                    </div>
                    <div id="rightRail" class="bg-grid">
                        <?php echo $jenislayanan; ?>
                    </div>
                </div>
                <div id="statusRail">
                    <div id="leftRail">
                        <label>Nama Pemohon  &nbsp; &nbsp;&nbsp; &nbsp;   &nbsp;  &nbsp;</label>
                    </div>
                    <div id="rightRail">
                        <?php echo $namapemohon; ?>
                    </div>
                </div>
                <div id="statusRail">
                    <div id="leftRail" class="bg-grid">
                        <label>Alamat Pemohon  &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp;  &nbsp;</label>
                    </div>
                    <div id="rightRail" class="bg-grid">
                        <?php echo $alamatpemohon; ?>
                    </div>
                </div>
                <div id="statusRail">
                    <div id="leftRail">
                        <label>Nama Perusahaan   &nbsp; &nbsp; &nbsp;  &nbsp;  &nbsp;</label>
                    </div>
                    <div id="rightRail">
                        <?php echo $namaperusahaan; ?>
                    </div>
                </div>



            </fieldset>
            <br><br>
            <p style="padding-left: 5px">
                <?php foreach ($list_form as $data) {
                ?>
                            <label><?php echo $data->n_property; ?>  &nbsp;&nbsp;  &nbsp;  &nbsp;</label>
                <?php
                            $property_input = array(
                                'name' => 'property[]',
                                'value' => '',
                                'class' => 'input-wrc',
                                'id' => 'permohonan'
                            );
                            echo form_input($property_input);
                ?><br>
                <?php } ?>
                <?php
                        echo form_submit('submit', 'Save');
                        echo form_reset('reset', 'Reset');
                        echo form_close();
                ?>
            </p>
        </div>
    </div>
    <br style="clear: both;" />
</div>
