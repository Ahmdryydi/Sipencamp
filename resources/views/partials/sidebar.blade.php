<div class="sidebar-wrapper" id="sidebar">
  <!-- Brand Logo / Identity -->
  <a href="{{ route('dashboard') }}" class="sidebar-brand">
    <i class="bi bi-asterisk"></i>
    <span>Sipencamp</span>
  </a>

  <!-- Navigation Menu -->
  <div class="flex-grow-1 overflow-y-auto">
    <!-- Group: Menu Utama -->
    <div class="sidebar-menu-section">
      <div class="sidebar-menu-title">Main Menu</div>
      <ul class="sidebar-menu-list">
        <li class="sidebar-menu-item">
          <a href="{{ route('dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" id="menu-overview">
            <i class="bi bi-grid-fill"></i>
            <span>Dashboard</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- Group: Data Master -->
    <div class="sidebar-menu-section">
      <div class="sidebar-menu-title">Master Data</div>
      <ul class="sidebar-menu-list">
        <li class="sidebar-menu-item">
          <a href="{{ route('kategori.index') }}" class="sidebar-menu-link">
            <i class="bi bi-tags"></i>
            <span>Kategori</span>
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="{{ route('peralatan.index') }}" class="sidebar-menu-link">
            <i class="bi bi-box-seam"></i>
            <span>Peralatan</span>
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="{{ route('paket.index') }}" class="sidebar-menu-link">
            <i class="bi bi-box"></i>
            <span>Paket Sewa</span>
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="#" class="sidebar-menu-link">
            <i class="bi bi-stars"></i>
            <span>Rekomendasi Paket AI</span>
          </a>
        </li>
      </ul>
    </div>

    <!-- Group: Transaksi -->
    <div class="sidebar-menu-section">
      <div class="sidebar-menu-title">Transaksi</div>
      <ul class="sidebar-menu-list">
        <li class="sidebar-menu-item">
          <a href="{{ route('penyewaan.index') }}" class="sidebar-menu-link">
            <i class="bi bi-cart-check"></i>
            <span>Penyewaan</span>
          </a>
        </li>
        <li class="sidebar-menu-item">
          <a href="{{ route('pembayaran.index') }}" class="sidebar-menu-link">
            <i class="bi bi-wallet2"></i>
            <span>Pembayaran</span>
          </a>
        </li>
      </ul>
    </div>
  </div>

  <!-- Sidebar Profile Card -->
  <div class="sidebar-profile">
    <img src="{{ asset('assets/images/avatar.png') }}" alt="Administrator" class="sidebar-profile-img"
      onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop'">
    <div class="sidebar-profile-info">
      <div class="sidebar-profile-name">Administrator</div>
      <div class="sidebar-profile-email">admin@sipencamp.com</div>
    </div>
  </div>
</div>