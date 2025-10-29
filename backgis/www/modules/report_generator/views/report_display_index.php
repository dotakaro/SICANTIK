<style type="text/css">
    .combogrid input[type="text"]{
        width:10px;
    }
    .cg-searchButton{
        margin-top:0px !important;
    }
    #result_display{
        overflow-x: scroll;
    }

</style>
<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name ?></h2>
        </div>

        <div class="entry">
            <div id="tabs">
                <ul>
                    <li><a href="#tabs-1"><b><i>Report Display</i></b></a></li>
<!--                    <li><a href="#tabs-3"><b><i>Hasil</i></b></a></li>-->
                </ul>
                <div id="tabs-1">
                    <fieldset id="half">
                        <?php
                        $attr = array(
                            'class' => 'searchForm',
                            'id' => 'searchForm'
                        );
                        echo form_open("report_generator", $attr);
                        /*$id_report = array(
                            'name' => 'report_generator_id',
                            'id'=>'report_generator_id',
                            'type'=>'hidden'
                        );

                        $report_code = array(
                            'name' => 'report_code',
                            'id' => 'report_code'
                        );*/
                        $btnShow = array(
                            'name' => 'btn_show',
                            'id' => 'btn_show',
                            'class' => 'button-wrc',
                            'type' => 'submit',
                            'disabled'=>true
                        );
                        ?>
                        <table id="t_cari" width="100%">
                            <tbody>
                                <tr>
                                    <td width="15%"> <?php echo form_label('ID Laporan'); ?> </td>
<!--                                    <td width="85%"> --><?php //echo form_input($report_code); ?><!-- </td>-->
                                    <td width="85%"> <?php echo form_dropdown('report_generator_id', $opsiReportCode, '','class = "input-select-wrc required"  style="width:500px" id="opt_report_code"');; ?> </td>
                                </tr>
                                <tr>
                                    <td width="15%">&nbsp;</td>
                                    <td width="85%">
                                        <?php
//                                        echo form_input($id_report);
                                        echo form_button($btnShow,'Tampilkan');
                                        echo form_close();
                                        ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table id="t_filter" width="100%">
                            <tbody>
                            </tbody>
                        </table>
                    </fieldset>
                    <div id="result_display"</div>
                </div>
<!--                <div id="tabs-3">-->
<!--                    <div id="result_display"</div>-->
<!--                </div>-->
        </div>
    </div>
    <br style="clear: both;" />
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var webroot='<?php echo base_url();?>';
        var oTable = '';

        //Fungsi untuk loading filter berdasarkan id report generator yang dipilih
        function loadFilter(reportGeneratorId){
            $.ajax({
                url:webroot+'report_generator/report_display/ajax_load_filter',
                type:'POST',
                dataType:'json',
                data:{
                    'report_generator_id' : reportGeneratorId
                },
                beforeSend:function(){
                    $("#content").loadOverStart();
                },
                complete:function(){
                    $("#content").loadOverStop();
                },
                success:function(ret){
                    var htmlFilter = '';
                    $('#t_cari tbody tr.report-filter').remove();
                    if(ret.rows.length > 0){
                        htmlFilter += '<tr class="report-filter"><td colspan="2">&nbsp;</td></tr>';
                        htmlFilter += '<tr class="report-filter"><td colspan="2"><strong>Report Filter<strong> : </td></tr>';
                        $.each(ret.rows, function(key, val){
                            htmlFilter += '<tr class="report-filter">';
                            htmlFilter += '<td style="15%">'+val.filter_name+'</td>';
                            htmlFilter += '<td style="85%">';
                            var filterVar = val.filter_variable.replace(/[$]+/g,'');//Replace dulu $ agar bisa diextract di PHP
                            switch(val.filter_type){
                                case 'date':
                                    htmlFilter += '<input type="text" class="filter-datepicker" name="filter['+filterVar+']">';
                                    break;
                                case 'dropdown'://Jika tipenya dropdown, Susun input select html
                                case 'single_dropdown'://Jika tipenya dropdown, Susun input select html
                                case 'multi_dropdown'://Jika tipenya dropdown, Susun input select html
                                    var dropdownHtml = '';
                                    var filterList = val.filter_result;
                                    if(filterList.length > 0){//Jika filter ada isinya
                                        if(val.filter_type == 'single_dropdown'){
                                            dropdownHtml += '<select name="filter['+filterVar+']" size="'+filterList.length+'" class="filter-single-dropdown">';
                                        }else{
                                            dropdownHtml += '<select name="filter['+filterVar+'][]" multiple="multiple" size="'+filterList.length+'" class="filter-dropdown">';
                                        }
//                                        dropdownHtml += '<option value="null">-pilih salah satu</option>';
                                        $.each(filterList, function (indexList, listData){
                                            dropdownHtml += '<option value="';

                                            //Field pertama diambil sebagai value, field kedua dst sebagai label
                                            var indexField = 0;
                                            $.each(listData, function (optField, optValue){
                                                switch(indexField){
                                                    case 0:
                                                        dropdownHtml += optValue+'">';
                                                        break;
                                                    case 1:
                                                        dropdownHtml += optValue;
                                                        break;
                                                    default:
                                                        dropdownHtml += ' - '+optValue;
                                                        break;
                                                }
                                                indexField++;
                                            });
                                            dropdownHtml += '</option>';
                                        });
                                        dropdownHtml += '</select>';
                                    }
                                    htmlFilter += dropdownHtml;
                                    break;
                                default:
                                    htmlFilter += '<input type="text" name="filter['+filterVar+']">';
                                    break;
                            }
                            htmlFilter += '</td>';
                            htmlFilter += '</tr>';
                        });
                    }
                    $('#t_cari tbody tr:first').after(htmlFilter);

                    //Inisialisasi datepicker untuk filter bertipe datepicker
                    $(".filter-datepicker").datepicker({
                            changeMonth: true,
                            changeYear: true,
                            dateFormat: 'yy-mm-dd',
                            closeText: 'X'
                    });

                    //Inisialisasi datepicker untuk filter bertipe dropdown
                    var filterDropdownElem = $('.filter-dropdown');
                    filterDropdownElem.multiselect({
                        show:'blind',
                        hide:'blind',
                        multiple: true,
                        header: true,
                        noneSelectedText: 'Pilih',
                        selectedList: 1
                    });
                    filterDropdownElem.multiselectfilter();

                    //Inisialisasi datepicker untuk filter bertipe single dropdown
                    var filterDropdownElem = $('.filter-single-dropdown');
                    filterDropdownElem.multiselect({
                        show:'blind',
                        hide:'blind',
                        multiple: false,
                        header: true,
                        noneSelectedText: 'Pilih',
                        selectedList: 1
                    });
                    filterDropdownElem.multiselectfilter();
                }
            })
        }

        $('#tabs').tabs();

        /*$( "#report_code" ).live('keyup', function(e){
            //Improved with keycode checking to prevent extra typing after select
            var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
            var keyCode = $.ui.keyCode;
            $('#btn_show').attr('disabled','disabled');
        });
f
        $( "#report_code" ).combogrid({
            width:'600px',
            colModel: [{'columnName':'id','hidden':true,'label':'ID'},
                {'columnName':'report_code','width':'35','label':'ID Laporan','align':'left'},
                {'columnName':'short_desc','width':'65','label':'Deskripsi Singkat','align':'left'}],
            url:webroot+'report_generator/combo_grid_report_display',
            //"select item" event handler to set input fields
            select: function( event, ui ) {
                $( "#report_code" ).val( ui.item.report_code);
                $( "#report_generator_id" ).val( ui.item.id);
                $( "#btn_show" ).removeAttr( 'disabled');
                loadFilter(ui.item.id);//Load Filter
                return false;
            }
        });*/

        $('#opt_report_code').multiselect({
            show:'blind',
            hide:'blind',
            multiple: false,
            header: 'Pilih salah satu',
            noneSelectedText: 'Pilih salah satu',
            selectedList: 1
        }).multiselectfilter();

        $('#opt_report_code').change(function(){
           var reportId = $('#opt_report_code').val();
            if(reportId != undefined && reportId!=''){
                $( "#btn_show" ).removeAttr( 'disabled');
                loadFilter(reportId);//Load Filter
                $('#result_display').html('');
            }
        });

        $("#btn_show").click(function(){
//            var report_generator_id = $( "#report_id" ).val();
            var report_generator_id = $( "#opt_report_code" ).val();
            console.log(report_generator_id);
                $.ajax({
                    url:webroot+'report_generator/report_display/show',
                    type:'POST',
                    data:$('form#searchForm').serializeArray(),
                    beforeSend:function(){
                        $("#content").loadOverStart();
                    },
                    complete:function(){
                        $("#content").loadOverStop();
                    },
                    success:function(ret){
                        $('#result_display').html(ret);
                        oTable = $('#tbl_result').dataTable({
                            'bJQueryUI': true,
                            'sPaginationType': 'full_numbers'
                        });
                    }
                });
//            }
            return false;
        });
    });
</script>	