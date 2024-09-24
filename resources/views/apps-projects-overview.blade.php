@extends('layouts.master')
@section('title')
    {{ $company->name }}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card mt-n4">
                <div class="bg-primary-subtle">
                    <div class="card-body pb-0 px-4">
                        <div class="row mb-3">
                            <div class="col-md">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-auto">
                                        <div class="avatar-md">
                                            <div class="avatar-title bg-white rounded-circle">
                                                <img src="{{ asset($company->image ? '/storage/' . $company->image : '/build/images/users/multi-user.jpg') }}"
                                                    alt="" class="avatar-xs"
                                                    style="width: 70px;height:70px;border-radius:50%">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div>
                                            <h4 class="fw-bold">{{ $company->name }}</h4>
                                            <div class="hstack gap-3 flex-wrap">
                                                <div class="fw-medium">Create Date : <span
                                                        class="">{{ $created_date }}</span></div>
                                                <div class="vr"></div>
                                                <div class="fw-medium">Your role in this company : <span
                                                        class="">{{ $role->name }}</span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Menambahkan baris baru untuk tombol di bawah informasi perusahaan -->
                                <div class="row mt-4">
                                    <div class="col-md">
                                        <div
                                            class="d-flex flex-column flex-md-row align-items-center justify-content-start button-container">
                                            <button class="btn btn-primary btn-custom me-2" data-bs-toggle="modal"
                                                data-bs-target="#editCompanyModal">
                                                <i class="ri-pencil-line align-bottom me-1"></i> Edit Company
                                            </button>
                                            <button class="btn btn-danger btn-custom" data-bs-toggle="modal"
                                                data-bs-target="#deleteCompanyModal" data-id="{{ $company->id }}">
                                                <i class="ri-delete-bin-line align-bottom me-1"></i> Delete Company
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade zoomIn" id="deleteCompanyModal" tabindex="-1"
                                    aria-labelledby="deleteCompanyLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="btn-close" id="deleteRecord-close"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-5 text-center">
                                                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                                    colors="primary:#405189,secondary:#f06548"
                                                    style="width:90px;height:90px">
                                                </lord-icon>
                                                <div class="mt-4 text-center">
                                                    <h4 class="fs-semibold">Are you sure you want to delete this company?
                                                    </h4>
                                                    <p class="text-muted fs-14 mb-4 pt-1">Deleting this company will remove
                                                        it permanently from the database.</p>
                                                    <div class="hstack gap-2 justify-content-center remove">
                                                        <button
                                                            class="btn btn-link link-success fw-medium text-decoration-none"
                                                            data-bs-dismiss="modal">
                                                            <i class="ri-close-line me-1 align-middle"></i> Close
                                                        </button>
                                                        <form id="delete-form" method="POST"
                                                            action="/companies/delete/{{ $company->id }}?permission=Delete Company&idcp={{ $company->id }}">
                                                            @csrf
                                                            <input type="hidden" name="id" id="delete-company-id">
                                                            <input type="hidden" name="user"
                                                                value="{{ $user->name }}">
                                                            <button type="submit" class="btn btn-danger"
                                                                id="delete-record">Yes, Delete It!</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Edit Company -->
                                <div class="modal fade" id="editCompanyModal" tabindex="-1"
                                    aria-labelledby="editCompanyModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content border-0">
                                            <div class="modal-header bg-info-subtle p-3">
                                                <h5 class="modal-title" id="editCompanyModalLabel">Edit Company</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form
                                                action="/companies/edit/{{ $company->id }}?permission=Edit Company&idcp={{ $company->id }}"
                                                method="POST" enctype="multipart/form-data" autocomplete="off"
                                                id="editCompanyForm">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $company->id }}">
                                                <input type="hidden" name="user" value="{{ $user->name }}">
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <!-- Image Preview -->
                                                        <div class="col-lg-12 text-center mb-3">
                                                            <img id="preview-image"
                                                                src="{{ asset($company->image ? '/storage/' . $company->image : '/build/images/users/multi-user.jpg') }}"
                                                                alt="Company Image"
                                                                style="width: 150px;height:150px; border-radius: 50%">
                                                        </div>
                                                        <!-- Input for new image -->
                                                        <div class="col-lg-12">
                                                            <label for="image"
                                                                class="form-label text-black mb-1">Company
                                                                Image</label>
                                                            <input type="file" class="form-control" id="image"
                                                                name="image" accept="image/*"
                                                                onchange="previewImage(event)">
                                                        </div>
                                                        <!-- Input for company name -->
                                                        <div class="col-lg-12">
                                                            <label for="name"
                                                                class="form-label text-black mb-1">Company Name</label>
                                                            <input type="text"
                                                                class="form-control @error('name') is-invalid @enderror"
                                                                id="name" name="name"
                                                                value="{{ $company->name }}" required>
                                                            @error('name')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="col-lg-12">
                                                            <label for="status"
                                                                class="form-label text-black mb-1">status
                                                                <span style="color:var(--error)">*</span>
                                                            </label>
                                                            <select
                                                                class="form-select  @error('status') is-invalid @enderror"
                                                                name="status" aria-label="Default select example">
                                                                <option selected class="text-muted">Select a status
                                                                </option>
                                                                <option value="private"
                                                                    {{ $company->status == 'private' ? 'selected' : '' }}>
                                                                    private</option>
                                                                <option value="public"
                                                                    {{ $company->status == 'public' ? 'selected' : '' }}>
                                                                    public</option>
                                                            </select>
                                                            @error('status')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <!-- Input for company description -->
                                                        <div class="col-lg-12">
                                                            <label for="description" class="form-label text-black"
                                                                style="font-weight:600;">Description
                                                                <span style="color: var(--error);">*</span>
                                                            </label>
                                                            <input id="description" name="description" type="hidden"
                                                                class="@error('description') is-invalid @enderror"
                                                                value="{{ $company->description }}">
                                                            <trix-editor input="description"></trix-editor>
                                                            @error('description')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>
                    <!-- end card body -->
                </div>
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content text-muted">
                <div class="tab-pane fade show active" id="project-overview" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-muted">
                                        <h6 class="mb-3 fw-semibold text-uppercase">Description</h6>
                                        <p>{!! $company->description !!}</p>


                                        <div class="pt-3 border-top border-top-dashed mt-4">
                                            <div class="row gy-3">
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 fw-medium" style="font-size: 14px;">Create Date :
                                                        </p>
                                                        <h5 class="fs-15 mb-0">
                                                            {{ \Carbon\Carbon::parse($company->created_at)->format('d F Y') }}
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 fw-medium" style="font-size: 14px;">Code Company :
                                                        </p>
                                                        <div class="badge bg-success fs-12">{{ $company->code }}</div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 fw-medium" style="font-size: 14px;">Total Members :
                                                        </p>
                                                        <div class="badge bg-danger fs-12">{{ $company->users->count() }}
                                                            orang</div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 fw-medium" style="font-size: 14px;">Status :</p>
                                                        <div class="badge bg-warning fs-12">{{ $company->status }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class=" fw-medium text-muted text-truncate mb-0"> Total Employess
                            </p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <?php
                            $totalEmployess = $company->users->count();
                            ?>
                            <h4 class="fs-20 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                    data-target="{{ $totalEmployess }}">0</span> Employee</h4>
                            <a href="/companies/users/{{ $company->id }}?permission=Read Company User&idcp={{ $company->id }}"
                                class="text-decoration-underline">View All</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-secondary-subtle rounded fs-3">
                                <i class='bx bxs-user text-secondary'></i>
                            </span>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class=" fw-medium text-muted text-truncate mb-0">Total Roles</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <?php
                            $roles = $company->roles->unique('id'); // Ganti 'name' dengan atribut yang ingin di-unique
                            $totalRoles = $roles->count();
                            ?>
                            <h4 class="fs-20 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                    data-target="{{ $totalRoles }}">0</span> Role</h4>
                            <a href="/companies/roles/{{ $company->id }}?permission=Read Company Role&idcp={{ $company->id }}"
                                class="text-decoration-underline">View All</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-primary-subtle rounded fs-3">
                                <i class='bx bxs-user-detail text-primary'></i>
                            </span>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class=" fw-medium text-muted text-truncate mb-0">Total Categories</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <?php
                            $totalCategories = $company->categories->count();
                            ?>
                            <h4 class="fs-20 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                    data-target="{{ $totalCategories }}">0</span> Category</h4>
                            <a href="/companies/categories/{{ $company->id }}?permission=Read Category Technology&idcp={{ $company->id }}"
                                class="text-decoration-underline">View All</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                <i class='bx bxs-book-content text-success'></i>
                            </span>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class=" fw-medium text-muted text-truncate mb-0">Total Technologies</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <?php
                            $totalTechnologies = $company->technologies->count();
                            
                            ?>
                            <h4 class="fs-20 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                    data-target="{{ $totalTechnologies }}">0</span> Technologies </h4>
                            <a href="/companies/technologies/{{ $company->id }}?permission=Read Technology&idcp={{ $company->id }}"
                                class="text-decoration-underline">View All</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                <i class="ri-window-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->


    </div> <!-- end row-->
    <div>
        <h4 class="mt-1">All Categories</h4>
        <div class="row mt-3">
            <?php
            $categories = $company->categories;
            ?>
            @if ($categories->isNotEmpty())
                @foreach ($categories as $category)
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">
                                    <i class="ri-window-line align-bottom me-1"></i>
                                    <?php
                                    $totalTechnologies = App\Models\Technology::where('company_id', $company->id)
                                        ->where('category_id', $category->id)
                                        ->count();
                                    ?>
                                    {{ $totalTechnologies }} Technologies
                                </div>
                                <h6 class="card-title mb-0">{{ $category->name }}</h6>
                            </div>
                            <div class="card-body" style="height: 135px">
                                <p class="card-text text-muted mb-0">{{ $category->description }}</p>
                            </div>
                            <div class="card-footer">
                                <a href="" class="link-success float-end">View Radar <i
                                        class="ri-arrow-right-s-line align-middle ms-1 lh-1"></i></a>
                                <p class="text-muted mb-0">
                                    {{ \Carbon\Carbon::parse($category->created_at)->format('d F Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div id="job-list"
                    style="display: flex;align-items:center;justify-content:center;margin-block:50px;gap:15px;flex-direction:column">
                    <img src="/build/images/warning.png" width="80px">
                    <h5>There are no categories in this company!</h5>
                </div>
            @endif
        </div>
    </div>
    <div>
        <div class="d-flex align-items-center justify-content-between mt-1">
            <h4 class="">Employees</h4>
            <a href="/companies/users/{{ $company->id }}?permission=Read Company User&idcp={{ $company->id }}"
                class="d-flex align-items-center">
                <span style="font-size: 17px">see all</span>
                <i class="ri-arrow-right-s-line" style="font-size: 20px"></i>
            </a>
        </div>
        <div class="row mt-3">
            <?php
            $employess = $company->users;
            ?>
            @foreach ($employess as $employee)
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header text-center">
                            <h6 class="card-title mb-0">Our Employee</h6>
                        </div>
                        <div class="card-body p-4 text-center">
                            <div class="mx-auto avatar-md mb-3">
                                <img src="{{ asset($employee->photo ? 'storage/' . $employee->photo : '/build/images/users/user-dummy-img.jpg') }}"
                                    alt="user-img" class="img-thumbnail rounded-circle mb-1"
                                    style="width: 70px;height:70px" />
                            </div>
                            <h5 class="card-title mb-1">{{ $employee->name }}</h5>
                            <?php
                            $role = $user
                                ->roles()
                                ->wherePivot('company_id', $company->id)
                                ->first();
                            ?>
                            <p class="text-muted mb-0 fw-medium" style="font-size: 12px;">{{ $role->name }}</p>
                        </div>
                        <div class="card-footer text-center">
                            <i class="ri-window-line align-bottom me-1"></i>
                            <?php
                            $totalTechnologies = App\Models\Technology::where('company_id', $company->id)
                                ->where('user_id', $employee->id)
                                ->count();
                            ?>
                            {{ $totalTechnologies }} Technologies
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <!-- end row -->
@endsection
@section('script')
    <script src="{{ URL::asset('build/js/pages/project-overview.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/project-overview.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.remove-item-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    document.getElementById('delete-company-id').value = id;
                });
            });
        });

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('preview-image');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        @if (session('success_update'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success_update') }}",
                confirmButtonText: 'Oke',
            });
        @endif
    </script>
@endsection
