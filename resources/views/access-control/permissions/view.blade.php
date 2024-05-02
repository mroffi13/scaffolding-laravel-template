@extends('layouts.app')

@section('title', 'Permissions - Access Control')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Permissions</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item active"><a href="{{ route('permissions') }}">Permissions</a></div>
                    <div class="breadcrumb-item">
                        @if (!empty($permission))
                            View Permission #{{ $permission->id }}
                        @endif
                    </div>
                </div>
            </div>
            @if ($errors->any())
                <x-app-session-status :success="true" :message="$errors->first()" :toastr="'error'" />
            @endif
            <div class="section-body">
                <h2 class="section-title">
                    @if (!empty($permission))
                        View Permission {{ $permission->display_name }}
                    @endif
                </h2>
                <div class="card">
                    <div class="card-header">
                        @if (!empty($permission))
                            View Permission {{ $permission->display_name }}
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <p>{{ $permission?->name ?? '' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Display Name</label>
                            <p>{{ $permission?->display_name ?? '' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <p>{{ $permission?->description ?? '' }}</p>
                        </div>
                    </div>
                    <div class="card-footer">
                        @if ($userLogin->isAbleTo('acl-update'))
                            <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-primary">Edit</a>
                        @endif
                        <a href="{{ route('permissions') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
    <!-- Page Specific JS File -->
@endpush
