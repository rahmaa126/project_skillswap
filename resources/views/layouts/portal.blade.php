<!doctype html>
<html lang="id">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'SkillSwap Portal Pengguna')</title>

    <!-- Theme Init -->
    <script>
      (() => {
        'use strict';
        const root = document.documentElement;
        const STORAGE_KEY = 'lte-theme';
        let stored = null;
        try { stored = localStorage.getItem(STORAGE_KEY); } catch {}
        let resolved = stored || (globalThis.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        root.setAttribute('data-bs-theme', resolved);
      })();
    </script>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}" />

    @stack('styles')
  </head>
  <body class="bg-body-tertiary">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body shadow-sm sticky-top border-bottom py-2">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-4" href="{{ route('portal.dashboard') }}">
          <img src="{{ asset('adminlte/assets/img/AdminLTELogo.png') }}" alt="Logo" width="34" height="34" class="rounded shadow-sm">
          <span>Skill<span class="text-primary">Swap</span></span>
          <span class="badge bg-primary-subtle text-primary fs-7 ms-1">Pengguna</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#portalNavbar" aria-controls="portalNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="portalNavbar">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3 gap-1">
            <li class="nav-item">
              <a class="nav-link px-3 rounded {{ request()->routeIs('portal.dashboard') ? 'active fw-bold text-primary bg-primary-subtle' : '' }}" href="{{ route('portal.dashboard') }}">
                <i class="bi bi-speedometer2 me-1"></i> Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link px-3 rounded {{ request()->routeIs('portal.skills.*') ? 'active fw-bold text-primary bg-primary-subtle' : '' }}" href="{{ route('portal.skills.index') }}">
                <i class="bi bi-compass me-1"></i> Jelajah Skill
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link px-3 rounded {{ request()->routeIs('portal.my-skills.*') ? 'active fw-bold text-primary bg-primary-subtle' : '' }}" href="{{ route('portal.my-skills.index') }}">
                <i class="bi bi-stars me-1"></i> Skill Saya
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link px-3 rounded {{ request()->routeIs('portal.swaps.*') ? 'active fw-bold text-primary bg-primary-subtle' : '' }}" href="{{ route('portal.swaps.index') }}">
                <i class="bi bi-arrow-left-right me-1"></i> Sesi Swap
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link px-3 rounded {{ request()->routeIs('portal.battles.*') ? 'active fw-bold text-primary bg-primary-subtle' : '' }}" href="{{ route('portal.battles.index') }}">
                <i class="bi bi-lightning-charge me-1"></i> Skill Battles
              </a>
            </li>
          </ul>

          <div class="d-flex align-items-center gap-3">
            @auth
              <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle py-1 px-2 rounded hover-bg-light" data-bs-toggle="dropdown" aria-expanded="false">
                  <img
                    src="{{ Auth::user()->profile?->avatar_url ?? asset('adminlte/assets/img/avatar'.((Auth::id() % 4) + 1).'.png') }}"
                    alt="{{ Auth::user()->name }}"
                    width="36"
                    height="36"
                    class="rounded-circle border me-2"
                  />
                  <div class="d-none d-md-block text-start me-1">
                    <div class="fw-semibold lh-1">{{ Auth::user()->name }}</div>
                    <small class="badge {{ Auth::user()->isAdmin() ? 'text-bg-danger' : 'text-bg-primary' }} fs-8 mt-1">
                      {{ Auth::user()->isAdmin() ? 'Administrator' : 'Pengguna' }}
                    </small>
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                  <li class="px-3 py-2 border-bottom">
                    <span class="d-block small text-muted">Login sebagai</span>
                    <strong class="text-body">{{ Auth::user()->email }}</strong>
                  </li>
                  <li>
                    <a class="dropdown-item py-2" href="{{ route('portal.profile.edit') }}">
                      <i class="bi bi-person me-2 text-primary"></i> Edit Profil Saya
                    </a>
                  </li>
                  <li>
                    <a class="dropdown-item py-2" href="{{ route('portal.my-skills.index') }}">
                      <i class="bi bi-stars me-2 text-warning"></i> Kelola Skill Saya
                    </a>
                  </li>
                  @if(Auth::user()->isAdmin())
                    <li><hr class="dropdown-divider"></li>
                    <li>
                      <a class="dropdown-item py-2 text-danger fw-semibold" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-shield-lock me-2"></i> Masuk ke Panel Admin
                      </a>
                    </li>
                  @endif
                  <li><hr class="dropdown-divider"></li>
                  <li>
                    <form action="{{ route('logout') }}" method="POST">
                      @csrf
                      <button type="submit" class="dropdown-item py-2 text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                      </button>
                    </form>
                  </li>
                </ul>
              </div>
            @else
              <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-3">Login</a>
              <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-3">Daftar</a>
            @endauth
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
      <div class="container">
        <!-- Flash Alerts -->
        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif
        @if($errors->any())
          <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-circle-fill me-2"></i> Terdapat kesalahan input:</div>
            <ul class="mb-0 ps-3">
              @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
              @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        @yield('content')
      </div>
    </main>

    <!-- Footer -->
    <footer class="bg-body border-top py-4 mt-5">
      <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
        <div class="small text-secondary">
          &copy; {{ date('Y') }} <strong>SkillSwap</strong> &bull; Platform Pertukaran Skill & Pengetahuan Antar Pengguna.
        </div>
        <div class="small text-secondary d-flex gap-3">
          <a href="{{ url('/') }}" class="text-decoration-none text-secondary">Beranda</a>
          <a href="{{ route('portal.skills.index') }}" class="text-decoration-none text-secondary">Katalog Skill</a>
          @auth
            @if(Auth::user()->isAdmin())
              <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-danger fw-semibold">Admin Panel</a>
            @endif
          @endauth
        </div>
      </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    @stack('scripts')
  </body>
</html>
