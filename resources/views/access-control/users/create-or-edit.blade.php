@extends('layouts.app')

@section('title', 'Users - Access Control')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Users</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Users</a></div>
                    <div class="breadcrumb-item">
                        @if (!empty($user))
                            Edit User #{{ $user->id }}
                        @else
                            Create User
                        @endif
                    </div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">
                    @if (!empty($user))
                        Edit User {{ $user->name }}
                    @else
                        Create User
                    @endif
                </h2>
                <form
                    action="@if (!empty($user)) {{ route('users.update', $user->id) }}@else{{ route('users.store') }} @endif" method="POST">
                    <div class="card">
                        <div class="card-header">
                            @if (!empty($user))
                                Form Edit User {{ $user->name }}
                            @else
                                Form Create User
                            @endif
                        </div>
                        <div class="card-body">
                            @csrf
                            @if (!empty($user))
                                @method('PATCH')
                            @endif
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Name</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" name="name" value="{{ old('name', $user?->name ?? '') }}"
                                        placeholder="Name .." class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" name="email" value="{{ old('email', $user?->email ?? '') }}"
                                        placeholder="Email .." class="form-control @error('email') is-invalid @enderror">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Password</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="password" name="password" placeholder="Password .."
                                        class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            @if (!empty($user))
                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status</label>
                                    <div class="col-sm-12 col-md-7">
                                        <label class="custom-switch mt-2">
                                            <input type="checkbox" name="custom-switch-checkbox"
                                                {{ old('custom-switch-checkbox', $user?->getAttributes()['active'] ?? 0) == 1 ? 'checked' : '' }}
                                                value="1" class="custom-switch-input">
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">Active</span>
                                        </label>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary note-btn">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
    <!-- Page Specific JS File -->
    @vite(['resources/js/access-control/users/create-or-edit.js'])
@endpush
