<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            /*$tambah_button = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Tambah Pemohon',
                'onclick' => 'parent.location=\'' . site_url('pemohon/create') . '\''
            );
            echo form_button($tambah_button);*/
            ?>
            <script type="text/javascript">
                $(document).ready(function()
                {
                    var initDataTable = function() { $('#pemohon').dataTable
                    ({
                        'bServerSide'    : true,
                        'bAutoWidth'     : false,
                        'sPaginationType': 'full_numbers',
                        'sAjaxSource'    : '<?php echo base_url(); ?>sandbox/datatables/getMobileDataTables',
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
                        $('#pemohon').dataTable().fnDestroy();
                    }

                    initDataTable();

                    $('.btn-activate').live('click',function(e){
                        e.preventDefault();
                        var anchorElem = $(this).parent();
                        var url = anchorElem.attr('href');
                        $.ajax({
                            'url':url,
                            'type':'GET',
                            'dataType':'json',
                            'success':function(r){
                                if(r.success == true){
                                    destroyDataTable();
                                    initDataTable();
                                }
                            }
                        });
                    });
                });
            </script>
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="pemohon">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID (SIM/KTP/Passport)</th>
                        <th>Username</th>
                        <th>Status</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
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
