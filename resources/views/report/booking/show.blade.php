@extends('layouts.app')

@section('title')
    Report Booking Detail - {{ $booking->id }}
@endsection

@section('content')
    <div class="page-heading">
        <div class="container">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Report Booking Detail</h3>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="container">
                <hr>
                <div class="row py-2">

                    <div class="col-md-12">
                        <a href="{{ route('report.booking.index') }}" class="btn btn-primary mb-4">
                            Back
                        </a>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body">
                                    <h4 class="card-title">{{ $booking->room->class }}</h4>
                                    <p class="card-text">Room Number - {{ $booking->room->name }}</p>
                                    <p class="card-text">Fasilitas : {{ $booking->room->facility }}</p>
                                </div>
                                <img class="img-fluid w-100" src="{{ asset('storage/' . $booking->room->photo) }}"
                                    alt="Card image cap">
                            </div>
                            <div class="card-footer d-flex justify-content-center">
                                <h2 class="fst-italic">{{ 'Rp. ' . number_format($booking->room->price, 0, ',', '.') }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="fw-bold">
                                    Data Booking
                                    @if ($booking->status == 'cancel')
                                        <span
                                            class="badge bg-secondary">{{ $booking->status == 'checkout' ? 'Check-Out' : ucfirst($booking->status) }}</span>
                                    @else
                                        <span
                                            class="badge bg-success">{{ $booking->status == 'checkout' ? 'Check-Out' : ucfirst($booking->status) }}</span>
                                    @endif
                                </h4>
                                <input type="text" name="booking_id" value="{{ $booking->id }}" hidden>
                                <input type="text" name="user_id" value="{{ auth()->user()->id }}" hidden>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="fw-bold">Data Guest</label>
                                    <input type="text" class="form-control"
                                        value="{{ $booking->guest->name }} ({{ $booking->guest->type }} - {{ $booking->guest->identity_number }})"
                                        readonly>
                                </div>
                                <div class="row py-2">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Check-In</label>
                                            <input type="date" class="form-control"
                                                value="{{ $booking->checkin_format }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Check-Out</label>
                                            <input type="date" class="form-control"
                                                value="{{ $booking->checkout_format }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Number of days</label>
                                            <input type="number" class="form-control" placeholder="Number of days"
                                                value="{{ $booking->day }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Room Charge</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control" placeholder="Room Charge"
                                            value="{{ number_format($booking->room_charge, 0, ',', '.') }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Deposit</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control" placeholder="Deposit"
                                            value="{{ number_format($booking->deposit, 0, ',', '.') }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Total Payment</label>
                                    <div class="input-group mb-3">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control" placeholder="Total payment"
                                            value="{{ number_format($booking->total_payment, 0, ',', '.') }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Booking Log --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>History</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Receptionist</th>
                                                <th>Action</th>
                                                <th>Note</th>
                                                <th>Timestamp</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($booking->logs as $log)
                                                <tr>
                                                    <td>{{ $log->user->username }}</td>
                                                    <td>{{ $log->action }}</td>
                                                    <td>{{ $log->description }}</td>
                                                    <td>{{ $log->created_at }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkoutButtons = document.querySelectorAll('.btn-checkout');

            checkoutButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const roomId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Are you sure you want to checkout?',
                        text: "This will end the guest's stay and the room will be available for cleaning!",
                        icon: 'primary',
                        showCancelButton: true,
                        confirmButtonColor: '#435ebe',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, checkout it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit form sesuai ID
                            document.getElementById('checkout-form-' + roomId).submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
