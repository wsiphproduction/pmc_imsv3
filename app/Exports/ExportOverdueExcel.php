<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;
use \Carbon\Carbon;

class ExportOverdueExcel implements FromCollection, WithHeadings
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
        return DB::table('v_payments_tbl')->select('poNumber','name','orderDate','paymentDate','amount','paymentAging')->where('paymentDate','<',Carbon::today())->where('isPaid','=',0)->where('status','=','OPEN')->orderBy('paymentAging','desc')->get(); 
    }

    public function headings(): array
    {
        return [
            'PO Number',
            'Supplier',
            'PO Date',
            'Payment Date',
            'Amount',
            'Payment Aging'
        ];
    }
}
