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

const beratTerbaca = (nilai) =>
    new Intl.NumberFormat('id-ID', { maximumFractionDigits: 2 }).format(nilai);

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

const bukaDialogTimbang = (dialog) => {
    if (!dialog || typeof dialog.showModal !== 'function') {
        return;
    }

    dialog.showModal();
    dialog.querySelector('[data-berat]')?.focus();
};

document.querySelectorAll('[data-buka-timbang]').forEach((tombol) => {
    tombol.addEventListener('click', () => {
        bukaDialogTimbang(document.getElementById(tombol.dataset.bukaTimbang));
    });
});

const dialogOtomatis = document.querySelector('[data-buka-otomatis]');

if (dialogOtomatis) {
    bukaDialogTimbang(document.getElementById(dialogOtomatis.dataset.bukaOtomatis));
}

document.querySelectorAll('dialog[id^="dialog-timbang-"]').forEach((dialog) => {
    dialog.querySelectorAll('[data-tutup-timbang]').forEach((tombol) => {
        tombol.addEventListener('click', () => dialog.close());
    });

    dialog.addEventListener('click', (event) => {
        if (event.target === dialog) {
            dialog.close();
        }
    });
});

document.querySelectorAll('[data-form-timbang]').forEach((form) => {
    const berat = form.querySelector('[data-berat]');
    const harga = form.querySelector('[data-harga]');
    const total = form.querySelector('[data-total]');
    const selisih = form.querySelector('[data-selisih]');
    const tercatat = Number(form.dataset.beratTercatat || 0);
    const tombol = form.querySelector('[data-simpan]');
    const label = form.querySelector('[data-simpan-label]');

    const hitung = () => {
        const nilai = angkaBerat(berat?.value) * angkaHarga(harga?.value);

        if (total) {
            total.textContent = rupiah(nilai);
        }

        if (selisih) {
            const beda = angkaBerat(berat?.value) - tercatat;

            if (Math.abs(beda) < 0.005) {
                selisih.textContent = 'Sama dengan berat tercatat.';
            } else {
                const arah = beda > 0 ? '+' : '-';
                selisih.textContent = `Selisih ${arah}${beratTerbaca(Math.abs(beda))} kg dari berat tercatat.`;
            }
        }
    };

    berat?.addEventListener('input', () => {
        berat.removeAttribute('aria-invalid');
        hitung();
    });

    harga?.addEventListener('input', hitung);
    hitung();

    form.addEventListener('submit', () => {
        if (!form.checkValidity()) {
            return;
        }

        form.setAttribute('aria-busy', 'true');

        if (tombol) {
            tombol.disabled = true;
        }

        if (label) {
            label.textContent = 'Menyimpan hasil timbang...';
        }
    });
});

const kalkulatorWarga = document.querySelector('[data-estimasi-warga]');

if (kalkulatorWarga) {
    const jenis = kalkulatorWarga.querySelector('[data-jenis-sampah]');
    const berat = kalkulatorWarga.querySelector('[data-berat]');
    const hargaTerpilih = kalkulatorWarga.querySelector('[data-harga-terpilih]');
    const total = kalkulatorWarga.querySelector('[data-estimasi-angka]');
    const rincian = kalkulatorWarga.querySelector('[data-rincian-estimasi]');
    let jedaHitung;

    const hitung = () => {
        const opsi = jenis?.selectedOptions?.[0];
        const harga = angkaHarga(opsi?.dataset.harga ?? 0);
        const satuan = opsi?.dataset.satuan || 'kg';
        const nilaiBerat = angkaBerat(berat?.value);
        const nilai = nilaiBerat * harga;
        const adaJenis = Boolean(opsi?.value);
        const adaBerat = nilaiBerat > 0;

        if (hargaTerpilih) {
            hargaTerpilih.textContent = adaJenis
                ? `Harga acuan: ${rupiah(harga)}/${satuan}`
                : 'Pilih jenis untuk melihat harga acuan.';
        }

        if (total) {
            total.textContent = rupiah(nilai);
            total.dataset.aktif = adaJenis && adaBerat ? 'true' : 'false';
        }

        if (rincian) {
            if (!adaJenis) {
                rincian.textContent = 'Pilih jenis sampah dulu.';
            } else if (!adaBerat) {
                rincian.textContent = 'Isi berat untuk melihat perkiraan.';
            } else {
                rincian.textContent = `${beratTerbaca(nilaiBerat)} ${satuan} x ${rupiah(harga)}/${satuan}`;
            }
        }
    };

    jenis?.addEventListener('change', hitung);

    berat?.addEventListener('input', () => {
        clearTimeout(jedaHitung);
        jedaHitung = setTimeout(hitung, 150);
    });

    hitung();
}

const formPengajuan = document.querySelector('[data-pengajuan-form]');

if (formPengajuan) {
    const tombol = formPengajuan.querySelector('[data-submit]');
    const label = formPengajuan.querySelector('[data-submit-label]');
    const status = formPengajuan.querySelector('[data-status-pengajuan]');

    formPengajuan.addEventListener('submit', () => {
        if (!formPengajuan.checkValidity()) {
            return;
        }

        formPengajuan.setAttribute('aria-busy', 'true');

        if (tombol) {
            tombol.disabled = true;
        }

        if (label) {
            label.textContent = 'Mengirim pengajuan...';
        }

        if (status) {
            status.textContent = 'Pengajuan sedang dikirim.';
        }
    });
}
