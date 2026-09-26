@extends('layouts.app')

@section('title', 'Dashboard - Sipencamp')

@section('content')
<!-- Dashboard Header Banner -->
<div class="page-header">
  <div>
    <h1 class="page-title">Dashboard Admin</h1>
    <p class="page-subtitle">Kelola penyewaan peralatan camping dan paket rekomendasi AI dengan muah.</p>
  </div>
  <button class="btn-date-picker" type="button" id="date-picker-trigger">
    <i class="bi bi-calendar4-event"></i>
    <span id="selected-date-range">{{ date('F d, Y') }}</span>
    <i class="bi bi-chevron-down ms-1"></i>
  </button>
</div>

<!-- Main Layout Grid -->
<div class="row g-4">
  <!-- Stat Cards Row -->
  <div class="col-12">
    <div class="row g-4">
      <!-- Stat Card 1 -->
      <div class="col-md-4">
        <div class="card alert-green-card">
          <div class="position-relative z-index-2">
            <span class="alert-green-badge">Sipencamp AI</span>
            <div class="alert-green-date">{{ date('d M Y') }}</div>
            <div class="alert-green-text">Fitur Rekomendasi Paket AI Siap Digunakan</div>
          </div>
          <a href="#" class="alert-green-link z-index-2">
            <span>Lihat Paket AI</span>
            <i class="bi bi-arrow-right"></i>
          </a>
          <svg class="alert-green-bg-shape" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g transform="translate(50,50)">
              <rect x="-6" y="-45" width="12" height="90" rx="6" ry="6" fill="#B4F105" />
              <rect x="-6" y="-45" width="12" height="90" rx="6" ry="6" fill="#B4F105" transform="rotate(60)" />
              <rect x="-6" y="-45" width="12" height="90" rx="6" ry="6" fill="#B4F105" transform="rotate(120)" />
            </g>
          </svg>
        </div>
      </div>

      <!-- Stat Card 2: Total Pendapatan -->
      <div class="col-md-4">
        <div class="card card-stat d-flex flex-column justify-content-between">
          <div>
            <div class="card-header">
              <span class="stat-label">Total Penyewaan</span>
            </div>
            <div class="stat-value">Rp 0</div>
            <div class="trend-badge trend-up">
              <i class="bi bi-arrow-up-right"></i>
              <span>Transaksi Aktif</span>
            </div>
          </div>
          <div class="sparkline-container sparkline-card-footer">
            <div id="income-sparkline"></div>
          </div>
        </div>
      </div>

      <!-- Stat Card 3: Peralatan Disewa -->
      <div class="col-md-4">
        <div class="card card-stat d-flex flex-column justify-content-between">
          <div>
            <div class="card-header">
              <span class="stat-label">Peralatan Tersewa</span>
            </div>
            <div class="stat-value">0 Item</div>
            <div class="trend-badge trend-down">
              <i class="bi bi-box-seam"></i>
              <span>Stok Alat</span>
            </div>
          </div>
          <div class="sparkline-container sparkline-card-footer">
            <div id="return-sparkline"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection