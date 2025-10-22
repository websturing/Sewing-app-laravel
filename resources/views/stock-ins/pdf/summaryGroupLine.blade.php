<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ public_path('css/pdf-style.css') }}">
</head>

<body>
    <div class="page">
        <div class="text-sm">
            <div class="float-left">PT. GHIMLI INDONESIA</div>
            <div class="float-right">
                Generated on: {{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('F j, Y G:i') }}
            </div>
            <div class="clearer"></div>
        </div>
        <div class="header">
            <h2>Stock-in Summary Report by Line</h2>
            @if ($isRangeDate)
                <p>
                    {{ \Carbon\Carbon::parse($startDate)->setTimezone('Asia/Jakarta')->format('F j, Y') }}
                    &nbsp;&nbsp; - &nbsp;&nbsp;
                    {{ \Carbon\Carbon::parse($endDate)->setTimezone('Asia/Jakarta')->format('F j, Y') }}
                </p>
            @endif
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Line</th>
                    <th>Color</th>
                    <th>Bundles</th>
                    <th>Cut Pcs</th>
                    <th>Gl Number</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $index => $row)
                    <tr>
                        <td width="10px">{{ $index + 1 }}</td>
                        <td width="80px" class="text-center font-semibold" valign="top">{{ $row['line_name'] }}</td>
                        <td style="padding: 0px">
                            @foreach ($row['details'] as $i => $detail)
                                <div class="p-8"> {{ $detail['color'] }} </div>
                                @if ($i < count($row['details']) - 1)
                                    <hr class="inner-separator">
                                @endif
                            @endforeach
                            @if (count($row['details']) > 0)
                                <div class="p-8 bg-gray">Subtotal</div>
                            @else
                                <div class="p-8">- &nbsp;</div>
                            @endif
                        </td>
                        <td width="70px" class="font-semibold text-center" style="padding: 0px">
                            @php($totalBundle = 0)
                            @foreach ($row['details'] as $i => $detail)
                                @php($totalBundle += $detail['total_bundle'])
                                <div class="p-8">{{ number_format($detail['total_bundle']) }}</div>
                                @if ($i < count($row['details']) - 1)
                                    <hr class="inner-separator">
                                @endif
                            @endforeach
                            <div class="p-8 bg-gray">{{ $totalBundle }}</div>
                        </td>
                        <td width="70px" class="font-semibold text-center" style="padding: 0px">
                            @php($totalPcs = 0)
                            @foreach ($row['details'] as $i => $detail)
                                @php($totalPcs += $detail['total_pcs'])
                                <div class="p-8">{{ number_format($detail['total_pcs']) }}</div>
                                @if ($i < count($row['details']) - 1)
                                    <hr class="inner-separator">
                                @endif
                            @endforeach
                            <div class="p-8 bg-gray">{{ $totalPcs }}</div>
                        </td>
                        <td width="100px" class="font-semibold text-center" style="padding: 0px">
                            @foreach ($row['details'] as $i => $detail)
                                <div class="p-8">{{ $detail['gl_no'] }}</div>
                                @if ($i < count($row['details']) - 1)
                                    <hr class="inner-separator">
                                @endif
                            @endforeach
                            <div class="p-8 bg-gray">-</div>
                        </td>
                        <td width="150px">
                            @foreach ($row['details'] as $i => $detail)
                                <div class="p-8">{{ $detail['last_updated'] }}</div>
                                @if ($i < count($row['details']) - 1)
                                    <hr class="inner-separator">
                                @endif
                            @endforeach
                            <div class="p-8 bg-gray">-</div>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</body>

</html>
