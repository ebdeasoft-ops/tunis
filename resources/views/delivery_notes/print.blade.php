@extends('layouts.master')

@section('css')
<style>
    /* 1. التنسيقات العامة للمعاينة على الشاشة */
    .invoice-card {
        background-color: #fff;
        padding: 30px;
        border: 1px solid #ddd;
        margin: 20px auto;
        width: 95%;
        max-width: 900px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #333;
        padding-bottom: 15px;
    }

    .details-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .details-table th, .details-table td {
        border: 1px solid #000;
        padding: 12px;
        text-align: center;
        font-size: 16px;
    }

    .details-table th {
        background-color: #f8f9fa !important;
        -webkit-print-color-adjust: exact;
    }

    .thick { font-weight: bold; }
    .text-center { text-align: center; }

    /* 2. تنسيقات الطباعة فقط */
  @media print {
    /* إخفاء الهيدر، الفوتر، القائمة الجانبية، وأي أزرار */
    nav, .main-header, .main-sidebar, .main-footer, .breadcrumb-header, #print_Button, .no-print {
        display: none !important;
    }

    /* إلغاء أي هوامش أو خلفيات يضعها الـ Layout الأساسي */
    body {
        background-color: white !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .main-content {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }

    /* تأكيد أن كارد الفاتورة يأخذ عرض الصفحة بالكامل */
    .invoice-card {
        border: none !important;
        box-shadow: none !important;
        width: 100% !important;
        margin: 0 !important;
    }

    /* إخفاء الرابط والوقت اللي المتصفح بيحطهم فوق وتحت تلقائياً */
    @page {
        margin: 1cm;
    }
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-center no-print mt-4">
        <button class="btn btn-danger btn-lg shadow" id="print_Button" onclick="window.print()">
            {{ __('home.print') }} <i class="mdi mdi-printer ml-1"></i>
        </button>
    </div>

     <div class="invoice-header mt-5" style="display: flex;justify-content:space-between;width:100%" dir=rtl>




                        <div class="billed-from" style="width:33%;text-align: center;">
                            <br>

                            <span class="thick" style="font-size:18px">{{Namear}}</span>
                            <br>
                            <p class="tx-16 thick"> {{describtionar}}</p>
                            <p class="tx-16 thick">{{STar}}</p>
                            <p class="tx-16 thick">{{Taxar}}</p>

                        </div><!-- billed-from -->
                        <div >
                            <?php
                            $logo = camplogo;
                            ?>
                            <a href="https://ebdeasoft.com/"><img src="{{ asset('assets\img\brand').'/'.$logo }}" class="logo-1" alt="logo" style="width: 150px; height: 150px;"></a>

                        </div>

                        <div class="billed-from" style="width:33%;text-align: center;">
                            <br>
                            <span class="thick" style="font-size:19px">{{Nameen}}</span>
                            <br>
                            <p class="tx-16 thick" > {{describtionen}} </p>
                            <span class="tx-16 thick">{{STen}} </span>
                            <p class="tx-16 thick"> {{Taxen}} </p>

                        </div>

                    </div><!-- invoice-header -->


<h4 class="text-center thick" style="text-decoration: underline; margin: 30px 0; font-size: 24px;">DELIVERY NOTE / إذن تسليم</h4>

<div class="header-info" style="margin-bottom: 30px; line-height: 2;">
    <div style="display: flex; justify-content: space-between; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 15px;">
        <div class="thick">
            <span style="color: #555;">Date / التاريخ:</span>
            <span style="margin-left: 10px;">{{ date('d/m/Y', strtotime($note->created_at)) }}</span>
        </div>
        <div class="thick">
            <span style="color: #555;">No / رقم:</span>
            <span style="margin-left: 10px;">{{ $note->id }}</span>
        </div>
    </div>

    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; border: 1px solid #ddd;">
        <div class="thick" style="font-size: 18px;">
            <strong style="color: #333;">Customer / العميل:</strong>
            <span style="margin-left: 15px; border-bottom: 1px dashed #999;">{{ $note->customer->name ?? '---' }}</span>
        </div>
    </div>
</div>

      <table class="details-table">
    <thead>
        <tr>
            <th style="width: 10%;">SQ#</th>
            <th style="width: 20%;">Part ID / رقم القطعة</th> <th style="width: 45%;">Product Description / وصف المنتج</th>
            <th style="width: 25%;">Qty / الكمية</th>
        </tr>
    </thead>
    <tbody>
        @foreach($note->items as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            {{-- تغيير من id إلى Product_Code --}}
            <td class="thick">{{ $item->product->Product_Code ?? '---' }}</td>
            <td class="thick" style="text-align: right; padding-right: 10px;">
                {{ $item->product->product_name ?? '---' }}
            </td>
            <td class="thick">{{ $item->quantity + 0 }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            {{-- ضبط إجمالي الكمية ليشمل + 0 لإزالة الأصفار الزائدة --}}
            <th colspan="3" style="text-align: left; padding-left: 10px; background: #eee;">Total Quantity / إجمالي الكمية</th>
            <th style="background: #eee;">{{ $note->items->sum('quantity') + 0 }}</th>
        </tr>
    </tfoot>
</table>

        <div class="row mt-5 pt-4">
            <div class="col-6 text-center">
                <p class="thick">Recipient Signature / توقيع المستلم</p>
                <p>........................................</p>
            </div>
            <div class="col-6 text-center">
                <p class="thick">Authorized Signature / التوقيع المعتمد</p>
                <p>........................................</p>
            </div>
        </div>

        <div class="mt-5 text-center tx-12 text-muted border-top pt-2">
            هذه الوثيقة صادرة عن نظام Ebdea Soft المحاسبي
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // وظيفة الطباعة (اختياري لو أردت تخصيص الـ Div فقط)
    function printDiv() {
        window.print();
    }
</script>
@endsection
