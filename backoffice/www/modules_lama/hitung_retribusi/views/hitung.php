<?php echo $this->load->view('add_edit_script');?>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <?php
        $attr = array('id' => 'form');
        echo form_open('hitung_retribusi/save', $attr);
        ?>
        <div class="entry">
            <?php
            $id_retribusi = array(
                'name' => 'id',
                'id'=>'retribusi_id',
                'type'=>'hidden',
                'value'=>$retribusi_id
            );
            echo form_input($id_retribusi);

            $id_permohonan = array(
                'name' => 'tmpermohonan_id',
                'id'=>'tmpermohonan_id',
                'type'=>'hidden',
                'value'=>$permohonan_id
            );
            echo form_input($id_permohonan);

            $this->load->model('setting_tarif/setting_tarif_harga');

            echo '<label class="label-wrc">No Pendaftaran</label>';
            echo $data_permohonan->pendaftaran_id;
            echo "<br style='clear:both' />";

            echo '<label class="label-wrc">Nama Izin</label>';
            echo $data_permohonan->trperizinan_n_perizinan;
            echo "<br style='clear:both' />";

            echo '<label class="label-wrc">Nama Pemohon</label>';
            echo $data_permohonan->tmpemohon_n_pemohon;
            echo "<br style='clear:both' />";

            echo '<label class="label-wrc">Jenis Perhitungan</label>';
            echo form_radio('jenis_perhitungan','otomatis',true).'otomatis';
            echo form_radio('jenis_perhitungan','manual',false).'manual';
            echo "<br style='clear:both' />";
            ?>
            <table class="display" style="width:500px;">
                <thead>
                    <th style="width:30%;">Nama Item</th>
					<th style="width:30%;">Tarif</th>
					<th style="width:20%;">Jumlah</th>
					<th style="width:20%;">Subtotal</th>
                </thead>
                <tbody>
                    <?php
                    foreach($list_item_tarif as $key=>$item_tarif){
                        echo '<tr class="item-tarif">';
                        $setting_tarif_item_id = array(
                            'name' => 'RetribusiDetail['.$key.'][setting_tarif_item_id]',
                            'id'=>'setting_tarif_item_id['.$key.']',
                            'type'=>'hidden',
                            'value'=>$item_tarif->id
                        );

                        if($item_tarif->satuan ==''){
                            echo '<td><label class="label-wrc">'.$item_tarif->nama_item.'</label>'.form_input($setting_tarif_item_id).'</td>';
                        }else{
                            echo '<td><label class="label-wrc">'.$item_tarif->nama_item.' / '.$item_tarif->satuan.'</label>'.form_input($setting_tarif_item_id).'</td>';
                        }
                        $setting_tarif_harga = new setting_tarif_harga();
                        $kategori_harga = $setting_tarif_harga->where('setting_tarif_item_id', $item_tarif->id)->get();
                        $num_kategori_harga = $setting_tarif_harga->where('setting_tarif_item_id', $item_tarif->id)->count();
                        $option_kategori = array();

                        if($num_kategori_harga>0){ //Jika ada option, munculkan dropdown
                            $option_kategori['-1'] = 'Pilih salah satu';
                            foreach($kategori_harga as $kategori){
                                $option_kategori[$kategori->harga] = $kategori->kategori.' - '.$kategori->harga;
                            }
                            echo '<td>'.form_dropdown("RetribusiDetail[$key][tarif_kategori]", $option_kategori,null,'id="tarif_kategori['.$key.']" class="input-select-wrc required-option"').'</td>';

                        }else{ //Jika tidak ada option
                            $txt_item_tarif = array(
                                'name' => "RetribusiDetail[$key][tarif_kategori]",
                                'id' => "tarif_kategori[$key]",
                                'class' => 'input-wrc amount',
                                'value' => 0,
                                'placeholder'=>'Tarif',
                            );
                            echo '<td>'.form_input($txt_item_tarif).'</td>';
                        }

                        $jumlah = array(
                            'name' => "RetribusiDetail[$key][jumlah]",
                            'id' => "jumlah[$key]",
                            'class' => 'input-wrc amount',
                            'value' => 1,
                            'placeholder'=>'jumlah',
                            'style'=>'width:50px'
                        );
                        echo '<td>'.form_input($jumlah).'</td>';

                        $subtotal= array(
                            'name' => "RetribusiDetail[$key][subtotal]",
                            'id' => strtolower(remove_whitespace("subtotal_$item_tarif->nama_item")),
                            'class' => 'input-wrc amount',
                            'value' => 0,
                            'placeholder'=>'nilai item',
                        );
                        echo '<td>'.form_input($subtotal).'</td>';

                        //echo "<br style='clear:both' />";
                        echo '</tr>';
                    }

                    echo '<tr>';
                    echo '<td colspan="3"><label class="label-wrc">Nilai Retribusi</label></td>';
                    $nilai_retribusi_input = array(
                        'name' => 'nilai_retribusi',
                        'id' => 'nilai_retribusi',
                        'class' => 'input-wrc required amount',
                        'readonly'=>true
                    );
                    echo '<td>'.form_input($nilai_retribusi_input,set_value('nilai_retribusi',$nilai_retribusi)).'</td>';
                    echo '</tr>';
                    ?>
                </tbody>
            </table>

            <?php

            echo "<br style='clear:both' />";

            $save_form = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan'
            );
            echo form_submit($save_form);
            echo "<span></span>";
            $cancel = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('hitung_retribusi') . '\''
            );
            echo form_button($cancel);

            ?>
        </div>
        <?php echo form_close();?>
    </div>
    <br style="clear: both;" />
</div>
