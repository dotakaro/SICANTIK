<script language="javascript" type="text/javascript">
    $(document).ready(function() {
        $('#no_daftar').multiselect({
           show:'blind',
           hide:'blind',
           multiple: false,
           header: "Pilih salah satu",
           noneSelectedText: "Pilih salah satu",
           selectedList: 1
        }).multiselectfilter();
    });
    // < ![CDATA[
    var base_url = "<?php echo base_url(); ?>";
    var jenis_id = "<?php echo $_REQUEST['jenis_izin_id']; ?>";
    //]] >
    $(document).ready(function() {
        $('#year_id').change(function(){
                $('#show_list_izin').fadeOut();
                $.post(base_url+'pendaftaran/daftar_izin2/'+jenis_id, {
                    tahun_id: $('#year_id').val()
                }, function(response){
                    setTimeout("finishAjax('show_list_izin', '"+escape(response)+"')", 400);
                });
                return false;
        });
    } );

    function finishAjax(id, response){
      $('#'+id).html(unescape(response));
      $('#'+id).fadeIn();
    }
</script>
<div class="contentForm" id="show_daftar_izin">
    <?php
        $year = new year();
        $year->order_by('tahun', 'DESC')->get();
        foreach ($year as $data){
            $opsi_year[$data->tahun] = $data->tahun;
        }
        echo form_label('Tahun Surat Izin Lama');
        echo form_dropdown('year', $opsi_year, '','class = "input-select-wrc" id="year_id"');
    ?>
    <div class="contentForm" id="show_list_izin">
    <?php
    $perizinan = new trperizinan();
    $perizinan->get_by_id($_REQUEST['jenis_izin_id']);
    $daftar_p = new tmpermohonan();
    $daftar_p->where_related($perizinan)
            ->where('c_izin_dicabut', 0) // 0 -> izin yg berlaku
            ->where('c_izin_selesai', 1) //SK Sudah diserahkan
            ->order_by('id','ASC')->limit(0)->get();

    $c_bap = "1";
    if($daftar_p->id){
        foreach ($daftar_p as $row){
            $bap = new tmbap();
            $bap->where_related($row)->get();
            if($bap->status_bap === $c_bap){
                $row->tmsk->get();
                $opsi_daftar[$row->id] = $row->tmsk->no_surat;
    //            $opsi_daftar[$row->id] = $row->pendaftaran_id.', No Surat: '.$row->tmsk->no_surat;
            }
        }
    }else{
            $opsi_daftar[0] = '-';
    }

    echo form_label('No Surat Izin Lama');
    echo form_dropdown('no_daftar', $opsi_daftar, '','class = "input-select-wrc" id="no_daftar" multiple="multiple"');
    ?>
    </div>
</div>