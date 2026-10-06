@extends('layouts.portal')

@section('title', 'Battle #'.$battle->id.' | SkillSwap')

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-lg-8">
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header bg-danger text-white py-3 text-center">
        <h4 class="fw-bold mb-0"><i class="bi bi-lightning-charge-fill me-2"></i>Arena Battle: {{ $battle->skill->name }}</h4>
      </div>
      <div class="card-body p-4">
        <!-- Matchup -->
        <div class="row g-3 text-center align-items-center mb-4">
          <div class="col-5">
            <div class="p-3 border rounded-3 bg-body-secondary">
              <span class="badge text-bg-primary mb-2">Challenger</span>
              <h5 class="fw-bold mb-0">{{ $battle->player1->name }}</h5>
              <small class="text-secondary">{{ $battle->player1->email }}</small>
            </div>
          </div>
          <div class="col-2">
            <span class="fw-bold fs-3 text-danger">VS</span>
          </div>
          <div class="col-5">
            <div class="p-3 border rounded-3 bg-body-secondary">
              <span class="badge text-bg-warning mb-2 text-dark">Opponent</span>
              <h5 class="fw-bold mb-0">{{ $battle->player2->name ?? 'Menunggu Lawan...' }}</h5>
              <small class="text-secondary">{{ $battle->player2->email ?? '-' }}</small>
            </div>
          </div>
        </div>

        <div class="alert alert-info border-0 text-center mb-4">
          <i class="bi bi-info-circle me-1"></i> Status Pertandingan: <strong class="text-uppercase">{{ $battle->status }}</strong>
          @if($battle->winner)
            <div class="mt-2 text-success fw-bold fs-5">
              <i class="bi bi-trophy-fill me-1"></i> Pemenang: {{ $battle->winner->name }}
            </div>
          @endif
        </div>

        <div class="text-center">
          <a href="{{ route('portal.battles.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Arena Battle
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
