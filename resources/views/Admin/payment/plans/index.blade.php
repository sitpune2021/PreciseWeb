@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Form Start -->
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        {{ isset($plan) ? 'Edit Plan' : 'Add Payment Plan' }}
                    </h5>
                </div>

                <div class="card-body">

                    @if(session('success'))
                    <div class="d-flex">
                        <div id="successAlert"
                            class="alert alert-success alert-dismissible fade show py-2 px-3 mb-2"
                            style="max-width:500px;">
                            {{ session('success') }}
                        </div>
                    </div>
                    @endif

                    <form action="{{ isset($plan) ? route('admin.plans.update',$plan->id) : route('admin.plans.store') }}" method="POST">
                        @csrf
                        @if(isset($plan))
                        @method('PUT')
                        @endif

                        <div class="row g-3">

                            <!-- Title -->
                            <div class="col-md-3">
                                <label class="form-label">Plan Title <span class="text-red">*</span></label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', $plan->title ?? '') }}"
                                    placeholder="Enter Plan Title">
                                <small class="text-red">
                                    @error('title') {{ $message }} @enderror
                                </small>
                            </div>

                            <!-- Price -->
                            <div class="col-md-2">
                                <label class="form-label">Price <span class="text-red">*</span></label>
                                <input type="number" step="0.01" name="price"
                                    class="form-control only-positive"
                                    value="{{ old('price', $plan->price ?? '') }}"
                                    placeholder="Enter Price">
                                <small class="text-red">
                                    @error('price') {{ $message }} @enderror
                                </small>
                            </div>

                            <!-- Days -->
                            <div class="col-md-2">
                                <label class="form-label">Days <span class="text-red">*</span></label>
                                <input type="number" name="days"
                                    class="form-control only-positive"
                                    value="{{ old('days', $plan->days ?? '') }}"
                                    placeholder="Plan Days">
                                <small class="text-red">
                                    @error('days') {{ $message }} @enderror
                                </small>
                            </div>
                            <div class="col-md-2">
                                <label>Short Text <span class="text-red">*</span></label>
                                <input type="text" name="short_text" placeholder="Short Text" class="form-control"
                                    value="{{ old('short_text', $plan->short_text ?? '') }}">
                                <small class="text-red">
                                    @error('short_text') {{ $message }} @enderror
                                </small>
                            </div>


                            <!-- GST -->
                            <div class="col-md-2">
                                <label class="form-label">GST % <span class="text-red">*</span></label>
                                <input type="number" step="0.01" name="gst"
                                    class="form-control only-positive"
                                    value="{{ old('gst', $plan->gst ?? '') }}"
                                    placeholder="GST %">
                                <small class="text-red">
                                    @error('gst') {{ $message }} @enderror
                                </small>
                            </div>

                            <!-- Status -->
                            <div class="col-md-2">
                                <label class="form-label">Active</label>
                                <select name="is_active" class="form-control">
                                    <option value="1"
                                        {{ (old('is_active', $plan->is_active ?? 1) == 1) ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="0"
                                        {{ (old('is_active', $plan->is_active ?? 1) == 0) ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                            </div>


                            <!-- Button -->
                            <div class="col-md-1 d-flex ">
                                <button type="submit"
                                    class="btn btn-primary mt-4 w-100 align-self-center">
                                    {{ isset($plan) ? 'Update' : 'Add' }}
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <!-- Plan List -->
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Payment Plan List</h5>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Sr.No</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Short Text</th>
                                    <th>Days</th>
                                    <th>GST %</th>
                                    <th>Status</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($plans as $p)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $p->title }}</td>
                                    <td>₹ {{ number_format($p->price,2) }}</td>
                                    <td>{{ $p->short_text }}</td>
                                    </td>
                                    <td>{{ $p->days }}</td>
                                    <td>{{ $p->gst }}%</td>
                                    <td>
                                        <!-- <form action="{{ route('admin.plans.toggle', $p->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-sm {{ $p->is_active ? 'btn-success' : 'btn-danger' }}">
                                                {{ $p->is_active ? 'Active' : 'Inactive' }}
                                            </button>
                                        </form> -->

                                        <form action="{{ route('admin.plans.toggle') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $p->id }}">

                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    role="switch"
                                                    id="statusSwitch{{ $p->id }}"
                                                    name="status"
                                                    value="1"
                                                    onchange="this.form.submit()"
                                                    {{ $p->is_active == 1 ? 'checked' : '' }}>
                                            </div>
                                        </form>

                                    </td>

                                    <td>
                                        <a href="{{ route('admin.plans.edit',$p->id) }}"
                                            class="btn btn-success btn-sm">
                                            <i class="ri-pencil-fill"></i>
                                        </a>

                                        <form action="{{ route('admin.plans.delete',$p->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">
                                                <i class="ri-delete-bin-fill"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">No Plans Found</td>
                                </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.only-positive').forEach(function(input) {
        input.addEventListener('keydown', function(e) {
            if (e.key === '-' || e.key === 'e') {
                e.preventDefault();
            }
        });
    });
</script>

@endsection