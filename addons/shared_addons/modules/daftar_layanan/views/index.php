<div class="block">
	<div class="block-title">
		<a class="right" href="{{ url:site}}">Kembali ke halaman utama</a>
		<h2>Daftar Jenis Perizinan</h2>
	</div>
	
	<div class="block-content">
		<table width="100%" cellpadding="0" cellspacing="0" class="blue styled-table">
		    <thead>
		            <th style="width:60%; border: none;" >Jenis Perizinan</th>
		            <th style="width:20%; border: none;">Lama Pengerjaan</th>
		            <th style="width:20%; border: none;">&nbsp;</th>
		    </thead>
		    <tbody>
		        <?php
		            $n=1;
		            foreach ($list as $row) {
		                $x = str_replace(' ', "_", $row['jenis_perizinan']);
		                $link = anchor('daftar_layanan/syarat/' . $row['id'], 'Detail');
		                echo "
		                        <tr>
		                            <td style='color:black;'><a href='daftar_layanan/syarat/".$row['id']."'>".$n.". ". $row['jenis_perizinan']."</a></td>
		                            <td style='color:black; text-align: center;'><a href='daftar_layanan/syarat/".$row['id']."'> ".$row['v_hari']." hari </a></td>
		                            <td>$link</td>
		                        </tr>
		                    ";
		                $n++;
		            }
		        ?>


		    </tbody>
		</table>
	</div>
</div>