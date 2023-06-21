<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class ExportInProgress implements FromCollection, WithHeadings
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
        return DB::table('v_in_progress')->select('poNumber','name','status','remarks')->get();
    }

    public function headings(): array
    {
        return [
            'PO #',
            'Supplier',
            'Status',
            'Remarks'
        ];
    }
}
