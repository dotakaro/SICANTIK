<!--<h2>Pendaftaran Surat Izin Sukses</h2>
<p>Terima kasih</p>-->



<div class="block">
    <div class="block-title">Data Izin yang Anda ajukan</div>
    <div class="block-content">
        <div class="success-box">
            <?php echo $this->session->flashdata('success');?>
        </div>

        <table class="blue styled-table width="100%">
        <tbody>
        <tr>
            <td width="20%">No Pendaftaran </td>
            <td>:</td>
            <td><b><?php echo $no_pendaftaran;?></b></td>
        </tr>
        <tr>
            <td>Nama Pemohon  </td>
            <td>:</td>
            <td><b><?php echo $nama_pemohon;?></b></td>
        </tr>
        <tr>
            <td>Nama Perizinan  </td>
            <td>:</td>
            <td><b><?php echo $nama_perizinan;?></b></td>
        </tr>
        </tbody>
        </table>

        <?php
        echo form_open('perizinan_online/save_pdf','target="_blank"');
        echo form_hidden('regis_no', $no_pendaftaran);
        echo form_hidden('regis_name', $nama_pemohon);
        echo form_hidden('regis_izin', $nama_perizinan);
        echo form_submit('btn_save','Save as PDF','class="button"');
        echo form_close();
        ?>
    </div>
</div>


            