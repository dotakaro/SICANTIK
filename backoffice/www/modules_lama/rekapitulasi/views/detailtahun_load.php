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
                 <h2 align="center"><?php echo $list->n_perizinan.' '. $this->lib_date->mysql_to_human($tgla)." - ".$this->lib_date->mysql_to_human($tglb);
?></h2>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="reportgrid">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Status Permohonan</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Nama</th>
                        <th>Perusahaan</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = NULL;
                    echo $izin_id;
//                    foreach ($list as $data){
                        $izin = new trperizinan();
                        $izin->get_by_id($izin_id);
                        $permohonan = new tmpermohonan();
                        $data_pendaftaran = $permohonan->where("date(d_entry) between '$tgla' and '$tglb'")->where_related($izin)->get();
//                        $relasi = new tmpermohonan_trperizinan();
//                        $list_relasi = $relasi->where('trperizinan_id',$data->id)->limit(10)->get();
                        foreach ($data_pendaftaran as $row){
                        $i++;
//                        $permohonan = new tmpermohonan;
//                        $listpermohonan = $permohonan
//                                          ->where('id', $row->tmpermohonan_id)
//                                          ->where('d_tahun', $list_tahun->d_tahun)
//                                          ->order_by('id', 'ASC')->get();
                                $row->tmpemohon->get();
                                $row->trstspermohonan->get();
                                $row->tmperusahaan->get();
                ?>
                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td><?php echo $row->pendaftaran_id; ?></td>
                        <td><?php echo $row->trstspermohonan->n_sts_permohonan; ?></td>
                        <td><?php echo $this->lib_date->mysql_to_human($row->d_entry); ?></td>
                        <td><?php echo $row->tmpemohon->n_pemohon; ?></td>
                        <td><?php echo $row->tmperusahaan->n_perusahaan; ?></td>
                    </tr>
                <?php
                }
//            }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
