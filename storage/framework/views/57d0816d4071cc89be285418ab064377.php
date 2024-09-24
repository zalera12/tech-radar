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

    <div class="container">
        <h2 class="mb-4">All Messages</h2>
        <div class="row">
            <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12 project-card">
                    <div class="card card-height-100">
                        <div class="card-body">
                            <div class="d-flex flex-column h-100">
                                <div class="d-flex align-items-center pb-2 justify-content-end"
                                    style="align-items: center;border-bottom: 1px solid rgba(128, 128, 128, 0.2); /* abu-abu dengan transparansi 20% */">
                                    <button style="background-color: transparent; border: none; padding: 0; color: inherit;"
                                        data-bs-toggle="modal" data-bs-target="#deleteRecordModal"
                                        onclick="setNotificationIdToDelete('<?php echo e($notification->id); ?>')">
                                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Remove
                                    </button>

                                </div>
                                <div class="d-flex align-items-center mb-2 pt-3">
                                    <div class="me-3">
                                        <div class="avatar-sm">
                                            <span class="avatar-title bg-success-subtle rounded p-2">
                                                <img src="<?php echo e(URL::asset('build/images/message.png')); ?>"
                                                    style="width: 50px;height:40px" alt="" class="img-fluid p-1">
                                            </span>
                                        </div>
                                    </div>
                                    <h5 class="mb-1 fs-15"><a href="apps-projects-overview"
                                            class="text-body"><?php echo e($notification->title); ?></a></h5>
                                </div>
                                <p class="text-muted text-truncate-two-lines mb-3 mt-3">
                                    <?php echo e($notification->message); ?></p>

                            </div>

                        </div>
                        <!-- end card body -->
                        <div class="card-footer bg-transparent border-top-dashed py-2">
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="">
                                    <div class="text-muted">
                                        <i class="ri-calendar-event-fill me-1 align-bottom"></i>
                                        <?php echo e(\Carbon\Carbon::parse($notification->created_at)->format('d F Y')); ?>


                                    </div>
                                </div>

                            </div>

                        </div>
                        <!-- end card footer -->
                    </div>
                    <!-- end card -->
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <div class="col-sm-6">
                <div
                    class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                    <?php if($notifications->onFirstPage()): ?>
                        <div class="page-item disabled">
                            <span class="page-link">Previous</span>
                        </div>
                    <?php else: ?>
                        <div class="page-item">
                            <a href="<?php echo e($notifications->appends(request()->except('page'))->previousPageUrl()); ?>"
                                class="page-link" id="page-prev">Previous</a>
                        </div>
                    <?php endif; ?>

                    <!-- Page Numbers -->
                    <span id="page-num" class="pagination">
                        <?php $__currentLoopData = $notifications->links()->elements[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($page == $notifications->currentPage()): ?>
                                <span class="page-item active"><span class="page-link"><?php echo e($page); ?></span></span>
                            <?php else: ?>
                                <a href="<?php echo e($notifications->appends(request()->except('page'))->url($page)); ?>"
                                    class="page-item"><span class="page-link"><?php echo e($page); ?></span></a>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </span>

                    <?php if($notifications->hasMorePages()): ?>
                        <div class="page-item">
                            <a href="<?php echo e($notifications->appends(request()->except('page'))->nextPageUrl()); ?>"
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
        <!-- Modal Hapus -->
        <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-labelledby="deleteRecordLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" id="deleteRecord-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-5 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                            colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px">
                        </lord-icon>
                        <div class="mt-4 text-center">
                            <h4 class="fs-semibold">You are about to delete a notification?</h4>
                            <p class="text-muted fs-14 mb-4 pt-1">Deleting this notification will remove it permanently
                                from the database.</p>
                            <div class="hstack gap-2 justify-content-center remove">
                                <button class="btn btn-link link-success fw-medium text-decoration-none"
                                    data-bs-dismiss="modal">
                                    <i class="ri-close-line me-1 align-middle"></i> Close
                                </button>
                                <!-- Form untuk hapus notifikasi -->
                                <form id="delete-form" method="POST" action="/message/delete">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <input type="hidden" name="notification_id" id="notification-id-to-delete">
                                    <button type="submit" class="btn btn-danger" id="delete-record">Yes, Delete
                                        It!!</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--end delete modal -->
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/libs/list.js/list.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/list.pagination.js/list.pagination.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/crm-companies.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/sweetalert2/sweetalert2.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
    <script>
        function setNotificationIdToDelete(id) {
            document.getElementById('notification-id-to-delete').value = id;
        }

        <?php if(session('success')): ?>
            <
            div class = "alert alert-success" >
            <?php echo e(session('success')); ?>

                <
                /div>
        <?php endif; ?>
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\magang\test ui adminDashboard\Laravel\corporate\resources\views/apps-crm-messages.blade.php ENDPATH**/ ?>