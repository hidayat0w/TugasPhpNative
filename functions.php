<?php 

//koneksi kedatabase
$conn = mysqli_connect("localhost", "root", "", "phpdasar1");

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while( $row = mysqli_fetch_assoc($result)){
    $rows[] = $row;

    }
    return $rows;
}

function tambah($data) {
    global $conn;

    $nama = htmlspecialchars($data["nama"]);
    $nrp = htmlspecialchars($data["nrp"]);
    $email = htmlspecialchars($data["email"]);
    $jurusan = htmlspecialchars($data["jurusan"]);

 //uplod gambar
 $gambar = upload();
 if( !$gambar ) {
        return false;
 }
   
//query insert data
$query = "INSERT into mahasiswa 
            VALUES
                ('', '$nama', '$nrp', '$email', '$jurusan', '$gambar')";
 
mysqli_query($conn, $query);

return mysqli_affected_rows($conn);
}

function upload() {

    $namafile = $_FILES['gambar']['name'];
    $ukuranfile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    //cek apakah tidak ada gambar yg diupload
    if( $error === 4 ){
        echo "<script>
                alert('Pilih Gambar Terlebih dahulu!');
             </script>";
             return false;
    }

//cek apakah yang diupload adalah gambar
    $ekstensigambarvalid = ['jpg','jpeg','png'];
    $ekstensigambar = explode('.', $namafile );
    $ekstensigambar = strtolower(end($ekstensigambar));

    if( !in_array($ekstensigambar, $ekstensigambarvalid)) {
        echo "<script>
        alert('File Yang Anda Upload Bukan Gambar!');
     </script>";
     return false;
    }

    //cek jika ukuran terlalu besar
    if( $ukuranfile > 1000000 ) {
        echo "<script>
        alert('Ukuran Gambar Terlalu Besar!');
     </script>";
     return false;
    }

// generate nama gmbar baru
$namafilebaru = uniqid();
$namafilebaru .= '.'; 
$namafilebaru .= $ekstensigambar;

//lolos pengecekan gambar iap diupload
    move_uploaded_file($tmpName, 'img/' . $namafilebaru);
    
    return $namafilebaru;

}

function hapus($id){
    global $conn;
    mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id");

    return mysqli_affected_rows($conn);
}

function ubah($data) {
    global $conn;

    $id = $data["id"];
    $nama = htmlspecialchars($data["nama"]);
    $nrp = htmlspecialchars($data["nrp"]);
    $email = htmlspecialchars($data["email"]);
    $jurusan = htmlspecialchars($data["jurusan"]);
    $gambarlama = htmlspecialchars($data["gambarlama"]);
    $gambar = htmlspecialchars($data["gambar"]);

//cek apakah user memilh gambar baru atau tidak
    if( $_FILES['gambar']['error'] === 4 ) {
        $gambar = $gambarlama;
    }else {
        $gambar = upload();
    }




//query update data
$query = "UPDATE mahasiswa SET
            nrp = '$nrp',
            nama = '$nama',
            email = '$email',
            jurusan = '$jurusan',
            gambar = '$gambar'
        WHERE id = $id
        ";
 
mysqli_query($conn, $query);

return mysqli_affected_rows($conn);
}

function cari($keyword) {
    $query = "SELECT * FROM mahasiswa
                WHERE
            nama LIKE '%$keyword%' OR
            nrp LIKE '%$keyword%' OR
            email LIKE '%$keyword%' OR
            jurusan LIKE '%$keyword%' 
        ";
        return query($query);
}

function registrasi($data) {
    global $conn;

    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);

//cek username sudah ada / belum
    $result = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");

    if( mysqli_fetch_assoc($result) ) {
        echo "<script>
                alert('Username Yang Dipilih Sudah Terdaftar!');
            </script>";
        return false;
    }

//cek konfirmasi password
    if($password !== $password2 ){
        echo "<script>
                alert('Konfirmasi Password Tidak Sesuai!');
            </script>";
            return false;
    }

//enkripsi password terlebih dahulu
$password = password_hash($password, PASSWORD_DEFAULT);

//tambah user baru kedatabase
mysqli_query($conn, "INSERT INTO user VALUES('', '$username', '$password')");

return mysqli_affected_rows($conn);


}

?>