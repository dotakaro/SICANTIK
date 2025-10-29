<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="setting_formula">
                <thead>
                <tr>
                    <th style="width:5%;">No</th>
                    <th style="width:20%;">Nama Perizinan</th>
                    <th style="width:15%;">Keterangan</th>
                    <th style="width:30%;">Formula</th>
                    <th style="width:20%;">Dihitung Oleh</th>
                    <th style="width:10%;">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($list as $key=>$data){
                    ?>
                    <tr>
                        <td><?php echo ($key+1);?></td>
                        <td><?php echo $data->n_perizinan; ?></td>
                        <td><?php echo ($data->setting_formula_retribusi->id)?'Sudah disetting':'Belum disetting';?></td>
                        <td><?php echo $data->setting_formula_retribusi->formula;?></td>
                        <td>
                            <?php
                            $list_detail = $data->setting_formula_retribusi->setting_formula_detail->get();
                            $list_unit_kerja = array();
                            foreach($list_detail as $formula_detail){
                                if(is_null($formula_detail->trunitkerja_id) || $formula_detail->trunitkerja_id == 0){
                                    continue;
                                }
                                $list_unit_kerja[] = $formula_detail->trunitkerja->n_unitkerja;
                            }
                            echo implode(' atau ',$list_unit_kerja);
                            ?>
                        </td>
                        <td style="text-align:center;">
                            <?php
                            $img_edit = array(
                                'src' => 'assets/images/icon/property.png',
                                'alt' => 'Edit',
                                'title' => 'Edit',
                                'border' => '0',
                            );

                            if($data->setting_formula_retribusi->id){
                            ?>
                            <a class="page-help" href="<?php echo site_url('setting_formula/edit'."/".$data->setting_formula_retribusi->id); ?>">
                                <?php
                                }else{
                                ?>
                                <a class="page-help" href="<?php echo site_url('setting_formula/add/'.$data->id); ?>">
                                    <?php
                                    }
                                    echo img($img_edit); ?>
                                </a>
                        </td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
