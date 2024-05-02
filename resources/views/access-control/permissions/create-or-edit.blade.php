@extends('layouts.app')

@section('title', 'Permissions - Access Control')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/izitoast/dist/css/iziToast.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
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
                            Edit Permission #{{ $permission->id }}
                        @else
                            Create Permission
                        @endif
                    </div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">
                    @if (!empty($permission))
                        Edit Permission {{ $permission->name }}
                    @else
                        Create Permission
                    @endif
                </h2>
                <form
                    action="@if (!empty($permission)) {{ route('permissions.update', $permission->id) }}@else{{ route('permissions.store') }} @endif"
                    method="POST">
                    <div class="card">
                        <div class="card-header">
                            @if (!empty($permission))
                                Form Edit Permission {{ $permission->display_name }}
                            @else
                                Form Create Permission
                            @endif
                        </div>
                        <div class="card-body">
                            @csrf
                            @if (!empty($permission))
                                @method('PATCH')
                            @endif
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Module</label>
                                <div class="col-sm-12 col-md-7">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            @php
                                                $permissions_option = [
                                                    'create' => 'Create',
                                                    'read' => 'Read',
                                                    'update' => 'Update',
                                                    'delete' => 'Delete',
                                                    'menu' => 'Menu',
                                                ];
                                            @endphp
                                            <select name="permission" class="form-control">
                                                @foreach ($permissions_option as $perm => $label)
                                                    <option value="{{ $perm }}"
                                                        {{ old('permission', explode('-', $permission?->name ?? '')[count(explode('-', $permission?->name ?? ''))-1] ?? '') == $perm ? 'selected' : '' }}>
                                                        {{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @php
                                            $perm_name = !empty($permission->name)
                                                ? explode('-', $permission?->name)
                                                : [];
                                            if (!empty($perm_name)) {
                                                unset($perm_name[count($perm_name) - 1]);
                                            }
                                        @endphp
                                        <input type="text" name="module"
                                            value="{{ old('module', ucwords(implode(' ', $perm_name)) ?? '') }}"
                                            placeholder="Name .."
                                            class="form-control @error('module') is-invalid @enderror">
                                        @error('module')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Description</label>
                                <div class="col-sm-12 col-md-7">
                                    <textarea name="description" id="" cols="30" rows="10" placeholder="Description .."
                                        class="form-control @error('description') is-invalid @enderror">{{ old('description', $permission?->description ?? '') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary note-btn">Submit</button>
                            <a href="{{ route('permissions') }}" class="btn btn-secondary">Cancel</a>
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
