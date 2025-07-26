@extends('layouts.app')

@section('title')
    Guest
@endsection

@section('content')
    <div class="page-heading">
        <h3>Manage Guest</h3>
    </div>
    <div class="page-content">
        <div class="row">
            <div class="col-lg-12">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible show fade">
                        <Strong>Success </Strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif



                <a href="{{ route('guest.create') }}" class="btn btn-primary mb-4">
                    New guest
                </a>

                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped" id="table1">
                            <thead>
                                <tr>
                                    <th>Identity type</th>
                                    <th>Identity number</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Email / Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($guests as $guest)
                                    <tr>
                                        <td>{{ $guest->type }}</td>
                                        <td>{{ $guest->identity_number }}</td>
                                        <td>{{ $guest->name }}</td>
                                        <td>{{ $guest->address }}</td>
                                        <td>{{ $guest->email }} / {{ $guest->phone }}</td>
                                        <td>
                                            <a href="{{ route('guest.edit', $guest->id) }}" class="btn btn-md">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <!-- Tombol delete -->
                                            <button type="button" class="btn btn-md text-danger ms-2 btn-delete"
                                                data-id="{{ $guest->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                            <!-- Form delete disembunyikan -->
                                            <form id="delete-form-{{ $guest->id }}"
                                                action="{{ route('guest.destroy', $guest->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
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
    <link rel="stylesheet" href="{{ asset('assets') }}/extensions/sweetalert2/sweetalert2.min.css">
@endpush

@push('script')
    <script src="{{ asset('assets') }}/extensions/simple-datatables/umd/simple-datatables.js"></script>
    <script src="{{ asset('assets') }}/extensions/sweetalert2/sweetalert2.min.js"></script>>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const roomId = this.getAttribute('data-id');

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
                            document.getElementById('delete-form-' + roomId).submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
