<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel - CarsDekho</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <style>
    .sidebar {
      min-height: 100vh;
      background: #2c3e50;
      position: fixed;
      top: 0;
      left: 0;
      width: 250px;
      z-index: 1000;
      padding-top: 20px;
    }

    .sidebar .nav-link {
      color: #ecf0f1;
      padding: 12px 20px;
      border-left: 3px solid transparent;
      transition: all 0.3s;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
      background: #34495e;
      border-left-color: #3498db;
      color: white;
    }

    .sidebar .nav-link i {
      width: 20px;
      margin-right: 10px;
      text-align: center;
    }

    .main-content {
      margin-left: 250px;
      padding: 20px;
    }

    .navbar-admin {
      background: white;
      box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
      margin-left: 250px;
    }

    .stat-card {
      border-radius: 10px;
      border: none;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s;
    }

    .stat-card:hover {
      transform: translateY(-5px);
    }

    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
        min-height: auto;
      }

      .main-content,
      .navbar-admin {
        margin-left: 0;
      }
    }
  </style>
</head>

<body>
  <!-- Sidebar -->
  <nav class="sidebar d-none d-md-block">
    <div class="p-3">
      <h5 class="text-white">
        <i class="fas fa-car"></i> CarsDekho Admin
      </h5>
      <p class="text-muted small mb-0">Welcome, {{ Auth::user()->name }}</p>
    </div>

    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}"
          href="{{ route('admin.dashboard') }}">
          <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ request()->is('admin/header*') ? 'active' : '' }}" href="{{ route('admin.header') }}">
          <i class="fas fa-heading"></i> Header
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ request()->is('admin/banners*') ? 'active' : '' }}" href="{{ route('admin.banners') }}">
          <i class="fas fa-image"></i> Banners
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ request()->is('admin/most-searched-cars*') ? 'active' : '' }}"
          href="{{ route('admin.most-searched-cars') }}">
          <i class="fas fa-search"></i> Most Searched Cars
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ request()->is('admin/latest-cars*') ? 'active' : '' }}"
          href="{{ route('admin.latest-cars') }}">
          <i class="fas fa-car"></i> Latest Cars
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ request()->is('admin/customers*') ? 'active' : '' }}"
          href="{{ route('admin.customers') }}">
          <i class="fas fa-users"></i> Customers
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link {{ request()->is('admin/footer*') ? 'active' : '' }}" href="{{ route('admin.footer') }}">
          <i class="fas fa-shoe-prints"></i> Footer
        </a>
      </li>
      <li class="nav-item mt-4">
        <a class="nav-link" href="{{ route('home') }}">
          <i class="fas fa-globe"></i> View Website
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}"
          onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
          @csrf
        </form>
      </li>
    </ul>
  </nav>

  <!-- Top Navigation (Mobile) -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm d-md-none">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <i class="fas fa-car"></i> Admin
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mobileNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('home') }}">View Site</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="main-content">
    <div class="container-fluid">
      @yield('content')
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>

</html>