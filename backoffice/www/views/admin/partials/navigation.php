<div id="navigation">
<!--    <ul id="nav" class="dropdown dropdown-horizontal">-->
<!--        <li><a href="--><?php //echo site_url(); ?><!--">Home</a></li>-->
<!--        --><?php
//            if($this->session->userdata('Instalator')) {
//                echo $this->menu_loader->install();
//            } else {
//                echo $this->menu_loader->create_menu($this->session_info['app_list_auth']);
//            }
        ?>
        <!-- <li><a href="<?php echo site_url('pengguna/password/'.$this->session_info['realname']); ?>"><?php echo "Ganti Password"; ?></a></li> -->
<!--        <li><a href="--><?php //echo site_url('login/logoff'); ?><!--">--><?php //echo "Logoff sebagai " . $this->session_info['realname']; ?><!--</a></li>-->
<!--    </ul>-->
<!--    --><?php //echo "<pre>";print_r($this->acl->perms);exit();?>
    <?php echo $this->menu_loader->getMenu($this->session_info['realname'], $this->acl->perms);?>
</div>