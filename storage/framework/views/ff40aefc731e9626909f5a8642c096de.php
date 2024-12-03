
<?php $__env->startSection('title'); ?>
    <?php echo e($company->name); ?>

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
                                                <div class="fw-medium">Create Date : <span
                                                        class=""><?php echo e($created_date); ?></span></div>
                                                <div class="vr"></div>
                                                <div class="fw-medium">Your role in this company : <span
                                                        class=""><?php echo e($role->name); ?></span></div>
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
                                            <button class="btn btn-danger btn-custom me-2" data-bs-toggle="modal"
                                                data-bs-target="#deleteCompanyModal" data-id="<?php echo e($company->id); ?>">
                                                <i class="ri-delete-bin-line align-bottom me-1"></i> Delete Company
                                            </button>
                                            <?php 
                                                  $roleId = App\Models\company_users::where('company_id', $company->id)
                                                        ->where('user_id', $user->id)
                                                        ->pluck('role_id')
                                                        ->first();
                                                    $role = App\Models\Role::where('id',$roleId)->first()->name;
                                            ?>
                                            <?php if($role != 'OWNER'): ?>
                                                <button class="btn btn-danger btn-custom me-2" data-bs-toggle="modal"
                                                    data-bs-target="#leavingCompanyModal" data-id="<?php echo e($company->id); ?>">
                                                    <i class="ri-share-forward-2-line align-bottom me-1"></i> Leaving The Company
                                                </button>
                                            <?php endif; ?>
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
                                                            <input type="hidden" name="user"
                                                                value="<?php echo e($user->name); ?>">
                                                            <button type="submit" class="btn btn-danger"
                                                                id="delete-record">Yes, Delete It!</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade zoomIn" id="leavingCompanyModal" tabindex="-1"
                                    aria-labelledby="leavingCompanyLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="btn-close" id="leavingRecord-close"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body p-5 text-center">
                                                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                                    colors="primary:#405189,secondary:#f06548"
                                                    style="width:90px;height:90px">
                                                </lord-icon>
                                                <div class="mt-4 text-center">
                                                    <h4 class="fs-semibold">Are you sure you want to leaving this company?
                                                    </h4>
                                                    <p class="text-muted fs-14 mb-4 pt-1">Leaving from this company will remove
                                                        you from the company.</p>
                                                    <div class="hstack gap-2 justify-content-center remove">
                                                        <button
                                                            class="btn btn-link link-success fw-medium text-decoration-none"
                                                            data-bs-dismiss="modal">
                                                            <i class="ri-close-line me-1 align-middle"></i> Close
                                                        </button>
                                                        <form id="leaving-form" method="POST"
                                                            action="/companies/leaving/<?php echo e($company->id); ?>?permission=Leaving The Company&idcp=<?php echo e($company->id); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="id" id="leaving-company-id">
                                                            <input type="hidden" name="user"
                                                                value="<?php echo e($user->name); ?>">
                                                            <button type="submit" class="btn btn-danger"
                                                                id="leaving-record">Yes, I am!</button>
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
                                            <form
                                                action="/companies/edit/<?php echo e($company->id); ?>?permission=Edit Company&idcp=<?php echo e($company->id); ?>"
                                                method="POST" enctype="multipart/form-data" autocomplete="off"
                                                id="editCompanyForm">
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
                                                                class="form-label text-black mb-1">Company
                                                                Image</label>
                                                            <input type="file" class="form-control" id="image"
                                                                name="image" accept="image/*"
                                                                onchange="previewImage(event)">
                                                        </div>
                                                        <!-- Input for company name -->
                                                        <div class="col-lg-12">
                                                            <label for="name"
                                                                class="form-label text-black mb-1">Company Name</label>
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
                                                        <div class="col-lg-12">
                                                            <label for="status"
                                                                class="form-label text-black mb-1">status
                                                                <span style="color:var(--error)">*</span>
                                                            </label>
                                                            <select
                                                                class="form-select  <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                name="status" aria-label="Default select example">
                                                                <option selected class="text-muted">Select a status
                                                                </option>
                                                                <option value="private"
                                                                    <?php echo e($company->status == 'private' ? 'selected' : ''); ?>>
                                                                    private</option>
                                                                <option value="public"
                                                                    <?php echo e($company->status == 'public' ? 'selected' : ''); ?>>
                                                                    public</option>
                                                            </select>
                                                            <?php $__errorArgs = ['status'];
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
                                                            <label for="description" class="form-label text-black" style="font-weight:600;">Description
                                                                <span style="color: var(--error);">*</span>
                                                            </label>
                                                            <textarea id="description" name="description" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5"><?php echo e($company->description); ?></textarea>
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
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="text-muted">
                                        <h6 class="mb-3 fw-semibold text-uppercase">Description</h6>
                                        <p><?php echo $company->description; ?></p>


                                        <div class="pt-3 border-top border-top-dashed mt-4">
                                            <div class="row gy-3">
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 fw-medium" style="font-size: 14px;">Create Date :
                                                        </p>
                                                        <h5 class="fs-15 mb-0">
                                                            <?php echo e(\Carbon\Carbon::parse($company->created_at)->format('d F Y')); ?>

                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 fw-medium" style="font-size: 14px;">Code Company :
                                                        </p>
                                                        <div class="badge bg-success fs-12"><?php echo e($company->code); ?></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 fw-medium" style="font-size: 14px;">Total Members :
                                                        </p>
                                                        <div class="badge bg-danger fs-12"><?php echo e($company->users->count()); ?>

                                                            employee</div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-6">
                                                    <div>
                                                        <p class="mb-2 fw-medium" style="font-size: 14px;">Status :</p>
                                                        <div class="badge bg-warning fs-12"><?php echo e($company->status); ?></div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class=" fw-medium text-muted text-truncate mb-0"> Total Employess
                            </p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <?php
                            $totalEmployess = $company->users->count();
                            ?>
                            <h4 class="fs-20 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                    data-target="<?php echo e($totalEmployess); ?>">0</span> Employee</h4>
                            <a href="/companies/users/<?php echo e($company->id); ?>?permission=Read Company User&idcp=<?php echo e($company->id); ?>"
                                class="text-decoration-underline">View All</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-secondary-subtle rounded fs-3">
                                <i class='bx bxs-user text-secondary'></i>
                            </span>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class=" fw-medium text-muted text-truncate mb-0">Total Roles</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <?php
                            $roles = $company->roles->unique('id'); // Ganti 'name' dengan atribut yang ingin di-unique
                            $totalRoles = $roles->count();
                            ?>
                            <h4 class="fs-20 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                    data-target="<?php echo e($totalRoles); ?>">0</span> Role</h4>
                            <a href="/companies/roles/<?php echo e($company->id); ?>?permission=Read Company Role&idcp=<?php echo e($company->id); ?>"
                                class="text-decoration-underline">View All</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-primary-subtle rounded fs-3">
                                <i class='bx bxs-user-detail text-primary'></i>
                            </span>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class=" fw-medium text-muted text-truncate mb-0">Total Categories</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <?php
                            $totalCategories = $company->categories->count();
                            ?>
                            <h4 class="fs-20 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                    data-target="<?php echo e($totalCategories); ?>">0</span> Category</h4>
                            <a href="/companies/categories/<?php echo e($company->id); ?>?permission=Read Category Technology&idcp=<?php echo e($company->id); ?>"
                                class="text-decoration-underline">View All</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                <i class='bx bxs-book-content text-success'></i>
                            </span>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->

        <div class="col-xl-3 col-md-6">
            <!-- card -->
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class=" fw-medium text-muted text-truncate mb-0">Total Technologies</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <?php
                            $totalTechnologies = $company->technologies->count();
                            
                            ?>
                            <h4 class="fs-20 fw-semibold ff-secondary mb-4"><span class="counter-value"
                                    data-target="<?php echo e($totalTechnologies); ?>">0</span> Technologies </h4>
                            <a href="/companies/technologies/<?php echo e($company->id); ?>?permission=Read Technology&idcp=<?php echo e($company->id); ?>"
                                class="text-decoration-underline">View All</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-success-subtle rounded fs-3">
                                <i class="ri-window-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->


    </div> <!-- end row-->
    <div>
        <h4 class="mt-1">All Categories</h4>
        <div class="row mt-3">
            <?php
            $categories = $company->categories;
            ?>
            <?php if($categories->isNotEmpty()): ?>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-end">
                                    <i class="ri-window-line align-bottom me-1"></i>
                                    <?php
                                    $totalTechnologies = App\Models\Technology::where('company_id', $company->id)
                                        ->where('category_id', $category->id)
                                        ->count();
                                    ?>
                                    <?php echo e($totalTechnologies); ?> Technologies
                                </div>
                                <h6 class="card-title mb-0"><?php echo e($category->name); ?></h6>
                            </div>
                            <div class="card-body" style="height: 135px">
                                <p class="card-text text-muted mb-0"><?php echo e($category->description); ?></p>
                            </div>
                            <div class="card-footer">
                                <?php if($totalTechnologies > 0): ?>
                                    <a href="https://viz.tech-radar.gci.my.id/?documentId=https://viz.tech-radar.gci.my.id/files/<?php echo e(strtoupper($category->name)); ?> - <?php echo e($company->name); ?>.json"
                                        class="link-success float-end">View Radar <i
                                        class="ri-arrow-right-s-line align-middle ms-1 lh-1"></i></a>
                                <?php else: ?>
                                    <a href="#"  
                                        class="link-danger float-end">No Technologies Available <i
                                        class="ri-alert-line align-middle ms-1 lh-1"></i></a>
                                <?php endif; ?>
                                <p class="text-muted mb-0">
                                    <?php echo e(\Carbon\Carbon::parse($category->created_at)->format('d F Y')); ?>

                                </p>
                            </div>
                        </div>
                        
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <div id="job-list"
                    style="display: flex;align-items:center;justify-content:center;margin-block:50px;gap:15px;flex-direction:column">
                    <img src="/build/images/warning.png" width="80px">
                    <h5>There are no categories in this company!</h5>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div>
        <div class="d-flex align-items-center justify-content-between mt-1">
            <h4 class="">Employees</h4>
            <a href="/companies/users/<?php echo e($company->id); ?>?permission=Read Company User&idcp=<?php echo e($company->id); ?>"
                class="d-flex align-items-center">
                <span style="font-size: 17px">see all</span>
                <i class="ri-arrow-right-s-line" style="font-size: 20px"></i>
            </a>
        </div>
        <div class="row mt-3">
            <?php
            $employess = $company
                ->users()
                ->wherePivot('status', 'ACCEPTED')
                ->orderBy('pivot_created_at', 'asc') // Urutkan berdasarkan waktu yang terlama
                ->get();
            ?>
            <?php $__currentLoopData = $employess; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header text-center">
                            <h6 class="card-title mb-0"></h6>
                            <?php
                            $roleId = App\Models\company_users::where('company_id', $company->id)
                                ->where('user_id', $employee->id)
                                ->pluck('role_id')
                                ->first();
                            $role = App\Models\Role::where('id',$roleId)->first()->name;
                            ?>
                            <p class="mb-0 fw-medium" style="font-size: 14px;"><?php echo e($role); ?></p>
                        </div>
                        <div class="card-body p-4 text-center">
                            <div class="mx-auto avatar-md mb-3">
                                <img src="<?php echo e(asset($employee->photo ? 'storage/' . $employee->photo : '/build/images/users/user-dummy-img.jpg')); ?>"
                                    alt="user-img" class="img-thumbnail rounded-circle mb-1"
                                    style="width: 70px;height:70px" />
                            </div>
                            <h5 class="card-title mb-1"><?php echo e($employee->name); ?></h5>
                            <?php
                            //cari data company dan user tertentu
                            $waktu_masuk = App\Models\company_users::where('company_id', $company->id)
                                ->where('user_id', $employee->id)
                                ->pluck('created_at')
                                ->first();
                            ?>
                            <p class="text-muted mb-0 fw-medium" style="font-size: 12px;"><?php echo e($waktu_masuk->diffForHumans()); ?></p>

                        </div>
                        <div class="card-footer text-center">
                            <i class="ri-window-line align-bottom me-1"></i>
                            <?php
                            $totalTechnologies = App\Models\Technology::where('company_id', $company->id)
                                ->where('user_id', $employee->id)
                                ->count();
                            ?>
                            <?php echo e($totalTechnologies); ?> Technologies
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
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