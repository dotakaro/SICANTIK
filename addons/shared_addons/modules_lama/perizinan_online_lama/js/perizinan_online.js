jQuery(function($) {
  $(document).ready(function(){
      $('#jenis_izin').change(function(){
          var nama_perizinan = $('#jenis_izin option:selected').text();
          $('input[name="nama_perizinan"]').val(nama_perizinan);
      });
      
    $("#provinsi_pemohon").change(function(){
        var selected_label = $(this).find('option:selected').text();
        $(this).next('input[type="hidden"]').val(selected_label);

        var id=$(this).val();
        var dataString="";
        $.ajax({
            type: "POST",
            url: site+"perizinan_online/list_daerah/1/"+id,
            data: dataString,
            cache: false,
            success: function(html){
                $("#kabupaten_pemohon").html(html);
                $("#kecamatan_pemohon").html("<option value=''>Pilih Kecamatan :</option>");
                $("#kelurahan_pemohon").html("<option value=''>Pilih Kelurahan :</option>");
            }
        });
    });

    $("#kabupaten_pemohon").change(function(){
        var selected_label = $(this).find('option:selected').text();
        $(this).next('input[type="hidden"]').val(selected_label);

        var id=$(this).val();
        var dataString="";
        $.ajax({
            type: "POST",
            url: site+'perizinan_online/list_daerah/2/'+id,
            data: dataString,
            cache: false,
            success: function(html){
                $("#kecamatan_pemohon").html(html);
                $("#kelurahan_pemohon").html("<option value=''>Pilih Kelurahan :</option>");
            }
        });
    });

     $("#kecamatan_pemohon").change(function(){
      var selected_label = $(this).find('option:selected').text();
      $(this).next('input[type="hidden"]').val(selected_label);

      var id=$(this).val();
      var dataString="";
      $.ajax({
          type: "POST",
          url: site+'perizinan_online/list_daerah/3/'+id,
          data: dataString,
          cache: false,
          success: function(html){
              $("#kelurahan_pemohon").html(html);
          }
      });
    });

      $("#kelurahan_pemohon").change(function(){
          var selected_label = $(this).find('option:selected').text();
          $(this).next('input[type="hidden"]').val(selected_label);
      });

      $("#provinsi_perusahaan").change(function(){
            var selected_label = $(this).find('option:selected').text();
            $(this).next('input[type="hidden"]').val(selected_label);

            var id=$(this).val();
            var dataString="";
            $.ajax({
                type: "POST",
                        
                url: site+"perizinan_online/list_daerah/1/"+id,
                data: dataString,
                cache: false,
                success: function(html){
                    $("#kabupaten_perusahaan").html(html);
                    $("#kecamatan_perusahaan").html("<option value=''>Pilih Kecamatan :</option>");
                    $("#kelurahan_perusahaan").html("<option value=''>Pilih Kelurahan :</option>");
                } 
            });
        });
                
        $("#kabupaten_perusahaan").change(function(){
            var selected_label = $(this).find('option:selected').text();
            $(this).next('input[type="hidden"]').val(selected_label);

            var id=$(this).val();
            var dataString="";
            $.ajax({
                type: "POST",
                url: site+'perizinan_online/list_daerah/2/'+id,
                data: dataString,
                cache: false,
                success: function(html){
                    $("#kecamatan_perusahaan").html(html);
                    $("#kelurahan_perusahaan").html("<option value=''>Pilih Kelurahan :</option>");
                } 
            });
        });   
                
        $("#kecamatan_perusahaan").change(function(){
            var selected_label = $(this).find('option:selected').text();
            $(this).next('input[type="hidden"]').val(selected_label);

            var id=$(this).val();
            var dataString="";
            $.ajax({
                type: "POST",
                url: site+'perizinan_online/list_daerah/3/'+id,
                data: dataString,
                cache: false,
                success: function(html){
                    $("#kelurahan_perusahaan").html(html);
                } 
            });
        });

      $("#kelurahan_perusahaan").change(function(){
          var selected_label = $(this).find('option:selected').text();
          $(this).next('input[type="hidden"]').val(selected_label);
      });

      $("#jenis_izin").change(function(){
          var jenisIzinId = $(this).val();
          var dataString="";
          var htmlOption = '<option>Pilih Unit Kerja :</option>';
          $.ajax({
              type: "GET",
              url: site+'perizinan_online/get_list_unit/'+jenisIzinId,
              data: dataString,
              dataType:'json',
              cache: false,
              success: function(jsonData){
                  if(jsonData != null){
                      $.each(jsonData, function(key,value){
                        htmlOption += '<option value="'+value.id+'">'+value.n_unitkerja+'</option>';
                      });
                  }
                  $("#unit_kerja_id").html(htmlOption);
              }
          });
      });

      $('#unit_kerja_id').change(function(){
          var selected_label = $(this).find('option:selected').text();
          $(this).next('input[type="hidden"]').val(selected_label);
      });

  });
});