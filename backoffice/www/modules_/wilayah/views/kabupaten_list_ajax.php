<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            $btnAddKabupaten = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Tambah Kabupaten',
                'onclick' => 'parent.location=\'' . site_url('wilayah/kabupaten/create') . '\''
            );
            echo form_button($btnAddKabupaten);
            ?>
            <script type="text/javascript">
                $(document).ready(function()
                {
                    var initDataTable = function() { $('#list_kabupaten').dataTable
                    ({
                        'bServerSide'    : true,
                        'bAutoWidth'     : false,
                        'sPaginationType': 'full_numbers',
                        'sAjaxSource'    : '<?php echo base_url(); ?>wilayah/kabupaten/getKabupatenDatatables',
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
                        $('#list_kabupaten').dataTable().fnDestroy();
                    }

                    initDataTable();
                });
            </script>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="list_kabupaten">
                <thead>
                <tr>
                    <th width="10%">No</th>
                    <th>Nama Kabupaten</th>
                    <th>Nama Provinsi</th>
                    <th>Nama Ibukota</th>
                    <th>Kode Daerah</th>
                    <th width="18%">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="6" class="dataTables_empty">Loading data from server.</td>
                </tr>
                </tbody>

            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
s