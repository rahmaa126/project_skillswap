@extends('layouts.admin')

@section('title', 'AI Engine Logs | SkillSwap Admin')

@section('content-header')
<div class="row align-items-center mb-3">
  <div class="col-sm-6">
    <h2 class="mb-0 fw-bold fs-3 text-body">AI Engine Logs</h2>
    <p class="text-secondary small mb-0">Audit AI matching queries, battle question generations, and token metrics</p>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-end bg-transparent p-0 mb-0">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">AI Logs</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<!-- Metric Cards -->
<div class="row g-3 mb-4">
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card shadow-sm border-0 border-start border-primary border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">AI Executions</span>
          <h3 class="fw-bold mb-0 text-primary">{{ $totalLogs }}</h3>
        </div>
        <div class="bg-primary-subtle text-primary rounded-circle p-3">
          <i class="bi bi-cpu fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card shadow-sm border-0 border-start border-success border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Successful</span>
          <h3 class="fw-bold mb-0 text-success">{{ $successLogs }}</h3>
        </div>
        <div class="bg-success-subtle text-success rounded-circle p-3">
          <i class="bi bi-check-circle fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card shadow-sm border-0 border-start border-danger border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Failed</span>
          <h3 class="fw-bold mb-0 text-danger">{{ $failedLogs }}</h3>
        </div>
        <div class="bg-danger-subtle text-danger rounded-circle p-3">
          <i class="bi bi-x-circle fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="card shadow-sm border-0 border-start border-info border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Tokens Consumed</span>
          <h3 class="fw-bold mb-0 text-info">{{ number_format($totalTokens) }}</h3>
        </div>
        <div class="bg-info-subtle text-info rounded-circle p-3">
          <i class="bi bi-speedometer fs-4"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Table Card -->
<div class="card shadow-sm border-0">
  <div class="card-header bg-transparent border-bottom p-3">
    <form method="GET" action="{{ route('admin.ai-logs.index') }}" class="row g-2 align-items-center">
      <div class="col-12 col-md-5">
        <select name="feature" class="form-select">
          <option value="">All Features</option>
          <option value="smart_matching" {{ request('feature') === 'smart_matching' ? 'selected' : '' }}>Smart Matching</option>
          <option value="battle_question" {{ request('feature') === 'battle_question' ? 'selected' : '' }}>Battle Questions</option>
          <option value="skill_recommendation" {{ request('feature') === 'skill_recommendation' ? 'selected' : '' }}>Recommendations</option>
          <option value="moderation" {{ request('feature') === 'moderation' ? 'selected' : '' }}>Content Moderation</option>
        </select>
      </div>
      <div class="col-6 col-md-4">
        <select name="status" class="form-select">
          <option value="">All Statuses</option>
          <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
          <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
        </select>
      </div>
      <div class="col-6 col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-fill">Filter</button>
        @if(request()->anyFilled(['feature', 'status']))
          <a href="{{ route('admin.ai-logs.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
        @endif
      </div>
    </form>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="ps-3">ID</th>
            <th>Feature</th>
            <th>Model</th>
            <th>Invoked By</th>
            <th>Related Skill</th>
            <th>Tokens (Prompt / Comp)</th>
            <th>Status</th>
            <th>Executed At</th>
          </tr>
        </thead>
        <tbody>
          @forelse($logs as $log)
          <tr>
            <td class="ps-3 fw-bold text-secondary">#{{ $log->id }}</td>
            <td>
              <span class="badge text-bg-light border text-uppercase">
                {{ str_replace('_', ' ', $log->feature) }}
              </span>
            </td>
            <td>
              <span class="fw-semibold">{{ $log->model }}</span>
            </td>
            <td>
              <small>{{ $log->user->name ?? ($log->user_id ? 'User #'.$log->user_id : 'System') }}</small>
            </td>
            <td>
              <small>{{ $log->skill->name ?? ($log->skill_id ? 'Skill #'.$log->skill_id : '-') }}</small>
            </td>
            <td>
              <div class="small">
                <span class="text-secondary">{{ $log->prompt_tokens }}</span> /
                <span class="text-secondary">{{ $log->completion_tokens }}</span>
                <span class="badge bg-secondary-subtle text-secondary ms-1">{{ $log->prompt_tokens + $log->completion_tokens }} total</span>
              </div>
            </td>
            <td>
              <span class="badge {{ $log->status === 'success' ? 'text-bg-success' : 'text-bg-danger' }}">
                {{ strtoupper($log->status) }}
              </span>
              @if($log->error_message)
                <div class="small text-danger mt-1">{{ Str::limit($log->error_message, 40) }}</div>
              @endif
            </td>
            <td class="small text-secondary">
              {{ $log->created_at ? $log->created_at->format('M d, Y H:i:s') : '-' }}
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center text-secondary py-4">No AI logs found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($logs->hasPages())
  <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-end">
    {{ $logs->links() }}
  </div>
  @endif
</div>
@endsection
