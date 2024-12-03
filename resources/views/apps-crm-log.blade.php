@extends('layouts.master')
@section('title')
    logs
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            {{ $company->name }}
        @endslot
        @slot('title')
            Logs
        @endslot
    @endcomponent

    <div class="row mt-1">
        <!--end col-->
        <div class="">
            <div class="card" id="companyList">
                <div class="card-header">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="input-group mb-3">
                                    <input type="text" name="search" class="form-control search bg-light border-light"
                                        id="searchJob" value="{{ request('search') }}" placeholder="Search for logs...">
                                    <!-- Memastikan filter tetap dibawa ketika search dilakukan -->
                                    <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                                    <input type="hidden" name="permission" value="Read Change Log">
                                    <input type="hidden" name="idcp" value="{{ $company->id }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="ri-search-line search-icon"></i>
                                    </button>
                                </div>
                            </form>

                        </div>

                        <div class="col-md-auto ms-auto">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold">Sort By : </span>
                                <form action="{{ url()->current() }}" method="GET" id="filterForm">
                                    <!-- Hidden input untuk menjaga parameter yang sudah ada di URL -->
                                    <input type="hidden" name="permission" value="Read Change Log">
                                    <input type="hidden" name="idcp" value="{{ $company->id }}">
                                    <input type="hidden" name="search" value="{{ request('search') }}">

                                    <select class="form-control" style="cursor: pointer" name="sort_order" id="sortOrder"
                                        onchange="this.form.submit()">
                                        <option value="terbaru" {{ request('sort_order') == 'terbaru' ? 'selected' : '' }}>
                                            Terbaru</option>
                                        <option value="terlama" {{ request('sort_order') == 'terlama' ? 'selected' : '' }}>
                                            Terlama</option>
                                        <option value="A-Z" {{ request('sort_order') == 'A-Z' ? 'selected' : '' }}>A-Z
                                        </option>
                                        <option value="Z-A" {{ request('sort_order') == 'Z-A' ? 'selected' : '' }}>Z-A
                                        </option>
                                    </select>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <div class="table-responsive table-card mb-3">
                            @if ($logs->isNotEmpty())
                                <table class="table align-middle table-nowrap mb-0" id="categoryTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">decription</th>
                                            <th scope="col">time</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($logs as $index => $log)
                                            <tr>
                                                <td>{{ $logs->firstItem() + $index }}</td>

                                                <td class="name">{{ $log->name }}</td>
                                                <td class="description">{{ $log->description }}</td>
                                                <td class="time">
                                                    {{ \Carbon\Carbon::parse($log->created_at)->format('d F Y') }}
                                                </td>

                                                <td>
                                                    <ul class="list-inline hstack gap-2 mb-0">
                                                        <li class="list-inline-item" data-bs-toggle="tooltip"
                                                            data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                            <a class="remove-item-btn" data-bs-toggle="modal"
                                                                href="#deleteLogModal" data-id="{{ $log->id }}">
                                                                <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="noresult mt-5">
                                    <div class="text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                            colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                                        </lord-icon>
                                        <h5 class="mt-2">Apologies, No Logs Data Available</h5>
                                        <p class="text-muted mb-0">Unfortunately, there are no logs available to display.
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <div class="col-sm-6">
                                <div
                                    class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                                    @if ($logs->onFirstPage())
                                        <div class="page-item disabled">
                                            <span class="page-link">Previous</span>
                                        </div>
                                    @else
                                        <div class="page-item">
                                            <a href="{{ $logs->appends(request()->except('page'))->previousPageUrl() }}"
                                                class="page-link" id="page-prev">Previous</a>
                                        </div>
                                    @endif

                                    <!-- Page Numbers -->
                                    <span id="page-num" class="pagination">
                                        @foreach ($logs->links()->elements[0] as $page => $url)
                                            @if ($page == $logs->currentPage())
                                                <span class="page-item active"><span
                                                        class="page-link">{{ $page }}</span></span>
                                            @else
                                                <a href="{{ $logs->appends(request()->except('page'))->url($page) }}"
                                                    class="page-item"><span
                                                        class="page-link">{{ $page }}</span></a>
                                            @endif
                                        @endforeach
                                    </span>

                                    @if ($logs->hasMorePages())
                                        <div class="page-item">
                                            <a href="{{ $logs->appends(request()->except('page'))->nextPageUrl() }}"
                                                class="page-link" id="page-next">Next</a>
                                        </div>
                                    @else
                                        <div class="page-item disabled">
                                            <span class="page-link">Next</span>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Modal Hapus -->
                    <div class="modal fade" id="deleteLogModal" tabindex="-1" aria-labelledby="deleteLogModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0">
                                <div class="modal-header bg-danger-subtle p-3">
                                    <h5 class="modal-title" id="deleteLogModalLabel">Delete Log</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="/companies/logs/delete?permission=Delete Log&idcp={{ $company->id }}"
                                    method="POST" id="deleteLogForm">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" value="" id="log_id" name="log_id">
                                    <input type="hidden" value="{{ $company->id }}" id="company_id"
                                        name="company_id">

                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this log?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!--end delete modal -->

                </div>
            </div>
            <!--end card-->
        </div>

    </div>
    <!--end row-->
@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/list.js/list.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/list.pagination.js/list.pagination.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/crm-companies.init.js') }}"></script>
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Handle Delete Category Button Click
            var deleteButtons = document.querySelectorAll('.remove-item-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    var logId = this.getAttribute('data-id');
                    var form = document.getElementById('deleteLogForm');
                    document.getElementById('log_id').value = logId;
                });
            });

        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 1500
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'error',
                text: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 1500
            });
        @endif
    

    </script>
@endsection
