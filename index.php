<?php 
session_start();

if( ! isset($_SESSION["login"]) ) {
    header("Location: login.php");
    exit;
}

require 'functions.php';

//pagination
//konfigurasi
$jumlahdataperhalaman = 5;
$jumlahdata = count(query("SELECT * FROM mahasiswa"));
$jumlahhalaman = ceil($jumlahdata / $jumlahdataperhalaman);
$halamanaktif = ( isset($_GET["halaman"])) ? $_GET["halaman"] : 1;
$awaldata = ($jumlahdataperhalaman * $halamanaktif) - $jumlahdataperhalaman;

$mahasiswa = query("SELECT * FROM mahasiswa LIMIT $awaldata, $jumlahdataperhalaman");


//tombol cari diklik
if(isset($_POST["cari"])) {
    $mahasiswa = cari($_POST["keyword"]);
}

// if (isset($_GET["cari"])) {
//     $jumlahdataperhalaman=2;
//     $cari=$_GET["cari"];
//     $jumlahdata=count(cari("SELECT * FROM mahasiswa WHERE nama LIKE '%$cari%' OR no LIKE '%$cari%' OR id LIKE '%$cari%'"));
//     $jumlahhalaman=ceil($jumlahdata/$jumlahdataperhalaman);
//     $halamanaktif=(isset($_GET["halaman"])) ? $_GET["halaman"] : 1; 
//     $awaldata=$jumlahdataperhalaman*$halamanaktif-$jumlahdataperhalaman;
//     $isi=query("SELECT * FROM mahasiswa WHERE nama LIKE '%$cari%' OR no LIKE '%$cari%' OR id LIKE '%$cari%'LIMIT $awaldata, $jumlahdataperhalaman");
// }
// else{
//     $jumlahdataperhalaman=2;
//     $jumlahdata=count(query("SELECT * FROM mahasiswa"));
//     $jumlahhalaman=ceil($jumlahdata/$jumlahdataperhalaman);
//     $halamanaktif=(isset($_GET["halaman"])) ? $_GET["halaman"] : 1; 
//     $awaldata=$jumlahdataperhalaman*$halamanaktif-$jumlahdataperhalaman;
//     $isi=query("SELECT * FROM mahasiswa ORDER BY id ASC LIMIT $awaldata,$jumlahdataperhalaman");

// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Halaman Admin</title>
</head>
<body>
    
<h1>Daftar Mahasiswa</h1>

<a href="logout.php">Logout</a>

<br>
<br>

<a href="tambah.php">Tambahkan Data Mahasiswa</a>
<br></br>

<form action="" method="post">

<input type="text" name="keyword" size="40" autofocus placeholder="Masukkan keyword Pencarian.." autocomplete="off">
<button type="submit" name="cari">Cari Data Mahasiswa</button>

</form>
<br>
<!-- navigasi -->
<?php if( $halamanaktif > 1 ) : ?>
    <a href="?halaman=<?= $halamanaktif - 1;?>">&laquo;</a>
<?php endif;?>


<?php for($i = 1; $i <= $jumlahhalaman; $i++ ) : ?>
    <?php if($i == $halamanaktif) : ?>
    <a href="?halaman=<?=$i; ?>" style="font-weight: bold; color: red;"><?= $i;?></a>
    <?php else : ?>
        <a href="?halaman=<?=$i;?>"><?= $i;?></a>
    <?php endif; ?>
<?php endfor; ?>

<?php if( $halamanaktif < $jumlahhalaman ) : ?>
    <a href="?halaman=<?= $halamanaktif + 1;?>">&raquo;</a>
<?php endif;?>

<br></br>

<table border="1" cellpadding="10" cellspacing="0">

<tr>
    <th>No.</th>
    <th>Aksi</th>
    <th>Gambar</th>
    <th>NRP</th>
    <th>Nama</th>
    <th>Email</th>
    <th>Jurusan</th>

</tr>

<?php $i = 1; ?>
<?php foreach( $mahasiswa as $row ) : ?>

<tr>
    <td><?php echo $i; ?></td>
    <td><a href="ubah.php?id=<?php echo $row["id"]; ?>">Ubah</a> |
    <a href="hapus.php?id=<?php echo $row["id"]; ?>" onclick="return confirm('Apakah Anda ingin menghapus data ini?');">Hapus</a>
</td>

<td><img src="img/<?php echo $row["gambar"]; ?>" width="75"></td>
<td><?= $row["nrp"]; ?> </td>
<td><?php echo $row["nama"]; ?> </td>
<td><?php echo $row["email"];?> </td>
<td> <?php echo $row["jurusan"]; ?></td>

</tr>

<?php $i++ ?>
<?php endforeach; ?>

</table>

</body>
</html>