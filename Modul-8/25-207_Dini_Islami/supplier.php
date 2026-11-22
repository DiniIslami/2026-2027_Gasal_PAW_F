
<?php
session_start();
include 'koneksi.php';


if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}


if ($_SESSION['level'] != '1') {
    echo "<script>
        alert('Anda tidak memiliki akses!');
        window.location='home.php';
    </script>";
    exit;
}


if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    mysqli_query($koneksi, "
        INSERT INTO supplier (nama, telp, alamat)
        VALUES ('$nama', '$telp', '$alamat')
    ");

    header("Location: supplier.php");
    exit;
}


if (isset($_POST['update'])) {
    $id = $_POST['id_supplier'];
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    mysqli_query($koneksi, "
        UPDATE supplier SET
        nama='$nama',
        telp='$telp',
        alamat='$alamat'
        WHERE id_supplier='$id'
    ");

    header("Location: supplier.php");
    exit;
}


if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    $cek = mysqli_query($koneksi, "
        SELECT * FROM barang WHERE supplier_id='$id'
    ");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
            alert('Supplier tidak dapat dihapus karena masih digunakan oleh barang!');
            window.location='supplier.php';
        </script>";
    } else {
        mysqli_query($koneksi, "
            DELETE FROM supplier WHERE id_supplier='$id'
        ");

        header("Location: supplier.php");
        exit;
    }
}

$data_edit = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $query_edit = mysqli_query($koneksi, "
        SELECT * FROM supplier WHERE id_supplier='$id'
    ");

    $data_edit = mysqli_fetch_assoc($query_edit);
}

$data_supplier = mysqli_query($koneksi, "
    SELECT * FROM supplier
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Supplier</title>

    <style>
        body {
            font-family: Arial;
            margin: 30px;
        }

        h2 {
            color: #6099ad;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #6099ad;
            color: white;
        }

        input, textarea {
            padding: 8px;
            margin: 5px;
        }

        button {
            padding: 8px 15px;
            background-color: #78b64a;
            color: white;
            border: none;
            cursor: pointer;
        }

        a {
            text-decoration: none;
            margin: 5px;
        }
    </style>
</head>

<body>

<h2>Data Supplier</h2>

<p>Login sebagai: <?php echo $_SESSION['nama']; ?></p>

<a href="home.php">Kembali ke Home</a>

<h3><?php echo $data_edit ? "Edit Supplier" : "Tambah Supplier"; ?></h3>

<form method="POST">

    <?php if ($data_edit) { ?>
        <input type="hidden" name="id_supplier"
        value="<?php echo $data_edit['id_supplier']; ?>">
    <?php } ?>

    <input type="text" name="nama"
        placeholder="Nama Supplier" required
        value="<?php echo $data_edit['nama'] ?? ''; ?>">

    <input type="text" name="telp"
        placeholder="Nomor Telepon" required
        value="<?php echo $data_edit['telp'] ?? ''; ?>">

    <input type="text" name="alamat"
        placeholder="Alamat" required
        value="<?php echo $data_edit['alamat'] ?? ''; ?>">

    <?php if ($data_edit) { ?>
        <button type="submit" name="update">Update</button>
        <a href="supplier.php">Batal</a>
    <?php } else { ?>
        <button type="submit" name="tambah">Tambah</button>
    <?php } ?>

</form>

<h3>Daftar Supplier</h3>

<table>
    <tr>
        <th>No</th>
        <th>Nama Supplier</th>
        <th>Telepon</th>
        <th>Alamat</th>
        <th>Aksi</th>
    </tr>

    <?php
    $no = 1;

    while ($data = mysqli_fetch_assoc($data_supplier)) {
    ?>

    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo $data['nama']; ?></td>
        <td><?php echo $data['telp']; ?></td>
        <td><?php echo $data['alamat']; ?></td>

        <td>
            <a href="supplier.php?edit=<?php echo $data['id_supplier']; ?>">
                Edit
            </a>

            <a href="supplier.php?hapus=<?php echo $data['id_supplier']; ?>"
            onclick="return confirm('Yakin ingin menghapus supplier ini?')">
                Hapus
            </a>
        </td>
    </tr>

    <?php } ?>

</table>

</body>
</html>
