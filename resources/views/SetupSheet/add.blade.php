@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="mb-0 flex-grow-1">
                                {{ isset($setupSheet) ? 'Edit Setup Sheet' : 'Add Setup Sheet' }}
                            </h4>
                        </div>

                        <div class="card-body">
                            <div class="live-preview">
                                <form action="{{ isset($setupSheet) ? route('updateSetupSheet', base64_encode($setupSheet->id)) : route('storeSetupSheet') }}" method="POST">
                                    @csrf
                                    @if(isset($setupSheet))
                                    @method('PUT')
                                    @endif

                                    <div class="row">
                                        <!-- Part Code -->
                                       <div class="col-md-4">
    <div class="mb-3">
        <label for="code" class="form-label">Customer Name <span class="mandatory">*</span></label>
        <select class="form-select" id="customer_id" name="customer_id">
            <option value="">Select Customer</option>
            @foreach($codes as $c)
                <option value="{{ $c->id }}" data-code="{{ $c->code }}">
                    {{ $c->name }} - ({{ $c->code }})
                </option>
            @endforeach
        </select>
        @error('customer_id')
        <span class="text-red">{{ $message }}</span>
        @enderror
        <span class="text-danger customer"></span>
    </div>
</div>

<!-- Part Code -->
<div class="col-md-3">
    <div class="mb-3">
        <label for="part_code" class="form-label">Part Code <span class="mandatory">*</span></label>
        <input type="text" class="form-control" id="part_code" name="part_code" readonly>
        @error('part_code')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

<!-- Work Order No -->
<div class="col-md-2">
    <div class="mb-3">
        <label for="work_order_no" class="form-label">Work Order No <span class="mandatory">*</span></label>
        <input type="text" class="form-control" id="work_order_no" name="work_order_no">
        @error('work_order_no')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>

                                            <!-- Date -->
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="date" class="form-label">Date <span class="mandatory">*</span></label>
                                                    <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $setupSheet->date ?? '') }}">
                                                    @error('date')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Sizes -->
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="size_in_x" class="form-label">Size In X <span class="mandatory">*</span></label>
                                                    <input type="text" class="form-control" id="size_in_x" name="size_in_x" value="{{ old('size_in_x', $setupSheet->size_in_x ?? '') }}">
                                                    @error('size_in_x')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="size_in_y" class="form-label">Size In Y <span class="mandatory">*</span></label>
                                                    <input type="text" class="form-control" id="size_in_y" name="size_in_y" value="{{ old('size_in_y', $setupSheet->size_in_y ?? '') }}">
                                                    @error('size_in_y')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="size_in_z" class="form-label">Size In Z <span class="mandatory">*</span></label>
                                                    <input type="text" class="form-control" id="size_in_z" name="size_in_z" value="{{ old('size_in_z', $setupSheet->size_in_z ?? '') }}">
                                                    @error('size_in_z')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Setting -->
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="setting" class="form-label">Setting <span class="mandatory">*</span></label>
                                                    <input type="text" class="form-control" id="setting" name="setting" value="{{ old('setting', $setupSheet->setting ?? '') }}">
                                                    @error('setting')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- E Time -->
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="e_time" class="form-label">E Time <span class="mandatory">*</span></label>
                                                    <input type="text" class="form-control" id="e_time" name="e_time" value="{{ old('e_time', $setupSheet->e_time ?? '') }}">
                                                    @error('e_time')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Dropdowns -->
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="x_refer" class="form-label">X Refer <span class="mandatory">*</span></label>
                                                    <select class="form-select" id="x_refer" name="x_refer">
                                                        <option value="">Select X</option>
                                                        <option value="Centre" {{ old('x_refer', $setupSheet->x_refer ?? '') == 'Centre' ? 'selected' : '' }}>Centre</option>
                                                        <option value="Face Shown" {{ old('x_refer', $setupSheet->x_refer ?? '') == 'Face Shown' ? 'selected' : '' }}>Face Shown</option>
                                                        <option value="Hole Centre" {{ old('x_refer', $setupSheet->x_refer ?? '') == 'Hole Centre' ? 'selected' : '' }}>Hole Centre</option>
                                                    </select>
                                                    @error('x_refer')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="y_refer" class="form-label">Y Refer <span class="mandatory">*</span></label>
                                                    <select class="form-select" id="y_refer" name="y_refer">
                                                        <option value="">Select Y</option>
                                                        <option value="Centre" {{ old('y_refer', $setupSheet->y_refer ?? '') == 'Centre' ? 'selected' : '' }}>Centre</option>
                                                        <option value="Face Shown" {{ old('y_refer', $setupSheet->y_refer ?? '') == 'Face Shown' ? 'selected' : '' }}>Face Shown</option>
                                                        <option value="Hole Centre" {{ old('y_refer', $setupSheet->y_refer ?? '') == 'Hole Centre' ? 'selected' : '' }}>Hole Centre</option>
                                                    </select>
                                                    @error('y_refer')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="z_refer" class="form-label">Z Refer <span class="mandatory">*</span></label>
                                                    <select class="form-select" id="z_refer" name="z_refer">
                                                        <option value="">Select Z</option>
                                                        <option value="Top" {{ old('z_refer', $setupSheet->z_refer ?? '') == 'Top' ? 'selected' : '' }}>Top</option>
                                                        <option value="Centre" {{ old('z_refer', $setupSheet->z_refer ?? '') == 'Centre' ? 'selected' : '' }}>Centre</option>
                                                        <option value="Face Shown" {{ old('z_refer', $setupSheet->z_refer ?? '') == 'Face Shown' ? 'selected' : '' }}>Face Shown</option>
                                                        <option value="Hole Centre" {{ old('z_refer', $setupSheet->z_refer ?? '') == 'Hole Centre' ? 'selected' : '' }}>Hole Centre</option>
                                                    </select>
                                                    @error('z_refer')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="clamping" class="form-label">Clamping <span class="mandatory">*</span></label>
                                                    <select class="form-select" id="clamping" name="clamping">
                                                        <option value="">Select Clamping</option>
                                                        <option value="Vice" {{ old('clamping', $setupSheet->clamping ?? '') == 'Vice' ? 'selected' : '' }}>Vice</option>
                                                        <option value="Fixture" {{ old('clamping', $setupSheet->clamping ?? '') == 'Fixture' ? 'selected' : '' }}>Fixture</option>
                                                        <option value="Magnet" {{ old('clamping', $setupSheet->clamping ?? '') == 'Magnet' ? 'selected' : '' }}>Magnet</option>
                                                    </select>
                                                    @error('clamping')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Thickness & Qty -->
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="thickness" class="form-label">Thickness <span class="mandatory">*</span></label>
                                                    <input type="text" class="form-control" id="thickness" name="thickness" value="{{ old('thickness', $setupSheet->thickness ?? '') }}">
                                                    @error('thickness')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="qty" class="form-label">Quantity <span class="mandatory">*</span></label>
                                                    <input type="text" class="form-control" id="qty" name="qty" value="{{ old('qty', $setupSheet->qty ?? '') }}">
                                                    @error('qty')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Description -->
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Description <span class="mandatory">*</span></label>
                                                    <textarea class="form-control" id="description" name="description" placeholder="Enter Description">{{ old('description', $setupSheet->description ?? '') }}</textarea>
                                                    @error('description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Holes -->
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="holes" class="form-label">No. of Holes <span class="mandatory">*</span></label>
                                                    <input type="text" class="form-control" id="holes" name="holes" value="{{ old('holes', $setupSheet->holes ?? '') }}">
                                                    @error('holes')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="hole_x" class="form-label">Hole X <span class="mandatory">*</span></label>
                                                    <input type="text" class="form-control" id="hole_x" name="hole_x" value="{{ old('hole_x', $setupSheet->hole_x ?? '') }}">
                                                    @error('hole_x')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="hole_y" class="form-label">Hole Y <span class="mandatory">*</span></label>
                                                    <input type="text" class="form-control" id="hole_y" name="hole_y" value="{{ old('hole_y', $setupSheet->hole_y ?? '') }}">
                                                    @error('hole_y')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="hole_dia" class="form-label">Hole Dia <span class="mandatory">*</span></label>
                                                    <input type="text" class="form-control" id="hole_dia" name="hole_dia" value="{{ old('hole_dia', $setupSheet->hole_dia ?? '') }}">
                                                    @error('hole_dia')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label for="hole_depth" class="form-label">Hole Depth <span class="mandatory">*</span></label>
                                                    <input type="text" class="form-control" id="hole_depth" name="hole_depth" value="{{ old('hole_depth', $setupSheet->hole_depth ?? '') }}">
                                                    @error('hole_depth')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Submit -->
                                            <div class="col-lg-12">
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-primary">{{ isset($setupSheet) ? 'Update' : 'Submit' }}</button>
                                                    &nbsp;
                                                    @if(isset($setupSheet))
                                                    <a href="{{ route('ViewSetupSheet') }}" class="btn btn-info">Cancel</a>
                                                    @else
                                                    <button type="reset" class="btn btn-info">Reset</button>
                                                    @endif
                                                </div>
                                            </div>

                                    </div><!-- end row -->
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div><!-- end row -->
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).on('change', '#customer_id', function () {
        var customer_id = $(this).val();
        if(customer_id){
            $.ajax({
                url: "{{ route('getPartCodeByCustomer') }}",
                type: "GET",
                data: { customer_id: customer_id },
                success: function (data) {
                    if(data.part_code){
                        $('#part_code').val(data.part_code); // Part Code field auto fill
                    } else {
                        $('#part_code').val('');
                    }
                }
            });
        } else {
            $('#part_code').val('');
        }
    });
</script>


@endsection