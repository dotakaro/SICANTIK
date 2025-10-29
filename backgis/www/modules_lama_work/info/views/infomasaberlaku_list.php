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
                    'sAjaxSource'    : '<?php echo base_url(); ?>info/infomasaberlaku/list_data',
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
                        <th>Pemohon</th>
                        <th>Jenis Permohonan</th>
                        <th>Masa Berlaku</th>
                        <th>Status</th>
                        <th>-</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8" align="center">Loading data from server.</td>
                    </tr>
                </tbody>
            </table>
        </div>
<!--        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="masaberlakuinfo">
                <thead>
                    <tr>
                        <th width="2%">No</th>
                        <th width="15%">No Pendaftaran</th>
                        <th width="29%">Jenis Izin</th>
                        <th width="20%">Pemohon</th>
                        <th width="10%">Jenis Permohonan</th>
                        <th width="18%">Masa Berlaku</th>
                        <th width="6%">SMS<br>(< 1 bln)</th>
                    </tr>
                </thead>
                <tbody>-->
                <?php
//                    $i = 0;
//                    foreach ($list as $data){
//                        $i++;
//                        $data->trperizinan->get();
//                        $data->tmpemohon->get();
//                        $data->trjenis_permohonan->get();
                ?>
<!--                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->pendaftaran_id; ?></td>
                        <td><?php echo $data->trperizinan->n_perizinan; ?></td>
                        <td><?php echo $data->tmpemohon->n_pemohon; ?></td>
                        <td><?php echo $data->trjenis_permohonan->n_permohonan; ?></td>
                        <td>
                        <?php
                        if($data->d_berlaku_izin){
                            if($data->d_berlaku_izin != '0000-00-00'){
                                $per_masa = 31 * 86400; //31-> 31 hari, 86400-> 1 hari
                                $tgl_skr = $this->lib_date->get_date_now();
                                $hari_ini = strtotime($tgl_skr);
                                $hari_berlaku = strtotime($data->d_berlaku_izin);
                                $masa_berlaku = $hari_berlaku - $hari_ini;
                                
                                if($masa_berlaku <= 0)
                                echo "<b style='color: #FF0000;'>".$this->lib_date->mysql_to_human($data->d_berlaku_izin)." (habis)</b>";
                                else if($masa_berlaku <= $per_masa)
                                echo "<b style='color: #FF0000;'>".$this->lib_date->mysql_to_human($data->d_berlaku_izin)." (< 1 bln)</b>";
                                else if($masa_berlaku <= $per_masa * 2)
                                echo "<b style='color: #FF0000;'>".$this->lib_date->mysql_to_human($data->d_berlaku_izin)." (< 2 bln)</b>";
                                else
                                echo $this->lib_date->mysql_to_human($data->d_berlaku_izin);
                            }
                            else echo "SK Belum dibuat";
                        }else echo "SK Belum dibuat";
                        ?>
                        </td>
                        <td>
                            <center>
                                <?php
                                $confirm_text = 'Apakah pemohon akan dikirimi sms konfirmasi?';
                                $img_edit = array(
                                    'src' => 'assets/images/icon/tick.png',
                                    'alt' => 'SMS Konfirmasi',
                                    'title' => 'SMS Konfirmasi',
                                    'border' => '0',
                                    'onClick' => 'return confirm_link(\''.$confirm_text.'\')',
                                );
                                if($data->d_berlaku_izin){
                                    if($masa_berlaku <= $per_masa){
                                        if($data->tmpemohon->telp_pemohon){
                                ?>
                                <a class="page-help" href="<?php echo site_url('info/infomasaberlaku/sms_confirm/'.$data->id) ?>"
                                ><?php echo img($img_edit); ?></a>
                                <?php
                                        }else echo "No telp tdk ada.";
                                    }
                                }
                                ?>
                            </center>
                        </td>
                    </tr>-->
                <?php
//                    }
                ?>
<!--                </tbody>
            </table>
        </div>-->
    </div>
    <br style="clear: both;" />
</div>
