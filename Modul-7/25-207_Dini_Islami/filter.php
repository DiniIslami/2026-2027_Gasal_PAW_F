
<?php
include 'koneksi.php';

$tanggal_mulai = $_GET['tanggal_mulai'] ?? "";
$tanggal_akhir = $_GET['tanggal_akhir'] ?? "";

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Penjualan</title>

    <style>
        div {
            width: 500px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <h2>Laporan Penjualan</h2>

    <div class="no-print">
        <button onclick="window.print()">Print</button>
        <a href="excel.php?tanggal_mulai=<?= $tanggal_mulai ?>&tanggal_akhir=<?= $tanggal_akhir ?>">
            <button>Export</button>
        </a>

        <form method="get" action="">
            Tanggal mulai:
            <input type="date" name="tanggal_mulai"
                value="<?= $tanggal_mulai ?>">

            Tanggal akhir:
            <input type="date" name="tanggal_akhir"
                value="<?= $tanggal_akhir ?>">

            <button type="submit">Submit</button>
        </form>
    </div>

    <div>
        <canvas id="myChart"></canvas>
    </div>

    <table border="1">
        <tr>
            <th>No</th>
            <th>Total Pendapatan</th>
            <th>Tanggal Transaksi</th>
        </tr>

        <?php

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

        $labels = [];
        $data = [];
        $total_pendapatan = 0;
        $no = 1;

        while ($row = mysqli_fetch_assoc($result)) {

            $labels[] = $row['tanggal'];
            $data[] = $row['total_harian'];

            $total_pendapatan += $row['total_harian'];

            echo "<tr>";
            echo "<td>".$no++."</td>";
            echo "<td>Rp ".number_format($row['total_harian'], 0, ',', '.')."</td>";
            echo "<td>".$row['tanggal']."</td>";
            echo "</tr>";

        }

        ?>
    </table>

    <?php

    if ($tanggal_mulai != "" && $tanggal_akhir != "") {

        $query_pelanggan = mysqli_query($koneksi, "
            SELECT COUNT(DISTINCT id_pelanggan) AS jumlah
            FROM transaksi
            WHERE waktu_transaksi BETWEEN '$tanggal_mulai 00:00:00'
            AND '$tanggal_akhir 23:59:59'
        ");

    } else {

        $query_pelanggan = mysqli_query($koneksi, "
            SELECT COUNT(DISTINCT id_pelanggan) AS jumlah
            FROM transaksi
        ");

    }

    $pelanggan = mysqli_fetch_assoc($query_pelanggan);

    ?>

    <h3>Ringkasan Laporan</h3>

    <p>Jumlah Pelanggan Unik: <?= $pelanggan['jumlah'] ?> Orang</p>

    <p>Total Pendapatan: Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></p>

    <script>
        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'bar',

            data: {
                labels: <?php echo json_encode($labels); ?>,

                datasets: [{
                    label: 'Total Pendapatan per Hari',
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: '#6099ad',
                    borderWidth: 1
                }]
            },

            options: {
                responsive: true,

                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

</body>
</html>
