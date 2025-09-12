<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $invoice->invoice_no }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 4px; font-size: 12px; }
        .no-border { border: none !important; }
        .center { text-align: center; }
        .left { text-align: left; }
        .right { text-align: right; }
        h2,h3,h4 { margin: 2px; padding: 0; }
    </style>
</head>
<body>

    {{-- Top GST Invoice Heading --}}
    <table width="100%">
        <tr>
            <td class="center" colspan="2" style="font-size:14px; font-weight:bold;">
                GST TAX INVOICE <br>
                <small>(U/s 31 of CGST ACT & SGST ACT R.W. Sec 20 of IGST Act)</small>
            </td>
        </tr>
    </table>

    {{-- Company Header with Invoice Info --}}
    <table width="100%">
        <tr>
            {{-- Company Info --}}
            <td width="60%" class="left">
                <table class="no-border" width="100%">
                    <tr>
                        <td width="20%">
                            @if($invoice->client && $invoice->client->logo)
                                <img src="{{ asset($invoice->client->logo) }}" width="80">
                            @endif
                        </td>
                        <td class="left">
                            <h3>{{ $invoice->client->name ?? '' }}</h3>
                            <p>Address :{{ $invoice->client->address ?? '' }}</p>
                            <p>Cell: {{ $invoice->client->phone_no ?? '' }}</p>
                            <p>Email: {{ $invoice->client->email_id ?? '' }}</p>
                            <p><b>Manufacturing of Press Components tools, Dies, Moulds, Patterns & All Types of VMC Machining Works.</b></p>
                        </td>
                    </tr>
                </table>
            </td>

            {{-- Invoice Details --}}
            <td width="40%" class="left">
                <table width="100%">
                    <tr><td>Invoice No.</td><td>{{ $invoice->invoice_no ?? '' }}</td></tr>
                    <tr><td>Date</td><td>{{ $invoice->invoice_date ?? '' }}</td></tr>
                    <tr><td>Our Ch.No.</td><td>{{ $invoice->our_ch_no ?? '' }}</td></tr>
                    <tr><td>Date</td><td>{{ $invoice->our_ch_no_date ?? '' }}</td></tr>
                    <tr><td>Y.Ch.No.</td><td>{{ $invoice->y_ch_no ?? '' }}</td></tr>
                    <tr><td>Date</td><td>{{ $invoice->y_ch_no_date ?? '' }}</td></tr>
                    <tr><td>P.O.No.</td><td>{{ $invoice->p_o_no ?? '' }}</td></tr>
                    <tr><td>Date</td><td>{{ $invoice->p_o_no_date ?? '' }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- GST + MSME Footer Bar --}}
    <table width="100%">
        <tr>
            <td class="center">
                GST No. : {{ $invoice->gst_no ?? '' }}
                &nbsp;&nbsp;&nbsp; Date : {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}
            </td>
            <td class="center">
                MSME No : {{ $invoice->msme_no ?? '' }}
            </td>
        </tr>
    </table>
             

    {{-- Buyer & Consignee --}}
    <table width="100%">
        <tr>
            <td width="50%" class="left">
                <strong>Buyer:</strong><br>
                {{ $invoice->buyer_name ?? '' }}<br>
                {{ $invoice->buyer_address ?? '' }}<br>
                GST: {{ $invoice->ki_gst ?? '' }}
            </td>
            <td width="50%" class="left">
                <strong>Consignee:</strong><br>
                {{ $invoice->consignee_name ?? '' }}<br>
                {{ $invoice->consignee_address ?? '' }}<br>
                GST: {{ $invoice->kind_gst ?? '' }}
            </td>
        </tr>
    </table>

    {{-- Items Table (Single Item) --}}
    <table width="100%">
        <tr>
            <th>Sr</th>
            <th>Particulars</th>
            <th>HSN/SAC</th>
            <th>Qty</th>
            <th>Rate</th>
            <th>Amount</th>
            <th>Hrs/Job</th>
            <th>Cost</th>
        </tr>
        <tr>
            <td>1</td>
            <td class="left">{{ $invoice->description ?? '' }}</td>
            <td>{{ $invoice->hsn_code ?? '' }}</td>
            <td>{{ $invoice->qty ?? '' }}</td>
            <td class="right">{{ number_format((float)($invoice->rate ?? 0), 2) }}</td>
            <td class="right">{{ number_format((float)($invoice->amount ?? 0), 2) }}</td>
            <td>{{ $invoice->hrs_per_job ?? '' }}</td>
            <td class="right">{{ number_format((float)($invoice->cost ?? 0), 2) }}</td>
        </tr>
    </table>

    {{-- Totals --}}
    <table width="100%">
        <tr>
            <td class="no-border left" width="60%">
                <strong>In Words:</strong> {{ $invoice->amount_in_words ?? '' }}
            </td>
            <td width="40%">
                <table width="100%">
                    <tr><td>Sub Total</td><td class="right">{{ number_format((float)($invoice->sub_total ?? 0), 2) }}</td></tr>
                    <tr><td>CGST</td><td class="right">{{ number_format((float)($invoice->cgst ?? 0), 2) }}</td></tr>
                    <tr><td>SGST</td><td class="right">{{ number_format((float)($invoice->sgst ?? 0), 2) }}</td></tr>
                    <tr><td>IGST</td><td class="right">{{ number_format((float)($invoice->igst ?? 0), 2) }}</td></tr>
                    <tr><td><strong>Total</strong></td><td class="right"><strong>{{ number_format((float)($invoice->grand_total ?? 0), 2) }}</strong></td></tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Declaration & Notes --}}
    <p><strong>Declaration:</strong> {{ $invoice->declaration ?? '' }}</p>
    <p><strong>Note:</strong> {{ $invoice->note ?? '' }}</p>
    <p><strong>Bank Details:</strong><br>{!! nl2br($invoice->bank_details ?? '') !!}</p>

    {{-- Signatures --}}
    <table width="100%">
        <tr>
            <td class="center">Receiver's Signature</td>
            <td class="center">For {{ $invoice->client->name ?? '' }}</td>
        </tr>
    </table>

    <script> window.print(); </script>
</body>
</html>
