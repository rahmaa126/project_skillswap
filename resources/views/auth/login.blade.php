<!doctype html>
<html lang="id">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <title>Login | SkillSwap</title>

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
  </head>
  <body class="login-page bg-body-secondary d-flex align-items-center justify-content-center min-vh-100 py-4">
    <div class="login-box" style="width: 440px; max-width: 92%;">
      <!-- Card -->
      <div class="card card-outline card-primary shadow-lg border-0 rounded-4 overflow-hidden">
        <div class="card-header text-center py-4 border-0 bg-transparent">
          <a href="{{ url('/') }}" class="text-decoration-none d-flex align-items-center justify-content-center gap-2 mb-1">
            <img src="{{ asset('adminlte/assets/img/AdminLTELogo.png') }}" alt="Logo" width="40" height="40" class="rounded shadow-sm">
            <span class="h2 mb-0 fw-bold text-body">Skill<span class="text-primary">Swap</span></span>
          </a>
          <div class="text-secondary small">Masuk ke Akun Anda (Admin &amp; Pengguna)</div>
        </div>

        <div class="card-body login-card-body px-4 pb-4 pt-0">
          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show small mb-3" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif
          @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show small mb-3" role="alert">
              {{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif
          @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show small mb-3" role="alert">
              <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                  <li>{{ $err }}</li>
                @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          <form action="{{ route('login.post') }}" method="POST" id="loginForm">
            @csrf
            <div class="input-group mb-3">
              <div class="form-floating">
                <input id="loginEmail" type="email" name="email" class="form-control" placeholder="name@example.com" value="{{ old('email', 'admin@skillswap.test') }}" required autofocus />
                <label for="loginEmail">Alamat Email</label>
              </div>
              <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            </div>

            <div class="input-group mb-3">
              <div class="form-floating">
                <input id="loginPassword" type="password" name="password" class="form-control" placeholder="Password" value="password" required />
                <label for="loginPassword">Password</label>
              </div>
              <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            </div>

            <div class="row align-items-center mb-3">
              <div class="col-7">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="remember" id="rememberMe" checked />
                  <label class="form-check-label small" for="rememberMe">Ingat Saya</label>
                </div>
              </div>
            </div>

            <div class="d-grid gap-2 mb-3">
              <button type="submit" class="btn btn-primary fw-semibold py-2">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
              </button>
            </div>
          </form>

          <!-- Quick Demo Buttons for Both Roles -->
          <div class="border-top pt-3 mt-3">
            <div class="text-center small text-secondary fw-semibold mb-2">Pilih Cepat Akun Demo (2 Role Tersedia):</div>
            <div class="row g-2">
              <div class="col-6">
                <button type="button" class="btn btn-outline-danger btn-sm w-100 py-2 d-flex flex-column align-items-center" onclick="fillAccount('admin@skillswap.test', 'password')">
                  <span class="fw-bold"><i class="bi bi-shield-lock me-1"></i> Role Admin</span>
                  <small class="text-muted fs-8">admin@skillswap.test</small>
                </button>
              </div>
              <div class="col-6">
                <button type="button" class="btn btn-outline-primary btn-sm w-100 py-2 d-flex flex-column align-items-center" onclick="fillAccount('budi@skillswap.test', 'password')">
                  <span class="fw-bold"><i class="bi bi-person me-1"></i> Role Pengguna</span>
                  <small class="text-muted fs-8">budi@skillswap.test</small>
                </button>
              </div>
            </div>
          </div>

          <div class="text-center mt-3 pt-3 border-top small">
            Belum punya akun? <a href="{{ route('register') }}" class="fw-bold text-decoration-none">Daftar sebagai Pengguna Baru</a>
          </div>
        </div>
        <div class="card-footer text-center bg-transparent py-2 border-0">
          <small class="text-muted">SkillSwap &bull; Platform Pertukaran Keahlian</small>
        </div>
      </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script>
      function fillAccount(email, password) {
        document.getElementById('loginEmail').value = email;
        document.getElementById('loginPassword').value = password;
        document.getElementById('loginForm').submit();
      }
    </script>
  </body>
</html>
