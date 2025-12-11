<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>

    <meta charset="utf-8" />
    <title>PRECISE ENGINEERING</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/icon.ico')}}">

    <!-- jsvectormap css -->
    <link href="{{asset('assets/libs/jsvectormap/jsvectormap.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- gridjs css -->
    <link rel="stylesheet" href="{{asset('assets/libs/gridjs/theme/mermaid.min.css')}}">


    <!--datatable css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

    <!-- Layout config Js -->
    <script src="{{asset('assets/js/layout.js')}}"></script>
    <!--machin record Ajax -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Bootstrap Css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{asset('assets/css/custom.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* Make Select2 look like a normal input */
        .select2-container--default .select2-selection--single {
            height: 36px;
            padding: 6px 12px;
            border: 1px solid #ced4da;
            border-radius: 6px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
            right: 10px;
        }
    </style>
</head>





<body>

    <header id="page-topbar">
        <div class="layout-width">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box horizontal-logo">
                        <a href="index.html" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="{{asset('assets/images/logo-sm.png')}}" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="{{asset('assets/images/logo-dark.png')}}" alt="" height="17">
                            </span>
                        </a>

                        <a href="index.html" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="{{asset('assets/images/logo-sm.png')}}" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="{{asset('assets/images/logo-light.png')}}" alt="" height="17">
                            </span>
                        </a>
                    </div>

                    <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger material-shadow-none" id="topnav-hamburger-icon">
                        <span class="hamburger-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>


                </div>

                <div class="d-flex align-items-center">

                    <div class="dropdown d-md-none topbar-head-dropdown header-item">
                        <button type="button" class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-search fs-22"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">
                            <form class="p-3">
                                <div class="form-group m-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                        <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="ms-1 header-item d-none d-sm-flex">
                        <button type="button" class="btn btn-icon btn-topbar material-shadow-none btn-ghost-secondary rounded-circle" data-toggle="fullscreen">
                            <i class='bx bx-fullscreen fs-22'></i>
                        </button>
                    </div>

                    <div class="dropdown ms-sm-3 header-item topbar-user">
                        <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="d-flex align-items-center">
                                <img class="rounded-circle header-profile-user" src="{{ asset('assets/images/users/avatar-1.jpg') }}" alt="Header Avatar">

                                <span class="text-start ms-xl-2">
                                    <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">
                                        {{ auth()->user()->name }}
                                    </span>

                                    <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">
                                        {{
                                        auth()->user()->user_type == 1 ? 'Super Admin' :
                                        (auth()->user()->user_type == 2 ? 'Admin' :
                                        (auth()->user()->user_type == 3 ? 'Programmer' :
                                        (auth()->user()->user_type == 4 ? 'Supervisor' :
                                        (auth()->user()->user_type == 5 ? 'Finance' : 'Unknown'))))
                                        }}
                                    </span>


                                    {{-- RED plan indicator --}}
                                    @if (!in_array(auth()->user()->user_type, [1, 3, 4, 5]))
                                    @php
                                    $planColor = ( auth()->user()->plan_status == 1) ? 'text-success small' : 'text-red small';
                                    $planName = $clientData->plan->title ?? 'No Plan';
                                    @endphp
                                    <span class="{{ $planColor }} fw-bold ms-1">{{ $clientData->plan->title ?? 'No Plan' }} (Active)</span>
                                    @endif

                                </span>
                            </span>
                        </button>

                        <div class="dropdown-menu dropdown-menu-end">
                            <!-- User Info -->
                            <h6 class="dropdown-header">Welcome {{ auth()->user()->name }}</h6>

                            {{-- PLAN DETAILS --}}
                            @if (!in_array(auth()->user()->user_type, [1, 3, 4, 5]))
                            <div class="dropdown-item">
                                <strong>Plan:</strong> {{ $planName }} <br>
                                <strong>Expiry:</strong>
                                {{ \Carbon\Carbon::parse($clientData?->plan_expiry)->format('d M Y') ?? 'N/A' }}

                            </div>
                            @endif

                            <hr class="dropdown-divider">

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>

                            @if (!in_array(auth()->user()->user_type, [1, 3, 4, 5]))
                            <a class="dropdown-item" href="{{ route('Setting') }}">
                                <i class="ri-settings-3-line text-muted fs-16 align-middle me-1"></i>
                                <span class="align-middle">Settings</span>
                            </a>
                            @endif


                            {{-- RENEW PLAN BUTTON IF EXPIRED --}}
                            @if (!in_array(auth()->user()->user_type, [1, 3, 4, 5]))
                            <a class="dropdown-item {{ auth()->user()->plan_status == 0 ? 'text-danger' : '' }}"
                                href="{{ route('Payment') }}">
                                <i class="ri-refresh-line fs-16 align-middle me-1"></i>
                                <span class="align-middle">Renew Plan</span>
                            </a>
                            @endif

                            <a class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                                <span class="align-middle" data-key="t-logout">Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>


    <!-- removeNotificationModal -->
    <div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
                </div>
                <div class="modal-body">
                    <div class="mt-2 text-center">
                        <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                            <h4>Are you sure ?</h4>
                            <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                        <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete It!</button>
                    </div>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <!-- ========== App Menu ========== -->
    <div class="app-menu navbar-menu">
        <!-- LOGO -->
        <div class="navbar-brand-box">
            <!-- Dark Logo-->
            <a href="index.html" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="{{asset('assets/images/logo-sm.png')}}" alt="" height="22">
                </span>
                <span class="logo-lg">
                    <img src="{{asset('assets/images/logo-dark.png')}}" alt="" height="17">
                </span>
            </a>
            <!-- Light Logo-->
            <a href="index.html" class="logo logo-light">
                <span class="logo-sm">
                    <img src="{{asset('assets/images/precise.png')}}" alt="" height="22">
                </span>
                <span class="logo-lg">
                    <img src="{{asset('assets/images/pre2.png')}}" alt="" height="100">
                </span>
            </a>
            <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                <i class="ri-record-circle-line"></i>
            </button>
        </div>

        <div class="dropdown sidebar-user m-1 rounded">
            <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="d-flex align-items-center gap-2">
                    <img class="rounded header-profile-user" src="{{asset('assets/images/users/avatar-1.jpg')}}" alt="Header Avatar">
                    <span class="text-start">
                        <span class="d-block fw-medium sidebar-user-name-text">Anna Adame</span>
                        <span class="d-block fs-14 sidebar-user-name-sub-text"><i class="ri ri-circle-fill fs-10 text-success align-baseline"></i> <span class="align-middle">Online</span></span>
                    </span>
                </span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <!-- item-->
                <h6 class="dropdown-header">Welcome Anna!</h6>
                <a class="dropdown-item" href="pages-profile.html"><i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Profile</span></a>
                <a class="dropdown-item" href="apps-chat.html"><i class="mdi mdi-message-text-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Messages</span></a>
                <a class="dropdown-item" href="apps-tasks-kanban.html"><i class="mdi mdi-calendar-check-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Taskboard</span></a>
                <a class="dropdown-item" href="pages-faqs.html"><i class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Help</span></a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="pages-profile.html"><i class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Balance : <b>$5971.67</b></span></a>
                <a class="dropdown-item" href="pages-profile-settings.html"><span class="badge bg-success-subtle text-success mt-1 float-end">New</span><i class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Settings</span></a>
                <a class="dropdown-item" href="auth-lockscreen-basic.html"><i class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Lock screen</span></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

                <a class="dropdown-item"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                    <span class="align-middle" data-key="t-logout">Logout</span>
                </a>
            </div>
        </div>


        <div id="scrollbar">
            <div class="container-fluid">
                <div id="two-column-menu"></div>
                <ul class="navbar-nav" id="navbar-nav">
                    <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="ri-dashboard-2-line"></i>
                            <span data-key="t-dashboards">Dashboards</span>
                        </a>
                    </li>

                    <!-- Clients -->
                    @if(auth()->user()->user_type == 1)

                    <li class="menu-title"><i class="ri-user-3-line me-2 text-success"></i><span>Clients</span></li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('AddClient','ViewClient') ? '' : 'collapsed' }}" href="#sidebarClient" data-bs-toggle="collapse">
                            <i class="ri-user-star-line"></i> <span>Client Master</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->routeIs('AddClient','ViewClient') ? 'show' : '' }}" id="sidebarClient">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item"><a href="{{ route('AddClient') }}" class="nav-link {{ request()->routeIs('AddClient') ? 'active' : '' }}"><i class="ri-user-add-line me-1"></i> Add</a></li>
                                <li class="nav-item"><a href="{{ route('ViewClient') }}" class="nav-link {{ request()->routeIs('ViewClient') ? 'active' : '' }}"><i class="ri-eye-line me-1"></i> View</a></li>
                            </ul>
                        </div>
                    </li>
                    @endif

                    {{-- Masters Menu (Only if ANY master permission exists) --}}
                    @if(
                    hasPermission('Operator','add') ||
                    hasPermission('Machine','add') ||
                    hasPermission('Setting','add') ||
                    hasPermission('Hsncode','add') ||
                    hasPermission('MaterialType','add') ||
                    hasPermission('FinancialYear','add') ||
                    hasPermission('UserAdmin','add') ||
                    hasPermission('UserAdmin','view') ||
                    hasPermission('UserAdmin','edit')
                    )
                    <li class="menu-title"><i class="ri-settings-3-line me-2 text-warning"></i><span>Masters</span></li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('AddOperator','AddMachine','AddSetting','addHsn','AddMaterialType','AddFinancialYear','AddUserAdmin','ListUserAdmin','RolePermission') ? '' : 'collapsed' }}"
                            href="#sidebarMaster" data-bs-toggle="collapse">
                            <i class="ri-database-2-line"></i> <span>Master</span>
                        </a>

                        <div class="collapse menu-dropdown {{ request()->routeIs('AddOperator','AddMachine','AddSetting','addHsn','AddMaterialType','AddFinancialYear','AddUserAdmin','ListUserAdmin','RolePermission') ? 'show' : '' }}" id="sidebarMaster">

                            <ul class="nav nav-sm flex-column">

                                @if(hasPermission('Operator','add'))
                                <li><a href="{{ route('AddOperator') }}" class="nav-link {{ request()->routeIs('AddOperator') ? 'active':'' }}">Add Operator</a></li>
                                @endif

                                @if(hasPermission('Machine','add'))
                                <li><a href="{{ route('AddMachine') }}" class="nav-link {{ request()->routeIs('AddMachine') ? 'active':'' }}">Add Machine</a></li>
                                @endif

                                @if(hasPermission('Setting','add'))
                                <li><a href="{{ route('AddSetting') }}" class="nav-link {{ request()->routeIs('AddSetting') ? 'active':'' }}">Add Setting</a></li>
                                @endif

                                @if(hasPermission('Hsncode','add'))
                                <li><a href="{{ route('addHsn') }}" class="nav-link {{ request()->routeIs('addHsn') ? 'active':'' }}">Add Hsncode</a></li>
                                @endif

                                @if(hasPermission('MaterialType','add'))
                                <li><a href="{{ route('AddMaterialType') }}" class="nav-link {{ request()->routeIs('AddMaterialType') ? 'active':'' }}">Add Material Type</a></li>
                                @endif

                                @if(hasPermission('FinancialYear','add'))
                                <li><a href="{{ route('AddFinancialYear') }}" class="nav-link {{ request()->routeIs('AddFinancialYear') ? 'active':'' }}">Add Financial Year</a></li>
                                @endif


                                {{-- Role & Permission Menu  --}}
                                @if(hasPermission('UserAdmin','add') || hasPermission('UserAdmin','view') || hasPermission('UserAdmin','edit'))
                                <li class="nav-item">
                                    <a class="nav-link menu-link {{ request()->routeIs('AddUserAdmin','ListUserAdmin','RolePermission') ? '' : 'collapsed' }}"
                                        href="#sidebarUserAdmin" data-bs-toggle="collapse">
                                        <i class="ri-user-add-line me-1"></i> Role & Permission
                                    </a>

                                    <div class="collapse menu-dropdown {{ request()->routeIs('AddUserAdmin','ListUserAdmin','RolePermission') ? 'show':'' }}" id="sidebarUserAdmin">

                                        <ul class="nav nav-sm flex-column">

                                            @if(hasPermission('UserAdmin','add'))
                                            <li><a href="{{ route('AddUserAdmin') }}" class="nav-link {{ request()->routeIs('AddUserAdmin') ? 'active':'' }}">Create User</a></li>
                                            @endif

                                            @if(hasPermission('UserAdmin','view'))
                                            <li><a href="{{ route('ListUserAdmin') }}" class="nav-link {{ request()->routeIs('ListUserAdmin') ? 'active':'' }}">View Users</a></li>
                                            @endif

                                            @if(hasPermission('UserAdmin','edit'))
                                            <li><a href="{{ route('RolePermission') }}" class="nav-link {{ request()->routeIs('RolePermission') ? 'active':'' }}">Role & Permission</a></li>
                                            @endif

                                        </ul>
                                    </div>
                                </li>
                                @endif

                            </ul>
                        </div>
                    </li>
                    @endif


                    <!-- Customers -->
                    @if(hasPermission('Customer', 'view'))
                    <li class="menu-title">
                        <i class="ri-user-heart-line me-2 text-danger"></i><span>Customers</span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('AddCustomer','ViewCustomer') ? '' : 'collapsed' }}"
                            href="#sidebarCustomers" data-bs-toggle="collapse">
                            <i class="ri-team-line"></i> <span>Customer Master</span>
                        </a>

                        <div class="collapse menu-dropdown {{ request()->routeIs('AddCustomer','ViewCustomer') ? 'show' : '' }}"
                            id="sidebarCustomers">
                            <ul class="nav nav-sm flex-column">

                                @if(hasPermission('Customer', 'add'))
                                <li class="nav-item">
                                    <a href="{{ route('AddCustomer') }}" class="nav-link {{ request()->routeIs('AddCustomer') ? 'active' : '' }}">
                                        <i class="ri-user-add-line me-1"></i> Add
                                    </a>
                                </li>
                                @endif

                                @if(hasPermission('Customer', 'view'))
                                <li class="nav-item">
                                    <a href="{{ route('ViewCustomer') }}" class="nav-link {{ request()->routeIs('ViewCustomer') ? 'active' : '' }}">
                                        <i class="ri-eye-line me-1"></i> View
                                    </a>
                                </li>
                                @endif

                            </ul>
                        </div>
                    </li>
                    @endif

                    @if(hasPermission('Vendors', 'view'))
                    <!-- Vendors -->
                    <li class="menu-title"><i class="ri-store-2-line me-2 text-info"></i><span>Vendors</span></li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('AddVendor','ViewVendor') ? '' : 'collapsed' }}" href="#sidebarVendor" data-bs-toggle="collapse">
                            <i class="ri-truck-line"></i> <span>Vendor Master</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->routeIs('AddVendor','ViewVendor') ? 'show' : '' }}" id="sidebarVendor">
                            <ul class="nav nav-sm flex-column">
                                @if(hasPermission('Vendors', 'add'))
                                <li class="nav-item"><a href="{{ route('AddVendor') }}" class="nav-link {{ request()->routeIs('AddVendor') ? 'active' : '' }}"><i class="ri-add-circle-line me-1"></i> Add</a></li>
                                @endif
                                @if(hasPermission('Vendors', 'view'))
                                <li class="nav-item"><a href="{{ route('ViewVendor') }}" class="nav-link {{ request()->routeIs('ViewVendor') ? 'active' : '' }}"><i class="ri-eye-line me-1"></i> View</a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif


                    @if(hasPermission('Projects', 'view'))
                    <!-- Projects -->
                    <li class="menu-title"><i class="ri-briefcase-line me-2 text-purple"></i><span>Projects</span></li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('AddProject','ViewProject') ? '' : 'collapsed' }}" href="#sidebarProject" data-bs-toggle="collapse">
                            <i class="ri-task-line"></i> <span>Project Entry</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->routeIs('AddProject','ViewProject') ? 'show' : '' }}" id="sidebarProject">
                            <ul class="nav nav-sm flex-column">
                                @if(hasPermission('Projects', 'add'))
                                <li class="nav-item"><a href="{{ route('AddProject') }}" class="nav-link {{ request()->routeIs('AddProject') ? 'active' : '' }}"><i class="ri-add-circle-line me-1"></i> Add</a></li>
                                @endif
                                @if(hasPermission('Projects', 'view'))
                                <li class="nav-item"><a href="{{ route('ViewProject') }}" class="nav-link {{ request()->routeIs('ViewProject') ? 'active' : '' }}"><i class="ri-eye-line me-1"></i> View</a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif

                    <!-- Work Order -->
                    @if(hasPermission('WorkOrders', 'view'))
                    <li class="menu-title"><i class="ri-file-list-2-line me-2 text-secondary"></i><span>Work Orders</span></li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('AddWorkOrder','ViewWorkOrder') ? '' : 'collapsed' }}" href="#sidebarWorkOrder" data-bs-toggle="collapse">
                            <i class="ri-clipboard-line"></i> <span>Work Order Entry</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->routeIs('AddWorkOrder','ViewWorkOrder') ? 'show' : '' }}" id="sidebarWorkOrder">
                            <ul class="nav nav-sm flex-column">
                                @if(hasPermission('WorkOrders', 'add'))
                                <li class="nav-item"><a href="{{ route('AddWorkOrder') }}" class="nav-link {{ request()->routeIs('AddWorkOrder') ? 'active' : '' }}"><i class="ri-add-line me-1"></i> Add</a></li>
                                @endif
                                @if(hasPermission('WorkOrders', 'view'))
                                <li class="nav-item"><a href="{{ route('ViewWorkOrder') }}" class="nav-link {{ request()->routeIs('ViewWorkOrder') ? 'active' : '' }}"><i class="ri-eye-line me-1"></i> View</a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif

                    @if(hasPermission('SetupSheet', 'view'))
                    <!-- Setup Sheet -->
                    <li class="menu-title"><i class="ri-layout-4-line me-2 text-dark"></i><span>Setup Sheet</span></li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('AddSetupSheet','ViewSetupSheet') ? '' : 'collapsed' }}" href="#sidebarsetup" data-bs-toggle="collapse">
                            <i class="ri-file-settings-line"></i> <span>Setup Sheet</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->routeIs('AddSetupSheet','ViewSetupSheet') ? 'show' : '' }}" id="sidebarsetup">
                            <ul class="nav nav-sm flex-column">

                                @if(hasPermission('SetupSheet', 'add'))
                                <li class="nav-item"><a href="{{ route('AddSetupSheet') }}" class="nav-link {{ request()->routeIs('AddSetupSheet') ? 'active' : '' }}"><i class="ri-add-circle-line me-1"></i> Add</a></li>
                                @endif
                                @if(hasPermission('SetupSheet', 'view'))
                                <li class="nav-item"><a href="{{ route('ViewSetupSheet') }}" class="nav-link {{ request()->routeIs('ViewSetupSheet') ? 'active' : '' }}"><i class="ri-eye-line me-1"></i> View</a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif

                    @if(hasPermission('MachineRecord', 'view'))
                    <!-- Machine Record -->
                    <li class="menu-title"><i class="ri-cpu-line me-2 text-primary"></i><span>Machine Records</span></li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('AddMachinerecord','ViewMachinerecord') ? '' : 'collapsed' }}" href="#sidebarMrecord" data-bs-toggle="collapse">
                            <i class="ri-database-line"></i> <span>Machine Record</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->routeIs('AddMachinerecord','ViewMachinerecord') ? 'show' : '' }}" id="sidebarMrecord">
                            <ul class="nav nav-sm flex-column">

                                @if(hasPermission('MachineRecord', 'add'))
                                <li class="nav-item"><a href="{{ route('AddMachinerecord') }}" class="nav-link {{ request()->routeIs('AddMachinerecord') ? 'active' : '' }}"><i class="ri-add-line me-1"></i> Add</a></li>
                                @endif

                                @if(hasPermission('MachineRecord', 'view'))
                                <li class="nav-item"><a href="{{ route('ViewMachinerecord') }}" class="nav-link {{ request()->routeIs('ViewMachinerecord') ? 'active' : '' }}"><i class="ri-eye-line me-1"></i> View</a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif

                    @if(hasPermission('MaterialReq', 'view'))
                    <li class="menu-title"><i class="ri-cpu-line me-2 text-primary"></i><span>Material Requirement</span></li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('AddMaterialReq','ViewMaterialReq') ? '' : 'collapsed' }}"
                            href="#sidebarMaterialreq" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ request()->routeIs('AddMaterialReq','ViewMaterialReq') ? 'true' : 'false' }}"
                            aria-controls="sidebarMaterialreq">
                            <i class="ri-apps-2-line"></i> <span data-key="t-apps">Material Requirement</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->routeIs('AddMaterialReq','ViewMaterialReq') ? 'show' : '' }}" id="sidebarMaterialreq">
                            <ul class="nav nav-sm flex-column">

                                @if(hasPermission('MaterialReq', 'add'))
                                <li class="nav-item">
                                    <a href="{{ route('AddMaterialReq') }}" class="nav-link {{ request()->routeIs('AddMaterialReq') ? 'active' : '' }}"> Add</a>
                                </li>
                                @endif

                                @if(hasPermission('MaterialReq', 'view'))
                                <li class="nav-item">
                                    <a href="{{ route('ViewMaterialReq') }}" class="nav-link {{ request()->routeIs('ViewMaterialReq') ? 'active' : '' }}"> View</a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif

                    <!-- Material Order -->
                    @if(hasPermission('MaterialOrder', 'view'))
                    <li class="menu-title"><i class="ri-shopping-bag-3-line me-2 text-success"></i><span>Material Orders</span></li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('AddMaterialorder','ViewMaterialorder') ? '' : 'collapsed' }}" href="#sidebarMaterial" data-bs-toggle="collapse">
                            <i class="ri-shopping-cart-2-line"></i> <span>Material Order</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->routeIs('AddMaterialorder','ViewMaterialorder') ? 'show' : '' }}" id="sidebarMaterial">
                            <ul class="nav nav-sm flex-column">

                                @if(hasPermission('MaterialOrder', 'add'))
                                <li class="nav-item"><a href="{{ route('AddMaterialorder') }}" class="nav-link {{ request()->routeIs('AddMaterialorder') ? 'active' : '' }}"><i class="ri-add-line me-1"></i> Add</a></li>
                                @endif

                                @if(hasPermission('MaterialOrder', 'view'))
                                <li class="nav-item"><a href="{{ route('ViewMaterialorder') }}" class="nav-link {{ request()->routeIs('ViewMaterialorder') ? 'active' : '' }}"><i class="ri-eye-line me-1"></i> View</a></li>
                                @endif

                            </ul>
                        </div>
                    </li>
                    @endif

                    @if(hasPermission('Invoice', 'view'))
                    <!-- Invoice -->
                    <li class="menu-title">
                        <i class="ri-bill-line me-2 text-warning"></i>
                        <span>Invoices</span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link 
                     {{ request()->routeIs(
                'proforma.index','proforma.create','proforma.history','invoice.history' ) ? '' : 'collapsed' }}"
                            href="#sidebarInvoice"
                            data-bs-toggle="collapse">
                            <i class="ri-file-list-3-line"></i>
                            <span>Invoice</span>
                        </a>

                        <div class="collapse menu-dropdown 
                        {{ request()->routeIs(
                            'proforma.index','proforma.create', 'proforma.history','invoice.history'
                        ) ? 'show' : '' }}"
                            id="sidebarInvoice">

                            <ul class="nav nav-sm flex-column">

                                <!-- Proforma Invoice -->
                                <li class="nav-item">
                                    <a href="{{ route('proforma.index') }}"
                                        class="nav-link {{ request()->routeIs('proforma.index') ? 'active' : '' }}">
                                        <i class="ri-file-list-3-line me-1"></i> Proforma Invoice
                                    </a>
                                </li>

                                <!-- Tax Invoice -->
                                <!-- <li class="nav-item">
                                    <a href="{{ route('invoice.add') }}"
                                        class="nav-link {{ request()->routeIs('invoice.index') ? 'active' : '' }}">
                                        <i class="ri-file-list-3-line me-1"></i>Proforma Invoice
                                    </a>
                                </li> -->
                                <li class="nav-item">
                                    <a href="{{ route('invoice.view') }}"
                                        class="nav-link {{ request()->routeIs('invoice.view') ? 'active' : '' }}">
                                        <i class="ri-file-list-3-line me-1"></i>Invoice List
                                    </a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    @endif

                    @if(hasPermission('Subscription', 'view'))
                    <!-- Subscription plan  -->
                    <li class="menu-title">
                        <i class="ri-bank-card-2-line me-2 text-success"></i><span>Subscription Plan</span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('Payment','PaymentList') ? '' : 'collapsed' }}"
                            href="#sidebarPayment" data-bs-toggle="collapse">
                            <i class="ri-wallet-3-line"></i> <span>Subscription Plan</span>
                        </a>
                        <div class="collapse menu-dropdown {{ request()->routeIs('Payment','PaymentList') ? 'show' : '' }}" id="sidebarPayment">
                            <ul class="nav nav-sm flex-column">
                                @if(hasPermission('Subscription', 'view'))
                                <li class="nav-item">
                                    <a href="{{ route('Payment') }}"
                                        class="nav-link {{ request()->routeIs('Payment') ? 'active' : '' }}">
                                        <i class="ri-refresh-line me-1"></i> Subscription Plan
                                    </a>
                                </li>
                                @endif
                                @if(hasPermission('Subscription', 'view'))
                                <li class="nav-item">
                                    <a href="{{ route('PaymentList') }}"
                                        class="nav-link {{ request()->routeIs('PaymentList') ? 'active' : '' }}">
                                        <i class="ri-list-check me-1"></i> Payment History
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                    @endif
                    <br><br><br><br><br><br>

                </ul>
            </div>
        </div>
    </div>


    <div class="sidebar-background"></div>
    </div>
    <!-- Left Sidebar End -->
    <!-- Vertical Overlay-->
    <div class="vertical-overlay"></div>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    @yield('content')
    <!-- JAVASCRIPT -->
    <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('assets/libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
    <script src="{{asset('assets/js/plugins.js')}}"></script>

    @if(session('plan_alert'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- <script>
        Swal.fire({
            icon: 'warning', // warning icon for plan alerts
            title: 'Warning!',
            text: "{{ session('plan_alert') }}",
            width: 350,
            padding: '1rem',
            timer: 8000,
            timerProgressBar: true,
            showConfirmButton: true,
            customClass: {
                popup: 'swal2-popup-small'
            }
        });

      
    </script> -->
    @endif


    <!-- apexcharts -->
    <script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>

    <!-- Vector map-->
    <script src="{{asset('assets/libs/jsvectormap/jsvectormap.min.js')}}"></script>
    <script src="{{asset('assets/libs/jsvectormap/maps/world-merc.js')}}"></script>

    <!-- gridjs js -->
    <script src="{{asset('assets/libs/gridjs/gridjs.umd.js')}}"></script>

    <!-- Dashboard init -->
    <script src="{{asset('assets/js/pages/dashboard-job.init.js')}}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });
    </script>
    <script>
        $(document).ready(function() {

            // On select2 change
            $('#customer_id').on('select2:select', function(e) {
                let selectedOption = $(this).find(':selected');
                let code = selectedOption.data('code') || '';
                let id = selectedOption.data('id') || '';
                $('#code').val(code);
                $('#work_order_no').val(id)
                let details = selectedOption.data('details') || '';
                console.log(details);
                $('#description').val(details.description || '');
                $('#material').val(details.material || '');
                $('#quantity').val(details.qty || '');
                $('#f_diameter').val(details.dia || '');
                $('#f_length').val(details.length || '');
                $('#f_width').val(details.width || '');
                $('#f_height').val(details.height || '');
                $('#date').val(details.date || '');
                $('#r_length').val(details.length || '');
                $('#r_width').val(details.width || '');
                $('#r_height').val(details.r_height || '')
            });

            // Trigger on page load (edit mode)
            // if ($('#customer_id').val()) {
            //     let selectedOption = $('#customer_id').find(':selected');
            //     let code = selectedOption.data('code') || '';
            //     let id = selectedOption.data('id') || '';
            //     let details = selectedOption.data('details') || '';
            //     console.log(details);

            //     $('#code').val(code);
            //     $('#work_order_no').val(id)
            //     $('#description').val(details.description || '');
            //     $('#code').val(details.code || '');
            //     $('#work_order_no').val(details.work_order_no || '');
            //     $('#material').val(details.material || '');
            //     $('#quantity').val(details.qty || '');
            //     $('#f_diameter').val(details.f_diameter || '');
            //     $('#f_length').val(details.f_length || '');
            //     $('#f_width').val(details.f_width || '');
            //     $('#f_height').val(details.f_height || '');
            //     $('#r_diameter').val(details.r_diameter || '');
            //     $('#r_length').val(details.r_length || '');
            //     $('#r_width').val(details.r_width || '');
            //     $('#r_height').val(details.r_height || '')


            // }
        });
    </script>


    <!--datatable js-->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="{{asset('assets/js/pages/datatables.init.js')}}"></script>

    <!-- App js -->
    <script src="{{asset('assets/js/app.js')}}"></script>
</body>