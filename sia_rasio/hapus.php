<?php
// 1. Koneksi ke Database
$koneksi = mysqli_connect("localhost", "root", "decade", "db_sia_rasio");

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. Tangkap ID dari URL (Pastikan ID berupa angka untuk keamanan)
$id = (int) $_GET['id'];

// 3. Jalankan Query Hapus
$query = "DELETE FROM data_keuangan WHERE id = $id";

if (mysqli_query($koneksi, $query)) {
    // Kalau sukses, langsung lempar balik ke dashboard
    header("Location: dashboard.php");
    exit();
} else {
    echo "Gagal menghapus data: " . mysqli_error($koneksi);
}

mysqli_close($koneksi);
?>