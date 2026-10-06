@extends('layouts.portal')

@section('title', 'Kelola Skill Saya | SkillSwap')

@section('content')
<div class="row align-items-center mb-4">
  <div class="col-12 col-md-8">
    <h2 class="fw-bold mb-1">Kelola Keahlian Saya</h2>
    <p class="text-secondary small mb-0">Tentukan keahlian yang dapat Anda ajarkan dan topik yang ingin Anda kuasai</p>
  </div>
  <div class="col-12 col-md-4 text-md-end mt-2 mt-md-0">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSkillModal">
      <i class="bi bi-plus-lg me-1"></i> Tambah Keahlian Baru
    </button>
  </div>
</div>

<div class="row g-4">
  <!-- Offered Skills (Diajarkan) -->
  <div class="col-12 col-lg-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-header bg-success-subtle border-bottom py-3">
        <h5 class="card-title fw-bold text-success-emphasis mb-0">
          <i class="bi bi-arrow-up-circle-fill me-2"></i> Keahlian Yang Saya Ajarkan (Offered)
        </h5>
        <small class="text-secondary">Orang lain dapat meminta sesi belajar pada topik ini kepada Anda</small>
      </div>
      <div class="card-body">
        @if($offeredSkills->isEmpty())
          <div class="text-center py-5 text-secondary">
            <i class="bi bi-mortarboard fs-1 text-muted d-block mb-2"></i>
            <p>Anda belum mendaftarkan keahlian yang bisa diajarkan.</p>
            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#addSkillModal">
              + Tambah Skill Sekarang
            </button>
          </div>
        @else
          <div class="list-group list-group-flush">
            @foreach($offeredSkills as $item)
              <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="bg-success-subtle text-success p-2 rounded-circle">
                    <i class="bi bi-stars fs-5"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-0">{{ $item->skill->name }}</h6>
                    <small class="text-secondary">{{ $item->skill->category?->name ?? 'General' }} &bull; Min: {{ $item->skill->min_score }} pts</small>
                  </div>
                </div>
                <form action="{{ route('portal.my-skills.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus skill ini dari daftar keahlian diajarkan?')" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Hapus">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Wanted Skills (Ingin Dipelajari) -->
  <div class="col-12 col-lg-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-header bg-primary-subtle border-bottom py-3">
        <h5 class="card-title fw-bold text-primary-emphasis mb-0">
          <i class="bi bi-arrow-down-circle-fill me-2"></i> Keahlian Yang Ingin Dipelajari (Wanted)
        </h5>
        <small class="text-secondary">Sistem AI akan mencocokkan Anda dengan mentor yang menguasai topik ini</small>
      </div>
      <div class="card-body">
        @if($wantedSkills->isEmpty())
          <div class="text-center py-5 text-secondary">
            <i class="bi bi-lightbulb fs-1 text-muted d-block mb-2"></i>
            <p>Anda belum memilih keahlian yang ingin dipelajari.</p>
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addSkillModal">
              + Cari Skill Untuk Dipelajari
            </button>
          </div>
        @else
          <div class="list-group list-group-flush">
            @foreach($wantedSkills as $item)
              <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="bg-primary-subtle text-primary p-2 rounded-circle">
                    <i class="bi bi-compass fs-5"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-0">{{ $item->skill->name }}</h6>
                    <small class="text-secondary">{{ $item->skill->category?->name ?? 'General' }}</small>
                  </div>
                </div>
                <form action="{{ route('portal.my-skills.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus skill ini dari daftar yang ingin dipelajari?')" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Hapus">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Add Skill Modal -->
<div class="modal fade" id="addSkillModal" tabindex="-1" aria-labelledby="addSkillModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow">
      <form action="{{ route('portal.my-skills.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="addSkillModalLabel">Tambah Keahlian ke Profil</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Pilih Skill dari Katalog</label>
            <select name="skill_id" class="form-select" required>
              <option value="">-- Pilih Skill --</option>
              @foreach($allSkills as $s)
                <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->category?->name ?? 'General' }})</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Tipe Hubungan</label>
            <div class="form-check mb-2">
              <input class="form-check-input" type="radio" name="type" id="typeOffered" value="offered" checked>
              <label class="form-check-label" for="typeOffered">
                <strong>Saya bisa mengajarkan skill ini</strong> (Offered)
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="type" id="typeWanted" value="wanted">
              <label class="form-check-label" for="typeWanted">
                <strong>Saya ingin belajar skill ini</strong> (Wanted)
              </label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan ke Profil</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
