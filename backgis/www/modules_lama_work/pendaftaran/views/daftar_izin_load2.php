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
    $c_bap = "1";
//    $perizinan = new trperizinan();
//    $perizinan->get_by_id($jenis_id);
//    $daftar_p = new tmpermohonan();
//    $daftar_p->where_related($perizinan)
//            ->where('c_izin_dicabut', 0) // 0 -> izin yg berlaku
//            ->where('c_izin_selesai', 1) //SK Sudah diserahkan
//            ->order_by('id','ASC')->get();
//
//
//    if($daftar_p->id){
//        foreach ($daftar_p as $row){
//            $bap = new tmbap();
//            $bap->where_related($row)->get();
//            if($bap->status_bap === $c_bap){
//                $row->tmsk->get();
//                if(substr($row->tmsk->tgl_surat,0,4) === $_REQUEST['tahun_id'])
//                $opsi_daftar[$row->id] = $row->tmsk->no_surat;
//                else $opsi_daftar[0] = '-';
//    //            $opsi_daftar[$row->id] = $row->pendaftaran_id.', No Surat: '.$row->tmsk->no_surat;
//            }
//        }
//    }else{
//            $opsi_daftar[0] = '-';
//    }

    $query = "SELECT A.id, G.no_surat
    FROM tmpermohonan as A
    INNER JOIN tmpermohonan_trperizinan as B ON  B.tmpermohonan_id = A.id
    INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
    INNER JOIN tmbap_tmpermohonan as D ON D.tmpermohonan_id = A.id
    INNER JOIN tmbap as E ON D.tmbap_id = E.id
    INNER JOIN tmpermohonan_tmsk as F ON F.tmpermohonan_id = A.id
    INNER JOIN tmsk as G ON F.tmsk_id = G.id
    WHERE C.id = ".$jenis_id."
    AND E.status_bap = ".$c_bap."
    AND LEFT(G.tgl_surat, 4) = '".$_REQUEST['tahun_id']."'
    AND A.c_izin_dicabut = 0
    AND A.c_izin_selesai = 1
    order by A.id ASC";
    $results = mysql_query($query);
    if(mysql_num_rows($results)){
    while ($rows = mysql_fetch_assoc(@$results)){
        $opsi_daftar[$rows['id']] = $rows['no_surat'];
    }
    }else $opsi_daftar[0] = '-';

    echo form_label('No Surat Izin Lama');
    echo form_dropdown('no_daftar', $opsi_daftar, '','class = "input-select-wrc" id="no_daftar" multiple="multiple"');
    ?>
</div>