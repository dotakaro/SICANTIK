<div class="block">
	<div class="block-title">
		<a class="right" href="{{ url:site}}daftar_layanan">Kembali ke Daftar Jenis Perizinan</a>
		<h2><?php echo strtoupper($nama_jenis); ?></h2>
	</div>
	<div class="block-content">
		<table width="100%" cellpadding="0" cellspacing="0" class="blue styled-table">
	        <thead>
	            <tr>
	                <th width="5%">No</th>
	                <th style="width:80%">Daftar Syarat</th>

	            </tr>
	        </thead>
	        <tbody>
	            <?php
	            $n = 1;
	            foreach ($list as $row) {

	                echo "<tr>";
	                if($row['syarat_perizinan']=="Belum Tersedia Syarat Perizinan")
	                  echo "<td>-</td>";  
	                else
	                  echo "<td>$n</td>";
	                  echo "<td>" . $row['syarat_perizinan'] . "</td>";
	                echo "</tr>";
	                $n++;
	            }
	            ?>


	        </tbody>
	    </table>
            
            <?php if(!empty($downloads['dasar_hukum'])):?>
            <table width="100%" cellpadding="0" cellspacing="0" class="blue styled-table">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th style="width:80%">Dasar Hukum</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $n = 1;
                    foreach ($downloads['dasar_hukum'] as $row) {
                        echo "<tr>";
                            echo "<td>".$n."</td>";
                            echo "<td>".anchor('files/download/'.$row->file_download, $row->file_desc)."</td>";
                        echo "</tr>";
                        $n++;
                    }
                    ?>
                </tbody>
            </table>
            <?php endif;?>

            <?php if(!empty($downloads['formulir'])):?>
                <table width="100%" cellpadding="0" cellspacing="0" class="blue styled-table">
                    <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th style="width:80%">Formulir Perizinan</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $n = 1;
                    foreach ($downloads['formulir'] as $row) {
                        echo "<tr>";
                        echo "<td>".$n."</td>";
                        echo "<td>".anchor('files/download/'.$row->file_download, $row->file_desc)."</td>";
                        echo "</tr>";
                        $n++;
                    }
                    ?>
                    </tbody>
                </table>
            <?php endif;?>
	</div>
</div>