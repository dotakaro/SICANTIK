
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <div class="entry">
            <?php
            $attr = array('id' => 'form');
            echo form_open('unitkerja/' . $save_method,$attr);
//            echo form_hidden('id', $id);
            echo form_input(array('name'=>'id','type'=>'hidden','id'=>'id','value'=>$id));
            ?>
            <label class="label-wrc">Unit Kerja</label>
            <?php
            $n_unitkerja_input = array(
                'name' => 'n_unitkerja',
                'value' => $n_unitkerja,
                'class' => 'input-wrc required',
                'style'=>'width:400px;'
            );
            echo form_input($n_unitkerja_input);
            ?>
            <br style="clear: both" />

<!--            <label class="label-wrc">Institusi Daerah</label>-->
            <?php
            /*$checked = false;
            if($flag_institusi_daerah == 1){
                $checked = true;
            }
            echo form_hidden('flag_institusi_daerah', 0);
            echo form_checkbox('flag_institusi_daerah', 1, $checked);*/
            ?>
<!--            <br style="clear: both" />-->

            <label class="label-wrc">Nama Daerah</label>
            <?php
            $kode_daerah_input = array(
                'name' => 'kode_daerah_text',
                'id' => 'kode_daerah_text',
                'value' => $kode_daerah_text,
                'class' => 'input-wrc',
                'style'=>'width:400px;'
            );
            echo form_input($kode_daerah_input);

            $hiddenKodeDaerah = array(
                'name' => 'kode_daerah',
                'id' => 'kode_daerah',
                'value' => $kode_daerah,
                'type'=>'hidden'
            );
            echo form_input($hiddenKodeDaerah);

//            echo form_dropdown('kode_daerah', $listKodeDaerah, $kode_daerah, 'id="kode_daerah" class = "input-select-wrc required" style="width:400px;"');
            ?>
            <br style="clear: both" />

            <label>&nbsp;</label>
             <?php
            $add_role = array(
                'name' => 'submit',
                'class' => 'submit-wrc',
                'content' => 'Simpan',
                'type' => 'submit',
                'value' => 'Simpan',
            );
             if($id){
                $add_role['disabled']='disabled';
             }
            echo form_submit($add_role);
            echo "<span></span>";
            $cancel_role = array(
                'name' => 'button',
                'class' => 'button-wrc',
                'content' => 'Batal',
                'onclick' => 'parent.location=\''. site_url('unitkerja') . '\''
            );
            echo form_button($cancel_role);
            echo form_close();
            ?>

            <div class="spacer"></div>
           
        </div>
    </div>
    <br style="clear: both;" />
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var webroot = '<?php echo base_url();?>';
        $('#form').validate();
        $( '#kode_daerah_text' ).combogrid({
            searchButton:false,
            width:'600px',
            colModel: [
                {'columnName':'kode_daerah','width':'35','label':'Kode Daerah','align':'left'},
                {'columnName':'n_daerah','width':'65','label':'Nama Daerah','align':'left'}
            ],
            url:webroot+'unitkerja/combogrid_daerah',
            select: function( event, ui ) {
                $( '#kode_daerah' ).val( ui.item.kode_daerah);
                $( '#kode_daerah_text' ).val( ui.item.n_daerah);
                $.ajax({
                    url: webroot + "unitkerja/register_kodedaerah_exist",
                    type:"post",
                    beforeSend:function(){
                        $('input[type="submit"]').attr('disabled','disabled');
                    },
                    data:{
                        id: function(){
                            return $("#id").val();
                        },
                        kode_daerah: function(){
                            return $("#kode_daerah").val();
                        }
                    },
                    dataType:'json',
                    success:function(r){
                        if(r == true){
                            $('#kode_daerah_text').val('');
                            $('#kode_daerah').val('');
                            alert('Kode Daerah sudah digunakan');
                        }else{
                            $('input[type="submit"]').removeAttr('disabled');
                        }
                    }
                })
                return false;
            }
        });

        $('#kode_daerah_text').change(function(){//Jika menghapus kode daerah text, hapus hidden valuenya
            if($('#kode_daerah_text').val() == ''){
                $('#kode_daerah').val('');
            }
        });

        //Validasi apakah kode unit kerja sudah digunakan
        $("#kode_daerah_text").validate({
            onfocusout: true,
            rules:{
                npwp:{
                    remote:{
                        url: site + "unitkerja/register_kodedaerah_exist",
                        type:"post",
                        data:{
                            id: function(){
                                return $("#kode_daerah").val();
                            },
                            kode_daerah: function(){
                                return $("#kode_daerah").val();
                            }
                        }
                    }
                }
            },
            messages:{
                npwp:{
                    remote:'Kode Daerah sudah digunakan!'
                }
            }
        });
    });
</script>
