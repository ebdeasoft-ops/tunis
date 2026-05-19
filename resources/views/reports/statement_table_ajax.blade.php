<div class="table-responsive">
    <div class="mb-3">
        <h6>{{ __('home.acount_name') }}: <b>{{ $account_name }}</b></h6>
        <p>{{ __('report.fromdate') }}: {{ $start_at }} | {{ __('report.todate') }}: {{ $end_at }}</p>
    </div>
    <table class="table table-bordered table-striped text-center">
        <thead>
            <tr class="bg-light">
                <th>#</th>
                <th>{{ __('home.date') }}</th>
                <th>{{ __('home.notesClient') }}</th>
                <th>{{ __('home.debit') }}</th>
                <th>{{ __('home.credit') }}</th>
                <th>{{ __('home.current balance') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="3"><b>{{ __('home.oping') }}</b></td>
                <td class="text-success">{{ number_format($debit, 2) }}</td>
                <td class="text-danger">{{ number_format($credit, 2) }}</td>
                <td>{{ number_format($blance, 2) }}</td>
            </tr>
            @foreach($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row['date'] }}</td>
                <td>{{ $row['note'] }}</td>
                <td>{{ number_format($row['depit'], 2) }}</td>
                <td>{{ number_format($row['credit'], 2) }}</td>
                <td>{{ number_format($row['current_blance'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-light font-weight-bold">
            <tr>
                <td colspan="3 text-right">الإجمالي</td>
                <td>{{ number_format(collect($data)->sum('depit') + $debit, 2) }}</td>
                <td>{{ number_format(collect($data)->sum('credit') + $credit, 2) }}</td>
                <td>-</td>
            </tr>
        </tfoot>
    </table>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5>كشف حساب: {{ $account_name }}</h5>
    <button type="button" class="btn btn-success btn-sm" id="btnExportExcel" data-id="{{ $account_id }}">
        <i class="las la-file-excel"></i> تصدير إكسيل
    </button>
</div>

<div class="table-responsive">
    </div>