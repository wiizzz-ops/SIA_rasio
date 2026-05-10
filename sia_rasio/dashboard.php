<?php
// 1. Koneksi ke Database
$koneksi = mysqli_connect("localhost", "root", "decade", "db_sia_rasio");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// 2. Ambil data dari tabel untuk ditampilkan dan dibuat grafik
// Urutkan berdasarkan tahun agar grafik dari kiri ke kanan (tahun lama ke baru)
$query = mysqli_query($koneksi, "SELECT * FROM data_keuangan ORDER BY tahun_periode ASC");

// Siapkan array kosong (keranjang) untuk menampung data grafik
$label_tahun = [];
$data_current_ratio = [];
$data_roa = [];
$data_debt_ratio = [];

while ($row = mysqli_fetch_assoc($query)) {
    // Masukkan data ke masing-masing array
    $label_tahun[] = $row['tahun_periode'] . " (" . $row['nama_perusahaan'] . ")";
    $data_current_ratio[] = $row['current_ratio'];
    $data_roa[] = $row['roa'];
    $data_debt_ratio[] = $row['debt_ratio'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Analisis Rasio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light mt-4 mb-5">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dashboard Kinerja Keuangan</h2>
        <a href="index.php" class="btn btn-primary">+ Tambah Data Baru</a>
    </div>

    <div class="card shadow-sm mb-5">
        <div class="card-header bg-white">
            <h5 class="m-0">Grafik Tren Rasio Keuangan</h5>
        </div>
        <div class="card-body">
            <canvas id="grafikRasio" height="100"></canvas>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="m-0">Riwayat Perhitungan</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped m-0">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Perusahaan</th>
                            <th>Tahun</th>
                            <th>Current Ratio</th>
                            <th>ROA</th>
                            <th>Debt Ratio</th>
                            <th>Tanggal Input</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Kembalikan pointer query ke awal untuk dilooping lagi di tabel
                        mysqli_data_seek($query, 0);
                        $no = 1;
                        while ($baris = mysqli_fetch_assoc($query)) { ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $baris['nama_perusahaan']; ?></td>
                                <td><?= $baris['tahun_periode']; ?></td>
                                <td><?= number_format($baris['current_ratio'], 2); ?></td>
                                <td><?= number_format($baris['roa'], 4); ?></td>
                                <td><?= number_format($baris['debt_ratio'], 2); ?></td>
                                <td><?= $baris['created_at']; ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $baris['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="hapus.php?id=<?= $baris['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin data ini mau dihapus?');">Hapus</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('grafikRasio').getContext('2d');
    
    // PHP membuang data array ke JavaScript menggunakan json_encode
    const chartRasio = new Chart(ctx, {
        type: 'line', // Jenis grafik: garis
        data: {
            labels: <?= json_encode($label_tahun); ?>, // Sumbu X (Bawah)
            datasets: [
                {
                    label: 'Current Ratio (Likuiditas)',
                    data: <?= json_encode($data_current_ratio); ?>, // Sumbu Y
                    borderColor: 'rgba(54, 162, 235, 1)', // Warna Biru
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 2,
                    tension: 0.3 // Bikin garisnya agak melengkung (nggak kaku)
                },
                {
                    label: 'ROA (Profitabilitas)',
                    data: <?= json_encode($data_roa); ?>,
                    borderColor: 'rgba(75, 192, 192, 1)', // Warna Hijau
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.3
                },
                {
                    label: 'Debt Ratio (Solvabilitas)',
                    data: <?= json_encode($data_debt_ratio); ?>,
                    borderColor: 'rgba(255, 99, 132, 1)', // Warna Merah
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 2,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true // Sumbu Y dimulai dari angka 0
                }
            }
        }
    });
</script>

</body>
</html>