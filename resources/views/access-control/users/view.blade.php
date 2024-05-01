@extends('layouts.app')

@section('title', 'Users - Access Control')

@push('style')
    <!-- CSS Libraries -->
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
                            View User #{{ $user->id }}
                        @endif
                    </div>
                </div>
            </div>
            @if ($errors->any())
                <x-app-session-status :success="true" :message="$errors->first()" :toastr="'error'" />
            @endif
            <div class="section-body">
                <h2 class="section-title">
                    @if (!empty($user))
                        View User {{ $user->name }}
                    @endif
                </h2>
                <div class="card">
                    <div class="card-header">
                        @if (!empty($user))
                            Form Edit User {{ $user->name }}
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <p>{{ $user?->name ?? '' }}</p>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <p>{{ $user?->email ?? '' }}</p>
                        </div>
                        @if (!empty($user))
                            <div class="form-group">
                                <label class="form-label d-block">Status</label>
                                @switch($user->getAttributes()['active'])
                                    @case(1)
                                        <span class="badge badge-success">{{ $user->active }}</span>
                                    @break

                                    @default
                                        <span class="badge badge-danger">{{ $user->active }}</span>
                                @endswitch
                            </div>
                        @endif

                    </div>
                    <div class="card-footer">
                        @if ($userLogin->isAbleTo('users-update'))
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">Edit</a>
                        @endif
                        <a href="{{ route('users') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
                <div class="section-title">
                    Roles
                </div>
                <div class="card">
                    <div class="card-header">
                        <button class="btn btn-info note-btn" data-toggle="modal" data-target="#modalAcl">Assign Access
                            Control</button>
                    </div>
                    <div class="card-body">
                        <div class="list-role">
                            @foreach ($user->roles as $role)
                                <span class="badge badge-primary">{{ $role->display_name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="section-title">
                    Permissions
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="list-role">
                            @foreach ($user->allPermissions() as $permission)
                                <span class="badge badge-primary mb-2">{{ $permission->display_name }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="modal fade" tabindex="-1" role="dialog" id="modalAcl">
            <div class="modal-dialog modal-lg" role="document">
                <form action="{{ route('users.assign-acl', $user->id) }}" method="POST">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Assign Access Control</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            @method('PATCH')
                            <div class="section">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="section-title">
                                            Roles
                                        </div>
                                        <div class="selectgroup selectgroup-pills">
                                            @foreach ($roles as $role)
                                                <label class="selectgroup-item">
                                                    <input type="checkbox" name="role[]" value="{{ $role->id }}"
                                                        class="selectgroup-input"
                                                        {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">{{ $role->display_name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <div class="section-title">
                                            Permissions
                                        </div>
                                        <div class="selectgroup selectgroup-pills">
                                            @foreach ($permissions as $permission)
                                                <label class="selectgroup-item">
                                                    <input type="checkbox" name="permission[]"
                                                        value="{{ $permission->id }}" class="selectgroup-input"
                                                        {{ $user->isAbleTo($permission->name) ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">{{ $permission->display_name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/izitoast/dist/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
    <!-- Page Specific JS File -->
@endpush
