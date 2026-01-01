@extends('layouts.header')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <h5 class="card-title mb-0">
                {{ isset($data) ? 'Edit invoice' : 'Add invoice' }}
            </h5>

            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(isset($data))

                @endif
                <form action="{{ isset($data) ? route('proformaUpdate', base64_encode($data->id)) : route('proforma.store') }}" method="POST">

                    <input type="hidden" name="invoice_no" value="{{ $data->invoice_no ?? '' }}">


                    @csrf
                    <div class="row align-items-end">
                        <div class="row g-3">
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Select Customer <span class="text-red">*</span></label>
                                <select name="customer_id"
                                    class="form-select js-example-basic-single"
                                    id="customerSelect"
                                    {{ isset($data) ? 'disabled' : '' }}>
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $cust)
                                    <option value="{{ $cust->id }}"
                                        {{ old('customer_id', $data->customer_id ?? '') == $cust->id ? 'selected' : '' }}>
                                        {{ $cust->name }} ({{ $cust->code ?? '' }})
                                    </option>
                                    @endforeach
                                </select>

                                @if(isset($data))
                                <input type="hidden" name="customer_id" id="customer_id" value="{{ $data->customer_id }}">
                                <input type="hidden" id="editMode" value="1">
                                @endif


                                @error('customer_id')
                                <div class="text-red mt-1 small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-4">
                                <label class="form-label">Invoice Date</label>
                                <input type="date" class="form-control"
                                    name="invoice_date"
                                    value="{{ old('invoice_date', isset($data->invoice_date) ? \Carbon\Carbon::parse($data->invoice_date)->format('Y-m-d') : '') }}">
                                @error('invoice_date')
                                <span class="text-red small">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                    </div>
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Item Details</h5>
                            <button type="button" class="btn btn-secondary btn-sm" id="addItemBtn" disabled>+ Add Item</button>
                        </div>

                        <div class="card-body">
                            <table class="table table-bordered text-center align-middle" id="itemsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Particulars</th>
                                        <th>Qty</th>
                                        <th>Rate (₹)</th>
                                        <th>Amount (₹)</th>
                                        <th>Material Rate</th>
                                        <th>Hrs</th>
                                        <th>ADJ</th>
                                        <th>EST</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody id="itemsTableBody">
                                    @if(isset($invoice) && $invoice->items->count())
                                    @foreach($invoice->items as $item)

                                    <tr>
                                        <td>
                                            <select name="desc[]" class="form-select machineSelect" required>
                                                <option value="{{ $item->part_name }}" selected>{{ $item->part_name }}</option>
                                            </select>

                                            <input type="hidden" name="project_id[]" class="project_id" value="{{ $item->project_id }}">
                                            <input type="hidden" name="work_order_id[]" class="work_order_id" value="{{ $item->work_order_id }}">
                                            <input type="hidden" name="hsn_code_item[]" class="hsn_code_item" value="{{ $item->hsn_code ?? '' }}">
                                        </td>

                                        <td><input type="number" name="qty[]" class="form-control qty" value="{{ $item->qty }}" readonly></td>
                                        <td><input type="number" name="rate[]" class="form-control rate" value="{{ $item->rate }}" readonly></td>
                                        <td><input type="text" name="amount[]" class="form-control amount" value="{{ $item->amount }}" readonly></td>

                                        <td><input type="number" name="material_rate[]" class="form-control material_rate" value="{{ $item->material_rate ?? 0 }}" readonly></td>
                                        <td><input type="text" name="hrs[]" class="form-control hrs" value="{{ $item->hrs }}" readonly></td>
                                        <td><input type="number" name="adj[]" class="form-control adj" value="{{ $item->adj ?? 0 }}"></td>
                                        <td><input type="text" name="vmc_hr[]" class="form-control vmc_hr" value="{{ $item->vmc ?? 0 }}" readonly></td>

                                        <td><button type="button" class="btn btn-danger btn-sm removeItem">X</button></td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card p-3 shadow-sm mb-4">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">HSN Code</label>
                                <select name="hsn_code[]" id="hsnSelect" class="form-select hsnSelect">
                                    @if(!isset($data))
                                    <option value="">Select HSN</option>
                                    @endif

                                    @foreach($hsncodes as $h)
                                    <option value="{{ $h->hsn_code }}"
                                        data-sgst="{{ $h->sgst }}"
                                        data-cgst="{{ $h->cgst }}"
                                        data-igst="{{ $h->igst }}"
                                        {{ isset($invoice->items[0]) && $invoice->items[0]->hsn_code == $h->hsn_code ? 'selected' : '' }}>
                                        {{ $h->hsn_code }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Sub Total (₹)</label>
                                <input type="text" id="sub_total" name="sub_total" class="form-control" value="{{ old('sub_total', $data->sub_total ?? '') }}" readonly>
                            </div>

                            <div class="col-md-2">
                                <label class="form-label fw-bold">SGST %</label>
                                <input type="text" id="sgst_percent" name="sgst_percent" class="form-control" value="{{ old('sgst_percent', $data->sgst_percent ?? '') }}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">CGST %</label>
                                <input type="text" id="cgst_percent" name="cgst_percent" class="form-control" value="{{ old('cgst_percent', $data->cgst_percent ?? 0) }}" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold">IGST %</label>
                                <input type="text" id="total_tax_percent" name="total_tax_percent" class="form-control" value="{{ old('total_tax_percent', $data->total_tax_percent ?? '') }}" readonly>
                            </div>
                        </div>

                        <div class="row g-3 mt-2">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Total Tax (₹)</label>
                                <input type="text" id="total_tax" name="total_tax" class="form-control" value="{{ old('total_tax', $data->total_tax ?? '') }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Round Off (₹)</label>
                                <input type="text" id="round_off" name="round_off" class="form-control" value="{{ old('round_off', $data->round_off ?? '') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-success">Grand Total (₹)</label>
                                <input type="text" id="grand_total" name="grand_total" class="form-control fw-bold text-success" value="{{ old('grand_total', $data->grand_total ?? '') }}" readonly>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="sgst_amt" name="sgst_amt">
                    <input type="hidden" id="cgst_amt" name="cgst_amt">

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Declaration</label>
                            <textarea name="declaration" class="form-control" rows="2">{{ old('declaration', $data->declaration ?? $adminSetting->declaration ??'') }}</textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Note</label>
                            <textarea name="note" class="form-control" rows="2">{{ old('note', $data->note ?? $adminSetting->note ??'') }}</textarea>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Bank Details</label>
                            <textarea name="bank_details" class="form-control" rows="2">{{ old('bank_details', $data->bank_details ?? $adminSetting->bank_details ??'') }}</textarea>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label fw-bold">Amount in Words</label>
                            <textarea id="amount_words" name="amount_in_words" class="form-control" rows="2">
                            {{ old('amount_in_words', $data->amount_in_words ?? '') }}
                            </textarea>

                        </div>
                    </div>

                    <div class="col-lg-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($data) ? 'Update' : 'Submit' }}
                        </button>
                        &nbsp;
                        @if(isset($data))
                        <a href="{{ route('proforma.index') }}" class="btn btn-info">Cancel</a>
                        @else
                        <button type="reset" class="btn btn-info">Reset</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<select id="machineSelectTemplate" class="d-none"></select>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let machineData = [];
        let globalSGST = 0,
            globalCGST = 0,
            globalIGST = 0;
        let gstLoaded = false;
        let itemsLoaded = false;
        let editMode = $("#editMode").length && $("#editMode").val() == "1";

        $('.js-example-basic-single').select2();

        function tryCalculate() {
            if (editMode) {
                if (gstLoaded && itemsLoaded) {
                    calculateTotals();
                }
            } else {
                if (gstLoaded) {
                    calculateTotals();
                }
            }
        }

        function fetchMachineRecords(customerId) {
    if (!customerId) return;

    $.get(`/proforma/fetch-machine-records/${customerId}`, function (data) {

        machineData = data || [];
        let options = '<option value="">Select Description</option>';

        machineData.forEach(item => {
            options += `
                <option value="${item.part_description}"
                    data-project-id="${item.project_id}"
                    data-workorder-id="${item.workorder_id}"
                    data-hsn="${item.hsn_code ?? ''}"
                    data-qty="${item.quantity}"
                    data-exp="${item.exp_time}"
                    data-vmc="${item.vmc_hr}"
                    data-material_rate="${item.material_rate}">
                    ${item.part_description}
                </option>`;
        });

        $('#machineSelectTemplate').html(options);

        $('#addItemBtn')
            .prop('disabled', false)
            .removeClass('btn-secondary')
            .addClass('btn-success');

        if (editMode) {
            loadExistingInvoiceItems();
        }
    });
}


        $('#addItemBtn').on('click', function() {
            const options = $('#machineSelectTemplate').html();

            const row = `
<tr>
    <td>
        <select name="desc[]" class="form-select machineSelect" required>${options}</select>
        <input type="hidden" name="work_order_id[]" class="work_order_id">
    </td>
    <td><input type="number" name="qty[]" class="form-control qty" step="1" min="1" readonly></td>
    <td><input type="number" name="rate[]" class="form-control rate" step="0.01" readonly></td>
    <td><input type="text" name="amount[]" class="form-control amount" readonly></td>
    <td><input type="number" name="material_rate[]" class="form-control material_rate" step="0.01" readonly></td>
    <td><input type="text" name="hrs[]" class="form-control hrs" readonly></td>
    <td><input type="number" name="adj[]" class="form-control adj" value="0"></td>
    <td><input type="text" name="vmc_hr[]" class="form-control vmc_hr" readonly></td>
    <td><button type="button" class="btn btn-danger btn-sm removeItem">X</button></td>
</tr>`;
            $('#itemsTable tbody').append(row);
        });

        $(document).on('click', '.removeItem', function() {
            $(this).closest('tr').remove();
            if (gstLoaded) calculateTotals();
        });

        $('#customerSelect').on('change', function() {
            const customerId = $(this).val();

            if (!editMode) {
                $('#itemsTable tbody').empty();
            }

            $('#machineSelectTemplate').empty();
            $('#addItemBtn').prop('disabled', true)
                .removeClass('btn-success')
                .addClass('btn-secondary');

            globalSGST = 0;
            globalCGST = 0;
            globalIGST = 0;
            gstLoaded = false;

            $('#sgst_percent, #cgst_percent, #total_tax_percent').val('');

            if (customerId) fetchMachineRecords(customerId);
        });

        if (editMode) {
            const selectedCustomerId = "{{ $data->customer_id ?? '' }}";
            if (selectedCustomerId) {
                $('#customerSelect').val(selectedCustomerId).trigger('change');
            } else {
                itemsLoaded = $('#itemsTable tbody tr').length > 0;
            }
        }

        function loadExistingInvoiceItems() {
            let items = @json($data->items ?? []);
            console.log(items);

            if (!items || !Array.isArray(items)) items = [];

            const options = $('#machineSelectTemplate').html();

            $('#itemsTable tbody').empty();
            items.forEach(inv => {

                let descField = '';

                if ("{{ isset($data) ? 1 : 0 }}" == "1") {
                    descField = `<input type="text" name="desc[]" class="form-control" value="${inv.part_name}" readonly>`;
                } else {
                    descField = `<select name="desc[]" class="form-select machineSelect" required>${options}</select>`;
                }

                let row = `
            <tr>
                <td> ${descField}
                    <input type="hidden" name="work_order_id[]" value="${inv.work_order_id}">
                     <input type="hidden" name="project_id[]" value="${inv.project_id}">
                     <input type="hidden" name="id[]" value="${inv.id}">
                    
                </td>              
                <td><input type="number" name="qty[]" class="form-control qty" value="${inv.qty}" readonly></td>
                <td><input type="number" name="rate[]" class="form-control rate" value="${inv.rate}" readonly></td>
                <td><input type="text" name="amount[]" class="form-control amount" value="${inv.amount}" readonly></td>
                <td><input type="number" name="material_rate[]" class="form-control material_rate" value="${inv.material_rate}" readonly></td>
                <td><input type="text" name="hrs[]" class="form-control hrs" value="${inv.hrs}" readonly></td>
                <td><input type="number" name="adj[]" class="form-control adj" value="${inv.adj ?? 0}"></td>
                <td><input type="text" name="vmc_hr[]" class="form-control vmc_hr" value="${inv.vmc ?? 0}" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm removeItem">X</button></td>
            </tr>`;
                $('#itemsTable tbody').append(row);
                if ("{{ isset($data) ? 1 : 0 }}" != "1") {
                    let lastRow = $('#itemsTable tbody tr').last();
                    lastRow.find('.machineSelect').val(inv.part_name).trigger('change');
                }
            });

            itemsLoaded = true;
            tryCalculate();
        }

        $(document).on('change', '.machineSelect', function() {
            const selected = $(this).find('option:selected');
            const row = $(this).closest('tr');

            row.find('.qty').val(selected.data('qty') || 1);
            row.find('.hrs').val(selected.data('exp') || 0);
            row.find('.material_rate').val(selected.data('material_rate') || 0);
            row.find('.vmc_hr').val(selected.data('vmc') || 0);

            if (!row.find('input[name="project_id[]"]').length) {
                row.append(`<input type="hidden" name="project_id[]" value="${selected.data('project-id') || ''}">`);
            } else {
                row.find('input[name="project_id[]"]').val(selected.data('project-id') || '');
            }

            if (!row.find('input[name="work_order_id[]"]').length) {
                row.append(`<input type="hidden" name="work_order_id[]" value="${selected.data('workorder-id') || ''}">`);
            } else {
                row.find('input[name="work_order_id[]"]').val(selected.data('workorder-id') || '');
            }

            (function calcRowNow(r) {
                const qty = parseFloat(r.find('.qty').val()) || 0;
                const exp = parseFloat(r.find('.hrs').val()) || 0;
                const adj = parseFloat(r.find('.adj').val()) || 0;
                const materialRate = parseFloat(r.find('.material_rate').val()) || 0;

                const rate = qty > 0 ? ((exp + adj) * materialRate) / qty : 0;
                const amount = rate * qty;

                r.find('.rate').val(rate.toFixed(2));
                r.find('.amount').val(amount.toFixed(2));
            })(row);

            if (gstLoaded) calculateTotals();
        });

        $('#hsnSelect').on('change', function() {
            const selected = $(this).find('option:selected');

            globalSGST = parseFloat(selected.data('sgst')) || 0;
            globalCGST = parseFloat(selected.data('cgst')) || 0;
            globalIGST = parseFloat(selected.data('igst')) || 0;

            $('#sgst_percent').val(globalSGST);
            $('#cgst_percent').val(globalCGST);
            $('#total_tax_percent').val(globalIGST);

            gstLoaded = true;

            tryCalculate();
        });

        let selectedHSN = $("#hsnSelect").val();
        if (selectedHSN) {
            $("#hsnSelect").val(selectedHSN).trigger('change');
        }

        $(document).on('input', '.qty, .rate, .material_rate, .adj', function() {
            if (gstLoaded) calculateTotals();
        });

        function calculateTotals() {



            let sub = 0;
            $('#itemsTable tbody tr').each(function() {
                const qty = parseFloat($(this).find('.qty').val()) || 0;
                const exp = parseFloat($(this).find('.hrs').val()) || 0;
                const adj = parseFloat($(this).find('.adj').val()) || 0;
                const materialRate = parseFloat($(this).find('.material_rate').val()) || 0;

                const rate = qty > 0 ? ((exp + adj) * materialRate) / qty : 0;
                const amount = rate * qty;

                $(this).find('.rate').val(rate.toFixed(2));
                $(this).find('.amount').val(amount.toFixed(2));

                sub += amount;
            });

            const sgstAmt = (sub * (globalSGST || 0)) / 100;
            const cgstAmt = (sub * (globalCGST || 0)) / 100;
            const igstAmt = (sub * (globalIGST || 0)) / 100;

            const totalTax = sgstAmt + cgstAmt + igstAmt;

            const beforeRound = sub + totalTax;
            const roundOff = Math.round(beforeRound) - beforeRound;
            const grand = beforeRound + roundOff;

            $('#sub_total').val(sub.toFixed(2));
            $('#sgst_amt').val(sgstAmt.toFixed(2));
            $('#cgst_amt').val(cgstAmt.toFixed(2));
            $('#igst_amt').val(igstAmt.toFixed(2));
            $('#total_tax').val(totalTax.toFixed(2));
            $('#round_off').val(roundOff.toFixed(2));
            $('#grand_total').val(grand.toFixed(2));
            $('#amount_words').val(numberToWords(Math.round(grand)) + " only");

        }

        // ---------- numberToWords ----------
        function numberToWords(num) {
            const a = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten",
                "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen",
                "Eighteen", "Nineteen"
            ];
            const b = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];

            if ((num = num.toString()).length > 9) return "Overflow";
            let n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
            if (!n) return "";
            let str = '';
            str += (n[1] != 0) ? (a[+n[1]] || b[n[1][0]] + ' ' + a[n[1][1]]) + ' Crore ' : '';
            str += (n[2] != 0) ? (a[+n[2]] || b[n[2][0]] + ' ' + a[n[2][1]]) + ' Lakh ' : '';
            str += (n[3] != 0) ? (a[+n[3]] || b[n[3][0]] + ' ' + a[n[3][1]]) + ' Thousand ' : '';
            str += (n[4] != 0) ? (a[+n[4]] || b[n[4][0]] + ' ' + a[n[4][1]]) + ' Hundred ' : '';
            str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[+n[5]] || b[n[5][0]] + ' ' + a[n[5][1]]) + ' ' : '';
            return str.trim();
        }

        // ---------- Prevent auto-submit in edit mode (keeps your existing logic) ----------
        if (editMode) {
            $('#invoiceForm').on('submit', function(e) {
                if (!window.allowSubmit) {
                    e.preventDefault();
                    return false;
                }
            });

            // ensure updateBtn handler exists (blade should set id when editing)
            $(document).on('click', '#updateBtn', function() {
                window.allowSubmit = true;
                $('#invoiceForm').submit();
            });
        }

    }); // end document.ready
</script>


@endsection