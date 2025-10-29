<script type="text/javascript">
    $(document).ready(function() {
//        $(document).ready(function() {
            $('#list_izin_paralel').multiselect({
               show:'blind',
               hide:'blind',
               header: 'Pilih opsi',
               noneSelectedText: 'Pilih opsi',
               selectedText:'# dari # pilihan'
            }).multiselectfilter();
//        } );
        initValidation();
        onChangeListIzin();
    } );
</script>
<div class="contentForm" id="show_jenis_izin">
    <?php
    if($_REQUEST)
    {
        $username = new user();
        $username->where('username', $this->session->userdata('username'))->get();
        $paralel_jenis = new trparalel();
        $paralel_jenis->get_by_id($_REQUEST['izin_paralel_id']);
        $list_izin_paralel = $this->perizinan->where_related($username)->where_related($paralel_jenis)->get();
        $opsi_list_izin = array();
        foreach ($list_izin_paralel as $data_list_izin) {
            $opsi_list_izin[$data_list_izin->id] = $data_list_izin->n_perizinan;
        }
        echo form_label('List Izin');
        echo form_dropdown('list_izin_paralel[]', @$opsi_list_izin, '','class = "input-select-wrc required" id="list_izin_paralel" multiple="multiple"');
    }
    ?>
</div>