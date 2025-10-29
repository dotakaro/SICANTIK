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
                        <th>Status BAP</th>
                        <th>Permohonan Masuk</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                  $i = 0;
                    foreach ($list as $data){
                      
                       $relasi = new tmpermohonan_trperizinan();
                       $permohonan = new tmpermohonan();
                       $bap = new tmbap();
                       $mohonbap = new tmbap_tmpermohonan();
                       $list_a      = $relasi->where('trperizinan_id',$data->id)->get();

                       foreach($list_a as $a){
                           $i++;
                           $list_b = $permohonan->where('id', $a->tmpermohonan_id)->get();
           
                           foreach($list_b as $b)
                           {
                               $b->tmpemohon->get();
                               $b->tmperusahaan->get();
                               $b->tmbap->get();
                ?>
                  
                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td><?php echo $b->pendaftaran_id; ?></td>
                        <td><?php echo $b->tmpemohon->n_pemohon;?></td>
                        <td><?php echo $b->tmperusahaan->n_perusahaan;?></td>
                        <td>
                        <?php 
                            if($b->tmbap->status_bap === '1')
                                {echo "Izin diterbitkan";}
                                  else if($b->tmbap->status_bap === '0')
                                      {echo "Izin ditolak";}
                                             else {echo "Izin dalam proses";}

                      
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
        </div>
    </div>
</div>
