<section class="title">
    <!-- We'll use $this->method to switch between portal_theme.create & portal_theme.edit -->
    <h4><?php echo lang('portal_theme:' . $this->method); ?></h4>
</section>

<section class="item">
    <div class="content">
        <?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>

        <div class="form_inputs">

            <ul class="fields">
                <!--<li>
                    <label for="nama_portal">Nama Portal</label>

                    <div class="input">
                        <?php /*echo form_input("nama_portal", set_value("nama_portal", $nama_portal)); */?>
                    </div>
                </li>-->
                <li>
                    <label for="nama_instansi">Nama Instansi</label>

                    <div class="input">
                        <?php echo form_input("nama_instansi", set_value("nama_instansi", $nama_instansi)); ?>
                    </div>
                </li>
                <li>
                    <label for="jabatan">Warna Dasar</label>

                    <div class="input">
                        <?php echo form_input("warna_dasar", set_value("warna_dasar", $warna_dasar)); ?>
                    </div>
                </li>
                <li>
                    <label for="fileinput">Logo Portal (360 x 80 pixel)
                        <?php if (isset($logo_portal)): ?>
                            <small>File sekarang: <?php echo img('files/thumb/' . $logo_portal . '/360x80/fit'); ?></small>
                        <?php endif; ?>
                    </label>

                    <div class="input"><?php echo form_upload('logo_portal', NULL, 'class="width-15"'); ?></div>
                </li>
                <li>
                    <label for="fileinput">Logo Instansi (120 x 150 pixel)
                        <?php if (isset($logo_instansi)): ?>
                            <small>File sekarang: <?php echo img('files/thumb/' . $logo_instansi . '/120x150/fit'); ?></small>
                        <?php endif; ?>
                    </label>

                    <div class="input"><?php echo form_upload('logo_instansi', NULL, 'class="width-15"'); ?></div>
                </li>
                <li>
                    <label for="fileinput">Logo Footer (126 x 36 pixel)
                        <?php if (isset($logo_footer)): ?>
                            <small>File sekarang: <?php echo img('files/thumb/' . $logo_footer. '/126x36/fit'); ?></small>
                        <?php endif; ?>
                    </label>

                    <div class="input"><?php echo form_upload('logo_footer', NULL, 'class="width-15"'); ?></div>
                </li>
            </ul>

        </div>

        <div class="buttons">
            <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save'))); ?>
        </div>

        <?php echo form_close(); ?>
    </div>
</section>