<script type="text/javascript">
    // < ![CDATA[
    var base_url = "<?php echo base_url(); ?>";
    //]] >
    $(document).ready(function() {
        initValidation();
        onChangeListIzin();
//        $(document).ready(function() {

            $('#jenis_izin').multiselect({
                show:'blind',
                hide:'blind',
                multiple: false,
                header: 'Pilih salah satu',
                noneSelectedText: 'Pilih salah satu',
                selectedList: 1
            }).multiselectfilter();

            $('#list_izin_paralel').multiselect({
               show:'blind',
               hide:'blind',
               header: 'Pilih opsi',
               noneSelectedText: 'Pilih opsi',
               selectedText:'# dari # pilihan'
            }).multiselectfilter();
//        });
        
        $('#list_paralel_id').change(function(){
            $('#show_list_izin').fadeOut();
            $.post(base_url+'pelayanan/pendaftaran/list_paralel_izin', {
                izin_paralel_id: $('#list_paralel_id').val()
            }, function(response){
                setTimeout("finishAjax('show_list_izin', '"+escape(response)+"')", 400);
            });
            return false;
        });
    });

    function finishAjax(id, response){
      $('#'+id).html(unescape(response));
      $('#'+id).fadeIn();
    }
</script>
<div class="contentForm" id="show_jenis_izin">
    <?php
    $username = new user();
    $username->where('username', $this->session->userdata('username'))->get();
    $listizinnya = $this->perizinan->where_related($username)->order_by('id', 'ASC')->get();
    echo form_hidden('paralel', $_REQUEST['jenis_paralel_id']);
    if($_REQUEST['jenis_paralel_id'] == "no"){
        $opsi_izin = array();
        if($listizinnya->id){
            foreach ($listizinnya as $row){
                $opsi_izin[$row->id] = $row->n_perizinan;
            }
        }

        echo form_label('Jenis Izin','name_jenis_izin');
        echo form_dropdown('jenis_izin', $opsi_izin, '','class = "input-select-wrc required" id="jenis_izin" multiple="multiple"');
    }else{
        $opsi_izin = array();
        if($list_paralel->id){
            foreach ($list_paralel as $row){
                $opsi_izin[$row->id] = $row->n_paralel;
            }
        }

        echo form_label('Jenis Paralel','name_jenis_paralel');
        echo form_dropdown('jenis_paralel', $opsi_izin, '','class = "input-select-wrc required" id="list_paralel_id"');
    ?>
<div class="contentForm bg-grid" id="show_list_izin">
    <?php
        $paralel_jenis = new trparalel();
        $paralel_jenis->select_min('id')->get();
        $opsi_list_izin = array();
        $list_izin_paralel = $this->perizinan->where_related($username)->where_related($paralel_jenis)->get();
        foreach ($list_izin_paralel as $data_list_izin) {
            $opsi_list_izin[$data_list_izin->id] = $data_list_izin->n_perizinan;
        }
        echo form_label('List Izin');
        echo form_dropdown('list_izin_paralel[]', $opsi_list_izin, '','class = "input-select-wrc required" id="list_izin_paralel" multiple="multiple"');
    ?>
</div>
    <?php
    }
        echo form_hidden('jenis_permohonan', $jenis_id);
    ?>
</div>