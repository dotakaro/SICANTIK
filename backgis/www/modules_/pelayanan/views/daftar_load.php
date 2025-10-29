<script language="javascript" type="text/javascript">
    function popup_link(site, targetDiv){
        $.ajax({url: site,success: function(response){$(targetDiv).html(response);}, dataType: "html"});
    }

    $(document).ready(function() {
        oTable = $('#daftargrid').dataTable({
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
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="daftargrid">
                <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th width="25%">No Pendaftaran</th>
                        <th width="20%">Pemohon</th>
                        <th width="33%">Jenis Izin</th>
                        <th width="10%">Tanggal Permohonan</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = 0;
                    $results = mysql_query($list);
                    while ($rows = mysql_fetch_assoc(@$results)){
                        $i++;
                        ?>
                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td><?php echo $rows['pendaftaran_id'];?></td>
                        <td><?php echo $rows['n_pemohon'];?></td>
                        <td><?php echo $rows['n_perizinan'];?></td>
                        <td><?php
                        if($rows['d_terima_berkas']){
                            if($rows['d_terima_berkas'] != '0000-00-00') echo $this->lib_date->mysql_to_human($rows['d_terima_berkas']);
                        } ?>
                        </td>
                        <td align="center">
                            <a href="javascript:popup_link('<?php echo base_url();?>pelayanan/pendaftaran/pick_daftar_data/<?php echo $rows['id'];?>', '#tabs-1')">
                            <img src="<?php echo base_url();?>assets/images/icon/navigation-down.png" border="0" alt="Pilih Pemohon" /></a>
                        </td>
                    </tr>
                <?php
                    }
//                    foreach ($list as $data){
//                        $i++;
//                        $data->trperizinan->get();
//                        $data->tmpemohon->get();
                ?>
<!--                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td><?php echo $data->pendaftaran_id; ?></td>
                        <td><?php echo $data->tmpemohon->n_pemohon; ?></td>
                        <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                        <td><?php
                        if($data->d_terima_berkas){
                            if($data->d_terima_berkas != '0000-00-00') echo $this->lib_date->mysql_to_human($data->d_terima_berkas);
                        } ?></td>
                        <td align="center">
                            <a href="javascript:popup_link('<?php echo base_url();?>pelayanan/pendaftaran/pick_daftar_data/<?php echo $data->id;?>', '#tabs-1')">
                            <img src="<?php echo base_url();?>assets/images/icon/navigation-down.png" border="0" alt="Pilih Pemohon" /></a>
                        </td>
                    </tr>-->
                <?php
//                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
