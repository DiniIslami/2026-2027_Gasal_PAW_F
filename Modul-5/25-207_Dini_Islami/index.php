
<?php
include 'koneksi.php';
$tabel = [
    'pelanggan',
    'supplier',
    'user',
    'barang',
    'transaksi',
    'transaksi_detail',
    'pembayaran'
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Database Penjualan</title>
    <style>    
body {
    font-family: Arial, sans-serif;
    margin: 40px auto;
    width: 850px;
    color: #555;
    background-color: #fff;
}

h2 {
    color: #6099ad;
    font-size: 25px;
    font-weight: normal;
    margin-bottom: 25px;
}

.tambah {
    display: inline-block;
    float: right;
    background-color: #78b64a;
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 3px;
    font-size: 13px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-size: 13px;
}

th {
    background-color: #d5edf6;
    color: #555;
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}

td {
    padding: 12px;
    border: 1px solid #ddd;
}

.edit {
    display: inline-block;
    background-color: #ed8526;
    color: white;
    padding: 8px 12px;
    text-decoration: none;
    border-radius: 3px;
}

.hapus {
    display: inline-block;
    background-color: #d52b2b;
    color: white;
    padding: 8px 12px;
    text-decoration: none;
    border-radius: 3px;
}

form {
    width: 600px;
    margin: 30px auto;
}

.form-group {
    display: flex;
    align-items: center;
    margin-bottom: 18px;
}

label {
    width: 130px;
    font-size: 14px;
    font-weight: bold;
    text-align: right;
    margin-right: 20px;
}

input, textarea {
    width: 300px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
    font-family: Arial;
    font-size: 14px;
}

textarea {
    height: 35px;
    resize: vertical;
}

.simpan {
    background-color: #78b64a;
    color: white;
    border: none;
    padding: 10px 16px;
    cursor: pointer;
    border-radius: 3px;
}

.batal {
    display: inline-block;
    background-color: #d52b2b;
    color: white;
    padding: 10px 16px;
    text-decoration: none;
    border-radius: 3px;
    font-size: 13px;
}

    </style>
</head>

<body>

<?php
include 'koneksi.php';

$query = mysqli_query($koneksi, "SELECT * FROM supplier");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Master Supplier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Data Master Supplier</h2>

<a href="tambah.php" class="tambah">Tambah Data</a>

<table>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Telp</th>
        <th>Alamat</th>
        <th>Tindakan</th>
    </tr>

    <?php
    $no = 1;

    while ($data = mysqli_fetch_assoc($query)) {
    ?>

    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo htmlspecialchars($data['nama']); ?></td>
        <td><?php echo htmlspecialchars($data['telp']); ?></td>
        <td><?php echo htmlspecialchars($data['alamat']); ?></td>
        <td>
            <a href="edit.php?id=<?php echo $data['id_supplier']; ?>"
               class="edit">Edit</a>

            <a href="hapus.php?id=<?php echo $data['id_supplier']; ?>"
            class="hapus" 
            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
        </td>
    </tr>

    <?php } ?>

</table>

</body>
</html>


</body>
</html>
