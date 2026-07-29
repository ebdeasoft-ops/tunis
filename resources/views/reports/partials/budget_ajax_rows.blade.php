@if($children->count() > 0)
    <table style="width: 100%; margin: 0; border: none;">
        <tbody>
            @foreach($children as $child)
                @php
                    $direct = $directBalances[$child->id] ?? null;
                    $open_d = $direct ? (float)$direct->open_debtor : 0;
                    $open_c = $direct ? (float)$direct->open_creditor : 0;
                    $curr_d = $direct ? (float)$direct->curr_debtor : 0;
                    $curr_c = $direct ? (float)$direct->curr_creditor : 0;
                    
                    $net = round(($open_d + $curr_d) - ($open_c + $curr_c), 2);
                @endphp
                <tr>
                    <td style="text-align: right; padding-right: 35px; border-top: 1px solid #eee;">
                        <span class="toggle-account" data-id="{{ $child->id }}">
                            <i class="fa fa-angle-double-down text-info ml-1 fa-icon"></i> {{ $child->name }}
                        </span>
                    </td>
                    <td style="border-top: 1px solid #eee;">{{ $net > 0 ? number_format($net, 2) : '0.00' }}</td>
                    <td style="border-top: 1px solid #eee;">{{ $net < 0 ? number_format(abs($net), 2) : '0.00' }}</td>
                    <td style="border-top: 1px solid #eee;">{{ $net == 0 ? '0.00' : number_format(abs($net), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div style="padding: 10px; text-align: center; color: #777;">لا توجد حسابات فرعية</div>
@endif