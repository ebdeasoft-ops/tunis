<?php

namespace App\Exports;

use App\Models\Avt;
use App\Models\invoices;
use App\Models\return_sales;
use App\Models\sales;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class SalesAndReturnExport implements WithMultipleSheets
{
    public function __construct(
        private string $from,
        private string $to,
        private $branch
    ) {}

    public function sheets(): array
    {
        return [
            new SalesAndReturnSalesSheet($this->from, $this->to, $this->branch),
            new SalesAndReturnReturnsSheet($this->from, $this->to, $this->branch),
        ];
    }
}

class SalesAndReturnSalesSheet implements FromQuery, WithMapping, WithHeadings, WithChunkReading, WithTitle, WithEvents, ShouldAutoSize
{
    private float $avt;
    private float $totalPrice = 0;
    private float $totalDiscount = 0;
    private float $totalVat = 0;
    private float $totalBefore = 0;

    public function __construct(private string $from, private string $to, private $branch)
    {
        $this->avt = (float) optional(Avt::find(1))->AVT;
    }

    public function query()
    {
        $q = invoices::query()
            ->select([
                'id', 'created_at', 'customer_id', 'Pay',
                'cashamount', 'bankamount', 'Bank_transfer', 'creaditamount',
                'Number_of_Quantity', 'discount', 'discountOnProduct',
            ])
            ->addSelect([
                // نفس حسابات الـ Blade لكن داخل قاعدة البيانات
                'disc_inv'   => return_sales::selectRaw('COALESCE(SUM(discountoninvoice),0)')->whereColumn('invoice_id', 'invoices.id'),
                'disc_val'   => return_sales::selectRaw('COALESCE(SUM(discountvalue),0)')->whereColumn('invoice_id', 'invoices.id'),
                'sales_disc' => sales::selectRaw('COALESCE(SUM(Discount_Value),0)')->whereColumn('invoice_id', 'invoices.id'),
            ])
            ->with('customer:id,name')
            ->where('created_at', '>=', $this->from)
            ->where('created_at', '<=', $this->to)
            ->where('save', 1)
            ->orderBy('id');

        if ($this->branch != '-') {
            $q->where('branchs_id', $this->branch);
        }

        return $q;
    }

    public function map($p): array
    {
        $price = $p->cashamount + $p->bankamount + $p->Bank_transfer + $p->creaditamount;

        $discOnInvoice = $p->Number_of_Quantity == 0
            ? (float) $p->disc_inv
            : ($p->discount - $p->discountOnProduct);

        $discount = (float) $p->disc_val + (float) $p->sales_disc + $discOnInvoice;

        $before = $this->avt > 0 ? ($price * 100) / (100 + ($this->avt * 100)) : $price;
        $vat    = $before * $this->avt;

        $this->totalPrice    += $price;
        $this->totalDiscount += $discount;
        $this->totalVat      += $vat;
        $this->totalBefore   += $before;

        $pay = match ($p->Pay) {
            'Cash'          => __('report.cash'),
            'Shabka'        => __('report.shabka'),
            'Credit'        => __('report.credit'),
            'Bank_transfer' => __('home.Bank_transfer'),
            default         => __('home.Partition of the amount'),
        };

        return [
            $p->id,
            (string) $p->created_at,
            optional($p->customer)->name,
            $pay,
            round($before + $discount, 2),
            round($discount, 2),
            round($before, 2),
            round($vat, 2),
            round($price, 2),
        ];
    }

    public function headings(): array
    {
        return [
            __('home.Invoice_no'), __('home.date'), __('home.clietName'), __('home.paymentmethod'),
            __('home.total'), __('home.discount'), __('home.totalafterdiscount'),
            __('home.avt'), __('home.totalwithTax'),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $ws  = $event->sheet->getDelegate();
                $row = $ws->getHighestRow() + 1;
                $ws->fromArray([[
                    'المجموع الكلي', '', '', '',
                    round($this->totalDiscount + $this->totalBefore, 2),
                    round($this->totalDiscount, 2),
                    round($this->totalBefore, 2),
                    round($this->totalVat, 2),
                    round($this->totalPrice, 2),
                ]], null, 'A' . $row);
                $ws->getStyle("A{$row}:I{$row}")->getFont()->setBold(true);
            },
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function title(): string
    {
        return 'Sales';
    }
}

class SalesAndReturnReturnsSheet implements FromQuery, WithMapping, WithHeadings, WithChunkReading, WithTitle, WithEvents, ShouldAutoSize
{
    private float $avt;
    private float $total = 0;
    private int $counter = 0;

    public function __construct(private string $from, private string $to, private $branch)
    {
        $this->avt = (float) optional(Avt::find(1))->AVT;
    }

    public function query()
    {
        $q = return_sales::query()
            ->select('invoice_id')
            ->selectRaw('MAX(created_at) as last_date')
            ->selectRaw('SUM(return_quantity) as qty')
            ->selectRaw('SUM(return_Unit_Price * return_quantity - discountvalue - discountoninvoice) as sub')
            ->with(['Invoice:id,Pay,customer_id', 'Invoice.customer:id,name'])
            ->where('created_at', '>=', $this->from)
            ->where('created_at', '<=', $this->to)
            ->groupBy('invoice_id')
            ->orderBy('invoice_id');

        if ($this->branch != '-') {
            $q->where('branch_id', $this->branch);
        }

        return $q;
    }

    public function map($r): array
    {
        $each = $r->sub + ($r->sub * $this->avt);
        $this->total += $each;
        $this->counter++;

        $pay = optional($r->Invoice)->Pay;
        $payLabel = match ($pay) {
            'Cash'          => __('report.cash'),
            'Credit'        => __('report.credit'),
            'Bank_transfer' => __('home.Bank_transfer'),
            default         => __('report.shabka'),
        };

        return [
            $this->counter,
            $r->last_date ? explode(' ', (string) $r->last_date)[0] : '',
            $r->invoice_id,
            optional(optional($r->Invoice)->customer)->name,
            $payLabel,
            round($each, 2),
            $r->qty,
        ];
    }

    public function headings(): array
    {
        return [
            '#', __('report.date'), __('report.invoiceNo'), __('home.clietName'),
            __('home.paymentmethod'), __('home.total'), __('home.quantity'),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $ws  = $event->sheet->getDelegate();
                $row = $ws->getHighestRow() + 1;
                $ws->fromArray([[__('report.totalallprice'), '', '', '', '', round($this->total, 2), '']], null, 'A' . $row);
                $ws->getStyle("A{$row}:G{$row}")->getFont()->setBold(true);
            },
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function title(): string
    {
        return 'Returns';
    }
}