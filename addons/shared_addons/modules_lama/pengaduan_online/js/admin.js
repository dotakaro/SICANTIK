// you need to add the sortable class to the tbody or nothing is going to happen
jQuery(function($) {
  $(function() {
    // retrieve the ids of root pages so we can POST them along
    function data_callback(even, ui) {
      var item_array = $("tbody.ui-sortable-container").sortable("toArray");
      $.post(window.location.href + "/order", {
        items: item_array,
        csrf_hash_name: $.cookie(pyro.csrf_cookie_name)
      });
    }
    $("tbody.ui-sortable-container").sortable({
      opacity: 0.7,
      // placeholder: 'ui-state-highlight',
      forcePlaceholderSize: true,
      items: 'tr',
      cursor: "move",
      scroll: false,
      update: function(event, ui) {
        data_callback();
      }
    }).disableSelection();
  });
});

jQuery(function($) {
  $(document).ready(function(){
        $("#provinsi_pemohon").change(function(){
            var id=$(this).val();
            var dataString="";
            $.ajax({
                type: "POST",
                        
                url: site+"perizinan_online/list_daerah/1/"+id,
                data: dataString,
                cache: false,
                success: function(html){
                    $("#kabupaten_pemohon").html(html).trigger('liszt:updated');
                    $("#kecamatan_pemohon").html("<option value=''>Pilih Kecamatan :</option>").trigger('liszt:updated');
                    $("#kelurahan_pemohon").html("<option value=''>Pilih Kelurahan :</option>").trigger('liszt:updated');
                } 
            });
        });
                
        $("#kabupaten_pemohon").change(function(){
            var id=$(this).val();
            var dataString="";
            $.ajax({
                type: "POST",   
                url: site+'perizinan_online/list_daerah/2/'+id,
                data: dataString,
                cache: false,
                success: function(html){
                    $("#kecamatan_pemohon").html(html).trigger('liszt:updated');
                    $("#kelurahan_pemohon").html("<option value=''>Pilih Kelurahan :</option>").trigger('liszt:updated');
                } 
            });
        });   
                
        $("#kecamatan_pemohon").change(function(){
            var id=$(this).val();
            var dataString="";
            $.ajax({
                type: "POST",
                url: site+'perizinan_online/list_daerah/3/'+id,
                data: dataString,
                cache: false,
                success: function(html){
                    $("#kelurahan_pemohon").html(html).trigger('liszt:updated');
                } 
            });
        });                  
                
        $("#provinsi_perusahaan").change(function(){
            var id=$(this).val();
            var dataString="";
            $.ajax({
                type: "POST",
                        
                url: site+"perizinan_online/list_daerah/1/"+id,
                data: dataString,
                cache: false,
                success: function(html){
                    $("#kabupaten_perusahaan").html(html).trigger('liszt:updated');
                    $("#kecamatan_perusahaan").html("<option value=''>Pilih Kecamatan :</option>").trigger('liszt:updated');
                    $("#kelurahan_perusahaan").html("<option value=''>Pilih Kelurahan :</option>").trigger('liszt:updated');
                } 
            });
        });
                
        $("#kabupaten_perusahaan").change(function(){
            var id=$(this).val();
            var dataString="";
            $.ajax({
                type: "POST",
                url: site+'perizinan_online/list_daerah/2/'+id,
                data: dataString,
                cache: false,
                success: function(html){
                    $("#kecamatan_perusahaan").html(html).trigger('liszt:updated');
                    $("#kelurahan_perusahaan").html("<option value=''>Pilih Kelurahan :</option>").trigger('liszt:updated');
                } 
            });
        });   
                
        $("#kecamatan_perusahaan").change(function(){
            var id=$(this).val();
            var dataString="";
            $.ajax({
                type: "POST",
                url: site+'perizinan_online/list_daerah/3/'+id,
                data: dataString,
                cache: false,
                success: function(html){
                    $("#kelurahan_perusahaan").html(html).trigger('liszt:updated');
                } 
            });
        });   
  });
});