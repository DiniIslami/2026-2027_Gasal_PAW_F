
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
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $hp = $_POST['hp'];
    $level = $_POST['level'];

    mysqli_query($koneksi, "
        INSERT INTO user (username, password, nama, alamat, hp, level)
        VALUES ('$username', '$password', '$nama', '$alamat', '$hp', '$level')
    ");

    header("Location: user.php");
    exit;
}

if (isset($_POST['update'])) {
    $id = $_POST['id_user'];
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $hp = $_POST['hp'];
    $level = $_POST['level'];

    if ($_POST['password'] != "") {
        $password = $_POST['password'];

        mysqli_query($koneksi, "
            UPDATE user SET
            username='$username',
            password='$password',
            nama='$nama',
            alamat='$alamat',
            hp='$hp',
            level='$level'
            WHERE id_user='$id'
        ");
    } else {
        mysqli_query($koneksi, "
            UPDATE user SET
            username='$username',
            nama='$nama',
            alamat='$alamat',
            hp='$hp',
            level='$level'
            WHERE id_user='$id'
        ");
    }

    header("Location: user.php");
    exit;
}


if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    if ($id == $_SESSION['id_user']) {
        echo "<script>
            alert('Akun yang sedang digunakan tidak dapat dihapus!');
            window.location='user.php';
        </script>";
    } else {
        mysqli_query($koneksi, "
            DELETE FROM user WHERE id_user='$id'
        ");

        header("Location: user.php");
        exit;
    }
}

$data_edit = null;

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];

    $query_edit = mysqli_query($koneksi, "
        SELECT * FROM user WHERE id_user='$id'
    ");

    $data_edit = mysqli_fetch_assoc($query_edit);
}

$data_user = mysqli_query($koneksi, "
    SELECT * FROM user
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data User</title>

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

<h2>Data User</h2>

<p>Login sebagai: <?php echo $_SESSION['nama']; ?></p>

<a href="home.php">Kembali ke Home</a>

<h3><?php echo $data_edit ? "Edit User" : "Tambah User"; ?></h3>

<form method="POST">

    <?php if ($data_edit) { ?>
        <input type="hidden" name="id_user"
        value="<?php echo $data_edit['id_user']; ?>">
    <?php } ?>

    <input type="text" name="username"
        placeholder="Username" required
        value="<?php echo $data_edit['username'] ?? ''; ?>">

    <input type="password" name="password"
        placeholder="<?php echo $data_edit ? 'Password baru (kosongkan jika tidak diubah)' : 'Password'; ?>"
        <?php echo $data_edit ? '' : 'required'; ?>>

    <input type="text" name="nama"
        placeholder="Nama Lengkap" required
        value="<?php echo $data_edit['nama'] ?? ''; ?>">

    <input type="text" name="alamat"
        placeholder="Alamat" required
        value="<?php echo $data_edit['alamat'] ?? ''; ?>">

    <input type="text" name="hp"
        placeholder="Nomor HP" required
        value="<?php echo $data_edit['hp'] ?? ''; ?>">

    <select name="level" required>
        <option value="">Pilih Level</option>

        <option value="1"
        <?php
        if ($data_edit && $data_edit['level'] == '1') {
            echo "selected";
        }
        ?>>
            Owner
        </option>

        <option value="2"
        <?php
        if ($data_edit && $data_edit['level'] == '2') {
            echo "selected";
        }
        ?>>
            Kasir
        </option>
    </select>

    <?php if ($data_edit) { ?>
        <button type="submit" name="update">Update</button>
        <a href="user.php">Batal</a>
    <?php } else { ?>
        <button type="submit" name="tambah">Tambah</button>
    <?php } ?>

</form>

<h3>Daftar User</h3>

<table>
    <tr>
        <th>No</th>
        <th>Username</th>
        <th>Nama</th>
        <th>Alamat</th>
        <th>HP</th>
        <th>Level</th>
        <th>Aksi</th>
    </tr>

    <?php
    $no = 1;

    while ($data = mysqli_fetch_assoc($data_user)) {
    ?>

    <tr>
        <td><?php echo $no++; ?></td>
        <td><?php echo $data['username']; ?></td>
        <td><?php echo $data['nama']; ?></td>
        <td><?php echo $data['alamat']; ?></td>
        <td><?php echo $data['hp']; ?></td>
        <td>
            <?php
            if ($data['level'] == '1') {
                echo "Owner";
            } else {
                echo "Kasir";
            }
            ?>
        </td>

        <td>
            <a href="user.php?edit=<?php echo $data['id_user']; ?>">
                Edit
            </a>

            <a href="user.php?hapus=<?php echo $data['id_user']; ?>"
            onclick="return confirm('Yakin ingin menghapus user ini?')">
                Hapus
            </a>
        </td>
    </tr>

    <?php } ?>

</table>

</body>
</html>
