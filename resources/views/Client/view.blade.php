@extends('layouts.header')
@section('content')

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">View Clients</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sr.No</th>
                                            <th>Client Name</th>
                                            <th>Email Address</th>
                                            <th>Phone No.</th>
                                            <!-- <th>GST No.</th> -->
                                            <!-- <th>Address</th> -->
                                            <!-- <th>Client Logo</th> -->
                                            <th>Status</th>
                                            <th width="12%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($client as $c)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $c->name }}</td>
                                            <td>{{ $c->email_id }}</td>
                                            <td>{{ $c->phone_no }}</td>
                                            <!-- <td>{{ $c->gst_no }}</td> -->
                                            <!-- <td>{{ $c->address }}</td> -->

                                            <!-- <td>
                                                @if($c->logo)
                                                <img src="{{ asset($c->logo) }}" alt="Client Logo" width="50" height="50">
                                                @else
                                                No Logo
                                                @endif
                                            </td> -->
                                            <td>
                                                <form action="{{ route('updateClientStatus') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $c->id }}">
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            role="switch"
                                                            id="statusNameSwitch{{ $c->id }}"
                                                            name="status"
                                                            value="1"
                                                            onchange="this.form.submit()"
                                                            {{ $c->status == '1' ? 'checked' : '' }}>
                                                    </div>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="{{route('editClient', base64_encode($c->id))}}">
                                                    <button type="button" class="btn btn-success btn-icon waves-effect waves-light">
                                                        <i class="ri-pencil-fill align-bottom"></i>
                                                    </button>
                                                </a>

                                                <button type="button" class="btn btn-primary btn-icon waves-effect waves-light viewClient"
                                                    data-client='@json($c)'>
                                                    <i class="ri-eye-fill align-bottom"></i>
                                                </button>

                                                <a href="{{route('deleteClient', base64_encode($c->id))}}"
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                    <button type="button" class="btn btn-danger btn-icon waves-effect waves-light">
                                                        <i class="ri-delete-bin-fill align-bottom"></i>
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
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

<div class="modal fade" id="viewClientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Client Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Client Name</th>
                            <td id="vc_name"></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td id="vc_email"></td>
                        </tr>
                        <tr>
                            <th>Phone No.</th>
                            <td id="vc_phone"></td>
                        </tr>

                        <tr>
                            <th>GST Number</th>
                            <td id="vc_gst"></td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td id="vc_status"></td>
                        </tr>
                        <tr>
                            <th>Client Logo</th>
                            <td id="vc_logo"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('.viewClient').forEach(function(btn) {

            btn.addEventListener('click', function(e) {
                e.preventDefault();

                let data = JSON.parse(this.dataset.client);

                document.getElementById('vc_name').textContent = data.name ?? '';
                document.getElementById('vc_email').textContent = data.email_id ?? '';
                document.getElementById('vc_phone').textContent = data.phone_no ?? '';
                document.getElementById('vc_gst').textContent = data.gst_no ?? '';
                document.getElementById('vc_status').textContent = data.status == 1 ? 'Active' : 'Inactive';

                // Logo
                let logoTd = document.getElementById('vc_logo');
                logoTd.innerHTML = '';

                if (data.logo) {
                    let img = document.createElement('img');
                    img.src = "{{ asset('') }}" + data.logo;
                    img.width = 50;
                    img.height = 50;
                    logoTd.appendChild(img);
                } else {
                    logoTd.textContent = "No Logo";
                }

                // open modal
                let modal = new bootstrap.Modal(document.getElementById('viewClientModal'));
                modal.show();
            });

        });

    });
</script>

@endsection