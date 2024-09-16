<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="17">
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
                <li class="menu-title"><span>@lang('translation.menu')</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="ri-dashboard-2-line"></i> <span>@lang('translation.dashboards')</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="/index" class="nav-link">Main Dashboard</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarAuth" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarAuth">
                        <i class="ri-account-circle-line"></i> <span>Your Acount</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarAuth">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="/auth-profile" class="nav-link">@lang('translation.profile')
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:void(0);" onclick="confirmLogout(event)"
                                    class="nav-link">@lang('translation.logout')
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarCompanies" data-bs-toggle="collapse" role="button"
                        aria-expanded="false" aria-controls="sidebarCompanies">
                        <i class="ri-community-line"></i> <span>Companies</span>
                    </a>

                    {{-- Ambil data perusahaan yang terkait dengan user dengan status Accepted dari pivot --}}
                    @php
                        $user = auth()->user();
                        $acceptedCompanies = $user->companies()->wherePivot('status', 'Accepted')->get();
                    @endphp

                    @if ($acceptedCompanies->isNotEmpty())
                        <div class="collapse menu-dropdown" id="sidebarCompanies">
                            <ul class="nav nav-sm flex-column">
                                @foreach ($acceptedCompanies as $company)
                                    @php
                                        // Mengambil ID role dari pivot
                                        $pivot = $user
                                            ->companies()
                                            ->where('company_id', $company->id)
                                            ->first()->pivot;
                                        $roleId = $pivot ? $pivot->role_id : null;

                                        // Mengambil role berdasarkan ID dari relasi roles
                                        $role = $roleId ? \App\Models\Role::find($roleId) : null;
                                    @endphp

                                    <li class="nav-item">
                                        <a href="#collapse-{{ $company->id }}" class="nav-link"
                                            data-bs-toggle="collapse" role="button" aria-expanded="false"
                                            aria-controls="collapse-{{ $company->id }}">{{ $company->name }}</a>
                                        <div class="collapse menu-dropdown" id="collapse-{{ $company->id }}">
                                            <ul class="nav nav-sm flex-column">
                                                <!-- Link ke Main Page -->
                                                <li class="nav-item">
                                                    <a href="/companies/main/{{ $company->id }}?permission=Read Company Profile&idcp={{ $company->id }}"
                                                        class="nav-link">Main Page</a>
                                                </li>
                                                <!-- Link ke Technologies -->
                                                <li class="nav-item">
                                                    <a href="/companies/technologies/{{ $company->id }}?permission=Read Technology&idcp={{ $company->id }}"
                                                        class="nav-link">Technologies</a>
                                                </li>
                                                <!-- Link ke Categories -->
                                                <li class="nav-item">
                                                    <a href="/companies/categories/{{ $company->id }}?permission=Read Category Technology&idcp={{ $company->id }}"
                                                        class="nav-link">Categories</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="/companies/users/{{ $company->id }}?permission=Read Company User&idcp={{ $company->id }}"
                                                        class="nav-link">Users</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="/companies/roles/{{ $company->id }}?permission=Read Company Role&idcp={{ $company->id }}"
                                                        class="nav-link">Roles</a>
                                                </li>
                                                <!-- Link ke Permissions, hanya muncul jika role user adalah Owner -->
                                                @if ($role && $role->name === 'OWNER')
                                                    <li class="nav-item">
                                                        <a href="/companies/permissions/{{ $company->id }}?permission=Read User permission&idcp={{ $company->id }}"
                                                            class="nav-link">Permissions</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="/companies/pendingMember/{{ $company->id }}?permission=Read Pending Company User&idcp={{ $company->id }}"
                                                            class="nav-link">Pending Member</a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div> 
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                document.querySelector('.menu-link[href="#sidebarCompanies"]').addEventListener('click', function(
                                event) {
                                    event.preventDefault(); // Mencegah dropdown muncul
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'No Company Linked',
                                        text: 'You are not linked to any company.',
                                    });
                                });
                            });
                        </script>
                    @endif

                </li>
                <!-- end Dashboard Menu -->

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
