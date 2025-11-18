<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ public_path('css/pdf-style.css') }}">
</head>

<body>
    <div class="page font-semibold">
        <div class="text-sm">
            <div class="float-left">PT. GHIMLI INDONESIA</div>
            <div class="float-right">
                Generated on: {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('F j, Y G:i') }}
                {{-- <span>
                    {{ \Carbon\Carbon::parse($startDate)->setTimezone('Asia/Jakarta')->format('F j, Y') }}
                    &nbsp;&nbsp; - &nbsp;&nbsp;
                    {{ \Carbon\Carbon::parse($endDate)->setTimezone('Asia/Jakarta')->format('F j, Y') }}
                </span> --}}
            </div>
            <div class="clearer"></div>
        </div>
        <div class="header">
            <h2>SEWING COMPLETION REPORT</h2>
        </div>
        <div class="content">
            <div>
                <table style="font-size: 11px; font-weight: bold; width: 100% !important;">
                    <tr>
                        <td width="10%">GL#</td>
                        <td width="1.5%">:</td>
                        <td>{{ $glNumber }}</td>
                        <td width="15%">FABRIC TYPE</td>
                        <td width="1.5%">:</td>
                        <td width="35%">-</td>
                        <td>DATE</td>
                        <td width="1.5%">:</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>PO. NO</td>
                        <td>:</td>
                        <td>-</td>
                        <td>FABRIC CONS</td>
                        <td>:</td>
                        <td>-</td>
                        <td>DELIVERY DATE</td>
                        <td>:</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>BUYER</td>
                        <td>:</td>
                        <td>-</td>
                        <td>TOTAL OUTPUT QTY</td>
                        <td>:</td>
                        <td>{{ $results['total_output'] }}</td>
                        <td>PO Marker</td>
                        <td>:</td>
                        <td> - </td>
                    </tr>
                    <tr>
                        <td>STYLE</td>
                        <td>:</td>
                        <td>- </td>
                        <td>DIFF (Output - MI)</td>
                        <td>:</td>
                        <td>{{ $diffStockOutput }}</td>
                        <td>Actual Marker Length</td>
                        <td>:</td>
                        <td> - </td>
                    </tr>
                    <tr>
                        <td>MI QTY</td>
                        <td>:</td>
                        <td>{{ $results['mi_order'] }}</td>
                        <td>TOTAL REPLACEMENT</td>
                        <td>:</td>
                        <td>-</td>
                    </tr>
                </table>

            </div>
            <div class="clearer">&nbsp;</div>


            {{-- table colors  --}}

            @foreach ($results['colors'] as $i => $color)
                <div class="box">
                    <table id="table">
                        <thead>
                            <tr>
                                <th width="100px">COLOR</th>
                                <th colspan="{{ count($color['sizes']) }}">{{ $color['color'] }}</th>
                                <th>-</th>
                            </tr>
                            <tr>
                                <th>Size</th>
                                @foreach ($color['sizes'] as $size)
                                    <th width="20px">{{ $size['size'] }}</th>
                                @endforeach
                                <th width="60px">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>MI QTY</td>
                                @foreach ($color['sizes'] as $size)
                                    <td class="text-center">{{ $size['order_qty'] }}</td>
                                @endforeach
                                <td class="text-center">{{ $color['total_order_qty'] }}</td>
                            </tr>
                            <tr>
                                <td>STOCK IN QTY</td>
                                @foreach ($color['sizes'] as $size)
                                    <td class="text-center">{{ $size['pcs'] }}</td>
                                @endforeach
                                <td class="text-center">{{ $color['total_pcs'] }}</td>
                            </tr>
                            <tr>
                                <td>OUTPUT QTY</td>
                                @foreach ($color['sizes'] as $size)
                                    <td class="text-center">0</td>
                                @endforeach
                                <td class="text-center">0</td>
                            </tr>
                            <tr>
                                <td>DIFF (STOCKIN - MI )</td>
                                @foreach ($color['sizes'] as $size)
                                    @php
                                        $result = (int) $size['pcs'] - (int) $size['order_qty'];
                                        $colorClass = $result < 0 ? 'text-red' : ($result > 0 ? 'text-green' : '');

                                    @endphp

                                    <td class="text-center {{ $colorClass }}">
                                        {{ $result > 0 ? '+' : '' }}{{ $result }}
                                    </td>
                                @endforeach
                                @php
                                    $totalDiffStockIn = (int) $color['total_pcs'] - (int) $color['total_order_qty'];
                                @endphp
                                <td class="text-center {{ $colorClass }}">
                                    {{ $totalDiffStockIn > 0 ? '+' : '' }}{{ $totalDiffStockIn }}
                                </td>
                            </tr>
                            <tr>
                                <td>DIFF (OUTPUT - MI)</td>
                                @foreach ($color['sizes'] as $size)
                                    @php
                                        $result = 0 - (int) $size['pcs'];
                                        $colorClass = $result < 0 ? 'text-red' : ($result > 0 ? 'text-green' : '');
                                        $totalOutput = 0 - (int) $color['total_order_qty'];
                                        $colorClassTotal =
                                            $totalOutput < 0 ? 'text-red' : ($totalOutput > 0 ? 'text-green' : '');
                                    @endphp

                                    <td class="text-center {{ $colorClass }}">
                                        {{ $result > 0 ? '+' : '' }}{{ $result }}
                                    </td>
                                @endforeach
                                <td class="text-center {{ $colorClassTotal }}">
                                    {{ $totalOutput > 0 ? '+' : '' }}{{ $totalOutput }}</td>
                            </tr>
                            <tr>
                                <td>REPLACEMENT</td>
                                @foreach ($color['sizes'] as $size)
                                    <td class="text-center">0</td>
                                @endforeach
                                <td class="text-center">0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @if (($i + 1) % 2 == 0)
                    <div style="clear: both"></div>
                @endif
            @endforeach

        </div>

    </div>
</body>

</html>
