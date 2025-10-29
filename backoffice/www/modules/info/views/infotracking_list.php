<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <script type="text/javascript">
            $(document).ready(function()
            {
                $('#info_list').dataTable
                ({
                    'bServerSide'    : true,
                    'bAutoWidth'     : false,
                    'sPaginationType': 'full_numbers',
                    'sAjaxSource'    : '<?php echo base_url(); ?>info/infotracking/datatables_infotracking',
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
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="info_list">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Jenis Izin</th>
                        <th>Lokasi Izin</th>
                        <th>Pemohon</th>
						<th>Nama Perusahaan</th>
                        <th>Jenis Permohonan</th>
                        <th>Status Terakhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8" align="center">Loading data from server.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
