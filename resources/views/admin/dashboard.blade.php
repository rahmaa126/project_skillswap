@extends('layouts.admin')

@section('title', 'AdminLTE 4 - SkillSwap Dashboard')

@section('content-header')
<div class="row align-items-center mb-3">
  <div class="col-sm-6">
    <h2 class="mb-0 fw-bold fs-3 text-body">SkillSwap Admin Dashboard</h2>
    <p class="text-secondary small mb-0">Platform overview, exchange activities, and system statistics</p>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-end bg-transparent p-0 mb-0">
      <li class="breadcrumb-item"><a href="{{ url('/admin') }}" class="text-decoration-none">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<!--begin::Row Metric Widgets-->
<div class="row g-3 mb-4">
  <!-- Users Box -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="small-box text-bg-primary shadow-sm rounded-3">
      <div class="inner p-3">
        <h3 class="fw-bold fs-2">{{ $usersCount }}</h3>
        <p class="mb-0">Active Users</p>
      </div>
      <i class="small-box-icon bi bi-people-fill"></i>
      <a href="#users" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover py-1 px-3 d-block">
        View all users <i class="bi bi-arrow-right-short"></i>
      </a>
    </div>
  </div>

  <!-- Skills Box -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="small-box text-bg-success shadow-sm rounded-3">
      <div class="inner p-3">
        <h3 class="fw-bold fs-2">{{ $skillsCount }}</h3>
        <p class="mb-0">Total Skills</p>
      </div>
      <i class="small-box-icon bi bi-stars"></i>
      <a href="#skills" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover py-1 px-3 d-block">
        Explore skills <i class="bi bi-arrow-right-short"></i>
      </a>
    </div>
  </div>

  <!-- Swap Sessions Box -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="small-box text-bg-warning shadow-sm rounded-3">
      <div class="inner p-3 text-dark">
        <h3 class="fw-bold fs-2">{{ $sessionsCount }}</h3>
        <p class="mb-0">Swap Sessions</p>
      </div>
      <i class="small-box-icon bi bi-arrow-left-right text-dark"></i>
      <a href="#swaps" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover py-1 px-3 d-block">
        View sessions <i class="bi bi-arrow-right-short"></i>
      </a>
    </div>
  </div>

  <!-- Battles Box -->
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="small-box text-bg-danger shadow-sm rounded-3">
      <div class="inner p-3">
        <h3 class="fw-bold fs-2">{{ $battlesCount }}</h3>
        <p class="mb-0">Skill Battles</p>
      </div>
      <i class="small-box-icon bi bi-lightning-charge-fill"></i>
      <a href="#battles" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover py-1 px-3 d-block">
        View battles <i class="bi bi-arrow-right-short"></i>
      </a>
    </div>
  </div>
</div>
<!--end::Row Metric Widgets-->

<!--begin::Row Charts & Highlights-->
<div class="row g-3 mb-4">
  <!-- Chart Card -->
  <div class="col-12 col-lg-8">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between">
        <h5 class="card-title fw-semibold mb-0">
          <i class="bi bi-pie-chart me-1 text-primary"></i> Skills Distribution by Category
        </h5>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse" aria-label="Collapse">
            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <div id="categoryChart" style="min-height: 280px;"></div>
      </div>
    </div>
  </div>

  <!-- Summary & Quick Stats -->
  <div class="col-12 col-lg-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-header bg-transparent border-bottom">
        <h5 class="card-title fw-semibold mb-0">
          <i class="bi bi-info-circle me-1 text-info"></i> System Overview
        </h5>
      </div>
      <div class="card-body d-flex flex-column justify-content-around">
        <div class="d-flex align-items-center justify-content-between p-2 rounded bg-body-secondary mb-2">
          <div class="d-flex align-items-center">
            <div class="bg-primary text-white rounded p-2 me-3">
              <i class="bi bi-grid fs-5"></i>
            </div>
            <div>
              <div class="fw-bold">Skill Categories</div>
              <small class="text-secondary">Classified fields</small>
            </div>
          </div>
          <span class="fs-5 fw-bold">{{ $categoriesCount }}</span>
        </div>

        <div class="d-flex align-items-center justify-content-between p-2 rounded bg-body-secondary mb-2">
          <div class="d-flex align-items-center">
            <div class="bg-warning text-white rounded p-2 me-3">
              <i class="bi bi-star-fill fs-5"></i>
            </div>
            <div>
              <div class="fw-bold">Reviews & Ratings</div>
              <small class="text-secondary">Community trust</small>
            </div>
          </div>
          <span class="fs-5 fw-bold">{{ $reviewsCount }}</span>
        </div>

        <div class="d-flex align-items-center justify-content-between p-2 rounded bg-body-secondary">
          <div class="d-flex align-items-center">
            <div class="bg-success text-white rounded p-2 me-3">
              <i class="bi bi-shield-check fs-5"></i>
            </div>
            <div>
              <div class="fw-bold">AdminLTE Version</div>
              <small class="text-secondary">Bootstrap 5.3 + ESM</small>
            </div>
          </div>
          <span class="badge text-bg-success">v4.9.1</span>
        </div>
      </div>
    </div>
  </div>
</div>
<!--end::Row Charts & Highlights-->

<!--begin::Row Recent Data-->
<div class="row g-3">
  <!-- Recent Swap Sessions -->
  <div class="col-12 col-lg-7" id="swaps">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between">
        <h5 class="card-title fw-semibold mb-0">
          <i class="bi bi-arrow-left-right me-1 text-warning"></i> Recent Swap Sessions
        </h5>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
          </button>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Requester</th>
                <th>Partner</th>
                <th>Status</th>
                <th>Scheduled At</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentSessions as $session)
              <tr>
                <td class="fw-semibold">#{{ $session->id }}</td>
                <td>{{ $session->requester->name ?? 'User #'.$session->requester_id }}</td>
                <td>{{ $session->partner->name ?? 'User #'.$session->partner_id }}</td>
                <td>
                  @php
                    $statusClass = match(strtolower($session->status)) {
                      'completed' => 'text-bg-success',
                      'in_progress', 'ongoing' => 'text-bg-primary',
                      'pending' => 'text-bg-warning',
                      'cancelled', 'rejected' => 'text-bg-danger',
                      default => 'text-bg-secondary'
                    };
                  @endphp
                  <span class="badge {{ $statusClass }} text-uppercase fs-7">{{ $session->status }}</span>
                </td>
                <td class="text-secondary small">{{ $session->scheduled_at ? $session->scheduled_at->format('M d, Y H:i') : '-' }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center text-secondary py-3">No swap sessions recorded yet.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Registered Users -->
  <div class="col-12 col-lg-5" id="users">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between">
        <h5 class="card-title fw-semibold mb-0">
          <i class="bi bi-person-badge me-1 text-primary"></i> Registered Users
        </h5>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
          </button>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>User</th>
                <th>Role</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentUsers as $user)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <img
                      src="{{ asset('adminlte/assets/img/avatar'.(($user->id % 4) + 1).'.png') }}"
                      class="rounded-circle me-2"
                      alt="{{ $user->name }}"
                      width="36"
                      height="36"
                    />
                    <div>
                      <div class="fw-semibold fs-7">{{ $user->name }}</div>
                      <small class="text-secondary">{{ $user->email }}</small>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="badge text-bg-light border text-uppercase">{{ $user->role ?? 'user' }}</span>
                </td>
                <td>
                  @if($user->is_active ?? true)
                    <span class="badge text-bg-success">Active</span>
                  @else
                    <span class="badge text-bg-secondary">Inactive</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="3" class="text-center text-secondary py-3">No users found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!--end::Row Recent Data-->

<!--begin::Row Skills & Categories-->
<div class="row g-3 mt-1" id="skills">
  <div class="col-12">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between">
        <h5 class="card-title fw-semibold mb-0">
          <i class="bi bi-stars me-1 text-success"></i> Skills Directory
        </h5>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
          </button>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Skill Name</th>
                <th>Category</th>
                <th>Min Score</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentSkills as $skill)
              <tr>
                <td class="fw-semibold">
                  <i class="bi bi-check-circle-fill text-success me-1"></i> {{ $skill->name }}
                </td>
                <td>
                  <span class="badge text-bg-info text-dark">
                    {{ $skill->category->name ?? 'General' }}
                  </span>
                </td>
                <td>{{ $skill->min_score ?? 0 }} pts</td>
                <td>
                  @if($skill->is_active ?? true)
                    <span class="badge text-bg-success">Active</span>
                  @else
                    <span class="badge text-bg-secondary">Inactive</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="4" class="text-center text-secondary py-3">No skills recorded yet.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<!--end::Row Skills & Categories-->
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    @php
      $categoryNames = $categories->pluck('name')->toArray();
      $categorySkillCounts = $categories->pluck('skills_count')->toArray();
      if (empty($categoryNames)) {
        $categoryNames = ['Programming', 'Design', 'Language', 'Marketing'];
        $categorySkillCounts = [4, 3, 2, 1];
      }
    @endphp

    const categoryNames = {!! json_encode($categoryNames) !!};
    const categoryCounts = {!! json_encode($categorySkillCounts) !!};

    const options = {
      series: [{
        name: 'Skills Count',
        data: categoryCounts
      }],
      chart: {
        type: 'bar',
        height: 280,
        toolbar: { show: false }
      },
      plotOptions: {
        bar: {
          borderRadius: 6,
          horizontal: false,
          columnWidth: '45%',
          distributed: true
        }
      },
      dataLabels: {
        enabled: true
      },
      xaxis: {
        categories: categoryNames,
        labels: {
          style: {
            fontSize: '12px'
          }
        }
      },
      yaxis: {
        title: {
          text: 'Number of Skills'
        }
      },
      colors: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1', '#0dcaf0'],
      legend: {
        show: false
      }
    };

    const chart = new ApexCharts(document.querySelector("#categoryChart"), options);
    chart.render();
  });
</script>
@endpush
