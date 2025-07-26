@extends('layouts.app')

@section('title')
    Check Out
@endsection

@section('content')
    <div class="page-heading">
        <h3>Check-Out</h3>
    </div>
    <div class="page-content">
        <div class="row">
            @if (session('success'))
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible show fade">
                        <Strong>Success </Strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if ($bookings->isEmpty())
                <div class="col-12 text-center">
                    <div class="alert alert-light-primary color-primary">
                        Check-Out Data Not Available
                    </div>
                </div>
            @else
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
                                    <div style="font-size: 11pt;" class="text-center">
                                        <span class="badge bg-light-primary my-1">
                                            <strong>Check-In Date</strong>
                                            {{ \Carbon\Carbon::parse($booking->checkin)->format('d-m-Y') }}
                                        </span>
                                        <span class="badge bg-light-primary my-1">
                                            <strong>Check-Out Date</strong>
                                            {{ \Carbon\Carbon::parse($booking->checkout)->format('d-m-Y') }}
                                        </span>
                                    </div>
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
            @endif
        </div>
    </div>
@endsection
