<div id="content">
    <div class="post">
        <div class="title">
                  <h2><?php echo $page_name; ?></h2>
        </div>
    <div class="subnav">
            <div class="status">
            <?php
                echo form_open(site_url('monitoring/create'));
                 ?>
        <fieldset>
            <legend>Pencarian</legend>
            <tr bgcolor="#E2FDD4">
      <td width="30%" height="30">Bulan</td>
      <td width="70%">
        <select name="select_maksud" class="text_select">
        <option value="semua">Batal</option>
        <option value="semua">Dicabut</option>
       
        </select>
      </td>
  </tr>
            <div align="right">

                  <td><input type="button" name="btn_lihat" value="Tampilkan" class="button" onclick="" /></td>

                 </div>
       </fieldset>
            </div>
        <div class="entry">
           <table cellpadding="0" cellspacing="0" border="0" class="display" id="monitoring">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Nama Pemohon</th>
                        <th>Alamat Pemohon</th>
                        <th>Jenis Izin</th>

                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = NULL;
                    foreach ($listpermohonan as $data){
                        $i++;
                        $data->tmpemohon->get();
                        $data->trperizinan->get();


                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $data->pendaftaran_id; ?></td>
                        <td><?php echo $data->d_entry; ?></td>
                        <td><?php echo $data->tmpemohon->n_pemohon; ?></td>
                        <td><?php echo $data->tmpemohon->a_pemohon; ?></td>
                        <td><?php echo $data->trperizinan->n_perizinan; ?></td>

                      </tr>
                <?php
                    }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Nama Pemohon</th>
                        <th>Alamat Pemohon</th>
                        <th>Jenis Izin</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div
  </div>
     <br style="clear: both;" />
</div>
