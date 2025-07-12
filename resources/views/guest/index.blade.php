@extends('layouts.app')

@section('title')
    Guest
@endsection

@section('content')
    <div class="page-heading">
        <div class="container">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Manage Guest</h3>
                        <p class="text-subtitle text-muted">Kelola Tamu</p>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible show fade">
                                <Strong>Success </Strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif



                        <a href="{{ route('guest.create') }}" class="btn btn-primary mb-4">
                            New guest
                        </a>

                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped" id="table1">
                                    <thead>
                                        <tr>
                                            <th>Identity type</th>
                                            <th>Identity number</th>
                                            <th>Name</th>
                                            <th>Address</th>
                                            <th>Email / Phone</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($guests as $guest)
                                            <tr>
                                                <td>{{ $guest->type }}</td>
                                                <td>{{ $guest->identity_number }}</td>
                                                <td>{{ $guest->name }}</td>
                                                <td>{{ $guest->address }}</td>
                                                <td>{{ $guest->email }} / {{ $guest->phone }}</td>
                                                <td>
                                                    <a href="{{ route('guest.edit', $guest->id) }}" class="btn btn-md">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <!-- Tombol delete -->
                                                    <button type="button" class="btn btn-md text-danger ms-2 btn-delete"
                                                        data-id="{{ $guest->id }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>

                                                    <!-- Form delete disembunyikan -->
                                                    <form id="delete-form-{{ $guest->id }}"
                                                        action="{{ route('guest.destroy', $guest->id) }}" method="POST"
                                                        style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets') }}/vendors/simple-datatables/style.css">
@endpush

@push('script')
    <script src="{{ asset('assets') }}/vendors/simple-datatables/simple-datatables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Simple Datatable
        let table1 = document.querySelector('#table1');
        let dataTable = new simpleDatatables.DataTable(table1);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const guestId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Are you sure you want to delete?',
                        text: "Deleted data cannot be returned!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit form sesuai ID
                            document.getElementById('delete-form-' + guestId).submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
