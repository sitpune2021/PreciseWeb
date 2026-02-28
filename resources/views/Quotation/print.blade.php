<!DOCTYPE html>
<html>

<head>
    <title>Quotation Print</title>

    <style>
        body {
            font-family: Calibri, Arial, Helvetica, sans-serif;
            font-size: 9px;
            margin: 0;
            padding: 0;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        /* CELL */
        th,
        td {
            border: 1px solid #000;
            padding: 3px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        /* HEADER */
        th {
            font-weight: bold;
            text-align: center;
        }

        /* HEADER BACKGROUND */
        thead th {
            background: #d9d9d9;
        }

        /* ALIGNMENT */
        .left {
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        /* FONT */
        .bold {
            font-weight: bold;
        }

        /* TITLE */
        .title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            letter-spacing: 1px;
        }

        /* ALTERNATE ROW */
        .gray {
            background: #f2f2f2;
        }

        /* ROW HEIGHT */
        td {
            height: 18px;
        }

        /* REMOVE BORDER */
        .no-border td {
            border: none;
        }

        /* COMPANY BOX */
        .company-box {
            display: flex;
            align-items: flex-start;
        }

        /* LOGO */
        .company-logo {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .company-logo img {
            max-width: 60px;
            max-height: 60px;
            object-fit: contain;
        }

        /* HEADER TABLE */
        .header-table td {
            padding: 4px;
        }

        /* TOTAL SECTION */
        .total-table th {
            background: #d9d9d9;
        }

        .total-table td {
            padding: 5px;
        }

        /* PRINT SETTINGS */
        @media print {

            @page {
                size: A4 landscape;
                margin: 8mm;
            }

            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            table,
            th,
            td {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

        }
    </style>
</head>

<body>
    <!--TITLE-->
    <table>
        <tr>
            <td class="title">
                QUOTATION –
                {{ now()->month >= 4 ? now()->year.'–'.(now()->year+1) : (now()->year-1).'–'.now()->year }}
            </td>

        </tr>
    </table>

    <!--HEADER -->
    <table style="margin-top:5px;">
        <tr>
            <!-- LEFT COMPANY SECTION  -->
            <td style="width:40%; border:1.5px solid #000; vertical-align:top; padding:6px;">

                <table class="no-border" style="width:100%;">
                    <tr>
                        <!-- Logo -->
                        <td style="width:70px; vertical-align:top;">
                            <img src="{{ 
                            $adminSetting && $adminSetting->logo
                                ? asset('uploads/settings/' . $adminSetting->logo)
                                : asset('uploads/default-logo.png')
                        }}"
                                style="max-width:60px; max-height:60px; object-fit:contain;"
                                alt="Company Logo">
                        </td>

                        <!-- Company Details -->
                        <td class="left" style="vertical-align:top; padding-left:5px;">
                            <strong style="font-size:13px;">
                                {{ $client->name }}
                            </strong><br>

                            {{ $client->address }}<br>

                            Email : {{ $client->email_id }}<br>
                            Cell : {{ $client->phone_no }}
                        </td>
                    </tr>
                </table>

            </td>

            <!--  RIGHT QUOTATION INFO  -->
            <td style="width:60%; border:1.5px solid ; padding:0;">

                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td class="bold center" style="width:20%; border:1px solid #000;">Quotation No.</td>
                        <td class="center" style="width:30%; border:1px solid #000;">
                            {{ $quotation->quotation_no }}
                        </td>

                        <td class="bold center" style="width:20%; border:1px solid #000;">Project Name</td>
                        <td class="center" style="width:30%; border:1px solid #000;">
                            {{ $quotation->project_name }}
                        </td>
                    </tr>

                    <tr>
                        <td class="bold center" style="border:1px solid #000;">Date</td>
                        <td class="center" style="border:1px solid #000;">
                            {{ \Carbon\Carbon::parse($quotation->date)->format('d-M-Y') }}
                        </td>

                        <td class="bold center" style="border:1px solid ">Customer Name</td>
                        <td class="center" style="border:1px solid #000;">
                            {{ $quotation->customer->name }}
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>


    <!--MAIN TABLE -->
    <table>

        <thead>
            <tr>
                <th rowspan="2" width="30px">SR<br>No</th>
                <th rowspan="2" width="100px">Description</th>

                <th colspan="4">Raw Material & Size</th>

                <th rowspan="2">Qty IN<br>Kg</th>
                <th rowspan="2" width="40px">MATL</th>
                <th rowspan="2">Rate Per<br>Kg</th>
                <th rowspan="2">QTY</th>
                <th rowspan="2" width="60px">MATL<br>Cost</th>

                <th colspan="5">RS / SQ.CM / MIN</th>
                <th colspan="2">VMC</th>
                <th colspan="2">EDM DRILL</th>

                <th rowspan="2">H&amp;T</th>
                <th rowspan="2">WI CUT</th>
                <th rowspan="2" width="70px">Machining<br>Cost</th>
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
                <td class="center">{{ number_format($item->length,2) }}</td>
                <td class="center">{{ number_format($item->width,2) }}</td>
                <td class="center">{{ number_format($item->height,2) }}</td>

                <td class="right">{{$item->qty_in_kg }}</td>
                <td class="center">MS</td>
                <td class="right">{{ (int)$item->material_rate }}</td>
                <td class="center">{{ (int)$item->qty }}</td>
                <td class="right">Rs. {{ number_format($item->material_cost,0) }}</td>

                <td class="right">{{ (int)$item->lathe }}</td>
                <td class="right">{{ (int)$item->mg }}</td>
                <td class="right">{{ (int)$item->rg }}</td>
                <td class="right">{{ (int)$item->cg }}</td>
                <td class="right">{{ (int)$item->sg }}</td>

                <td class="right">{{ $item->vmc_soft }}</td>
                <td class="right">{{ $item->vmc_hard }}</td>

                <td class="center">{{ (int)$item->edm_qty }}</td>
                <td class="right">{{ (int)$item->edm_hole }}</td>

                <td class="right">{{ number_format($item->ht,0) }}</td>
                <td class="right">{{ number_format($item->wirecut,0) }}</td>

                <td class="right bold">Rs. {{ number_format($item->machining_cost,0) }}</td>
            </tr>
            @endforeach
        </tbody>

    </table>
    <table>

        <tr>
            <td style="width:13%" class="center">EDM Sparking</td>
            <td colspan="22"></td>
        </tr>

        <tr>
            <td class="center">Polishing</td>
            <td colspan="22"></td>
        </tr>

        <tr>
            <td class="center">Texture</td>
            <td colspan="22"></td>
        </tr>

        <tr>
            <td class="center">Blackodising</td>
            <td colspan="22"></td>
        </tr>

        <tr>
            <td class="center">Design</td>
            <td colspan="22"></td>
        </tr>

        <tr>
            <td class="center">Spares</td>
            <td colspan="22"></td>
        </tr>

        <tr>
            <td class="center">Hardware</td>
            <td colspan="22"></td>
        </tr>

        <tr>
            <td class="center">Assembly</td>
            <td colspan="22"></td>
        </tr>

    </table>
    <!--TERMS + TOTAL-->
    <table>
        <tr>
            <td style="width:70%" class="left bold">
                Terms & Conditions :<br>
                All Taxes Extra.<br>
                {{ $quotation->terms_conditions }}
                <br>
                <br>
                <strong>GST No :</strong> 27AAMFP5025G1Z6
            </td>
            <td style="width:30%; vertical-align:top;">

                <table style="border:1.5px solid #000;">

                    <tr>
                        <th style="width:55%;" class="right">Particular</th>
                        <th style="width:15%;" class="center">%</th>
                        <th style="width:30%;" class="right">Amount</th>
                    </tr>

                    <tr>
                        <td class="bold right">Total Manufacturing Cost</td>
                        <td class="center">-</td>
                        <td class="right">
                            Rs. {{ number_format($quotation->total_manufacturing_cos,0) }}
                        </td>
                    </tr>

                    <tr>
                        <td class="bold right">Profit</td>
                        <td class="center bold">
                            {{ $quotation->profit_percent }}%
                        </td>
                        <td class="right">
                            Rs. {{ number_format($quotation->profit,0) }}
                        </td>
                    </tr>

                    <tr>
                        <td class="bold right">Overhead</td>
                        <td class="center bold">
                            {{ $quotation->overhead_percent }}%
                        </td>
                        <td class="right">
                            Rs. {{ number_format($quotation->overhead,0) }}
                        </td>
                    </tr>

                    <tr style="background-color:#e0e0e0;">
                        <td class="bold right">Total Tool Cost</td>
                        <td class="center">-</td>
                        <td class="right bold">
                            Rs. {{ number_format($quotation->total_tool_cost ?? 0, 0) }}
                        </td>
                    </tr>

                </table>
            </td>
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