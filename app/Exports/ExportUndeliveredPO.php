<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class ExportUndeliveredPO implements FromCollection, WithHeadings
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
        return DB::table('v_delivery_report')->select('poNumber','name','orderDate','qty','po_delivery','expectedCompletionDate','remarks','delivery_term','status')->where('deliveryStatus','=','UNDELIVERED')->where('status','=','OPEN')->get();
    }

    public function headings(): array
    {
        return [
            'PO #',
            'Supplier',
            'Order Date',
            'Qty',
            'Undelivered Qty',
            'Completion Date',
            'Remarks',
            'Delivery Term',
            'Status'

        ];
    }
}
