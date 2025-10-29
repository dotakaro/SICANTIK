<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            $btnAddKelurahan = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Tambah Kelurahan',
                'onclick' => 'parent.location=\'' . site_url('wilayah/kelurahan/create') . '\''
            );
            echo form_button($btnAddKelurahan);
            ?>
            <script type="text/javascript">
                $(document).ready(function()
                {
                    var initDataTable = function() { $('#list_kelurahan').dataTable
                    ({
                        'bServerSide'    : true,
                        'bAutoWidth'     : false,
                        'sPaginationType': 'full_numbers',
                        'sAjaxSource'    : '<?php echo base_url(); ?>wilayah/kelurahan/getKelurahanDatatables',
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
                    })};

                    var destroyDataTable = function(){
                        $('#list_kelurahan').dataTable().fnDestroy();
                    }

                    initDataTable();
                });
            </script>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="list_kelurahan">
                <thead>
                <tr>
                    <th width="10%">No</th>
                    <th>Nama Kelurahan</th>
                    <th>Nama Kecamatan</th>
                    <th>Nama Kabupaten</th>
                    <th>Nama Provinsi</th>
                    <th>Kode Daerah</th>
                    <th width="70">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="7" class="dataTables_empty">Loading data from server.</td>
                </tr>
                </tbody>

            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
s