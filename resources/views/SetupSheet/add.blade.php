@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">

                             @if(isset($setupSheet))
                            <!-- Back Button ONLY on Edit -->
                            <a href="{{ route('ViewSetupSheet') }}" class="btn btn-sm btn-outline-success me-2">
                                ← Back
                            </a>
                            @endif

                            <h4 class="mb-0 flex-grow-1">
                                {{ isset($setupSheet) ? 'Edit Setup Sheet' : 'Add Setup Sheet' }}
                            </h4>
                        </div>

                        <div class="card-body">
                            @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            <div class="live-preview">
                                <form action="{{ isset($setupSheet) ? route('updateSetupSheet', base64_encode($setupSheet->id)) : route('storeSetupSheet') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @if(isset($setupSheet))
                                    @method('PUT')
                                    @endif

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="customer_id" class="form-label">Customer Code <span class="mandatory">*</span></label>
                                                <select class="form-select js-example-basic-single"
                                                    id="customer_id"
                                                    name="customer_id"
                                                    data-selected="{{ old('customer_id', $setupSheet->customer_id ?? '') }}"
                                                    {{ isset($setupSheet) ? 'disabled' : '' }}>
                                                    <option value="">Select Customer Code</option>
                                                    @foreach($codes as $c)
                                                    <option value="{{ $c->id }}" data-code="{{ $c->code }}"
                                                        {{ old('customer_id', $setupSheet->customer_id ?? '') == $c->id ? 'selected' : '' }}>
                                                        {{ $c->code }}
                                                    </option>

                                                    @endforeach
                                                </select>
                                                @error('customer_id')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror

                                                @if(isset($setupSheet))
                                                <input type="hidden" name="customer_id" value="{{ $setupSheet->customer_id }}">
                                                @endif
                                            </div>
                                        </div>


                                        <!-- Part Code -->
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="part_code" class="form-label">Part Code <span class="mandatory">*</span></label>
                                                <select id="part_code"
                                                    name="part_code"
                                                    class="form-control form-select js-example-basic-single"
                                                    data-selected="{{ old('part_code', $setupSheet->part_code ?? '') }}">
                                                    <option value="">Select Part Code</option>
                                                    @if(isset($setupSheet) && $setupSheet->customer_id)
                                                    @php
                                                    $parts = \App\Models\WorkOrder::where('customer_id', $setupSheet->customer_id)
                                                    ->with('customer:id,code')
                                                    ->get();
                                                    @endphp
                                                    @foreach($parts as $wo)
                                                    @php
                                                    $partCode = ($wo->customer?->code ?? '') . '_' . ($wo->customer_id ?? '') . '_' . ($wo->part ?? '');
                                                    @endphp
                                                    <option value="{{ $partCode }}"
                                                        {{ old('part_code', $setupSheet->part_code ?? '') == $partCode ? 'selected' : '' }}>
                                                        {{ $partCode }}
                                                    </option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                                @error('part_code')
                                                <span class="text-red small">{{ $message }}</span>
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
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Date -->
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="date" class="form-label">Date <span class="mandatory">*</span></label>
                                                <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $setupSheet->date ?? '') }}">
                                                @error('date')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Sizes -->
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="size_in_x" class="form-label">Size In X </label>
                                                <input type="text" class="form-control mt-1" id="size_in_x" name="size_in_x" value="{{ old('size_in_x', $setupSheet->size_in_x ?? '') }}">
                                                @error('size_in_x')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="size_in_y" class="form-label">Size In Y </label>
                                                <input type="text" class="form-control  mt-1" id="size_in_y" name="size_in_y" value="{{ old('size_in_y', $setupSheet->size_in_y ?? '') }}">
                                                @error('size_in_y')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="size_in_z" class="form-label">Size In Z </span></label>
                                                <input type="text" class="form-control  mt-1" id="size_in_z" name="size_in_z" value="{{ old('size_in_z', $setupSheet->size_in_z ?? '') }}">
                                                @error('size_in_z')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">Setting <span class="text-red">*</span></label>
                                            <select name="setting" class="form-control form-select  mt-1">
                                                <option value="">Select Setting</option>
                                                @foreach($settings as $setting)
                                                <option value="{{ $setting->setting_name }}"
                                                    {{ old('setting', $record->setting ?? '') == $setting->setting_name ? 'selected' : '' }}>
                                                    {{ $setting->setting_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('setting') <span class="text-red small small">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- E Time -->
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="e_time" class="form-label">Expected Time<span class="mandatory">*</span></label>
                                                <input type="text" class="form-control" id="e_time" name="e_time" value="{{ old('e_time', $setupSheet->e_time ?? '') }}">
                                                @error('e_time')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>


                                        <!-- X Refer -->
                                        <div class="col-md-2" style="position: relative;">
                                            <div class="mb-3">
                                                <label for="x_refer" class="form-label">X Axis Reference <span class="mandatory">*</span></label>
                                                <input type="text" id="x_refer" name="x_refer" class=" form-select"
                                                    placeholder="Select or type" autocomplete="off"
                                                    value="{{ old('x_refer', $setupSheet->x_refer ?? '') }}">
                                                <ul id="xOptions" class="dropdown-list">
                                                    <li data-value="">Select X refer</li>
                                                    <li data-value="Centre">Centre</li>
                                                    <li data-value="Face Shown">Face Shown</li>
                                                    <li data-value="Hole Centre">Hole Centre</li>
                                                </ul>
                                                @error('x_refer')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Y Refer -->
                                        <div class="col-md-2" style="position: relative;">
                                            <div class="mb-3">
                                                <label for="y_refer" class="form-label">Y Axis Reference <span class="mandatory">*</span></label>
                                                <input type="text" id="y_refer" name="y_refer" class="form-control form-select"
                                                    placeholder="Select or type" autocomplete="off"
                                                    value="{{ old('y_refer', $setupSheet->y_refer ?? '') }}">
                                                <ul id="yOptions" class="dropdown-list">
                                                    <li data-value="">Select Y refer</li>
                                                    <li data-value="Centre">Centre</li>
                                                    <li data-value="Face Shown">Face Shown</li>
                                                    <li data-value="Hole Centre">Hole Centre</li>
                                                </ul>
                                                @error('y_refer')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Z Refer -->
                                        <div class="col-md-2" style="position: relative;">
                                            <div class="mb-3">
                                                <label for="z_refer" class="form-label">Z Axis Reference<span class="mandatory">*</span></label>
                                                <input type="text" id="z_refer" name="z_refer" class="form-control form-select"
                                                    placeholder="Select or type" autocomplete="off"
                                                    value="{{ old('z_refer', $setupSheet->z_refer ?? '') }}">
                                                <ul id="zOptions" class="dropdown-list">
                                                    <li data-value="">Select Z refer</li>
                                                    <li data-value="Top">Top</li>
                                                    <li data-value="Centre">Centre</li>
                                                    <li data-value="Face Shown">Face Shown</li>
                                                    <li data-value="Hole Centre">Hole Centre</li>
                                                </ul>
                                                @error('z_refer')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-2" style="position: relative;">
                                            <div class="mb-3">
                                                <label for="clamping" class="form-label">Clamping <span class="mandatory">*</span></label>
                                                <input type="text" id="clamping" name="clamping" class="form-control form-select"
                                                    placeholder="Select or type" autocomplete="off"
                                                    value="{{ old('clamping', $setupSheet->clamping ?? '') }}">
                                                <ul id="clampingOptions" class="dropdown-list">
                                                    <li data-value="">Select Clamping</li>
                                                    <li data-value="Vice">Vice</li>
                                                    <li data-value="Fixture">Fixture</li>
                                                    <li data-value="Magnet">Magnet</li>
                                                </ul>
                                                @error('clamping')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="mb-3">
                                                <label for="qty" class="form-label">Quantity <span class="mandatory">*</span></label>
                                                <input type="number" step="1" min="1" class="form-control" id="qty" name="qty"
                                                    value="{{ old('qty', $setupSheet->qty ?? '') }}"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,5)">
                                                @error('qty')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="part_description" class="form-label">Part Description</label>
                                                <input type="text" class="form-control" id="part_description"
                                                    name="description">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="setup_image" class="form-label">Upload Image</label>
                                                <input type="file" class="form-control" id="setup_image" name="setup_image" accept="image/*">
                                                @error('setup_image')
                                                <span class="text-red small">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        @if(isset($setupSheet) && $setupSheet->setup_image)
                                        <div class="col-md-2">
                                            <div class="mt-2">
                                                <img src="{{ asset('setup_images/'.$setupSheet->setup_image) }}"
                                                    alt="Setup Image"
                                                    class="img-thumbnail"
                                                    style="width:70px; height:70px; object-fit:cover;">
                                            </div>

                                            @endif
                                        </div>
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


                                        <div id="dowel-holes-wrapper">
                                            @for($i = 0; $i < $count; $i++)
                                                <div class="row dowel-group mb-2">
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" name="hole_x[]" placeholder="Hole X"
                                                        value="{{ old('hole_x.' . $i, $holeXData[$i] ?? '') }}">
                                                    @error('hole_x.' . $i)
                                                    <span class="text-red small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" name="hole_y[]" placeholder="Hole Y"
                                                        value="{{ old('hole_y.' . $i, $holeYData[$i] ?? '') }}">
                                                    @error('hole_y.' . $i)
                                                    <span class="text-red small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" name="hole_dia[]" placeholder="Hole Dia"
                                                        value="{{ old('hole_dia.' . $i, $holeDiaData[$i] ?? '') }}">
                                                    @error('hole_dia.' . $i)
                                                    <span class="text-red small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="text" class="form-control" name="hole_depth[]" placeholder="Hole Depth"
                                                        value="{{ old('hole_depth.' . $i, $holeDepthData[$i] ?? '') }}">
                                                    @error('hole_depth.' . $i)
                                                    <span class="text-red small">{{ $message }}</span>
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
                            </div>
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
        let isEditMode = $("#setup_id").val() ? true : false;
        $("#customer_id").on("change", function() {
            let customer_id = $(this).val();

            if (customer_id) {
                $.ajax({
                    url: "/get-customer-parts/" + customer_id,
                    type: "GET",
                    success: function(response) {
                        let $partCode = $("#part_code");

                        $partCode.empty().append('<option value="">Select Part Code</option>');

                        response.forEach(function(item) {
                            $partCode.append(
                                `<option value="${item.part_code}"
                                     data-description="${item.part_description}"
                                     data-workorder="${item.work_order_no}"
                                     data-size_x="${item.size_in_x}"
                                     data-size_y="${item.size_in_y}"
                                     data-size_z="${item.size_in_z}"
                                     data-qty="${item.qty}"
                                     data-etime="${item.e_time}">
                                 ${item.part_code}
                             </option>`
                            );
                        });

                        let oldPart = $("#part_code").data("selected");
                        if (oldPart) {
                            $partCode.val(oldPart).trigger("change");
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert("Something went wrong while fetching parts");
                    },
                });
            } else {
                $("#part_code").empty().append('<option value="">Select Part Code</option>');
            }
        });

        $(document).on("change", "#part_code", function() {
            let selected = $(this).find(":selected");
            if (selected.val()) {
                if (!isEditMode) {
                    $("#part_description").val(selected.data("description"));
                }
                $("#work_order_no").val(selected.data("workorder"));
                $("#size_in_x").val(selected.data("size_x"));
                $("#size_in_y").val(selected.data("size_y"));
                $("#size_in_z").val(selected.data("size_z"));
                $("#qty").val(selected.data("qty"));
                $("#e_time").val(selected.data("etime"));
            }
        });


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
<script>
    function setupCustomDropdown(inputId, listId) {
        const input = document.getElementById(inputId);
        const list = document.getElementById(listId);
        const items = list.querySelectorAll('li');

        function showList() {
            list.style.display = 'block';

            items.forEach(item => {
                item.classList.remove('active');
                if (item.dataset.value === input.value) {
                    item.classList.add('active');
                }
            });
        }

        input.addEventListener('focus', showList);
        input.addEventListener('click', showList);

        // Hide on blur
        input.addEventListener('blur', () => {
            setTimeout(() => list.style.display = 'none', 150);
        });

        // Select option
        items.forEach(item => {
            item.addEventListener('mousedown', (e) => {
                e.preventDefault(); // blur block
                input.value = item.dataset.value;
                items.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                list.style.display = 'none';
            });
        });
    }

    setupCustomDropdown('x_refer', 'xOptions');
    setupCustomDropdown('y_refer', 'yOptions');
    setupCustomDropdown('z_refer', 'zOptions');
    setupCustomDropdown('clamping', 'clampingOptions');
</script>

@endsection