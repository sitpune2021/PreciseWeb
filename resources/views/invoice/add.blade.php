@extends('layouts.header')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <h4 class="mb-3">Add New Invoice</h4>

            <form action="{{ route('invoice.store') }}" method="POST" id="invoiceForm">
                @csrf

                <!-- Customer -->

                <!-- <div class="col-md-4">
    <label for="customer_id" class="form-label">Customer Code <span class="text-red">*</span>
    </label>
    <select class="form-select js-example-basic-single" id="customerSelect" name="customer_id">
        <option value="">Select Customer</option>
        @foreach($customers as $cust)
            <option value="{{ $cust->id }}">{{ $cust->name }} ({{ $cust->code ?? '' }})</option>
        @endforeach
    </select>
    @error('customer_id')
        <span class="text-red small">{{ $message }}</span>
    @enderror
</div> -->


                <div class="mb-4">
                    <label class="form-label fw-bold">Select Customer <span class="text-danger">*</span></label>
                    <select name="customer_id" class="form-select w-50  " id="customerSelect">
                        <option value="">Select Customer</option>
                        @foreach($customers as $cust)
                        <option value="{{ $cust->id }}">{{ $cust->name }} ({{ $cust->code ?? '' }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- Items -->
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
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- GST Summary -->
                <div class="card p-3 shadow-sm mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">HSN Code</label>
                            <select name="hsn_code" id="hsnSelect" class="form-select">
                                <option value="">Select HSN</option>
                                @foreach($hsncodes as $h)
                                <option value="{{ $h->hsn_code }}" data-sgst="{{ $h->sgst }}" data-cgst="{{ $h->cgst }}">
                                    {{ $h->hsn_code }}
                                </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-3">
                            <label class="form-label fw-bold">Sub Total (₹)</label>
                            <input type="text" id="sub_total" name="sub_total" class="form-control" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">SGST %</label>
                            <input type="text" id="sgst_percent" name="sgst_percent" class="form-control" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">CGST %</label>
                            <input type="text" id="cgst_percent" name="cgst_percent" class="form-control" readonly>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">IGST %</label>
                            <input type="text" id="total_tax_percent" name="total_tax_percent" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Total Tax (₹)</label>
                            <input type="text" id="total_tax" name="total_tax" class="form-control" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Round Off (₹)</label>
                            <input type="text" id="round_off" name="round_off" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-success">Grand Total (₹)</label>
                            <input type="text" id="grand_total" name="grand_total" class="form-control fw-bold text-success" readonly>
                        </div>
                    </div>
                </div>

                <!-- Hidden tax fields -->
                <input type="hidden" id="sgst_amt" name="sgst_amt">
                <input type="hidden" id="cgst_amt" name="cgst_amt">

                <!-- Declaration, Note & Amount in Words -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Declaration</label>
                        <textarea name="declaration" class="form-control" rows="2">All particulars are true.</textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Note</label>
                        <textarea name="note" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">bank Details</label>
                        <textarea name="bank_details" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Amount in Words</label>
                        <textarea id="amount_words" name="amount_in_words" class="form-control" rows="2" readonly></textarea>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary px-4 me-2">Save Invoice</button>
                    <a href="{{ route('invoice.index') }}" class="btn btn-secondary px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hidden Template -->
<select id="machineSelectTemplate" class="d-none"></select>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let machineData = [];
    let globalSGST = 0,
        globalCGST = 0;

    /* ================================
       FETCH MACHINE RECORDS BY CUSTOMER
       ================================ */
    function fetchMachineRecords(customerId) {
        if (!customerId) return;
        $.get(`/invoice/fetch-machine-records/${customerId}`, function(data) {
            machineData = data || [];
            let options = '<option value="">Select Description</option>';
            machineData.forEach(item => {
                options += `
                <option value="${item.part_description}"
                        data-hsn="${item.hsn_code}" 
                        data-qty="${item.quantity}" 
                        data-exp="${item.exp_time}" 
                        data-vmc="${item.vmc_hr}" 
                        data-material_rate="${item.material_rate}">
                    ${item.part_description}
                </option>`;
            });
            $('#machineSelectTemplate').html(options);
            $('#addItemBtn').prop('disabled', false)
                .removeClass('btn-secondary')
                .addClass('btn-success');
        });
    }

    /* ================================
       ADD NEW ITEM ROW
       ================================ */
    $('#addItemBtn').on('click', function() {
        const options = $('#machineSelectTemplate').html();
        const row = `
        <tr>
            <td><select name="desc[]" class="form-select machineSelect" required>${options}</select></td>
            <td><input type="number" name="qty[]" class="form-control qty" min="1" value=""></td>
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

    /* ================================
       REMOVE ITEM
       ================================ */
    $(document).on('click', '.removeItem', function() {
        $(this).closest('tr').remove();
        calculateTotals();
    });

    /* ================================
       ON CUSTOMER CHANGE
       ================================ */
    $('#customerSelect').on('change', function() {
        const customerId = $(this).val();
        $('#itemsTable tbody').empty();
        $('#machineSelectTemplate').empty();
        $('#addItemBtn').prop('disabled', true)
            .removeClass('btn-success')
            .addClass('btn-secondary');
        globalSGST = 0;
        globalCGST = 0;
        $('#sgst_percent, #cgst_percent, #total_tax_percent').val('');
        if (customerId) fetchMachineRecords(customerId);
    });

    /* ================================
       MACHINE SELECT CHANGE
       ================================ */
    $(document).on('change', '.machineSelect', function() {
        const selected = $(this).find('option:selected');
        const row = $(this).closest('tr');
        row.find('.qty').val(selected.data('qty') || 1);
        row.find('.hrs').val(selected.data('exp') || 0);
        row.find('.material_rate').val(selected.data('material_rate') || 0);
        row.find('.vmc_hr').val(selected.data('vmc') || 0);
        calculateTotals();
    });

    /* ================================
       HSN SELECT CHANGE (OUTSIDE)
       ================================ */
    $('#hsnSelect').on('change', function() {
        const selected = $(this).find('option:selected');
        globalSGST = parseFloat(selected.data('sgst')) || 0;
        globalCGST = parseFloat(selected.data('cgst')) || 0;
        $('#sgst_percent').val(globalSGST);
        $('#cgst_percent').val(globalCGST);
        $('#total_tax_percent').val(globalSGST + globalCGST);
        calculateTotals();
    });

    /* ================================
       INPUT CHANGE → Recalculate
       ================================ */
    $(document).on('input', '.qty, .rate, .material_rate, .adj', calculateTotals);

    /* ================================
       MAIN CALCULATION FUNCTION
       ================================ */
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
        const totalTax = sgstAmt + cgstAmt;

        const beforeRound = sub + totalTax;
        const roundOff = Math.round(beforeRound) - beforeRound;
        const grand = beforeRound + roundOff;

        $('#sub_total').val(sub.toFixed(2));
        $('#sgst_amt').val(sgstAmt.toFixed(2));
        $('#cgst_amt').val(cgstAmt.toFixed(2));
        $('#total_tax').val(totalTax.toFixed(2));
        $('#round_off').val(roundOff.toFixed(2));
        $('#grand_total').val(grand.toFixed(2));
        $('#amount_words').val(numberToWords(Math.round(grand)) + " only");
    }

    /* ================================
       AMOUNT IN WORDS (Indian Format)
       ================================ */
    function numberToWords(num) {
        const a = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten",
            "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen",
            "Eighteen", "Nineteen"
        ];
        const b = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];

        if ((num = num.toString()).length > 9) return "Overflow";
        let n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
        if (!n) return;
        let str = '';
        str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + ' Crore ' : '';
        str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + ' Lakh ' : '';
        str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + ' Thousand ' : '';
        str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + ' Hundred ' : '';
        str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + ' ' : '';
        return str.trim();
    }
</script>

@endsection