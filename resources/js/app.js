const angkaBerat = (nilai) => {
    const teks = String(nilai ?? '').replace(/\s/g, '');

    if (teks === '') {
        return 0;
    }

    if (teks.includes(',')) {
        return Number(teks.replace(/\./g, '').replace(',', '.')) || 0;
    }

    return Number(teks) || 0;
};

const angkaHarga = (nilai) => {
    const teks = String(nilai ?? '').replace(/\s/g, '').replace(/\./g, '');

    if (teks === '') {
        return 0;
    }

    return Number(teks.replace(',', '.')) || 0;
};

const rupiah = (nilai) =>
    'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(Math.round(nilai));

const form = document.querySelector('[data-setoran-form]');

if (form) {
    const jenis = document.getElementById('jenis_sampah');
    const berat = document.getElementById('berat');
    const harga = document.getElementById('harga_per_kg');
    const estimasi = document.getElementById('estimasi-nilai');
    const status = document.getElementById('status-simpan');
    const tombol = form.querySelector('[data-submit]');
    const labelTombol = form.querySelector('[data-submit-label]');
    const skeleton = document.getElementById('riwayat-loading');

    const hitungEstimasi = () => {
        if (!estimasi) {
            return;
        }

        estimasi.textContent = rupiah(angkaBerat(berat?.value) * angkaHarga(harga?.value));
    };

    jenis?.addEventListener('change', () => {
        const opsi = jenis.selectedOptions[0];
        const hargaAcuan = opsi?.dataset.harga;

        if (harga && hargaAcuan) {
            harga.value = hargaAcuan;
        }

        hitungEstimasi();
    });

    berat?.addEventListener('input', hitungEstimasi);
    harga?.addEventListener('input', hitungEstimasi);
    hitungEstimasi();

    form.addEventListener('submit', () => {
        if (!form.checkValidity()) {
            return;
        }

        form.setAttribute('aria-busy', 'true');

        if (tombol) {
            tombol.disabled = true;
        }

        if (labelTombol) {
            labelTombol.textContent = 'Menyimpan setoran...';
        }

        if (status) {
            status.textContent = 'Setoran sedang disimpan.';
        }

        if (skeleton) {
            skeleton.hidden = false;
        }
    });
}
