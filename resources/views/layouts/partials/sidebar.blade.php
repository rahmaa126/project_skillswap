<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <!--begin::Sidebar Brand-->
  <div class="sidebar-brand">
    <a href="{{ url('/admin') }}" class="brand-link text-decoration-none">
      <img
        src="{{ asset('adminlte/assets/img/AdminLTELogo.png') }}"
        alt="SkillSwap Logo"
        class="brand-image opacity-75 shadow rounded"
      />
      <span class="brand-text fw-semibold">Skill<span class="text-primary">Swap</span></span>
    </a>
  </div>
  <!--end::Sidebar Brand-->

  <!--begin::Sidebar Search-->
  <div class="sidebar-search px-3 pt-2" role="search">
    <label for="sidebar-search-input" class="visually-hidden">Filter menu</label>
    <div class="input-group input-group-sm">
      <input
        type="search"
        id="sidebar-search-input"
        class="form-control"
        placeholder="Filter menu…"
        autocomplete="off"
        data-lte-toggle="sidebar-search"
        data-lte-target="#navigation"
      />
      <span class="input-group-text"><i class="bi bi-search"></i></span>
    </div>
    <p class="fs-7 text-secondary mt-2 mb-0" data-lte-search-empty role="status" hidden>
      No matching items.
    </p>
  </div>
  <!--end::Sidebar Search-->

  <!--begin::Sidebar Wrapper-->
  <div class="sidebar-wrapper">
    <nav class="mt-2" aria-label="Main navigation">
      <!--begin::Sidebar Menu-->
      <ul
        class="nav sidebar-menu flex-column"
        data-lte-toggle="treeview"
        data-accordion="false"
        id="navigation"
      >
        <li class="nav-header text-uppercase fs-7 text-secondary px-3 mb-1">General</li>

        <li class="nav-item">
          <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="nav-icon bi bi-speedometer2"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-header text-uppercase fs-7 text-secondary px-3 mt-3 mb-1">Master Data</li>

        <li class="nav-item {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.skills.*') || request()->routeIs('admin.categories.*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.skills.*') || request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-folder2-open"></i>
            <p>
              Data Management
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-people"></i>
                <p>Users</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.skills.index') }}" class="nav-link {{ request()->routeIs('admin.skills.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-stars"></i>
                <p>Skills</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-grid"></i>
                <p>Skill Categories</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-header text-uppercase fs-7 text-secondary px-3 mt-3 mb-1">Skill Activities</li>

        <li class="nav-item">
          <a href="{{ route('admin.swap-sessions.index') }}" class="nav-link {{ request()->routeIs('admin.swap-sessions.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-arrow-left-right text-info"></i>
            <p>Swap Sessions</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.battles.index') }}" class="nav-link {{ request()->routeIs('admin.battles.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-lightning-charge text-warning"></i>
            <p>Skill Battles</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.matches.index') }}" class="nav-link {{ request()->routeIs('admin.matches.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-intersect text-success"></i>
            <p>Skill Matches</p>
          </a>
        </li>

        <li class="nav-header text-uppercase fs-7 text-secondary px-3 mt-3 mb-1">System & Reports</li>

        <li class="nav-item">
          <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-star text-warning"></i>
            <p>Reviews & Ratings</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-flag text-danger"></i>
            <p>Reports</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('admin.ai-logs.index') }}" class="nav-link {{ request()->routeIs('admin.ai-logs.*') ? 'active' : '' }}">
            <i class="nav-icon bi bi-cpu text-primary"></i>
            <p>AI Engine Logs</p>
          </a>
        </li>

        <li class="nav-header text-uppercase fs-7 text-secondary px-3 mt-3 mb-1">Quick Links</li>

        <li class="nav-item">
          <a href="{{ url('/login') }}" class="nav-link">
            <i class="nav-icon bi bi-box-arrow-in-right"></i>
            <p>Login Page (Auth)</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ url('/') }}" class="nav-link" target="_blank">
            <i class="nav-icon bi bi-globe"></i>
            <p>Public Site</p>
          </a>
        </li>
      </ul>
      <!--end::Sidebar Menu-->
    </nav>
  </div>
  <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->
