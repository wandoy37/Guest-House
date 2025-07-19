@extends('layouts.app')

@section('title')
    Booking Report
@endsection

@section('content')
    <div class="page-heading">
        <div class="container">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Booking Report</h3>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="container">
                <div class="row pt-2">
                    <div class="card">
                        <div class="card-header">
                            <h4>Filter</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 mb-3 d-flex justify-content-start">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="income" class="form-label mb-0">Total Incomes</label>
                                            <input type="text" class="form-control" id="income" readonly
                                                value="Rp. {{ number_format($bookings->sum('room_charge'), 0, ',', '.') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="income" class="form-label mb-0">Total Bookings</label>
                                            <input type="text" class="form-control" id="income" readonly
                                                value="{{ $bookings->count() }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 mb-3 d-flex justify-content-end">
                                    <form action="{{ route('report.booking.index') }}" method="GET">
                                        <div class="row g-3 align-items-end">
                                            <div class="col-md-4">
                                                <label for="start_date" class="form-label mb-0">Check-In</label>
                                                <input type="date" name="checkin" id="checkin" class="form-control"
                                                    value="{{ request('checkin') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="end_date" class="form-label mb-0">Check-Out</label>
                                                <input type="date" name="checkout" id="checkout" class="form-control"
                                                    value="{{ request('checkout') }}">
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-primary">Filter</button>
                                                <a href="{{ route('report.booking.index') }}"
                                                    class="btn btn-secondary">Reset</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row pt-2">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>Room</th>
                                        <th>Guest</th>
                                        <th>Check-In/Check-Out</th>
                                        <th>Status</th>
                                        <th>Income</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($bookings->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                Bookings Data Not Available
                                            </td>
                                        </tr>
                                    @else
                                        @foreach ($bookings as $booking)
                                            <tr>
                                                <td>
                                                    {{ $booking->room->class }}<br>
                                                    {{ $booking->room->name }}
                                                </td>
                                                <td>
                                                    {{ $booking->guest->name }}<br>
                                                    {{ $booking->guest->identity_number }}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($booking->checkin)->format('d-m-Y') }} /
                                                    {{ \Carbon\Carbon::parse($booking->checkout)->format('d-m-Y') }}
                                                </td>
                                                <td>
                                                    <span class="badge bg-success">Paid</span>
                                                </td>
                                                <td>{{ number_format($booking->room_charge, 0, ',', '.') }}</td>
                                                <td>
                                                    <a href="{{ route('guest.edit', $booking->id) }}" class="btn btn-md">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-md text-danger ms-2 btn-delete"
                                                        data-id="{{ $booking->id }}">
                                                        <i class="bi bi-trash"></i>
                                                    </button>

                                                    <form id="delete-form-{{ $booking->id }}"
                                                        action="{{ route('guest.destroy', $booking->id) }}" method="POST"
                                                        style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
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
