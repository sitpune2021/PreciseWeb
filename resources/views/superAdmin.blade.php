@php
use App\Models\Client;
use App\Models\Project;

use App\Models\Operator;
use App\Models\Machine;
use App\Models\Setting;
use App\Models\Hsncode;
use App\Models\MaterialType;
use App\Models\FinancialYear;
use App\Models\Vendor;
use App\Models\WorkOrder;
use App\Models\MachineRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


// ---------------- Clients ----------------

$totalClients = Client::count();
$totalClientsPercentage = $totalClients;

// Active Clients
$activeClients = Client::where('status', 1)->count();
$activeClientsPercentage = $activeClients; // count = percentage

// Current Month Clients
$currentMonthClients = Client::whereMonth('created_at', Carbon::now()->month)
->whereYear('created_at', Carbon::now()->year)
->count();
$currentMonthPercentage = $currentMonthClients; // count = percentage


// Renewal Clients
$renewalsThisMonth = Client::whereYear('updated_at', Carbon::now()->year)
->whereMonth('updated_at', Carbon::now()->month)
->count();
$renewalPercentage = $renewalsThisMonth; // count = percentage

// ---------------- Projects ----------------
$projects = Project::with('customer')
->where('admin_id', Auth::id())
->orderBy('id', 'desc')
->take(5)
->get();

$totalProjects = Project::where('admin_id', Auth::id())->count();

// ---------------- Operators ----------------
$totalOperators = Operator::where('admin_id', Auth::id())->count();
$activeOperators = Operator::where('admin_id', Auth::id())->where('status', 1)->count();
$latestOperators = Operator::where('admin_id', Auth::id())->orderBy('created_at', 'desc')->take(5)->get();

// ---------------- Machines ----------------
$totalMachines = Machine::where('admin_id', Auth::id())->count();
$activeMachines = Machine::where('admin_id', Auth::id())->where('status', 1)->count();
$latestMachines = Machine::where('admin_id', Auth::id())->orderBy('created_at', 'desc')->take(5)->get();

// ---------------- Settings ----------------
$totalSettings = Setting::where('admin_id', Auth::id())->count();
$activeSettings = Setting::where('admin_id', Auth::id())->where('status', 1)->count();
$latestSettings = Setting::where('admin_id', Auth::id())->orderBy('created_at', 'desc')->take(5)->get();

// ---------------- HSN Codes ----------------
$totalHsn = Hsncode::where('admin_id', Auth::id())->count();
$activeHsn = Hsncode::where('admin_id', Auth::id())->where('status', 1)->count();
$latestHsn = Hsncode::where('admin_id', Auth::id())->orderBy('created_at', 'desc')->take(5)->get();

// ---------------- Material Types ----------------
$totalMaterialTypes = MaterialType::where('admin_id', Auth::id())->count();
$latestMaterialTypes = MaterialType::where('admin_id', Auth::id())->orderBy('created_at', 'desc')->take(5)->get();

// ---------------- Financial Years ----------------
$totalYears = FinancialYear::where('admin_id', Auth::id())->count();
$activeYears = FinancialYear::where('admin_id', Auth::id())->where('status', 1)->count();
$latestYears = FinancialYear::where('admin_id', Auth::id())->orderBy('created_at', 'desc')->take(5)->get();

// ---------------- Vendors ----------------
$totalVendors = Vendor::where('admin_id', Auth::id())->count();
$activeVendors = Vendor::where('admin_id', Auth::id())->where('status', 'Active')->count();
$inactiveVendors = Vendor::where('admin_id', Auth::id())->where('status', 'Inactive')->count();
$newThisMonthVendors = Vendor::where('admin_id', Auth::id())
->whereMonth('created_at', Carbon::now()->month)
->whereYear('created_at', Carbon::now()->year)
->count();
$latestVendors = Vendor::where('admin_id', Auth::id())->orderBy('created_at', 'desc')->take(5)->get();

// ---------------- Work Orders ----------------
$workOrdersMonthly = WorkOrder::select(
DB::raw('COUNT(id) as total'),
DB::raw('MONTH(date) as month')
)
->where('admin_id', Auth::id())
->whereYear('date', Carbon::now()->year)
->groupBy('month')
->pluck('total', 'month');

$months = [];
$totals = [];
for ($m = 1; $m <= 12; $m++) {
    $months[]=Carbon::create()->month($m)->format('M'); // Jan, Feb...
    $totals[] = $workOrdersMonthly[$m] ?? 0;
    }

    // 1. Total Work Orders
    $totalWorkOrders = WorkOrder::where('admin_id', Auth::id())->count();

    $completed = WorkOrder::where('admin_id', Auth::id())
    ->withCount('invoiceItems')
    ->having('invoice_items_count', '>', 0)
    ->count();

    $inProgress = WorkOrder::where('admin_id', Auth::id())
    ->withCount('invoiceItems')
    ->having('invoice_items_count', '=', 0)
    ->count();


    $newWorkOrders = WorkOrder::where('admin_id', Auth::id())
    ->whereMonth('created_at', Carbon::now()->month)
    ->whereYear('created_at', Carbon::now()->year)
    ->count();
    // For Work Orders table (limit 5)
    $latestWorkOrders = WorkOrder::where('admin_id', Auth::id())
    ->latest()
    ->take(5)
    ->get();


    // ---------------- Machine Records ----------------
    $totalMachineRecords = MachineRecord::where('admin_id', Auth::id())->count();
    $newThisMonthMachineRecords = MachineRecord::where('admin_id', Auth::id())
    ->whereMonth('created_at', Carbon::now()->month)
    ->whereYear('created_at', Carbon::now()->year)
    ->count();
    $lastMonthMachineRecords = MachineRecord::where('admin_id', Auth::id())
    ->whereMonth('created_at', Carbon::now()->subMonth()->month)
    ->whereYear('created_at', Carbon::now()->subMonth()->year)
    ->count();
    $thisYearMachineRecords = MachineRecord::where('admin_id', Auth::id())
    ->whereYear('created_at', Carbon::now()->year)
    ->count();
    $todayMachineRecords = MachineRecord::where('admin_id', Auth::id())
    ->whereDate('created_at', Carbon::today())
    ->count();
    $latestMachineRecords = MachineRecord::where('admin_id', Auth::id())
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get();

    @endphp

    @extends('layouts.header')
    @section('content')

    <style>
        .newCard {
            height: 200px;
        }

        .newText {
            font-size: 20px;
            margin-top: 27px;
        }

        .newJob {
            margin-top: 35px;
        }
    </style>

    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                            <h4 class="mb-sm-0"> Dashboard</h4>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                    <li class="breadcrumb-item active"> Dashboard</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                @if(auth()->user()->user_type == 1)
                <div class="row">
                    <div class="col-xl-4 col-md-4">
                        <div class="card card-animate">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-uppercase text-muted mb-2" style="font-size:15px;">Total Clients</p>
                                    <h4>{{ $totalClients }}</h4>
                                </div>
                                <div id="total_clients_chart" data-percentage="{{ $totalClientsPercentage }}"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Clients -->
                    <div class="col-xl-4 col-md-4">
                        <div class="card card-animate">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-uppercase text-muted mb-2" style="font-size:15px;">Active Clients</p>
                                    <h4>{{ $activeClients }}</h4>
                                </div>
                                <div id="active_clients_chart" data-percentage="{{ $activeClientsPercentage }}"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Month -->
                    <div class="col-xl-4 col-md-4">
                        <div class="card card-animate">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-uppercase text-muted mb-2" style="font-size:15px;">New Client (Current Month)</p>
                                    <h4>{{ $currentMonthClients }}</h4>
                                </div>
                                <div id="current_month_chart" data-percentage="{{ $currentMonthPercentage }}"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Renewal -->
                    <div class="col-xl-4 col-md-4">
                        <div class="card card-animate">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-uppercase text-muted mb-2" style="font-size:15px;">Upcoming Renewal</p>
                                    <h4>{{ $renewalsThisMonth }}</h4>
                                </div>
                                <div id="renewal_chart" data-percentage="{{ $renewalPercentage }}"></div>
                            </div>
                        </div>
                    </div>

                </div>
                @endif

                @if(auth()->user()->user_type == 2 || auth()->user()->user_type==3 || auth()->user()->user_type==4 || auth()->user()->user_type==5 )

                <!--------------------- project page ---------------------------------->
                @if(hasPermission('Dashboard', 'view_project'))
                <div class="col-xl-12">
                    <div class="card shadow-lg border-0  h-100">
                        <div class="card-header d-flex justify-content-between align-items-center bg-gradient bg-light border-0 rounded-top-4">
                            <h4 class="card-title mb-0">
                                <i class="ri-briefcase-4-line me-2 text-primary"></i> Latest Projects
                            </h4>
                            <div class="flex-shrink-0">
                                <a href="{{ route('AddProject') }}"
                                    class="btn btn-sm btn-primary rounded-pill me-2 shadow-sm btn-animate">
                                    <i class="ri-add-line"></i> Add New
                                </a>
                                <a href="{{ route('ViewProject') }}"
                                    class="btn btn-sm btn-success rounded-pill shadow-sm btn-animate">
                                    View All <i class="ri-arrow-right-line ms-1"></i>
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle table-hover table-nowrap mb-0">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>SR NO.</th>
                                            <th>Date</th>
                                            <th>Project No.</th>
                                            <th>Code</th>
                                            <th>Project</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($projects as $project)
                                        <tr class="align-middle text-center">
                                            <td class="text-muted">{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="badge text-dark">
                                                    {{ \Carbon\Carbon::parse($project->date)->format('d M, Y') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-primary">{{ $project->project_no }}</span>

                                            </td>
                                            <td>
                                                <span class="">
                                                    {{ $project->customer?->code ?? '—' }}
                                                </span>
                                            </td>
                                            <td class="text-start">
                                                <div>
                                                    <h6 class="mb-0">{{ $project->project_name }}</h6>
                                                    <small class="text-muted">{{ $project->description ?? '' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge text-dark">{{ $project->quantity }}</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No projects found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">
                                    Showing <span class="fw-semibold">{{ $projects->count() }}</span> of <span class="fw-semibold">{{ $totalProjects }}</span> Results
                                </small>
                                <a href="{{ route('ViewProject') }}" class="btn btn-link btn-sm">View More</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <!-- project end -->
                <!-- ---------------- Work Orders Table -------------- -->
                @if(hasPermission('Dashboard', 'view_work_orders'))
                <div class="col-xl-12 mt-4">
                    <div class="card shadow-lg border-0  h-100">
                        <div class="card-header d-flex justify-content-between align-items-center bg-gradient bg-light border-0 rounded-top-4">
                            <h4 class="card-title mb-0">
                                <i class="ri-file-list-3-line me-2 text-primary"></i> Latest Work Orders
                            </h4>
                            <div class="flex-shrink-0">
                                <a href="{{ route('AddWorkOrder') }}"
                                    class="btn btn-sm btn-primary rounded-pill me-2 shadow-sm btn-animate">
                                    <i class="ri-add-line"></i> Add New
                                </a>
                                <a href="{{ route('ViewWorkOrder') }}"
                                    class="btn btn-sm btn-success rounded-pill shadow-sm btn-animate">
                                    View All <i class="ri-arrow-right-line ms-1"></i>
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle table-hover table-nowrap mb-0">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>SR NO.</th>
                                            <th>Date</th>
                                            <th>WO No.</th>
                                            <th>Customer</th>
                                            <th>Part Name</th>
                                            <th>Qty</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse($latestWorkOrders as $work)
                                        <tr class="align-middle text-center">
                                            <td class="text-muted">{{ $loop->iteration }}</td>

                                            <td>
                                                <span class="badge text-dark">
                                                    {{ \Carbon\Carbon::parse($work->date)->format('d M, Y') }}
                                                </span>
                                            </td>

                                            <td>{{ $work->project?->project_no ?? 'N/A' }}</td>


                                            <td>
                                                {{ $work->customer?->code ?? '—' }}
                                            </td>

                                            <td class="text-start">
                                                <h6 class="mb-0">{{ $work->part_name }}</h6>
                                                <small class="text-muted">{{ $work->part_description ?? '' }}</small>
                                            </td>

                                            <td>
                                                <span class="badge text-dark">{{ $work->quantity }}</span>
                                            </td>

                                            <td>
                                                @if($work->invoices->count() > 0)
                                                <span class="badge bg-success">Completed</span>
                                                @else
                                                <span class="badge bg-warning text-dark">In Progress</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No work orders found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>

                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <small class="text-muted">
                                    Showing <span class="fw-semibold">{{ $latestWorkOrders->count() }}</span> of
                                    <span class="fw-semibold">{{ $totalWorkOrders }}</span> Results
                                </small>

                                <a href="{{ route('ViewWorkOrder') }}" class="btn btn-link btn-sm">View More</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- ====== Latest Machine Records ====== -->
                @if(hasPermission('Dashboard', 'view_machinerecord'))
                <div class="col-xxl-8">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Latest Machine Records</h4>
                            <div class="flex-shrink-0">
                                <a href="{{ route('AddMachinerecord') }}"
                                    class="btn btn-sm btn-primary rounded-pill me-2 shadow-sm btn-animate">
                                    <i class="ri-add-line"></i> Add New
                                </a>
                                <a href="{{ route('ViewMachinerecord') }}"
                                    class="btn btn-sm btn-success rounded-pill shadow-sm btn-animate">
                                    View All <i class="ri-arrow-right-line ms-1"></i>
                                </a>
                            </div>

                        </div>

                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                    <thead class="text-muted table-light">
                                        <tr>
                                            <th scope="col">SR NO.</th>
                                            <th scope="col">Part No</th>
                                            <th scope="col">Work Order</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">Start Time</th>
                                            <th scope="col">End Time</th>
                                            <th scope="col">Total Run (Hrs)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($latestMachineRecords as $rec)
                                        <tr>
                                            <td>
                                                <a href="#!" class="fw-medium link-primary">{{ $rec->id }}</a>
                                            </td>
                                            <td class="fw-bold">{{ $rec->part_no }}</td>
                                            <td>{{ $rec->work_order }}</td>

                                            <td>
                                                {{ $rec->start_time ? \Carbon\Carbon::parse($rec->start_time)->format('d M Y') : '-' }}
                                            </td>

                                            <td>
                                                {{ $rec->start_time ? \Carbon\Carbon::parse($rec->start_time)->format('h:i A') : '-' }}
                                            </td>

                                            <td>
                                                {{ $rec->end_time ? \Carbon\Carbon::parse($rec->end_time)->format('h:i A') : '-' }}
                                            </td>

                                            <td>
                                                @if($rec->start_time && $rec->end_time)
                                                @php
                                                $diffInMinutes = \Carbon\Carbon::parse($rec->start_time)
                                                ->diffInMinutes(\Carbon\Carbon::parse($rec->end_time));
                                                $hours = number_format($diffInMinutes / 60, 2);
                                                @endphp
                                                <span class="text-success">
                                                    {{ $hours }} hr
                                                </span>
                                                @else
                                                <span class="text-muted">Running...</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No Machine Records Found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endif
            </div>
            <br><br>
            <!-- End Page-content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Design & Develop by Sit
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- @if(session('plan_alert'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
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
        </script>
        @endif -->
        @endsection