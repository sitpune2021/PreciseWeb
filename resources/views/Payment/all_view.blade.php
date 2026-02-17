 @extends('layouts.header')
 @section('content')

 <div class="main-content">

     <div class="page-content">
         <div class="container-fluid">

             <div class="row">
                 <div class="col-lg-12">
                     <div class="card">
                         <div class="card-header">
                             <h5 class="card-title mb-0">Subscription Plans List</h5>
                         </div>
                         <div class="card-body">
                             <div class="table-responsive">
                                 <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                     <thead class="table-light">
                                         <tr>
                                             <th>#</th>
                                             <th>Customer Name</th>
                                             <th>Customer Email</th>
                                             <th>plan type</th>
                                             <th>Razorpay Order ID</th>
                                             <th>Razorpay Payment ID</th>
                                             <th>Amount (₹)</th>
                                             <th>Payment Status</th>
                                             <th>Plan Status</th>
                                             <th>Date</th>
                                         </tr>
                                     </thead>
                                     <tbody>
                                         @forelse($subscription as $key => $pay)
                                         <tr>
                                             <td>{{ $key + 1 }}</td>
                                             <td>{{ $pay->user->name ?? 'N/A' }}</td>
                                             <td>{{ $pay->user->email ?? 'N/A' }}</td>
                                             <td>{{ $pay->plan->title ?? 'N/A' }}</td>
                                             <td>{{ $pay->razorpay_order_id ?? '-' }}</td>
                                             <td>{{ $pay->razorpay_payment_id ?? '-' }}</td>

                                             <td>{{ $pay->amount }}</td>

                                             <td>
                                                 <span class="badge bg-{{ $pay->payment_status == 'completed' ? 'success' : ($pay->payment_status == 'failed' ? 'danger' : 'secondary') }}">
                                                     {{ ucfirst($pay->payment_status) }}
                                                 </span>
                                             </td>

                                             <td>
                                                 <span class="badge bg-{{ $pay->plan_status == '1' ? 'success' : 'secondary' }}">
                                                     {{ $pay->plan_status == '1' ? 'Active' : 'Inactive' }}
                                                 </span>
                                             </td>

                                             <td>{{ \Carbon\Carbon::parse($pay->created_at)->format('d M Y') }}</td>
                                         </tr>
                                         @empty
                                         <tr>
                                             <td colspan="10" class="text-center text-muted">No payments found.</td>
                                         </tr>
                                         @endforelse
                                     </tbody>
                                 </table>
                             </div>
                         </div>
                     </div>
                 </div>
             </div><!--end row-->

         </div>
     </div>
 </div>




 @endsection