@extends('layouts.app')

@section('title')
    Room
@endsection

@section('content')
    <div class="page-heading">
        <div class="container">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Manage Rooms</h3>
                        <p class="text-subtitle text-muted">Kelola Kamar</p>
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



                        <a href="{{ route('room.create') }}" class="btn btn-primary mb-4">
                            Create a new room
                        </a>

                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped" id="table1">
                                    <thead>
                                        <tr>
                                            <th>Room Name/Number</th>
                                            <th>Class</th>
                                            <th>Photo</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rooms as $room)
                                            <tr>
                                                <td>{{ $room->name }}</td>
                                                <td>{{ $room->class }}</td>
                                                <td>
                                                    <a href="{{ asset('storage/' . $room->photo) }}" target="_blank"
                                                        class="btn btn-md btn-primary">
                                                        <i class="bi bi-image-fill"></i>
                                                    </a>
                                                </td>
                                                <td>{{ number_format($room->price, 0, ',', '.') }}</td>
                                                <td>
                                                    <a href="{{ route('room.edit', $room->id) }}" class="btn btn-md">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <!-- Tombol delete -->
                                                    <button type="button" class="btn btn-md text-danger ms-2 btn-delete"
                                                        data-id="{{ $room->id }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>

                                                    <!-- Form delete disembunyikan -->
                                                    <form id="delete-form-{{ $room->id }}"
                                                        action="{{ route('room.destroy', $room->id) }}" method="POST"
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
                    const roomId = this.getAttribute('data-id');

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
                            document.getElementById('delete-form-' + roomId).submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
