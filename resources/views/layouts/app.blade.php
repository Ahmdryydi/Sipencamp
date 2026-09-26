<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Sipencamp - Admin Dashboard')</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.ico') }}">

  <!-- Local Third-Party Libraries -->
  <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/libs/apexcharts/apexcharts.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">

  <!-- Main Design System & Custom Stylesheet -->
  <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
  @stack('styles')
</head>

<body>

  <!-- Sidebar Component -->
  @include('partials.sidebar')

  <!-- Main Content Area -->
  <div class="main-wrapper">

    <!-- Top Navbar Component -->
    @include('partials.header')

    <!-- Dynamic Content -->
    @yield('content')

    <!-- Footer Component -->
    @include('partials.footer')

  </div>

  <!-- Local Third-Party Libraries Script dependencies -->
  <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>

  <!-- Local dashboard interactions controller -->
  <script src="{{ asset('assets/js/dashboard.js') }}"></script>
  @stack('scripts')
</body>

</html>