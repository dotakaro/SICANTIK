<div id="content" style="width: 1024px;">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <script type="text/javascript">
            $(document).ready(function()
            {
                $('#pendaftar_list').dataTable
                ({
                    'bServerSide'    : true,
                    'bAutoWidth'     : false,
                    'sPaginationType': 'full_numbers',
                    'sAjaxSource'    : '<?php echo base_url(); ?>pelayanan/pendaftaran/get_data_no_surat_lama_other/<?php echo $id_jenis ?>',
                    'aoColumns'      :
                        [
                        {
                            'bSearchable': false,
                            'bVisible'   : true,
                            'bSortable'  : false
                        },
                        null,
                        null,
                        null,
                        null,
                        null,
                        {
                            'bSearchable': false,
                            'bVisible'   : true,
                            'bSortable'  : false
                        }
                    ],
                    'fnServerData': function(sSource, aoData, fnCallback)
                    {
                        $.ajax
                        ({
                            'dataType': 'json',
                            'type'    : 'POST',
                            'url'     : sSource,
                            'data'    : aoData,
                            'success' : fnCallback
                        });
                    }
                });
            });

        </script>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="pendaftar_list">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Surat Lama</th>
                        <th>Nama</th>
                        <th>Nama Perusahaan</th>
                        <th>Jenis Izin</th>
                        <th>Tanggal Berlaku Izin</th>
                        <th>Pilih</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="7">Fetching data from server.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
