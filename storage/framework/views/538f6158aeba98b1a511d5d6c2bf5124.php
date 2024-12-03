
<?php $__env->startSection('title'); ?>
    Pending Members
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
            Pending Members
        <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>

    <div class="row mt-1">
        <!--end col-->
        <div class="">
            <div class="card" id="companyList">
                <div class="card-header">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <form action="<?php echo e(url()->current()); ?>" method="GET">
                                <div class="input-group mb-3">
                                    <input type="text" name="search" class="form-control search bg-light border-light"
                                        id="searchJob" value="<?php echo e(request('search')); ?>"
                                        placeholder="Search for pending members...">
                                    <!-- Memastikan filter tetap dibawa ketika search dilakukan -->
                                    <input type="hidden" name="sort_order" value="<?php echo e(request('sort_order')); ?>">
                                    <input type="hidden" name="permission" value="Read Pending Company User">
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
                                    <input type="hidden" name="permission" value="Read Pending Company User">
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
                            <?php if($pendingMembers->isNotEmpty()): ?>
                                <table class="table align-middle table-nowrap mb-0" id="pendingMemberTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Photo</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $pendingMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr data-id="<?php echo e($member->id); ?>" data-company-id="<?php echo e($company->id); ?>"
                                                data-role-id="<?php echo e($member->pivot->role_id); ?>"
                                                data-status="<?php echo e($member->pivot->status); ?>">
                                                <td><?php echo e($pendingMembers->firstItem() + $index); ?></td>
                                                <!-- Adjust index for pagination -->
                                                <td>
                                                    <img src="<?php echo e(asset($member->photo ? 'storage/' . $member->photo : '/build/images/users/user-dummy-img.jpg')); ?>"
                                                        alt="User Photo" class="avatar-xxs rounded-circle object-fit-cover">
                                                </td>
                                                <td><?php echo e($member->name); ?></td>
                                                <td><?php echo e($member->email); ?></td>
                                                <td>
                                                    <?php
                                                    $roleId = $member->pivot->role_id;
                                                    $role = App\Models\Role::find($roleId);
                                                    ?>
                                                    <?php echo e($role ? $role->name : 'N/A'); ?>

                                                </td>
                                                <td><?php echo e($member->pivot->status); ?></td>
                                                <td>
                                                    <a href="#editPendingMemberModal" data-bs-toggle="modal">
                                                        <i class="edit-item-btn ri-pencil-fill align-bottom text-muted"></i>
                                                    </a>
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
                                        <h5 class="mt-2">Apologies, No Pending Members Data Available</h5>
                                        <p class="text-muted mb-0">Unfortunately, there are no Pending Members available to
                                            display.</p>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            <div class="col-sm-6">
                                <div
                                    class="pagination-block pagination pagination-separated justify-content-center justify-content-sm-end mb-sm-0">
                                    <?php if($pendingMembers->onFirstPage()): ?>
                                        <div class="page-item disabled">
                                            <span class="page-link">Previous</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="page-item">
                                            <a href="<?php echo e($pendingMembers->appends(request()->except('page'))->previousPageUrl()); ?>"
                                                class="page-link" id="page-prev">Previous</a>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Page Numbers -->
                                    <span id="page-num" class="pagination">
                                        <?php $__currentLoopData = $pendingMembers->links()->elements[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($page == $pendingMembers->currentPage()): ?>
                                                <span class="page-item active"><span
                                                        class="page-link"><?php echo e($page); ?></span></span>
                                            <?php else: ?>
                                                <a href="<?php echo e($pendingMembers->appends(request()->except('page'))->url($page)); ?>"
                                                    class="page-item"><span
                                                        class="page-link"><?php echo e($page); ?></span></a>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </span>

                                    <?php if($pendingMembers->hasMorePages()): ?>
                                        <div class="page-item">
                                            <a href="<?php echo e($pendingMembers->appends(request()->except('page'))->nextPageUrl()); ?>"
                                                class="page-link" id="page-next">Next</a>
                                        </div>
                                    <?php else: ?>
                                        <div class="page-item disabled">
                                            <span class="page-link">Next</span>
                                        </div>
                                    <?php endif; ?>
                                </div>




                            </div><!-- end col -->

                        </div>
                    </div>
                    <!-- Modal Edit Role for User -->
                    <div class="modal fade" id="editPendingMemberModal" tabindex="-1"
                        aria-labelledby="editPendingMemberModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="editPendingMemberForm"
                                    action="<?php echo e(route('companies.pendingMember.update', ['member' => ':id'])); ?>"
                                    method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>

                                    <!-- Hidden inputs to send permission and idcp -->
                                    <input type="hidden" name="permission" value="Acc Company User">
                                    <input type="hidden" name="idcp" value="<?php echo e($company->id); ?>">
                                    <input type="hidden" name="user" value="<?php echo e($user->name); ?>">
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

        <?php if(session('update_success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "<?php echo e(session('update_success')); ?>",
                confirmButtonText: 'Oke',
            });
        <?php endif; ?>
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\magang\test ui adminDashboard\Laravel\corporate\resources\views/apps-crm-pending-members.blade.php ENDPATH**/ ?>