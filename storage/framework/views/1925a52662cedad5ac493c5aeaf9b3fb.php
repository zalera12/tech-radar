<?php $__env->startSection('title'); ?>
    Permissions
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
            Permissions
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

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
                                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <th scope="col"><?php echo e($role->name); ?></th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <!-- Column Permission Name -->
                                            <td><?php echo e($permission->name); ?></td>

                                            <!-- Role Checkboxes -->
                                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    // Check if the role is connected to the permission for the specific company
                                                    $isConnected = $rolePermissions
                                                        ->where('role_id', $role->id)
                                                        ->where('permission_id', $permission->id)
                                                        ->isNotEmpty();
                                                ?>
                                                <td>
                                                    <form
                                                        action="<?php echo e(url('/toggle-role-permission')); ?>?permission=Manage User Permission&idcp=<?php echo e($company->id); ?>"
                                                        method="POST">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="role_id" value="<?php echo e($role->id); ?>">
                                                        <input type="hidden" name="permission_id"
                                                            value="<?php echo e($permission->id); ?>">
                                                        <input type="hidden" name="company_id" value="<?php echo e($company->id); ?>">
                                                        <input type="hidden" name="is_connected"
                                                            value="<?php echo e($isConnected ? 1 : 2); ?>">

                                                        <input type="checkbox" class="form-check-input"
                                                            <?php echo e($isConnected ? 'checked' : ''); ?>

                                                            onclick="this.form.submit()">
                                                    </form>
                                                </td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                </div>
            </div>
            <!--end card-->
        </div>

    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/libs/list.js/list.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/list.pagination.js/list.pagination.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/crm-companies.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
    <script>
        <?php if(session('success_create')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "<?php echo e(session('success_create')); ?>",
                showConfirmButton: false,
                timer: 1500
            });
        <?php endif; ?>
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\magang\test ui adminDashboard\Laravel\corporate\resources\views/apps-crm-permissions.blade.php ENDPATH**/ ?>