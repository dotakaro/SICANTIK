<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            $btnAddProvinsi = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Tambah Provinsi',
                'onclick' => 'parent.location=\'' . site_url('wilayah/create') . '\''
            );
            echo form_button($btnAddProvinsi);
            ?>
            <script type="text/javascript">
                $(document).ready(function()
                {
                    var initDataTable = function() { $('#list_provinsi').dataTable
                    ({
                        'bServerSide'    : true,
                        'bAutoWidth'     : false,
                        'sPaginationType': 'full_numbers',
                        'sAjaxSource'    : '<?php echo base_url(); ?>wilayah/getWilayahDatatables',
                        'aoColumns'      :
                            [
                                {
                                    'bSearchable': false,
                                    'bVisible'   : true,
                                    'bSortable'  : false
                                },
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
                        $('#list_provinsi').dataTable().fnDestroy();
                    }

                    initDataTable();
                });
            </script>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="list_provinsi">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Provinsi</th>
                    <th>Kode Daerah</th>
                    <th width="18%">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="4" class="dataTables_empty">Loading data from server.</td>
                </tr>
                </tbody>

            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
s