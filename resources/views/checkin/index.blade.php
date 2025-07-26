@extends('layouts.app')

@section('title')
    Check In
@endsection

@section('content')
    <div class="page-heading">
        <h3>Check-In</h3>
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
                                        @if ($room->status == 'checkin')
                                            Not Available
                                        @else
                                            {{ ucfirst($room->status) }}
                                        @endif
                                    </span>
                                </h4>
                                <p class="card-text">
                                    {{ $room->name }}
                                </p>
                            </div>
                            <img class="img-fluid w-100" src="{{ asset('storage/' . $room->photo) }}" alt="Card image cap">
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <span class="fst-italic">{{ 'Rp. ' . number_format($room->price, 0, ',', '.') }}</span>
                            @if ($room->status == 'available')
                                <a href="{{ route('check.in.reservation', $room->id) }}"
                                    class="btn btn-outline-primary">Reservation</a>
                            @else
                                <span style="font-style: italic; margin-bottom:14px">Not Available</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
