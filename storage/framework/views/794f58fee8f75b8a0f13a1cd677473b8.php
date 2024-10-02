<?php $__env->startSection('title'); ?>
    Roles
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('components.breadcrumb'); ?>
        <?php $__env->slot('li_1'); ?>
            <?php echo e($company->name); ?>

        <?php $__env->endSlot(); ?>
        <?php $__env->slot('title'); ?>
            Roles
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <h4>Roles Page</h4>
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="flex-grow-1">
                            <button class="btn btn-secondary add-btn" data-bs-toggle="modal" data-bs-target="#showModal"><i
                                    class="ri-add-fill me-1 align-bottom"></i> Add Role</button>
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
                            <form action="<?php echo e(url()->current()); ?>" method="GET">
                                <div class="input-group mb-3">
                                    <input type="text" name="search" class="form-control search bg-light border-light"
                                        id="searchJob" value="<?php echo e(request('search')); ?>" placeholder="Search for roles...">
                                    <!-- Memastikan filter tetap dibawa ketika search dilakukan -->
                                    <input type="hidden" name="sort_order" value="<?php echo e(request('sort_order')); ?>">
                                    <input type="hidden" name="permission" value="Read Company Role">
                                    <input type="hidden" name="idcp" value="<?php echo e($company->id); ?>">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="ri-search-line search-icon"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-auto ms-auto">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold">Sort By : </span>
                                <form action="<?php echo e(url()->current()); ?>" method="GET" id="filterForm">
                                    <!-- Hidden input untuk menjaga parameter yang sudah ada di URL -->
                                    <input type="hidden" name="permission" value="Read Company Role">
                                    <input type="hidden" name="idcp" value="<?php echo e($company->id); ?>">
                                    <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">

                                    <select class="form-control" style="cursor: pointer" name="sort_order" id="sortOrder"
                                        onchange="this.form.submit()">
                                        <option value="terbaru" <?php echo e(request('sort_order') == 'terbaru' ? 'selected' : ''); ?>>
                                            Terbaru</option>
                                        <option value="terlama" <?php echo e(request('sort_order') == 'terlama' ? 'selected' : ''); ?>>
                                            Terlama</option>
                                        <option value="A-Z" <?php echo e(request('sort_order') == 'A-Z' ? 'selected' : ''); ?>>A-Z
                                        </option>
                                        <option value="Z-A" <?php echo e(request('sort_order') == 'Z-A' ? 'selected' : ''); ?>>Z-A
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
                            <?php if($roles->isNotEmpty()): ?>
                            <table class="table align-middle table-nowrap mb-0" id="roleTable">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($roles->firstItem() + $index); ?></td>
                                            <td class="name"><?php echo e($role->name); ?></td>
                                            <td class="description"><?php echo e($role->description); ?></td>
                                            <td>
                                                <ul class="list-inline hstack gap-2 mb-0">
                                                    <li class="list-inline-item edit" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top" title="Edit">
                                                        <a class="edit-item-btn" href="#editModal" data-bs-toggle="modal"
                                                            data-id="<?php echo e($role->id); ?>" data-name="<?php echo e($role->name); ?>"
                                                            data-description="<?php echo e($role->description); ?>">
                                                            <i class="ri-pencil-fill align-bottom text-muted"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item" data-bs-toggle="tooltip"
                                                        data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                        <a class="remove-item-btn" data-bs-toggle="modal"
                                                            href="#deleteRecordModal" data-id="<?php echo e($role->id); ?>">
                                                            <i class="ri-delete-bin-fill align-bottom text-muted"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>     
                            <?php else: ?>
                            <div class="noresult mt-5">
                                <div class="text-center">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                        colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px">
                                    </lord-icon>
                                    <h5 class="mt-2">Apologies, No Roles Data Available</h5>
                                    <p class="text-muted mb-0">Unfortunately, there are no Roles available to display.
                                    </p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <div class="col-sm-6">
                                <div
                                    class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                                    <?php if($roles->onFirstPage()): ?>
                                        <div class="page-item disabled">
                                            <span class="page-link">Previous</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="page-item">
                                            <a href="<?php echo e($roles->appends(request()->except('page'))->previousPageUrl()); ?>"
                                                class="page-link" id="page-prev">Previous</a>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Page Numbers -->
                                    <span id="page-num" class="pagination">
                                        <?php $__currentLoopData = $roles->links()->elements[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($page == $roles->currentPage()): ?>
                                                <span class="page-item active"><span
                                                        class="page-link"><?php echo e($page); ?></span></span>
                                            <?php else: ?>
                                                <a href="<?php echo e($roles->appends(request()->except('page'))->url($page)); ?>"
                                                    class="page-item"><span
                                                        class="page-link"><?php echo e($page); ?></span></a>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </span>

                                    <?php if($roles->hasMorePages()): ?>
                                        <div class="page-item">
                                            <a href="<?php echo e($roles->appends(request()->except('page'))->nextPageUrl()); ?>"
                                                class="page-link" id="page-next">Next</a>
                                        </div>
                                    <?php else: ?>
                                        <div class="page-item disabled">
                                            <span class="page-link">Next</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0">
                                <div class="modal-header bg-info-subtle p-3">
                                    <h5 class="modal-title" id="editModalLabel">Edit Role</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                        id="close-edit-modal"></button>
                                </div>
                                <form class="tablelist-form"
                                    action="/companies/roles/edit?permission=Edit Company Role&idcp=<?php echo e($company->id); ?>"
                                    method="POST" autocomplete="off" id="editRoleForm">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" id="edit-role-id" name="role_id"
                                        value="<?php echo e(old('id')); ?>" />
                                    <input type="hidden" name="company" value="<?php echo e($company->id); ?>">
                                    <div class="modal-body">
                                        <input type="hidden" id="edit-company-id" name="company_id"
                                            value="<?php echo e($company->id); ?>" />
                                        <input type="hidden" name="user" value="<?php echo e($user->name); ?>" />
                                        <div class="row g-3">
                                            <div class="col-lg-12">
                                                <label for="edit-name" class="form-label text-secondary mb-1">Role
                                                    Name
                                                    <span style="color:var(--error)">*</span>
                                                </label>
                                                <input type="text"
                                                    class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    id="edit-name" name="name" placeholder="Enter role name"
                                                    value="<?php echo e(old('name')); ?>" required>
                                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong><?php echo e($message); ?></strong>
                                                    </span>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="edit-description"
                                                    class="form-label text-secondary mb-1">Description</label>
                                                <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="edit-description" name="description"
                                                    placeholder="Enter description"><?php echo e(old('description')); ?></textarea>
                                                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong><?php echo e($message); ?></strong>
                                                    </span>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content border-0">
                                <div class="modal-header bg-info-subtle p-3">
                                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                        id="close-modal"></button>
                                </div>
                                <form class="tablelist-form"
                                    action="/companies/roles/add?permission=Add Company Role&idcp=<?php echo e($company->id); ?>"
                                    method="POST" autocomplete="off">
                                    <?php echo csrf_field(); ?>
                                    <div class="modal-body">
                                        <input type="hidden" id="id-field" name="company_id"
                                            value="<?php echo e($company->id); ?>" />
                                        <input type="hidden" name="user" value="<?php echo e($user->name); ?>" />
                                        <div class="row g-3">
                                            <div class="col-lg-12">
                                                <label for="name" class="form-label text-secondary mb-1">Role Name
                                                    <span style="color:var(--error)">*</span>
                                                </label>
                                                <input type="text"
                                                    class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    id="name" name="name" placeholder="Enter role name"
                                                    value="<?php echo e(old('name')); ?>" required>
                                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong><?php echo e($message); ?></strong>
                                                    </span>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="description"
                                                    class="form-label text-secondary mb-1">Description</label>
                                                <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" name="description"
                                                    placeholder="Enter description"><?php echo e(old('description')); ?></textarea>
                                                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong><?php echo e($message); ?></strong>
                                                    </span>
                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success">Add Role</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <!--end add modal-->

                    <!-- Modal Hapus -->
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
                                        colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px">
                                    </lord-icon>
                                    <div class="mt-4 text-center">
                                        <h4 class="fs-semibold">You are about to delete a role?</h4>
                                        <p class="text-muted fs-14 mb-4 pt-1">Deleting this role will remove it permanently
                                            from the database.</p>
                                        <div class="hstack gap-2 justify-content-center remove">
                                            <button class="btn btn-link link-success fw-medium text-decoration-none"
                                                data-bs-dismiss="modal">
                                                <i class="ri-close-line me-1 align-middle"></i> Close
                                            </button>
                                            <form id="delete-form" method="POST"
                                                action="<?php echo e(route('roles.delete')); ?>?permission=Delete Company Role&idcp=<?php echo e($company->id); ?>">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <input type="hidden" name="company" value="<?php echo e($company->id); ?>">
                                                <input type="hidden" name="company_id" value="<?php echo e($company->id); ?>">
                                                <input type="hidden" name="user" value="<?php echo e($user->name); ?>">
                                                <input type="hidden" name="role_id" id="role-id-to-delete">
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/libs/list.js/list.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/list.pagination.js/list.pagination.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/crm-companies.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Event listener for edit buttons
            document.querySelectorAll('.edit-item-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Get data attributes from clicked button
                    const roleId = this.getAttribute('data-id');
                    const roleName = this.getAttribute('data-name');
                    const roleDescription = this.getAttribute('data-description');

                    // Set modal form fields with fetched data
                    document.getElementById('edit-role-id').value = roleId;
                    document.getElementById('edit-name').value = roleName;
                    document.getElementById('edit-description').value = roleDescription;
                });
            });

            document.querySelectorAll('.remove-item-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // Get role ID from data attribute
                    const roleId = this.getAttribute('data-id');
                    // Set role ID to the hidden input in the delete form
                    document.getElementById('role-id-to-delete').value = roleId;
                });
            });
        });



        // Check if there's a success message in session
        <?php if(session('success_update')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "<?php echo e(session('success_update')); ?>",
                showConfirmButton: false,
                timer: 1500
            });
        <?php endif; ?>

        <?php if(session('success_delete')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "<?php echo e(session('success_delete')); ?>",
                showConfirmButton: false,
                timer: 1500
            });
        <?php endif; ?>
        <?php if(session('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'error',
                text: "<?php echo e(session('error')); ?>",
                showConfirmButton: false,
                timer: 1500
            });
        <?php endif; ?>
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\magang\test ui adminDashboard\Laravel\corporate\resources\views/apps-crm-roles.blade.php ENDPATH**/ ?>