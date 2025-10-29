

<html>
<head>
<title>Log Activity </title>
</head>
<!--<body onLoad="window.print()">-->
<body>
    <div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
<form name="form1" method="post">
   
     <fieldset>
         <legend style="color: #045000" align="bottom">
             <?php
            echo 'Aktivitas User Periode '. $this->lib_date->mysql_to_human($tgla)." - ".$this->lib_date->mysql_to_human($tglb);
            ?>
          </legend>
 <table align=left>
        <tr>
            <td align="center">
           <?php
                    $Back_data = array(
                   'src' => base_url().'assets/images/icon/back_alt.png',
                    'alt' => 'Lihat di HTML to Openoffice',
                    'title' => 'Kembali',
                    'onclick' => 'parent.location=\''. site_url('log'). '\''
                    );
                    echo img($Back_data);
            ?>
           
            </td>
        </tr>
    </table>
   <table align="center" width="800" border="1" class="display" cellpadding="1" cellspacing="0" id="rev">
        <tr class="title">
            <td align="center"><font  size="1" color="#1A1A1A"><b>No</b></font></td>
            <td align="center"><font  size="1" color="#1A1A1A"><b>User</b></font></td>
            <td align="center"><font  size="1" color="#1A1A1A"><b>Module</b></font></td>
            <td align="center"><font  size="1" color="#1A1A1A" ><b>Aksi</b></font></td>
            <td align="center"><font  size="1" color="#1A1A1A"><b>Tanggal</b></font></td>
           
    </tr>
    <tr>
<!---------------------------------------------------------------------------------------------------.-->
             <?php
              $i = NULL;
                         
              if (empty($user))
              {
                echo "<script>alert('Data Tidak Ditemukan');
                   window.location= ('./');</script>";
              }
                        foreach ($user as $dt)
                            {
                          $i++;
                        ?>
                    <tr>
                        
                        <td align="center"><?php echo $i; ?></td>
                        <td ><?php echo $dt->users; ?></td>

                        <td><?php echo $dt->module; ?></td>
                        <td ><?php echo $dt->action_type; ?></td>
                        <td><?php
                        $pecah = explode ("-",$dt->action_date);
                        $jam_ex = explode(" ",$pecah[2]);
                        echo ($jam_ex[0].'-'.$pecah[1].'-'.$pecah[0].' '.$jam_ex[1]);?></td>
                        

                        
                    </tr><?php } ?>
               
<!---------------------------------------------------------------------------------------------------.-->
      </tr>
</table>
         </fieldset>
    
</form>
        </div>
        </div>
</body>
</html>
