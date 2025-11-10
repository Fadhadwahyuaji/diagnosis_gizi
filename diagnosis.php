<?php
require_once 'templates/header.php'; ?>

<div class="card shadow-sm">
    <div class="card-header">
        <h4 class="mb-0">PENENTUAN BERAT BADAN IDEAL DAN REKOMENDASI NUTRISI SEIMBANG DEWASA 19-25 TAHUN</h4>
    </div>
    <div class="card-body">
        <form action="proses_diagnosis.php" method="POST">

            <div class="row">
                <div class="mb-3">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                        placeholder="Nama Lengkap Anda" required>
                    <small class="form-text text-muted">Masukkan nama lengkap untuk identifikasi hasil diagnosis</small>
                </div>

                <div class="col-md-6">

                    <div class="mb-3">
                        <label for="berat_badan" class="form-label">Berat Badan (Kg)</label>
                        <input type="number" step="0.1" class="form-control" id="berat_badan" name="berat_badan"
                            placeholder="Contoh: 70" required>
                    </div>

                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="perempuan">perempuan</option>
                            <option value="laki-laki">laki-laki</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="jenis_olahraga" class="form-label">Jenis Olahraga (pilih salah satu jika
                            ada)</label>
                        <select class="form-select" id="jenis_olahraga" name="jenis_olahraga">
                            <option value="G13">Tidak ada</option>
                            <option value="G10">Ringan (jalan santai, yoga)</option>
                            <option value="G11">Sedang (jogging, bersepeda)</option>
                            <option value="G12">Berat (lari cepat, angkat beban)</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tinggi_badan" class="form-label">Tinggi Badan (cm)</label>
                        <input type="number" step="0.1" class="form-control" id="tinggi_badan" name="tinggi_badan"
                            placeholder="Contoh: 170" required>
                    </div>

                    <div class="mb-3">
                        <label for="frekuensi_olahraga" class="form-label">Frekuensi Olahraga</label>
                        <select class="form-select" id="frekuensi_olahraga" name="frekuensi_olahraga" required>
                            <option value="G05">Tidak Pernah</option>
                            <option value="G06">Jarang (1x/minggu)</option>
                            <option value="G07">Cukup (2-3x/minggu)</option>
                            <option value="G08">Sering (4-5x/minggu)</option>
                            <option value="G09">Rutin (>=6x/minggu)</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="mb-3">
                <h5>Kebiasaan makan (pilih salah satu untuk setiap kategori)</h5>

                <div class="mt-3">
                    <label class="form-label fw-bold">1. Konsumsi Buah</label>
                    <div class="d-flex flex-wrap">
                        <div class="form-check me-3"><input class="form-check-input" type="checkbox" name="makan[]"
                                value="buah_setiap_hari" id="buah1" data-group="buah"><label class="form-check-label"
                                for="buah1">setiap
                                hari</label></div>
                        <div class="form-check me-3"><input class="form-check-input" type="checkbox" name="makan[]"
                                value="buah_3-6" id="buah2" data-group="buah"><label class="form-check-label"
                                for="buah2">3-6x/minggu</label></div>
                        <div class="form-check me-3"><input class="form-check-input" type="checkbox" name="makan[]"
                                value="buah_1-2" id="buah3" data-group="buah"><label class="form-check-label"
                                for="buah3">1-2x/minggu</label></div>
                        <div class="form-check me-3"><input class="form-check-input" type="checkbox" name="makan[]"
                                value="G15" id="buah4" data-group="buah"><label class="form-check-label"
                                for="buah4">Jarang/Tidak
                                Pernah</label></div>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label fw-bold">2. Konsumsi Sayur</label>
                    <div class="d-flex flex-wrap">
                        <div class="form-check me-3"><input class="form-check-input" type="checkbox" name="makan[]"
                                value="sayur_setiap_hari" id="sayur1" data-group="sayur"><label class="form-check-label"
                                for="sayur1">setiap hari</label></div>
                        <div class="form-check me-3"><input class="form-check-input" type="checkbox" name="makan[]"
                                value="sayur_3-6" id="sayur2" data-group="sayur"><label class="form-check-label"
                                for="sayur2">3-6x/minggu</label></div>
                        <div class="form-check me-3"><input class="form-check-input" type="checkbox" name="makan[]"
                                value="sayur_1-2" id="sayur3" data-group="sayur"><label class="form-check-label"
                                for="sayur3">1-2x/minggu</label></div>
                        <div class="form-check me-3"><input class="form-check-input" type="checkbox" name="makan[]"
                                value="G17" id="sayur4" data-group="sayur"><label class="form-check-label"
                                for="sayur4">Jarang/Tidak
                                Pernah</label></div>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label fw-bold">3. Konsumsi Fast Food</label>
                    <div class="d-flex flex-wrap">
                        <div class="form-check me-3"><input class="form-check-input" type="checkbox" name="makan[]"
                                value="G18_setiap_hari" id="ff1" data-group="fastfood"><label class="form-check-label"
                                for="ff1">setiap
                                hari</label></div>
                        <div class="form-check me-3"><input class="form-check-input" type="checkbox" name="makan[]"
                                value="G18_3-6" id="ff2" data-group="fastfood"><label class="form-check-label"
                                for="ff2">3-6x/minggu</label>
                        </div>
                        <div class="form-check me-3"><input class="form-check-input" type="checkbox" name="makan[]"
                                value="G18_1-2" id="ff3" data-group="fastfood"><label class="form-check-label"
                                for="ff3">1-2x/minggu</label>
                        </div>
                        <div class="form-check me-3"><input class="form-check-input" type="checkbox" name="makan[]"
                                value="ff_jarang" id="ff4" data-group="fastfood"><label class="form-check-label"
                                for="ff4">Jarang/Tidak
                                Pernah</label></div>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label fw-bold">4. Konsumsi Minuman Manis</label>
                    <div class="d-flex flex-wrap">
                        <div class="form-check me-3"><input class="form-check-input" type="checkbox" name="makan[]"
                                value="G19_setiap_hari" id="mm1" data-group="minuman"><label class="form-check-label"
                                for="mm1">setiap
                                hari</label></div>
                        <div class="form-check me-3"><input class="form-check-input" type="checkbox" name="makan[]"
                                value="G19_3-6" id="mm2" data-group="minuman"><label class="form-check-label"
                                for="mm2">3-6x/minggu</label>
                        </div>
                        <div class="form-check me-3"><input class="form-check-input" type="checkbox" name="makan[]"
                                value="G19_1-2" id="mm3" data-group="minuman"><label class="form-check-label"
                                for="mm3">1-2x/minggu</label>
                        </div>
                        <div class="form-check me-3"><input class="form-check-input" type="checkbox" name="makan[]"
                                value="mm_jarang" id="mm4" data-group="minuman"><label class="form-check-label"
                                for="mm4">Jarang/Tidak
                                Pernah</label></div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="mb-3">
                <h5>Riwayat penyakit (centang jika ada)</h5>
                <div class="form-check"><input class="form-check-input" type="checkbox" name="penyakit[]" value="G20"
                        id="p1"><label class="form-check-label" for="p1">Tidak ada</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" name="penyakit[]" value="G21"
                        id="p2"><label class="form-check-label" for="p2">Diabetes</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" name="penyakit[]" value="G22"
                        id="p3"><label class="form-check-label" for="p3">Hipertensi</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" name="penyakit[]" value="G23"
                        id="p4"><label class="form-check-label" for="p4">Kolestrol Tinggi</label></div>
                <div class="form-check"><input class="form-check-input" type="checkbox" name="penyakit[]" value="G24"
                        id="p5"><label class="form-check-label" for="p5">penyakit pencernaan</label></div>
                <small class="form-text text-muted">jika tidak ada penyakit, centang 'Tidak ada' untuk memastikan input
                    jelas</small>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-lg">Hitung & Tampilkan Rekomendasi</button>
            </div>
            <p class="text-center mt-3 text-muted"><small>catatan: Hasil bersifat edukatif dan bukan pengganti
                    konsultasi tenaga kesehatan</small></p>

        </form>
    </div>
</div>

<script>
    // Script untuk membatasi checkbox agar hanya bisa pilih 1 per kategori
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil semua checkbox pada kebiasaan makan
        const checkboxesMakan = document.querySelectorAll('input[name="makan[]"]');

        checkboxesMakan.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    const group = this.getAttribute('data-group');

                    // Uncheck checkbox lain dalam group yang sama
                    checkboxesMakan.forEach(function(otherCheckbox) {
                        if (otherCheckbox.getAttribute('data-group') === group &&
                            otherCheckbox !== checkbox) {
                            otherCheckbox.checked = false;
                        }
                    });
                }
            });
        });
    });
</script>

<?php require_once 'templates/footer.php'; ?>