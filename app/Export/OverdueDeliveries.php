<?php

namespace App\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use Carbon\Carbon;
use App\logistics;
use App\PO;

class OverdueDeliveries implements FromCollection, WithMapping, WithHeadings
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
        $qry = logistics::where('status','Delivered')->whereRaw('actualDeliveryDate > expectedDeliveryDate');

        if(isset($this->req->from)){

        	$deliveries = $qry->whereBetween('actualDeliveryDate',["".$this->req->from."","".$this->req->to.""])->orderBy('actualDeliveryDate','asc');

        } else {

            $deliveries = $qry;

        }

        return $deliveries->get();
    }

    public function map($deliveries) : array {
        
        return [
            $deliveries->po_details->poNumber,
            $deliveries->po_details->supplier_name->name,
            $deliveries->waybill,
            $deliveries->expectedDeliveryDate,
            $deliveries->actualDeliveryDate,
            'Delivered'

        ];

    }

    public function headings() : array
    {
        return [
            'PO Number',
            'Supplier',
            'Waybill',
            'Supplier Committed Date',
            'Actual Delivery Date',
            'Status'
        ];

            
    }
}
