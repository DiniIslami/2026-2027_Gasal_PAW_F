
<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

// Tambah transaksi
if (isset($_POST['tambah_transaksi'])) {
    $keterangan = $_POST['keterangan'];
    $waktu = str_replace('T', ' ', $_POST['waktu_transaksi']);
    $alamat = $_POST['alamat_transaksi'];
    $pelanggan = $_POST['id_pelanggan'];

    mysqli_query($koneksi, "
        INSERT INTO transaksi
        (keterangan, waktu_transaksi, total, alamat_transaksi, id_pelanggan)
        VALUES ('$keterangan', '$waktu', 0, '$alamat', '$pelanggan')
    ");

    $id = mysqli_insert_id($koneksi);

    header("Location: transaksi.php?detail=$id");
    exit;
}

// Tambah barang ke detail transaksi
if (isset($_POST['tambah_detail'])) {
    $id_transaksi = $_POST['id_transaksi'];
    $id_barang = $_POST['id_barang'];
    $qty = $_POST['qty'];

    // Cek apakah barang sudah ada di transaksi
    $cek = mysqli_query($koneksi, "
        SELECT * FROM transaksi_detail
        WHERE id_transaksi='$id_transaksi'
        AND id_barang='$id_barang'
    ");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
            alert('Barang sudah dimasukkan ke transaksi ini!');
            window.location='transaksi.php?detail=$id_transaksi';
        </script>";
        exit;
    }

    // Ambil harga barang
    $barang = mysqli_query($koneksi, "
        SELECT harga FROM barang
        WHERE id_barang='$id_barang'
    ");

    $data_barang = mysqli_fetch_assoc($barang);
    $harga = $data_barang['harga'];

    // Simpan detail transaksi
    mysqli_query($koneksi, "
        INSERT INTO transaksi_detail
        (id_transaksi, id_barang, harga, qty)
        VALUES ('$id_transaksi', '$id_barang', '$harga', '$qty')
    ");

    // Hitung ulang total transaksi
    mysqli_query($koneksi, "
        UPDATE transaksi
        SET total = (
            SELECT SUM(harga * qty)
            FROM transaksi_detail
            WHERE id_transaksi='$id_transaksi'
        )
        WHERE id_transaksi='$id_transaksi'
    ");

    header("Location: transaksi.php?detail=$id_transaksi");
    exit;
}

// Tambah pembayaran
if (isset($_POST['tambah_pembayaran'])) {
    $id_transaksi = $_POST['id_transaksi'];
    $waktu = str_replace('T', ' ', $_POST['waktu_bayar']);
    $total = $_POST['total_bayar'];
    $metode = $_POST['metode'];

    mysqli_query($koneksi, "
        INSERT INTO pembayaran
        (waktu_bayar, total, metode, transaksi_id)
        VALUES ('$waktu', '$total', '$metode', '$id_transaksi')
    ");

    header("Location: transaksi.php?detail=$id_transaksi");
    exit;
}

// Ambil data pelanggan
$pelanggan = mysqli_query($koneksi, "
    SELECT * FROM pelanggan
");

// Ambil daftar transaksi
$data_transaksi = mysqli_query($koneksi, "
    SELECT transaksi.*, pelanggan.nama AS nama_pelanggan
    FROM transaksi
    LEFT JOIN pelanggan
    ON transaksi.id_pelanggan = pelanggan.id_pelanggan
    ORDER BY id_transaksi DESC
");

// Jika memilih detail transaksi
$detail_id = $_GET['detail'] ?? '';

$data_detail = null;
$data_pembayaran = null;
$data_transaksi_pilih = null;

if ($detail_id != '') {

    $pilih = mysqli_query($koneksi, "
        SELECT * FROM transaksi
        WHERE id_transaksi='$detail_id'
    ");

    $data_transaksi_pilih = mysqli_fetch_assoc($pilih);

    if ($data_transaksi_pilih) {

        $data_detail = mysqli_query($koneksi, "
            SELECT transaksi_detail.*, barang.nama_barang
            FROM transaksi_detail
            JOIN barang
            ON transaksi_detail.id_barang = barang.id_barang
            WHERE transaksi_detail.id_transaksi='$detail_id'
        ");

        $data_pembayaran = mysqli_query($koneksi, "
            SELECT * FROM pembayaran
            WHERE transaksi_id='$detail_id'
        ");

        // Barang yang belum digunakan pada transaksi ini
        $data_barang = mysqli_query($koneksi, "
            SELECT * FROM barang
            WHERE id_barang NOT IN (
                SELECT id_barang
                FROM transaksi_detail
                WHERE id_transaksi='$detail_id'
            )
        ");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Transaksi</title>

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

        .bagian {
            margin-top: 30px;
        }
    </style>
</head>

<body>

<h2>Data Transaksi</h2>

<p>Login sebagai: <?php echo $_SESSION['nama']; ?></p>

<a href="home.php">Kembali ke Home</a>

<div class="bagian">
    <h3>Tambah Transaksi</h3>

    <form method="POST">

        <input type="text" name="keterangan"
            placeholder="Keterangan transaksi" required>

        <input type="datetime-local" name="waktu_transaksi" required>

        <input type="text" name="alamat_transaksi"
            placeholder="Alamat transaksi" required>

        <select name="id_pelanggan" required>
            <option value="">Pilih Pelanggan</option>

            <?php while ($p = mysqli_fetch_assoc($pelanggan)) { ?>
                <option value="<?php echo $p['id_pelanggan']; ?>">
                    <?php echo $p['nama']; ?>
                </option>
            <?php } ?>
        </select>

        <button type="submit" name="tambah_transaksi">
            Buat Transaksi
        </button>

    </form>
</div>

<div class="bagian">
    <h3>Daftar Transaksi</h3>

    <table>
        <tr>
            <th>No</th>
            <th>Keterangan</th>
            <th>Waktu</th>
            <th>Total</th>
            <th>Alamat</th>
            <th>Pelanggan</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;
        while ($t = mysqli_fetch_assoc($data_transaksi)) {
        ?>

        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $t['keterangan']; ?></td>
            <td><?php echo $t['waktu_transaksi']; ?></td>
            <td>Rp <?php echo number_format($t['total'], 0, ',', '.'); ?></td>
            <td><?php echo $t['alamat_transaksi']; ?></td>
            <td><?php echo $t['nama_pelanggan']; ?></td>
            <td>
                <a href="transaksi.php?detail=<?php echo $t['id_transaksi']; ?>">
                    Detail
                </a>
            </td>
        </tr>

        <?php } ?>
    </table>
</div>

<?php if ($data_transaksi_pilih) { ?>

<div class="bagian">

    <h3>Detail Transaksi #<?php echo $detail_id; ?></h3>

    <p>
        Keterangan: <?php echo $data_transaksi_pilih['keterangan']; ?>
    </p>

    <h4>Tambah Barang</h4>

    <form method="POST">

        <input type="hidden" name="id_transaksi"
            value="<?php echo $detail_id; ?>">

        <select name="id_barang" required>
            <option value="">Pilih Barang</option>

            <?php while ($b = mysqli_fetch_assoc($data_barang)) { ?>
                <option value="<?php echo $b['id_barang']; ?>">
                    <?php echo $b['nama_barang']; ?>
                </option>
            <?php } ?>

        </select>

        <input type="number" name="qty"
            placeholder="Jumlah" min="1" required>

        <button type="submit" name="tambah_detail">
            Tambah Barang
        </button>

    </form>

    <h4>Daftar Barang Transaksi</h4>

    <table>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
        </tr>

        <?php
        $no = 1;
        while ($d = mysqli_fetch_assoc($data_detail)) {
        ?>

        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $d['nama_barang']; ?></td>
            <td>Rp <?php echo number_format($d['harga'], 0, ',', '.'); ?></td>
            <td><?php echo $d['qty']; ?></td>
            <td>
                Rp <?php echo number_format(
                    $d['harga'] * $d['qty'], 0, ',', '.'
                ); ?>
            </td>
        </tr>

        <?php } ?>

    </table>

    <h3>
        Total Transaksi:
        Rp <?php echo number_format(
            $data_transaksi_pilih['total'], 0, ',', '.'
        ); ?>
    </h3>

    <h4>Tambah Pembayaran</h4>

    <form method="POST">

        <input type="hidden" name="id_transaksi"
            value="<?php echo $detail_id; ?>">

        <input type="datetime-local" name="waktu_bayar" required>

        <input type="number" name="total_bayar"
            placeholder="Total pembayaran" min="1" required>

        <select name="metode" required>
            <option value="">Pilih Metode</option>
            <option value="Tunai">Tunai</option>
            <option value="Transfer">Transfer</option>
            <option value="EDC">EDC</option>
        </select>

        <button type="submit" name="tambah_pembayaran">
            Simpan Pembayaran
        </button>

    </form>

    <h4>Riwayat Pembayaran</h4>

    <table>
        <tr>
            <th>No</th>
            <th>Waktu Bayar</th>
            <th>Total Pembayaran</th>
            <th>Metode</th>
        </tr>

        <?php
        $no = 1;
        while ($bayar = mysqli_fetch_assoc($data_pembayaran)) {
        ?>

        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $bayar['waktu_bayar']; ?></td>
            <td>
                Rp <?php echo number_format($bayar['total'], 0, ',', '.'); ?>
            </td>
            <td><?php echo $bayar['metode']; ?></td>
        </tr>

        <?php } ?>

    </table>

</div>

<?php } ?>

</body>
</html>
