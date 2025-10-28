<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice PE{{ $invoice->invoice_no ?? 'XXXX' }}</title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

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
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            margin: 8px 0;
            line-height: 1.4;
        }

        .declaration-box {
            border: 1px solid #000;
            padding: 8px;
            font-size: 12px;
            line-height: 1.4;
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

    <div class="invoice-title">
        <h2>GST TAX INVOICE</h2>
        <p class="small">(U/s 31 of CGST ACT & SGST ACT R.W. Sec 20 of IGST Act)</p>
    </div>

    <table>
        <tr>
            <td class="header-left" width="50%">
                <div class="company-box">
                    <div class="company-logo">
                        @if(isset($c) && $c->logo)
                        <img src="{{ asset($c->logo) }}" width="90" height="90">
                        @else
                        <img src="{{ asset('uploads/default-logo.png') }}" width="90" height="90">
                        @endif
                    </div>
                    <div>
                        <h3><strong>{{ $c->name ?? 'Company Name' }}</strong></h3>
                        <p class="small">
                            {{ $c->address ?? 'Company Address' }}<br>
                            Phone: {{ $c->phone_no ?? '-' }}<br>
                            E-mail: {{ $c->email_id ?? '-' }}
                        </p>
                    </div>
                </div>
            </td>

            <td width="50%">
                <table style="width:100%; border-collapse: collapse; font-size:12px;">
                    <tr>
                        <td><strong>Invoice No. :</strong></td>
                        <td><strong>{{ 'PE' . preg_replace('/[^0-9]/', '', $invoice->invoice_no ?? '0000') }}</strong></td>
                        <td><strong>Date :</strong></td>
                        <td>{{ \Carbon\Carbon::parse($invoice->invoice_date ?? now())->format('d-M-Y') }}</td>
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

    <p class="invoice-subtitle">
        Manufacturing of Press Components Tools, Dies, Moulds, Patterns & All Types of VMC Machining Works.
    </p>

    <table>
        <tr>
            <td width="50%" class="center bold" style="font-size:15px;">
                GST No: 27AAMFP5025G1Z6 | Date: 26-03-2017
            </td>
            <td width="50%" class="center bold" style="font-size:15px;">
                MSME No: UDYAM-MH-26-0589771
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td width="50%">
                <strong>Consignee:</strong><br>
                {{ $invoice->buyer_name ?? 'N/A' }}<br>
                {{ $invoice->buyer_address ?? '' }}<br>
                Customer Name: {{ $invoice->customer->name ?? 'N/A' }}<br>
                Contact: {{ $invoice->customer->phone_no ?? 'N/A' }}<br>
                GST: {{ $invoice->customer->gst_no ?? 'N/A' }}
            </td>

            <td width="50%">
                <strong>Buyer (if other than Consignee):</strong><br>
                {{ $invoice->buyer2_name ?? '-' }}<br>
                {{ $invoice->buyer2_address ?? '' }}<br>
                Customer Name: {{ $invoice->customer->name ?? 'N/A' }}<br>
                Contact: {{ $invoice->customer->phone_no ?? 'N/A' }}<br>
                GST: {{ $invoice->customer->gst_no ?? 'N/A' }}
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
        <tbody>
            @foreach($invoice->items as $i => $item)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td class="center">{{ $item->id ?? '-' }}</td>
                <td>{{ $item->part_name ?? '' }}</td>
                <td class="center">{{ $item->hsn_code ?? '' }}</td>
                <td class="center">{{ $item->qty ?? 1 }}</td>
                <td class="right">{{ number_format($item->rate ?? 0, 2) }}</td>
                <td class="right">{{ number_format($item->amount ?? 0, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @php
    use App\Models\Hsncode;

    $firstItem = $invoice->items->first();
    $hsnCode = $firstItem->hsn_code ?? null;

    $hsnMaster = $hsnCode ? Hsncode::where('hsn_code', $hsnCode)->first() : null;

    $cgst_rate = $hsnMaster->cgst ?? 0;
    $sgst_rate = $hsnMaster->sgst ?? 0;

    // IGST rate = CGST + SGST (rate-wise)
    $igst_rate = $cgst_rate + $sgst_rate;

    // Calculate total tax amounts
    $cgst_total = $invoice->items->sum('cgst') ?? 0;
    $sgst_total = $invoice->items->sum('sgst') ?? 0;

    // IGST total = (CGST total + SGST total)
    $igst_total = $cgst_total + $sgst_total;

    // Total tax %
    $totalTaxRate = $cgst_rate + $sgst_rate + $igst_rate;

    // Total tax amount
    $totalTaxAmt = $cgst_total + $sgst_total + $igst_total;
    @endphp


    <table style="margin-top:10px; width:100%; border-collapse: collapse;">
        <tr>
            <td width="68%" rowspan="6" class="declaration-box" style="padding:12px; font-size:13px; line-height:1.4; vertical-align:top; border:1px solid #000;">
                <p><strong>Declaration:</strong> {{ $invoice->declaration ?? '' }}</p>
                <hr>
                <p><strong>Note:</strong> {{ $invoice->note ?? '' }}</p>
                <hr>
                <p><strong>Bank Details:</strong><br>{!! nl2br($invoice->bank_details ?? '') !!}</p>
                <hr>
                <p style="margin:2px 0;"><strong>Amount in Words:</strong> {{ $invoice->amount_in_words ?? '' }}</p>
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
            <td class="right">IGST</td>
            <td class="right">{{ $igst_rate }}%</td>
            <td class="right">{{ number_format($igst_total, 2) }}</td>
        </tr>
        <tr class="bold">
            <td class="right bold">Grand Total</td>
            <td colspan="2" class="right bold">{{ number_format($invoice->grand_total ?? 0, 2) }}</td>
        </tr>
    </table>

    <table style="margin-top:20px;">
        <tr>
            <td width="50%" class="center">Receiver’s Signature</td>
            <td width="50%" class="center">
                For <strong>Precise Engineering</strong><br>Authorised Signatory
            </td>
        </tr>
    </table>

    <script>
        window.onload = function() {
            window.print();
            window.onafterprint = function() {
                window.location.href = "{{ route('invoice.index') }}";
            };
        };
    </script>

</body>

</html>