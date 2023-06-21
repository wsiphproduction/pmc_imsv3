<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class ExportWaybill implements FromCollection, WithHeadings
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
        return DB::table('v_delivery_per_waybill')->select('waybill','poNumber','name','customStartDate','customClearedDate','actualDeliveryDate','poExpectedDeliveryDate')->whereBetween('actualDeliveryDate',[$this->r->from,$this->r->to])->get();
    }

    public function headings(): array
    {
        return [
            'Waybill',
            'Po #',
            'Supplier',
            'Custom Start Date',
            'Custom Cleared Date',
            'Actual Delivery Date',
            'PO Expected Delivery Date'
        ];
    }
}
