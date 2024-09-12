@extends('layouts.master')
@section('title') Candidate Lists View @endsection
@section('content')
@component('components.breadcrumb')
@slot('li_1') Companies @endslot
@slot('title') Users @endslot
@endcomponent

<div class="row g-4 mb-4">
    <div class="col-sm-auto">
        <div>
            <a href="#!" class="btn btn-primary"><i class="ri-add-line align-bottom me-1"></i> Add Candidate</a>
        </div>
    </div>
    <div class="col-sm">
        <div class="d-md-flex justify-content-sm-end gap-2">
            <div class="search-box ms-md-2 flex-shrink-0 mb-3 mb-md-0">
                <input type="text" class="form-control" id="searchJob" autocomplete="off" placeholder="Search for candidate name or designation...">
                <i class="ri-search-line search-icon"></i>
            </div>

            <select class="form-control w-md" data-choices data-choices-search-false>
                <option value="All">All</option>
                <option value="Today">Today</option>
                <option value="Yesterday" selected>Yesterday</option>
                <option value="Last 7 Days">Last 7 Days</option>
                <option value="Last 30 Days">Last 30 Days</option>
                <option value="This Month">This Month</option>
                <option value="Last Year">Last Year</option>
            </select>
        </div>
    </div>
</div>

<div class="row gy-2 mb-2 bg-white mt-3">
    <div class="table-responsive table-card mb-1">
                        <table class="table table-nowrap align-middle" id="jobListTable">
                            <thead class="text-muted table-light">
                                <tr class="text-uppercase">
                                    <th scope="col" style="width: 25px;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="checkAll" value="option">
                                        </div>
                                    </th>
                                    <th class="sort" data-sort="id" style="width: 140px;">Application ID</th>
                                    <th class="sort" data-sort="company">Company Name</th>
                                    <th class="sort" data-sort="designation">Designation</th>
                                    <th class="sort" data-sort="date">Apply Date</th>
                                    <th class="sort" data-sort="contacts">Contacts</th>
                                    <th class="sort" data-sort="type">Type</th>
                                    <th class="sort" data-sort="status">Status</th>
                                    <th class="sort" data-sort="city">Action</th>
                                </tr>
                            </thead>
                            <tbody class="list form-check-all">
                                <tr>
                                    <th scope="row">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="checkAll" value="option1">
                                        </div>
                                    </th>
                                    <td class="id"><a href="#" class="fw-medium link-primary">#VZ001</a></td>
                                    <td class="company">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img src="{{URL::asset('build/images/brands/slack.png')}}" alt="" class="avatar-xxs rounded-circle image_src object-fit-cover">
                                            </div>
                                            <div class="flex-grow-1 ms-2">Syntyce Solutions</div>
                                        </div>
                                    </td>
                                    <td class="designation">Web Designer</td>
                                    <td class="date">30 Sep,2022</td>
                                    <td class="contacts">785-685-4616</td>
                                    <td class="type">Full Time</td>
                                    <td class="status"><span class="badge bg-danger-subtle text-danger text-uppercase">Rejected</span>
                                    </td>
                                    <td>
                                        <ul class="list-inline hstack gap-2 mb-0">
                                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="View">
                                                <a href="apps-job-details" class="text-primary d-inline-block">
                                                    <i class="ri-eye-fill fs-16"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                <a href="#showModal" data-bs-toggle="modal" class="text-primary d-inline-block edit-item-btn">
                                                    <i class="ri-pencil-fill fs-16"></i>
                                                </a>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Remove">
                                                <a class="text-danger d-inline-block remove-item-btn" data-bs-toggle="modal" href="#deleteOrder">
                                                    <i class="ri-delete-bin-5-fill fs-16"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="noresult" style="display: none">
                            <div class="text-center">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#405189,secondary:#0ab39c" style="width:75px;height:75px"></lord-icon>
                                <h5 class="mt-2">Sorry! No Result Found</h5>
                                <p class="text-muted">We've searched more than 150+ result We did not find jobs for you search.</p>
                            </div>
                        </div>
                    </div>
</div>
<!-- end row -->

<div class="row g-0 justify-content-end mb-4" id="pagination-element">
    <!-- end col -->
    <div class="col-sm-6">
        <div class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
            <div class="page-item">
                <a href="javascript:void(0);" class="page-link" id="page-prev">Previous</a>
            </div>
            <span id="page-num" class="pagination"></span>
            <div class="page-item">
                <a href="javascript:void(0);" class="page-link" id="page-next">Next</a>
            </div>
        </div>
    </div><!-- end col -->
</div>
<!-- end row -->

@endsection
@section('script')
<!-- job-candidate-grid js -->
<script src="{{URL::asset('build/js/pages/job-candidate-lists.init.js')}}"></script>

<!-- App js -->
<script src="{{URL::asset('build/js/app.js')}}"></script>
@endsection
