
<?php
include 'koneksi.php';

$tanggal_mulai = $_GET['tanggal_mulai'];
$tanggal_selesai = $_GET['tanggal_selesai'];

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_penjualan.xls");

$query = mysqli_query($koneksi, "
    SELECT
        DATE(waktu_transaksi) AS tanggal,
        SUM(total) AS total_harian
    FROM transaksi
    WHERE DATE(waktu_transaksi)
    BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'
    GROUP BY DATE(waktu_transaksi)
    ORDER BY tanggal ASC
");
?>

<h3>Laporan Penjualan</h3>

<table border="1">
    <tr>
        <th>No</th>
        <th>Total Pendapatan</th>
        <th>Tanggal Transaksi</th>
    </tr>

    <?php
    $no = 1;

    while ($data = mysqli_fetch_assoc($query)) {
    ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $data['total_harian']; ?></td>
            <td><?php echo $data['tanggal']; ?></td>
        </tr>
    <?php } ?>
</table>
