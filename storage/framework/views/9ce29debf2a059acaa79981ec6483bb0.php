<?php $__env->startSection('title'); ?>
    logs
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
            Logs
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <h4>Logs Page</h4>
    <div class="row mt-3">
        <!--end col-->
        <div class="">
            <div class="card" id="companyList">
                <div class="card-header">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <form action="<?php echo e(url()->current()); ?>" method="GET">
                                <div class="input-group mb-3">
                                    <input type="text" name="search" class="form-control search bg-light border-light"
                                        id="searchJob" value="<?php echo e(request('search')); ?>" placeholder="Search for logs...">
                                    <!-- Memastikan filter tetap dibawa ketika search dilakukan -->
                                    <input type="hidden" name="sort_order" value="<?php echo e(request('sort_order')); ?>">
                                    <input type="hidden" name="permission" value="Read Change Log">
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
                                    <input type="hidden" name="permission" value="Read Change Log">
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
                            <?php if($logs->isNotEmpty()): ?>
                                <table class="table align-middle table-nowrap mb-0" id="categoryTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">No</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">decription</th>
                                            <th scope="col">time</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($logs->firstItem() + $index); ?></td>

                                                <td class="name"><?php echo e($log->name); ?></td>
                                                <td class="description"><?php echo e($log->description); ?></td>
                                                <td class="time">
                                                    <?php echo e(\Carbon\Carbon::parse($log->created_at)->format('d F Y')); ?>

                                                </td>

                                                <td>
                                                    <ul class="list-inline hstack gap-2 mb-0">
                                                        <li class="list-inline-item" data-bs-toggle="tooltip"
                                                            data-bs-trigger="hover" data-bs-placement="top" title="Delete">
                                                            <a class="remove-item-btn" data-bs-toggle="modal"
                                                                href="#deleteLogModal" data-id="<?php echo e($log->id); ?>">
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
                                        <h5 class="mt-2">Apologies, No Logs Data Available</h5>
                                        <p class="text-muted mb-0">Unfortunately, there are no logs available to display.
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <div class="col-sm-6">
                                <div
                                    class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                                    <?php if($logs->onFirstPage()): ?>
                                        <div class="page-item disabled">
                                            <span class="page-link">Previous</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="page-item">
                                            <a href="<?php echo e($logs->appends(request()->except('page'))->previousPageUrl()); ?>"
                                                class="page-link" id="page-prev">Previous</a>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Page Numbers -->
                                    <span id="page-num" class="pagination">
                                        <?php $__currentLoopData = $logs->links()->elements[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($page == $logs->currentPage()): ?>
                                                <span class="page-item active"><span
                                                        class="page-link"><?php echo e($page); ?></span></span>
                                            <?php else: ?>
                                                <a href="<?php echo e($logs->appends(request()->except('page'))->url($page)); ?>"
                                                    class="page-item"><span
                                                        class="page-link"><?php echo e($page); ?></span></a>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </span>

                                    <?php if($logs->hasMorePages()): ?>
                                        <div class="page-item">
                                            <a href="<?php echo e($logs->appends(request()->except('page'))->nextPageUrl()); ?>"
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

                    <!-- Modal Hapus -->
                    <div class="modal fade" id="deleteLogModal" tabindex="-1" aria-labelledby="deleteLogModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0">
                                <div class="modal-header bg-danger-subtle p-3">
                                    <h5 class="modal-title" id="deleteLogModalLabel">Delete Log</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="/companies/logs/delete?permission=Delete Log&idcp=<?php echo e($company->id); ?>"
                                    method="POST" id="deleteLogForm">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <input type="hidden" value="" id="log_id" name="log_id">
                                    <input type="hidden" value="<?php echo e($company->id); ?>" id="company_id"
                                        name="company_id">

                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this log?</p>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/libs/list.js/list.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/list.pagination.js/list.pagination.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/crm-companies.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Handle Delete Category Button Click
            var deleteButtons = document.querySelectorAll('.remove-item-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    var logId = this.getAttribute('data-id');
                    var form = document.getElementById('deleteLogForm');
                    document.getElementById('log_id').value = logId;
                });
            });

        });

        <?php if(session('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "<?php echo e(session('success')); ?>",
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\magang\test ui adminDashboard\Laravel\corporate\resources\views/apps-crm-log.blade.php ENDPATH**/ ?>