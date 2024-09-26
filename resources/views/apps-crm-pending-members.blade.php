@extends('layouts.master')
@section('title')
    Pending Members
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
            Pending Members
        @endslot
    @endcomponent

    <h4>Pending Members Page</h4>
    <div class="row mt-3">
        <!--end col-->
        <div class="">
            <div class="card" id="companyList">
                <div class="card-header">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="input-group mb-3">
                                    <input type="text" name="search" class="form-control search bg-light border-light"
                                        id="searchJob" value="{{ request('search') }}"
                                        placeholder="Search for pending members...">
                                    <!-- Memastikan filter tetap dibawa ketika search dilakukan -->
                                    <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                                    <input type="hidden" name="permission" value="Read Pending Company User">
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
                                    <input type="hidden" name="permission" value="Read Pending Company User">
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
                            @if ($pendingMembers->isNotEmpty())
                                <table class="table align-middle table-nowrap mb-0" id="pendingMemberTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Photo</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pendingMembers as $index => $member)
                                            <tr data-id="{{ $member->id }}" data-company-id="{{ $company->id }}"
                                                data-role-id="{{ $member->pivot->role_id }}"
                                                data-status="{{ $member->pivot->status }}">
                                                <td>{{ $pendingMembers->firstItem() + $index }}</td>
                                                <!-- Adjust index for pagination -->
                                                <td>
                                                    <img src="{{ asset($member->photo ? 'storage/' . $member->photo : '/build/images/users/user-dummy-img.jpg') }}"
                                                        alt="User Photo" class="avatar-xxs rounded-circle object-fit-cover">
                                                </td>
                                                <td>{{ $member->name }}</td>
                                                <td>{{ $member->email }}</td>
                                                <td>
                                                    <?php
                                                    $roleId = $member->pivot->role_id;
                                                    $role = App\Models\Role::find($roleId);
                                                    ?>
                                                    {{ $role ? $role->name : 'N/A' }}
                                                </td>
                                                <td>{{ $member->pivot->status }}</td>
                                                <td>
                                                    <a href="#editPendingMemberModal" data-bs-toggle="modal">
                                                        <i class="edit-item-btn ri-pencil-fill align-bottom text-muted"></i>
                                                    </a>
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
                                        <h5 class="mt-2">Apologies, No Pending Members Data Available</h5>
                                        <p class="text-muted mb-0">Unfortunately, there are no Pending Members available to
                                            display.</p>
                                    </div>
                                </div>
                            @endif

                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <div class="col-sm-6">
                                <div
                                    class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                                    @if ($pendingMembers->onFirstPage())
                                        <div class="page-item disabled">
                                            <span class="page-link">Previous</span>
                                        </div>
                                    @else
                                        <div class="page-item">
                                            <a href="{{ $pendingMembers->appends(request()->except('page'))->previousPageUrl() }}"
                                                class="page-link" id="page-prev">Previous</a>
                                        </div>
                                    @endif

                                    <!-- Page Numbers -->
                                    <span id="page-num" class="pagination">
                                        @foreach ($pendingMembers->links()->elements[0] as $page => $url)
                                            @if ($page == $pendingMembers->currentPage())
                                                <span class="page-item active"><span
                                                        class="page-link">{{ $page }}</span></span>
                                            @else
                                                <a href="{{ $pendingMembers->appends(request()->except('page'))->url($page) }}"
                                                    class="page-item"><span
                                                        class="page-link">{{ $page }}</span></a>
                                            @endif
                                        @endforeach
                                    </span>

                                    @if ($pendingMembers->hasMorePages())
                                        <div class="page-item">
                                            <a href="{{ $pendingMembers->appends(request()->except('page'))->nextPageUrl() }}"
                                                class="page-link" id="page-next">Next</a>
                                        </div>
                                    @else
                                        <div class="page-item disabled">
                                            <span class="page-link">Next</span>
                                        </div>
                                    @endif
                                </div>




                            </div><!-- end col -->

                        </div>
                    </div>
                    <!-- Modal Edit Role for User -->
                    <div class="modal fade" id="editPendingMemberModal" tabindex="-1"
                        aria-labelledby="editPendingMemberModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="editPendingMemberForm"
                                    action="{{ route('companies.pendingMember.update', ['member' => ':id']) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')

                                    <!-- Hidden inputs to send permission and idcp -->
                                    <input type="hidden" name="permission" value="Acc Company User">
                                    <input type="hidden" name="idcp" value="{{ $company->id }}">
                                    <input type="hidden" name="user" value="{{ $user->name }}">
                                    <input type="hidden" name="company_id" value="{{ $company->id }}">

                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editPendingMemberModalLabel">Edit Pending Member</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Select Role -->
                                        <div class="mb-3">
                                            <label for="editRole" class="form-label">Role</label>
                                            <select class="form-select" id="editRole" name="role_id">
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Select Status -->
                                        <div class="mb-3">
                                            <label for="editStatus" class="form-label">Status</label>
                                            <select class="form-select" id="editStatus" name="status">
                                                <option value="ACCEPTED">ACCEPTED</option>
                                                <option value="WAITING">WAITING</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
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
            document.querySelectorAll('.edit-item-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const memberId = row.getAttribute('data-id');
                    const roleId = row.getAttribute('data-role-id');
                    const status = row.getAttribute('data-status');

                    const form = document.getElementById('editPendingMemberForm');
                    form.action = form.action.replace(':id', memberId);
                    document.getElementById('editRole').value = roleId;
                    document.getElementById('editStatus').value = status;

                    const modal = new bootstrap.Modal(document.getElementById(
                        'editPendingMemberModal'));
                    modal.show();
                });
            });

            // Menangani modal dengan baik saat ditutup
            const editModal = document.getElementById('editPendingMemberModal');

            // Event listener untuk reset action dan menghapus kelas/gaya overflow
            editModal.addEventListener('hidden.bs.modal', function() {
                // Menghapus backdrop modal jika masih ada
                const modalBackdrop = document.querySelector('.modal-backdrop');
                if (modalBackdrop) {
                    modalBackdrop.remove();
                }

                // Menghapus kelas modal-open dari body
                document.body.classList.remove('modal-open');
                // Reset padding dan style
                document.body.style.paddingRight = '';
                document.body.style.overflow = '';
                // Reset form action ke nilai default
                const form = document.getElementById('editPendingMemberForm');
                form.action = "{{ route('companies.pendingMember.update', ['member' => ':id']) }}";
            });
        });

        @if (session('update_success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('update_success') }}",
                confirmButtonText: 'Oke',
            });
        @endif
    </script>
@endsection
