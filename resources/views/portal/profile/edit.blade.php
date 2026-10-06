@extends('layouts.portal')

@section('title', 'Edit Profil Saya | SkillSwap')

@section('content')
<div class="row justify-content-center">
  <div class="col-12 col-lg-8">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-transparent border-bottom py-3">
        <h4 class="card-title fw-bold mb-0"><i class="bi bi-person-gear me-2 text-primary"></i>Pengaturan Profil Saya</h4>
        <small class="text-secondary">Perbarui informasi diri Anda agar rekan belajar lebih mudah mengenal Anda</small>
      </div>
      <form action="{{ route('portal.profile.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body p-4">
          <div class="row g-3">
            <div class="col-12 text-center mb-3">
              <img
                src="{{ $user->profile?->avatar_url ?? asset('adminlte/assets/img/avatar'.(($user->id % 4) + 1).'.png') }}"
                alt="{{ $user->name }}"
                width="90"
                height="90"
                class="rounded-circle border border-2 shadow-sm mb-2"
              />
              <div class="small text-secondary">Peran: <span class="badge text-bg-primary text-uppercase">{{ $user->role }}</span></div>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
              @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold">Alamat Email <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
              @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold">Kota / Domisili</label>
              <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" placeholder="e.g. Jakarta, Bandung, Surabaya..." value="{{ old('city', $user->profile?->city) }}">
              @error('city') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold">Nomor WhatsApp / Telepon</label>
              <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="e.g. 08123456789" value="{{ old('phone', $user->profile?->phone) }}">
              @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
              <label class="form-label fw-semibold">Bio / Perkenalan Singkat</label>
              <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="3" placeholder="Ceritakan latar belakang, ketertarikan, dan pengalaman Anda...">{{ old('bio', $user->profile?->bio) }}</textarea>
              @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
              <hr class="my-2">
              <h6 class="fw-bold text-secondary mb-3"><i class="bi bi-shield-lock me-1"></i> Ganti Password <small class="fw-normal text-muted">(kosongkan jika tidak ingin mengubah)</small></h6>
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold">Password Baru</label>
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter">
              @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12 col-md-6">
              <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
              <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
            </div>
          </div>
        </div>
        <div class="card-footer bg-transparent border-top d-flex justify-content-between p-3">
          <a href="{{ route('portal.dashboard') }}" class="btn btn-outline-secondary">Kembali</a>
          <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i> Simpan Profil</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
