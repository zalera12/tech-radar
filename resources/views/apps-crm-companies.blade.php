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
            Companies
        @endslot
        @slot('title')
            Users
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
                    <div
                        class="row g-2 justify-content-between align-items-center flex-md-row align-items-center flex-column">
                        <!-- Form Search -->
                        <div class="col-md-3 col-12 d-flex flex-column align-items-center">
                            <form action="{{ url()->current() }}" method="GET" class="d-flex align-items-center w-100">
                                <div class="input-group mb-0">
                                    <input type="text" name="search" class="form-control search bg-light border-light"
                                        id="searchJob" value="{{ request('search') }}" placeholder="Search for members...">
                                    <!-- Memastikan parameter filter dan sort_order tetap ada saat search dilakukan -->
                                    <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                                    <input type="hidden" name="role_id" value="{{ request('role_id') }}">
                                    <input type="hidden" name="idcp" value="{{ $company->id }}">
                                    <input type="hidden" name="permission" value="Read Company User">

                                    <button class="btn btn-primary" type="submit">
                                        <i class="ri-search-line search-icon"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Form Filter Role -->
                        <div class="col-md-3 col-12 d-flex flex-column align-items-center">
                            <form action="{{ url()->current() }}" method="GET" class="d-flex align-items-center w-100">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                                <input type="hidden" name="idcp" value="{{ $company->id }}">
                                <input type="hidden" name="permission" value="Read Company User">

                                <div class="input-group mb-0 d-flex align-items-center">
                                    <label for="roleFilter" class="form-label me-3">Filter Role</label>
                                    <select class="form-control" name="role_id" id="roleFilter"
                                        onchange="this.form.submit()">
                                        <option value="">-- All Roles --</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>

                        <!-- Form Sort Order -->
                        <div class="col-md-3 col-12 d-flex flex-column align-items-center">
                            <form action="{{ url()->current() }}" method="GET" class="d-flex align-items-center w-100">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="role_id" value="{{ request('role_id') }}">
                                <input type="hidden" name="idcp" value="{{ $company->id }}">
                                <input type="hidden" name="permission" value="Read Company User">

                                <div class="input-group mb-0 d-flex align-items-center">
                                    <label for="sortOrder" class="form-label me-3">Urutkan Berdasarkan</label>
                                    <select class="form-control" name="sort_order" id="sortOrder"
                                        onchange="this.form.submit()">
                                        <option value="terbaru"
                                            {{ request('sort_order') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                        <option value="terlama"
                                            {{ request('sort_order') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                                        <option value="A-Z" {{ request('sort_order') == 'A-Z' ? 'selected' : '' }}>A-Z
                                        </option>
                                        <option value="Z-A" {{ request('sort_order') == 'Z-A' ? 'selected' : '' }}>Z-A
                                        </option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="card-body">
                    <div>
                        <div class="table-responsive table-card mb-3">
                            <table class="table align-middle table-nowrap mb-0" id="customerTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="sort" data-sort="name" scope="col">No</th>
                                        <th class="sort" data-sort="name" scope="col">Photo</th>
                                        <th class="sort" data-sort="owner" scope="col">Name</th>
                                        <th class="sort" data-sort="industry_type" scope="col">Email</th>
                                        <th class="sort" data-sort="role" scope="col">Role</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($companyUsers as $index => $member)
                                        <tr data-id="{{ $member->id }}" data-company-id="{{ $company->id }}"
                                            data-role-id="{{ $member->pivot->role_id }}">
                                            <td>{{ $companyUsers->firstItem() + $index }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <img src="{{ asset($member->photo ? 'storage/' . $member->photo : '/build/images/users/user-dummy-img.jpg') }}"
                                                            alt="User Photo"
                                                            class="avatar-xxs rounded-circle object-fit-cover">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="name">{{ $member->name }}</td>
                                            <td class="email">{{ $member->email }}</td>
                                            <td>
                                                <?php
                                                $roleId = $member->pivot->role_id;
                                                $role = App\Models\Role::find($roleId);
                                                ?>
                                                {{ $role ? $role->name : 'N/A' }}
                                            </td>
                                            <td>
                                                <ul class="list-inline hstack gap-2 mb-0">
                                                    <li class="list-inline-item edit" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top" title="Message">
                                                        <a href="javascript:void(0);" class="text-muted d-inline-block">
                                                            <i class="ri-question-answer-line fs-16"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Edit Role">
                                                        <a class="edit-item-btn" href="#editUserRoleModal"
                                                            data-bs-toggle="modal">
                                                            <i class="ri-pencil-fill align-bottom text-muted"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                        <a class="remove-item-btn" data-bs-toggle="modal"
                                                            href="#deleteRecordModal" data-userId="{{ $member->id }}">
                                                            <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                                        </a>
                                                    </li>
                                                </ul>
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
                                        companies for you search.</p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <div class="col-sm-6">
                                <div
                                    class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                                    @if ($companyUsers->onFirstPage())
                                        <div class="page-item disabled">
                                            <span class="page-link">Previous</span>
                                        </div>
                                    @else
                                        <div class="page-item">
                                            <a href="{{ $companyUsers->appends(request()->except('page'))->previousPageUrl() }}"
                                                class="page-link" id="page-prev">Previous</a>
                                        </div>
                                    @endif

                                    <!-- Page Numbers -->
                                    <span id="page-num" class="pagination">
                                        @foreach ($companyUsers->links()->elements[0] as $page => $url)
                                            @if ($page == $companyUsers->currentPage())
                                                <span class="page-item active"><span
                                                        class="page-link">{{ $page }}</span></span>
                                            @else
                                                <a href="{{ $companyUsers->appends(request()->except('page'))->url($page) }}"
                                                    class="page-item">
                                                    <span class="page-link">{{ $page }}</span>
                                                </a>
                                            @endif
                                        @endforeach
                                    </span>

                                    @if ($companyUsers->hasMorePages())
                                        <div class="page-item">
                                            <a href="{{ $companyUsers->appends(request()->except('page'))->nextPageUrl() }}"
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
                    <!-- Modal Edit Role for User -->
                    <div class="modal fade" id="editUserRoleModal" tabindex="-1"
                        aria-labelledby="editUserRoleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="editUserRoleForm" data-idCompany="{{ $company->id }}"
                                    action="/companies/users/edit?permission=Edit Company User&idcp={{ $company->id }}"
                                    method="POST">
                                    @csrf
                                    <input type="hidden" name="id" id="id-user" value="">
                                    <input type="hidden" name="user" value="{{ $user->name }}">
                                    <input type="hidden" name="company_id" value="{{ $company->id }}"
                                        id="id-company">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editUserRoleModalLabel">Edit User Role</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Select Role -->
                                        <div class="mb-3">
                                            <label for="editUserRole" class="form-label">Role</label>
                                            <select class="form-select" id="editUserRole" name="role_id">
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
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
                                <form action="/companies/user/add?permission=Add Company User&idcp={{ $company->id }}"
                                    method="POST" class="tablelist-form">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" id="company_id" name="company_id"
                                            value="{{ $company->id }}" />
                                            <input type="hidden" id="user" name="user"
                                            value="{{ $user->name }}" />
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
                                        <form id="deleteUserForm"
                                            action="{{ route('companies.roles.delete') }}?permission=Delete Company User&idcp={{ $company->id }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="user" value="{{ $user->name }}" >
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
        // Event listener untuk tombol edit role
        document.querySelectorAll('.edit-item-btn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                const Id = row.getAttribute('data-id');
                const idCompany = row.getAttribute('data-company-id');
                const roleId = row.getAttribute('data-role-id');

                // Set nilai hidden input di form modal
                document.getElementById('id-user').value = Id;
                document.getElementById('id-company').value = idCompany;

                // Set action URL form sesuai dengan ID user
                const form = document.getElementById('editUserRoleForm');

                // Set selected option pada select role berdasarkan roleId dari row
                const selectRole = document.getElementById('editUserRole');

                // Reset option selection untuk memastikan tidak ada yang terpilih secara default
                selectRole.querySelectorAll('option').forEach(option => {
                    option.removeAttribute('selected');
                });

                // Pilih option yang sesuai dengan roleId
                const selectedOption = selectRole.querySelector(`option[value="${roleId}"]`);
                if (selectedOption) {
                    selectedOption.setAttribute('selected', 'selected');
                }

                // Tampilkan modal edit
                const modal = new bootstrap.Modal(document.getElementById('editUserRoleModal'));
                modal.show();
            });
        });

        // Reset form action ketika modal ditutup dan bersihkan elemen-elemen tambahan
        document.getElementById('editUserRoleModal').addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('editUserRoleForm');
            form.action = "/companies/users/edit?permission=Edit Company User&idcp=" + form.getAttribute(
                'data-idCompany');

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
        });

        // Event listener untuk tombol hapus item
        document.querySelectorAll('.remove-item-btn').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-userId');
                const companyId = this.closest('tr').getAttribute('data-company-id');

                // Set nilai hidden input di form
                document.getElementById('userId').value = userId;
                document.getElementById('companyId').value = companyId;

                // Tampilkan modal delete
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteRecordModal'));
                deleteModal.show();
            });
        });

        // Bersihkan elemen setelah modal hapus ditutup
        document.getElementById('deleteRecordModal').addEventListener('hidden.bs.modal', function() {
            const modalBackdrop = document.querySelector('.modal-backdrop');
            if (modalBackdrop) {
                modalBackdrop.remove();
            }
            document.body.classList.remove('modal-open');
            document.body.style.paddingRight = '';
            document.body.style.overflow = '';
        });
    </script>
@endsection
