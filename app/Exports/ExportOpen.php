<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class ExportOpen implements FromCollection, WithHeadings
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
        return viewPO::select('')->where('status','=',$this->r->status)->get()
    }

    public function headings(): array
    {
        return [
            'PO #',
            'Supplier',
            'Payment',
            'Delivery'
        ];
    }
}
