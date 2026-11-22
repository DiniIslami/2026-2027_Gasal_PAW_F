
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
    $kode = $_POST['kode_barang'];
    $nama = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $supplier = $_POST['supplier_id'];

    mysqli_query($koneksi, "
        INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id)
        VALUES ('$kode', '$nama', '$harga', '$stok', '$supplier')
    ");

    header("Location: barang.php");
    exit;
}


if (isset($_POST['update'])) {
    $id = $_POST['id_barang'];
    $kode = $_POST['kode_barang'];
    $nama = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $supplier = $_POST['supplier_id'];

    mysqli_query($koneksi, "
        UPDATE barang SET
        kode_barang='$kode',
        nama_barang='$nama',
        harga='$harga',
        stok='$stok',
        supplier_id='$supplier'
        WHERE id_barang='$id'
    ");

    header("Location: barang.php");
    exit;
}


if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($koneksi, "
        DELETE FROM barang WHERE id_barang='$id'
    ");

    header("Location: barang.php");
    exit;
}


$data_edit = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $query_edit = mysqli_query($koneksi, "
        SELECT * FROM barang WHERE id_barang='$id'
    ");

    $data_edit = mysqli_fetch_assoc($query_edit);
}


$supplier = mysqli_query($koneksi, "SELECT * FROM supplier");


$data_barang = mysqli_query($koneksi, "
    SELECT barang.*, supplier.nama AS nama_supplier
    FROM barang
    LEFT JOIN supplier
    ON barang.supplier_id = supplier.id_supplier
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Barang</title>
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

        input, select {
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

<h2>Data Barang</h2>

<p>Login sebagai: <?php echo $_SESSION['nama']; ?></p>

<a href="home.php">Kembali ke Home</a>

<h3><?php echo $data_edit ? "Edit Barang" : "Tambah Barang"; ?></h3>

<form method="POST">
    <?php if ($data_edit) { ?>
        <input type="hidden" name="id_barang"
        value="<?php echo $data_edit['id_barang']; ?>">
    <?php } ?>

    <input type="text" name="kode_barang"
        placeholder="Kode Barang" required
        value="<?php echo $data_edit['kode_barang'] ?? ''; ?>">

    <input type="text" name="nama_barang"
        placeholder="Nama Barang" required
        value="<?php echo $data_edit['nama_barang'] ?? ''; ?>">

    <input type="number" name="harga"
        placeholder="Harga" required
        value="<?php echo $data_edit['harga'] ?? ''; ?>">

    <input type="number" name="stok"
        placeholder="Stok" required
        value="<?php echo $data_edit['stok'] ?? ''; ?>">

    <select name="supplier_id" required>
        <option value="">Pilih Supplier</option>

        <?php
        mysqli_data_seek($supplier, 0);
        while ($s = mysqli_fetch_assoc($supplier)) {
        ?>
            <option value="<?php echo $s['id_supplier']; ?>"
            <?php
            if ($data_edit && $data_edit['supplier_id'] == $s['id_supplier']) {
                echo "selected";
            }
            ?>>
                <?php echo $s['nama']; ?>
            </option>
        <?php } ?>
    </select>

    <?php if ($data_edit) { ?>
        <button type="submit" name="update">Update</button>
        <a href="barang.php">Batal</a>
    <?php } else { ?>
        <button type="submit" name="tambah">Tambah</button>
    <?php } ?>
</form>

<h3>Daftar Barang</h3>

<table>
    <tr>
        <th>No</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Supplier</th>
        <th>Aksi</th>
    </tr>

    <?php
    $no = 1;
    while ($data = mysqli_fetch_assoc($data_barang)) {
    ?>
    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo $data['kode_barang']; ?></td>
        <td><?php echo $data['nama_barang']; ?></td>
        <td><?php echo $data['harga']; ?></td>
        <td><?php echo $data['stok']; ?></td>
        <td><?php echo $data['nama_supplier']; ?></td>
        <td>
            <a href="barang.php?edit=<?php echo $data['id_barang']; ?>">
                Edit
            </a>

            <a href="barang.php?hapus=<?php echo $data['id_barang']; ?>"
            onclick="return confirm('Yakin ingin menghapus barang ini?')">
                Hapus
            </a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
