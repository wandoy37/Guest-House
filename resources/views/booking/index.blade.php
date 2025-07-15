@extends('layouts.app')

@section('title')
    Booking
@endsection

@section('content')
    <div class="page-heading">
        <div class="container">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Booking</h3>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="container">
                <div class="row">
                    @if (session('success'))
                        <div class="col-md-12">
                            <div class="alert alert-success alert-dismissible show fade">
                                <Strong>Success </Strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                    @endif

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped" id="table1">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Room Class</th>
                                            <th>Guest</th>
                                            <th>Check-In</th>
                                            <th>Check-Out</th>
                                            <th>Total Payment (Rp)</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($bookings as $booking)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $booking->room->class }} - {{ $booking->room->name }}</td>
                                                <td>{{ $booking->guest->name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($booking->checkin)->format('d-m-Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($booking->checkout)->format('d-m-Y') }}</td>
                                                <td>{{ number_format($booking->total_payment, 0, ',', '.') }}</td>
                                                <td>
                                                    @php
                                                        $status = match ($booking->status) {
                                                            'checkin' => 'Check-In',
                                                            'checkout' => 'Check-Out',
                                                            default => ucwords(
                                                                str_replace('_', ' ', $booking->status),
                                                            ),
                                                        };

                                                        $badgeClass = match ($booking->status) {
                                                            'checkin' => 'bg-primary',
                                                            'checkout' => 'bg-success',
                                                            default
                                                                => 'bg-secondary', // atau warna lain sesuai kebutuhan
                                                        };
                                                    @endphp

                                                    <span class="badge {{ $badgeClass }}">
                                                        {{ $status }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('booking.show', $booking->id) }}" class="btn btn-md">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
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
