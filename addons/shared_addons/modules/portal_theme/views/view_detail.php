<style type="text/css">
    table{
        width:600px;
        background: none repeat scroll 0 0 #FCFCFC;
    }
    table td{
        padding:5px;
        vertical-align: top;
        text-align: left;
    }
    table th{
        color: #5A9E25;
        padding:5px;
        vertical-align: top;
        text-align: left;
    }
    table img{
        border: 1px solid #5A9E25;
    }
</style>
<table>
    <tr>
        <td rowspan="7">
            <?php echo img(base_url().'/files/thumb/'.$portal_theme->foto.'/200x300/fit');?>
        </td>
        <th width="30%">
            NIP
        </th>
        <td width="30%">
            <?php echo $portal_theme->nip;?>
        </td>
    </tr>
    <tr>
        <th>Nama</th>
        <td>
            <?php echo $portal_theme->nama_portal_theme;?>
        </td>
    </tr>
    <tr>
        <th>Tempat / Tgl Lahir</th>
        <td>
            <?php echo $portal_theme->tempat_lahir;?>,
            <?php echo date('d M Y',strtotime($portal_theme->tgl_lahir));?>
        </td>
    </tr>
    <tr>
        <th>Pendidikan</th>
        <td><?php echo $portal_theme->pendidikan;?></td>
    </tr>
    <tr>
        <th>Jabatan</th>
        <td><?php echo $portal_theme->jabatan;?></td>
    </tr>
    <tr>
        <th>Alamat</th>
        <td><?php echo $portal_theme->alamat;?></td>
    </tr>
    <tr>
        <th>No Telp</th>
        <td><?php echo $portal_theme->no_telp;?></td>
    </tr>
</table>