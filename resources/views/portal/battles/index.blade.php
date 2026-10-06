@extends('layouts.portal')

@section('title', 'Skill Battles Arena | SkillSwap')

@section('content')
<div class="row align-items-center mb-4">
  <div class="col-12 col-md-8">
    <h2 class="fw-bold mb-1">Skill Battles Arena</h2>
    <p class="text-secondary small mb-0">Uji kemampuan kuis skill Anda bersama anggota lain dan raih skor tertinggi</p>
  </div>
  <div class="col-12 col-md-4 text-md-end mt-2 mt-md-0">
    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#newBattleModal">
      <i class="bi bi-lightning-charge me-1"></i> Buat Tantangan Baru
    </button>
  </div>
</div>

<div class="row g-4">
  <!-- Open Challenges to Join -->
  <div class="col-12 col-lg-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-header bg-danger-subtle border-bottom py-3">
        <h5 class="card-title fw-bold text-danger-emphasis mb-0">
          <i class="bi bi-fire me-2"></i> Tantangan Menunggu Lawan ({{ $openChallenges->count() }})
        </h5>
        <small class="text-secondary">Pilih tantangan yang dibuka oleh pengguna lain untuk bertanding</small>
      </div>
      <div class="card-body">
        @if($openChallenges->isEmpty())
          <div class="text-center py-5 text-secondary">
            <i class="bi bi-cup-hot fs-1 text-muted d-block mb-2"></i>
            <p>Tidak ada tantangan terbuka saat ini.</p>
            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#newBattleModal">
              Buka Tantangan Pertama!
            </button>
          </div>
        @else
          <div class="list-group list-group-flush">
            @foreach($openChallenges as $open)
              <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="bg-danger text-white rounded-circle p-2">
                    <i class="bi bi-lightning-charge-fill"></i>
                  </div>
                  <div>
                    <h6 class="fw-bold mb-0">{{ $open->skill->name }}</h6>
                    <small class="text-secondary">Dibuat oleh: <strong>{{ $open->player1->name }}</strong></small>
                  </div>
                </div>
                <form action="{{ route('portal.battles.join', $open) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-danger px-3">
                    <i class="bi bi-play-fill me-1"></i> Terima & Ikut
                  </button>
                </form>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- My Battle History -->
  <div class="col-12 col-lg-6">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-header bg-transparent border-bottom py-3">
        <h5 class="card-title fw-bold mb-0">
          <i class="bi bi-trophy-fill text-warning me-2"></i> Riwayat Battle Saya
        </h5>
      </div>
      <div class="card-body p-0">
        @if($myBattles->isEmpty())
          <div class="text-center py-5 text-secondary">
            <p class="mb-0">Anda belum pernah bertanding dalam battle.</p>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="ps-3">Skill</th>
                  <th>Lawan</th>
                  <th>Hasil</th>
                  <th class="text-end pe-3">Detail</th>
                </tr>
              </thead>
              <tbody>
                @foreach($myBattles as $b)
                  @php
                    $opponent = $b->player1_id === $user->id ? $b->player2 : $b->player1;
                  @endphp
                  <tr>
                    <td class="ps-3 fw-bold">{{ $b->skill->name }}</td>
                    <td>{{ $opponent->name ?? 'Menunggu Lawan...' }}</td>
                    <td>
                      @if($b->status === 'waiting')
                        <span class="badge text-bg-warning">Menunggu</span>
                      @elseif($b->winner_id === $user->id)
                        <span class="badge text-bg-success"><i class="bi bi-trophy me-1"></i>Menang</span>
                      @elseif($b->winner_id)
                        <span class="badge text-bg-danger">Kalah</span>
                      @else
                        <span class="badge text-bg-secondary">{{ ucfirst($b->status) }}</span>
                      @endif
                    </td>
                    <td class="text-end pe-3">
                      <a href="{{ route('portal.battles.show', $b) }}" class="btn btn-sm btn-outline-info">
                        <i class="bi bi-eye"></i>
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- New Battle Modal -->
<div class="modal fade" id="newBattleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow">
      <form action="{{ route('portal.battles.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title fw-bold">Buat Tantangan Skill Battle</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Pilih Skill yang Diuji</label>
            <select name="skill_id" class="form-select" required>
              <option value="">-- Pilih Skill --</option>
              @foreach($availableSkills as $sk)
                <option value="{{ $sk->id }}">{{ $sk->name }} ({{ $sk->category?->name ?? 'General' }})</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Tantang Pengguna Tertentu <small class="text-secondary fw-normal">(Opsional - biarkan kosong untuk tantangan terbuka)</small></label>
            <select name="player2_id" class="form-select">
              <option value="">-- Tantangan Terbuka (Siapa saja boleh bergabung) --</option>
              @foreach($otherUsers as $ou)
                <option value="{{ $ou->id }}">{{ $ou->name }} ({{ $ou->email }})</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Mulai Tantangan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
