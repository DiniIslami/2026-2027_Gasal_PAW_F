
<?php
session_start();
include 'koneksi.php';

// Cek login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$tanggal_mulai = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

$rekap = [];
$labels = [];
$data_total = [];

$total_pendapatan = 0;
$total_pelanggan = 0;

if ($tanggal_mulai != '' && $tanggal_selesai != '') {

    // Rekap penjualan harian
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

    while ($row = mysqli_fetch_assoc($query)) {
        $rekap[] = $row;
        $labels[] = $row['tanggal'];
        $data_total[] = $row['total_harian'];
    }

    // Ringkasan laporan
    $query_ringkasan = mysqli_query($koneksi, "
        SELECT
            COUNT(DISTINCT id_pelanggan) AS jumlah_pelanggan,
            SUM(total) AS total_pendapatan
        FROM transaksi
        WHERE DATE(waktu_transaksi)
        BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'
    ");

    $ringkasan = mysqli_fetch_assoc($query_ringkasan);

    $total_pelanggan = $ringkasan['jumlah_pelanggan'] ?? 0;
    $total_pendapatan = $ringkasan['total_pendapatan'] ?? 0;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        input, button {
            padding: 8px;
            margin: 5px;
        }

        button {
            background-color: #78b64a;
            color: white;
            border: none;
            cursor: pointer;
        }

        a {
            text-decoration: none;
            margin: 5px;
        }

        .ringkasan {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            border: 1px solid #ccc;
            padding: 15px;
            flex: 1;
        }

        .grafik {
            width: 600px;
            max-width: 100%;
            margin-top: 25px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

<h2>Laporan Penjualan</h2>

<p>Login sebagai: <?php echo $_SESSION['nama']; ?></p>

<div class="no-print">
    <a href="home.php">Kembali ke Home</a>

    <h3>Filter Tanggal</h3>

    <form method="GET">
        <label>Tanggal Mulai:</label>
        <input type="date" name="tanggal_mulai"
            value="<?php echo $tanggal_mulai; ?>" required>

        <label>Tanggal Selesai:</label>
        <input type="date" name="tanggal_selesai"
            value="<?php echo $tanggal_selesai; ?>" required>

        <button type="submit">Tampilkan</button>
    </form>
</div>

<?php if ($tanggal_mulai != '' && $tanggal_selesai != '') { ?>

    <?php if ($tanggal_mulai > $tanggal_selesai) { ?>

        <p>Tanggal mulai tidak boleh melebihi tanggal selesai!</p>

    <?php } else { ?>

        <h3>Grafik Penjualan Harian</h3>

        <?php if (count($rekap) > 0) { ?>

            <div class="grafik">
                <canvas id="grafikPenjualan"></canvas>
            </div>

            <script>
                const ctx = document.getElementById('grafikPenjualan');

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?php echo json_encode($labels); ?>,
                        datasets: [{
                            label: 'Total Pendapatan',
                            data: <?php echo json_encode($data_total); ?>,
                            backgroundColor: '#6099ad'
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

        <?php } else { ?>

            <p>Tidak ada data penjualan pada rentang tanggal tersebut.</p>

        <?php } ?>

        <h3>Rekapitulasi Penjualan Harian</h3>

        <table>
            <tr>
                <th>No</th>
                <th>Total Pendapatan</th>
                <th>Tanggal Transaksi</th>
            </tr>

            <?php
            $no = 1;

            foreach ($rekap as $row) {
            ?>

            <tr>
                <td><?php echo $no++; ?></td>
                <td>
                    Rp <?php echo number_format(
                        $row['total_harian'], 0, ',', '.'
                    ); ?>
                </td>
                <td><?php echo $row['tanggal']; ?></td>
            </tr>

            <?php } ?>

        </table>

        <div class="ringkasan">

            <div class="card">
                <h4>Jumlah Pelanggan Unik</h4>
                <p><?php echo $total_pelanggan; ?> Orang</p>
            </div>

            <div class="card">
                <h4>Total Pendapatan</h4>
                <p>
                    Rp <?php echo number_format(
                        $total_pendapatan, 0, ',', '.'
                    ); ?>
                </p>
            </div>

        </div>

        <div class="no-print">
            <br>

            <button onclick="window.print()">Cetak</button>

            <a href="export.php?tanggal_mulai=<?php echo $tanggal_mulai; ?>&tanggal_selesai=<?php echo $tanggal_selesai; ?>">
                <button type="button">Ekspor Excel</button>
            </a>
        </div>

    <?php } ?>

<?php } ?>

</body>
</html>
