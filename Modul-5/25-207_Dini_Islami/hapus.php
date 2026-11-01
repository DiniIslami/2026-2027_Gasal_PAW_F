
<?php
include 'koneksi.php';
$id = $_GET['id'];

$query = mysqli_query($koneksi, "DELETE FROM supplier
    WHERE id_supplier = '$id'");

if ($query) {
    header("Location: index.php");
    exit;
} else {
    echo "Gagal menghapus data!";
}
?>
