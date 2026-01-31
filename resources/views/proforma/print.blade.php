<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_no ?? 'XXXX' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }

        .no-border {
            border: none !important;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .header-left {
            background-color: #cafae2;
            vertical-align: top;
        }

        .company-box {
            display: flex;
            align-items: flex-start;
        }

        .company-logo {
            width: 90px;
            height: 90px;
            margin-right: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .gst-heading {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
        }

        .small {
            font-size: 11px;
        }

        h2 {
            margin: 0;
        }

        h3 {
            margin: 4px 0 0 0;
        }

        .invoice-title {
            text-align: center;
            margin: 4px 0;
        }

        .invoice-subtitle {
            border-left: 1px solid #686666ff;
            border-right: 1px solid #525151ff;
            margin: 0;
            padding: 6px 0;
            text-align: center;
            font-weight: bold;
            font-size: 12.5px;
            line-height: 1.4;
        }

        .declaration-box {
            border: 1px solid #c1c1c1ff;
            padding: 8px;
            font-size: 12px;
            line-height: 1.4;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }

        .header-table td {
            background-color: #cfceceff;
            color: #232323ff;
            font-weight: bold;
            font-size: 15px;
            text-align: center;
            padding: 6px 8px;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 13px;
        }

        .invoice-table td {
            vertical-align: top;
            border: 1px solid #000;
            padding: 8px 8px;
            font-size: 13px;
            line-height: 1.4;
        }

        .section-header {
            font-size: 12px;
            border-bottom: 1px solid #000;
            margin-bottom: 4px;
            padding-bottom: 2px;
        }

        .grey-row {
            border-top: 1px solid #000;
            margin-top: 6px;
            padding-top: 4px;
        }

        .gst-row {
            background-color: #cfceceff;
            padding: 3px 0;
        }

        .w-50 {
            width: 50%;
        }

        .invoice-wrapper {
            border: 2px solid #000;
            /* ✅ full outer border */
            width: 100%;
            margin: 0 auto;
            padding: 8px;
            box-sizing: border-box;
        }

        .invoice-table,
        .invoice-table td,
        .invoice-table th {
            border: 1px solid #000;
            border-collapse: collapse;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
        }



        .grey-row {
            border-top: 1px solid #000;
            margin-top: 4px;
            padding: 4px;
        }

        @media print {

            body,
            table,
            th,
            td {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            @page {
                size: A4;
                margin: 10mm;
            }
        }
    </style>
</head>

<body>

    <div class="invoice-title gst-row">
        <h2> PROFORMA INVOICE</h2>
        <p class="small"></p>
    </div>
    <table>
        <tr>
            <td class="header-left" width="50%">
                <div class="company-box">
                    <div class="company-logo">
                        <img
                            src="{{ 
                    $adminSetting && $adminSetting->logo
                        ? asset('uploads/settings/' . $adminSetting->logo)
                        : asset('uploads/default-logo.png')
                }}"
                            width="90"
                            height="90"
                            style="object-fit:contain;"
                            alt="Company Logo">
                    </div>

                    <div>
                        <h3><strong>{{ $c->name ?? 'Company Name' }}</strong></h3>
                        <p class="small">
                            {{ $c->address ?? 'Company Address' }} <br>
                            Phone: {{ $c->phone_no ?? '-' }} <br>
                            E-mail: {{ $c->email_id ?? '-' }}
                        </p>
                    </div>
                </div>
            </td>

            <td width="50%">
                <table style="width:100%; border-collapse: collapse; font-size:12px;">
                    <tr>

                        @php
                        $companyName = Auth::user()->name ?? 'AD';
                        $words = explode(' ', trim($companyName));
                        $adminCode = '';

                        foreach($words as $w){
                        if(!empty($w)){
                        $adminCode .= strtoupper(substr($w, 0, 1));
                        }
                        }

                        $adminCode = substr($adminCode, 0, 2);
                        @endphp

                        <td><strong>Invoice No. :</strong></td>

                        <td>{{ $adminCode . $invoice->invoice_no }}</td>
                        <td><strong>Date :</strong></td>
                        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date ?? now())->format('d-M-Y') ??''}}</td>
                    </tr>
                    <tr>
                        <td><strong>Our Ch.No. :</strong></td>
                        <td>{{ $invoice->our_ch_no ?? '-' }}</td>
                        <td><strong>Date :</strong></td>
                        <td>{{ $invoice->our_ch_date ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Y.Ch.No. :</strong></td>
                        <td>{{ $invoice->y_ch_no ?? '-' }}</td>
                        <td><strong>Date :</strong></td>
                        <td>{{ $invoice->y_ch_date ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>P.O.No. :</strong></td>
                        <td>{{ $invoice->p_o_no ?? '-' }}</td>
                        <td><strong>Date :</strong></td>
                        <td>{{ $invoice->p_o_date ?? '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <p class="invoice-subtitle"> Manufacturing of Press Components Tools, Dies, Moulds, Patterns & All Types of VMC Machining Works. </p>
    <table class="header-table">
        <tr>
            <td width="50%">
                GST No: {{ $adminSetting->gst_no ?? 'N/A' }} |
                Date: {{ $adminSetting && $adminSetting->date ? \Carbon\Carbon::parse($adminSetting->date)->format('d-m-Y') : 'N/A' }}
            </td>

            <td width="50%">
                MSME No: {{ $adminSetting->udyam_no ?? 'N/A' }}
            </td>
        </tr>

    </table>

    <table class="invoice-table">
        <tr>

            <td class="w-50">
                <div class="section-header">Consignee</div>
                <div><strong>{{ $invoice->customer->name ?? 'N/A' }}</strong></div>
                <div>{{ $invoice->customer->address ?? '' }}</div>

                <div class="grey-row">
                    <div><span class="label">Kind Attn: </span> <span class=" "><strong>{{ $invoice->customer->contact_person ?? 'N/A' }}</strong></span></div>
                    <div><span class="label">Contact No.: </span> <span class="value">{{ $invoice->customer->phone_no ?? 'N/A' }}</span></div>
                    <div class="gst-row"><span class="label">GST:</span> <span class="value"><strong>{{ $invoice->customer->gst_no ?? 'N/A' }}</strong></span></div>
                </div>
            </td>


            <td class="w-50">
                <div class="section-header">Buyer (If other than Consignee)</div>
                <div><strong>{{ $invoice->customer->name ?? 'N/A' }}</strong></div>
                <div>{{ $invoice->customer->address ?? '' }}</div>

                <div class="grey-row">
                    <div><span class="label">Kind Attn: </span> <span class="value"><strong>{{ $invoice->customer->contact_person ?? 'N/A' }}</strong></span></div>
                    <div><span class="label">Contact No.: </span> <span class="value">{{ $invoice->customer->phone_no ?? 'N/A' }}</span></div>
                    <div class="gst-row"><span class="label">GST:</span> <span class="value"><strong>{{ $invoice->customer->gst_no ?? 'N/A' }}</strong></span></div>
                </div>
            </td>
        </tr>
    </table>

    <table style="margin-top:5px;">
        <thead>
            <tr class="center bold">
                <th width="5%">Sr.</th>
                <th width="12%">W.O. No.</th>
                <th width="30%">Particulars</th>
                <th width="10%">HSN/SAC</th>
                <th width="5%">Qty</th>
                <th width="10%">Rate</th>
                <th width="10%">Amount (Rs.)</th>
            </tr>
        </thead>

        <tbody> @foreach($invoice->items as $i => $item) <tr>


                <td class="center">{{ $i + 1 }}</td>
                <td class="center">
                    {{ $item->workOrder->project->project_no }}
                </td>

                <td>{{ $item->part_name ?? '' }}</td>
                <td class="center">{{ $item->hsn_code ?? '' }}</td>
                <td class="center">{{ $item->qty ?? 1 }}</td>
                <td class="right">{{ number_format($item->rate ?? 0, 2) }}</td>
                <td class="right">{{ number_format($item->amount ?? 0, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>@php
    use App\Models\Hsncode;

    $hsn_code = $invoice->items->value('hsn_code');
    $hsnCode = $hsn_code ?? null;
    $hsnMaster = $hsnCode ? Hsncode::where('hsn_code', $hsnCode)->first() : null;

    $cgst_rate = $hsnMaster->cgst ?? 0;
    $sgst_rate = $hsnMaster->sgst ?? 0;
    $igst_rate = $hsnMaster->igst ?? 0;

    if (($cgst_rate > 0 || $sgst_rate > 0) && $igst_rate > 0) {
    $igst_rate = 0;
    }

    if ($igst_rate > 0) {
    $cgst_rate = 0;
    $sgst_rate = 0;
    $cgst_total = 0;
    $sgst_total = 0;

    $igst_total = ($invoice->sub_total * $igst_rate) / 100;
    $total_tax_percent = $igst_rate;
    $total_tax_amount = $igst_total;
    } else {
    $igst_rate = 0;
    $cgst_total = ($invoice->sub_total * $cgst_rate) / 100;
    $sgst_total = ($invoice->sub_total * $sgst_rate) / 100;
    $igst_total = 0;

    $total_tax_percent = $cgst_rate + $sgst_rate;
    $total_tax_amount = $cgst_total + $sgst_total;
    }

    $grand_total = $invoice->sub_total + $total_tax_amount;

    @endphp


    <table style="margin-top:10px; width:100%; border-collapse: collapse;">
        <tr>
            <td width="68%" rowspan="6" class="declaration-box" style="padding:12px; font-size:13px; line-height:1.4; vertical-align:top; border:1px solid #000;">
                <p><strong>Declaration:</strong> {{ $invoice->declaration ?? 'N/A' }}</p>
                <hr>
                <p class="gst-row"><strong>Note:</strong> {{ $invoice->note ?? 'N/A' }}</p>
                <hr>
                <p class="gst-row"><strong>Bank Details:</strong><br>{!! nl2br($invoice->bank_details ?: 'N/A') !!}</p>
                <hr>
                <p style="margin:2px 0;"><strong>Amount in Words:</strong> {{ $invoice->amount_in_words ?: 'N/A' }}</p>
            </td>
            <td colspan="2" class="right bold">Sub Total</td>
            <td class="right bold">{{ number_format($invoice->sub_total ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td class="right bold">Tax Type</td>
            <td class="right bold">%</td>
            <td class="right bold">Amount</td>
        </tr>
        <tr>
            <td class="right">CGST</td>
            <td class="right">{{ $cgst_rate }}%</td>
            <td class="right">{{ number_format($cgst_total, 2) }}</td>
        </tr>
        <tr>
            <td class="right">SGST</td>
            <td class="right">{{ $sgst_rate }}%</td>
            <td class="right">{{ number_format($sgst_total, 2) }}</td>
        </tr>
        <tr>
            <td class="right gst-row">Total Tax Payable</td>
            <td class="right gst-row">{{ $igst_rate }}%</td>
            <td class="right gst-row">{{ number_format($igst_total, 2) }}</td>
        </tr>
        <tr class="bold">
            <td class="right bold">Grand Total</td>
            <td colspan="2" class="right bold">{{ number_format($grand_total, 2) }}</td>
        </tr>

    </table>
    @if(!empty($adminSetting->footer_note))
    <div style="margin-top:30px; font-size:13px; padding-top:10px; text-align:left;">
        <strong>Note:</strong> {{ $adminSetting->footer_note }}
    </div>
    @else
    <div style="margin-top:30px; font-size:13px; border-top:1px solid #000; padding-top:10px; text-align:left;">
        <strong>Note:</strong>
    </div>
    @endif

    <div class="company-stamp" style="text-align:right; margin-top:10px;">
        @if(isset($adminSetting) && $adminSetting->stamp)
        <img src="{{ asset('uploads/settings/' . $adminSetting->stamp) }}" width="100" height="100" alt="Company Stamp">
        @endif
    </div>

    <script>
        window.onload = function() {
            window.print();
            window.onafterprint = function() {
                window.location.href = "{{ route('proforma.index') }}";
            };
        };
    </script>
</body>

</html>