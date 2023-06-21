<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class ExportUpcomingDeliveries implements FromCollection, WithHeadings
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
        return DB::table('v_delivery_report')->select('poNumber', 'name', 'qty', 'po_delivery', 'remarks','delivery_term')
            ->whereBetween('expectedCompletionDate',[$this->r->from,$this->r->to])
            ->where('status','=','OPEN')
            ->get();

    }

    public function headings(): array
    {
        return [
            'PO #',
            'Supplier',
            'Ordered Qty',
            'Undelivered Qty',
            'Delivery Date',
            'Remarks',
            'Delivery Term'
        ];
    }
}
