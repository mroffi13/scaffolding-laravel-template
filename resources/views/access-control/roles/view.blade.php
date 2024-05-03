@extends('layouts.app')

@section('title', 'Roles - Access Control')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Roles</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item active"><a href="{{ route('roles') }}">Roles</a></div>
                    <div class="breadcrumb-item">
                        @if (!empty($role))
                            View Role #{{ $role->id }}
                        @endif
                    </div>
                </div>
            </div>
            @if ($errors->any())
                <x-app-session-status :success="true" :message="$errors->first()" :toastr="'error'" />
            @endif
            <div class="section-body">
                <h2 class="section-title">
                    @if (!empty($role))
                        View Role {{ $role->display_name }}
                    @endif
                </h2>
                <div class="card">
                    <div class="card-header">
                        @if (!empty($role))
                            View Role {{ $role->display_name }}
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Role Name</label>
                            <p>{{ $role?->display_name ?? '-' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Description</label>
                            <p>{{ $role?->description ?? '-' }}</p>
                        </div>
                        <div class="section-title">
                            Permissions
                        </div>
                        <div class="list-role">
                            @foreach ($role->permissions as $permission)
                                <span class="badge badge-primary mb-2">{{ $permission->display_name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                        @if ($userLogin->isAbleTo('acl-update'))
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary">Edit</a>
                        @endif
                        <a href="{{ route('roles') }}" class="btn btn-secondary">Back</a>
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
