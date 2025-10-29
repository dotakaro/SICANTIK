<?php
        $this->tr_instansi = new Tr_instansi();

        $logo = $this->tr_instansi->get_by_id(14);
        $img = array('src'=>'uploads/logo/' . $logo->value,

                     'width'=>'80',
                     'height'=>'80');
   ?>
<html>
    
    <head>
        <title>Administrator Login Form</title>
        <link type='text/css' rel='stylesheet' href="<?php echo site_url('assets/css/'.$app_folder.'/login.css'); ?>" />
    </head>
    <body>
        <div id="stylized">
            
            <div class="loginform">
                <div class="logo"><?php echo img($img); ?><br><br><br>
                <p>
        <?php
            $folder = $this->tr_instansi->get_by_id(9);
            echo "<b>".$folder->value."</b>";
            $app_city = $this->tr_instansi->get_by_id(4);
            $wilayah = new trkabupaten();
            $wilayah->get_by_id($app_city->value);
            echo br(1)."<font size='2'>".$wilayah->n_kabupaten."</font>";
            ?>
        </p>

                </div>
                <?php
                echo form_open('login/validate');
                ?>
                <div class="form">
                    <ul>
                        <li><h2>Username</h2>&nbsp;<input type="text" name="username" id="username" /></li>
                    </ul>
                    <ul>
                        <li><h2>Password</h2>&nbsp;<input type="password" name="password" id="password" /></li>
                    </ul>
                    <ul>
                        <li><button type="submit">Log In</button></li>
                    </ul>
                </div>
                <?php
                echo form_close();
                ?>
            </div>
            <p style="margin-bottom: 50px;margin-left: 40px;"><span style="color: red;font-style: italic;"><?php echo $salah; ?></span></p>
            <div class="footer">
                    <h3>Copyright | <?php echo date('Y') ." ". $app_name ;?></h3>
            </div>            
        </div>
    </body>
</html>