<!DOCTYPE html>
<html>

<head>
    <title>Quotation Print</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 3px;
            vertical-align: middle;
        }

        th {
            font-weight: bold;
            text-align: center;
        }

        .left {
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
        }

        .gray {
            background: #e6e6e6;
        }

        .no-border td {
            border: none;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 8mm;
            }
        }
    </style>
</head>

<body>
    <!-- ================= TITLE ================= -->
    <table>
        <tr>
            <td class="title">QUOTATION – 2025–2026</td>
        </tr>
    </table>

    <!-- ================= HEADER ================= -->
    <table>
        <tr>
            <td style="width:35%">
                <table class="no-border">
                    <tr>
                        <td style="width:60px">
                            @if($client && $client->logo)
                            <img src="{{ asset($client->logo) }}" height="55">
                            @endif
                        </td>
                        <td class="left">
                            <strong>{{ $client->name }}</strong><br>
                            {{ $client->address }}<br>
                            Email : {{ $client->email_id }}<br>
                            Cell : {{ $client->phone_no }}
                        </td>
                    </tr>
                </table>
            </td>

            <td style="width:65%">
                <table>
                    <tr>
                        <td class="bold">Quotation No.</td>
                        <td>{{ $quotation->quotation_no }}</td>
                        <td class="bold">Project Name</td>
                        <td>{{ $quotation->project_name }}</td>
                    </tr>
                    <tr>
                        <td class="bold">Date</td>
                        <td>{{ \Carbon\Carbon::parse($quotation->date)->format('d-M-Y') }}</td>
                        <td class="bold">Customer Name</td>
                        <td>{{ $quotation->customer->name }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>



    <!-- ================= MAIN TABLE ================= -->
    <table>

        <thead>
            <tr>
                <th rowspan="2" width="30px">Sl<br>No</th>
                <th rowspan="2" width="100px">Description</th>

                <th colspan="4">Raw Material & Size</th>

                <th rowspan="2">Qty<br>Kg</th>
                <th rowspan="2">MATL</th>
                <th rowspan="2">Rate<br>Kg</th>
                <th rowspan="2">QTY</th>
                <th rowspan="2">MATL<br>Cost</th>

                <th colspan="5">RS / SQ.CM / MIN</th>
                <th colspan="2">VMC</th>
                <th colspan="2">EDM DRILL</th>

                <th rowspan="2">H&amp;T</th>
                <th rowspan="2">WIRECUT</th>
                <th rowspan="2">Machining<br>Cost</th>
            </tr>

            <tr>
                <th>DIA</th>
                <th>Length</th>
                <th>Width</th>
                <th>Height</th>

                <th>LATH</th>
                <th>MG</th>
                <th>RG</th>
                <th>CG</th>
                <th>SG</th>

                <th>Soft</th>
                <th>Hard</th>

                <th>Qty</th>
                <th>Per Hole</th>
            </tr>
        </thead>

        <tbody>
            @foreach($quotation->items as $i=>$item)
            <tr class="{{ $i % 2 ? 'gray' : '' }}">
                <td class="center">{{ $i+1 }}</td>
                <td class="left">{{ $item->description }}</td>

                <td class="center">{{ (int)$item->dia }}</td>
                <td class="center">{{ (int)$item->length }}</td>
                <td class="center">{{ (int)$item->width }}</td>
                <td class="center">{{ (int)$item->height }}</td>

                <td class="right">{{ (int)$item->qty_in_kg }}</td>
                <td class="center">MS</td>
                <td class="right">{{ (int)$item->material_rate }}</td>
                <td class="center">{{ (int)$item->qty }}</td>
                <td class="right">Rs. {{ number_format($item->material_cost,0) }}</td>

                <td class="right">{{ (int)$item->lathe }}</td>
                <td class="right">{{ (int)$item->mg }}</td>
                <td class="right">{{ (int)$item->rg }}</td>
                <td class="right">{{ (int)$item->cg }}</td>
                <td class="right">{{ (int)$item->sg }}</td>

                <td class="right">{{ (int)$item->vmc_soft }}</td>
                <td class="right">{{ (int)$item->vmc_hard }}</td>

                <td class="center">{{ (int)$item->edm_qty }}</td>
                <td class="right">{{ (int)$item->edm_hole }}</td>

                <td class="right">{{ number_format($item->ht,0) }}</td>
                <td class="right">{{ number_format($item->wirecut,0) }}</td>

                <td class="right bold">Rs. {{ number_format($item->machining_cost,0) }}</td>
            </tr>
            @endforeach
        </tbody>

    </table>

    <!-- ================= TERMS + TOTAL ================= -->
    <table>
        <tr>
            <td style="width:70%" class="left bold">
                Terms & Conditions :<br>
                All Taxes Extra.<br>
                Transportation at your end.<br>
                Lead time 1–2 weeks after PO.<br>
                50% payment against PO, 40% after trial, 10% against delivery.<br><br>
                <strong>GST No :</strong> 27AAMFP5025G1Z6
            </td>

            <td style="width:30%">
                <table>
                    <tr>
                        <td class="bold right">Total Manufacturing Cost</td>
                        <td class="right">Rs. {{ number_format($quotation->total_manufacturing_cos,0) }}</td>
                    </tr>
                    <tr>
                        <td class="bold right">Profit</td>
                        <td class="right">Rs. {{ number_format($quotation->profit,0) }}</td>
                    </tr>
                    <tr>
                        <td class="bold right">Overhead</td>
                        <td class="right">Rs. {{ number_format($quotation->overhead,0) }}</td>
                    </tr>
                    <tr>
                        <td class="bold right">Total Tool Cost</td>
                        <td class="right bold">
                            Rs. {{ number_format($quotation->total_manufacturing_cos + $quotation->profit + $quotation->overhead,0) }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>

</body>

</html>