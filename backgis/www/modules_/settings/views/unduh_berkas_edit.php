<style>
.ket
{
     font-size: 9px;
     color: #A3A3A3;
}
.error
{
    color: red;
}
.sukses
{
    color: #00C632;
}


</style>
<div id="content">
    <div class="post">
        <div class="title">     
            <h2><?php echo $page_name; ?></h2>
        </div>

        <div class="entry">
            <?php
            $cek = "checked";
            $attr = array('id' => 'form');
            echo form_open('settings/unduhBerkas/' . $save_method, $attr);
             $service_input = array(
                'name' => 'alamat',
                'value' => $alamat,
                'class' => 'input-wrc required',
                'id' => 'service'
            );
              
               $cancel = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\'' . site_url('') . '\''
            );
            if ($this->session->flashdata('error'))
            {
                echo "<div class='error'>Pesan : ".$this->session->flashdata('error')."<hr></div>";
            }
            else if ($this->session->flashdata('sukses'))
            {
                echo "<div class='sukses'>Pesan : ".$this->session->flashdata('sukses')."<hr></div>";
            }
            ?>

            <table>
                <tr>
                  <td><label class="label-wrc">Alamat App Daerah</label></td>
                  <td><?php echo form_input($service_input); ?></td>
                </tr>
                <tr>
                <td></td>
                <td><?php echo "<div class='ket'>ex. http://[Host Ip Address]/[Path to]/[App Daerah] </div>"; ?></td>
                </tr>
            </table>

              

            <br><br>
              <INPUT TYPE="submit" name="submit" class="submit-wrc" value="Simpan" /> 

           <?php echo form_button($cancel); echo form_close(); ?>
              
              

        </div>

    </div>
    <br style="clear: both;" />
</div>