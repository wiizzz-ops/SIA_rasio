<?php
// 1. Koneksi ke Database
$host = "localhost";
$user = "root"; // Default Laragon/XAMPP
$pass = "decade";     // Default Laragon/XAMPP biasanya kosong
$db   = "db_sia_rasio";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// 2. Tangkap data dari form (index.php)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_perusahaan  = $_POST['nama_perusahaan'];
    $tahun_periode    = $_POST['tahun_periode'];
    $aset_lancar      = $_POST['aset_lancar'];
    $kewajiban_lancar = $_POST['kewajiban_lancar'];
    $laba_bersih      = $_POST['laba_bersih'];
    $total_aset       = $_POST['total_aset'];
    $total_utang      = $_POST['total_utang'];

    // 3. Perhitungan Rasio & Pencegahan Division by Zero
    
    // a. Current Ratio
    if ($kewajiban_lancar > 0) {
        $current_ratio = $aset_lancar / $kewajiban_lancar;
    } else {
        $current_ratio = 0; // Hindari error pembagian dengan 0
    }

    // b. Return on Assets (ROA)
    if ($total_aset > 0) {
        $roa = $laba_bersih / $total_aset;
    } else {
        $roa = 0;
    }

    // c. Debt to Asset Ratio
    if ($total_aset > 0) {
        $debt_ratio = $total_utang / $total_aset;
    } else {
        $debt_ratio = 0;
    }

    // 4. Fitur Tambahan: Interpretasi Hasil 
    // Logika sederhana: 
    // Current Ratio bagus jika > 1.5, ROA bagus jika > 0.05 (5%), Debt Ratio bagus jika < 0.5 (50%)
    $status_likuiditas   = ($current_ratio > 1.5) ? "Baik" : "Kurang Baik";
    $status_profitabilitas = ($roa > 0.05) ? "Baik" : "Kurang Baik";
    $status_solvabilitas = ($debt_ratio < 0.5) ? "Baik" : "Kurang Baik";

    // 5. Simpan ke Database
    $query = "INSERT INTO data_keuangan 
              (nama_perusahaan, tahun_periode, aset_lancar, kewajiban_lancar, laba_bersih, total_aset, total_utang, current_ratio, roa, debt_ratio) 
              VALUES 
              ('$nama_perusahaan', '$tahun_periode', '$aset_lancar', '$kewajiban_lancar', '$laba_bersih', '$total_aset', '$total_utang', '$current_ratio', '$roa', '$debt_ratio')";

    if (mysqli_query($koneksi, $query)) {
        // Jika sukses, kita tampilkan hasilnya langsung pakai Bootstrap
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <title>Hasil Analisis</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body class="bg-light mt-5">
            <div class="container">
                <div class="alert alert-success text-center">
                    <h4>✅ Data Berhasil Disimpan & Dihitung!</h4>
                </div>
                
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-dark text-white">
                        <h5>Hasil Analisis Rasio: <?php echo $nama_perusahaan; ?> (<?php echo $tahun_periode; ?>)</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead class="table-primary">
                                <tr>
                                    <th>Jenis Rasio</th>
                                    <th>Nilai Rasio</th>
                                    <th>Interpretasi Sistem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>Current Ratio</strong> (Likuiditas)</td>
                                    <td><?php echo number_format($current_ratio, 2); ?></td>
                                    <td><span class="badge <?php echo ($status_likuiditas == 'Baik') ? 'bg-success' : 'bg-danger'; ?>"><?php echo $status_likuiditas; ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Return on Asset / ROA</strong> (Profitabilitas)</td>
                                    <td><?php echo number_format($roa, 4); ?></td>
                                    <td><span class="badge <?php echo ($status_profitabilitas == 'Baik') ? 'bg-success' : 'bg-danger'; ?>"><?php echo $status_profitabilitas; ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Debt to Asset Ratio</strong> (Solvabilitas)</td>
                                    <td><?php echo number_format($debt_ratio, 2); ?></td>
                                    <td><span class="badge <?php echo ($status_solvabilitas == 'Baik') ? 'bg-success' : 'bg-danger'; ?>"><?php echo $status_solvabilitas; ?></span></td>
                                </tr>
                            </tbody>
                        </table>
                        <a href="index.php" class="btn btn-secondary mt-3">Kembali ke Form</a>
                        <a href="dashboard.php" class="btn btn-primary mt-3">Lihat Grafik & Semua Data</a>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
    }

    mysqli_close($koneksi);
}
?>
