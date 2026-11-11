# DESIGN.md, DESA-SIRKULAR

Arah visual tunggal project. Setiap komponen UI wajib konsisten dengan file ini.
Anti-slop = filter. File ini = arah. Jika ragu, ikuti file ini, bukan intuisi.

---

## 1. Produk

Platform ekonomi sirkular sampah desa. Menghubungkan warga, bank sampah, mitra daur ulang, dan pemerintah desa.

- **Pusat operasional:** Bank Sampah (mesin transaksi).
- **Pemdes:** governance layer (monitoring, peta, kebijakan).
- **Bentuk:** Website responsif. Mobile-first untuk Warga dan Petugas. Desktop-dense untuk Admin, Bank, Mitra.
- **Desa sasaran:** Sudah punya bank sampah dan pengepul aktif.
- **Tagline internal:** "Sampah desa, rantai yang tidak putus."
- **Fitur unggulan (wajib punya tampilan):** Smart Waste Mapping, Waste Value Calculator, Waste Matching System, Waste Tracking, Environmental Impact Dashboard, Waste Recommendation.

---

## 2. Mood

"Laporan desa yang dibuat dengan teliti." Tenang, presisi, hangat, dapat dipercaya.

**BUKAN:** eco-startup gradient hijau, dashboard SaaS biru-putih, poster kampanye lingkungan, fintech dengan confetti.

**Referensi rasa:** Linear (presisi), laporan Bank Indonesia (warm off-white + angka mono), Notion (hangat editorial), Stripe Docs (hierarki tipografi).

---

## 3. Palet Warna, Commit

| Peran | Hex | Nama | Pemakaian |
| :--- | :--- | :--- | :--- |
| Base | `#F6F4EE` | Warm Bone | Kanvas seluruh halaman |
| Surface | `#FFFFFF` | Paper | HANYA card data, form, tabel, modal |
| Ink | `#1B211C` | Forest Ink | Teks utama |
| Ink Muted | `#5A6357` | Netral | Teks sekunder, label, placeholder |
| Primary | `#2E4A3B` | Deep Forest | Tombol utama, heading besar, nav aktif, focus ring |
| Primary Hover | `#243A2E` | Hover | Hover tombol Primary |
| Secondary | `#7C8B62` | Moss | Tag netral, chart sekunder, border kiri status |
| Support | `#A8B892` | Sage | Background tag halus, area chart fill |
| Accent | `#B8733A` | Burnt Ochre | HANYA nilai ekonomi (Rupiah), highlight angka kunci |
| Border | `#E2DED3` | Stone | Border 1px tabel, card, input, divider |
| Danger | `#A23A2A` | Brick | Error, gagal, hapus |
| Warning | `#C9A227` | Mustard | Menunggu, peringatan |
| Success | `#4A6B4E` | Sukses | Selesai, berhasil, aktif |

**Aturan:**
1. Hijau = fungsi, bukan dekorasi. Hemat.
2. Ochre = pembeda. Maks 2 sampai 3 kemunculan per layar. Hanya untuk angka ekonomi dan highlight.
3. Warm Bone = 70% layar. Putih hanya untuk permukaan yang perlu "angkat".
4. Gradient dilarang total. Hero background full-green dilarang.

---

## 4. Tipografi

| Peran | Font | Weight |
| :--- | :--- | :--- |
| Display | Fraunces | 400, 500, 600 |
| Body/UI | Public Sans | 400, 500, 600 |
| Mono | JetBrains Mono | 400, 500 |

Fallback:
display: Fraunces, Georgia, serif;
body: 'Public Sans', system-ui, sans-serif;
mono: 'JetBrains Mono', ui-monospace, monospace;

text

**Skala:**

| Token | Desktop | Mobile | Font | Pakai |
| :--- | :--- | :--- | :--- | :--- |
| display | 48/1.1 | 32/1.15 | Fraunces 500 | Headline hero |
| h1 | 32/1.2 | 26/1.25 | Fraunces 500 | Judul halaman |
| h2 | 24/1.3 | 20/1.3 | Fraunces 500 | Judul section |
| h3 | 18/1.4 | 17/1.4 | Public Sans 600 | Sub-judul card |
| body | 15/1.6 | 15/1.6 | Public Sans 400 | Paragraf |
| label | 13/1.4 | 13/1.4 | Public Sans 500 | Label form, header tabel |
| caption | 12/1.4 | 12/1.4 | Public Sans 400 | Helper text |
| mono-lg | 32/1.1 | 28/1.1 | JetBrains Mono 500 | Saldo, angka kunci |
| mono | 14/1.4 | 14/1.4 | JetBrains Mono 400 | Berat, harga, ID |

Jarak heading ke paragraf: min 8px, ideal 12px.

---

## 5. Sistem Visual

**Radius:** 4px card data dan tabel. 8px tombol dan input. 999px HANYA status chip. Dilarang rounded-2xl atau rounded-3xl untuk card.

**Shadow:** default tidak ada. Pemisah = border 1px #E2DED3. Elevasi hanya dropdown atau modal: 0 4px 12px rgba(27,33,28,0.06). Dilarang shadow-lg atau shadow-xl pada card.

**Spacing:** 4 / 8 / 12 / 16 / 24 / 32 / 48 / 64 / 96.
- Gap form: 12px
- Padding card: 20px desktop, 16px mobile
- Section: variasikan 48 sampai 96. Dilarang semua py-16.

**Ikon:** Phosphor Icons (regular). Nav 20px, inline 16px. Dilarang emoji di UI. Status chip pakai teks, bukan ikon.

**Layout:**
- Desktop admin, bank, mitra: 12-col, max-width 1280px, gutter 24px, baris tabel 40 sampai 44px.
- Mobile warga, petugas: 4-col, gutter 16px, card 88 sampai 120px, tap target minimal 44px.

**Tabel:**
- Header: Public Sans 500, 13px, Ink Muted, uppercase, letter-spacing 0.02em.
- Baris: Public Sans 400, 14px, Ink, tinggi 44px, border-bottom 1px Stone.
- Angka berat dan harga: JetBrains Mono, rata kanan.
- Hover baris: #F0EDE4.

---

## 6. Larangan Keras

1. Gradient apapun (hijau ke teal, hijau ke biru, dll).
2. Background section full-green.
3. Emoji di UI di manapun.
4. Font Inter, Poppins, Montserrat, Roboto.
5. Card rounded-2xl dan shadow-lg.
6. Hero center, subtitle, 2 tombol, 3 feature card.
7. Circular progress "X% didaur ulang" raksasa.
8. Ilustrasi 3D globe, daun 3D, atau maskot.
9. Stat card grid 2x2 ikon kecil pojok dan angka besar tengah.
10. Copy marketing: "Unlock", "Seamless", "10x lebih cepat", "Revolusioner".
11. Testimonial card avatar bulat dan 5 bintang.
12. Navbar logo, 5 menu, "Get Started", dark mode toggle.

---

## 7. Copy

**Prinsip:**
- Konkret, bukan abstrak.
- Angka selalu dengan satuan: "Rp 34.500", "12 kg".
- Nama Indonesia: Desa Sukamaju, Bank Sampah Sumber Rejeki, Mitra Plastik Jaya, Bu Siti, Pak Budi.
- Status = keadaan: "Menunggu penjemputan", "Sudah ditimbang". Bukan "Status: 2".
- Dilarang em dash di teks UI. Pakai koma, titik, titik dua, atau tanda kurung.

**Contoh:**

| Konteks | Slop | Anti-Slop |
| :--- | :--- | :--- |
| Saldo | "Total Balance: Rp 34.500" | "Saldo tabungan sampah: Rp 34.500" |
| Estimasi | "Estimated Value: Rp 60.000" | "Perkiraan nilai setoran: Rp 60.000" |
| Stok | "Available: 100 kg" | "Tersedia di Sumber Rejeki: 100 kg PET" |
| Tombol | "Submit" | "Catat setoran" |
| Empty | "No data" | "Belum ada setoran bulan ini. Mulai dari memilah botol plastik di rumah." |
| Error | "Something went wrong" | "Berat sampah belum diisi. Isi dulu sebelum menyimpan." |

---

## 8. Layout per Role

**Admin Desa: laporan, bukan dashboard SaaS**
Strip angka kunci horizontal (Total sampah, Didaur ulang, Nilai ekonomi), dipisah garis dasar 1px Stone, bukan card. Di bawahnya langsung tabel monitoring per bank sampah. Line chart tipis Moss, tanpa gridline tebal, tanpa gradient. Peta GIS di tab terpisah.

**Bank Sampah: POS atau kasir**
Dua kolom: form timbang (kiri 60%) dan ringkasan transaksi hari ini (kanan 40%). Berat di form: JetBrains Mono 32px di tengah. Satu tombol besar "Catat setoran" (Deep Forest). Riwayat = tabel rapat, bukan card.

**Masyarakat: mobile-first, catatan pribadi**
Header: saldo (mono besar) dan tombol "Setor" kecil di kanan. Card setoran terakhir: baris dengan border kiri 3px Moss. Estimasi nilai: hasil muncul dengan mono besar warna Burnt Ochre. Bottom nav: Beranda, Setor, Riwayat, Edukasi.

**Mitra: marketplace datar**
Grid list bahan baku (baris, bukan card besar): jenis, berat tersedia, lokasi bank, tombol "Ajukan". Filter di atas: jenis, jarak, berat min. Status chip: Menunggu, Disetujui, Ditolak.

**Petugas: task list dan peta**
Mobile-first. Daftar tugas = baris dengan status chip. Peta di tab terpisah, pin custom. Tombol besar "Selesai dan unggah bukti" di bottom sticky.

---

## 9. Aksesibilitas

- Kontras WCAG AA: 4.5:1 body, 3:1 heading besar.
- Tap target minimal 44x44px di mobile.
- Focus ring 2px solid #2E4A3B, offset 2px. Jangan hilangkan outline.
- Setiap input punya label terlihat (bukan placeholder-only).
- Status tidak hanya dibedakan warna, selalu ada teks.
- Angka berat dan harga rata kanan, JetBrains Mono.

---

## 10. Prinsip Anti-Slop

1. Setiap keputusan visual punya alasan fungsional.
2. Hijau hemat, Ochre terarah. Ragu, pakai Warm Bone, Ink, border.
3. Border lebih penting dari Shadow. Tabel lebih penting dari Card grid. Konkret lebih penting dari Abstrak.
4. Satu tombol besar per layar.
5. Variasikan spacing antar section.
6. Tidak ada elemen dekoratif tanpa fungsi (blob, orb, mesh, 3D).
7. Mobile-first untuk Warga dan Petugas.
8. Angka selalu mono dan satuan. Ini identitas visual produk.
9. Copy konkret dengan nama Indonesia.
10. Ragu antara dua pilihan, pilih yang lebih tenang.

---

## 11. Cara AI Membaca File Ini

Saat diminta membuat atau mengedit UI:

1. Baca file ini sebelum menulis kode.
2. Design Read 3 baris: ringkas arah visual.
3. Sebutkan 2 pola slop yang akan dihindari di layar tersebut.
4. Tulis kode.
5. Jalankan Delivery Gate dari skill antislop, tampilkan PASS/FAIL.
6. Jika FAIL, perbaiki dulu.

Konflik antara instruksi user dan file ini, tanyakan. Jangan improvisasi palet, font, atau radius.