<?php
// filepath: d:\PROYEK\diagnosis_gizi\diagnosis.php
require_once 'templates/umum/header.php';
?>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">
            <i class="bi bi-clipboard2-pulse"></i>
            PENENTUAN BERAT BADAN IDEAL DAN REKOMENDASI NUTRISI SEIMBANG DEWASA 19-25 TAHUN
        </h4>
    </div>
    <div class="card-body">
        <form action="proses_diagnosis.php" method="POST">

            <!-- BAGIAN 1: DATA DIRI -->
            <h5 class="text-primary mb-3"><i class="bi bi-person-fill"></i> Data Diri</h5>

            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap"
                    placeholder="Nama Lengkap Anda" required>
                <small class="form-text text-muted">Masukkan nama lengkap untuk identifikasi hasil diagnosis</small>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="berat_badan" class="form-label">Berat Badan (Kg) <span
                                class="text-danger">*</span></label>
                        <input type="number" step="0.1" class="form-control" id="berat_badan" name="berat_badan"
                            placeholder="Contoh: 70" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tinggi_badan" class="form-label">Tinggi Badan (cm) <span
                                class="text-danger">*</span></label>
                        <input type="number" step="0.1" class="form-control" id="tinggi_badan" name="tinggi_badan"
                            placeholder="Contoh: 170" required>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <!-- BAGIAN 2: AKTIVITAS FISIK -->
            <h5 class="text-primary mb-3"><i class="bi bi-activity"></i> Aktivitas Fisik & Olahraga</h5>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="frekuensi_olahraga" class="form-label">Frekuensi Olahraga <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="frekuensi_olahraga" name="frekuensi_olahraga" required>
                            <option value="">-- Pilih Frekuensi --</option>
                            <option value="G05">Tidak Pernah</option>
                            <option value="G06">Jarang (1 kali/minggu)</option>
                            <option value="G07">Cukup (2-3 kali/minggu)</option>
                            <option value="G08">Sering (4-5 kali/minggu)</option>
                            <option value="G09">Rutin (≥6 kali/minggu)</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="jenis_olahraga" class="form-label">Jenis Olahraga <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="jenis_olahraga" name="jenis_olahraga" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="G13">Tidak ada</option>
                            <option value="G10">Ringan (jalan santai, yoga)</option>
                            <option value="G11">Sedang (jogging, bersepeda)</option>
                            <option value="G12">Berat (lari cepat, angkat beban)</option>
                        </select>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <!-- BAGIAN 3: KEBIASAAN MAKAN -->
            <h5 class="text-primary mb-3"><i class="bi bi-egg-fried"></i> Kebiasaan Makan</h5>

            <div class="mb-3">
                <label class="form-label d-block">Konsumsi Buah</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pola_buah" id="buah1" value="buah_setiap_hari">
                    <label class="form-check-label" for="buah1">
                        Sering (≥2 porsi/hari)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pola_buah" id="buah2" value="buah_jarang">
                    <label class="form-check-label" for="buah2">
                        Jarang (<3 kali/minggu) </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pola_buah" id="buah3" value="buah_cukup" checked>
                    <label class="form-check-label" for="buah3">
                        Cukup (3-7 kali/minggu)
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label d-block">Konsumsi Sayur</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pola_sayur" id="sayur1"
                        value="sayur_setiap_hari">
                    <label class="form-check-label" for="sayur1">
                        Sering (>3 porsi/hari)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pola_sayur" id="sayur2" value="sayur_jarang">
                    <label class="form-check-label" for="sayur2">
                        Jarang (<3 kali/minggu) </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pola_sayur" id="sayur3" value="sayur_cukup"
                        checked>
                    <label class="form-check-label" for="sayur3">
                        Cukup (3-7 kali/minggu)
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Kebiasaan Lainnya (bisa pilih lebih dari 1)</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="makan[]" value="fastfood_sering" id="ff1">
                    <label class="form-check-label" for="ff1">
                        Sering makan fast food (>3x/minggu)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="makan[]" value="minuman_manis_sering"
                        id="mm1">
                    <label class="form-check-label" for="mm1">
                        Sering minum minuman manis (>1x/hari)
                    </label>
                </div>
            </div>

            <hr class="my-4">

            <!-- BAGIAN 4: RIWAYAT PENYAKIT -->
            <h5 class="text-primary mb-3"><i class="bi bi-clipboard2-pulse"></i> Riwayat Kesehatan</h5>

            <div class="mb-3">
                <label class="form-label">Riwayat Penyakit (bisa pilih lebih dari 1)</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="penyakit[]" value="G20" id="p0" checked>
                    <label class="form-check-label" for="p0">
                        Tidak ada riwayat penyakit kronis
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="penyakit[]" value="G21" id="p1">
                    <label class="form-check-label" for="p1">
                        Diabetes
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="penyakit[]" value="G22" id="p2">
                    <label class="form-check-label" for="p2">
                        Hipertensi (Tekanan darah tinggi)
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="penyakit[]" value="G23" id="p3">
                    <label class="form-check-label" for="p3">
                        Kolesterol Tinggi
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="penyakit[]" value="G24" id="p4">
                    <label class="form-check-label" for="p4">
                        Penyakit Pencernaan (Maag)
                    </label>
                </div>
            </div>

            <hr class="my-4">

            <!-- TOMBOL SUBMIT -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-search"></i> Diagnosa Sekarang
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    // Auto uncheck "Tidak ada penyakit" jika pilih penyakit lain
    document.querySelectorAll('input[name="penyakit[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const tidakAda = document.getElementById('p0');
            if (this.value !== 'G20' && this.checked) {
                tidakAda.checked = false;
            }
        });
    });

    // Pastikan radio button buah dan sayur sebagai array
    document.querySelectorAll('input[name="pola_buah"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Hapus semua yang lama
            document.querySelectorAll('input[name="makan[]"][value^="buah"]').forEach(el => el.remove());

            // Tambah hidden input baru
            if (this.value !== 'buah_cukup') {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'makan[]';
                hidden.value = this.value;
                this.form.appendChild(hidden);
            }
        });
    });

    document.querySelectorAll('input[name="pola_sayur"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('input[name="makan[]"][value^="sayur"]').forEach(el => el.remove());

            if (this.value !== 'sayur_cukup') {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'makan[]';
                hidden.value = this.value;
                this.form.appendChild(hidden);
            }
        });
    });
</script>

<?php require_once 'templates/umum/footer.php'; ?>