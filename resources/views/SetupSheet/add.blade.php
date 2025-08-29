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

                                        <!-- Customer -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="customer_id" class="form-label">Customer Name <span class="mandatory">*</span></label>
                                                <select class="form-select js-example-basic-single" id="customer_id" name="customer_id" {{ isset($setupSheet) ? 'disabled' : '' }}>
                                                    <option value="">Select Customer</option>
                                                    @foreach($codes as $c)
                                                    <option value="{{ $c->id }}" data-code="{{ $c->code }}"
                                                        {{ old('customer_id', $setupSheet->customer_id ?? '') == $c->id ? 'selected' : '' }}>
                                                        {{ $c->name }} - ({{ $c->code }})
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('customer_id')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Part Code -->
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="part_code" class="form-label">Part Code <span class="mandatory">*</span></label>
                                                <select id="part_code" name="part_code" class="form-control">
                                                    <option value="">Select Part Code</option>
                                                    @if(isset($setupSheet) && $setupSheet->customer_id)
                                                    @php
                                                    $parts = \App\Models\WorkOrder::where('customer_id', $setupSheet->customer_id)
                                                    ->with('customer:id,code')
                                                    ->get();
                                                    @endphp
                                                    @foreach($parts as $wo)
                                                    @php
                                                    $partCode = ($wo->customer->code ?? '') . '_' . $wo->customer_id . '_' . $wo->part;
                                                    @endphp
                                                    <option value="{{ $partCode }}"
                                                        {{ old('part_code', $setupSheet->part_code ?? '') == $partCode ? 'selected' : '' }}>
                                                        {{ $partCode }}
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                                @error('part_code')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>


                                        <!-- Work Order No -->
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="work_order_no" class="form-label">Work Order No <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="work_order_no"
                                                    name="work_order_no" readonly
                                                    value="{{ old('work_order_no', $setupSheet->work_order_no ?? '') }}">
                                                @error('work_order_no')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Date -->
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="date" class="form-label">Date <span class="mandatory">*</span></label>
                                                <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $setupSheet->date ?? '') }}">
                                                @error('date')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Sizes -->
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="size_in_x" class="form-label">Size In X <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="size_in_x" name="size_in_x" value="{{ old('size_in_x', $setupSheet->size_in_x ?? '') }}">
                                                @error('size_in_x')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="size_in_y" class="form-label">Size In Y <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="size_in_y" name="size_in_y" value="{{ old('size_in_y', $setupSheet->size_in_y ?? '') }}">
                                                @error('size_in_y')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="size_in_z" class="form-label">Size In Z <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="size_in_z" name="size_in_z" value="{{ old('size_in_z', $setupSheet->size_in_z ?? '') }}">
                                                @error('size_in_z')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>



                                        <div class="col-md-3">
                                            <label class="form-label">Setting <span class="text-red">*</span></label>
                                            <select name="setting" class="form-control form-select">
                                                <option value="">Select Setting</option>
                                                @foreach($settings as $setting)
                                                <option value="{{ $setting->setting_name }}"
                                                    {{ old('setting', $record->setting ?? '') == $setting->setting_name ? 'selected' : '' }}>
                                                    {{ $setting->setting_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('setting') <span class="text-red small">{{ $message }}</span> @enderror
                                        </div>


                                        <!-- E Time -->
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="e_time" class="form-label">E Time <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="e_time" name="e_time" value="{{ old('e_time', $setupSheet->e_time ?? '') }}">
                                                @error('e_time')
                                                <span class="text-red">{{ $message }}</span>
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
                                                <span class="text-red">{{ $message }}</span>
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
                                                <span class="text-red">{{ $message }}</span>
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
                                                <span class="text-red">{{ $message }}</span>
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
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Thickness & Qty -->
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="thickness" class="form-label">Thickness <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="thickness" name="thickness" value="{{ old('thickness', $setupSheet->thickness ?? '') }}">
                                                @error('thickness')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="qty" class="form-label">Quantity <span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="qty" name="qty" value="{{ old('qty', $setupSheet->qty ?? '') }}">
                                                @error('qty')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="part_description" class="form-label">Part Description</label>
                                                <input type="text" class="form-control" id="part_description"
                                                    name="part_description"
                                                    value="{{ old('part_description', $setupSheet->part_description ?? '') }}">
                                                @error('part_description')
                                                <span class="text-red">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Dowel Holes Heading -->
                                        <div class="col-md-12">
                                            <h4 class="text-center mt-4 mb-3"><b>Dowel Holes</b></h4>
                                        </div>

                                        @php
                                        $holesData = old('holes', $setupSheet->holes ?? []);
                                        $holeXData = old('hole_x', $setupSheet->hole_x ?? []);
                                        $holeYData = old('hole_y', $setupSheet->hole_y ?? []);
                                        $holeDiaData = old('hole_dia', $setupSheet->hole_dia ?? []);
                                        $holeDepthData = old('hole_depth', $setupSheet->hole_depth ?? []);
                                        $count = max(count($holesData), count($holeXData), count($holeYData), count($holeDiaData), count($holeDepthData));
                                        $count = $count > 0 ? $count : 1;
                                        @endphp

                                        <!-- <div id="dowel-holes-wrapper">

                                            @for($i = 0; $i < $count; $i++)
                                                <div class="row dowel-group mb-2">
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" name="holes[]" placeholder="No. of Holes"
                                                        value="{{ $holesData[$i] ?? '' }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" name="hole_x[]" placeholder="Hole X"
                                                        value="{{ $holeXData[$i] ?? '' }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" name="hole_y[]" placeholder="Hole Y"
                                                        value="{{ $holeYData[$i] ?? '' }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" name="hole_dia[]" placeholder="Hole Dia"
                                                        value="{{ $holeDiaData[$i] ?? '' }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" name="hole_depth[]" placeholder="Hole Depth"
                                                        value="{{ $holeDepthData[$i] ?? '' }}">
                                                </div>
                                                <div class="col-md-2 d-flex align-items-center">
                                                    @if($i == 0)
                                                    <button type="button" class="btn btn-success add-row">+</button>
                                                    @else
                                                    <button type="button" class="btn btn-danger remove-row">-</button>
                                                    @endif
                                                </div>
                                        </div>
                                        @endfor
                                    </div> -->


                                        <div id="dowel-holes-wrapper">
                                            @for($i = 0; $i < $count; $i++)
                                                <div class="row dowel-group mb-2">
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" name="holes[]" placeholder="No. of Holes"
                                                        value="{{ old('holes.' . $i, $holesData[$i] ?? '') }}">
                                                    @error('holes.' . $i)
                                                    <span class="text-red">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" name="hole_x[]" placeholder="Hole X"
                                                        value="{{ old('hole_x.' . $i, $holeXData[$i] ?? '') }}">
                                                    @error('hole_x.' . $i)
                                                    <span class="text-red">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" name="hole_y[]" placeholder="Hole Y"
                                                        value="{{ old('hole_y.' . $i, $holeYData[$i] ?? '') }}">
                                                    @error('hole_y.' . $i)
                                                    <span class="text-red">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" name="hole_dia[]" placeholder="Hole Dia"
                                                        value="{{ old('hole_dia.' . $i, $holeDiaData[$i] ?? '') }}">
                                                    @error('hole_dia.' . $i)
                                                    <span class="text-red">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" name="hole_depth[]" placeholder="Hole Depth"
                                                        value="{{ old('hole_depth.' . $i, $holeDepthData[$i] ?? '') }}">
                                                    @error('hole_depth.' . $i)
                                                    <span class="text-red">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-2 d-flex align-items-center">
                                                    @if($i == 0)
                                                    <button type="button" class="btn btn-success add-row">+</button>
                                                    @else
                                                    <button type="button" class="btn btn-danger remove-row">-</button>
                                                    @endif
                                                </div>
                                        </div>
                                        @endfor
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
    $(document).ready(function() {

        // Customer change event (तुझं original code)
        $("#customer_id").on("change", function() {
            let customer_id = $(this).val();

            if (customer_id) {
                $.ajax({
                    url: "/get-customer-parts/" + customer_id,
                    type: "GET",
                    success: function(response) {
                        let $partCode = $("#part_code");

                        // clear old options
                        $partCode.empty();
                        $partCode.append('<option value="">Select Part Code</option>');

                        // loop response and append options
                        response.forEach(function(item) {
                            $partCode.append(
                                `<option value="${item.part_code}">${item.part_code}</option>`
                            );
                        });

                        // ✅ Work Order no fill
                        $("#work_order_no").val(customer_id);

                        // ✅ जर edit असेल तर saved part auto-select होईल
                        let oldPart = $("#part_code").data("selected"); // Blade मधून येणार
                        if (oldPart) {
                            $partCode.val(oldPart).trigger("change");
                        } else {
                            if (response.length > 0) {
                                $("#part_description").val(response[0].part_description);
                            } else {
                                $("#part_description").val("");
                            }
                            
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert("Something went wrong while fetching parts");
                    },
                });
            } else {
                $("#part_code").empty().append('<option value="">-- Select Part Code --</option>');
                $("#work_order_no").val("");
            }
        });

        // Part Code select change → auto fill description + work order
        $(document).on("change", "#part_code", function() {
            let selected = $(this).find(":selected");
            if (selected.val()) {
                let partCode = selected.val();

                $.ajax({
                    url: "/get-part-details/" + partCode,
                    type: "GET",
                    success: function(data) {
                        $("#part_description").val(data.part_description);
                        $("#work_order_no").val(data.work_order_no);
                        
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                    
                });
            } else {
                $("#part_description").val("");
                $("#work_order_no").val("");
            }
        });

        // ✅ Page load ला Edit असेल तर auto fire कर
        let editCustomer = $("#customer_id").data("selected");
        if (editCustomer) {
            $("#customer_id").val(editCustomer).trigger("change");
        }
    });
</script>

<script>
    $(document).ready(function() {
        $(document).on("click", ".add-row", function() {
            let newRow = `
        <div class="row dowel-group mb-2">
            <div class="col-md-2"><input type="text" class="form-control" name="holes[]" placeholder="No. of Holes"></div>
            <div class="col-md-2"><input type="text" class="form-control" name="hole_x[]" placeholder="Hole X"></div>
            <div class="col-md-2"><input type="text" class="form-control" name="hole_y[]" placeholder="Hole Y"></div>
            <div class="col-md-2"><input type="text" class="form-control" name="hole_dia[]" placeholder="Hole Dia"></div>
            <div class="col-md-2"><input type="text" class="form-control" name="hole_depth[]" placeholder="Hole Depth"></div>
            <div class="col-md-2 d-flex align-items-center">
                <button type="button" class="btn btn-danger remove-row">-</button>
            </div>
        </div>`;
            $("#dowel-holes-wrapper").append(newRow);
        });

        $(document).on("click", ".remove-row", function() {
            $(this).closest(".dowel-group").remove();
        });
    });
</script>


@endsection