@extends('layouts.master')
@section('title')
    Permissions
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
            Permissions
        @endslot
    @endcomponent

    <div class="row">
        <!--end col-->
        <div class="">
            <div class="card" id="companyList">
                <div class="card-header">
                    <div class="row g-2">
                        <h5>Setting permission</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <div class="table-responsive table-card mb-3">
                            <table class="table align-middle table-nowrap mb-0" id="permissionTable">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">Permission Name</th>
                                        @foreach ($roles as $role)
                                            <th scope="col">{{ $role->name }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions as $permission)
                                        <tr>
                                            <!-- Column Permission Name -->
                                            <td>{{ $permission->name }}</td>

                                            <!-- Role Checkboxes -->
                                            @foreach ($roles as $role)
                                                @php
                                                    // Check if the role is connected to the permission for the specific company
                                                    $isConnected = $rolePermissions
                                                        ->where('role_id', $role->id)
                                                        ->where('permission_id', $permission->id)
                                                        ->isNotEmpty();
                                                @endphp
                                                <td>
                                                    <form
                                                        action="{{ url('/toggle-role-permission') }}?permission=Manage User Permission&idcp={{ $company->id }}"
                                                        method="POST">
                                                        @csrf
                                                        <input type="hidden" name="role_id" value="{{ $role->id }}">
                                                        <input type="hidden" name="permission_id"
                                                            value="{{ $permission->id }}">
                                                        <input type="hidden" name="company_id" value="{{ $company->id }}">
                                                        <input type="hidden" name="is_connected"
                                                            value="{{ $isConnected ? 1 : 2 }}">

                                                        <input type="checkbox" class="form-check-input"
                                                            {{ $isConnected ? 'checked' : '' }}
                                                            onclick="this.form.submit()">
                                                    </form>
                                                </td>
                                            @endforeach
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
                            <div class="pagination-wrap hstack gap-2">
                                <a class="page-item pagination-prev disabled" href="#">
                                    Previous
                                </a>
                                <ul class="pagination listjs-pagination mb-0"></ul>
                                <a class="page-item pagination-next" href="#">
                                    Next
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end card-->
        </div>

    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/list.js/list.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/list.pagination.js/list.pagination.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/crm-companies.init.js') }}"></script>
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        @if (session('success_create'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success_create') }}",
                showConfirmButton: false,
                timer: 1500
            });
        @endif
    </script>
@endsection
