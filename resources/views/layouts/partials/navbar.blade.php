<!--begin::Header-->
<nav class="app-header navbar navbar-expand bg-body shadow-sm">
  <!--begin::Container-->
  <div class="container-fluid">
    <!--begin::Start Navbar Links-->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" aria-label="Toggle sidebar">
          <i class="bi bi-list fs-5"></i>
        </a>
      </li>
      <li class="nav-item d-none d-md-block">
        <a href="{{ url('/admin') }}" class="nav-link fw-semibold">
          <i class="bi bi-speedometer2 me-1"></i> Dashboard
        </a>
      </li>
      <li class="nav-item d-none d-md-block">
        <a href="{{ url('/') }}" class="nav-link text-secondary">
          <i class="bi bi-arrow-up-right-square me-1"></i> Website
        </a>
      </li>
    </ul>
    <!--end::Start Navbar Links-->

    <!--begin::End Navbar Links-->
    <ul class="navbar-nav ms-auto align-items-center">
      <!--begin::Notifications Dropdown Menu-->
      <li class="nav-item dropdown">
        <a class="nav-link position-relative" data-bs-toggle="dropdown" href="#" aria-label="Notifications">
          <i class="bi bi-bell-fill"></i>
          <span class="position-absolute top-1 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
            3
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow">
          <span class="dropdown-item dropdown-header fw-bold">3 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="bi bi-arrow-left-right text-primary me-2"></i> New swap request received
            <span class="float-end text-secondary fs-7">5m</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="bi bi-person-check-fill text-success me-2"></i> New user registered
            <span class="float-end text-secondary fs-7">1h</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="bi bi-trophy-fill text-warning me-2"></i> Skill Battle completed
            <span class="float-end text-secondary fs-7">2h</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer text-center">See All Notifications</a>
        </div>
      </li>
      <!--end::Notifications Dropdown Menu-->

      <!--begin::Fullscreen Toggle-->
      <li class="nav-item">
        <a class="nav-link" href="#" data-lte-toggle="fullscreen" aria-label="Toggle fullscreen">
          <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
          <i data-lte-icon="minimize" class="bi bi-fullscreen-exit d-none"></i>
        </a>
      </li>
      <!--end::Fullscreen Toggle-->

      <!--begin::Color Mode Toggle-->
      <li class="nav-item dropdown">
        <a
          class="nav-link"
          href="#"
          id="bd-theme"
          aria-label="Toggle color scheme"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          <i class="bi bi-sun-fill" data-lte-theme-icon="light"></i>
          <i class="bi bi-moon-fill d-none" data-lte-theme-icon="dark"></i>
          <i class="bi bi-circle-half d-none" data-lte-theme-icon="auto"></i>
        </a>
        <ul
          class="dropdown-menu dropdown-menu-end shadow"
          aria-labelledby="bd-theme"
          style="--bs-dropdown-min-width: 8rem"
        >
          <li>
            <button
              type="button"
              class="dropdown-item d-flex align-items-center"
              data-bs-theme-value="light"
              aria-pressed="false"
            >
              <i class="bi bi-sun-fill me-2 text-warning"></i>
              Light
              <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button
              type="button"
              class="dropdown-item d-flex align-items-center"
              data-bs-theme-value="dark"
              aria-pressed="false"
            >
              <i class="bi bi-moon-fill me-2 text-primary"></i>
              Dark
              <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
          <li>
            <button
              type="button"
              class="dropdown-item d-flex align-items-center active"
              data-bs-theme-value="auto"
              aria-pressed="true"
            >
              <i class="bi bi-circle-half me-2 text-secondary"></i>
              Auto
              <i class="bi bi-check-lg ms-auto d-none"></i>
            </button>
          </li>
        </ul>
      </li>
      <!--end::Color Mode Toggle-->

      <!--begin::User Menu Dropdown-->
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
          <img
            src="{{ Auth::user()?->profile?->avatar_url ?? asset('adminlte/assets/img/avatar'.(((Auth::id() ?? 1) % 4) + 1).'.png') }}"
            class="user-image rounded-circle shadow-sm me-2 border"
            alt="{{ Auth::user()?->name ?? 'Admin' }}"
            width="32"
            height="32"
          />
          <span class="d-none d-md-inline fw-semibold">{{ Auth::user()?->name ?? 'Admin SkillSwap' }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow border-0">
          <!--begin::User Header-->
          <li class="user-header text-bg-primary p-3 text-center">
            <img
              src="{{ Auth::user()?->profile?->avatar_url ?? asset('adminlte/assets/img/avatar'.(((Auth::id() ?? 1) % 4) + 1).'.png') }}"
              class="rounded-circle shadow mb-2 border border-2 border-white"
              alt="{{ Auth::user()?->name ?? 'Admin' }}"
              width="75"
              height="75"
            />
            <p class="mb-0 fw-bold">{{ Auth::user()?->name ?? 'Admin SkillSwap' }}</p>
            <span class="badge bg-white text-primary text-uppercase mt-1">Role: {{ Auth::user()?->role ?? 'admin' }}</span>
          </li>
          <!--end::User Header-->
          <!--begin::Menu Body-->
          <li class="p-2 border-bottom">
            <a href="{{ route('portal.dashboard') }}" class="btn btn-sm btn-outline-primary w-100">
              <i class="bi bi-box-arrow-up-right me-1"></i> Buka Portal Pengguna
            </a>
          </li>
          <!--end::Menu Body-->
          <!--begin::Menu Footer-->
          <li class="user-footer d-flex justify-content-between p-3">
            <a href="{{ route('portal.profile.edit') }}" class="btn btn-sm btn-outline-secondary">
              <i class="bi bi-person me-1"></i> Profile
            </a>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
              </button>
            </form>
          </li>
          <!--end::Menu Footer-->
        </ul>
      </li>
      <!--end::User Menu Dropdown-->
    </ul>
    <!--end::End Navbar Links-->
  </div>
  <!--end::Container-->
</nav>
<!--end::Header-->
