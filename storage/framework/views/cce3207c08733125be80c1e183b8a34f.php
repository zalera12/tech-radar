
<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.profile'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(URL::asset('build/libs/swiper/swiper-bundle.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="profile-foreground position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg">
            <img src="<?php echo e(URL::asset('build/images/profile-bg.jpg')); ?>" alt="" class="profile-wid-img" />
        </div>
    </div>
    <div class="pt-4 mb-4 mb-lg-3 pb-lg-4 profile-wrapper">
        <div class="row g-4 d-flex align-items-center">
            <div class="col-auto">
                <div class="avatar-lg">
                    <img src="<?php echo e(asset($user->photo ? 'storage/'.$user->photo : '/build/images/users/user-dummy-img.jpg')); ?>" alt="user-img"
                        class="img-thumbnail rounded-circle" style="width: 96px;height:96px" />
                </div>
            </div>
            <!--end col-->
            <div class="col">
                <div class="p-2">
                    <h3 class="text-white mb-1"><?php echo e($user->name); ?></h3>
                </div>
            </div>
            <!--end col-->

        </div>
        <!--end row-->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div>
                <div class="d-flex profile-wrapper">
                    <!-- Nav tabs -->
         
                    <div class="flex-shrink-0">
                        <a href="/auth-profile-settings" class="btn btn-secondary"><i
                                class="ri-edit-box-line align-bottom"></i> Edit Profile</a>
                    </div>
                </div>
                <!-- Tab panes -->
                <div class="tab-content pt-4 text-muted">
                    <div class="tab-pane active" id="overview-tab" role="tabpanel">
                        <div class="row">
                            <div class="col-xxl-3">
                                <div class="card">
                                    <?php
                                        // Menghitung persentase kelengkapan profil
                                        $profileCompletion = $user->photo ? 100 : 75;
                                    ?>

                                    <div class="card-body">
                                        <h5 class="card-title mb-5">Complete Your Profile</h5>
                                        <div class="progress animated-progress custom-progress progress-label">
                                            <div class="progress-bar <?php echo e($profileCompletion == 100 ? 'bg-success' : 'bg-danger'); ?>"
                                                role="progressbar" style="width: <?php echo e($profileCompletion); ?>%"
                                                aria-valuenow="<?php echo e($profileCompletion); ?>" aria-valuemin="0"
                                                aria-valuemax="100">
                                                <div class="label"><?php echo e($profileCompletion); ?>%</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">Info</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th class="ps-0" scope="row">Name :</th>
                                                        <td class="text-muted"><?php echo e($user->name); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-0" scope="row">E-mail :</th>
                                                        <td class="text-muted"><?php echo e($user->email); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-0" scope="row">Google ID :</th>
                                                        <td class="text-muted"><?php echo e($user->google_id); ?>

                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="ps-0" scope="row">Joining Date</th>
                                                        <td class="text-muted">
                                                            <?php echo e(\Carbon\Carbon::parse($user->created_at)->format('d F Y')); ?>

                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->

                            </div>
                        </div>
                        <!--end row-->
                    </div>

                </div>
                <!--end tab-content-->
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/libs/swiper/swiper-bundle.min.js')); ?>"></script>

    <script src="<?php echo e(URL::asset('build/js/pages/profile.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
    <script>
        <?php if(session('success_update')): ?>
        Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "<?php echo e(session('success_update')); ?>",
                confirmButtonText: 'Oke',
            });
        <?php endif; ?>
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\magang\test ui adminDashboard\Laravel\corporate\resources\views/pages-profile.blade.php ENDPATH**/ ?>