@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">View Setup Sheets</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="buttons-datatables" class="display table table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Customer Name</th>
                                            <th>Part Code</th>
                                            <th>Work Order No</th>
                                            <th>Date</th>
                                            <th>Size X</th>
                                            <th>Size Y</th>
                                            <th>Size Z</th>
                                            <th>Setting</th>
                                            <th width="12%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sheets as $sheet)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $sheet->customer->name ?? '' }}</td>
                                            <td>{{ $sheet->part_code }}</td>
                                            <td>{{ $sheet->work_order_no }}</td>
                                            <td>{{ $sheet->date }}</td>
                                            <td>{{ $sheet->size_in_x }}</td>
                                            <td>{{ $sheet->size_in_y }}</td>
                                            <td>{{ $sheet->size_in_z }}</td>
                                            <td>{{ $sheet->setting }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <!-- Edit Button -->
                                                    <a href="{{ route('editSetupSheet', base64_encode($sheet->id)) }}" class="btn btn-success btn-icon waves-effect waves-light">
                                                        <i class="ri-pencil-fill align-bottom"></i>
                                                    </a>

                                                    <!-- View Button -->
                                                    <button type="button" class="btn btn-primary btn-icon viewSetupSheet"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewSetupSheetModal"
                                                        data-sheet='@json($sheet)'>
                                                        <i class="ri-eye-fill align-bottom"></i>
                                                    </button>

                                                    <!-- Delete Button -->
                                                    <a href="{{ route('deleteSetupSheet', base64_encode($sheet->id)) }}" class="btn btn-danger btn-icon waves-effect waves-light"
                                                        onclick="return confirm('Are you sure you want to delete this record?')">
                                                        <i class="ri-delete-bin-fill align-bottom"></i>
                                                    </a>
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
            </div><!--end row-->

        </div>
    </div>
</div>

<!-- View Setup Sheet Modal -->
<div class="modal fade" id="viewSetupSheetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Setup Sheet</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-footer">
                <button id="downloadSheetBtn" class="btn btn-success">Download</button>
            </div>

            <div class="modal-body">
                <!-- Sheet Header -->
                <div class="text-center mb-3">
                    <h4>Setup Sheet</h4>
                    <p id="sheet_customer_name_header"></p>
                </div>

                <!-- Main Sheet Table -->
                <table class="table table-bordered table-sm text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Customer Name</th>
                            <th>Part Code</th>
                            <th>Work Order No</th>
                            <th>Date</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="sheet_customer_name"></td>
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
                            <th>Thickness</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="sheet_x_refer"></td>
                            <td id="sheet_y_refer"></td>
                            <td id="sheet_z_refer"></td>
                            <td id="sheet_clamping"></td>
                            <td id="sheet_qty"></td>
                            <td id="sheet_thickness"></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Holes -->
                <h6 class="mt-3">Dowel Holes</h6>
        <table class="table table-bordered table-sm text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Hole #</th>
                            <th>X</th>
                            <th>Y</th>
                            <th>Dia</th>
                            <th>Depth</th>
                        </tr>
                    </thead>
                    <tbody id="holes_table_body">
                    </tbody>
                </table>

            </div>

        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.viewSetupSheet').forEach(function(btn) {
            btn.addEventListener('click', function() {
                let data = JSON.parse(this.getAttribute('data-sheet'));

              
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
                                </tr>
                            `;
                        holesBody.insertAdjacentHTML("beforeend", row);
                    }
                }


                // Top info
                document.getElementById('sheet_customer_name_header').textContent = data.customer?.name ?? '';
                document.getElementById('sheet_customer_name').textContent = data.customer?.name ?? '';
                document.getElementById('sheet_part_code').textContent = data.part_code ?? '';
                document.getElementById('sheet_work_order_no').textContent = data.work_order_no ?? '';
                document.getElementById('sheet_date').textContent = data.date ?? '';
                document.getElementById('sheet_description').textContent = data.description ?? '';

                // Sizes & Settings
                document.getElementById('sheet_size_x').textContent = data.size_in_x ?? '';
                document.getElementById('sheet_size_y').textContent = data.size_in_y ?? '';
                document.getElementById('sheet_size_z').textContent = data.size_in_z ?? '';
                document.getElementById('sheet_setting').textContent = data.setting ?? '';
                document.getElementById('sheet_e_time').textContent = data.e_time ?? '';

                // References
                document.getElementById('sheet_x_refer').textContent = data.x_refer ?? '';
                document.getElementById('sheet_y_refer').textContent = data.y_refer ?? '';
                document.getElementById('sheet_z_refer').textContent = data.z_refer ?? '';
                document.getElementById('sheet_clamping').textContent = data.clamping ?? '';
                document.getElementById('sheet_qty').textContent = data.qty ?? '';
                document.getElementById('sheet_thickness').textContent = data.thickness ?? '';

                // Holes loop
                for (let i = 0; i < 5; i++) {
                    document.getElementById(`sheet_hole_x_${i}`).textContent = hole_x[i] ?? '';
                    document.getElementById(`sheet_hole_y_${i}`).textContent = hole_y[i] ?? '';
                    document.getElementById(`sheet_hole_dia_${i}`).textContent = hole_dia[i] ?? '';
                    document.getElementById(`sheet_hole_depth_${i}`).textContent = hole_depth[i] ?? '';
                }


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

            html2canvas(element, {
                scale: 2
            }).then((canvas) => {
                const imgData = canvas.toDataURL("image/png");
                const pdf = new jsPDF("p", "mm", "a4");

                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

                pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);
                pdf.save("SetupSheet.pdf");
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("downloadSheetBtn").addEventListener("click", function() {
            const {
                jsPDF
            } = window.jspdf;


            var element = document.querySelector("#viewRecordModal .modal-body");

            html2canvas(element, {
                scale: 2
            }).then((canvas) => {
                const imgData = canvas.toDataURL("image/png");
                const pdf = new jsPDF("p", "mm", "a4");

                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

                pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);
                pdf.save("MachineRecord.pdf");
            });
        });
    });
</script>

@endsection