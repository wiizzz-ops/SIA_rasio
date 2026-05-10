<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Analisis Rasio Keuangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Form Input Laporan Keuangan</h4>
                </div>
                <div class="card-body p-4">
                    <form action="proses.php" method="POST">
                        
                        <h5 class="mt-2 border-bottom pb-2">1. Identitas Perusahaan</h5>
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <label class="form-label text-muted fw-bold">Nama Perusahaan</label>
                                <input type="text" name="nama_perusahaan" class="form-control" required placeholder="Contoh: PT Maju Jaya">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted fw-bold">Tahun Periode</label>
                                <input type="number" name="tahun_periode" class="form-control" required placeholder="Contoh: 2026" min="2000" max="2100">
                            </div>
                        </div>

                        <h5 class="mt-4 border-bottom pb-2">2. Data Keuangan (dalam Rupiah)</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-bold">Aset Lancar</label>
                                <input type="number" name="aset_lancar" class="form-control" required min="0" placeholder="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-bold">Kewajiban Lancar</label>
                                <input type="number" name="kewajiban_lancar" class="form-control" required min="0" placeholder="0">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label text-muted fw-bold">Laba Bersih</label>
                                <input type="number" name="laba_bersih" class="form-control" required placeholder="0">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-bold">Total Aset</label>
                                <input type="number" name="total_aset" class="form-control" required min="0" placeholder="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-bold">Total Utang</label>
                                <input type="number" name="total_utang" class="form-control" required min="0" placeholder="0">
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">Hitung Rasio & Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>