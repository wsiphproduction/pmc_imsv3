<?php

namespace App\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use Carbon\Carbon;
use App\logistics;
use App\PO;

class POStatus implements FromCollection, WithMapping, WithHeadings
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
        $qry = PO::whereNotNull('id');

        if(!isset($this->req->status)){

            $purchases = $qry->where('status','OPEN');

        } else {

            $purchases = $qry->where('status',$this->req->status);

        }

        if(isset($this->req->from)){

            $purchases = $qry->whereBetween('expectedCompletionDate',[$this->req->from,$this->req->to]);

        }

        return $purchases->orderBy('expectedCompletionDate','asc')->get();

    }

    public function map($purchases) : array {
        
        return [
            $purchases->poNumber,
            $purchases->supplier_name->name,
            $purchases->expectedCompletionDate,
            PO::paymentProgress($purchases->amount,$purchases->id).'% PAID',
            logistics::shipment_status($purchases->id).' SHIPPED',
            PO::deliveryProgress($purchases->qty,$purchases->id).'% DELIVERED',
            $purchases->delivery_term,
            $purchases->status

        ];

    }

    public function headings() : array
    {
        return [
            'PO Number',
            'Supplier',
            'Expected Completion Date',
            'Payment Progress',
            'Shipment Progress',
            'Delivery Progress',
            'Delivery Terms',
            'Status'
        ];

            
    }
}
