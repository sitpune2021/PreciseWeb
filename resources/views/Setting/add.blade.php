@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <!-- Form Start -->
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ isset($setting) ? 'Edit Setting ' : 'Add Setting ' }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ isset($setting) ? route('updateSetting', base64_encode($setting->id)) : route('storeSetting') }}" method="POST">
                        @csrf
                        @if(isset($setting))
                        @method('PUT')
                        @endif
                        <div class="row align-items-end">
                           <div class="col-md-4 col-sm-6 mb-3 position-relative">
                                <label for="setting_name" class="form-label">
                                    Setting Name <span class="mandatory">*</span>
                                </label>
                                <input type="text"
                                    class="form-control form-control-sm px-3 py-2"
                                    id="setting_name"
                                    name="setting_name"
                                    value="{{ old('setting_name', isset($setting) ? $setting->setting_name : '') }}"
                                    placeholder="Enter Setting Name"
                                    style="background-image: none !important;">
 
                                @error('setting_name')
                                    <small class="text-danger position-absolute"
                                        style="bottom:-18px; left:2px; font-size:12px;">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>
 
                            <div class="col-md-2 col-sm-6 mb-3">
                                <button type="submit" class="btn btn-primary w-100 px-3 py-2">
                                    {{ isset($setting) ? 'Update' : 'Add' }}
                                </button>
                            </div>
 
                        </div>

                        </div>
                    </form>
                </div>
            </div>
            <!-- Form End -->

            <!-- List Start -->
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Setting List</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">Sr.No</th>
                                    <th style="width: 60%; text-align: center;">Setting Name</th>
                                    <th style="width: 15%;">Status</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($settings as $s)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $s->setting_name }}</td>
                                    <td>
                                        <form action="{{ route('updateSettingStatus') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $s->id }}">
                                            <div class="form-check form-switch">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    role="switch"
                                                    id="statusSwitch{{ $s->id }}"
                                                    name="status"
                                                    value="1"
                                                    onchange="this.form.submit()"
                                                    {{ $s->status == 1 ? 'checked' : '' }}>
                                            </div>
                                        </form>
                                    </td>

                                    <td>
                                        <a href="{{ route('editSetting', base64_encode($s->id)) }}" class="btn btn-success btn-sm">
                                            <i class="ri-pencil-fill align-bottom"></i>
                                        </a>

                                        <a href="{{route('deleteSetting', base64_encode($s->id))}}"
                                        onclick="return confirm('Are you sure you want to delete this record?')">
                                            <button type="button" class="btn btn-danger btn-sm">
                                                <i class="ri-delete-bin-fill align-bottom"></i>
                                            </button>
                                        </a>


                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No settings found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- List End -->

        </div>
    </div>
</div>

@endsection