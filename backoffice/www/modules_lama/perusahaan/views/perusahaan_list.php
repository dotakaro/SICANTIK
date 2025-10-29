<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            $tambah_button = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Tambah Perusahaan',
                'onclick' => 'parent.location=\'' . site_url('perusahaan/create') . '\''
            );
            echo form_button($tambah_button);
            ?>
            <script type="text/javascript">
                $(document).ready(function()
                {
                    $('#perusahaan').dataTable
                    ({
                        'bServerSide'    : true,
                        'bAutoWidth'     : false,
                        'sPaginationType': 'full_numbers',
                        'sAjaxSource'    : '<?php echo base_url(); ?>sandbox/datatables_perusahaan/getDataTables',
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
                            {
                                'bSearchable': false,
                                'bVisible'   : true,
                                'bSortable'  : false
                            }
                        ],
                        'fnServerData'   : function(sSource, aoData, fnCallback)
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
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="perusahaan">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Perusahaan</th>
                        <th>NPWP</th>
                        <th>Alamat Perusahaan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="dataTables_empty">Loading data from server.</td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
