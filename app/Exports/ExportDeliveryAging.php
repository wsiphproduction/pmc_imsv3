<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;

class ExportDeliveryAging implements FromCollection, WithHeadings
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
        return DB::table('v_po_details')->select('poNumber','supplier','orderDate','incoterms','expectedDeliveryDate','actualDeliveryDate','AgingDeliveryDate','delivery_term','status','status_delivery')
            ->whereBetween('expectedDeliveryDate',[$this->r->from,$this->r->to])
            ->get();
    }

    public function headings(): array
    {
        return [
            'PO #',
            'Supplier',
            'Order Date',
            'Inco Terms',
            'Expected Delivery Date',
            'Actual Delivery Date',
            'Delivery Aging (days)',
            'Delivery Term',
            'Status',
            'Delivery Status'
        ];
    }
}
