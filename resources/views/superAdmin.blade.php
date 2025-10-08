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
use App\Models\SetupSheet;
use App\Models\MachineRecord;
use App\Models\MaterialReq;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


// ---------------- Clients ----------------

$totalClients = Client::count();
$totalClientsPercentage = $totalClients; // count = percentage

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

    $newWorkOrders = WorkOrder::where('admin_id', Auth::id())->where('status', 'new')->count();
    $inProgress = WorkOrder::where('admin_id', Auth::id())->where('status', 'in_progress')->count();
    $completed = WorkOrder::where('admin_id', Auth::id())->where('status', 'completed')->count();
    $totalWorkOrders = WorkOrder::where('admin_id', Auth::id())->count();

    // ---------------- Setup Sheets ----------------
    $totalSheets = SetupSheet::where('admin_id', Auth::id())->count();
    $newThisMonthSheets = SetupSheet::where('admin_id', Auth::id())
    ->whereMonth('date', Carbon::now()->month)
    ->whereYear('date', Carbon::now()->year)
    ->count();
    $latestSheets = SetupSheet::where('admin_id', Auth::id())
    ->orderBy('date', 'desc')
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

    // ---------------- Material Requirements ----------------
    $latestMaterialReq = MaterialReq::with('customer', 'materialtype')
    ->where('admin_id', Auth::id())
    ->orderBy('created_at', 'desc')
    ->take(5)
    ->get();
    $totalMaterialReq = MaterialReq::where('admin_id', Auth::id())->count();

 



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
                @if(auth()->user()->user_type == 2)


                <!--------------------- project page ---------------------------------->
             <div class="col-xl-12">
    <div class="card shadow-lg border-0 rounded-4 h-100">
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
                                <span class="fw-bold text-primary">{{ $project->id }}</span>
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

                <!-- project end -->



                <!-- --------------Work Orders --------------->

                <div class="row mb-4">
                    <!-- Left Side: Chart -->
                    <div class="col-xxl-8 col-lg-7">
                        <div class="card card-height-100">
                            <div class="card-header border-0 d-flex align-items-center">
                                <h4 class="card-title mb-0 flex-grow-1">Work Orders </h4>
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

                            <!-- Counters Row -->
                            <div class="card-header p-0 border-0 bg-light-subtle">
                                <div class="row g-0 text-center">
                                    <div class="col-6 col-sm-3 border border-dashed border-start-0">
                                        <div class="p-3">
                                            <h5 class="mb-1">
                                                <span class="counter-value" data-target="{{ $newWorkOrders }}">0</span>
                                            </h5>
                                            <p class="text-muted mb-0">New Work Orders</p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-3 border border-dashed border-start-0">
                                        <div class="p-3">
                                            <h5 class="mb-1">
                                                <span class="counter-value" data-target="{{ $inProgress }}">0</span>
                                            </h5>
                                            <p class="text-muted mb-0">In Progress</p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-3 border border-dashed border-start-0">
                                        <div class="p-3">
                                            <h5 class="mb-1">
                                                <span class="counter-value" data-target="{{ $completed }}">0</span>
                                            </h5>
                                            <p class="text-muted mb-0">Completed</p>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-3 border border-dashed border-start-0 border-end-0">
                                        <div class="p-3">
                                            <h5 class="mb-1 text-success">
                                                <span class="counter-value" data-target="{{ $totalWorkOrders }}">0</span>
                                            </h5>
                                            <p class="text-muted mb-0">Total Work Orders</p>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Chart -->
                            <!-- <div class="card-body p-0 pb-2">
                                <div class="w-100">
                                    <div id="workOrderChart" class="apex-charts" style="height: 350px;" dir="ltr"></div>
                                </div>
                            </div> -->
                        </div>
                    </div>

                    <!-------------- Right Side: Setup Sheets ------------->

                    <div class="col-xxl-4 col-lg-5 mt-3 mt-lg-0">
                        <div class="card shadow-lg border-0 rounded-4 h-100">
                            <div class="card-header bg-light border-0 rounded-top-4 d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 d-flex align-items-center">
                                    <i class="ri-history-line me-2 text-warning"></i> Latest Setup Sheets
                                </h5>

                                <div class="flex-shrink-0">
                                    <a href="{{ route('AddSetupSheet') }}"
                                        class="btn btn-sm btn-primary rounded-pill me-2 shadow-sm btn-animate">
                                        <i class="ri-add-line"></i> Add New
                                    </a>
                                    <a href="{{ route('ViewSetupSheet') }}"
                                        class="btn btn-sm btn-success rounded-pill shadow-sm btn-animate">
                                        View All <i class="ri-arrow-right-line ms-1"></i>
                                    </a>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row g-3">
                                    @forelse($latestSheets as $sheet)
                                    <div class="col-12">
                                        <div class="border rounded-3 shadow-sm p-3 h-100 d-flex">
                                            <div class="me-3">
                                                @if($sheet->setup_image)
                                                <img src="{{ asset('setup_images/'.$sheet->setup_image) }}"
                                                    alt="Setup Image"
                                                    class="rounded border shadow-sm"
                                                    style="width:70px; height:70px; object-fit:cover;">
                                                @else
                                                <div class="d-flex align-items-center justify-content-center bg-light rounded border"
                                                    style="width:70px; height:70px;">
                                                    <i class="ri-image-line text-muted fs-3"></i>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 text-primary fw-bold">{{ $sheet->part_code }}</h6>
                                                <p class="mb-1 small text-muted">
                                                    <i class="ri-barcode-line me-1"></i> {{ $sheet->work_order_no }}
                                                </p>
                                                <p class="mb-1 small">
                                                    <i class="ri-calendar-line me-1"></i>
                                                    {{ \Carbon\Carbon::parse($sheet->date)->format('d M Y') }}
                                                </p>
                                                <p class="mb-0 small">
                                                    <span class="badge bg-info text-dark">
                                                        {{ $sheet->size_in_x }} × {{ $sheet->size_in_y }} × {{ $sheet->size_in_z }}
                                                    </span>
                                                    <span class="badge bg-success">{{ $sheet->setting }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="col-12 text-center py-4">
                                        <i class="ri-file-warning-line text-muted fs-3 d-block"></i>
                                        <span class="text-muted">No Setup Sheets Found</span>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <!-- ====== Latest Machine Records (Big Table) ====== -->
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
                                                <td><a href="#!" class="fw-medium link-primary">{{ $rec->id }}</a></td>
                                                <td class="fw-bold">{{ $rec->part_no }}</td>
                                                <td>{{ $rec->work_order }}</td>
                                                <td>{{ \Carbon\Carbon::parse($rec->date)->format('d M Y') }}</td>
                                                <td>
                                                    {{ $rec->start_time ? \Carbon\Carbon::parse($rec->start_time)->format('H:i A') : '-' }}
                                                </td>
                                                <td>
                                                    {{ $rec->end_time ? \Carbon\Carbon::parse($rec->end_time)->format('H:i A') : '-' }}
                                                </td>
                                                <td>
                                                    @if($rec->start_time && $rec->end_time)
                                                    @php
                                                    $hours = \Carbon\Carbon::parse($rec->start_time)->diffInHours(\Carbon\Carbon::parse($rec->end_time));

                                                    @endphp
                                                    <span class="badge bg-success">
                                                        {{ $hours }}h
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

                    <!-- ========== Latest Material Requirements ========== -->
                    <div class="row mt-4">
                        <div class="col-xxl-6">
                            <div class="card shadow-lg border-0 rounded-4 h-100">
                                <div class="card-header d-flex justify-content-between align-items-center 
                         text-white border-0 rounded-top-4 py-3 px-4">
                                    <h4 class="card-title mb-0 d-flex align-items-center">
                                        <i class="ri-cube-line me-2"></i> Latest Material Requirements
                                    </h4>
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('AddMaterialReq') }}"
                                            class="btn btn-sm btn-primary rounded-pill me-2 shadow-sm btn-animate">
                                            <i class="ri-add-line"></i> Add New
                                        </a>
                                        <a href="{{ route('ViewMaterialReq') }}"
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
                                                    
                                                    <th>Code</th>
                                                    <th>Material</th>
                                                    <th>Qty</th>
                                                    <th>Total Cost</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($latestMaterialReq as $req)
                                                <tr class="align-middle text-center">
                                                    <td class="text-muted">{{ $loop->iteration }}</td>
                                                    
                                                    <td><span  >{{ $req->code }}</span></td>
                                                    <td>{{ $req->materialtype->material_type ?? '-' }}</td>
                                                    <td><span  >{{ $req->qty }}</span></td>
                                                    <td class="fw-bold text-success">
                                                        ₹ {{ number_format($req->total_cost,2) }}
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted py-4">
                                                        <i class="ri-file-warning-line fs-3 d-block mb-2"></i>
                                                        No Material Requirements Found
                                                    </td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <small class="text-muted">
                                            Showing <span class="fw-semibold">{{ $latestMaterialReq->count() }}</span> of
                                            <span class="fw-semibold">{{ $totalMaterialReq }}</span> Material Requirements
                                        </small>
                                        <a href="{{ route('ViewMaterialReq') }}" class="btn btn-link btn-sm">View More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

 

                    @endif

                </div>

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
                                Design & Develop by
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>




        @endsection