<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class ExportCustomClearing implements FromCollection, WithHeadings
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
        return DB::table('v_delivery_status')->select('poNumber','waybill', 'name','status','customStartDate','customClearedDate')->where('status','=','Custom Clearing')->get();
    }

    public function headings(): array
    {
        return [
            'PO #',
            'Waybill',
            'Supplier',
            'Status',
            'Custom Start Date',
            'Custom Cleared Date'
        ];
    }
}
