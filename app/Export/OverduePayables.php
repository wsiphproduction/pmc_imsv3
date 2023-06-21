<?php

namespace App\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use Carbon\Carbon;

use App\PaymentSchedule;

class OverduePayables implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $req;

    public function __construct($request)
    {
        $this->req = $request;

    }

    public function collection()
    {

        return PaymentSchedule::where('isPaid',0)->whereDate('paymentDate','<',Carbon::today())->orderBy('paymentDate','desc')->get();

    }

    public function map($payments) : array {
        
        return [
            $payments->po_details->poNumber,
            $payments->po_details->supplier_name->name,
            $payments->amount,
            $payments->po_details->currency,
            $payments->paymentDate,
            'UNPAID'
        ];

    }

    public function headings() : array
    {
        return [
            'PO Number',
            'Supplier',
            'Amount',
            'Currency',
            'Payment Date',
            'Status'
        ];

            
    }
}
