@extends('layouts.admin')

@section('title', 'Battle Details #'.$battle->id.' | SkillSwap Admin')

@section('content-header')
<div class="row align-items-center mb-3">
  <div class="col-sm-6">
    <h2 class="mb-0 fw-bold fs-3 text-body">Battle #{{ $battle->id }} Details</h2>
    <p class="text-secondary small mb-0">Examined questions, player submissions, and final winner</p>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-end bg-transparent p-0 mb-0">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
      <li class="breadcrumb-item"><a href="{{ route('admin.battles.index') }}" class="text-decoration-none">Battles</a></li>
      <li class="breadcrumb-item active" aria-current="page">Battle #{{ $battle->id }}</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<div class="row g-4">
  <div class="col-12 col-lg-8">
    <!-- Battle Matchup Card -->
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-header bg-transparent border-bottom">
        <h5 class="card-title fw-semibold mb-0"><i class="bi bi-lightning-charge me-2 text-danger"></i>Skill Battle Matchup</h5>
      </div>
      <div class="card-body">
        <div class="row g-3 text-center align-items-center">
          <div class="col-12 col-md-5">
            <div class="p-3 border rounded-3 bg-body-secondary {{ $battle->winner_id == $battle->player1_id ? 'border-success border-2 shadow-sm' : '' }}">
              <span class="badge text-bg-primary mb-2">Player 1</span>
              <h5 class="fw-bold mb-1">{{ $battle->player1->name }}</h5>
              <small class="text-secondary">{{ $battle->player1->email }}</small>
              @if($battle->winner_id == $battle->player1_id)
                <div class="mt-2 badge text-bg-success px-3 py-2"><i class="bi bi-trophy-fill me-1"></i> Winner</div>
              @endif
            </div>
          </div>
          <div class="col-12 col-md-2">
            <span class="fw-bold fs-4 text-danger">VS</span>
          </div>
          <div class="col-12 col-md-5">
            <div class="p-3 border rounded-3 bg-body-secondary {{ $battle->winner_id == $battle->player2_id ? 'border-success border-2 shadow-sm' : '' }}">
              <span class="badge text-bg-warning mb-2 text-dark">Player 2</span>
              <h5 class="fw-bold mb-1">{{ $battle->player2->name ?? 'Awaiting Opponent' }}</h5>
              <small class="text-secondary">{{ $battle->player2->email ?? '-' }}</small>
              @if($battle->winner_id == $battle->player2_id)
                <div class="mt-2 badge text-bg-success px-3 py-2"><i class="bi bi-trophy-fill me-1"></i> Winner</div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Battle Answers -->
    <div class="card shadow-sm border-0">
      <div class="card-header bg-transparent border-bottom">
        <h5 class="card-title fw-semibold mb-0"><i class="bi bi-journal-check me-2 text-primary"></i>Answers Submitted ({{ $battle->answers->count() }})</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="ps-3">Player</th>
                <th>Question</th>
                <th>Selected Option</th>
                <th>Result</th>
                <th>Time (sec)</th>
              </tr>
            </thead>
            <tbody>
              @forelse($battle->answers as $answer)
              <tr>
                <td class="ps-3 fw-semibold">{{ $answer->user->name ?? 'User #'.$answer->user_id }}</td>
                <td>{{ Str::limit($answer->question?->question_text ?? 'Question #'.$answer->question_id, 60) }}</td>
                <td><span class="badge text-bg-light border text-uppercase">{{ $answer->selected_option }}</span></td>
                <td>
                  <span class="badge {{ $answer->is_correct ? 'text-bg-success' : 'text-bg-danger' }}">
                    {{ $answer->is_correct ? 'Correct' : 'Wrong' }}
                  </span>
                </td>
                <td>{{ $answer->time_taken_seconds ?? '-' }}s</td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center text-secondary py-3">No answers recorded yet for this battle.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-4">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-transparent border-bottom">
        <h5 class="card-title fw-semibold mb-0"><i class="bi bi-info-circle me-2 text-info"></i>Battle Overview</h5>
      </div>
      <div class="card-body">
        <ul class="list-group list-group-flush small">
          <li class="list-group-item d-flex justify-content-between px-0">
            <span class="text-secondary">Tested Skill</span>
            <span class="fw-bold text-primary">{{ $battle->skill->name ?? 'Skill #'.$battle->skill_id }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between px-0">
            <span class="text-secondary">Status</span>
            <span class="badge text-bg-secondary text-uppercase">{{ $battle->status }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between px-0">
            <span class="text-secondary">Started At</span>
            <span>{{ $battle->started_at ? $battle->started_at->format('M d, Y H:i') : '-' }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between px-0">
            <span class="text-secondary">Finished At</span>
            <span>{{ $battle->finished_at ? $battle->finished_at->format('M d, Y H:i') : '-' }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between px-0">
            <span class="text-secondary">Created Date</span>
            <span>{{ $battle->created_at ? $battle->created_at->format('M d, Y H:i') : '-' }}</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
