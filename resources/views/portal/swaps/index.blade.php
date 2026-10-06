@extends('layouts.portal')

@section('title', 'Sesi Pertukaran Skill | SkillSwap')

@section('content')
<div class="row align-items-center mb-4">
  <div class="col-12 col-md-8">
    <h2 class="fw-bold mb-1">Sesi Pertukaran Keahlian (Skill Swap)</h2>
    <p class="text-secondary small mb-0">Kelola jadwal belajar 1-on-1, konfirmasi permintaan, dan riwayat sesi</p>
  </div>
  <div class="col-12 col-md-4 text-md-end mt-2 mt-md-0">
    <a href="{{ route('portal.dashboard') }}" class="btn btn-outline-primary">
      <i class="bi bi-cpu me-1"></i> Cari dari Rekomendasi AI
    </a>
  </div>
</div>

<!-- Tabs Card -->
<div class="card shadow-sm border-0">
  <div class="card-header bg-transparent border-bottom p-0">
    <ul class="nav nav-tabs card-header-tabs mx-3 mt-2" role="tablist">
      <li class="nav-item">
        <button class="nav-link active fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-active" type="button">
          <i class="bi bi-play-circle me-1 text-primary"></i> Sesi Aktif ({{ $activeSessions->count() }})
        </button>
      </li>
      <li class="nav-item">
        <button class="nav-link fw-semibold position-relative" data-bs-toggle="tab" data-bs-target="#tab-incoming" type="button">
          <i class="bi bi-envelope-open me-1 text-warning"></i> Permintaan Masuk
          @if($incomingRequests->isNotEmpty())
            <span class="badge rounded-pill bg-danger ms-1">{{ $incomingRequests->count() }}</span>
          @endif
        </button>
      </li>
      <li class="nav-item">
        <button class="nav-link fw-semibold" data-bs-toggle="tab" data-bs-target="#tab-history" type="button">
          <i class="bi bi-clock-history me-1 text-secondary"></i> Riwayat Selesai
        </button>
      </li>
    </ul>
  </div>

  <div class="card-body p-4">
    <div class="tab-content">
      <!-- Active Sessions -->
      <div class="tab-pane fade show active" id="tab-active">
        @if($activeSessions->isEmpty())
          <div class="text-center py-5 text-secondary">
            <i class="bi bi-calendar-x fs-1 text-muted d-block mb-2"></i>
            <p>Tidak ada sesi pertukaran yang sedang aktif saat ini.</p>
            <a href="{{ route('portal.skills.index') }}" class="btn btn-sm btn-primary">Jelajah Keahlian & Rekan Belajar</a>
          </div>
        @else
          <div class="row g-3">
            @foreach($activeSessions as $session)
              @php
                $isRequester = $session->requester_id === $user->id;
                $partner = $isRequester ? $session->partner : $session->requester;
                $mySkill = $isRequester ? $session->requesterSkill : $session->partnerSkill;
                $partnerSkill = $isRequester ? $session->partnerSkill : $session->requesterSkill;
              @endphp
              <div class="col-12 col-md-6">
                <div class="card border rounded-3 p-3 bg-body-secondary bg-opacity-50">
                  <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                      <img src="{{ $partner->profile?->avatar_url ?? asset('adminlte/assets/img/avatar'.(($partner->id % 4) + 1).'.png') }}" width="44" height="44" class="rounded-circle border" alt="Avatar">
                      <div>
                        <div class="fw-bold">{{ $partner->name }}</div>
                        <small class="text-secondary"><i class="bi bi-geo-alt me-1"></i>{{ $partner->profile?->city ?? 'Indonesia' }}</small>
                      </div>
                    </div>
                    <span class="badge text-bg-primary text-uppercase">{{ $session->status }}</span>
                  </div>

                  <div class="p-2 bg-body rounded border mb-3 small">
                    <div class="d-flex justify-content-between mb-1">
                      <span class="text-secondary">Anda Mengajarkan:</span>
                      <strong class="text-primary">{{ $mySkill->name }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                      <span class="text-secondary">Rekan Mengajarkan:</span>
                      <strong class="text-success">{{ $partnerSkill->name }}</strong>
                    </div>
                  </div>

                  <div class="small text-secondary mb-3">
                    <i class="bi bi-calendar3 me-1"></i> Jadwal: <strong>{{ $session->scheduled_at ? $session->scheduled_at->format('d M Y, H:i') : 'Belum ditentukan' }}</strong>
                  </div>

                  <div class="d-flex gap-2">
                    <form action="{{ route('portal.swaps.complete', $session) }}" method="POST" onsubmit="return confirm('Tandai sesi ini telah selesai dilaksanakan?')" class="flex-fill">
                      @csrf
                      @method('PATCH')
                      <button type="submit" class="btn btn-sm btn-success w-100">
                        <i class="bi bi-check2-circle me-1"></i> Selesaikan Sesi
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      <!-- Incoming Requests -->
      <div class="tab-pane fade" id="tab-incoming">
        @if($incomingRequests->isEmpty())
          <div class="text-center py-5 text-secondary">
            <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
            <p>Tidak ada permintaan sesi masuk yang tertunda.</p>
          </div>
        @else
          <div class="list-group list-group-flush">
            @foreach($incomingRequests as $inc)
              <div class="list-group-item d-flex flex-column flex-md-row justify-content-between align-items-md-center px-0 py-3 gap-3">
                <div class="d-flex align-items-center gap-3">
                  <img src="{{ $inc->requester->profile?->avatar_url ?? asset('adminlte/assets/img/avatar'.(($inc->requester_id % 4) + 1).'.png') }}" width="46" height="46" class="rounded-circle border" alt="Avatar">
                  <div>
                    <h6 class="fw-bold mb-1">{{ $inc->requester->name }}</h6>
                    <small class="text-secondary d-block">
                      Ingin belajar <strong class="text-success">{{ $inc->partnerSkill->name }}</strong> dari Anda, dan menawarkan mengajar <strong class="text-primary">{{ $inc->requesterSkill->name }}</strong>.
                    </small>
                    <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $inc->created_at ? $inc->created_at->diffForHumans() : '' }}</small>
                  </div>
                </div>
                <div class="d-flex gap-2">
                  <form action="{{ route('portal.swaps.respond', $inc) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="accept">
                    <button type="submit" class="btn btn-sm btn-success px-3">
                      <i class="bi bi-check-lg me-1"></i> Terima
                    </button>
                  </form>
                  <form action="{{ route('portal.swaps.respond', $inc) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="reject">
                    <button type="submit" class="btn btn-sm btn-outline-danger px-3">
                      <i class="bi bi-x-lg me-1"></i> Tolak
                    </button>
                  </form>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      <!-- History -->
      <div class="tab-pane fade" id="tab-history">
        @if($pastSessions->isEmpty())
          <div class="text-center py-5 text-secondary">
            <p>Belum ada riwayat sesi selesai.</p>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="ps-3">Rekan Belajar</th>
                  <th>Skill Ditukar</th>
                  <th>Status</th>
                  <th>Selesai Pada</th>
                  <th class="text-end pe-3">Ulasan</th>
                </tr>
              </thead>
              <tbody>
                @foreach($pastSessions as $past)
                  @php
                    $isRequester = $past->requester_id === $user->id;
                    $partner = $isRequester ? $past->partner : $past->requester;
                    $hasReviewed = $past->reviews->where('reviewer_id', $user->id)->isNotEmpty();
                  @endphp
                  <tr>
                    <td class="ps-3 fw-bold">{{ $partner->name }}</td>
                    <td class="small">{{ $past->requesterSkill->name }} &bull; {{ $past->partnerSkill->name }}</td>
                    <td><span class="badge text-bg-secondary text-uppercase">{{ $past->status }}</span></td>
                    <td class="small text-secondary">{{ $past->completed_at ? $past->completed_at->format('d M Y') : '-' }}</td>
                    <td class="text-end pe-3">
                      @if($hasReviewed)
                        <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle me-1"></i>Sudah Diulas</span>
                      @elseif($past->status === 'completed')
                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $past->id }}">
                          <i class="bi bi-star me-1"></i> Beri Ulasan
                        </button>

                        <!-- Review Modal -->
                        <div class="modal fade text-start" id="reviewModal{{ $past->id }}" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content border-0 shadow">
                              <form action="{{ route('portal.reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="session_id" value="{{ $past->id }}">
                                <input type="hidden" name="reviewee_id" value="{{ $partner->id }}">
                                <div class="modal-header">
                                  <h5 class="modal-title fw-bold">Ulasan untuk {{ $partner->name }}</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                  <div class="mb-3">
                                    <label class="form-label fw-semibold">Rating Bintang (1 - 5)</label>
                                    <select name="rating" class="form-select" required>
                                      <option value="5" selected>⭐⭐⭐⭐⭐ (5 - Sangat Puas)</option>
                                      <option value="4">⭐⭐⭐⭐ (4 - Bagus)</option>
                                      <option value="3">⭐⭐⭐ (3 - Cukup)</option>
                                      <option value="2">⭐⭐ (2 - Kurang)</option>
                                      <option value="1">⭐ (1 - Buruk)</option>
                                    </select>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label fw-semibold">Testimoni / Ulasan</label>
                                    <textarea name="comment" class="form-control" rows="3" placeholder="Ceritakan pengalaman belajar Anda bersama rekan ini..."></textarea>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                  <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      @else
                        <span class="text-muted small">-</span>
                      @endif
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
@endsection
