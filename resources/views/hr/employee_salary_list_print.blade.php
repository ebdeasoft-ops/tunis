@extends('layouts.master')

@section('css')
    <style>
        @media print {
            #print_Button, #export_excel_btn, .breadcrumb-header, .main-header, .main-sidebar, .main-footer {
                display: none !important;
            }
            body {
                background: #fff !important;
                font-size: 11pt;
                color: #000;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
                page-break-after: always;
            }
            .slip-container {
                page-break-inside: avoid;
                margin-bottom: 20px;
                border: 1px solid #ddd !important;
                padding: 15px !important;
            }
        }

        .invoice-title {
            font-weight: 700;
            color: #333;
        }
        
        .company-logo {
            max-width: 120px;
            height: auto;
            object-fit: contain;
        }

        .slip-card {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
        }
        
        .note-box {
            background-color: #fff8e1;
            border-right: 4px solid #ffc107;
            padding: 10px;
            font-size: 11pt;
            margin-bottom: 20px;
            border-radius: 4px;
        }
    </style>
@endsection

@section('title')
    {{ __('hr.salarydecoument') }}
@stop

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('hr.salarydecoument') }}</h4>
                <span class="text-muted mt-1 tx-13 mr-2 ml-2">/ {{ $month ?? '' }}</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-md-12 col-xl-12">
            
            <!-- أزرار التحكم -->
            <div class="mb-3 text-left" id="print_Button">
                <button class="btn btn-danger px-4 py-2" onclick="printDiv()">
                    <i class="mdi mdi-printer ml-1"></i> {{ __('home.print') }} (Print All)
                </button>
                <button class="btn btn-success px-4 py-2" id="export_excel_btn" onclick="exportTableToExcel('salarySlipsTable', 'salary_slips_{{ $month }}')">
                    <i class="mdi mdi-file-excel ml-1"></i> {{ __('تصدير إكسيل') }} (Excel)
                </button>
            </div>

            <!-- ملاحظة النظام -->
            <div class="note-box">
                <strong>ملاحظة هامة / Notice:</strong> 
                يتم قيد الزيادة، والخصم، والسلف تلقائياً من النظام، بينما يتم إدخال (الإضافي، الغياب، والإجازات، والبدلات) يدويًا أو احتسابها قبل نهاية الشهر.
            </div>

            <div class="main-content-body-invoice" id="print">
                
                @php $i = 0; @endphp
                @foreach ($list_salary_data as $data)
                    @php 
                        $i++;
                        $emp = $data['employeeData'];

                        // البدلات
                        $housingAllowance = $emp->housing_allowance ?? $data['housing_allowance'] ?? 0;
                        $transportAllowance = $emp->transport_allowance ?? $data['transport_allowance'] ?? 0;
                        $otherAllowance = $emp->other_allowance ?? $data['other_allowance'] ?? 0;
                        $totalAllowances = $housingAllowance + $transportAllowance + $otherAllowance;

                        // إجمالي الراتب المستحق (الأساسي + البدلات + الإضافي + المكافآت)
                        $overtimeAmount = $data['overtime_amount'] ?? 0;
                        $bonusAmount = $data['bounes'] ?? 0;
                        $grossSalary = $emp->salary + $totalAllowances + $overtimeAmount + $bonusAmount;

                        // الحسابات اليومية والخصومات
                        $dailyRate = ($emp->salary > 0) ? ($emp->salary / 30) : 0;
                        $unauthorizedAbsentDays = $data['unauthorized_absent_days'] ?? 0;
                        $unauthorizedAbsentPenalty = $unauthorizedAbsentDays * 2 * $dailyRate;
                        $totalDiscounts = $data['discount'] + $unauthorizedAbsentPenalty;
                        $loansAmount = $data['Loans'] ?? 0;

                        // صافي الراتب النهائي
                        $netSalary = $grossSalary - ($totalDiscounts + $loansAmount);
                    @endphp

                    <!-- قسيمة راتب مستقلة لكل موظف -->
                    <div class="card slip-card slip-container p-4">
                        <div class="card-body">
                            
                            <!-- رأس القسيمة -->
                            <div class="invoice-header d-flex justify-content-between align-items-center flex-wrap pb-3 border-bottom">
                                <div class="billed-from text-left" style="width: 30%;">
                                    <h5 class="invoice-title font-weight-bold">{{ Nameen ?? 'Company Name' }}</h5>
                                    <p class="mb-1 text-muted tx-12" dir="ltr">{{ describtionen ?? '' }}</p>
                                </div>
                                <div class="text-center my-2" style="width: 30%;">
                                    @php $logo = camplogo ?? 'default-logo.png'; @endphp
                                    <img src="{{ asset('assets/img/brand/' . $logo) }}" class="company-logo" alt="Logo">
                                </div>
                                <div class="billed-from text-right" style="width: 30%;">
                                    <h5 class="invoice-title font-weight-bold">{{ Namear ?? 'اسم الشركة' }}</h5>
                                    <p class="mb-1 text-muted tx-12">{{ describtionar ?? '' }}</p>
                                </div>
                            </div>

                            <!-- عنوان القسيمة والشهر -->
                            <div class="text-center my-3">
                                <h4 class="font-weight-bold text-primary">{{ __('hr.salarydecoument') }} - Pay Slip</h4>
                                <span class="badge badge-light px-3 py-1 tx-14 border text-secondary">{{ $month ?? '' }}</span>
                            </div>

                            <!-- بيانات الموظف الأساسية -->
                            <div class="row mb-3 bg-light p-2 rounded">
                                <div class="col-md-4">
                                    <strong>{{ __('hr.name') }} / Name:</strong> 
                                    {{ (app()->getLocale() == 'ar') ? $emp->name_ar : $emp->name_en }}
                                </div>
                                <div class="col-md-4">
                                    <strong>{{ __('hr.Id') }} / ID:</strong> {{ $emp->personal_identification }}
                                </div>
                                <div class="col-md-4">
                                    <strong>{{ __('hr.department') }} / Dept:</strong> 
                                    @if($emp->departments)
                                        {{ (app()->getLocale() == 'ar') ? $emp->departments->name_ar : $emp->departments->name_en }}
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>

                            <!-- جدول المستحقات (فوق) -->
                            <div class="table-responsive">
                                <h6 class="font-weight-bold text-success mb-2"><i class="fas fa-plus-circle ml-1"></i> المستحقات (Allowances & Additions)</h6>
                                <table class="table table-bordered text-center align-middle salary-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{ __('hr.salary') }} <br><small>Basic</small></th>
                                            <th>بدل سكن <br><small>Housing</small></th>
                                            <th>بدل انتقال <br><small>Transport</small></th>
                                            <th>بدلات أخرى <br><small>Other</small></th>
                                            <th>الإضافي <br><small>Overtime ({{ $data['overtime_hours'] ?? 0 }} س)</small></th>
                                            <th>{{ __('hr.increastotal') }} <br><small>Bonus</small></th>
                                            <th class="bg-success text-white">إجمالي المستحقات <br><small>Gross Total</small></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="font-weight-bold">{{ number_format($emp->salary, 2) }}</td>
                                            <td>{{ number_format($housingAllowance, 2) }}</td>
                                            <td>{{ number_format($transportAllowance, 2) }}</td>
                                            <td>{{ number_format($otherAllowance, 2) }}</td>
                                            <td class="text-success">{{ number_format($overtimeAmount, 2) }}</td>
                                            <td class="text-success">{{ number_format($bonusAmount, 2) }}</td>
                                            <td class="font-weight-bold text-success bg-light">{{ number_format($grossSalary, 2) }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- جدول تفاصيل الدوام والحضور -->
                                <table class="table table-bordered text-center align-middle salary-table mt-2">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{ __('حضور / غياب') }} <br><small>Att / Abs</small></th>
                                            <th>{{ __('إجازة بإذن') }} <br><small>Perm. Leave</small></th>
                                            <th>{{ __('غياب بدون إذن (خصم يومين)') }} <br><small>Unauthorized (2x)</small></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span class="text-success">ح: {{ $data['present_days'] ?? 0 }}</span> / 
                                                <span class="text-danger">غ: {{ $data['absent_days'] ?? 0 }}</span>
                                            </td>
                                            <td><span class="badge badge-info">{{ $data['permission_leave_days'] ?? 0 }} يوم</span></td>
                                            <td>
                                                <span class="badge badge-danger">{{ $unauthorizedAbsentDays }} يوم</span>
                                                @if($unauthorizedAbsentDays > 0)
                                                    <br><small class="text-danger">(-{{ number_format($unauthorizedAbsentPenalty, 2) }})</small>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <!-- جدول الخصومات والصافي (تحت) -->
                                <h6 class="font-weight-bold text-danger mt-3 mb-2"><i class="fas fa-minus-circle ml-1"></i> الخصومات والالتزامات (Deductions & Loans)</h6>
                                <table class="table table-bordered text-center align-middle salary-table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>{{ __('hr.decreasetotal') }} <br><small>Discounts / Penalties</small></th>
                                            <th>{{ __('home.amountLoans') }} <br><small>Loans</small></th>
                                            <th class="bg-primary text-white" style="font-size: 1.1rem;">{{ __('صافي الراتب') }} <br><small>Net Salary</small></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-danger font-weight-bold">-{{ number_format($totalDiscounts, 2) }}</td>
                                            <td class="text-warning font-weight-bold">-{{ number_format($loansAmount, 2) }}</td>
                                            <td class="font-weight-bold text-primary bg-light" style="font-size: 1.3rem;">
                                                {{ number_format($netSalary, 2) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- التوقيع -->
                            <div class="row mt-4 pt-3 border-top">
                                <div class="col-6 text-right">
                                    <strong>توقيع الموظف / Employee Signature:</strong>
                                    <div class="mt-4">........................................</div>
                                </div>
                                <div class="col-6 text-left">
                                    <strong>اعتماد الإدارة / Management:</strong>
                                    <div class="mt-4">........................................</div>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/chart.js/Chart.bundle.min.js') }}"></script>

    <!-- دالة الطباعة -->
    <script type="text/javascript">
        function printDiv() {
            var printContents = document.getElementById('print').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>

    <!-- دالة التصدير إلى إكسيل -->
    <script type="text/javascript">
        function exportTableToExcel(tableID, filename = ''){
            var html = "";
            @foreach ($list_salary_data as $data)
                @php 
                    $emp = $data['employeeData'];
                    $housingAllowance = $emp->housing_allowance ?? $data['housing_allowance'] ?? 0;
                    $transportAllowance = $emp->transport_allowance ?? $data['transport_allowance'] ?? 0;
                    $otherAllowance = $emp->other_allowance ?? $data['other_allowance'] ?? 0;
                    $overtimeAmount = $data['overtime_amount'] ?? 0;
                    $bonusAmount = $data['bounes'] ?? 0;
                    $totalAllowances = $housingAllowance + $transportAllowance + $otherAllowance;
                    $grossSalary = $emp->salary + $totalAllowances + $overtimeAmount + $bonusAmount;

                    $dailyRate = ($emp->salary > 0) ? ($emp->salary / 30) : 0;
                    $unauthorizedAbsentPenalty = ($data['unauthorized_absent_days'] ?? 0) * 2 * $dailyRate;
                    $totalDiscounts = $data['discount'] + $unauthorizedAbsentPenalty;
                    $loansAmount = $data['Loans'] ?? 0;
                    $netSalary = $grossSalary - ($totalDiscounts + $loansAmount);
                @endphp
                html += "<tr>";
                html += "<td>{{ $emp->name_ar }}</td>";
                html += "<td>{{ $emp->personal_identification }}</td>";
                html += "<td>{{ $emp->salary }}</td>";
                html += "<td>{{ $housingAllowance }}</td>";
                html += "<td>{{ $transportAllowance }}</td>";
                html += "<td>{{ $otherAllowance }}</td>";
                html += "<td>{{ $overtimeAmount }}</td>";
                html += "<td>{{ $bonusAmount }}</td>";
                html += "<td>{{ $grossSalary }}</td>";
                html += "<td>{{ $totalDiscounts }}</td>";
                html += "<td>{{ $loansAmount }}</td>";
                html += "<td>{{ $netSalary }}</td>";
                html += "</tr>";
            @endforeach

            var header = "<tr><th>Name</th><th>ID</th><th>Basic Salary</th><th>Housing</th><th>Transport</th><th>Other Allow.</th><th>Overtime</th><th>Bonus</th><th>Gross Salary</th><th>Discounts</th><th>Loans</th><th>Net Salary</th></tr>";
            var excelTemplate = `<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
            <head><meta charset="utf-8"></head><body><table>` + header + html + `</table></body></html>`;

            var blob = new Blob([excelTemplate], { type: 'application/vnd.ms-excel' });
            var url = window.URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = filename ? filename + '.xls' : 'salary_report.xls';
            a.click();
        }
    </script>
@endsection