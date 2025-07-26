@extends('layouts.app')

@section('title')
    Guest Report
@endsection

@section('content')
    <div class="page-heading">
        <h3>Guest Report</h3>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            Filter
                            <form action="{{ route('guest.revenue') }}" method="get">
                                <div class="row pt-4">
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <select class="form-select select2 @error('guest_id') is-invalid @enderror"
                                                name="guest_id">
                                                <option value="">-- Select --</option>
                                                @foreach ($dataGuest as $dg)
                                                    <option value="{{ $dg->id }}" @selected(request('guest_id') == $dg->id)>
                                                        {{ $dg->name }} - {{ $dg->identity_number }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                            <a href="{{ route('guest.revenue') }}" class="btn btn-secondary">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>Guest Name</th>
                                    <th>Check-In</th>
                                    <th>Check-Out</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($guests as $guest)
                                    <tr>
                                        <td>{{ $guest['name'] }}</td>
                                        <td>{{ \Carbon\Carbon::parse($guest['checkin'])->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($guest['checkout'])->format('d-m-Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
