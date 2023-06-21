<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class ExportOverduePaymentsSupplierOnly implements FromCollection, WithHeadings
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
        return DB::table('v_payments_tbl')->select('poNumber','name','orderDate','paymentDate','amount','paymentAging','delivery_term')->where('isPaid','=',0)->where('status','=','OPEN')->where('name','=',$this->r->supplr)->orderBy('paymentAging','desc')->get();
    }

    public function headings(): array
    {
        return [
            'PO #',
            'Supplier',
            'PO Date',
            'Payment Date',
            'Amount',
            'Aging',
            'Delivery Term'
        ];
    }
}
