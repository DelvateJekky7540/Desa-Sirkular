<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="DESA-SIRKULAR: Platform ekonomi sirkular sampah desa. Menghubungkan masyarakat, bank sampah, mitra daur ulang, dan pemerintah desa dalam satu ekosistem terintegrasi.">
  <title>DESA-SIRKULAR | Ekonomi Sirkular Sampah Desa</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
  <!-- HEADER -->
  <header class="header" role="banner">
    <div class="container header-inner">
      <a href="#" class="logo" aria-label="DESA-SIRKULAR Beranda">DESA-SIRKULAR</a>
      <nav class="nav" aria-label="Navigasi utama">
        <a href="#pilar">Pilar</a>
        <a href="#inovasi">Inovasi</a>
        <a href="#alur">Alur Kerja</a>
      </nav>
      <a href="#kontak" class="btn btn-primary header-cta">Jelajahi Platform</a>
      <button class="mobile-menu-btn" aria-label="Buka menu" aria-expanded="false">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
      </button>
    </div>
  </header>

  <main>
    <!-- HERO SECTION -->
    <section class="hero" aria-labelledby="hero-title">
      <div class="hero-bg" aria-hidden="true">
        <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=2500" alt="Tangan memegang tanah subur, melambangkan pengelolaan sumber daya alam" fetchpriority="high" width="2500" height="1600">
      </div>
      <div class="hero-overlay" aria-hidden="true"></div>
      <div class="hero-content">
        <div class="container hero-inner">
          <div class="hero-text">
            <h1 id="hero-title" class="hero-title">Membangun Masa Depan Desa dengan Ekonomi Sirkular</h1>
            <p class="hero-subtitle">Platform terintegrasi yang menghubungkan Masyarakat, Bank Sampah, Mitra Daur Ulang, dan Pemerintah Desa. Sampah desa, rantai yang tidak putus.</p>
            <div class="hero-cta">
              <a href="#pilar" class="btn btn-primary">Jelajahi Platform</a>
            </div>
            <div class="hero-stats" aria-label="Statistik platform">
              <div class="stat-item">
                <div class="stat-value mono-lg">340+</div>
                <div class="stat-label">Keluarga Terhubung</div>
              </div>
              <div class="stat-item">
                <div class="stat-value mono-lg">87%</div>
                <div class="stat-label">Sampah Terolah</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 4 PILAR EKOSISTEM -->
    <section id="pilar" class="section section-alt" aria-labelledby="pilar-title">
      <div class="container">
        <div class="pillars-section">
          <header class="pillars-header">
            <h2 id="pilar-title" class="section-title">Empat Pilar Ekosistem</h2>
            <p class="section-subtitle">Setiap pilar berperan vital. Tanpa satu, rantai putus. Bersama, mereka menciptakan ekonomi sirkular yang berkelanjutan.</p>
          </header>
          <div class="pillars-list">
            <!-- Pilar 1: Masyarakat -->
            <article class="pillar-item">
              <svg class="pillar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
              <div class="pillar-content">
                <h3 class="pillar-title">Masyarakat</h3>
                <p class="pillar-desc">Menyetorkan sampah terpilah, mendapatkan saldo tabungan, dan meminta penjemputan jadwal.</p>
              </div>
            </article>
            <!-- Pilar 2: Bank Sampah -->
            <article class="pillar-item">
              <svg class="pillar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22v-8"></path><path d="M8 18l4-4 4 4"></path><path d="M12 14v-6"></path><path d="M4 22h16"></path><path d="M8 14a4 4 0 0 1 8 0"></path></svg>
              <div class="pillar-content">
                <h3 class="pillar-title">Bank Sampah</h3>
                <p class="pillar-desc">Menimbang, memilah, mencatat transaksi, dan mengelola stok bahan baku siap daur ulang.</p>
              </div>
            </article>
            <!-- Pilar 3: Mitra Daur Ulang -->
            <article class="pillar-item">
              <svg class="pillar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg>
              <div class="pillar-content">
                <h3 class="pillar-title">Mitra Daur Ulang</h3>
                <p class="pillar-desc">Membeli bahan baku terpilah, memproses menjadi produk baru, menutup lingkaran ekonomi.</p>
              </div>
            </article>
            <!-- Pilar 4: Pemerintah Desa -->
            <article class="pillar-item">
              <svg class="pillar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
              <div class="pillar-content">
                <h3 class="pillar-title">Pemerintah Desa</h3>
                <p class="pillar-desc">Monitoring real-time, laporan dampak, pemetaan GIS bank sampah, dan kebijakan berbasis data.</p>
              </div>
            </article>
          </div>
        </div>
      </div>
    </section>

    <!-- INOVASI UNGGULAN -->
    <section id="inovasi" class="section section-dark" aria-labelledby="inovasi-title">
      <div class="container">
        <header class="section-header">
          <h2 id="inovasi-title" class="section-title">Bukan Sekadar Pencatatan Sampah</h2>
          <p class="section-subtitle">Tiga inovasi inti yang mengubah bank sampah dari pencatat manual menjadi mesin pertukaran nilai otomatis.</p>
        </header>
        <div class="features-bento">
          <!-- Feature 1: Waste Matching System - Primary (spans 2 cols, 2 rows on desktop) -->
          <article class="feature-card feature-card--primary">
            <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
            <h3 class="feature-title">Waste Matching System</h3>
            <p class="feature-desc">Pertemukan stok sampah bank sampah dengan kebutuhan bahan baku mitra secara otomatis. Algoritma pencocokan real-time menghilangkan pencarian manual.</p>
          </article>
          <!-- Feature 2: Waste Value Calculator -->
          <article class="feature-card feature-card--secondary">
            <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
            <h3 class="feature-title">Waste Value Calculator</h3>
            <p class="feature-desc">Hitung estimasi nilai <span class="feature-highlight">Rp 34.500/kg</span> dari berat sampah secara real-time. Transparansi harga untuk warga dan mitra.</p>
          </article>
          <!-- Feature 3: Waste Tracking -->
          <article class="feature-card feature-card--secondary">
            <svg class="feature-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
            <h3 class="feature-title">Waste Tracking</h3>
            <p class="feature-desc">Lacak perjalanan sampah dari sumbernya hingga menjadi produk baru. Audit trail lengkap untuk laporan ESG dan sertifikasi.</p>
          </article>
        </div>
      </div>
    </section>

    <!-- ALUR KERJA -->
    <section id="alur" class="section section-alt" aria-labelledby="alur-title">
      <div class="container">
        <header class="section-header">
          <h2 id="alur-title" class="section-title">Alur Kerja</h2>
          <p class="section-subtitle">Dari rumah warga hingga laporan pemerintahan. Empat langkah, satu platform terintegrasi.</p>
        </header>
        <div class="timeline">
          <div class="timeline-item">
            <span class="timeline-number" aria-hidden="true">1</span>
            <div class="timeline-content">
              <h3 class="timeline-title">Warga Memilah & Menyetor</h3>
              <p class="timeline-desc">Warga memilah sampah di rumah, menyetor ke Bank Sampah, atau meminta penjemputan via aplikasi. Setiap kilogram dicatat otomatis.</p>
            </div>
          </div>
          <div class="timeline-item">
            <span class="timeline-number" aria-hidden="true">2</span>
            <div class="timeline-content">
              <h3 class="timeline-title">Bank Sampah Menimbang & Mencatat</h3>
              <p class="timeline-desc">Petugas menimbang, memvalidasi jenis, dan memasukkan ke sistem. Saldo warga bertambah instan. Stok bahan baku ter-update real-time.</p>
            </div>
          </div>
          <div class="timeline-item">
            <span class="timeline-number" aria-hidden="true">3</span>
            <div class="timeline-content">
              <h3 class="timeline-title">Sistem Mencocokkan dengan Mitra</h3>
              <p class="timeline-desc">Algoritma matching menghubungkan stok bank sampah dengan pesanan mitra daur ulang. Penawaran harga transparan, deal instan.</p>
            </div>
          </div>
          <div class="timeline-item">
            <span class="timeline-number" aria-hidden="true">4</span>
            <div class="timeline-content">
              <h3 class="timeline-title">Mitra Mengambil, Dampak Tercatat</h3>
              <p class="timeline-desc">Mitra mengambil bahan baku, sistem mencatat CO2 terselamatkan, nilai ekonomi, dan volume. Laporan siap untuk Pemdes & ESG.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- FOOTER -->
  <footer id="kontak" class="footer" role="contentinfo">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <a href="#" class="logo" aria-label="DESA-SIRKULAR Beranda">DESA-SIRKULAR</a>
          <p class="footer-tagline">Inovasi Pengelolaan Sampah Desa Berbasis Ekonomi Sirkular.</p>
        </div>
        <nav aria-label="Navigasi Produk">
          <h3 class="footer-title">Produk</h3>
          <div class="footer-links">
            <a href="#pilar">Pilar Ekosistem</a>
            <a href="#inovasi">Inovasi Unggulan</a>
            <a href="#alur">Alur Kerja</a>
          </div>
        </nav>
        <nav aria-label="Navigasi Perusahaan">
          <h3 class="footer-title">Perusahaan</h3>
          <div class="footer-links">
            <a href="#">Tentang Kami</a>
            <a href="#">Karir</a>
            <a href="#">Blog</a>
          </div>
        </nav>
      </div>
      <div class="footer-bottom">
        <p class="footer-copyright">&copy; 2026 DESA-SIRKULAR. Hak cipta dilindungi.</p>
        <p class="footer-credit">Dibangun untuk masa depan desa yang berkelanjutan.</p>
      </div>
    </div>
  </footer>

  <script>
    // Mobile menu toggle
    const menuBtn = document.querySelector('.mobile-menu-btn');
    const nav = document.querySelector('.nav');
    menuBtn.addEventListener('click', () => {
      const isOpen = menuBtn.getAttribute('aria-expanded') === 'true';
      menuBtn.setAttribute('aria-expanded', !isOpen);
      nav.style.display = isOpen ? 'none' : 'flex';
      nav.style.position = 'absolute';
      nav.style.top = '72px';
      nav.style.left = '0';
      nav.style.right = '0';
      nav.style.background = 'var(--base)';
      nav.style.flexDirection = 'column';
      nav.style.padding = '24px';
      nav.style.borderBottom = '1px solid var(--border)';
      nav.style.gap = '16px';
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          e.preventDefault();
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          if (nav.style.display === 'flex') {
            nav.style.display = 'none';
            menuBtn.setAttribute('aria-expanded', 'false');
          }
        }
      });
    });

    // Header scroll effect
    let lastScroll = 0;
    window.addEventListener('scroll', () => {
      const header = document.querySelector('.header');
      const currentScroll = window.pageYOffset;
      if (currentScroll > 100) {
        header.style.boxShadow = '0 4px 20px rgba(6,78,59,0.08)';
      } else {
        header.style.boxShadow = 'none';
      }
      lastScroll = currentScroll;
    });
  </script>
</body>
</html>