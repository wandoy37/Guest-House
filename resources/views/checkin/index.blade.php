@extends('layouts.app')

@section('title')
    Check In
@endsection

@section('content')
    <div class="page-heading">
        <div class="container">
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1 order-last">
                        <h3>Check-In</h3>
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
                    @foreach ($rooms as $room)
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <h4 class="card-title">{{ $room->class }}
                                            <span
                                                class="badge {{ $room->status == 'available' ? 'bg-primary' : ($room->status == 'checkin' ? 'bg-secondary' : ($room->status == 'checkout' ? 'bg-warning text-dark' : 'bg-light text-dark')) }}">
                                                {{ ucfirst($room->status) }}
                                            </span>
                                        </h4>
                                        <p class="card-text">
                                            {{ $room->name }}
                                        </p>
                                    </div>
                                    <img class="img-fluid w-100" src="{{ asset('storage/' . $room->photo) }}"
                                        alt="Card image cap">
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <span class="fst-italic">{{ 'Rp. ' . number_format($room->price, 0, ',', '.') }}</span>
                                    @if ($room->status == 'available')
                                        <a href="{{ route('check.in.reservation', $room->id) }}"
                                            class="btn btn-outline-primary">Reservation</a>
                                    @else
                                        <a href="http://" class="btn btn-secondary">Detail</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
@endsection
