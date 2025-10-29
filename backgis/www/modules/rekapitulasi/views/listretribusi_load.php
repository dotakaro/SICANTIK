<script language="javascript" type="text/javascript">
    function popup_link(site, targetDiv){
        $.ajax({url: site,success: function(response){$(targetDiv).html(response);}, dataType: "html"});
    }

    $(document).ready(function() {
        oTable = $('#pendaftargrid').dataTable({
                "bJQueryUI": true,
                "bDestroy": true,
                "sPaginationType": "full_numbers"
        });

    } );
</script>
<div id="content" style="width: 800px;">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
           
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="pendaftargrid">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Pendaftaran ID</th>
                        <th>Nama</th>
                        <th>Perusahaan</th>
                      
                        <th>Status Retribusi</th>
                        <th>Permohonan Masuk</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = 0;
                    foreach ($list as $data){
                       $relasi = new tmpermohonan_trperizinan();
                       $izinret = new trperizinan_trretribusi();
                       $permohonan = new tmpermohonan();
                       $mohonstatus = new tmpermohonan_trstspermohonan();
                       $mohonbap = new tmbap_tmpermohonan();
                       $list_a      = $relasi->where('trperizinan_id',$data->id)->get();
                       $retribusi = new trretribusi();
                       
                       $izinret->where('trperizinan_id',$data->id)->get();
                       $x = $izinret->trretribusi_id;
                       $retribusi->where('id',$x)->get();
                       $harga = $retribusi->v_retribusi;

                       foreach($list_a as $a){
                           $i++;
                           $list_b = $permohonan->where('id', $a->tmpermohonan_id)->get();
                           $iddaftar = $list_b->id; //8.11.19.20.24
                           $status = $mohonstatus->where('tmpermohonan_id',$iddaftar)->get();
                           foreach($list_b as $b)
                           {
                               $b->tmpemohon->get();
                               $b->tmperusahaan->get();

                               $iddaftar = $b->id; //8.11.19.20.24
                               $status = $mohonstatus->where('tmpermohonan_id',$iddaftar)->get();

                   
                ?>

                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td><?php echo $b->pendaftaran_id; ?></td>
                        <td><?php echo $b->tmpemohon->n_pemohon;?></td>
                        <td><?php echo $b->tmperusahaan->n_perusahaan;?></td>
                       
                        <td>
                        <?php
                            if($status->id)
                            {
                                 if($status->trstspermohonan_id === '14'){

                                        echo "Terbayar";
                                }
                                else
                                {echo "Terhutang";}
                            }


                           

                         ?>
                        </td>
                        <td><?php echo  $this->lib_date->mysql_to_human($b->d_entry)?></td>
                    </tr>
                  <?php
                            }
                       }
                    }
                ?>
                     
                </tbody>
            </table>
            <b>  Harga Retribusi = Rp<?php echo number_format($harga,2,',','.');?> </b>
         
        </div>
    </div>
</div>
