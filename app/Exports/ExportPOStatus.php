<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class ExportPOStatus implements FromCollection, WithHeadings
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
        return DB::table('v_po_status')->select('poNumber','name','amount','paid','qty','delivered_qty')->where('status','=',$this->r->status)->get();
    }

    public function headings(): array
    {
        return [
            'PO #',
            'Supplier',
            'Amount',
            'Paid',
            'Qty',
            'Delivered'
        ];
    }
}
