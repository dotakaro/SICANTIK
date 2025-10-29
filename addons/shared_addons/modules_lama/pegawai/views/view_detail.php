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
            <?php echo img(base_url().'/files/thumb/'.$pegawai->foto.'/200x300/fit');?>
        </td>
        <th width="30%">
            NIP
        </th>
        <td width="30%">
            <?php echo $pegawai->nip;?>
        </td>
    </tr>
    <tr>
        <th>Nama</th>
        <td>
            <?php echo $pegawai->nama_pegawai;?>
        </td>
    </tr>
    <tr>
        <th>Tempat / Tgl Lahir</th>
        <td>
            <?php echo $pegawai->tempat_lahir;?>,
            <?php echo date('d M Y',strtotime($pegawai->tgl_lahir));?>
        </td>
    </tr>
    <tr>
        <th>Pendidikan</th>
        <td><?php echo $pegawai->pendidikan;?></td>
    </tr>
    <tr>
        <th>Jabatan</th>
        <td><?php echo $pegawai->jabatan;?></td>
    </tr>
    <tr>
        <th>Alamat</th>
        <td><?php echo $pegawai->alamat;?></td>
    </tr>
    <tr>
        <th>No Telp</th>
        <td><?php echo $pegawai->no_telp;?></td>
    </tr>
</table>