@extends('layouts.portal')

@section('title', 'Dashboard Pengguna | SkillSwap')

@section('content')
<!-- Hero Welcome Banner -->
<div class="card shadow-sm border-0 bg-primary text-white rounded-4 overflow-hidden mb-4 position-relative">
  <div class="card-body p-4 p-md-5 position-relative z-1">
    <div class="row align-items-center g-4">
      <div class="col-12 col-lg-8">
        <div class="d-flex align-items-center gap-3 mb-2">
          <img
            src="{{ $user->profile?->avatar_url ?? asset('adminlte/assets/img/avatar'.(($user->id % 4) + 1).'.png') }}"
            alt="{{ $user->name }}"
            width="64"
            height="64"
            class="rounded-circle border border-2 border-white shadow"
          />
          <div>
            <span class="badge bg-white text-primary fw-bold text-uppercase px-2 py-1 mb-1">Role: Pengguna</span>
            <h2 class="fw-bold mb-0">Selamat Datang, {{ $user->name }}!</h2>
          </div>
        </div>
        <p class="lead opacity-90 mb-3 fs-6">
          Siap menukar keahlian hari ini? Bagikan ilmumu dan pelajari hal baru dari sesama anggota komunitas SkillSwap.
        </p>
        <div class="d-flex flex-wrap gap-2">
          <a href="{{ route('portal.skills.index') }}" class="btn btn-light fw-semibold text-primary px-3 shadow-sm">
            <i class="bi bi-compass me-1"></i> Jelajah Skill
          </a>
          <a href="{{ route('portal.my-skills.index') }}" class="btn btn-outline-light px-3">
            <i class="bi bi-plus-circle me-1"></i> Atur Skill Saya
          </a>
          <a href="{{ route('portal.swaps.index') }}" class="btn btn-outline-light px-3">
            <i class="bi bi-arrow-left-right me-1"></i> Sesi Pertukaran
          </a>
        </div>
      </div>

      <div class="col-12 col-lg-4">
        <div class="bg-white bg-opacity-10 backdrop-blur rounded-3 p-3 border border-white border-opacity-25">
          <div class="row g-2 text-center">
            <div class="col-6">
              <div class="p-2 border-end border-white border-opacity-25">
                <div class="fs-4 fw-bold">
                  <i class="bi bi-star-fill text-warning me-1"></i>{{ $user->profile?->avg_rating ?? '0.0' }}
                </div>
                <small class="text-white-50">Rating Reputasi</small>
              </div>
            </div>
            <div class="col-6">
              <div class="p-2">
                <div class="fs-4 fw-bold">{{ $user->profile?->total_swaps ?? 0 }}</div>
                <small class="text-white-50">Total Swaps Selesai</small>
              </div>
            </div>
            <div class="col-6">
              <div class="p-2 border-end border-white border-opacity-25 border-top">
                <div class="fs-4 fw-bold text-success-subtle">{{ $user->offeredSkills->count() }}</div>
                <small class="text-white-50">Skill Diajarkan</small>
              </div>
            </div>
            <div class="col-6">
              <div class="p-2 border-top border-white border-opacity-25">
                <div class="fs-4 fw-bold text-info-subtle">{{ $user->wantedSkills->count() }}</div>
                <small class="text-white-50">Skill Dipelajari</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Pending Incoming Requests Notification (If any) -->
@if($pendingSessions->isNotEmpty())
<div class="card shadow-sm border-0 border-start border-warning border-4 mb-4">
  <div class="card-header bg-warning-subtle d-flex align-items-center justify-content-between py-3">
    <h5 class="card-title fw-bold text-warning-emphasis mb-0">
      <i class="bi bi-bell-fill me-2"></i> Permintaan Sesi Swap Masuk ({{ $pendingSessions->count() }})
    </h5>
    <span class="badge text-bg-warning">Menunggu Konfirmasi Anda</span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <tbody>
          @foreach($pendingSessions as $pending)
          <tr>
            <td class="ps-3 py-3">
              <div class="d-flex align-items-center gap-3">
                <img src="{{ $pending->requester->profile?->avatar_url ?? asset('adminlte/assets/img/avatar'.(($pending->requester_id % 4) + 1).'.png') }}" width="42" height="42" class="rounded-circle border" alt="Avatar">
                <div>
                  <div class="fw-bold">{{ $pending->requester->name }}</div>
                  <small class="text-secondary">Mengajukan pertukaran:</small>
                  <div class="mt-1">
                    <span class="badge bg-primary-subtle text-primary">{{ $pending->requesterSkill->name ?? 'Skill #'.$pending->requester_skill_id }}</span>
                    <i class="bi bi-arrow-left-right mx-1 text-muted"></i>
                    <span class="badge bg-success-subtle text-success">{{ $pending->partnerSkill->name ?? 'Skill #'.$pending->partner_skill_id }}</span>
                  </div>
                </div>
              </div>
            </td>
            <td class="text-end pe-3">
              <form action="{{ route('portal.swaps.respond', $pending) }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="action" value="accept">
                <button type="submit" class="btn btn-sm btn-success px-3 me-1">
                  <i class="bi bi-check-lg me-1"></i> Terima
                </button>
              </form>
              <form action="{{ route('portal.swaps.respond', $pending) }}" method="POST" class="d-inline">
                @csrf
                <input type="hidden" name="action" value="reject">
                <button type="submit" class="btn btn-sm btn-outline-danger px-3">
                  <i class="bi bi-x-lg me-1"></i> Tolak
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endif

<div class="row g-4">
  <!-- Left Column: AI Matches & Active Swaps -->
  <div class="col-12 col-lg-8">
    <!-- AI Smart Matching Recommendations -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between py-3">
        <h5 class="card-title fw-bold mb-0">
          <i class="bi bi-cpu-fill text-primary me-2"></i> Rekomendasi Partner Belajar (Smart Matching AI)
        </h5>
        <span class="badge bg-primary-subtle text-primary">Berdasarkan Keahlian Anda</span>
      </div>
      <div class="card-body">
        @if($matches->isEmpty())
          <div class="text-center py-4 text-secondary">
            <i class="bi bi-stars fs-1 text-muted d-block mb-2"></i>
            <p class="mb-2">Belum ada rekomendasi partner AI saat ini.</p>
            <small>Lengkapi keahlian yang Anda tawarkan dan ingin dipelajari untuk mendapatkan rekomendasi terbaik!</small>
            <div class="mt-3">
              <a href="{{ route('portal.my-skills.index') }}" class="btn btn-sm btn-primary">Atur Skill Saya</a>
            </div>
          </div>
        @else
          <div class="row g-3">
            @foreach($matches as $match)
              @php
                $isUserA = $match->user_a_id === $user->id;
                $partner = $isUserA ? $match->userB : $match->userA;
                $teachSkill = $isUserA ? $match->skillA : $match->skillB;
                $learnSkill = $isUserA ? $match->skillB : $match->skillA;
              @endphp
              <div class="col-12 col-md-6">
                <div class="card h-100 border rounded-3 p-3 bg-body-secondary bg-opacity-50">
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2">
                      <img src="{{ $partner->profile?->avatar_url ?? asset('adminlte/assets/img/avatar'.(($partner->id % 4) + 1).'.png') }}" width="36" height="36" class="rounded-circle border" alt="{{ $partner->name }}">
                      <div>
                        <div class="fw-bold small">{{ $partner->name }}</div>
                        <small class="text-secondary"><i class="bi bi-geo-alt me-1"></i>{{ $partner->profile?->city ?? 'Indonesia' }}</small>
                      </div>
                    </div>
                    <span class="badge bg-success-subtle text-success fw-bold">
                      <i class="bi bi-lightning-fill me-1"></i>{{ number_format($match->match_score, 0) }}% Cocok
                    </span>
                  </div>

                  <div class="small p-2 bg-body rounded border mb-3">
                    <div class="d-flex justify-content-between mb-1">
                      <span class="text-secondary">Anda Ajarkan:</span>
                      <strong class="text-primary">{{ $teachSkill->name ?? 'Skill #'.$teachSkill->id }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                      <span class="text-secondary">Dia Ajarkan:</span>
                      <strong class="text-success">{{ $learnSkill->name ?? 'Skill #'.$learnSkill->id }}</strong>
                    </div>
                  </div>

                  @if($match->ai_reason)
                    <div class="small text-muted fst-italic mb-3">
                      "{{ Str::limit($match->ai_reason, 90) }}"
                    </div>
                  @endif

                  <div class="mt-auto">
                    <form action="{{ route('portal.swaps.store') }}" method="POST">
                      @csrf
                      <input type="hidden" name="partner_id" value="{{ $partner->id }}">
                      <input type="hidden" name="requester_skill_id" value="{{ $teachSkill->id }}">
                      <input type="hidden" name="partner_skill_id" value="{{ $learnSkill->id }}">
                      <button type="submit" class="btn btn-sm btn-primary w-100 fw-semibold">
                        <i class="bi bi-send me-1"></i> Ajukan Sesi Swap
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>

    <!-- Active Swap Sessions -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between py-3">
        <h5 class="card-title fw-bold mb-0">
          <i class="bi bi-arrow-left-right text-success me-2"></i> Sesi Pertukaran Aktif & Berjalan
        </h5>
        <a href="{{ route('portal.swaps.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
      </div>
      <div class="card-body p-0">
        @if($activeSessions->isEmpty())
          <div class="text-center py-4 text-secondary">
            <p class="mb-0">Tidak ada sesi swap yang sedang berjalan saat ini.</p>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="ps-3">Rekan Belajar</th>
                  <th>Keahlian Yang Ditukar</th>
                  <th>Status</th>
                  <th>Jadwal</th>
                  <th class="text-end pe-3">Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach($activeSessions as $session)
                  @php
                    $isRequester = $session->requester_id === $user->id;
                    $partner = $isRequester ? $session->partner : $session->requester;
                    $mySkill = $isRequester ? $session->requesterSkill : $session->partnerSkill;
                    $partnerSkill = $isRequester ? $session->partnerSkill : $session->requesterSkill;
                  @endphp
                  <tr>
                    <td class="ps-3">
                      <div class="fw-bold">{{ $partner->name }}</div>
                      <small class="text-secondary">{{ $partner->email }}</small>
                    </td>
                    <td>
                      <small class="d-block"><span class="text-muted">Anda ajarkan:</span> <strong class="text-primary">{{ $mySkill->name }}</strong></small>
                      <small class="d-block"><span class="text-muted">Dia ajarkan:</span> <strong class="text-success">{{ $partnerSkill->name }}</strong></small>
                    </td>
                    <td>
                      <span class="badge text-bg-primary text-uppercase">{{ $session->status }}</span>
                    </td>
                    <td class="small text-secondary">
                      {{ $session->scheduled_at ? $session->scheduled_at->format('d M Y H:i') : '-' }}
                    </td>
                    <td class="text-end pe-3">
                      <form action="{{ route('portal.swaps.complete', $session) }}" method="POST" onsubmit="return confirm('Tandai sesi ini telah selesai?')" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-outline-success">
                          <i class="bi bi-check2-circle me-1"></i> Selesai
                        </button>
                      </form>
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

  <!-- Right Column: My Skills & Battles -->
  <div class="col-12 col-lg-4">
    <!-- My Skills Card -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between py-3">
        <h5 class="card-title fw-bold mb-0">
          <i class="bi bi-stars text-warning me-2"></i> Skill Saya
        </h5>
        <a href="{{ route('portal.my-skills.index') }}" class="btn btn-sm btn-outline-primary">Kelola</a>
      </div>
      <div class="card-body">
        <h6 class="fw-bold text-success small mb-2"><i class="bi bi-arrow-up-circle me-1"></i> Keahlian Yang Saya Ajarkan</h6>
        <div class="d-flex flex-wrap gap-1 mb-3">
          @forelse($user->offeredSkills as $offered)
            <span class="badge bg-success-subtle text-success border border-success-subtle p-2">
              {{ $offered->skill->name }}
            </span>
          @empty
            <span class="text-secondary small">Belum ada skill diajarkan.</span>
          @endforelse
        </div>

        <h6 class="fw-bold text-primary small mb-2"><i class="bi bi-arrow-down-circle me-1"></i> Keahlian Yang Ingin Dipelajari</h6>
        <div class="d-flex flex-wrap gap-1">
          @forelse($user->wantedSkills as $wanted)
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle p-2">
              {{ $wanted->skill->name }}
            </span>
          @empty
            <span class="text-secondary small">Belum ada skill yang ingin dipelajari.</span>
          @endforelse
        </div>
      </div>
    </div>

    <!-- Skill Battles Arena Card -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header bg-transparent border-bottom d-flex align-items-center justify-content-between py-3">
        <h5 class="card-title fw-bold mb-0">
          <i class="bi bi-lightning-charge text-danger me-2"></i> Skill Battles
        </h5>
        <a href="{{ route('portal.battles.index') }}" class="btn btn-sm btn-outline-danger">Arena</a>
      </div>
      <div class="card-body p-0">
        @if($battles->isEmpty())
          <div class="p-3 text-center text-secondary small">
            Belum pernah mengikuti battle kuis skill. Tantang anggota lain untuk menguji keahlianmu!
          </div>
        @else
          <ul class="list-group list-group-flush small">
            @foreach($battles as $battle)
              <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                <div>
                  <div class="fw-semibold">{{ $battle->skill->name }}</div>
                  <small class="text-muted">Lawan: {{ $battle->player1_id === $user->id ? ($battle->player2->name ?? 'Menunggu') : $battle->player1->name }}</small>
                </div>
                <div>
                  @if($battle->winner_id === $user->id)
                    <span class="badge text-bg-success"><i class="bi bi-trophy me-1"></i>Menang</span>
                  @elseif($battle->winner_id && $battle->winner_id !== $user->id)
                    <span class="badge text-bg-danger">Kalah</span>
                  @else
                    <span class="badge text-bg-secondary">{{ ucfirst($battle->status) }}</span>
                  @endif
                </div>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>

    <!-- Community Reviews -->
    <div class="card shadow-sm border-0">
      <div class="card-header bg-transparent border-bottom py-3">
        <h5 class="card-title fw-bold mb-0">
          <i class="bi bi-star-fill text-warning me-2"></i> Ulasan Rekan Belajar
        </h5>
      </div>
      <div class="card-body">
        @forelse($reviews as $rev)
          <div class="border-bottom pb-2 mb-2">
            <div class="d-flex justify-content-between align-items-center">
              <span class="fw-semibold small">{{ $rev->reviewer->name ?? 'Pengguna' }}</span>
              <div>
                @for($s = 1; $s <= 5; $s++)
                  <i class="bi bi-star{{ $s <= $rev->rating ? '-fill text-warning' : ' text-muted' }} fs-8"></i>
                @endfor
              </div>
            </div>
            <p class="small text-secondary mb-0 mt-1">"{{ $rev->comment ?: 'Puas dengan sesi pertukaran ilmu!' }}"</p>
          </div>
        @empty
          <p class="text-secondary small mb-0 text-center py-2">Belum ada ulasan yang diterima.</p>
        @endforelse
      </div>
    </div>
  </div>
</div>
@endsection
