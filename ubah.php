<?php

session_start();

if( !isset($_SESSION["login"] ) ) {
    header("Location: login.php");
    exit;
}

require 'functions.php';

$id =  $_GET["id"];

$mhs = query("SELECT * FROM mahasiswa WHERE id = $id")[0];


//cek apakah tombol submit sudah ditekan atau belum
if( isset($_POST["submit"])){

//ambil data melalui tiap elemen dalam form


//Cek apakah data berhasil diubah atau tidak 
if(ubah($_POST) > 0 ) {
    echo "
    <script>
        alert('Data Berhasil Diubah!');
        document.location.href = 'index.php';
    </script>";
}else {
    echo "    <script>
    alert('Data Gagal Diubah!');
    document.location.href = 'index.php';
</script>";
}

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Data Mahasiswa</title>
    <style>
        label {
            display: block;
            padding: 10px;
        }
    </style>
</head>
<body>

    <h1>Ubah Data Mahasiswa</h1>
    
<form action="" method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?= $mhs["id"]; ?>">
<input type="hidden" name="gambarlama" value="<?= $mhs["gambar"]; ?>">
    <ul>
        <li>
            <label for="nrp">NRP : </label>
            <input type="text" name="nrp" id="nrp" required value="<?= $mhs["nrp"]?>">
        </li>
        <li>
        <label for="nama">Nama : </label>
            <input type="text" name="nama" id="nama" value="<?= $mhs["nama"]?>">
        </li>
        <li>
        <label for="email">Email : </label>
            <input type="text" name="email" id="email" value="<?= $mhs["email"]?>">
        </li>
        <li>
        <label for="jurusan">Jurusan : </label>
            <input type="text" name="jurusan" id="jurusan" value="<?= $mhs["jurusan"]?>">
        </li>
        <li>
        <label for="gambar">Gambar : </label>
            <img src ="img/<?= $mhs['gambar']; ?>" width="100"> 
            <input type="file" name="gambar" id="gambar">
        </li>
        <li>
            <button type="submit" name="submit">Ubah Data!</button>
        </li>
    </ul>

</form>

</body>
</html>