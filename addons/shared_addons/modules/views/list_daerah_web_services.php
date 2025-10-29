<?php
if ($jenis_list == 1) {

    echo "<option value=''>Pilih Kabupaten : </option>";
    foreach ($list as $row) {
        $id=$row['id'];
        $nama=$row['n_kabupaten'] ;
        echo "<option value='$id'>$nama</option>";
    }

} elseif ($jenis_list == 2) {

    echo "<option value=''>Pilih Kecamatan : </option>";
    foreach ($list as $row) {
        $id=$row['id'];
        $nama=$row['nama'] ;
        echo "<option value='$id'>$nama</option>";
    }

} elseif($jenis_list == 3){

        echo "<option value=''>Pilih Kelurahan : </option>";
    foreach ($list as $row) {
        $id=$row['id'];
        $nama=$row['nama'] ;
        echo "<option value='$id'>$nama</option>";
    }


}

?>