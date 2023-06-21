<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class ExportUnpaidExcel implements FromCollection, WithHeadings
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
        return DB::table('v_report_unpaid_payments')->select('poNumber','supplier','orderDate','amount','paid','per_payment','expectedCompletionDate','AgingCompletionDate','delivery_term')->orderBy('AgingCompletionDate','desc')->get();
    }

    public function headings(): array
    {
        return [
            'PO #',
            'Supplier',
            'PO Date',
            'Amount',
            'Paid',
            'Payment Progress',
            'Completion Aging',
            'Completion Aging',
            'Delivery Term'

        ];
    }
}


