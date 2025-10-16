<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_no ?? 'XXXX' }}</title>
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
            background: transparent;
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
            padding: 0;
        }

        h3 {
            margin: 4px 0 0 0;
        }

        .invoice-title {
            text-align: center;
            margin: 4px 0;
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

    {{-- Title --}}
    <div class="invoice-title">
        <h2>GST TAX INVOICE</h2>
        <p class="small">(U/s 31 of CGST ACT & SGST ACT R.W. Sec 20 of IGST Act)</p>
    </div>

    {{-- Header --}}
    <table>
        <tr>
            {{-- Company Info --}}
            <td class="header-left" width="50%">
                <div class="company-box">
                    <div class="company-logo">
                        @if(isset($c) && $c->logo)
                        <img src="{{ asset($c->logo) }}" alt="Client Logo" width="90" height="90">
                        @else
                        <img src="{{ asset('uploads/default-logo.png') }}" alt="Default Logo" width="90" height="90">
                        @endif
                    </div>
                    <div>
                        <h3><strong>{{ $c->name ?? 'Company Name' }}</strong></h3>
                        <p class="small">
                            {{ $c->address ?? 'Company Address' }}<br>
                            Phone: {{ $c->phone_no ?? '-' }}<br>
                            E-mail: {{ $c->email_id ?? '-' }}<br>
                            GSTIN: {{ $c->gst_no ?? '-' }}
                        </p>
                    </div>
                </div>
            </td>


            {{-- Invoice Info --}}
            <td width="50%">
                <table style="width:100%; border-collapse: collapse;">
                    <tr>
                        <td style="width:30%;"><strong>Invoice No. :</strong></td>
                        <td style="width:30%;">{{ $invoice->invoice_no ?? 'PE0000' }}</td>
                        <td style="width:20%;"><strong>Date :</strong></td>
                        <td style="width:20%;">{{ \Carbon\Carbon::parse($invoice->invoice_date ?? now())->format('d-M-Y') }}</td>
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

    {{-- Subtitle --}}
    <p class="center small bold" style="margin-top:4px;">
        Manufacturing of Press Components Tools, Dies, Moulds, Patterns & All Types of VMC Machining Works.
    </p>

    {{-- GST and MSME Line --}}
    <table>
        <tr>
            <td width="50%">
                <strong>GST No:</strong> 27AAMFP5025G1Z6<br>
                <strong>Date:</strong> 26-03-2017
            </td>
            <td width="50%" class="bold">
                MSME No: UDYAM-MH-26-0589771
            </td>
        </tr>
    </table>

    {{-- Consignee / Buyer --}}
    <table>
        <tr>
            <td width="50%">
                <strong>Consignee:</strong><br>
                {{ $invoice->buyer_name ?? 'N/A' }}<br>
                {{ $invoice->buyer_address ?? '' }}<br>
                Kind Attn: {{ $invoice->kind_attn ?? 'N/A' }}<br>
                Contact: {{ $invoice->contact ?? '-' }}<br>
                GST: {{ $invoice->buyer_gst ?? 'N/A' }}
            </td>
            <td width="50%">
                <strong>Buyer (if other than Consignee):</strong><br>
                {{ $invoice->buyer2_name ?? '-' }}<br>
                {{ $invoice->buyer2_address ?? '' }}<br>
                Kind Attn: {{ $invoice->buyer2_kind_attn ?? '-' }}<br>
                Contact: {{ $invoice->buyer2_contact ?? '-' }}<br>
                GST: {{ $invoice->buyer2_gst ?? '-' }}
            </td>
        </tr>
    </table>

    {{-- Item Table --}}
    <table style="margin-top:5px;">
        <thead>
            <tr class="center bold">
                <th width="5%">Sr.</th>
                <th width="12%">W.O. No.</th>
                <th width="25%">Particulars</th>
                <th width="10%">HSN/SAC</th>
                <th width="5%">Qty</th>
                <th width="8%">Rate</th>
                <th width="8%">Amount (Rs.)</th>
                <th width="8%">Material Rate</th>
                <th width="5%">Hrs</th>
                <th width="5%">ADJ</th>
                <th width="5%">EST</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $i => $item)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td class="center">{{ $item->wo_no ?? '-' }}</td>
                <td> {{ $item->part_name ?? '' }}</td>
                <td class="center">{{ $item->hsn_code ?? '' }}</td>
                <td class="center">{{ $item->qty ?? '' }}</td>
                <td class="right">{{ isset($item->rate) ? number_format($item->rate,2) : '' }}</td>
                <td class="right">{{ isset($item->amount) ? number_format($item->amount,2) : '' }}</td>
                <td class="right">{{ isset($item->material_rate) ? number_format($item->material_rate,2) : '' }}</td>
                <td class="center">{{ $item->hrs ?? '' }}</td>
                <td class="center">{{ $item->adj ?? '' }}</td>
                <td class="center">{{ $item->est ?? '' }}</td>
            </tr>
            @endforeach

            @for($j = count($invoice->items); $j < 10; $j++)
                <tr>
                <td class="center">{{ $j + 1 }}</td>
                <td colspan="10">&nbsp;</td>
                </tr>
                @endfor
        </tbody>
    </table>

    {{-- Totals --}}
    <table style="margin-top:5px;">
        <tr>
            <td width="60%" rowspan="6" class="no-border" style="vertical-align:top;">
                <p><strong>Declaration:</strong> We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</p>
                <p><strong>Reverse charge applicable:</strong> No</p>
                <p><strong>Note:</strong> Interest will be charged @ 24% p.a. on overdue unpaid bills after 45 days.</p>
                <p><strong>Bank Details:</strong><br>
                    HDFC BANK, CHINCHWAD BRANCH<br>
                    A/C No: 0522102000003366<br>
                    IFSC: HDFC0000522
                </p>
                <p><strong>Amount in Words:</strong> {{ $invoice->amount_in_words ?? '' }}</p>
            </td>
            <td>Sub Total</td>
            <td class="right">{{ number_format($invoice->sub_total ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>CGST ({{ $invoice->cgst_rate   }}%)</td>
            <td class="right">{{ number_format($invoice->cgst ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>SGST ({{ $invoice->sgst_rate   }}%)</td>
            <td class="right">{{ number_format($invoice->sgst ?? 0, 2) }}</td>
        </tr>
        <!-- <tr><td>IGST</td><td class="right">{{ number_format($invoice->igst ?? 0, 2) }}</td></tr> -->
        <tr>
            <td>Total Tax Payable</td>
            <td class="right">{{ number_format(($invoice->cgst + $invoice->sgst + $invoice->igst) ?? 0, 2) }}</td>
        </tr>
        <tr class="bold">
            <td>Grand Total</td>
            <td class="right">{{ number_format($invoice->grand_total ?? 0, 2) }}</td>
        </tr>
    </table>

    {{-- Signatures --}}
    <table style="margin-top:20px;">
        <tr>
            <td width="50%" class="center">Receiver’s Signature</td>
            <td width="50%" class="center">
                For <strong>Precise Engineering</strong><br>
                Authorised Signatory
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