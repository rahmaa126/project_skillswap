@extends('layouts.admin')

@section('title', 'Reviews & Ratings | SkillSwap Admin')

@section('content-header')
<div class="row align-items-center mb-3">
  <div class="col-sm-6">
    <h2 class="mb-0 fw-bold fs-3 text-body">Reviews & Ratings</h2>
    <p class="text-secondary small mb-0">Community feedback, star ratings, and moderation</p>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-end bg-transparent p-0 mb-0">
      <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Reviews</li>
    </ol>
  </div>
</div>
@endsection

@section('content')
<!-- Metric Cards -->
<div class="row g-3 mb-4">
  <div class="col-12 col-sm-4">
    <div class="card shadow-sm border-0 border-start border-warning border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Total Reviews</span>
          <h3 class="fw-bold mb-0 text-warning">{{ $totalReviews }}</h3>
        </div>
        <div class="bg-warning-subtle text-warning rounded-circle p-3">
          <i class="bi bi-star-fill fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-4">
    <div class="card shadow-sm border-0 border-start border-success border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Avg Community Rating</span>
          <h3 class="fw-bold mb-0 text-success">{{ $avgRating }} / 5.0</h3>
        </div>
        <div class="bg-success-subtle text-success rounded-circle p-3">
          <i class="bi bi-award fs-4"></i>
        </div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-4">
    <div class="card shadow-sm border-0 border-start border-danger border-4">
      <div class="card-body d-flex align-items-center justify-content-between p-3">
        <div>
          <span class="text-secondary small text-uppercase fw-semibold">Hidden / Moderated</span>
          <h3 class="fw-bold mb-0 text-danger">{{ $hiddenReviews }}</h3>
        </div>
        <div class="bg-danger-subtle text-danger rounded-circle p-3">
          <i class="bi bi-eye-slash fs-4"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Table Card -->
<div class="card shadow-sm border-0">
  <div class="card-header bg-transparent border-bottom p-3">
    <form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-2 align-items-center">
      <div class="col-12 col-md-5">
        <select name="rating" class="form-select">
          <option value="">All Star Ratings</option>
          @for($r = 5; $r >= 1; $r--)
            <option value="{{ $r }}" {{ request('rating') == $r ? 'selected' : '' }}>{{ $r }} Stars</option>
          @endfor
        </select>
      </div>
      <div class="col-6 col-md-4">
        <select name="visibility" class="form-select">
          <option value="">All Visibility</option>
          <option value="visible" {{ request('visibility') === 'visible' ? 'selected' : '' }}>Public / Visible</option>
          <option value="hidden" {{ request('visibility') === 'hidden' ? 'selected' : '' }}>Hidden / Moderated</option>
        </select>
      </div>
      <div class="col-6 col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary flex-fill">Filter</button>
        @if(request()->anyFilled(['rating', 'visibility']))
          <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></a>
        @endif
      </div>
    </form>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="ps-3">Reviewer</th>
            <th>Reviewee</th>
            <th>Rating</th>
            <th>Comment</th>
            <th>Visibility</th>
            <th>Date</th>
            <th class="text-end pe-3">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($reviews as $review)
          <tr class="{{ $review->is_hidden ? 'opacity-75 bg-body-secondary' : '' }}">
            <td class="ps-3 fw-semibold">
              {{ $review->reviewer->name ?? 'User #'.$review->reviewer_id }}
            </td>
            <td class="fw-semibold">
              {{ $review->reviewee->name ?? 'User #'.$review->reviewee_id }}
            </td>
            <td>
              <div class="text-nowrap">
                @for($s = 1; $s <= 5; $s++)
                  <i class="bi bi-star{{ $s <= $review->rating ? '-fill text-warning' : ' text-muted' }}"></i>
                @endfor
                <span class="ms-1 fw-bold">{{ $review->rating }}.0</span>
              </div>
            </td>
            <td>
              <div class="small text-body">{{ $review->comment ?: 'No textual review provided.' }}</div>
            </td>
            <td>
              <form action="{{ route('admin.reviews.toggle-hide', $review) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-sm badge {{ $review->is_hidden ? 'text-bg-danger' : 'text-bg-success' }} border-0">
                  <i class="bi bi-{{ $review->is_hidden ? 'eye-slash' : 'eye' }} me-1"></i>{{ $review->is_hidden ? 'Hidden' : 'Visible' }}
                </button>
              </form>
            </td>
            <td class="small text-secondary">
              {{ $review->created_at ? $review->created_at->format('M d, Y') : '-' }}
            </td>
            <td class="text-end pe-3">
              <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Delete this review permanently?')" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center text-secondary py-4">No reviews recorded yet.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if($reviews->hasPages())
  <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-end">
    {{ $reviews->links() }}
  </div>
  @endif
</div>
@endsection
