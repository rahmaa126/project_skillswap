@extends('layouts.portal')

@section('title', 'Jelajah Katalog Skill | SkillSwap')

@section('content')
<div class="row align-items-center mb-4">
  <div class="col-12 col-md-8">
    <h2 class="fw-bold mb-1">Jelajah Katalog Keahlian</h2>
    <p class="text-secondary small mb-0">Temukan ribuan keahlian yang dapat dipelajari dan diajarkan dalam komunitas</p>
  </div>
  <div class="col-12 col-md-4 text-md-end mt-2 mt-md-0">
    <a href="{{ route('portal.my-skills.index') }}" class="btn btn-outline-primary">
      <i class="bi bi-stars me-1"></i> Lihat Skill Saya
    </a>
  </div>
</div>

<!-- Search & Filter Bar -->
<div class="card shadow-sm border-0 mb-4">
  <div class="card-body p-3">
    <form method="GET" action="{{ route('portal.skills.index') }}" class="row g-2 align-items-center">
      <div class="col-12 col-md-6">
        <div class="input-group">
          <span class="input-group-text bg-body-secondary border-end-0"><i class="bi bi-search"></i></span>
          <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama keahlian (misal: Python, Desain, Gitar)..." value="{{ request('search') }}">
        </div>
      </div>
      <div class="col-6 col-md-4">
        <select name="category_id" class="form-select">
          <option value="">Semua Kategori</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
              {{ $category->name }} ({{ $category->skills_count }})
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-6 col-md-2 d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-fill">Cari</button>
        @if(request()->anyFilled(['search', 'category_id']))
          <a href="{{ route('portal.skills.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
        @endif
      </div>
    </form>
  </div>
</div>

<!-- Skills Grid -->
<div class="row g-3">
  @forelse($skills as $skill)
  <div class="col-12 col-sm-6 col-lg-4">
    <div class="card shadow-sm border-0 h-100 rounded-3 hover-shadow">
      <div class="card-body d-flex flex-column p-4">
        <div class="d-flex align-items-start justify-content-between mb-2">
          <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
            {{ $skill->category?->name ?? 'General' }}
          </span>
          <span class="badge bg-warning-subtle text-warning-emphasis">
            <i class="bi bi-shield-check me-1"></i>Min {{ $skill->min_score }} pts
          </span>
        </div>

        <h5 class="fw-bold mb-2">{{ $skill->name }}</h5>
        <p class="text-secondary small mb-3 flex-grow-1">
          {{ Str::limit($skill->description, 110) ?: 'Belum ada penjelasan detail untuk keahlian ini.' }}
        </p>

        <div class="border-top pt-3 mt-auto d-flex align-items-center justify-content-between">
          <small class="text-muted">
            <i class="bi bi-people me-1"></i>{{ $skill->userSkills->count() }} Anggota aktif
          </small>
          <div class="dropdown">
            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
              + Ambil Skill
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
              <li>
                <form action="{{ route('portal.my-skills.store') }}" method="POST">
                  @csrf
                  <input type="hidden" name="skill_id" value="{{ $skill->id }}">
                  <input type="hidden" name="type" value="offered">
                  <button type="submit" class="dropdown-item small text-success py-2">
                    <i class="bi bi-arrow-up-circle me-1"></i> Saya bisa mengajarkan ini
                  </button>
                </form>
              </li>
              <li>
                <form action="{{ route('portal.my-skills.store') }}" method="POST">
                  @csrf
                  <input type="hidden" name="skill_id" value="{{ $skill->id }}">
                  <input type="hidden" name="type" value="wanted">
                  <button type="submit" class="dropdown-item small text-primary py-2">
                    <i class="bi bi-arrow-down-circle me-1"></i> Saya ingin belajar ini
                  </button>
                </form>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12 text-center py-5 text-secondary">
    <i class="bi bi-search fs-1 text-muted d-block mb-2"></i>
    <h5>Tidak ada keahlian yang cocok dengan pencarian Anda.</h5>
    <a href="{{ route('portal.skills.index') }}" class="btn btn-sm btn-primary mt-2">Lihat Semua Skill</a>
  </div>
  @endforelse
</div>

@if($skills->hasPages())
<div class="mt-4 d-flex justify-content-center">
  {{ $skills->links() }}
</div>
@endif
@endsection
