<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.companies'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Companies
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Users
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

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
                            <div class="search-box">
                                <input type="text" class="form-control search" placeholder="Search for company...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <div class="col-md-auto ms-auto">
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted">Sort by: </span>
                                <select class="form-control mb-0" data-choices data-choices-search-false
                                    id="choices-single-default">
                                    <option value="Owner">Owner</option>
                                    <option value="Company">Company</option>
                                    <option value="location">Location</option>
                                </select>
                            </div>
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
                                        <th class="sort" data-sort="star_value" scope="col">Google Id</th>
                                        <th class="sort" data-sort="role" scope="col">Role</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $companyUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr data-id="<?php echo e($member['user']->id); ?>" data-company-id="<?php echo e($company->id); ?>"
                                            data-role-id="<?php echo e($member['role']->id); ?>">
                                            <td><?php echo e($index + 1); ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <img src="<?php echo e(asset($member['user']->photo ? 'storage/' . $member['user']->photo : '/build/images/users/user-dummy-img.jpg')); ?>"
                                                            alt="User Photo"
                                                            class="avatar-xxs rounded-circle object-fit-cover">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="name"><?php echo e($member['user']->name); ?></td>
                                            <td class="email"><?php echo e($member['user']->email); ?></td>
                                            <td class="google_id"><?php echo e($member['user']->google_id); ?></td>
                                            <td class="role"><?php echo e($member['role']->name); ?></td>
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
                                                            href="#deleteRecordModal"
                                                            data-userId="<?php echo e($member['user']->id); ?>">
                                                            <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <!-- Modal Edit Role for User -->
                    <div class="modal fade" id="editUserRoleModal" tabindex="-1"
                        aria-labelledby="editUserRoleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="editUserRoleForm" data-idCompany="<?php echo e($company->id); ?>"
                                    action="/companies/users/edit?permission=Edit Company User&idcp=<?php echo e($company->id); ?>"
                                    method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" id="id-user" value="">
                                    <input type="hidden" name="company_id" value="<?php echo e($company->id); ?>"
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
                                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                <form action="/companies/user/add?permission=Add Company User&idcp=<?php echo e($company->id); ?>"
                                    method="POST" class="tablelist-form">
                                    <?php echo csrf_field(); ?>
                                    <div class="modal-body">
                                        <input type="hidden" id="company_id" name="company_id"
                                            value="<?php echo e($company->id); ?>" />
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
                                                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                        <form id="deleteUserForm" action="<?php echo e(route('companies.roles.delete')); ?>??permission=Delete Company User&idcp=<?php echo e($company->id); ?>"
                                            method="POST">
                                            <?php echo csrf_field(); ?>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/libs/list.js/list.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/list.pagination.js/list.pagination.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/crm-companies.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\magang\test ui adminDashboard\Laravel\corporate\resources\views/apps-crm-companies.blade.php ENDPATH**/ ?>