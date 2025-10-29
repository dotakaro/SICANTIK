<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            /*$addApi = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Tambah API',
                'onclick' => 'parent.location=\''. site_url('property_api/add') . '\''
            );
            echo form_button($addApi);*/
            $btnAcos = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Objek Access Control',
                'onclick' => 'parent.location=\''. site_url('modul_acl/list_aco') . '\''
            );
            echo form_button($btnAcos);

            $btnManagePermission = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Atur Hak Akses Peran',
                'onclick' => 'parent.location=\''. site_url('modul_acl/list_role') . '\''
            );
            echo form_button($btnManagePermission);
            ?>
        </div>
    </div>
    <br style="clear: both;" />
</div>