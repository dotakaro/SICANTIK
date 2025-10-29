<script language="javascript" type="text/javascript">
    function popup_link(site, targetDiv){
        $.ajax({url: site,success: function(response){$(targetDiv).html(response);}, dataType: "html"});
    }

    $(document).ready(function() {
        oTable = $('#pemohongrid').dataTable({
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
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="pemohongrid">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID (SIM/KTP/Passport)</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th width="60">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = 0;
                    foreach ($list as $data){
                        $i++;
                ?>
                    <tr>
                        <td align="center"><?php echo $i; ?></td>
                        <td><?php echo $data->no_referensi; ?></td>
                        <td><?php echo $data->n_pemohon; ?></td>
                        <td><?php echo $data->a_pemohon; ?></td>
                        <td align="center">
                            <a href="javascript:popup_link('<?php echo base_url();?>pelayanan/pendaftaran/pick_pemohon_data/<?php echo $data->id;?>', '#tabs-1')">
                            <img src="<?php echo base_url();?>assets/images/icon/navigation-down.png" border="0" alt="Pilih Pemohon" /></a>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
