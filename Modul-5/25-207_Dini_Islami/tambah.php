<?php
include 'koneksi.php';

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $query = mysqli_query($koneksi, "INSERT INTO supplier (nama, telp, alamat)
                                    VALUES ('$nama', '$telp', '$alamat')");

    if ($query) {
        header("Location: index.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Supplier</title>

    <style>
body {
    font-family: Arial, sans-serif;
    background-color: white;
}

.container {
    width: 400px;
    margin: 30px 0 0 50px;
}

h2 {
    color: #5b91a6;
    font-size: 18px;
    font-weight: normal;
    padding-bottom: 12px;
    border-bottom: 1px solid #eeeeee;
    margin-bottom: 15px;
}

table {
    width: 100%;
}

td {
    padding: 5px;
    font-size: 12px;
    color: #333333;
}

td:first-child {
    width: 90px;
    text-align: right;
}

input, textarea {
    width: 195px;
    padding: 7px;
    border: 1px solid #cccccc;
    border-radius: 3px;
    box-sizing: border-box;
    font-size: 12px;
}

textarea {
    height: 35px;
    resize: none;
}

button {
    background-color: #70ad47;
    color: white;
    border: none;
    border-radius: 3px;
    padding: 6px 10px;
    font-size: 12px;
    cursor: pointer;
}

a {
    display: inline-block;
    background-color: #c00000;
    color: white;
    text-decoration: none;
    border-radius: 3px;
    padding: 6px 10px;
    font-size: 12px;
}
    </style>
</head>

<body>

<div class="container">
    <h2>Tambah Data Master Supplier Baru</h2>
    <form method="POST">
        <table>
            <tr>
                <td>Nama Supplier</td>
                <td>
                    <input type="text" name="nama" required>
                </td>
            </tr>

            <tr>
                <td>Telepon</td>
                <td>
                    <input type="text" name="telp" required>
                </td>
            </tr>

            <tr>
                <td>Alamat</td>
                <td>
                    <textarea name="alamat" required></textarea>
                </td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <button type="submit" name="simpan">Simpan</button>
                    <a href="index.php">Kembali</a>
                </td>
            </tr>
        </table>
    </form>
</div>

</body>
</html>