<script type="text/javascript">
    $(document).ready(function() {
        $('#kabupaten_pemohon_id').change(function(){
            $.post('<?php echo base_url(); ?>pelayanan/pendaftaran/kecamatan_pemohon', { kabupaten_id: $('#kabupaten_pemohon_id').val() },
            function(data) {
                $('#show_kecamatan_pemohon').html(data);
                $('#show_kelurahan_pemohon').html('Data Tidak tersedia');
            });

        });
        $('#kecamatan_pemohon_id').change(function(){
            $.post('<?php echo base_url(); ?>pelayanan/pendaftaran/kelurahan_pemohon', { kecamatan_id: $('#kecamatan_pemohon_id').val() },
            function(data) {
                $('#show_kelurahan_pemohon').html(data);
            });
        });
    });


    function validasi()
    {
        var	first=document.forms[0].first_date.value;
        var	second=document.forms[0].second_date.value;

        if(first.length==0)
        {
            document.forms[0].first_date.focus();
            alert("Periode awal mohon diisi");
            return false;
        }

        else if(second.length==0)
        {
            document.forms[0].second_date.focus();
            alert("Periode akhir mohon diisi");
            return false;
        }
        else
        {
            window.location =  "monitoringkecamatan/cetak_kelurahan/<?php echo $kelurahan_id ?>/<?php echo $first_date; ?>/<?php echo $second_date; ?>"
            return true;
        }

    }
</script>

<div id="content">
    <div class="post">
        <div class="title">
            <h2><?php echo $page_name; ?></h2>
        </div>
        <fieldset id="half">
            <?php
            $attr = array(
                'class' => 'searchForm',
                'id' => 'searchForm'
            );
            echo form_open("monitoring/kecamatan", $attr);
            ?>
            <div class="contentForm">
                <?php
                $opsi_propinsi = array('0' => '-------Pilih data-------');
                foreach ($list_propinsi as $row) {
                    $opsi_propinsi[$row->id] = $row->n_propinsi;
                }
                echo form_label('Propinsi');
                echo form_dropdown('propinsi_pemohon', $opsi_propinsi, $propinsi_id, 'class = "input-select-wrc" id="propinsi_pemohon_id"');
                ?>
            </div>

            <div class="contentForm" >
                <?php
                echo form_label('Kabupaten');
                echo "<div id='show_kabupaten_pemohon'>";
                if (!empty($kabupaten_id)) {
                    $opsi_kabupaten = array('0' => '-------Pilih data-------');
                    foreach ($list_kabupaten as $row) {
                        $opsi_kabupaten[$row->id] = $row->n_kabupaten;
                    }
                    echo form_dropdown("kabupaten_pemohon", $opsi_kabupaten, $kabupaten_id, 'class = "input-select-wrc notSelect" id="kabupaten_pemohon_id"');
                } else {
                    echo "Data Tidak Tersedia";
                }
                echo "</div>";
                ?>
            </div>
            <div style="clear: both" ></div>
            <div class="contentForm">
                <?php
                echo form_label('Kecamatan');
                echo "<div id='show_kecamatan_pemohon'>";
                if (!empty($kecamatan_id)) {
                    $opsi_kecamatan = array('0' => '-------Pilih data-------');
                    foreach ($list_kecamatan as $row) {
                        $opsi_kecamatan[$row->id] = $row->n_kecamatan;
                    }
                    echo form_dropdown("kecamatan_pemohon", $opsi_kecamatan, $kecamatan_id, 'class = "input-select-wrc notSelect" id="kecamatan_pemohon_id"');
                } else {
                    echo "Data Tidak Tersedia";
                }
                echo "</div>";
                ?>
            </div>
            <div style="clear: both" ></div>
            <div class="contentForm">
                <?php
                echo form_label('Kelurahan');
                echo "<div id='show_kelurahan_pemohon'>";
                if (!empty($kelurahan_id)) {
                    $opsi_kelurahan = array('0' => '-------Pilih data-------');
                    foreach ($list_kelurahan as $row) {
                        $opsi_kelurahan[$row->id] = $row->n_kelurahan;
                    }
                    echo form_dropdown("kelurahan_pemohon", $opsi_kelurahan, $kelurahan_id, 'class = "input-select-wrc notSelect" id="kelurahan_pemohon_id"');
                } else {
                    echo "Data Tidak Tersedia";
                }

                echo "</div>";
                ?>

            </div>
            <div style="clear: both" ></div>

            <div class="contentForm">

                <?php
                $periodeawal_input = array(
                    'name' => 'first_date',
                    'class' => 'monbulan',
                    'id' => 'firstDateInput',
                    'readOnly' => TRUE,
                    'value' => $first_date
                );
                echo form_label('Periode Awal', 'd_tahun');
                echo form_input($periodeawal_input);
                ?>
            </div>
            <div style="clear: both" ></div>

            <div class="contentForm">
                <?php
                $periodeakhir_input = array(
                    'name' => 'second_date',
                    'class' => 'monbulan',
                    'id' => 'secondDateInput',
                    'readOnly' => TRUE,
                    'value' => $second_date
                );
                echo form_label('Periode Akhir', 'd_tahun');
                echo form_input($periodeakhir_input);
                ?>
            </div>
            <?php
                $cari = array(
                    'name' => 'submit',
                    'class' => 'button-wrc',
                    'content' => 'Cari',
                    'value' => 'Cari',
                    'type' => 'submit',
                    'onclick' => 'return validasi()'
                );
                echo form_button($cari);

                $cetak = array(
                    'name' => 'cetak',
                    'class' => 'button-wrc',
                    'id' => 'cetak',
                    'content' => 'Cetak',
                    'type' => 'button',
                    'onclick' => 'return validasi()'
                );

                if ($jumlah > 0) {
                    echo form_button($cetak);
                }
                echo form_close();
            ?>
        </fieldset>
        
        <div class="entry">
            <table cellpadding="0" cellspacing="0" border="0" class="display" id="listdata">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Pendaftaran</th>
                        <th>Nama Perizinan</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Nama Pemohon</th>
                        <th>Status Permohonan</th>
                        <th>Alamat Pemohon</th>
                        <th>Kelurahan</th>

                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8" class="dataTables_empty">Tidak ada  data..</td>
                    </tr>
                </tbody>

            </table>
        </div>
    </div>
    <br style="clear: both;" />
</div>
