@extends('layouts.master')
@section('title')
    notifications
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Dashboard
        @endslot
        @slot('title')
            Notifications
        @endslot
    @endcomponent

    <div class="container">
        <h2 class="mb-4">All Messages</h2>
        <div class="row">
            @foreach ($notifications as $notification)
                <div class="col-12 project-card">
                    <div class="card card-height-100">
                        <div class="card-body">
                            <div class="d-flex flex-column h-100">
                                <div class="d-flex align-items-center pb-2 justify-content-end"
                                    style="align-items: center;border-bottom: 1px solid rgba(128, 128, 128, 0.2); /* abu-abu dengan transparansi 20% */">
                                    <button style="background-color: transparent; border: none; padding: 0; color: inherit;"
                                        data-bs-toggle="modal" data-bs-target="#deleteRecordModal"
                                        onclick="setNotificationIdToDelete('{{ $notification->id }}')">
                                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Remove
                                    </button>

                                </div>
                                <div class="d-flex align-items-center mb-2 pt-3">
                                    <div class="me-3">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-success-subtle rounded p-2">
                                                <img src="{{ URL::asset('build/images/message.png') }}"
                                                    style="width: 50px;height:40px" alt="" class="img-fluid p-1">
                                            </span>
                                        </div>
                                    </div>
                                    <h5 class="mb-1 fs-15"><a href="apps-projects-overview"
                                            class="text-body">{{ $notification->title }}</a></h5>
                                </div>
                                <p class="text-muted text-truncate-two-lines mb-3 mt-3">
                                    {{ $notification->message }}</p>

                            </div>

                        </div>
                        <!-- end card body -->
                        <div class="card-footer bg-transparent border-top-dashed py-2">
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="">
                                    <div class="text-muted">
                                        <i class="ri-calendar-event-fill me-1 align-bottom"></i>
                                        {{ \Carbon\Carbon::parse($notification->created_at)->format('d F Y') }}

                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- end card footer -->
                    </div>
                    <!-- end card -->
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-end mt-3">
            <div class="col-sm-6">
                <div
                    class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                    @if ($notifications->onFirstPage())
                        <div class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </div>
                    @else
                        <div class="page-item">
                            <a href="{{ $notifications->appends(request()->except('page'))->previousPageUrl() }}"
                                class="page-link" id="page-prev">Previous</a>
                        </div>
                    @endif

                    <!-- Page Numbers -->
                    <span id="page-num" class="pagination">
                        @foreach ($notifications->links()->elements[0] as $page => $url)
                            @if ($page == $notifications->currentPage())
                                <span class="page-item active"><span class="page-link">{{ $page }}</span></span>
                            @else
                                <a href="{{ $notifications->appends(request()->except('page'))->url($page) }}"
                                    class="page-item"><span class="page-link">{{ $page }}</span></a>
                            @endif
                        @endforeach
                    </span>

                    @if ($notifications->hasMorePages())
                        <div class="page-item">
                            <a href="{{ $notifications->appends(request()->except('page'))->nextPageUrl() }}"
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
        <!-- Modal Hapus -->
        <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-labelledby="deleteRecordLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" id="deleteRecord-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-5 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                            colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px">
                        </lord-icon>
                        <div class="mt-4 text-center">
                            <h4 class="fs-semibold">You are about to delete a notification?</h4>
                            <p class="text-muted fs-14 mb-4 pt-1">Deleting this notification will remove it permanently
                                from the database.</p>
                            <div class="hstack gap-2 justify-content-center remove">
                                <button class="btn btn-link link-success fw-medium text-decoration-none"
                                    data-bs-dismiss="modal">
                                    <i class="ri-close-line me-1 align-middle"></i> Close
                                </button>
                                <!-- Form untuk hapus notifikasi -->
                                <form id="delete-form" method="POST" action="/message/delete">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="notification_id" id="notification-id-to-delete">
                                    <button type="submit" class="btn btn-danger" id="delete-record">Yes, Delete
                                        It!!</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--end delete modal -->
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/list.js/list.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/list.pagination.js/list.pagination.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/crm-companies.init.js') }}"></script>
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        function setNotificationIdToDelete(id) {
            document.getElementById('notification-id-to-delete').value = id;
        }

        @if (session('success'))
            <
            div class = "alert alert-success" >
            {{ session('success') }}
                <
                /div>
        @endif
    </script>
@endsection
