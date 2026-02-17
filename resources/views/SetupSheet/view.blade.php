@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    @if(session('success'))
                    <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index:1055;">
                        <div id="successAlert"
                            class="alert alert-success alert-dismissible fade show py-2 px-3 shadow-sm text-center"
                            style="max-width:500px;">
                            {{ session('success') }}
                        </div>
                    </div>
                    @endif
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h5 class="card-title mb-0">View Setup Sheets</h5>

                            <div class="ms-auto d-flex gap-2">
                                <!-- Add WorkOrder Button -->
                                @if(hasPermission('SetupSheet', 'add'))
                                <a href="{{ route('AddSetupSheet') }}" class="btn btn-success btn-sm">
                                    <i class="ri-add-line align-middle"></i> Add Setup Sheets
                                </a>
                                @endif

                                <!-- View Trash Button -->
                                <a href="{{ route('trashSetupSheet') }}" class="btn btn-warning btn-sm">
                                    View Trash
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                    <thead>
                                        <tr class="table-light">
                                            <th>Sr No.</th>
                                            <th>Image</th>
                                            <th>Part Code</th>
                                            <th>Work Order<br> No</th>
                                            <th>Date</th>
                                            <th>Size X</th>
                                            <th>Size Y</th>
                                            <th>Size Z</th>
                                            <th>Setting</th>
                                            @if(
                                            hasPermission('SetupSheet', 'edit') ||
                                            hasPermission('SetupSheet', 'delete')||
                                            hasPermission('SetupSheet', 'view')
                                            )
                                            <th width="12%">Action</th>
                                            @endif

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sheets as $sheet)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            <td>
                                                @if($sheet->setup_image)
                                                <img src="{{ asset('setup_images/'.$sheet->setup_image) }}"
                                                    alt="Setup Image" style="max-width:60px; height:auto;" class="img-thumbnail">
                                                @else
                                                <span class="text-muted">No Image</span>
                                                @endif
                                            </td>

                                            <td>{{ $sheet->part_code }}</td>
                                            <td>{{ $sheet->work_order_no }}</td>
                                            <td>{{ $sheet->date }}</td>
                                            <td>{{ $sheet->size_in_x }}</td>
                                            <td>{{ $sheet->size_in_y }}</td>
                                            <td>{{ $sheet->size_in_z }}</td>
                                            <td>{{ $sheet->setting }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    @if(hasPermission('SetupSheet', 'edit'))
                                                    <a href="{{ route('editSetupSheet', base64_encode($sheet->id)) }}" class="btn btn-success btn-icon waves-effect waves-light">
                                                        <i class="ri-pencil-fill align-bottom"></i>
                                                    </a>
                                                    @endif

                                                    @if(hasPermission('SetupSheet', 'delete'))
                                                    <a href="{{ route('deleteSetupSheet', base64_encode($sheet->id)) }}" class="btn btn-danger btn-icon waves-effect waves-light"
                                                        onclick="return confirm('Are you sure you want to delete this record?')">
                                                        <i class="ri-delete-bin-fill align-bottom"></i>
                                                    </a>
                                                    @endif

                                                    @if(hasPermission('SetupSheet', 'view'))
                                                    <button type="button" class="btn btn-warning btn-icon printSetupSheet"
                                                        data-sheet='@json($sheet)'>
                                                        <i class="fas fa-print"></i>
                                                    </button>
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



<!-- View Setup Sheet Modal -->
<!-- Modal -->
<div class="modal fade" id="viewSetupSheetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Setup Sheet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-footer">
                <button id="downloadSheetBtn" class="btn btn-light">
                    <i class="fas fa-download"></i>
                </button>
                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

            </div>

            <div class="modal-body">
                <!-- A4 Portrait Wrapper -->
                <div class="a4-portrait">

                    <!-- Sheet Header -->
                    <div class="text-center mb-3">
                        <div id="sheet_image_container" class="mb-2"></div>
                        <!-- <h4>Setup Sheet</h4> -->
                        <h5 id="sheet_description_heading" class="mt-1 text-muted"></h5>
                    </div>

                    <!-- Main Sheet Table -->
                    <table class="table table-bordered table-sm text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Part Code</th>
                                <th>Work Order No</th>
                                <th>Date</th>
                                <th>Part Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="sheet_part_code"></td>
                                <td id="sheet_work_order_no"></td>
                                <td id="sheet_date"></td>
                                <td id="sheet_description"></td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Sizes & Settings -->
                    <table class="table table-bordered table-sm text-center align-middle mt-2">
                        <thead class="table-light">
                            <tr>
                                <th>Size X</th>
                                <th>Size Y</th>
                                <th>Size Z</th>
                                <th>Setting</th>
                                <th>E Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="sheet_size_x"></td>
                                <td id="sheet_size_y"></td>
                                <td id="sheet_size_z"></td>
                                <td id="sheet_setting"></td>
                                <td id="sheet_e_time"></td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- References & Clamping -->
                    <table class="table table-bordered table-sm text-center align-middle mt-2">
                        <thead class="table-light">
                            <tr>
                                <th>X Refer</th>
                                <th>Y Refer</th>
                                <th>Z Refer</th>
                                <th>Clamping</th>
                                <th>Qty</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="sheet_x_refer"></td>
                                <td id="sheet_y_refer"></td>
                                <td id="sheet_z_refer"></td>
                                <td id="sheet_clamping"></td>
                                <td id="sheet_qty"></td>

                            </tr>
                        </tbody>
                    </table>

                    <!-- Holes -->
                    <h6 class="mt-3">Dowel Holes</h6>
                    <table class="table table-bordered table-sm text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Hole</th>
                                <th>X</th>
                                <th>Y</th>
                                <th>Dia</th>
                                <th>Depth</th>
                            </tr>
                        </thead>
                        <tbody id="holes_table_body"></tbody>
                    </table>

                </div> <!-- /.a4-portrait -->
            </div>

        </div>
    </div>
</div>


<!-- CSS -->
<style>
    .a4-portrait {
        width: 100%;
        min-height: 100%;
        padding: 0;
        margin: 0 auto;
        background: #fff;
        font-size: 15px;
    }

    /* Print Settings */
    @media print {
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        body * {
            visibility: hidden;
        }

        #viewSetupSheetModal,
        #viewSetupSheetModal * {
            visibility: visible;
        }

        #viewSetupSheetModal .modal-dialog {
            position: absolute;
            left: 0;
            top: 0;
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .a4-portrait {
            width: 100%;
            min-height: 100%;
            margin: 0;
            font-size: 20px;
        }


    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        function populateSheetData(data) {
            let holesBody = document.getElementById("holes_table_body");
            holesBody.innerHTML = "";
            if (data.hole_x && data.hole_x.length > 0) {
                for (let i = 0; i < data.hole_x.length; i++) {
                    let row = `
                <tr>
                    <td>${i + 1}</td>
                    <td>${data.hole_x[i] ?? ''}</td>
                    <td>${data.hole_y[i] ?? ''}</td>
                    <td>${data.hole_dia[i] ?? ''}</td>
                    <td>${data.hole_depth[i] ?? ''}</td>
                </tr>`;
                    holesBody.insertAdjacentHTML("beforeend", row);
                }
            }

            if (data.setup_image) {
                document.getElementById('sheet_image_container').innerHTML = `<div style=" width:370px; height:370px; 
            border:1px solid #ccc; 
            display:flex; 
            align-items:center; 
            justify-content:center; 
            margin:auto;">
            <img src="/setup_images/${data.setup_image}" 
                 alt="Setup Image" 
                 style="
                max-width:100%; 
                max-height:100%; 
                object-fit:contain;
                 ">
        </div>`;
            } else {
                document.getElementById('sheet_image_container').innerHTML = "";
            }

            document.getElementById('sheet_part_code').textContent = data.part_code ?? '';
            document.getElementById('sheet_work_order_no').textContent = data.work_order_no ?? '';
            document.getElementById('sheet_date').textContent = data.date ?? '';
            document.getElementById('sheet_description').textContent = data.description ?? '';
            document.getElementById('sheet_description_heading').textContent = data.description ?? '';

            document.getElementById('sheet_size_x').textContent = data.size_in_x ?? '';
            document.getElementById('sheet_size_y').textContent = data.size_in_y ?? '';
            document.getElementById('sheet_size_z').textContent = data.size_in_z ?? '';
            document.getElementById('sheet_setting').textContent = data.setting ?? '';
            document.getElementById('sheet_e_time').textContent = data.e_time ?? '';

            document.getElementById('sheet_x_refer').textContent = data.x_refer ?? '';
            document.getElementById('sheet_y_refer').textContent = data.y_refer ?? '';
            document.getElementById('sheet_z_refer').textContent = data.z_refer ?? '';
            document.getElementById('sheet_clamping').textContent = data.clamping ?? '';
            document.getElementById('sheet_qty').textContent = data.qty ?? '';
        }

        // Print Button Click
        document.querySelectorAll('.printSetupSheet').forEach(function(btn) {
            btn.addEventListener('click', function() {
                let data = JSON.parse(this.getAttribute('data-sheet'));

                populateSheetData(data);

                // Create a hidden printable area
                let printContents = document.querySelector('.a4-portrait').outerHTML;
                let originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
                location.reload(); // reload to restore JS functionality
            });
        });
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("downloadSheetBtn").addEventListener("click", function() {
            const {
                jsPDF
            } = window.jspdf;
            var element = document.querySelector("#viewSetupSheetModal .modal-body");

            // Temporarily remove scroll to capture full content
            const originalOverflow = element.style.overflow;
            element.style.overflow = 'visible';

            html2canvas(element, {
                scale: 2,
                useCORS: true
            }).then((canvas) => {
                const imgData = canvas.toDataURL("image/png");
                const pdf = new jsPDF("p", "mm", "a4");

                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

                pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);
                pdf.save("SetupSheet.pdf");

                element.style.overflow = originalOverflow;
            });
        });
    });
</script>

@endsection