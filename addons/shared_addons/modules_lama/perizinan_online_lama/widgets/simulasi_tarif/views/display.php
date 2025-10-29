<style type="text/css">
    #frm_simulasi_tarif input[type="text"]{
        text-align: right;
    }
    #frm_simulasi_tarif input[type="text"], #frm_simulasi_tarif select{
        width:100%;
        display:inline;
    }

</style>

<div class="block-content">
    <form id="frm_simulasi_tarif" method="post">
        <fieldset>
            <label for="no_pendaftaran">Pilih Jenis Izin</label>
            <select name="simulasi_izin" id="simulasi_izin" style="width:100%">
                <option>Pilih Izin</option>
            <?php
            foreach($list_izin_simulasi as $izin){
                echo '<option value='.$izin['id'].'>'.$izin['jenis_perizinan'].'</option>';
            }
            ;?>
            </select>
            <div id="loading" style="text-align:center;margin-top:10px;display: none;">
                {{ theme:image file="loading.gif" alt="Please Wait..." }}
            </div>
            <div id="simulasi_form" style="margin-top:10px;"></div>
        <fieldset>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function(){
       $('#simulasi_izin').change(function(){
          var trperizinan_id = $(this).val();
           $.ajax({
              url : '<?php echo base_url();?>perizinan_online/get_item_retribusi',
              type: 'POST',
              data: {
                  'trperizinan_id' : trperizinan_id
              },
              beforeSend:function(){
                  $('#loading').fadeIn(500);
                  $('#simulasi_form').html('');
              },
              success:function(r){
                  $('#simulasi_form').html(r);
              },
              error:function (jqXHR, textStatus, errorThrown){
                  alert(errorThrown);
              },
              complete:function(){
                  $('#loading').fadeOut(500);
              }
           });
       });
    });
</script>