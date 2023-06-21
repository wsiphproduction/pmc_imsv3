<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class ExportCompletionDate implements FromCollection, WithHeadings
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
        return DB::table('v_payment_delivery_report')->select('poNumber','name','orderDate','amount','paid','qty','delivered_qty','expectedCompletionDate','status')->whereBetween('expectedCompletionDate',[$this->r->from,$this->r->to])->get();
    }

    public function headings(): array
    {
        return [
            'PO #',
            'Supplier',
            'Order Date',
            'Amount',
            'Paid Amount',
            'Qty',
            'Delivered Qty',
            'Completion Date',
            'Status'
        ];
    }
}
