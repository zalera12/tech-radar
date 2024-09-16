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
                    <div class="d-flex align-items-center gap-2 justify-content-between flex-md-row flex-column">

                        <button class="btn btn-secondary add-btn" style="width: 260px;" data-bs-toggle="modal"
                            data-bs-target="#addTehcnologyModal"><i class="ri-add-fill me-1 align-bottom"></i> Add
                            Technologies</button>
                        <div class="d-flex align-items-center gap-2" style="width: 260px;">
                            <span class="fw-bold">Filter By : </span>
                            <form action="{{ url()->current() }}" method="GET" id="filterForm">
                                <!-- Hidden input untuk menjaga parameter yang sudah ada di URL -->
                                <input type="hidden" name="permission" value="Read Technology">
                                <input type="hidden" name="idcp" value="{{ $company->id }}">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="filter" value="{{ request('filter') }}">
                                <input type="hidden" name="filterQuadrant" value="{{ request('filterQuadrant') }}">
                                <input type="hidden" name="filterRing" value="{{ request('filterRing') }}">
                            
                            
                                <!-- Dropdown untuk filter By kategori -->
                                <select class="form-control" name="filterCategory" onchange="this.form.submit()" style="cursor: pointer;width:150px;text-align:center; margin-left:10px;">
                                    <option value="">All Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('filterCategory') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                            
                        </div>
                        <div class="d-flex align-items-center gap-2" style="width: 260px;">
                            <span class="fw-bold">Filter By : </span>
                            <form action="{{ url()->current() }}" method="GET" id="filterForm">
                                <!-- Hidden input untuk menjaga parameter yang sudah ada di URL -->
                                <input type="hidden" name="permission" value="Read Technology">
                                <input type="hidden" name="idcp" value="{{ $company->id }}">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="filter" value="{{ request('filter') }}">
                                <input type="hidden" name="filterCategory" value="{{ request('filterCategory') }}">
                                <input type="hidden" name="filterQuadrant" value="{{ request('filterQuadrant') }}">
                                <input type="hidden" name="filterRing" value="{{ request('filterRing') }}">
        
                            
                                <!-- Select untuk Ring (baru ditambahkan sesuai enum 'ring') -->
                                <select class="form-control" name="filterRing" onchange="this.form.submit()" style="cursor: pointer;width:150px;text-align:center; margin-left:10px;">
                                    <option value="">All Rings</option>
                                    <option value="HOLD" {{ request('filterRing') == 'HOLD' ? 'selected' : '' }}>HOLD</option>
                                    <option value="ADOPT" {{ request('filterRing') == 'ADOPT' ? 'selected' : '' }}>ADOPT</option>
                                    <option value="ASSESS" {{ request('filterRing') == 'ASSESS' ? 'selected' : '' }}>ASSESS</option>
                                    <option value="TRIAL" {{ request('filterRing') == 'TRIAL' ? 'selected' : '' }}>TRIAL</option>
                                </select>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
        <div class="col-xxl-9">
            <div class="card" id="companyList">
                <div class="card-header">
                    <div class="d-flex align-items-center gap-2 justify-content-between flex-md-row flex-column">

                        
                        <div class="" style="width: 260px;">
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control search bg-light border-light"
                                        id="searchJob" value="{{ request('search') }}" placeholder="Search for technologies...">
                                    <!-- Memastikan filter tetap dibawa ketika search dilakukan -->
                                    <input type="hidden" name="permission" value="Read Technology">
                                    <input type="hidden" name="idcp" value="{{ $company->id }}">
                                    <input type="hidden" name="filterCategory" value="{{ request('filterCategory') }}">
                                    <input type="hidden" name="filterQuadrant" value="{{ request('filterQuadrant') }}">
                                    <input type="hidden" name="filterRing" value="{{ request('filterRing') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="ri-search-line search-icon"></i>
                                    </button>
                                </div>
                            </form>
                            
                        </div>
                        <div class="d-flex align-items-center gap-2" style="width: 260px;">
                            <span class="fw-bold">Filter By : </span>
                            <form action="{{ url()->current() }}" method="GET" id="filterForm">
                                <!-- Hidden input untuk menjaga parameter yang sudah ada di URL -->
                                <input type="hidden" name="permission" value="Read Technology">
                                <input type="hidden" name="idcp" value="{{ $company->id }}">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="filter" value="{{ request('filter') }}">
                                <input type="hidden" name="filterCategory" value="{{ request('filterCategory') }}">
                                <input type="hidden" name="filterRing" value="{{ request('filterRing') }}">
                            
                                <!-- Filter Quadrant -->
                                <select class="form-control" style="cursor: pointer;width:150px;text-align:center;margin-left:10px;" name="filterQuadrant" id="filterQuadrant" onchange="this.form.submit()">
                                    <option value="">All Quadrant</option>
                                    <option value="Techniques" {{ request('filterQuadrant') == 'Techniques' ? 'selected' : '' }}>Techniques</option>
                                    <option value="Platforms" {{ request('filterQuadrant') == 'Platforms' ? 'selected' : '' }}>Platforms</option>
                                    <option value="Tools" {{ request('filterQuadrant') == 'Tools' ? 'selected' : '' }}>Tools</option>
                                    <option value="Language and Framework" {{ request('filterQuadrant') == 'Language and Framework' ? 'selected' : '' }}>Language and Framework</option>
                                </select>
                        
                            </form>
                            
                        </div>
                        
                        <div class="d-flex align-items-center gap-2" style="width: 260px;">
                            <span class="fw-bold">Filter By : </span>
                            <form action="{{ url()->current() }}" method="GET" id="filterForm">
                                <!-- Hidden input untuk menjaga parameter yang sudah ada di URL -->
                                <input type="hidden" name="permission" value="Read Technology">
                                <input type="hidden" name="idcp" value="{{ $company->id }}">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="filter" value="{{ request('filter') }}">
                                <input type="hidden" name="filterCategory" value="{{ request('filterCategory') }}">
                                <input type="hidden" name="filterQuadrant" value="{{ request('filterQuadrant') }}">
                                <input type="hidden" name="filterRing" value="{{ request('filterRing') }}">


                                <select class="form-control" style="cursor: pointer;width:150px;text-align:center;margin-left:10px;"
                                    name="sort_order" id="sortOrder" onchange="this.form.submit()">
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
                <div class="card-body mt-3">
                    <div>
                        <div class="table-responsive table-card mb-3">
                            <table class="table align-middle table-nowrap mb-0" id="technologiesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">User</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Is New</th>
                                        <th scope="col">Quadrant</th>
                                        <th scope="col">Ring</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($technologies as $index => $technology)
                                        <tr>
                                            <td>{{ $technologies->firstItem() + $index }}</td>
                                            <td class="category">{{ $technology->category->name }}</td>
                                            <td class="user">{{ $technology->user->name }}</td>
                                            <td class="name">{{ $technology->name }}</td>
                                            <td class="is_new">{{ $technology->is_new ? 'Yes' : 'No' }}</td>
                                            <td class="quadrant">{{ $technology->quadrant }}</td>
                                            <td class="ring">{{ $technology->ring }}</td>
                                            <td>
                                                <ul class="list-inline hstack gap-2 mb-0">
                                                    <li class="list-inline-item" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top" title="View">
                                                        <a href="{{ route('technologies.show', $technology->id) }}"
                                                            class="view-item-btn">
                                                            <i class="ri-eye-fill align-bottom text-muted"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item edit" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                        <a class="edit-item-btn" href="#editTechnologyModal"
                                                            data-bs-toggle="modal" data-id="{{ $technology->id }}"
                                                            data-name="{{ $technology->name }}"
                                                            data-category="{{ $technology->category->id }}"
                                                            data-description="{{ $technology->description }}"
                                                            data-quadrant="{{ $technology->quadrant }}"
                                                            data-ring="{{ $technology->ring }}">
                                                            <i class="ri-pencil-fill align-bottom text-muted"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                        <a class="remove-item-btn" data-bs-toggle="modal"
                                                            href="#deleteTechnologyModal"
                                                            data-id="{{ $technology->id }}">
                                                            <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editTechnologyModal" tabindex="-1"
                                aria-labelledby="editTechnologyModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0">
                                        <div class="modal-header bg-info-subtle p-3">
                                            <h5 class="modal-title" id="editTechnologyModalLabel">Edit Technology</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close" id="close-edit-modal"></button>
                                        </div>
                                        <form
                                            action="/companies/technologies/edit?permission=Edit Technology&idcp={{ $company->id }}"
                                            method="POST" autocomplete="off" id="editTechnologyForm">
                                            @csrf
                                            <input type="hidden" name="id" id="edit-technology-id">
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <input type="hidden" name="company_id" value="{{ $company->id }}">

                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-lg-12">
                                                        <label for="edit-category"
                                                            class="form-label text-secondary mb-1">Category
                                                            <span style="color:var(--error)">*</span>
                                                        </label>
                                                        <select class="form-select @error('category') is-invalid @enderror"
                                                            id="edit-category" name="category_id" required>
                                                            <option value="" disabled selected>Select Category
                                                            </option>
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}">{{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('ring')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <label for="edit-name"
                                                            class="form-label text-secondary mb-1">Technology Name
                                                            <span style="color:var(--error)">*</span>
                                                        </label>
                                                        <input type="text"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            id="edit-name" name="name"
                                                            placeholder="Enter technology name"
                                                            value="{{ old('name') }}" required>
                                                        @error('name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="mb-2">
                                                            <label for="description" class="form-label text-secondary"
                                                                style="font-weight:600;">Description
                                                                <span style="color: var(--error);">*</span>
                                                            </label>
                                                            <input id="edit-description" name="description"
                                                                type="hidden"
                                                                class="@error('description') is-invalid @enderror"
                                                                value="{{ old('description') }}">
                                                            <trix-editor input="edit-description"></trix-editor>
                                                            @error('description')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <label for="edit-quadrant"
                                                            class="form-label text-secondary mb-1">Quadrant
                                                            <span style="color:var(--error)">*</span>
                                                        </label>
                                                        <select class="form-select @error('quadrant') is-invalid @enderror"
                                                            id="edit-quadrant" name="quadrant" required>
                                                            <option value="" disabled selected>Select Quadrant
                                                            </option>
                                                            <option value="Techniques">Techniques</option>
                                                            <option value="Platforms">Platforms</option>
                                                            <option value="Tools">Tools</option>
                                                            <option value="Language and Framework">Language and Framework
                                                            </option>
                                                        </select>
                                                        @error('quadrant')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <label for="edit-ring" class="form-label text-secondary mb-1">Ring
                                                            <span style="color:var(--error)">*</span>
                                                        </label>
                                                        <select class="form-select @error('ring') is-invalid @enderror"
                                                            id="edit-ring" name="ring" required>
                                                            <option value="" disabled selected>Select Ring</option>
                                                            <option value="HOLD">HOLD</option>
                                                            <option value="ADOPT">ADOPT</option>
                                                            <option value="ASSESS">ASSESS</option>
                                                            <option value="TRIAL">TRIAL</option>
                                                        </select>
                                                        @error('ring')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="button" class="btn btn-light"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success">Save Changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>




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
                                <div class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                                    @if ($technologies->onFirstPage())
                                        <div class="page-item disabled">
                                            <span class="page-link">Previous</span>
                                        </div>
                                    @else
                                        <div class="page-item">
                                            <a href="{{ $technologies->appends(request()->except('page'))->previousPageUrl() }}" class="page-link" id="page-prev">Previous</a>
                                        </div>
                                    @endif
                                
                                    <!-- Page Numbers -->
                                    <span id="page-num" class="pagination">
                                        @foreach ($technologies->links()->elements[0] as $page => $url)
                                            @if ($page == $technologies->currentPage())
                                                <span class="page-item active"><span class="page-link">{{ $page }}</span></span>
                                            @else
                                                <a href="{{ $technologies->appends(request()->except('page'))->url($page) }}" class="page-item"><span class="page-link">{{ $page }}</span></a>
                                            @endif
                                        @endforeach
                                    </span>
                                
                                    @if ($technologies->hasMorePages())
                                        <div class="page-item">
                                            <a href="{{ $technologies->appends(request()->except('page'))->nextPageUrl() }}" class="page-link" id="page-next">Next</a>
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
                    <div class="modal fade" id="addTehcnologyModal" tabindex="-1"
                        aria-labelledby="addTechnologyModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0">
                                <div class="modal-header bg-info-subtle p-3">
                                    <h5 class="modal-title" id="addTechnologyModalLabel">Add Technology</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                        id="close-add-modal"></button>
                                </div>
                                <form
                                    action="/companies/technologies/add?permission=Add Technology&idcp={{ $company->id }}"
                                    method="POST" autocomplete="off">
                                    @csrf
                                    <input type="hidden" name="company_id" value="{{ $company->id }}">
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-lg-12">
                                                <label for="category" class="form-label text-secondary mb-1">category
                                                    <span style="color:var(--error)">*</span>
                                                </label>
                                                <select class="form-select @error('category') is-invalid @enderror"
                                                    id="category" name="category_id" required>
                                                    <option value="" disabled selected>Select category</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}">{{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('category_id')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="name" class="form-label text-secondary mb-1">Technology
                                                    Name
                                                    <span style="color:var(--error)">*</span>
                                                </label>
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    id="name" name="name" placeholder="Enter technology name"
                                                    value="{{ old('name') }}" required>
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="mb-2">
                                                    <label for="description" class="form-label text-secondary"
                                                        style="font-weight:600;">Description
                                                        <span style="color: var(--error);">*</span>
                                                    </label>
                                                    <input id="description" name="description" type="hidden"
                                                        class="@error('description') is-invalid @enderror"
                                                        value="{{ old('description') }}">
                                                    <trix-editor input="description"></trix-editor>
                                                    @error('description')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="quadrant" class="form-label text-secondary mb-1">Quadrant
                                                    <span style="color:var(--error)">*</span>
                                                </label>
                                                <select class="form-select @error('quadrant') is-invalid @enderror"
                                                    id="quadrant" name="quadrant" required>
                                                    <option value="" disabled selected>Select Quadrant</option>
                                                    <option value="Techniques">Techniques</option>
                                                    <option value="Platforms">Platforms</option>
                                                    <option value="Tools">Tools</option>
                                                    <option value="Language and Framework">Language and Framework</option>
                                                </select>
                                                @error('quadrant')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="ring" class="form-label text-secondary mb-1">Ring
                                                    <span style="color:var(--error)">*</span>
                                                </label>
                                                <select class="form-select @error('ring') is-invalid @enderror"
                                                    id="ring" name="ring" required>
                                                    <option value="" disabled selected>Select Ring</option>
                                                    <option value="HOLD">HOLD</option>
                                                    <option value="ADOPT">ADOPT</option>
                                                    <option value="ASSESS">ASSESS</option>
                                                    <option value="TRIAL">TRIAL</option>
                                                </select>
                                                @error('ring')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success">Add Technology</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!--end add modal-->

                    <!-- Modal Hapus -->
                    <div class="modal fade zoomIn" id="deleteTechnologyModal" tabindex="-1"
                        aria-labelledby="deleteTechnologyLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" id="deleteRecord-close"
                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-5 text-center">
                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                        colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px">
                                    </lord-icon>
                                    <div class="mt-4 text-center">
                                        <h4 class="fs-semibold">You are about to delete a technology?</h4>
                                        <p class="text-muted fs-14 mb-4 pt-1">Deleting this role will remove it permanently
                                            from the database.</p>
                                        <div class="hstack gap-2 justify-content-center remove">
                                            <button class="btn btn-link link-success fw-medium text-decoration-none"
                                                data-bs-dismiss="modal">
                                                <i class="ri-close-line me-1 align-middle"></i> Close
                                            </button>
                                            <form id="delete-form" method="POST"
                                                action="{{ route('technologies.delete') }}?permission=Delete Technology&idcp={{ $company->id }}">
                                                @csrf
                                                <input type="hidden" name="id" id="delete-technology-id">
                                                <input type="hidden" name="company_id" value="{{ $company->id }}">
                                                <button type="submit" class="btn btn-danger" id="delete-record">Yes,
                                                    Delete It!!</button>
                                            </form>

                                        </div>
                                    </div>
                                </div>
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
            // Event listener for edit button
            document.querySelectorAll('.edit-item-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const quadrant = this.getAttribute('data-quadrant');
                    const ring = this.getAttribute('data-ring');
                    const category = this.getAttribute('data-category');
                    const description = this.getAttribute('data-description');

                    // Set input values
                    document.getElementById('edit-technology-id').value = id;
                    document.getElementById('edit-name').value = name;
                    const modalElement = document.getElementById('editTechnologyModal');
                    const modal = new bootstrap.Modal(modalElement);
                    modal.show();

                    modalElement.addEventListener('shown.bs.modal', function() {
                        const trixEditor = document.querySelector(
                            "trix-editor[input='edit-description']");
                        if (trixEditor && trixEditor.editor) {
                            trixEditor.editor.loadHTML(description);
                        } else {
                            console.error('Trix editor not found or not ready.');
                        }
                    }, {
                        once: true
                    });

                    // Select the correct option for category
                    const categorySelect = document.getElementById('edit-category');
                    for (let option of categorySelect.options) {
                        option.selected = option.value == category;
                    }

                    // Select the correct option for quadrant
                    const quadrantSelect = document.getElementById('edit-quadrant');
                    for (let option of quadrantSelect.options) {
                        option.selected = option.value == quadrant;
                    }

                    // Select the correct option for ring
                    const ringSelect = document.getElementById('edit-ring');
                    for (let option of ringSelect.options) {
                        option.selected = option.value == ring;
                    }
                });
            });

            // Event listener for modal close to remove backdrop
            document.getElementById('editTechnologyModal').addEventListener('hidden.bs.modal', function() {
                document.body.classList.remove('modal-open');
                document.querySelectorAll('.modal-backdrop').forEach(backdrop => {
                    backdrop.remove();
                });
            });

            // Event listener for delete button
            document.querySelectorAll('.remove-item-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    document.getElementById('delete-technology-id').value = id;
                });
            });
        });

        // Check if there's a success message in session
        @if (session('success_update'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success_update') }}",
                showConfirmButton: false,
                timer: 1500
            });
        @endif

        @if (session('success_delete'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success_delete') }}",
                showConfirmButton: false,
                timer: 1500
            });
        @endif
    </script>
@endsection
