<script>
function konfirmasi(id)
{
     var nilai=confirm("Ganti data dengan yang baru..?");
     if(nilai == true)
     {
        window.location.href='../replacePerusahaan/'+id;
        return true;
     }
     else
     {
        return false;
     }
}

function konfirmasiBatal(id)
{
     var nilai=confirm("Tetap gunakan data yang lama..?");
     if(nilai == true)
     {
        window.location.href='../tetapPerusahaan/'+id;
        return true;
     }
     else
     {
        return false;
     }
}

</script>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <fieldset id="half">  
            <legend>Data perusahaan online</legend>
                <div id="statusRail">
                            <div id="leftRail" class="bg-grid">
                                <?php
                                echo form_label('No registrasi');
                                ?>
                            </div>
                            <div id="rightRail" class="bg-grid">
                                <?php
                                echo "<b>".$registrasiB."</b>";
                                ?>
                            </div>
                        </div>
                        
                        <div id="statusRail">
                            <div id="leftRail" class="bg-grid">
                                <?php
                                echo form_label('Npwp');
                                ?>
                            </div>
                            <div id="rightRail" class="bg-grid">
                                <?php
                                echo "<b>".$npwpB."</b>";
                                ?>
                            </div>
                        </div>
                        
                     <div id="statusRail">
                            <div id="leftRail">
                                <?php
                                echo form_label('Nama perusahaan');
                                ?>
                            </div>
                            <div id="rightRail">
                                <?php
                                echo "<b>".$n_perusahaanB."</b>";
                                ?>
                            </div>
                        </div>  
                        
                    <div id="statusRail">
                            <div id="leftRail" class="bg-grid">
                                <?php
                                echo form_label('Telp perusahaan');
                                ?>
                            </div>
                            <div id="rightRail" class="bg-grid">
                                <?php
                                echo "<b>".$no_telpB."</b>";
                                ?>
                            </div>
                        </div>
                        
                         <div id="statusRail">
                            <div id="leftRail">
                                <?php
                                echo form_label('Alamat perusahaan');
                                ?>
                            </div>
                            <div id="rightRail">
                                <?php
                                echo "<b>".$alamat_perusahaanB."</b>";
                                ?>
                            </div>
                        </div>  
                        
                        <div id="statusRail">
                            <div id="leftRail" class="bg-grid">
                                <?php
                                echo form_label('Kelurahan');
                                ?>
                            </div>
                            <div id="rightRail" class="bg-grid">
                                <?php
                                echo "<b>".$kelurahan_perusahaanB."</b>";
                                ?>
                            </div>
                        </div>
            </fieldset>
        
        
        </div>
<!-- ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo -->        
         <div class="entry">
            <fieldset id="half">  
            <legend>Data perusahaan backoffice</legend>
                <div id="statusRail">
                            <div id="leftRail" class="bg-grid">
                                <?php
                                echo form_label('No registrasi');
                                ?>
                            </div>
                            <div id="rightRail" class="bg-grid">
                                <?php
                                echo "<b>".$registrasiL."</b>";
                                ?>
                            </div>
                        </div>

                    <div id="statusRail">
                            <div id="leftRail" class="bg-grid">
                                <?php
                                echo form_label('Npwp');
                                ?>
                            </div>
                            <div id="rightRail" class="bg-grid">
                                <?php
                                echo "<b>".$npwpL."</b>";
                                ?>
                            </div>
                        </div>
                        
                     <div id="statusRail">
                            <div id="leftRail">
                                <?php
                                echo form_label('Nama perusahaan');
                                ?>
                            </div>
                            <div id="rightRail">
                                <?php
                                echo "<b>".$n_perusahaanL."</b>";
                                ?>
                            </div>
                        </div>  
                        
                    <div id="statusRail">
                            <div id="leftRail" class="bg-grid">
                                <?php
                                echo form_label('Telp perusahaan');
                                ?>
                            </div>
                            <div id="rightRail" class="bg-grid">
                                <?php
                                echo "<b>".$no_telpL."</b>";
                                ?>
                            </div>
                        </div>
                        
                         <div id="statusRail">
                            <div id="leftRail">
                                <?php
                                echo form_label('Alamat perusahaan');
                                ?>
                            </div>
                            <div id="rightRail">
                                <?php
                                echo "<b>".$alamat_perusahaanL."</b>";
                                ?>
                            </div>
                        </div>  
                        
                        <div id="statusRail">
                            <div id="leftRail" class="bg-grid">
                                <?php
                                echo form_label('Kelurahan');
                                ?>
                            </div>
                            <div id="rightRail" class="bg-grid">
                                <?php
                                echo "<b>".$kelurahan_perusahaanL."</b>";
                                ?>
                            </div>
                        </div>
            </fieldset>
            <br />
         <?php 
         $Ganti_data = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Gunakan data Online',
                'onclick' => 'return konfirmasi(\'' . $id_daftar . '\')'
            );
            echo form_button($Ganti_data);
            
             $Batal = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Gunakan backoffice',
                'onclick' => 'return konfirmasiBatal(\'' . $id_daftar . '\')'
            );
            echo form_button($Batal);
         
         ?>
        </div>
        
        
    </div>
</div>