<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class ExportSupplier implements FromCollection, WithHeadings
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
        return DB::table('v_report_payments')->select('poNumber','name','paymentDate','isPaid','currency','amount','status','delivery_term')->where('supplier_id','=',$this->r->supp)->get();
    }

    public function headings(): array
    {
        return [
            'PO #',
            'Supplier',
            'Payment Date',
            'Status',
            'Currency',
            'Amount',
            'Status',
            'Delivery Term'
        ];
    }
}
