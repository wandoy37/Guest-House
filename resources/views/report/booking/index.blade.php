@extends('layouts.app')

@section('title')
    Booking Report
@endsection

@section('content')
    <div class="page-heading">
        <h3>Booking Report</h3>
    </div>
    <div class="page-content">
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
                                    <label for="income" class="form-label mb-0">Total Revenue</label>
                                    <input type="text" class="form-control" id="income" readonly
                                        value="Rp. {{ number_format($bookings->sum('room_charge'), 0, ',', '.') }}">
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group has-icon-left">
                                        <label>Total Bookings</label>
                                        <div class="position-relative">
                                            <input type="text" class="form-control" value="{{ $bookings->count() }}"
                                                readonly>
                                            <div class="form-control-icon">
                                                <i class="bi bi-buildings"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-3 d-flex justify-content-end">
                            <form action="{{ route('report.booking.index') }}" method="GET">
                                <div class="row g-3 align-items-end">
                                    <div class="col-md-4">
                                        <label for="period_start" class="form-label mb-0">Period Start</label>
                                        <input type="date" name="period_start" id="period_start" class="form-control"
                                            value="{{ request('period_start') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="period_end" class="form-label mb-0">Period End</label>
                                        <input type="date" name="period_end" id="period_end" class="form-control"
                                            value="{{ request('period_end') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <a href="{{ route('report.booking.index') }}" class="btn btn-secondary">Reset</a>
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
                                            @if ($booking->status == 'checkout')
                                                <span class="badge bg-success">Paid</span>
                                            @else
                                                <span class="badge bg-primary">Check-In</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($booking->room_charge, 0, ',', '.') }}</td>
                                        <td>
                                            <a href="{{ route('report.booking.show', $booking->id) }}"
                                                class="btn btn-sm btn-primary">
                                                Detail
                                            </a>
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
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets') }}/extensions/simple-datatables/style.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/compiled/css/table-datatable.css">
@endpush

@push('script')
    <script src="{{ asset('assets') }}/extensions/simple-datatables/umd/simple-datatables.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Inisialisasi simpleDatatables cukup sekali
            let table1 = document.querySelector('#table1');
            let dataTable = new simpleDatatables.DataTable(table1);

            // Tambahkan konfigurasi custom kalau perlu (adaptasi bootstrap dsb)
            // ... misal: adaptPageDropdown(), adaptPagination(), dst ...
            // Move "per page dropdown" selector element out of label
            // to make it work with bootstrap 5. Add bs5 classes.
            function adaptPageDropdown() {
                const selector = dataTable.wrapper.querySelector(".dataTable-selector")
                selector.parentNode.parentNode.insertBefore(selector, selector.parentNode)
                selector.classList.add("form-select")
            }

            // Add bs5 classes to pagination elements
            function adaptPagination() {
                const paginations = dataTable.wrapper.querySelectorAll(
                    "ul.dataTable-pagination-list"
                )

                for (const pagination of paginations) {
                    pagination.classList.add(...["pagination", "pagination-primary"])
                }

                const paginationLis = dataTable.wrapper.querySelectorAll(
                    "ul.dataTable-pagination-list li"
                )

                for (const paginationLi of paginationLis) {
                    paginationLi.classList.add("page-item")
                }

                const paginationLinks = dataTable.wrapper.querySelectorAll(
                    "ul.dataTable-pagination-list li a"
                )

                for (const paginationLink of paginationLinks) {
                    paginationLink.classList.add("page-link")
                }
            }

            const refreshPagination = () => {
                adaptPagination()
            }

            // Patch "per page dropdown" and pagination after table rendered
            dataTable.on("datatable.init", () => {
                adaptPageDropdown()
                refreshPagination()
            })
            dataTable.on("datatable.update", refreshPagination)
            dataTable.on("datatable.sort", refreshPagination)

            // Re-patch pagination after the page was changed
            dataTable.on("datatable.page", adaptPagination)
        });
    </script>
@endpush
