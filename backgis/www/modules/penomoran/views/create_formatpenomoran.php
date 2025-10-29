<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">			
			<?php 
				$attr = array('id' => 'form');
				echo form_open('penomoran/formatpenomoran/' . $save_method, $attr);
			?>
			<?php echo form_hidden('id',$id);?>
			
			<fieldset id="half">
                <legend>Kode Format Penomoran</legend>
				<br>
				<div id="statusRail">
					<div id="leftRail" class="bg-grid">
					<?php
						echo form_label('Jenis Perizinan','perizinan');
					?>
					</div >
					<div id="rightRail" class="bg-grid">
						<select class="input-select-wrc required" name="perizinan" required="required">
						<option value="" selected="selected" >------------Pilih salah satu-----------</option>
						<?php
							foreach ($perizinan as $row){
								echo "<option value=".$row->id.">".$row->n_perizinan."</option>";
							}
						?>
						</select>
					</div>
				</div>			
				<div id="statusRail">
					<div id="leftRail">
					<?php
						echo form_label('Jenis Permohonan','permohonan');
					?>
					</div >
					<div id="rightRail">
						<select class="input-select-wrc required" name="permohonan" required="required">
						<option value="" selected="selected" >------------Pilih salah satu-----------</option>
						<?php
							foreach ($jenis_permohonan as $row =>$index){
								echo "<option value=".$row.">".$index."</option>";
							}
						?>
						</select>
					</div>
				</div>
				<div id="statusRail">
                    <div id="leftRail" class="bg-grid">
                    <?php
                        echo form_label('Format Kode','format');
                    ?>
                    </div>
                    <div id="rightRail" class="bg-grid">
                    <?php
						$kategori_input = array(
                            'name' => 'format',
                            'value' => "",
                            'class' => 'input-wrc required',
							'required' => 'required'
                        );
                        echo form_input($kategori_input);
                    ?>
                    </div>
				</div>
			</fieldset>
			
			<p style="padding-left: 200px">
            <?php
            $add_koefisien = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($add_koefisien);
            echo "<span></span>";
            $cancel_koefisien = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('pernomoran/formatpenomoran')
            );
            echo form_button($cancel_koefisien);
            echo form_close();
            ?>
            </p>
			<?php echo form_close(); ?>
		</div>
	</div>
	<br style="clear: both;" />
</div>