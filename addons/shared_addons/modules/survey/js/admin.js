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


    var keyOption = 'option';
      /**
       * Automate URL slug
       *
       * @source http://stackoverflow.com/questions/1053902/how-to-convert-a-title-to-a-url-slug-in-jquery/1054592#1054592
       */
      $('#description').keyup(function(){
          var text = $(this).val();
          text = text.toLowerCase();
          text = text.replace(/[^a-zA-Z0-9]+/g,'-');
          $("#slug").val(text);
      });

      // Datepicker
      $("#open_date, #close_date").datepicker({ dateFormat: 'yy-mm-dd' });

      var lastIndex = $('table#question_options tbody tr').length;
      //var lastIndex = 0;
      // Add a poll option
      function add_option() {
          var htmlOption = '<tr>';
          htmlOption += '<td><input required type="text" name="survey_option['+lastIndex+'][option_desc]" id="survey_option_'+lastIndex+'_option_desc" /></td>';
          htmlOption += '<td><input required type="number" name="survey_option['+lastIndex+'][weight] id="survey_option_'+lastIndex+'_weight" /></td>';
          htmlOption += '<td><input class="button btn-del-option" type="button" value="Delete"/></td>';
          htmlOption += '</tr>';
          $('table#question_options tbody').append(htmlOption);
          lastIndex++;
      }

      function showOption(){
          $('li.show-on-option').show();
      }

      function hideOption(){
          $('li.show-on-option').hide();
          $('#question_options').html('');
      }

      // If "Add Option" button is clicked
      $('#add_new_option').live('click', function() {
          add_option();
      });

      $('#question_type').change(function(){
         if($(this).val() == keyOption){
             showOption();
         }else{
             hideOption();
         }
      });

      $('.btn-del-option').live('click',function(){
         var btnDel = $(this).parent().parent().remove();
      });

      if($('#question_type').val()==keyOption){
          showOption();
      }else{
          hideOption();
      }

      // If user presses the "enter" key (use "live" because of AJAX DOM replacement)
      $('#new_option_title').live('keyup', function(e) {
          if (e.which == 13) {
              add_option();
          }
      });
  });
});