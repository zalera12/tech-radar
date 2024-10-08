<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index" class="logo logo-dark">
            <a href="/index">
                <h3>Tech Radar</h3>
            </a>
        </a>
        <!-- Light Logo-->
        <a href="index" class="logo logo-light">
            <span class="logo-sm">
                <img src="<?php echo e(URL::asset('build/images/logo-sm.png')); ?>" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?php echo e(URL::asset('build/images/logo-light.png')); ?>" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span><?php echo app('translator')->get('translation.menu'); ?></span></li>
                <div class="menu-dropdown">
                    <ul class="nav nav-sm gap-3 flex-column">
                        <a class="nav-item d-flex gap-2 align-items-center <?php echo e(request()->is('index') ? 'active text-primary' : ''); ?>"
                            href="/index">
                            <img src="<?php echo e(asset('/build/images/dashboard.png')); ?>"
                                style="width: 25px;height:25px;border-radius:50%;">
                            <span
                                class="<?php echo e(request()->is('index') ? 'text-primary' : 'text-muted'); ?>"><?php echo app('translator')->get('translation.dashboards'); ?></span>
                        </a>
                        <a class="nav-item d-flex gap-2 align-items-center"
                            href="/">
                            <img src="<?php echo e(asset('/build/images/home.png')); ?>"
                                style="width: 30px;height:30px;border-radius:50%;">
                            <span
                                class="text-muted">Home</span>
                        </a>
                    </ul>

                </div>
                
                <li class="menu-title" style="">
                    <span>Companies</span>
                </li>

                
                <?php
                    $user = auth()->user();
                    $acceptedCompanies = $user->companies()->wherePivot('status', 'Accepted')->get();
                ?>
                <?php if($acceptedCompanies->isNotEmpty()): ?>
                    <div class="menu-dropdown" id="sidebarCompanies">
                        <ul class="nav nav-sm gap-3 flex-column">
                            <?php $__currentLoopData = $acceptedCompanies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    // Mengambil ID role dari pivot
                                    $pivot = $user
                                        ->companies()
                                        ->where('company_id', $company->id)
                                        ->first()->pivot;
                                    $roleId = $pivot ? $pivot->role_id : null;

                                    // Mengambil role berdasarkan ID dari relasi roles
                                    $role = $roleId ? \App\Models\Role::find($roleId) : null;

                                    // Tentukan apakah dropdown harus aktif berdasarkan parameter URL
                                    $isExpanded = request('idcp') == $company->id ? 'show' : '';
                                    $ariaExpanded = request('idcp') == $company->id ? 'true' : 'false';
                                ?>

                                <li class="nav-item">
                                    <a class="d-flex gap-2 align-items-center company-link"
                                        href="#collapse-<?php echo e($company->id); ?>" data-bs-toggle="collapse" role="button"
                                        aria-expanded="<?php echo e($ariaExpanded); ?>" aria-controls="collapse-<?php echo e($company->id); ?>"
                                        id="text-sidebar">
                                        <img src="<?php echo e(asset($company->image ? '/storage/' . $company->image : '/build/images/users/multi-user.jpg')); ?>"
                                            style="width: 25px;height:25px;border-radius:50%;">

                                        <!-- Kondisi untuk memeriksa jika dropdown aktif, maka tambahkan kelas 'text-primary' untuk warna biru -->
                                        <span
                                            class="<?php echo e($ariaExpanded == 'true' ? 'text-primary' : 'text-muted'); ?>"><?php echo e($company->name); ?></span>
                                    </a>


                                    <div class="collapse menu-dropdown <?php echo e($isExpanded); ?>"
                                        id="collapse-<?php echo e($company->id); ?>">
                                        <ul class="nav nav-sm flex-column">
                                            <!-- Link ke Main Page -->
                                            <li class="nav-item">
                                                <a href="/companies/main/<?php echo e($company->id); ?>?permission=Read Company Profile&idcp=<?php echo e($company->id); ?>"
                                                    class="nav-link <?php echo e(request()->is('companies/main/' . $company->id) ? 'text-primary' : ''); ?>">
                                                    Main Page
                                                </a>
                                            </li>
                                            <!-- Link ke Categories -->
                                            <li class="nav-item">
                                                <a href="/companies/categories/<?php echo e($company->id); ?>?permission=Read Category Technology&idcp=<?php echo e($company->id); ?>"
                                                    class="nav-link <?php echo e(request()->is('companies/categories/' . $company->id) ? 'text-primary' : ''); ?>">
                                                    Categories
                                                </a>
                                            </li>
                                            <!-- Link ke Technologies -->
                                            <li class="nav-item">
                                                <a href="/companies/technologies/<?php echo e($company->id); ?>?permission=Read Technology&idcp=<?php echo e($company->id); ?>"
                                                    class="nav-link <?php echo e(request()->is('companies/technologies/' . $company->id) ? 'text-primary' : ''); ?>">
                                                    Technologies
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="/companies/users/<?php echo e($company->id); ?>?permission=Read Company User&idcp=<?php echo e($company->id); ?>"
                                                    class="nav-link <?php echo e(request()->is('companies/users/' . $company->id) ? 'text-primary' : ''); ?>">
                                                    Employees
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="/companies/roles/<?php echo e($company->id); ?>?permission=Read Company Role&idcp=<?php echo e($company->id); ?>"
                                                    class="nav-link <?php echo e(request()->is('companies/roles/' . $company->id) ? 'text-primary' : ''); ?>">
                                                    Roles
                                                </a>
                                            </li>
                                            <!-- Link ke Permissions, hanya muncul jika role user adalah Owner -->
                                                <li class="nav-item">
                                                    <a href="/companies/permissions/<?php echo e($company->id); ?>?permission=Read User permission&idcp=<?php echo e($company->id); ?>"
                                                        class="nav-link <?php echo e(request()->is('companies/permissions/' . $company->id) ? 'text-primary' : ''); ?>">
                                                        Permissions
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="/companies/pendingMember/<?php echo e($company->id); ?>?permission=Read Pending Company User&idcp=<?php echo e($company->id); ?>"
                                                        class="nav-link <?php echo e(request()->is('companies/pendingMember/' . $company->id) ? 'text-primary' : ''); ?>">
                                                        Pending Member
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="/companies/log/<?php echo e($company->id); ?>?permission=Read Change Log&idcp=<?php echo e($company->id); ?>"
                                                        class="nav-link <?php echo e(request()->is('companies/log/' . $company->id) ? 'text-primary' : ''); ?>">
                                                        Logs
                                                    </a>
                                                </li>
                                        </ul>
                                    </div>

                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                </li>
                <!-- end Dashboard Menu -->
                <li class="menu-title" style="">
                    <span>Notification</span>
                </li>
                <div class="menu-dropdown" style="position: relative">
                    <ul class="nav nav-sm gap-3 flex-column">
                        <a class="nav-item d-flex gap-2 align-items-center <?php echo e(request()->is('message') ? 'active text-primary' : ''); ?>"
                            href="/message">
                            <img src="<?php echo e(asset('/build/images/message.png')); ?>"
                                style="width: 25px;height:25px;border-radius:50%;">
                            <span
                                class="<?php echo e(request()->is('message') ? 'text-primary' : 'text-muted'); ?>">Notifications</span>
                        </a>
                    </ul>
                    <?php
                    $messageCount = App\Models\Notification::where('user_id', auth()->user()->id)
                        ->where('is_read', false)
                        ->count();
                    ?>
                    <?php if($messageCount > 0): ?>
                        <span
                            style="display: flex;justify-content:center;align-items:center;width: 18px;height:18px;border-radius:50%;color:white;font-size:10px;position: absolute;top:5px;right:62px;"
                            class="bg-primary me-3 "><?php echo e($messageCount); ?></span>
                    <?php endif; ?>
                </div>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
<?php /**PATH E:\magang\test ui adminDashboard\Laravel\corporate\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>