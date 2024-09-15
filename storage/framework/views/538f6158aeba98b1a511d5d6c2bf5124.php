<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.companies'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            Perusahaan
        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
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
                            <form action="<?php echo e(url()->current()); ?>" method="GET">
                                <div class="input-group mb-3">
                                    <input type="text" name="search" class="form-control search bg-light border-light"
                                        id="searchJob" value="<?php echo e(request('search')); ?>"
                                        placeholder="Search for members...">
                                    <!-- Hidden input untuk menjaga sort_order -->
                                    <input type="hidden" name="sort_order" value="<?php echo e(request('sort_order')); ?>">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="ri-search-line search-icon"></i>
                                    </button>
                                </div>
                            </form>
                                           
                        </div>
                        <div class="col-md-auto ms-auto">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold">Urutkan Berdasarkan : </span>
                                <form action="<?php echo e(url()->current()); ?>" method="GET" id="filterForm">
                                    <!-- Hidden input untuk menjaga search -->
                                    <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
                                    <select class="form-control" style="cursor: pointer" name="sort_order" id="sortOrder" onchange="this.form.submit()">
                                        <option value="terbaru" <?php echo e(request('sort_order') == 'terbaru' ? 'selected' : ''); ?>>Terbaru</option>
                                        <option value="terlama" <?php echo e(request('sort_order') == 'terlama' ? 'selected' : ''); ?>>Terlama</option>
                                        <option value="A-Z" <?php echo e(request('sort_order') == 'A-Z' ? 'selected' : ''); ?>>A-Z</option>
                                        <option value="Z-A" <?php echo e(request('sort_order') == 'Z-A' ? 'selected' : ''); ?>>Z-A</option>
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
                                        <th class="sort" data-sort="no" scope="col">No</th>
                                        <th class="sort" data-sort="photo" scope="col">Photo</th>
                                        <th class="sort" data-sort="name" scope="col">Name</th>
                                        <th class="sort" data-sort="email" scope="col">Email</th>
                                        <th class="sort" data-sort="role" scope="col">Role</th>
                                        <th class="sort" data-sort="status" scope="col">Status</th>
                                        <!-- Kolom Status -->
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $pendingMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr data-id="<?php echo e($member['user']->id); ?>" data-company-id="<?php echo e($company->id); ?>"
                                            data-role-id="<?php echo e($member['role']->id); ?>"
                                            data-status="<?php echo e($member['status']); ?>">
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
                                            <td class="role"><?php echo e($member['role']->name); ?></td>
                                            <td class="status"><?php echo e($member['status']); ?></td> <!-- Menampilkan Status -->
                                            <td>
                                                <ul class="list-inline hstack gap-2 mb-0">
                                                    <li class="list-inline-item" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top"
                                                        title="Edit Role">
                                                        <a class="edit-item-btn" href="#editPendingMemberModal"
                                                            data-bs-toggle="modal">
                                                            <i class="ri-pencil-fill align-bottom text-muted"></i>
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
                                <?php echo e($pendingMembers->links()); ?>

                            </div>
                            
                        </div>
                    </div>
                    <!-- Modal Edit Role for User -->
                    <div class="modal fade" id="editPendingMemberModal" tabindex="-1"
                        aria-labelledby="editPendingMemberModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="editPendingMemberForm"
                                    action="<?php echo e(route('companies.pendingMember.update', ['member' => ':id'])); ?>?permission=Update Pending Company User&idcp=<?php echo e($company->id); ?>"
                                    method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <input type="hidden" name="company_id" value="<?php echo e($company->id); ?>">
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
                                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                <form action="<?php echo e(route('company.addUser')); ?>" method="POST" class="tablelist-form">
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
                                        <form id="deleteUserForm" action="<?php echo e(route('companies.roles.delete')); ?>"
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
                form.action = "<?php echo e(route('companies.pendingMember.update', ['member' => ':id'])); ?>";
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\magang\test ui adminDashboard\Laravel\corporate\resources\views/apps-crm-pending-members.blade.php ENDPATH**/ ?>