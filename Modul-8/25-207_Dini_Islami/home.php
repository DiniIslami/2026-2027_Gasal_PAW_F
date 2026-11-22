
<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$nama = $_SESSION['nama'];
$level = $_SESSION['level'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home Sistem Penjualan</title>

    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #f1f4f6;
        }

        nav {
            background: #07518c;
            padding: 18px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }

        nav a:hover {
            color: #bde5ff;
        }

        .menu {
            display: flex;
            align-items: center;
        }

        .logout {
            background: #d9534f;
            padding: 8px 12px;
            border-radius: 5px;
        }

        .isi {
            width: 80%;
            margin: 80px auto;
            padding: 40px;
            background: white;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 10px #ddd;
        }

        .isi h2 {
            color: #07518c;
        }

        .isi p {
            color: #555;
        }
    </style>
</head>

<body>

<nav>
    <div class="menu">
        <b>SISTEM PENJUALAN</b>

        <a href="home.php">Home</a>

        <?php if ($level == '1') { ?>
            <a href="barang.php">Data Barang</a>
            <a href="supplier.php">Data Supplier</a>
            <a href="pelanggan.php">Data Pelanggan</a>
            <a href="user.php">Data User</a>
        <?php } ?>

        <a href="transaksi.php">Transaksi</a>
        <a href="laporan.php">Laporan</a>
    </div>

    <div>
        <?php echo htmlspecialchars($nama); ?>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</nav>

<div class="isi">
    <h2>Selamat Datang di Sistem Penjualan</h2>

    <p>
        Halo, <?php echo htmlspecialchars($nama); ?>!
    </p>

    <p>
        Anda login sebagai
        <b>
            <?php
            if ($level == '1') {
                echo "Owner";
            } else {
                echo "Kasir";
            }
            ?>
        </b>
    </p>

    <p>Silakan pilih menu untuk melanjutkan aktivitas.</p>
</div>

</body>
</html>
