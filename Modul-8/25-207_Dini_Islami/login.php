
<?php
session_start();
include 'koneksi.php';

if (isset($_SESSION['id_user'])) {
    header("Location: home.php");
    exit;
}

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $query = mysqli_query($koneksi, "
        SELECT * FROM user
        WHERE username = '$username'
        AND password = '$password'
    ");

    if (mysqli_num_rows($query) > 0) {

        $data = mysqli_fetch_assoc($query);

        session_regenerate_id(true);

        $_SESSION['id_user'] = $data['id_user'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['level'] = $data['level'];

        header("Location: home.php");
        exit;

    } else {
        $pesan = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Sistem Penjualan</title>

    <style>
        body {
            font-family: Arial;
            background: #eaf1f5;
        }

        .login {
            width: 350px;
            margin: 100px auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        h2 {
            text-align: center;
            color: #28749b;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #28749b;
            color: white;
            border: none;
            cursor: pointer;
        }

        .pesan {
            color: red;
            text-align: center;
        }
    </style>
</head>

<body>

<div class="login">
    <h2>Login Sistem Penjualan</h2>

    <?php
    if (isset($pesan)) {
        echo "<p class='pesan'>$pesan</p>";
    }
    ?>

    <form method="POST">
        <input type="text" name="username"
               placeholder="Username" required>

        <input type="password" name="password"
               placeholder="Password" required>

        <button type="submit" name="login">Login</button>
    </form>
</div>

</body>
</html>
