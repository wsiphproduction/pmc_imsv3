<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class ExportOverduePOWithSupplier implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $r;

    public function __construct($request)
    {
        $this->r = $request;

    }

    public function collection()
    {
        return DB::table('v_report_overdue_po')
            ->select('poNumber','supplier','orderDate','amount','paid','per_payment','qty','delivered_qty','per_delivery','expectedCompletionDate','AgingCompletionDate','delivery_term')
            ->where('supplier','=',$this->r->supplr)
            ->whereBetween('expectedCompletionDate',[$this->r->from,$this->r->to])
            ->orderBy('AgingCompletionDate','desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'PO #',
            'Supplier',
            'PO Date',
            'Amount',
            'Paid',
            'Payment Progress (%)',
            'Qty',
            'Delivered',
            'Delivery Progress (%)',
            'Completion Date',
            'Completion Aging',
            'Delivery Term'
        ];
    }
}
