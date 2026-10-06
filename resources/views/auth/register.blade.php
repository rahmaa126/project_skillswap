<!doctype html>
<html lang="id">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <title>Daftar Pengguna Baru | SkillSwap</title>

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
    <div class="login-box" style="width: 480px; max-width: 92%;">
      <div class="card card-outline card-success shadow-lg border-0 rounded-4 overflow-hidden">
        <div class="card-header text-center py-4 border-0 bg-transparent">
          <a href="{{ url('/') }}" class="text-decoration-none d-flex align-items-center justify-content-center gap-2 mb-1">
            <img src="{{ asset('adminlte/assets/img/AdminLTELogo.png') }}" alt="Logo" width="40" height="40" class="rounded shadow-sm">
            <span class="h2 mb-0 fw-bold text-body">Skill<span class="text-primary">Swap</span></span>
          </a>
          <div class="text-secondary small">Daftar Akun Pengguna Baru</div>
        </div>

        <div class="card-body px-4 pb-4 pt-0">
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

          <form action="{{ route('register.post') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label class="form-label fw-semibold small">Nama Lengkap <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="name" class="form-control" placeholder="Nama Anda" value="{{ old('name') }}" required autofocus />
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold small">Alamat Email <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="email@contoh.com" value="{{ old('email') }}" required />
              </div>
            </div>

            <div class="row g-2 mb-3">
              <div class="col-12 col-md-6">
                <label class="form-label fw-semibold small">Kota Domisili</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                  <input type="text" name="city" class="form-control" placeholder="e.g. Jakarta" value="{{ old('city') }}" />
                </div>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label fw-semibold small">Nomor WhatsApp</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                  <input type="text" name="phone" class="form-control" placeholder="e.g. 0812345678" value="{{ old('phone') }}" />
                </div>
              </div>
            </div>

            <div class="row g-2 mb-3">
              <div class="col-12 col-md-6">
                <label class="form-label fw-semibold small">Password <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                  <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required />
                </div>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label fw-semibold small">Konfirmasi Password <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                  <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required />
                </div>
              </div>
            </div>

            <div class="d-grid gap-2 mb-3 mt-4">
              <button type="submit" class="btn btn-success fw-semibold py-2">
                <i class="bi bi-check-circle me-1"></i> Daftar Sebagai Pengguna
              </button>
            </div>
          </form>

          <div class="text-center mt-3 pt-3 border-top small">
            Sudah memiliki akun? <a href="{{ route('login') }}" class="fw-bold text-decoration-none">Masuk ke Sini</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  </body>
</html>
