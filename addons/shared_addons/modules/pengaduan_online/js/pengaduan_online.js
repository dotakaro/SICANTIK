jQuery(function($) {
  $(document).ready(function(){
        $("#provinsi").change(function(){
            var selected_label = $(this).find('option:selected').text();
            $(this).next('input[type="hidden"]').val(selected_label);
            
            var id=$(this).val();
            var dataString="";
            $.ajax({
                type: "POST",
                        
                url: site+"pengaduan_online/list_daerah/1/"+id,
                data: dataString,
                cache: false,
                success: function(html){
                    $("#kabupaten").html(html);
                    $("#kecamatan").html("<option value=''>Pilih Kecamatan :</option>");
                    $("#kelurahan").html("<option value=''>Pilih Kelurahan :</option>");
                } 
            });
        });
                
        $("#kabupaten").change(function(){
            var selected_label = $(this).find('option:selected').text();
            $(this).next('input[type="hidden"]').val(selected_label);
            
            var id=$(this).val();
            var dataString="";
            $.ajax({
                type: "POST",   
                url: site+'pengaduan_online/list_daerah/2/'+id,
                data: dataString,
                cache: false,
                success: function(html){
                    $("#kecamatan").html(html);
                    $("#kelurahan").html("<option value=''>Pilih Kelurahan :</option>");
                } 
            });
        });   
                
        $("#kecamatan").change(function(){
            var selected_label = $(this).find('option:selected').text();
            $(this).next('input[type="hidden"]').val(selected_label);
            
            var id=$(this).val();
            var dataString="";
            $.ajax({
                type: "POST",
                url: site+'pengaduan_online/list_daerah/3/'+id,
                data: dataString,
                cache: false,
                success: function(html){
                    $("#kelurahan").html(html);
                } 
            });
        });    
        
        $("#kelurahan").change(function(){
            var selected_label = $(this).find('option:selected').text();
            $(this).next('input[type="hidden"]').val(selected_label);
        });   
  });
});