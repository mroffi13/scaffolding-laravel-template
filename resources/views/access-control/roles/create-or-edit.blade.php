@extends('layouts.app')

@section('title', 'Roles - Access Control')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
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
                            Edit Role #{{ $role->id }}
                        @else
                            Create Role
                        @endif
                    </div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">
                    @if (!empty($role))
                        Edit Role {{ $role->name }}
                    @else
                        Create Role
                    @endif
                </h2>
                <form
                    action="@if (!empty($role)) {{ route('roles.update', $role->id) }}@else{{ route('roles.store') }} @endif"
                    method="POST">
                    <div class="card">
                        <div class="card-header">
                            @if (!empty($role))
                                Form Edit Role {{ $role->display_name }}
                            @else
                                Form Create Role
                            @endif
                        </div>
                        <div class="card-body">
                            @csrf
                            @if (!empty($role))
                                @method('PATCH')
                            @endif
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Role Name</label>
                                <div class="col-sm-12 col-md-7">
                                    <input type="text" name="role_name"
                                        value="{{ old('role_name', $role?->display_name ?? '') }}"
                                        placeholder="Role name .." class="form-control @error('role_name') is-invalid @enderror">
                                    @error('role_name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Description</label>
                                <div class="col-sm-12 col-md-7">
                                    <textarea name="description" id="" cols="30" rows="10" placeholder="Description .."
                                        class="form-control @error('description') is-invalid @enderror">{{ old('description', $role?->description ?? '') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="section-title">
                                Permissions
                            </div>
                            <div class="selectgroup selectgroup-pills">
                                @foreach ($permissions as $permission)
                                    <label class="selectgroup-item">
                                        <input type="checkbox" name="permission[]"
                                            value="{{ $permission->id }}" class="selectgroup-input"
                                            {{ !empty($role) && in_array($permission->name, $role?->permissions()?->pluck('name')?->toArray() ?? []) ? 'checked' : '' }}>
                                        <span class="selectgroup-button">{{ $permission->display_name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary note-btn">Submit</button>
                            <a href="{{ route('roles') }}" class="btn btn-secondary">Cancel</a>
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
@endpush
