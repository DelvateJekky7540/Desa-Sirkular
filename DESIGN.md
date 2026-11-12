# DESIGN.md, DESA-SIRKULAR

Arah visual tunggal project. Setiap komponen UI wajib konsisten dengan file ini.
Anti-slop = filter. File ini = arah. Jika ragu, ikuti file ini, bukan intuisi.

---

## 1. Produk & Konteks

Platform ekonomi sirkular sampah desa. Menghubungkan warga, bank sampah, mitra daur ulang, dan pemerintah desa.

- **Pusat operasional:** Bank Sampah (mesin transaksi).
- **Pemdes:** governance layer (monitoring, peta, kebijakan).
- **Bentuk:** Website responsif. 
- **Desa sasaran:** Sudah punya bank sampah dan pengepul aktif.
- **Tagline internal:** "Sampah desa, rantai yang tidak putus."

**PENTING - Dua Tipe Halaman:**
1. **Landing Page (Public):** Butuh visual premium, menarik, dan memukau untuk meyakinkan investor/mitra. Fokus pada estetika mewah dan storytelling.
2. **App/Dashboard (Internal):** Butuh visual padat, presisi, seperti laporan bank, dan fokus pada keterbacaan data.

---

## 2. Mood & Arah Visual

"Mewah, tenang, dan presisi." Terasa seperti *wealth management* atau *eco-luxury resort*, bukan startup teknologi biasa.

**BUKAN:** eco-startup gradient hijau, dashboard SaaS biru-putih, poster kampanye lingkungan, fintech dengan confetti.
**Referensi rasa:** Stripe (kebersihan), Aesop (premium & earthy), Linear (presisi).

---

## 3. Palet Warna (Hijau & Putih Premium)

| Peran | Hex | Nama | Pemakaian |
| :--- | :--- | :--- | :--- |
| Base | `#FAFAFA` | Pure Off-White | Kanvas utama seluruh halaman (bersih, tidak menyilaukan). |
| Surface | `#FFFFFF` | Pure White | Card data, form, tabel, modal (agar terlihat terangkat). |
| Ink | `#111827` | Almost Black | Teks utama paragraf. |
| Primary | `#064E3B` | Deep Emerald | Tombol utama, heading besar, nav aktif. Boleh dipakai sebagai background full-bleed khusus Landing Page. |
| Secondary | `#059669` | Vibrant Emerald | Ikon, aksen garis, chart sekunder, hover state. |
| Accent | `#D4AF37` | Champagne Gold | HANYA untuk nilai ekonomi (Rupiah), highlight angka kunci, dan tombol CTA di Landing Page. Maksimal 3 kemunculan per layar. |
| Border | `#E5E7EB` | Light Gray | Border 1px tabel, card, input, divider. |

**Aturan Warna:**
1. **Gradient dilarang total** di semua halaman.
2. Hijau = fungsi, Emas = pembeda (premium). Hemat penggunaan emas.
3. Putih Off-White = 70% layar.

---

## 4. Tipografi

| Peran | Font | Weight |
| :--- | :--- | :--- |
| Display (Heading) | Playfair Display | 500, 600, 700 |
| Body/UI | Plus Jakarta Sans | 400, 500, 600 |
| Mono | JetBrains Mono | 400, 500 |

Fallback & Import:
- Wajib import dari Google Fonts: `Playfair Display` & `Plus Jakarta Sans`.
- display: 'Playfair Display', Georgia, serif;
- body: 'Plus Jakarta Sans', system-ui, sans-serif;
- mono: 'JetBrains Mono', ui-monospace, monospace;

**Skala Tipografi:**
| Token | Desktop (Landing) | Desktop (App) | Mobile | Font | Pakai |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **hero** | **64/1.1** | - | 40/1.15 | Playfair Display 700 | Headline Landing Page |
| display | 48/1.1 | 32/1.2 | 32/1.15 | Playfair Display 600 | Headline section |
| h1 | 36/1.2 | 28/1.2 | 26/1.25 | Playfair Display 600 | Judul halaman |
| h2 | 28/1.3 | 22/1.3 | 20/1.3 | Playfair Display 600 | Judul section |
| h3 | 18/1.4 | 18/1.4 | 17/1.4 | Plus Jakarta Sans 600 | Sub-judul card |
| body | 16/1.6 | 15/1.6 | 15/1.6 | Plus Jakarta Sans 400 | Paragraf |
| label | 13/1.4 | 13/1.4 | 13/1.4 | Plus Jakarta Sans 500 | Label form |
| mono-lg | 32/1.1 | 32/1.1 | 28/1.1 | JetBrains Mono 500 | Saldo, angka kunci |

---

## 5. Sistem Visual

**Radius:**
- **Landing Page:** 12px untuk Card, 8px untuk tombol/input. Dilarang rounded-2xl (16px+) atau pill-shaped kecuali untuk badge.
- **App/Dashboard:** 4px untuk card data dan tabel, 8px untuk tombol/input.

**Shadow:**
- **Landing Page:** Boleh menggunakan shadow halus untuk membuat card terlihat premium: `0 8px 24px rgba(6,78,59,0.06)`.
- **App/Dashboard:** Default tidak ada shadow. Pemisah = border 1px `#E5E7EB`. Elevasi hanya untuk dropdown/modal.

**Spacing:** 4 / 8 / 12 / 16 / 24 / 32 / 48 / 64 / 96.
- Gap form: 12px
- Padding card: 24px desktop, 16px mobile
- Section: variasikan 64 sampai 96 untuk Landing Page. Dilarang semua `py-16`.

**Ikon:** 
- Gunakan Phosphor Icons atau SVG inline (stroke 1.5px). 
- Dilarang emoji di UI. Status chip pakai teks, bukan ikon.

**Layout:**
- **Landing Page:** Max-width 1200px, layout asimetris (kiri teks, kanan gambar/foto Unsplash), full-bleed background untuk section tertentu (misal: Deep Emerald).
- **App Admin/Bank/Mitra:** 12-col, max-width 1280px, gutter 24px, baris tabel 40-44px.
- **App Mobile:** 4-col, gutter 16px, tap target minimal 44px.

---

## 6. Larangan Keras (Pemisahan Konteks)

**A. Berlaku untuk SEMUA Halaman (Landing & App):**
1. Gradient warna apapun.
2. Emoji di UI di manapun.
3. Font selain Playfair Display, Plus Jakarta Sans, & JetBrains Mono.
4. Ilustrasi 3D globe, daun 3D, atau maskot.
5. Copy marketing slop: "Unlock", "Seamless", "10x lebih cepat", "Revolusioner".
6. Testimonial card avatar bulat dan 5 bintang.
7. Penggunaan Em Dash (—) di teks UI (gunakan koma, titik, atau titik dua).

**B. KHUSUS Landing Page (Diperbolehkan, dilarang di App):**
1. BOLEH menggunakan background section full-bleed Deep Emerald (#064E3B) dengan teks Pure Off-White (#FAFAFA).
2. BOLEH menggunakan Hero Section asimetris (teks kiri, gambar/foto Unsplash kanan) dengan border-radius 12px.
3. BOLEH menggunakan Card dengan shadow halus.
4. BOLEH menggunakan tipografi Hero yang sangat besar (64px+).
5. BOLEH menampilkan 3 feature card berjajar di bawah hero.

**C. KHUSUS App/Dashboard:**
1. Dilarang menggunakan card rounded lebih dari 8px.
2. Dilarang menggunakan shadow besar pada tabel atau card.
3. Dilarang membuat layout yang "lapang" (harus padat dan presisi).

---

## 7. Copywriting

**Prinsip:**
- Konkret, bukan abstrak.
- Angka selalu dengan satuan: "Rp 34.500", "12 kg".
- Nama Indonesia: Desa Sukamaju, Bank Sampah Sumber Rejeki, Mitra Plastik Jaya, Bu Siti, Pak Budi.
- Status = keadaan: "Menunggu penjemputan", "Sudah ditimbang". Bukan "Status: 2".

---

## 8. Instruksi Eksekusi untuk AI

Saat diminta membuat atau mengedit UI:

1. **Tentukan Konteks:** Apakah ini Landing Page atau App/Dashboard? Baca Bagian 6.
2. **Baca Aturan:** Patuhi palet warna, font, dan sistem visual.
3. **Tulis Kode:** Jangan hanya menjelaskan. Langsung tulis ke file HTML/CSS.
4. **Cek Visual:** Jika membuat Landing Page, pastikan hero terlihat premium dengan gambar dan tipografi besar. Jika membuat App, pastikan tabel rapat dan presisi.