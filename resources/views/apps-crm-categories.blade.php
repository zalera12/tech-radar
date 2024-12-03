@extends('layouts.master')
@section('title')
    categories
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
            Categories
        @endslot
    @endcomponent

    <div class="row mt-1">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="flex-grow-1">
                            <button class="btn btn-secondary add-btn" data-bs-toggle="modal" data-bs-target="#showModal"><i
                                    class="ri-add-fill me-1 align-bottom"></i> Add Category</button>
                        </div>
                        <div class="flex-shrink-0">

                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                                        placeholder="Search for categories...">
                                    <!-- Memastikan filter tetap dibawa ketika search dilakukan -->
                                    <input type="hidden" name="sort_order" value="{{ request('sort_order') }}">
                                    <input type="hidden" name="permission" value="Read Category Technology">
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
                                    <input type="hidden" name="permission" value="Read Category Technology">
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
                            @if ($categories->isNotEmpty())
                                <table class="table align-middle table-nowrap mb-0" id="categoryTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $index => $category)
                                            <tr>
                                                <td>{{ $categories->firstItem() + $index }}</td>
                                                <td class="name">{{ $category->name }}</td>
                                                <td class="description">{{ $category->description }}</td>
                                                <td>
                                                    <ul class="list-inline hstack gap-2 mb-0">
                                                        <li class="list-inline-item edit" data-bs-toggle="tooltip"
                                                            data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                            <a class="edit-item-btn" href="#editCategoryModal"
                                                                data-bs-toggle="modal" data-id="{{ $category->id }}"
                                                                data-name="{{ $category->name }}"
                                                                data-description="{{ $category->description }}">
                                                                <i class="ri-pencil-fill align-bottom text-muted"></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item" data-bs-toggle="tooltip"
                                                            data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                            <a class="remove-item-btn" data-bs-toggle="modal"
                                                                href="#deleteCategoryModal" data-id="{{ $category->id }}">
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
                                        <h5 class="mt-2">Apologies, No Categories Data Available</h5>
                                        <p class="text-muted mb-0">Unfortunately, there are no Categories available to display.
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <div class="col-sm-6">
                                <div
                                    class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                                    @if ($categories->onFirstPage())
                                        <div class="page-item disabled">
                                            <span class="page-link">Previous</span>
                                        </div>
                                    @else
                                        <div class="page-item">
                                            <a href="{{ $categories->appends(request()->except('page'))->previousPageUrl() }}"
                                                class="page-link" id="page-prev">Previous</a>
                                        </div>
                                    @endif

                                    <!-- Page Numbers -->
                                    <span id="page-num" class="pagination">
                                        @foreach ($categories->links()->elements[0] as $page => $url)
                                            @if ($page == $categories->currentPage())
                                                <span class="page-item active"><span
                                                        class="page-link">{{ $page }}</span></span>
                                            @else
                                                <a href="{{ $categories->appends(request()->except('page'))->url($page) }}"
                                                    class="page-item"><span
                                                        class="page-link">{{ $page }}</span></a>
                                            @endif
                                        @endforeach
                                    </span>

                                    @if ($categories->hasMorePages())
                                        <div class="page-item">
                                            <a href="{{ $categories->appends(request()->except('page'))->nextPageUrl() }}"
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
                    <!-- Modal Edit -->
                    <div class="modal fade" id="editCategoryModal" tabindex="-1"
                        aria-labelledby="editCategoryModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0">
                                <div class="modal-header bg-info-subtle p-3">
                                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                        id="close-edit-modal"></button>
                                </div>
                                <form
                                    action="/companies/categories/edit?permission=Edit Category Technology&idcp={{ $company->id }}"
                                    method="POST" autocomplete="off" id="editCategoryForm">
                                    @csrf
                                    <input type="hidden" id="edit-category-id" name="category_id" />
                                    <input type="hidden" name="company_id" value="{{ $company->id }}" />
                                    <input type="hidden" name="user" value="{{ $user->name }}">
                                    <div class="modal-body">
                                        <div class="row g-3">
                                            <div class="col-lg-12">
                                                <label for="edit-name" class="form-label text-secondary mb-1">Category
                                                    Name
                                                    <span style="color:var(--error)">*</span>
                                                </label>
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    id="edit-name" name="name" placeholder="Enter category name"
                                                    required>
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="edit-description"
                                                    class="form-label text-secondary mb-1">Description</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror" id="edit-description" name="description"
                                                    placeholder="Enter description">{{ old('description') }}</textarea>
                                                @error('description')
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
                    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0">
                                <div class="modal-header bg-info-subtle p-3">
                                    <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                        id="close-add-modal"></button>
                                </div>
                                <form
                                    action="{{ route('categories.add', $company->id) }}?permission=Add Category Technology&idcp={{ $company->id }}"
                                    method="POST" autocomplete="off">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" name="company_id" value="{{ $company->id }}">
                                        <input type="hidden" name="user" value="{{ $user->name }}">
                                        <div class="row g-3">
                                            <div class="col-lg-12">
                                                <label for="name" class="form-label text-secondary mb-1">Category Name
                                                    <span style="color:var(--error)">*</span>
                                                </label>
                                                <input type="text"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    id="name" name="name" placeholder="Enter category name"
                                                    value="{{ old('name') }}" required>
                                                @error('name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="description"
                                                    class="form-label text-secondary mb-1">Description</label>
                                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                                    placeholder="Enter description">{{ old('description') }}</textarea>
                                                @error('description')
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
                                            <button type="submit" class="btn btn-success">Add Category</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!--end add modal-->

                    <!-- Modal Hapus -->
                    <div class="modal fade" id="deleteCategoryModal" tabindex="-1"
                        aria-labelledby="deleteTechnologyModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0">
                                <div class="modal-header bg-danger-subtle p-3">
                                    <h5 class="modal-title" id="deleteTechnologyModalLabel">Delete Category</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form
                                    action="/companies/categories/delete?permission=Delete Category Technology&idcp={{ $company->id }}"
                                    method="POST" id="deleteCategoryForm">
                                    @csrf
                                    <input type="hidden" value="" id="category_id" name="category_id">
                                    <input type="hidden" value="{{ $company->id }}" name="company_id">
                                    <input type="hidden" value="{{ $user->name }}" name="user">
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this technology?</p>
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
            // Handle Edit Category Button Click
            document.querySelectorAll('.edit-item-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Ambil data dari tombol yang diklik
                    const categoryId = this.getAttribute('data-id');
                    const categoryName = this.getAttribute('data-name');
                    const categoryDescription = this.getAttribute('data-description');

                    // Debugging log untuk memastikan data sudah terambil dengan benar

                    // Set nilai ke field modal
                    document.getElementById('edit-category-id').value = categoryId;
                    document.getElementById('edit-name').value = categoryName;
                    document.getElementById('edit-description').value = categoryDescription;

                    // Jika menggunakan Bootstrap untuk menampilkan modal
                    $('#editModal').modal(
                    'show'); // Gunakan ini jika menggunakan jQuery Bootstrap modal
                });
            });

            // Handle Delete Category Button Click
            var deleteButtons = document.querySelectorAll('.remove-item-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    var categoryId = this.getAttribute('data-id');
                    var form = document.getElementById('deleteCategoryForm');
                    document.getElementById('category_id').value = categoryId;

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
