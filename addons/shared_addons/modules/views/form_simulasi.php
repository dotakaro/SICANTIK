<?php
function remove_whitespace($string){
    return preg_replace("/\s+/", "",$string );
}
?>

<table>
    <thead>
        <th style="width:30%;">Nama Item</th>
        <th style="width:30%;">Tarif</th>
        <th style="width:10%;">Jumlah</th>
        <th style="width:30%;">Subtotal</th>
    </thead>
    <tbody>
        <?php
        foreach($list_item as $key=>$item_tarif){
            $option_kategori = array();
            echo '<tr>';

            if($item_tarif->satuan ==''){
                echo '<td>'.$item_tarif->nama_item.'</td>';
            }else{
                echo '<td>'.$item_tarif->nama_item.' / '.$item_tarif->satuan.'</td>';
            }

            if(!empty($item_tarif->option)){ //Jika ada option, munculkan dropdown
                $option_kategori['-1'] = 'Pilih salah satu';
                foreach($item_tarif->option as $harga=>$kategori){
                    $option_kategori[$harga] = $kategori;
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
                'placeholder'=>'jumlah'
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
            'class' => 'input-wrc required amount'
        );
        echo '<td>'.form_input($nilai_retribusi_input,null).'</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td colspan="4">';
        echo form_reset('btn_reset','Reset','class="button"');
        echo '</td>';
        echo '</tr>';
        ?>
    </tbody>
</table>

<script type="text/javascript">
    function calculate_total(){
        var formula_total = 0;
        <?php echo $formula_retribusi;?>
        if(isNaN(formula_total) || formula_total<0){
            formula_total = 0;
        }
        $('#nilai_retribusi').val(formula_total);
    }

    //Hitung jika ada perubahan pada item tarif (change)
    $('select[id*="tarif_kategori"]').change(function(e){ //Detect perubahan Kategori Dropdown
        var item_value = parseFloat($(this).val());
        var item_qty = parseInt($(this).parent().next().find('[id*="jumlah"]').val());
        var subtotal = item_value * item_qty;

        if(isNaN(subtotal) || subtotal < 0){
            subtotal = 0;
        }
        $(this).parent().next().next().find('[id*="subtotal"]').val(subtotal);
        calculate_total();
    });
    $('input[id*="tarif_kategori"]').keyup(function(e){ //Detect perubahan Kategori Input Manual
        var item_value = parseFloat($(this).val());
        var item_qty = parseInt($(this).parent().next().find('[id*="jumlah"]').val());
        var subtotal = item_value * item_qty;

        if(isNaN(subtotal) || subtotal < 0){
            subtotal = 0;
        }
        $(this).parent().next().next().find('[id*="subtotal"]').val(subtotal);
        calculate_total();
    });
    $('input[id*="jumlah"]').keyup(function(e){ //Detect perubahan pada jumlah
        var item_value = parseFloat($(this).parent().prev().find('[id*="tarif_kategori"]').val());
        var item_qty = parseInt($(this).val());
        var subtotal = item_value * item_qty;

        if(isNaN(subtotal) || subtotal < 0){
            subtotal = 0;
        }
        $(this).parent().next().find('[id*="subtotal"]').val(subtotal);
        calculate_total();
    });
</script>