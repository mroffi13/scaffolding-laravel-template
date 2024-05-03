@extends('layouts.app')

@section('title', 'Roles - Access Control')

@push('style')
    <!-- CSS Libraries -->
    {{-- <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/bootstrap.dataTables.min.css') }}" />
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Roles</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Roles</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Data Roles</h2>
                <p class="section-lead">Table of data Roles.</p>
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('roles.create') }}" class="btn btn-primary note-btn">Create Role <i
                                class="fas fa-plus"></i></a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-striped table-md table" id="dataList" data-url="{{ route('roles.get-data') }}">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('library/datatables/media/js/bootstrap.dataTables.min.js') }}"></script>

    <!-- Page Specific JS File -->
    @vite(['resources/js/access-control/roles/index.js'])
@endpush
