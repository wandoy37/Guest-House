@extends('layouts.app')

@section('title')
    Invoice Report
@endsection

@section('content')
    <div class="page-heading">
        <h3>Invoice Report</h3>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>Release Date</th>
                                    <th>Invoice Number</th>
                                    <th>Guest</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($invoice->created_at)->format('d-m-Y') }}</td>
                                        <td>{{ $invoice->invoice_number }}</td>
                                        <td>{{ $invoice->guest->name }}</td>
                                        <td>
                                            <a href="{{ route('invoice.show', $invoice->invoice_number) }}" target="_blank"
                                                class="btn btn-sm btn-primary">
                                                <i class="bi bi-receipt"></i>
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
