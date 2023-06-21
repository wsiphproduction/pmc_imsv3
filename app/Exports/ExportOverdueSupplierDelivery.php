<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class ExportOverdueSupplierDelivery implements FromCollection, WithHeadings
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
        return DB::table('v_po_details')->select('poNumber','supplier','orderDate','expectedCompletionDate','delivery_term','status')->where('status','OPEN')->where('supplier',$this->r->supplier)->orderBy('poNumber','desc')->get();
    }

    public function headings(): array
    {
        return [
            'PO #',
            'Supplier',
            'PO Date',
            'Expected Completion Date',
            'Delivery Term',
            'Status'

        ];
    }
}
