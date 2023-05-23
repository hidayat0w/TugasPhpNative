<?php 
session_start();

require 'functions.php';

//cek kue
if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
    $id = $_COOKIE['id'];
    $key = $_COOKIE['key'];

//ambil username berdasarkan id
    $result = mysqli_query($conn, "SELECT username FROM user WHERE id = $id");
    $row = mysqli_fetch_assoc($result);

//cek kue dan username
    if( $key === hash('sha256', $row['username'])) {
        $_SESSION['login'] = true;
    } 

}
// if(isset($_COOKIE['login'])) {
//     if($_COOKIE['login'] == 'true') {
//         $_SESSION['login'] = true;
//     }
// }

if( isset($_SESSION["login"])){
    header("Location: index.php");
    exit;
}

if( isset($_POST["login"] ) ) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");

//Cek username
    if(mysqli_num_rows($result) === 1 ) {

//cek password
        $row = mysqli_fetch_assoc($result);
        if(password_verify($password, $row["password"] ) ) {
//set session
            $_SESSION["login"] = true;

//cek remembr me
            if(isset($_POST['remember'])){
//buat cookie
                
                setcookie('key', hash('sha256', $row['username']), time()+60);
            }


            header("Location: index.php");
            exit;
        }

    }

    $error = true;

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
    <style>
        label {
            display: block;
            padding: 10px;
        }
    </style>
</head>
<body>
    <h1>Halaman Login</h1>

    
    <?php if(isset($error ) ) : ?>
        <p style="color: red; font-style: italic">Username atau Password salah!</p>
    <?php endif; ?>

    <form action="" method="post">
        <ul>
            <li>
                <label for="username">Username : </label>
                <input type="text" name="username" id="username">
            </li>
            <li>
                <label for="password">Password : </label>
                <input type="password" name="password" id="password">
            </li>
            <li>
                <label for="remember">Remember Me </label>
                <input type="checkbox" name="remember" id="remember">
            </li>
            <br>
            <li>
                <button type="submit" name="login">Login</button>
            </li>
        </ul>
    </form>








</body>
</html>