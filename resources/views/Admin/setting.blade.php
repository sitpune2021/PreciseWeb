@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="mb-0 flex-grow-1">Admin Settings</h4>
                        </div>

                        <div class="card-body">
                            <div class="live-preview">

                                <form action="{{ route('UpdateAdminSetting') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">

                                        <!-- GST -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">GST No<span class="mandatory"> *</span></label>
                                                <input type="text" class="form-control"
                                                    name="gst_no"
                                                    placeholder="Eg: 27ABCDE1234F1Z5"
                                                    value="{{ old('gst_no', $data->gst_no ?? '') }}">
                                                @error('gst_no') <span class="text-red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Date -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Date</label>
                                                <div class="d-flex align-items-center">
                                                    <input type="date" class="form-control" name="date" id="date"
                                                        value="{{ old('date', $data->date ?? '') }}">
                                                    <button type="button" class="btn btn-sm btn-outline-danger ms-2" id="clearDateBtn">Clear</button>
                                                </div>
                                                <input type="hidden" name="clear_date" id="clear_date" value="0">
                                                @error('date') <span class="text-red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Udyam No -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">MSME NO<span class="mandatory"> *</span></label>
                                                <input type="text" class="form-control"
                                                    name="udyam_no"
                                                    id="udyam_no"
                                                    placeholder="UDYAM-MH-26-0589771"
                                                    value="{{ old('udyam_no', $data->udyam_no ?? '') }}">
                                                @error('udyam_no') <span class="text-red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Bank details -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Bank Account Details</label>
                                                <textarea class="form-control" name="bank_details"
                                                    placeholder="Enter Bank Details">{{ old('bank_details', $data->bank_details ?? '') }}</textarea>
                                                @error('bank_details') <span class="text-red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Declaration -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Declaration</label>
                                                <textarea class="form-control" name="declaration"
                                                    placeholder="Enter Declaration">{{ old('declaration', $data->declaration ?? '') }}</textarea>
                                                @error('declaration') <span class="text-red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Note -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Note</label>
                                                <textarea class="form-control" name="note"
                                                    placeholder="Enter Note">{{ old('note', $data->note ?? '') }}</textarea>
                                                @error('note') <span class="text-red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Footer Note -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Footer Note</label>
                                                <textarea class="form-control" name="footer_note"
                                                    placeholder="Enter Footer Note">{{ old('footer_note', $data->footer_note ?? '') }}</textarea>
                                                @error('footer_note') <span class="text-red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Logo Upload -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Upload Logo</label>
                                                <input type="file" name="logo" class="form-control">
                                                @if(!empty($data->logo))
                                                <img src="/uploads/settings/{{ $data->logo }}" width="80" class="mt-2">
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" name="remove_logo" id="remove_logo">
                                                    <label class="form-check-label" for="remove_logo">
                                                        Delete existing logo
                                                    </label>
                                                </div>
                                                @endif
                                                @error('logo') <span class="text-red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Stamp Upload -->
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Upload Stamp PNG</label>
                                                <input type="file" name="stamp" class="form-control">
                                                @if(!empty($data->stamp))
                                                <img src="/uploads/settings/{{ $data->stamp }}" width="80" class="mt-2">
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" name="remove_stamp" id="remove_stamp">
                                                    <label class="form-check-label" for="remove_stamp">
                                                        Delete existing stamp
                                                    </label>
                                                </div>
                                                @endif
                                                @error('stamp') <span class="text-red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Submit -->
                                        <div class="col-lg-12">
                                            <div class="text-end">
                                                <a href="{{ route('invoice.index') }}" class="btn btn-secondary px-4">Cancel</a>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </div>

                                    </div><!-- row end -->
                                </form>

                            </div>
                        </div>

                    </div>
                </div><!-- col -->
            </div><!-- row -->

        </div>
    </div>
</div>
<script>
    document.getElementById('clearDateBtn').addEventListener('click', function() {
        document.getElementById('date').value = '';
        document.getElementById('clear_date').value = '1';
    });
</script>
@endsection