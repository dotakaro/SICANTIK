<script language="javascript" type="text/javascript">
    function popup_link(site, targetDiv){
        $.ajax({url: site,success: function(response){$(targetDiv).html(response);}, dataType: "html"});
    }

    $(document).ready(function() {
        oTable = $('#reportgrid').dataTable({
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
                 <h2 align="center"><?php echo $list->n_perizinan; ?></h2>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="reportgrid">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Id_Pendaftaran</th>
                        <th>Status Permohonan</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Nama</th>
                        <th>Perusahaan</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                     $i = NULL;
                    foreach ($list as $data){
                        $relasi = new tmpermohonan_trperizinan();
                        $permohonan = new tmpermohonan;
                        $list_relasi = $relasi->where('trperizinan_id',$data->id)->get();
                        foreach ($list_relasi as $row){
                        $i++;
                            $listpermohonan = $permohonan->where('id', $row->tmpermohonan_id)->get();
                            foreach ($listpermohonan as $listper){
                                $listper->tmpemohon->get();
                                $listper->trstspermohonan->get();
                                $listper->tmperusahaan->get();
                ?>
                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td><?php echo $listper->pendaftaran_id; ?></td>
                        <td><?php echo $listper->trstspermohonan->n_sts_permohonan; ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($listper->d_entry); ?></td>
                        <td><?php echo $listper->tmpemohon->n_pemohon; ?></td>
                        <td><?php echo $listper->tmperusahaan->n_perusahaan; ?></td>
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
