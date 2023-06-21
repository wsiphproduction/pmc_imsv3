<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class ExportInTransit implements FromCollection, WithHeadings
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
        return DB::table('v_in_transit')->select('poNumber','waybill', 'supplier','status','remarks')->get();
    }

    public function headings(): array
    {
        return [
            'PO #',
            'Waybill',
            'Supplier',
            'Status',
            'Remarks'
        ];
    }
}
