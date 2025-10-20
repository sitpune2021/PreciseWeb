<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice PE{{ $invoice->invoice_no ?? 'XXXX' }}</title>
    <style>
        @page { size: A4; margin: 10mm; }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 0;
        }

        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px 6px; vertical-align: top; }

        .no-border { border: none !important; }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }

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

        .small { font-size: 11px; }

        h2 { margin: 0; padding: 0; }
        h3 { margin: 4px 0 0 0; }

        .invoice-title {
            text-align: center;
            margin: 4px 0;
        }

        /* Subtitle */
        .invoice-subtitle {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            margin-top: 8px;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        /* Declaration Box */
        .declaration-box {
            border: 1px solid #000;
            padding: 8px;
            font-size: 12px;
            line-height: 1.4;
            /* background-color: #f9f9f9; */
        }

        .declaration-box p {
            margin: 4px 0;
        }

        .declaration-box strong {
            font-weight: bold;
        }

        @media print {
            body, table, th, td {
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
    <table style="width:100%; border-collapse: collapse; font-size:12px;">

        <tr>
            <td style="width:20%;"><strong>Invoice No. :</strong></td>
            <td style="width:30%;"><strong>{{ 'PE' . preg_replace('/[^0-9]/', '', $invoice->invoice_no ?? '0000') }}</strong></td>
            <td style="width:20%;"><strong>Date :</strong></td>
            <td style="width:30%;">{{ \Carbon\Carbon::parse($invoice->invoice_date ?? now())->format('d-M-Y') }}</td>
        </tr>
        
        

        <tr>
            <td style="width:20%;"><strong>Our Ch.No. :</strong></td>
            <td style="width:30%;">{{ $invoice->our_ch_no ?? '-' }}</td>
            <td style="width:20%;"><strong>Date :</strong></td>
            <td style="width:30%;">{{ $invoice->our_ch_date ?? '-' }}</td>
        </tr>

    
        <tr>
            <td style="width:20%;"><strong>Y.Ch.No. :</strong></td>
            <td style="width:30%;">{{ $invoice->y_ch_no ?? '-' }}</td>
            <td style="width:20%;"><strong>Date :</strong></td>
            <td style="width:30%;">{{ $invoice->y_ch_date ?? '-' }}</td>
        </tr>


        <tr>
            <td style="width:20%;"><strong>P.O.No. :</strong></td>
            <td style="width:30%;">{{ $invoice->p_o_no ?? '-' }}</td>
            <td style="width:20%;"><strong>Date :</strong></td>
            <td style="width:30%;">{{ $invoice->p_o_date ?? '-' }}</td>
        </tr>
    </table>
</td>
</tr>
    </table>

    {{-- Subtitle --}}
    <p class="invoice-subtitle">
        Manufacturing of Press Components Tools, Dies, Moulds, Patterns & All Types of VMC Machining Works.
    </p>

    {{-- GST and MSME Line --}}
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
                <td class="center">{{ $item->wo_no ?? '-' }}</td>
                <td>{{ $item->part_name ?? '' }}</td>
                <td class="center">{{ $item->hsn_code ?? '' }}</td>
                <td class="center">{{ $item->qty ?? 1 }}</td>
                <td class="right">{{ number_format($item->rate ?? 0, 2) }}</td>
                <td class="right">{{ number_format($item->amount ?? 0, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totals and Declaration --}}
  {{-- Totals and Declaration --}}
{{-- Totals and Declaration --}}
<!-- Totals and Declaration -->
<table style="margin-top:10px; width:100%; border-collapse: collapse;">
    <tr>
        <!-- Declaration Box -->
        <td width="68%" rowspan="6" class="declaration-box" style="padding:12px; font-size:13px; line-height:1.4; vertical-align:top; border:1px solid #000;">
            <p style="margin:2px 0;"><strong>Declaration:</strong> We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</p>
            <p style="margin:2px 0;">Reverse charge applicable: No</p>
            <hr>
            <p style="margin:2px 0;">Note: Interest will be charged @ 24% p.a. on overdue unpaid bills after 45 days.</p>
            <hr>
            <p style="margin:2px 0; font-weight:bold;">
                HDFC BANK, CHINCHWAD BRANCH | A/C No: 0522102000003366 | IFSC: HDFC0000522
            </p>
            <hr>
            <p style="margin:2px 0;">Amount in Words: {{ $invoice->amount_in_words ?? '' }}</p>
        </td>

        <!-- Totals Columns -->
        <td width="17%" class="right" style="padding:6px; font-weight:bold;">Sub Total</td>
        <td width="15%" class="right" style="padding:6px; font-weight:bold;">{{ number_format($invoice->sub_total ?? 0, 2) }}</td>
    </tr>
    <tr>
        <td class="right" style="padding:6px; font-weight:bold;">CGST ({{ $invoice->cgst_rate ?? 9 }}%)</td>
        <td class="right" style="padding:6px; font-weight:bold;">{{ number_format($invoice->cgst ?? 0, 2) }}</td>
    </tr>
    <tr>
        <td class="right" style="padding:6px; font-weight:bold;">SGST ({{ $invoice->sgst_rate ?? 9 }}%)</td>
        <td class="right" style="padding:6px; font-weight:bold;">{{ number_format($invoice->sgst ?? 0, 2) }}</td>
    </tr>
    <tr>
        <td class="right" style="padding:6px; font-weight:bold;">IGST</td>
        <td class="right" style="padding:6px; font-weight:bold;">{{ number_format($invoice->igst ?? 0, 2) }}</td>
    </tr>
    <tr>
        <td class="right" style="padding:6px; font-weight:bold;">Total Tax Payable</td>
        <td class="right" style="padding:6px; font-weight:bold;">{{ number_format(($invoice->cgst + $invoice->sgst + $invoice->igst) ?? 0, 2) }}</td>
    </tr>
    <tr class="bold">
        <td class="right" style="padding:6px; font-weight:bold;">Grand Total</td>
        <td class="right" style="padding:6px; font-weight:bold;">{{ number_format($invoice->grand_total ?? 0, 2) }}</td>
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