<html>
<head>
    <link rel="stylesheet" href="<?php echo base_url();?>addons/shared_addons/themes/portal_agam/css/main-stylesheet.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="<?php echo base_url();?>addons/shared_addons/themes/portal_agam/css/fonts.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="<?php echo base_url();?>addons/shared_addons/themes/portal_agam/css/colors.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="<?php echo base_url();?>addons/shared_addons/themes/portal_agam/css/custom.css" type="text/css" media="screen"/>
</head>

<body>
<div class="content">
    <div class="wrapper">
        <div class="block-content">
            <div class="header">
                <div class="wrapper">
                    <div class="header-logo">
                        <a href="http://localhost/portal_agam/">
                            <?php echo img('addons/shared_addons/themes/portal_agam/img/logo222.png',false,'alt="Portal Kabupaten Agam"');?>
                        </a>
                    </div>
                </div>
            </div>
            <div class="main-content">
                <div class="content-block main">
                    <div class="block">
                        <div class="block-content">
                            <h2>Data Izin yang Anda ajukan</h2>

                            <table class="blue styled-table" width="100%">
                            <tbody>
                            <tr>
                                <td width="20%">No Pendaftaran </td>
                                <td>:  <b><?php echo $no_pendaftaran;?></b></td>
                            </tr>
                            <tr>
                                <td>Nama Pemohon  </td>
                                <td>:  <b><?php echo $nama_pemohon;?></b></td>
                            </tr>
                            <tr>
                                <td>Nama Perizinan  </td>
                                <td>:  <b><?php echo $nama_perizinan;?></b></td>
                            </tr>
                            </tbody>
                            </table>
							<p><i><b><font size= "3" face="chiller" color="red">*Tanda terima ini adalah bukti pendaftaran secara online, proses perizinan dimulai setelah pemohon membawa berkas permohonan pada Dinas Penanaman Modal dan Pelayanan Perizinan Terpadu</font></i></b></p>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
</div>
</body>
</html>