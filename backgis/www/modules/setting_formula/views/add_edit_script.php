<style type="text/css">
    /*textarea {
        width: 490px !important;
        height: 50px;
        resize: none;
    }

    textarea#query_text {
        width: 100%;
        height: 100%;
    }*/

    #tbl_group {
        width: 900px;
        margin-left: 0;
    }
    ul#list_unit{
        margin-left:200px;
        margin-top:-15px;
        margin-bottom:0px;
        list-style:none;
    }

</style>

<script type="text/javascript">
    $(document).ready(function () {
        var numRow = 0;
        numRow = $('ul#list_unit li').length;
        var webroot = '<?php echo base_url();?>';

        var jsonUnitKerja = <?php echo json_encode($list_unit_kerja);?>;
        var optionUnitKerja = '';
        $.each(jsonUnitKerja, function (key, value){
           optionUnitKerja += '<option value="'+key+'">';
           optionUnitKerja += value;
           optionUnitKerja += '</option>';
        });

        function addNewUnit() {
            var row = '<li>';
            row += '<input type="hidden" value="" name="SettingFormulaDetail['+numRow+'][id]" class="setting-formula-detail">';
            row += '<select class="input-select-wrc unit-kerja" style="width:400px;" name="SettingFormulaDetail['+numRow+'][trunitkerja_id]">';
            row += optionUnitKerja;
            row += '</select>';
            row += '<button class="btn-delete" type="button">Hapus</button>';
            row += '</li>';
            $("ul#list_unit").append(row);
            numRow++;
        }

        function removeRowDetail(buttonElement) {
            $(buttonElement).parent().remove();
        }

        function deleteDetail(detail_id, buttonElement) {
            $.ajax({
                url: webroot + 'setting_formula/delete_detail',
                type: 'POST',
                dataType: 'json',
                data: {id_detail: detail_id},
                success: function (r) {
                    if (r.success != true) {
                        alert(r.message);
                    } else {
                        removeRowDetail(buttonElement);
                    }
                }
            });
        }

        $('#btn_add_unit').click(function(){
            addNewUnit();
        });

        $(".btn-delete").live('click', function () {
            var detail_id = $(this).parent().find('input[type="hidden"].setting-formula-detail').val();

            if (detail_id!= '') {
                var confirm = window.confirm('Apakah anda yakin ingin menghapus data ini?')
                if (confirm == true) {
                    deleteDetail(detail_id, this);
                }
            } else {
                removeRowDetail(this);
            }
        });

        /*$.validator.addMethod("required-option",
            function (value, element) {
                switch (element.nodeName.toLowerCase()) {
                    case 'select':
                        // could be an array for select-multiple or a string, both are fine this way
                        var val = $(element).val();
                        return val && val.length > 0 && val != -1;
                }
            },
            "Mohon pilih"
        );

        function addNewRow() {
            var row = '<tr>';
            row += '<td><input name="SettingTarifHarga[' + numRow + '][kategori]" class="required" style="width:100%"/></td>';
            row += '<td><input name="SettingTarifHarga[' + numRow + '][harga]" class="required" style="width:100%"/></td>';
            row += '<td><input type="button" class="btn_delete ui-widget-content" value="Hapus"/>';
            row += '<input type="hidden" name="SettingTarifHarga[' + numRow + '][id]" class="setting_tarif_harga_id"/>';
            row += '</td>' + '</tr>';
            $("#tbl_group tbody").append(row);
            numRow++;
        }

        $("#btn_add").click(function () {
            addNewRow();
        });

        $("#tabs").tabs();

        function removeRow(buttonElement) {
            $(buttonElement).parent().parent().remove();
        }

        function delete_kategori(setting_tarif_harga_id, buttonElement) {
            $.ajax({
                url: webroot + 'setting_tarif/delete_kategori',
                type: 'POST',
                dataType: 'json',
                data: {id_kategori: setting_tarif_harga_id},
                success: function (r) {
                    if (r.success != true) {
                        alert(r.message);
                    } else {
                        removeRow(buttonElement);
                    }
                }
            });
        }

        $(".btn_delete").live('click', function () {
            var setting_tarif_harga_id = $(this).parent().parent().find('td input[type="hidden"].setting_tarif_harga_id').val();

            if (setting_tarif_harga_id != '') {
                var confirm = window.confirm('Apakah anda yakin ingin menghapus data ini?')
                if (confirm == true) {
                    delete_kategori(setting_tarif_harga_id, this);
                }
            } else {
                removeRow(this);
            }
        });

        $('#trperizinan_id').multiselect({
            show: 'blind',
            hide: 'blind',
            multiple: false,
            header: 'Pilih salah satu',
            noneSelectedText: 'Pilih salah satu',
            selectedList: 1
        }).multiselectfilter();*/

    });
</script>