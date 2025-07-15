@extends('layouts.app')

@section('title')
    Check Out
@endsection

@section('content')
    <div class="page-heading">
        <div class="container">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Check-Out</h3>
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
                    <div class="col-md-12">
                        <div class="btn-group mb-3 float-end" role="group" aria-label="Filter Status">
                            <a href="{{ route('check.in') }}"
                                class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }}">
                                All
                            </a>
                            <a href="{{ route('check.in', ['status' => 'available']) }}"
                                class="btn {{ request('status') == 'available' ? 'btn-primary' : 'btn-outline-primary' }}">
                                Available
                            </a>
                            <a href="{{ route('check.in', ['status' => 'checkin']) }}"
                                class="btn {{ request('status') == 'checkin' ? 'btn-primary' : 'btn-outline-primary' }}">
                                Check-In
                            </a>
                            <a href="{{ route('check.in', ['status' => 'checkout']) }}"
                                class="btn {{ request('status') == 'checkout' ? 'btn-primary' : 'btn-outline-primary' }}">
                                Check-Out
                            </a>
                        </div>
                    </div>
                    @foreach ($bookings as $booking)
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <h4 class="card-title">{{ $booking->room->class }}
                                            <span class="badge bg-primary">
                                                @php
                                                    $status = match ($booking->status) {
                                                        'checkin' => 'Check-In',
                                                        default => ucwords(str_replace('_', ' ', $booking->status)),
                                                    };
                                                @endphp
                                                {{ $status }}
                                            </span>
                                        </h4>
                                        <p class="card-text">
                                            {{ $booking->room->name }}
                                        </p>
                                    </div>
                                    <img class="img-fluid w-100" src="{{ asset('storage/' . $booking->room->photo) }}"
                                        alt="Card image cap">
                                </div>
                                <div class="card-footer text-center">
                                    <a href="{{ route('check.out.show', $booking->id) }}" class="btn btn-outline-primary">
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
@endsection
