@php
    $vals = getAccountTotals($account->id, $allAccounts, $directBalances);
    $net = round(($vals['o_d'] + $vals['c_d']) - ($vals['o_c'] + $vals['c_c']), 2);
    
    $children = $allAccounts->where('parent_account_number', $account->id);
    $hasChildren = $children->isNotEmpty();

    // تحديد التصميم واللون بحسب مستوى الحساب (Level)
    $rowStyle = '';
    $icon = '';
    $indent = '';

    if ($level == 0) {
        // الحساب الرئيسي (المستوى الأول) - لون خلفية مميز وخط عريض
        $rowStyle = 'background-color: #e8f4f8; font-weight: bold; color: #2c3e50; font-size: 14px;';
        $icon = '<i class="fa fa-folder-open text-primary ml-1"></i>';
    } elseif ($level == 1) {
        // الحساب الأب (المستوى الثاني)
        $rowStyle = 'background-color: #f9fbfd; font-weight: bold; color: #34495e;';
        $icon = '<i class="fa fa-angle-double-down text-info ml-1"></i>';
        $indent = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    } else {
        // الحساب الابن (المستوى الثالث وما فروعها)
        $rowStyle = 'background-color: #ffffff; color: #555555;';
        $icon = '<i class="fa fa-long-arrow-alt-down text-muted ml-1"></i>';
        $indent = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $level);
    }
@endphp

<tr style="{{ $rowStyle }}">
    <td style="text-align: right; padding-right: 15px;">
        {!! $indent !!} {!! $icon !!} {{ $account->name }}
    </td>
    <td>{{ $net > 0 ? number_format($net, 2) : '0.00' }}</td>
    <td>{{ $net < 0 ? number_format(abs($net), 2) : '0.00' }}</td>
    <td>{{ $net == 0 ? '0.00' : number_format(abs($net), 2) }}</td>
</tr>

@foreach($children as $child)
    @include('reports.partials.budget_row', [
        'account' => $child, 
        'level' => $level + 1, 
        'allAccounts' => $allAccounts, 
        'directBalances' => $directBalances
    ])
@endforeach