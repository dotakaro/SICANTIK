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
</script>
<div class="contentForm" id="show_list_izin">
    <?php
    $c_bap = "2";

    $query = "SELECT A.id, A.pendaftaran_id
    FROM tmpermohonan as A
    INNER JOIN tmpermohonan_trperizinan as B ON  B.tmpermohonan_id = A.id
    INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
    INNER JOIN tmbap_tmpermohonan as D ON D.tmpermohonan_id = A.id
    INNER JOIN tmbap as E ON D.tmbap_id = E.id
    WHERE C.id = ".$jenis_id."
    AND E.status_bap = ".$c_bap."
    AND LEFT(A.d_terima_berkas, 4) = '".$_REQUEST['tahun_id']."'
    AND A.c_izin_dicabut = 0
    AND A.c_izin_selesai = 1
    order by A.id ASC";
    $results = mysql_query($query);
    if(mysql_num_rows($results)){
    while ($rows = mysql_fetch_assoc(@$results)){
        $opsi_daftar[$rows['id']] = $rows['pendaftaran_id'];
    }
    }else $opsi_daftar[0] = '-';

    echo form_label('No Pendaftaran Lama');
    echo form_dropdown('no_daftar', $opsi_daftar, '','class = "input-select-wrc" id="no_daftar" multiple="multiple"');
    ?>
</div>