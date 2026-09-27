<header class="navbar-custom">
  <div class="navbar-left">
    <button class="btn-desktop-toggle d-none d-xl-flex align-items-center justify-content-center me-3" id="desktop-sidebar-toggle" aria-label="Minimize Sidebar">
      <i class="bi bi-chevron-bar-left"></i>
    </button>
    <button class="sidebar-toggle-btn me-2" id="sidebar-toggle" aria-label="Toggle Navigation">
      <i class="bi bi-list"></i>
    </button>

    <!-- Quick Actions Dropdown -->
    {{-- <div class="dropdown ms-2">
      <button class="btn-quick-action dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="quick-actions-dropdown">
        <i class="bi bi-plus-lg"></i>
        <span>Tambah</span>
      </button>
      <ul class="dropdown-menu dropdown-menu-quick-action" aria-labelledby="quick-actions-dropdown">
        <li class="dropdown-header">Aksi Cepat</li>
        <li><a class="dropdown-item" href="#"><i class="bi bi-box-seam"></i> Tambah Peralatan</a></li>
        <li><a class="dropdown-item" href="#"><i class="bi bi-cart-plus"></i> Transaksi Baru</a></li>
      </ul>
    </div> --}}
  </div>

  <!-- Search Input -->
  <div class="navbar-search-wrapper">
    <input type="text" class="navbar-search-input" placeholder="Cari di Sipencamp..." id="main-search">
    <button class="navbar-search-btn" aria-label="Search">
      <i class="bi bi-search"></i>
    </button>
  </div>

  <!-- Right Actions -->
  <div class="navbar-actions">
    <button class="navbar-action-btn me-1" aria-label="Toggle Fullscreen" id="btn-fullscreen">
      <i class="bi bi-arrows-fullscreen"></i>
    </button>

    <!-- Profile Dropdown -->
    <div class="dropdown ms-2">
      <button class="navbar-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="profile-dropdown">
        <img src="{{ asset('assets/images/avatar.png') }}" alt="Profile Image" class="navbar-profile-img">
        <span class="navbar-profile-name d-none d-md-inline">Administrator</span>
        <i class="bi bi-chevron-down navbar-profile-caret"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-profile" aria-labelledby="profile-dropdown">
        <li class="dropdown-header">Selamat Datang!</li>
        <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Profil Saya</a></li>
        <li><a class="dropdown-item" href="{{ route('user.index') }}"><i class="bi bi-gear"></i> Pengaturan</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right"></i> Keluar</a></li>
      </ul>
    </div>
  </div>
</header>