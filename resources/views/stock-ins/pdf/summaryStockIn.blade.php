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
            <h1>Stock-in Summary Report by GL Number</h1>
            <p>{{ \Carbon\Carbon::now()->setTimezone('Asia/Jakarta')->format('F j, Y') }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Gl Number</th>
                    <th>Color</th>
                    <th>Bundles</th>
                    <th>Cut Pcs</th>
                    <th>Line Names</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $index => $row)
                    <tr>
                        <td width="10px">{{ $index + 1 }}</td>
                        <td width="80px" class="text-center font-semibold" valign="top">{{ $row['gl_no'] }}</td>
                        <td style="padding: 0px">
                            @foreach ($row['details'] as $i => $detail)
                                <div class="p-8"> {{ $detail['color'] }} </div>
                                @if ($i < count($row['details']) - 1)
                                    <hr class="inner-separator">
                                @endif
                            @endforeach
                            <div class="p-8 bg-gray">Subtotal</div>
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
                        <td width="220px" valign="top">{{ $row['line_names'] }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</body>

</html>
