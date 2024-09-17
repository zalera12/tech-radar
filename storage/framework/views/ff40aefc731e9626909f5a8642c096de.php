<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.overview'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card mt-n4">
                <div class="bg-primary-subtle">
                    <div class="card-body pb-0 px-4">
                        <div class="row mb-3">
                            <div class="col-md">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-auto">
                                        <div class="avatar-md">
                                            <div class="avatar-title bg-white rounded-circle">
                                                <img src="<?php echo e(asset($company->image ? '/storage/' . $company->image : '/build/images/users/multi-user.jpg')); ?>"
                                                    alt="" class="avatar-xs"
                                                    style="width: 70px;height:70px;border-radius:50%">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div>
                                            <h4 class="fw-bold"><?php echo e($company->name); ?></h4>
                                            <div class="hstack gap-3 flex-wrap">
                                                <div>Create Date : <span class="fw-medium"><?php echo e($created_date); ?></span></div>
                                                <div class="vr"></div>
                                                <div>Your role in this company : <span
                                                        class="fw-medium"><?php echo e($role->name); ?></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Menambahkan baris baru untuk tombol di bawah informasi perusahaan -->
                                <div class="row mt-4">
                                    <div class="col-md">
                                        <div
                                            class="d-flex flex-column flex-md-row align-items-center justify-content-start button-container">
                                            <button class="btn btn-primary btn-custom me-2" data-bs-toggle="modal"
                                                data-bs-target="#editCompanyModal">
                                                <i class="ri-pencil-line align-bottom me-1"></i> Edit Company
                                            </button>
                                            <button class="btn btn-danger btn-custom" data-bs-toggle="modal"
                                                data-bs-target="#deleteCompanyModal" data-id="<?php echo e($company->id); ?>">
                                                <i class="ri-delete-bin-line align-bottom me-1"></i> Delete Company
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade zoomIn" id="deleteCompanyModal" tabindex="-1"
                                    aria-labelledby="deleteCompanyLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="btn-close" id="deleteRecord-close"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-5 text-center">
                                                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                                    colors="primary:#405189,secondary:#f06548"
                                                    style="width:90px;height:90px">
                                                </lord-icon>
                                                <div class="mt-4 text-center">
                                                    <h4 class="fs-semibold">Are you sure you want to delete this company?
                                                    </h4>
                                                    <p class="text-muted fs-14 mb-4 pt-1">Deleting this company will remove
                                                        it permanently from the database.</p>
                                                    <div class="hstack gap-2 justify-content-center remove">
                                                        <button
                                                            class="btn btn-link link-success fw-medium text-decoration-none"
                                                            data-bs-dismiss="modal">
                                                            <i class="ri-close-line me-1 align-middle"></i> Close
                                                        </button>
                                                        <form id="delete-form" method="POST"
                                                            action="/companies/delete/<?php echo e($company->id); ?>?permission=Delete Company&idcp=<?php echo e($company->id); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="id" id="delete-company-id">
                                                            <input type="hidden" name="user" value="<?php echo e($user->name); ?>">
                                                            <button type="submit" class="btn btn-danger"
                                                                id="delete-record">Yes, Delete It!</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Edit Company -->
                                <div class="modal fade" id="editCompanyModal" tabindex="-1"
                                    aria-labelledby="editCompanyModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content border-0">
                                            <div class="modal-header bg-info-subtle p-3">
                                                <h5 class="modal-title" id="editCompanyModalLabel">Edit Company</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="/companies/edit/<?php echo e($company->id); ?>?permission=Edit Company&idcp=<?php echo e($company->id); ?>" method="POST"
                                                enctype="multipart/form-data" autocomplete="off" id="editCompanyForm">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="id" value="<?php echo e($company->id); ?>">
                                                <input type="hidden" name="user" value="<?php echo e($user->name); ?>">
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <!-- Image Preview -->
                                                        <div class="col-lg-12 text-center mb-3">
                                                            <img id="preview-image"
                                                                src="<?php echo e(asset($company->image ? '/storage/' . $company->image : '/build/images/users/multi-user.jpg')); ?>"
                                                                alt="Company Image"
                                                                style="width: 150px;height:150px; border-radius: 50%">
                                                        </div>
                                                        <!-- Input for new image -->
                                                        <div class="col-lg-12">
                                                            <label for="image"
                                                                class="form-label text-secondary mb-1">Company Image</label>
                                                            <input type="file" class="form-control" id="image"
                                                                name="image" accept="image/*"
                                                                onchange="previewImage(event)">
                                                        </div>
                                                        <!-- Input for company name -->
                                                        <div class="col-lg-12">
                                                            <label for="name"
                                                                class="form-label text-secondary mb-1">Company Name</label>
                                                            <input type="text"
                                                                class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                id="name" name="name"
                                                                value="<?php echo e($company->name); ?>" required>
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
                                                        <!-- Input for company description -->
                                                        <div class="col-lg-12">
                                                            <label for="description" class="form-label text-secondary"
                                                                style="font-weight:600;">Description
                                                                <span style="color: var(--error);">*</span>
                                                            </label>
                                                            <input id="description" name="description" type="hidden"
                                                                class="<?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                value="<?php echo e($company->description); ?>">
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
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                    </div>
                    <!-- end card body -->
                </div>
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content text-muted">
                <div class="tab-pane fade show active" id="project-overview" role="tabpanel">
                    <div class="row">
                        <div class="col-xl-9 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-muted">
                                        <h6 class="mb-3 fw-semibold text-uppercase">Description</h6>
                                        <p><?php echo $company->description; ?></p>


                                        <div class="pt-3 border-top border-top-dashed mt-4">
                                            <div class="row gy-3">
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Create Date :</p>
                                                        <h5 class="fs-15 mb-0">15 Sep, 2021</h5>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Due Date :</p>
                                                        <h5 class="fs-15 mb-0">29 Dec, 2021</h5>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Priority :</p>
                                                        <div class="badge bg-danger fs-12">High</div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 text-uppercase fw-medium">Status :</p>
                                                        <div class="badge bg-warning fs-12">Inprogress</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->


                        </div>
                        <!-- ene col -->
                        <div class="col-xl-3 col-lg-4">
                            <!-- end card -->

                            <div class="card">
                                <div class="card-header align-items-center d-flex border-bottom-dashed">
                                    <h4 class="card-title mb-0 flex-grow-1">Members</h4>
                                </div>

                                <div class="card-body">
                                    <div data-simplebar style="height: auto;" class="mx-n3 px-3">
                                        <div class="vstack gap-3">
                                            <?php $__currentLoopData = $companyMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-xs flex-shrink-0 me-3">
                                                        <img src="<?php echo e(asset($member['user']->photo ? 'storage/' . $member['user']->photo : '/build/images/users/user-dummy-img.jpg')); ?>"
                                                            alt="" class="img-fluid rounded-circle">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h5 class="fs-13 mb-0"><a href="#"
                                                                class="text-body d-block"><?php echo e($member['user']->name); ?></a>
                                                        </h5>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <div class="d-flex align-items-center gap-1">
                                                            <span
                                                                class="badge bg-success-subtle text-success"><?php echo e($member['role']->name); ?></span>

                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        <!-- end list -->
                                    </div>
                                </div>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>

            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/js/pages/project-overview.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/pages/project-overview.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.remove-item-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    document.getElementById('delete-company-id').value = id;
                });
            });
        });

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('preview-image');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\magang\test ui adminDashboard\Laravel\corporate\resources\views/apps-projects-overview.blade.php ENDPATH**/ ?>