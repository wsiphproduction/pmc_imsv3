<?php

namespace App\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use Carbon\Carbon;

use App\PO;

class OverduePO implements FromCollection, WithMapping, WithHeadings
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
        $qry = PO::where('status','OPEN');

        if(isset($this->req->from)){

            $qry->whereBetween('expectedCompletionDate',["".date('Y-m-d',strtotime($this->req->from))."","".date('Y-m-d',strtotime($this->req->to)).""]);

        } else {

            $qry->where('expectedCompletionDate','<',Carbon::today());

        } 

        return $qry->orderBy('expectedCompletionDate','desc')->get();

    }

    public function map($po) : array {
        
        return [
            $po->poNumber,
            $po->supplier_name->name,
            $po->orderDate,
            $po->expectedCompletionDate,
            $po->delivery_term,
            \App\logistics::delivery_status($po->id),
            \App\logistics::latest_delivery_status($po->id)
        ];

    }

    public function headings() : array
    {
        return [
            'PO Number',
            'Supplier',
            'Date Ordered',
            'Expected Completion Date',
            'Delivery Term',
            'Shipment Progress',
            'Shipment Status'
        ];

            
    }
}
