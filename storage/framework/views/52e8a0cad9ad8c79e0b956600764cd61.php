<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.dashboards'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('build/libs/swiper/swiper-bundle.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col">

            <div class="h-100">
                <div class="flex-grow-1">
                    <h4 class="fs-16 mb-1">Welcome, <?php echo e($user->name); ?>!</h4>
                    <p class="text-muted mb-0">Here's what's happening with your store today.</p>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between button-container">
                                    <button class="btn btn-primary btn-custom" data-bs-toggle="modal" data-bs-target="#JoinCompanyModal">
                                        <i class="ri-add-line align-bottom me-1"></i> Join Company
                                    </button>
                                    
                                    <button class="btn btn-primary btn-custom" data-bs-toggle="modal" data-bs-target="#CreateJobModal">
                                        <i class="ri-add-line align-bottom me-1"></i> Create Company
                                    </button>
                                </div>

                                <div class="row mt-3 gy-3">
                                    <div class="col-xxl-10 col-md-6">
                                        <div class="search-box">
                                            <input type="text" class="form-control search bg-light border-light"
                                                id="searchJob" autocomplete="off"
                                                placeholder="Search for jobs or companies...">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>
                                    <div class="col-xxl-2 col-md-6">
                                        <div class="input-light">
                                            <select class="form-control" data-choices data-choices-search-false
                                                name="choices-single-default" id="idStatus">
                                                <option value="All">All Selected</option>
                                                <option value="Newest" selected>Newest</option>
                                                <option value="Popular">Popular</option>
                                                <option value="Oldest">Oldest</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 d-none" id="found-job-alert">
                                        <div class="alert alert-success mb-0 text-center" role="alert">
                                            <strong id="total-result">253</strong> jobs found
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h4 class="mt-2" style="margin-bottom: 30px;">List Companies</h4>
                        <div class="row">
                            <div class="col-xxl-9">
                                <?php if($dataCompanies->isNotEmpty()): ?>
                                <div id="job-list">
                                    <?php $__currentLoopData = $dataCompanies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="card joblist-card">
                                        <div class="card-body">
                                            <div class="d-flex mb-4 align-items-center">
                                                <div class="avatar-md">
                                                    <div class="avatar-title bg-light rounded"> 
                                                        <img src="<?php echo e(asset($data->image ? '/storage/'.$data->image : '/build/images/users/multi-user.jpg')); ?>" alt="" class="companyLogo-img" style="width: 60px;height:60px"> 
                                                    </div>
                                                </div>
                                                <div class="ms-3 flex-grow-1"> 
                                                    <img src="build/images/small/img-8.jpg" alt="" class="d-none cover-img"> 
                                                    <a href="#!">
                                                        <h5 class="job-title"><?php echo e($data->name); ?></h5>
                                                    </a>
                                                    <!-- Menghapus title kedua dan tombol bookmark -->
                                                </div>
                                            </div>
                                            <p class="text-muted job-description">
                                                <?php echo $data->description; ?>

                                            </p>
                                        </div>
                                        <div class="card-footer border-top-dashed">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                                <div><i class="ri-user-3-line align-bottom me-1"></i> 74 Applied</div>
                                                <div><i class="ri-time-line align-bottom me-1"></i> 
                                                    <span class="job-postdate"><?php echo e(\Carbon\Carbon::parse($data->created_at)->format('d F Y')); ?></span>
                                                </div>
                                                <div>
                                                    <a href="#!" class="btn btn-primary viewjob-list">View More 
                                                        <i class="ri-arrow-right-line align-bottom ms-1"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>      
                                <?php else: ?>
                                    <div id="job-list">
                                        <h2>Anda tidak terkait dengan perusahaan manapun.</h2>
                                    </div>
                                <?php endif; ?>
                              
                                <div class="row g-0 justify-content-end mb-4" id="pagination-element">
                                    <!-- end col -->
                                    <div class="col-sm-6">
                                        <div
                                            class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
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
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="CreateJobModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0">
                            <form id="createjob-form" action="/companies/add" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="modal-body">
                                    <div class="row g-3">
                                        <div class="col-lg-12">
                                            <div class="px-1 pt-1">
                                                <div
                                                    class="modal-team-cover position-relative mb-0 mt-n4 mx-n4 rounded-top overflow-hidden">
                                                    <img src="<?php echo e(URL::asset('build/images/small/img-9.jpg')); ?>"
                                                        alt="" id="modal-cover-img" class="img-fluid">

                                                    <div class="d-flex position-absolute start-0 end-0 top-0 p-3">
                                                        <div class="flex-grow-1">
                                                            <h5 class="modal-title text-white" id="exampleModalLabel">
                                                                Create
                                                                New Companies
                                                            </h5>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <div class="d-flex gap-3 align-items-center">
                                                          
                                                                <button type="button" class="btn-close btn-close-white"
                                                                    id="close-jobListModal" data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center mb-4 mt-n5 pt-2">
                                                <div class="position-relative d-inline-block">
                                                    <div class="position-absolute bottom-0 end-0">
                                                    </div>
                                                    <div class="avatar-lg p-1">
                                                        <div class="avatar-title bg-light rounded-circle">
                                                            <img src="<?php echo e(URL::asset('build/images/users/multi-user.jpg')); ?>"
                                                                id="companylogo-img"
                                                                class="avatar-md rounded-circle object-fit-cover" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <h5 class="fs-13 mt-3">Create Company</h5>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label for="name" class="form-label text-secondary mb-1">Company Name
                                                <span style="color:var(--error)">*</span></label>
                                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                id="name" placeholder="Enter company name" name="name"
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
                                        <div class="mb-4">
                                            <label for="description" class="form-label text-secondary" style="font-weight:600;">Description
                                                <span style="color: var(--error);">*</span>
                                            </label>
                                            <input id="description" name="description" type="hidden" class="<?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('description')); ?>">
                                            <trix-editor input="description"></trix-editor>
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

                                        
                                        <div class="mb-4">
                                            <label for="image" class="form-label text-secondary mb-1">Company Logo</label>
                                            <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="image"
                                                name="image">
                                            <?php $__errorArgs = ['image'];
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
                                        <button type="submit" class="btn btn-success" id="add-btn">Add Job</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="JoinCompanyModal" tabindex="-1" aria-labelledby="joinCompanyLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0">
                            <form id="join-company-form" action="<?php echo e(route('company-users.join')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="modal-body">
                                    <div class="text-center mb-4">
                                        <h5 class="modal-title" id="joinCompanyLabel">Join Company</h5>
                                    </div>
                                    <div class="mb-4">
                                        <label for="company_code" class="form-label">Company Code</label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['company_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                               id="company_code" name="company_code" placeholder="Enter company code" required>
                                        <?php $__errorArgs = ['company_code'];
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
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-success">Join</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div> <!-- end .h-100-->


        </div> <!-- end col -->
    </div>
    <script>
        // Cek jika ada session berhasil
        <?php if(session('login_success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Selamat Datang!',
                text: "<?php echo e(session('login_success')); ?>",
                confirmButtonText: 'Oke',
            });
        <?php endif; ?>

        <?php if(session('add_success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "<?php echo e(session('add_success')); ?>",
                confirmButtonText: 'Oke',
            });
        <?php endif; ?>
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <!-- apexcharts -->
    <script src="<?php echo e(URL::asset('build/libs/apexcharts/apexcharts.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/jsvectormap/maps/world-merc.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/swiper/swiper-bundle.min.js')); ?>"></script>
    <!-- dashboard init -->
    <script src="<?php echo e(URL::asset('build/js/pages/dashboard-ecommerce.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\magang\test ui adminDashboard\Laravel\corporate\resources\views/index.blade.php ENDPATH**/ ?>