<script language="javascript" type="text/javascript">
    function popup_link(site, targetDiv){
        $.ajax({url: site,success: function(response){$(targetDiv).html(response);}, dataType: "html"});
    }

    $(document).ready(function() {
        oTable = $('#perusahaangrid').dataTable({
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
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="perusahaangrid">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Perusahaan</th>
                        <th>NPWP</th>
                        <th>Alamat Perusahaan</th>
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
                        <td><?php echo $data->n_perusahaan; ?></td>
                        <td><?php echo $data->npwp; ?></td>
                        <td><?php echo $data->a_perusahaan; ?></td>
                        <td align="center">
                            <a href="javascript:popup_link('<?php echo base_url();?>pelayanan/pendaftaran/pick_perusahaan_data/<?php echo $data->id;?>', '#tabs-2')">
                            <img src="<?php echo base_url();?>assets/images/icon/navigation-down.png" border="0" alt="Pilih Perusahaan" /></a>
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
