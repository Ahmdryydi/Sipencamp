<div class="sidebar-wrapper" id="sidebar">
  <!-- Brand Logo / Identity -->
  <a href="{{ route('dashboard') }}" class="sidebar-brand">
    <i class="bi bi-asterisk"></i>
    <span>SiPENCAMP</span>
  </a>

  <!-- Navigation Menu -->
  <div class="flex-grow-1 overflow-y-auto">
    <!-- Group: Menu Utama -->
    <div class="sidebar-menu-section">
      <div class="sidebar-menu-title">Menu</div>
      <ul class="sidebar-menu-list">
        <!-- 1. Dashboard -->
                <li class="sidebar-menu-item">
                    <a href="{{ route('dashboard') }}" class="sidebar-menu-link" id="menu-overview">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- 2. Kategori -->
                <li class="sidebar-menu-item">
                    <a href="{{ route('kategori.index') }}" class="sidebar-menu-link">
                        <i class="bi bi-tag"></i>
                        <span>Kategori</span>
                    </a>
                </li>

                <!-- 3. Peralatan -->
                <li class="sidebar-menu-item">
                    <a href="{{ route('peralatan.index') }}" class="sidebar-menu-link">
                        <i class="bi bi-backpack"></i>
                        <span>Peralatan</span>
                    </a>
                </li>

                <!-- 4. Paket Sewa -->
                <li class="sidebar-menu-item">
                    <a href="{{ route('paket.index') }}" class="sidebar-menu-link">
                        <i class="bi bi-box"></i>
                        <span>Paket Sewa</span>
                    </a>
                </li>

                <!-- 5. Rekomendasi Paket AI -->
                <li class="sidebar-menu-item">
                    <a href="{{ route('rekomendasi.index') }}" class="sidebar-menu-link">
                        <i class="bi bi-stars"></i>
                        <span>Rekomendasi AI</span>
                    </a>
                </li>

                <!-- 6. Laporan -->
                <li class="sidebar-menu-item">
                    <a href="{{ route('laporan.index') }}" class="sidebar-menu-link">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        <span>Laporan</span>
                    </a>
                </li>

                <!-- 7. Penyewaan -->
                <li class="sidebar-menu-item">
                    <a href="{{ route('penyewaan.index') }}" class="sidebar-menu-link">
                        <i class="bi bi-cart-check"></i>
                        <span>Penyewaan</span>
                    </a>
                </li>

                <!-- 8. Pembayaran -->
                <li class="sidebar-menu-item">
                    <a href="{{ route('pembayaran.index') }}" class="sidebar-menu-link">
                        <i class="bi bi-wallet2"></i>
                        <span>Pembayaran</span>
                    </a>
                </li>

                <!-- 9. Ulasan -->
                <li class="sidebar-menu-item">
                    <a href="{{ route('ulasan.index') }}" class="sidebar-menu-link">
                        <i class="bi bi-chat-square-text"></i>
                        <span>Ulasan</span>
                    </a>
                </li>
      </ul>
    </div>
  </div>

  <!-- Sidebar Profile Card -->
  {{-- <div class="sidebar-profile">
    <img src="{{ asset('assets/images/avatar.png') }}" alt="Administrator" class="sidebar-profile-img"
      onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop'">
    <div class="sidebar-profile-info">
      <div class="sidebar-profile-name">Administrator</div>
      <div class="sidebar-profile-email">admin@sipencamp.com</div>
    </div>
  </div> --}}
</div>