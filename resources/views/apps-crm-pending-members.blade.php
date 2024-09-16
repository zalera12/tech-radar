@extends('layouts.master')
@section('title')
    @lang('translation.companies')
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Perusahaan
        @endslot
        @slot('title')
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="flex-grow-1">
                            <button class="btn btn-secondary add-btn" data-bs-toggle="modal" data-bs-target="#showModal"><i
                                    class="ri-add-fill me-1 align-bottom"></i> Add Users</button>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="hstack text-nowrap gap-2">
                                <button class="btn btn-soft-danger" id="remove-actions" onClick="deleteMultiple()"><i
                                        class="ri-delete-bin-2-line"></i></button>
                                <button class="btn btn-primary"><i class="ri-filter-2-line me-1 align-bottom"></i>
                                    Filters</button>
                                <button class="btn btn-soft-success">Import</button>
                                <button type="button" id="dropdownMenuLink1" data-bs-toggle="dropdown"
                                    aria-expanded="false" class="btn btn-soft-info"><i class="ri-more-2-fill"></i></button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink1">
                                    <li><a class="dropdown-item" href="#">All</a></li>
                                    <li><a class="dropdown-item" href="#">Last Week</a></li>
                                    <li><a class="dropdown-item" href="#">Last Month</a></li>
                                    <li><a class="dropdown-item" href="#">Last Year</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
        <div class="col-xxl-9">
            <div class="card" id="companyList">
                <div class="card-header">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="input-group mb-3">
                                    <input type="text" name="search" class="form-control search bg-light border-light"
                                        id="searchJob" value="{{ request('search') }}" placeholder="Search for members...">
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
                                <span class="fw-bold">Urutkan Berdasarkan : </span>
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
                                            <td>{{ $pendingMembers->firstItem() + $index }}</td> <!-- Adjust index for pagination -->
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





                            <div class="noresult" style="display: none">
                                <div class="text-center">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                        colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                                    </lord-icon>
                                    <h5 class="mt-2">Sorry! No Result Found</h5>
                                    <p class="text-muted mb-0">We've searched more than 150+ companies We did not find any
                                        companies Mtfor you search.</p>
                                </div>
                            </div>
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
                                    <input type="hidden" name="permission" value="Update Pending Company User">
                                    <input type="hidden" name="idcp" value="{{ $company->id }}">

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



                    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0">
                                <div class="modal-header bg-info-subtle p-3">
                                    <h5 class="modal-title" id="exampleModalLabel">Add User to Company</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                        id="close-modal"></button>
                                </div>
                                <form action="{{ route('company.addUser') }}" method="POST" class="tablelist-form">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" id="company_id" name="company_id"
                                            value="{{ $company->id }}" />
                                        <div class="row g-3">
                                            <div class="col-lg-12">
                                                <div>
                                                    <label for="email-field" class="form-label">Gmail</label>
                                                    <input type="email" name="email" id="email-field"
                                                        class="form-control" placeholder="Enter Gmail" required />
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div>
                                                    <label for="role-field" class="form-label">Role</label>
                                                    <select class="form-select" name="role_id" id="role-field" required>
                                                        <option value="">Select role</option>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}">{{ $role->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success" id="add-btn">Add
                                                User</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--end add modal-->

                    <!-- Delete Confirmation Modal -->
                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1"
                        aria-labelledby="deleteRecordLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" id="deleteRecord-close"
                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-5 text-center">
                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                        colors="primary:#405189,secondary:#f06548"
                                        style="width:90px;height:90px"></lord-icon>
                                    <div class="mt-4 text-center">
                                        <h4 class="fs-semibold">You are about to remove a user from this company?</h4>
                                        <p class="text-muted fs-14 mb-4 pt-1">Removing this user will detach their
                                            association with the company.</p>
                                        <form id="deleteUserForm" action="{{ route('companies.roles.delete') }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" id="userId">
                                            <input type="hidden" name="company_id" id="companyId">
                                            <div class="hstack gap-2 justify-content-center remove">
                                                <button class="btn btn-link link-success fw-medium text-decoration-none"
                                                    data-bs-dismiss="modal">
                                                    <i class="ri-close-line me-1 align-middle"></i> Close
                                                </button>
                                                <button type="submit" class="btn btn-danger"
                                                    id="confirm-delete-record">Yes, Remove User</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
