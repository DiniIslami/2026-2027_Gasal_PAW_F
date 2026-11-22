
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
    $jk = $_POST['jenis_kelamin'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    mysqli_query($koneksi, "
        INSERT INTO pelanggan (nama, jenis_kelamin, telp, alamat)
        VALUES ('$nama', '$jk', '$telp', '$alamat')
    ");

    header("Location: pelanggan.php");
    exit;
}

if (isset($_POST['update'])) {
    $id = $_POST['id_pelanggan'];
    $nama = $_POST['nama'];
    $jk = $_POST['jenis_kelamin'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    mysqli_query($koneksi, "
        UPDATE pelanggan SET
        nama='$nama',
        jenis_kelamin='$jk',
        telp='$telp',
        alamat='$alamat'
        WHERE id_pelanggan='$id'
    ");

    header("Location: pelanggan.php");
    exit;
}


if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    $cek = mysqli_query($koneksi, "
        SELECT * FROM transaksi WHERE id_pelanggan='$id'
    ");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
            alert('Pelanggan tidak dapat dihapus karena sudah memiliki transaksi!');
            window.location='pelanggan.php';
        </script>";
    } else {
        mysqli_query($koneksi, "
            DELETE FROM pelanggan WHERE id_pelanggan='$id'
        ");

        header("Location: pelanggan.php");
        exit;
    }
}


$data_edit = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $query_edit = mysqli_query($koneksi, "
        SELECT * FROM pelanggan WHERE id_pelanggan='$id'
    ");

    $data_edit = mysqli_fetch_assoc($query_edit);
}


$data_pelanggan = mysqli_query($koneksi, "
    SELECT * FROM pelanggan
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Pelanggan</title>

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

<h2>Data Pelanggan</h2>

<p>Login sebagai: <?php echo $_SESSION['nama']; ?></p>

<a href="home.php">Kembali ke Home</a>

<h3><?php echo $data_edit ? "Edit Pelanggan" : "Tambah Pelanggan"; ?></h3>

<form method="POST">

    <?php if ($data_edit) { ?>
        <input type="hidden" name="id_pelanggan"
        value="<?php echo $data_edit['id_pelanggan']; ?>">
    <?php } ?>

    <input type="text" name="nama"
        placeholder="Nama Pelanggan" required
        value="<?php echo $data_edit['nama'] ?? ''; ?>">

    <select name="jenis_kelamin" required>
        <option value="">Pilih Jenis Kelamin</option>

        <option value="L"
        <?php
        if ($data_edit && $data_edit['jenis_kelamin'] == 'L') {
            echo "selected";
        }
        ?>>
            Laki-laki
        </option>

        <option value="P"
        <?php
        if ($data_edit && $data_edit['jenis_kelamin'] == 'P') {
            echo "selected";
        }
        ?>>
            Perempuan
        </option>
    </select>

    <input type="text" name="telp"
        placeholder="Nomor Telepon" required
        value="<?php echo $data_edit['telp'] ?? ''; ?>">

    <input type="text" name="alamat"
        placeholder="Alamat" required
        value="<?php echo $data_edit['alamat'] ?? ''; ?>">

    <?php if ($data_edit) { ?>
        <button type="submit" name="update">Update</button>
        <a href="pelanggan.php">Batal</a>
    <?php } else { ?>
        <button type="submit" name="tambah">Tambah</button>
    <?php } ?>

</form>

<h3>Daftar Pelanggan</h3>

<table>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Jenis Kelamin</th>
        <th>Telepon</th>
        <th>Alamat</th>
        <th>Aksi</th>
    </tr>

    <?php
    $no = 1;

    while ($data = mysqli_fetch_assoc($data_pelanggan)) {
    ?>

    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo $data['nama']; ?></td>
        <td><?php echo $data['jenis_kelamin']; ?></td>
        <td><?php echo $data['telp']; ?></td>
        <td><?php echo $data['alamat']; ?></td>

        <td>
            <a href="pelanggan.php?edit=<?php echo $data['id_pelanggan']; ?>">
                Edit
            </a>

            <a href="pelanggan.php?hapus=<?php echo $data['id_pelanggan']; ?>"
            onclick="return confirm('Yakin ingin menghapus pelanggan ini?')">
                Hapus
            </a>
        </td>
    </tr>

    <?php } ?>

</table>

</body>
</html>
