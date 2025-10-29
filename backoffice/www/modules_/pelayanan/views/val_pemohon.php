<script>
function konfirmasi(id)
{
     var nilai=confirm("Ganti data dengan yang baru..?");
     if(nilai == true)
     {
        window.location.href='../replacePemohon/'+id;
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
        window.location.href='../tetapPemohon/'+id;
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
            <legend>Data pemohon online</legend>
                <div id="statusRail">
                            <div id="leftRail" class="bg-grid">
                                <?php
                                echo form_label('Id,Ktp/SIM Pemohon');
                                ?>
                            </div>
                            <div id="rightRail" class="bg-grid">
                                <?php
                                echo "<b>".$no_referB." (".$cmbsourceB.")</b>";
                                ?>
                            </div>
                        </div>
                        
                     <div id="statusRail">
                            <div id="leftRail">
                                <?php
                                echo form_label('Nama Pemohon');
                                ?>
                            </div>
                            <div id="rightRail">
                                <?php
                                echo "<b>".$nama_pemohonB."</b>";
                                ?>
                            </div>
                        </div>  
                        
                    <div id="statusRail">
                            <div id="leftRail" class="bg-grid">
                                <?php
                                echo form_label('No Telp');
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
                                echo form_label('Alamat');
                                ?>
                            </div>
                            <div id="rightRail">
                                <?php
                                echo "<b>".$alamat_pemohonB."</b>";
                                ?>
                            </div>
                        </div>  
                        
                        <div id="statusRail">
                            <div id="leftRail" class="bg-grid">
                                <?php
                                echo form_label('Kelurahan Pemohon');
                                ?>
                            </div>
                            <div id="rightRail" class="bg-grid">
                                <?php
                                echo "<b>".$kelurahan_pemohonB."</b>";
                                ?>
                            </div>
                        </div>
            </fieldset>
        
        
        </div>
<!-- ooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo -->        
         <div class="entry">
            <fieldset id="half">  
            <legend>Data pemohon backoffice</legend>
                <div id="statusRail">
                            <div id="leftRail" class="bg-grid">
                                <?php
                                echo form_label('Id,Ktp/SIM Pemohon');
                                ?>
                            </div>
                            <div id="rightRail" class="bg-grid">
                                <?php
                                echo "<b>".$no_referL." ($cmbsourceL)</b>";
                                ?>
                            </div>
                        </div>
                        
                     <div id="statusRail">
                            <div id="leftRail">
                                <?php
                                echo form_label('Nama Pemohon');
                                ?>
                            </div>
                            <div id="rightRail">
                                <?php
                                echo "<b>".$nama_pemohonL."</b>";
                                ?>
                            </div>
                        </div>  
                        
                    <div id="statusRail">
                            <div id="leftRail" class="bg-grid">
                                <?php
                                echo form_label('No Telp');
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
                                echo form_label('Alamat');
                                ?>
                            </div>
                            <div id="rightRail">
                                <?php
                                echo "<b>".$alamat_pemohonL."</b>";
                                ?>
                            </div>
                        </div>  
                        
                        <div id="statusRail">
                            <div id="leftRail" class="bg-grid">
                                <?php
                                echo form_label('Kelurahan Pemohon');
                                ?>
                            </div>
                            <div id="rightRail" class="bg-grid">
                                <?php
                                echo "<b>".$kelurahan_pemohonL."</b>";
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