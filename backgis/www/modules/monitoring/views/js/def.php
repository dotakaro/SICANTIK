<script>   
$(document).ready(function() {
         $('.monbulan').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });                   
                 $('#propinsi_pemohon_id').change(function(){
                                        $.post('<?php echo  base_url() ;?>pelayanan/pendaftaran/kabupaten_pemohon', { propinsi_id: $('#propinsi_pemohon_id').val() },
                                                       function(data) {
                                                         $('#show_kabupaten_pemohon').html(data);
                                                         $('#show_kecamatan_pemohon').html('Data Tidak tersedia');
                                                         $('#show_kelurahan_pemohon').html('Data Tidak tersedia');
                                                       });
                                         });     
            });           
</script>