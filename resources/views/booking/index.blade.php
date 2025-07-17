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
                    {{-- @if (session('success'))
                        <div class="col-md-12">
                            <div class="alert alert-success alert-dismissible show fade">
                                <Strong>Success </Strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                    @endif --}}

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped bookings-table" id="table1">
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
                                                    <div class="btn-group mb-1">
                                                        <div class="dropdown">
                                                            <button class="btn btn-secondary btn-sm dropdown-toggle"
                                                                type="button" id="dropdownMenuButton"
                                                                data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                Action
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <a href="{{ route('booking.show', $booking->id) }}"
                                                                    class="btn btn-md dropdown-item">
                                                                    <i class="bi bi-pencil-square"></i>
                                                                    Update
                                                                </a>
                                                                @unless ($booking->invoice)
                                                                    <button type="button"
                                                                        class="btn btn-md dropdown-item btn-invocie"
                                                                        data-id="{{ $booking->id }}">
                                                                        <i class="bi bi-receipt "></i>
                                                                        Invoice
                                                                    </button>

                                                                    <form id="invocie-form-{{ $booking->id }}"
                                                                        action="{{ route('invoice.store') }}" method="POST"
                                                                        style="display: none;">
                                                                        @csrf
                                                                        <input type="text" name="booking_id"
                                                                            value="{{ $booking->id }}">
                                                                        <input type="text" name="user_id"
                                                                            value="{{ Auth::user()->id }}">
                                                                    </form>
                                                                @endunless

                                                                @isset($booking->invoice)
                                                                    <a href="{{ route('invoice.show', $booking->invoice) }}"
                                                                        target="_blank" class="btn btn-md dropdown-item">
                                                                        <i class="bi bi-receipt"></i>
                                                                        Invoice
                                                                    </a>
                                                                @endisset
                                                            </div>
                                                        </div>
                                                    </div>
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
            const invocieButtons = document.querySelectorAll('.btn-invocie');

            invocieButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const guestId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Are you sure you want to create this invoice?',
                        text: "Once created, the invoice will be generated and cannot be undone!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#435ebe',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, invocie it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit form sesuai ID
                            document.getElementById('invocie-form-' + guestId).submit();
                        }
                    });
                });
            });
        });
    </script>
    @if (session('success'))
        <script>
            // Show success message
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });

            // Open invoice in new tab
            @if (session('open_invoice'))
                window.open('{{ session('open_invoice') }}', '_blank');
            @endif
        </script>
    @endif

    <script>
        $(document).ready(function() {
            $('#bookings-table').DataTable({
                "ordering": false
            });
        });
    </script>
@endpush
