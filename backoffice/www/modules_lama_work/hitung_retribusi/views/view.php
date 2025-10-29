<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <?php
        //$attr = array('id' => 'form');
        //echo form_open('hitung_retribusi/save', $attr);
        ?>
        <div class="entry">
            <?php
            $id_retribusi = array(
                'name' => 'id',
                'id'=>'retribusi_id',
                'type'=>'hidden',
                'value'=>$retribusi->id
            );
            echo form_input($id_retribusi);

            $id_permohonan = array(
                'name' => 'tmpermohonan_id',
                'id'=>'tmpermohonan_id',
                'type'=>'hidden',
                'value'=>$retribusi->tmpermohonan_id
            );
            echo form_input($id_permohonan);
            $this->load->model('setting_tarif/setting_tarif_item');
            ?>
            <table>
                <thead>
                    <th style="width:150px;">Nama Item</th>
                    <th style="width:150px;">Tarif</th>
                    <th style="width:150px;">Jumlah</th>
                    <th style="width:150px;">Subtotal</th>
                </thead>
                <tbody>
                    <?php
                    $setting_tarif_item = new setting_tarif_item();
                    foreach($retribusi->retribusi_detail as $key=>$detail){
                        $item_retribusi = $setting_tarif_item->get_by_id($detail->setting_tarif_item_id);
                        echo '<tr>';
                        echo '<td>'.$item_retribusi->nama_item.'</td>';
                        echo '<td style="text-align:right;">'.number_format($detail->tarif_kategori,0,',','.').'</td>';
                        echo '<td style="text-align:right;">'.number_format($detail->jumlah,0,',','.').'</td>';
                        echo '<td style="text-align:right;">'.number_format($detail->subtotal,0,',','.').'</td>';
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td colspan="3"><label class="label-wrc">Nilai Retribusi</label></td>';
                    echo '<td style="text-align:right">'.number_format($retribusi->nilai_retribusi,0,',','.').'</td>';
                    echo '</tr>';
                    ?>
                </tbody>
            </table>

            <?php

            echo "<br style='clear:both' />";

            $cancel = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Kembali',
                'onclick' => 'parent.location=\''. site_url('hitung_retribusi') . '\''
            );
            echo form_button($cancel);

            ?>
        </div>
        <?php //echo form_close();?>
    </div>
    <br style="clear: both;" />
</div>
