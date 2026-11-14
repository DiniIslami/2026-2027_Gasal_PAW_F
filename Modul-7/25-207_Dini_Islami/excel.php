
<?php
include 'koneksi.php';

$tanggal_mulai = $_GET['tanggal_mulai'] ?? "";
$tanggal_akhir = $_GET['tanggal_akhir'] ?? "";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_penjualan.xls");

if ($tanggal_mulai != "" && $tanggal_akhir != "") {

    $result = mysqli_query($koneksi, "
        SELECT DATE(waktu_transaksi) AS tanggal,
        SUM(total) AS total_harian
        FROM transaksi
        WHERE waktu_transaksi BETWEEN '$tanggal_mulai 00:00:00'
        AND '$tanggal_akhir 23:59:59'
        GROUP BY DATE(waktu_transaksi)
        ORDER BY tanggal ASC
    ");

} else {

    $result = mysqli_query($koneksi, "
        SELECT DATE(waktu_transaksi) AS tanggal,
        SUM(total) AS total_harian
        FROM transaksi
        GROUP BY DATE(waktu_transaksi)
        ORDER BY tanggal ASC
    ");

}

echo "<table border='1'>";

echo "
<tr>
    <th>No</th>
    <th>Total Pendapatan</th>
    <th>Tanggal Transaksi</th>
</tr>
";

$no = 1;
$total = 0;

while ($row = mysqli_fetch_assoc($result)) {

    $total += $row['total_harian'];

    echo "
    <tr>
        <td>".$no++."</td>
        <td>".$row['total_harian']."</td>
        <td>".$row['tanggal']."</td>
    </tr>
    ";

}

echo "
<tr>
    <th>Total Keseluruhan</th>
    <th>".$total."</th>
    <th></th>
</tr>
";

echo "</table>";
?>
