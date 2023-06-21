<?php

namespace App\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use App\logistics;
use DB;

class DeliveryStatus implements FromCollection, WithMapping, WithHeadings
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
        $qry = logistics::whereNotNull('id');

        if(!isset($this->req->status)){

            $deliveries = $qry->where('status','Pending')->orderBy('expectedDeliveryDate','asc');

        } else {

            if($this->req->status == 'Delivered'){

                $deliveries = $qry->where('status','Delivered')->whereBetween('actualDeliveryDate',[$this->req->from,$this->req->to])->orderBy('actualDeliveryDate','asc');

            } elseif($this->req->status == 'All'){

                $deliveries = $qry->where('status','<>','Delivered')->whereBetween('expectedDeliveryDate',[$this->req->from,$this->req->to])->orderBy('expectedDeliveryDate','asc');

            } else {
                $deliveries = $qry->where('status',$this->req->status)->orderBy('expectedDeliveryDate','asc');
            }


        }

        return $deliveries->get();

    }

    public function map($deliveries) : array {
        
        if(!isset($this->req->status)){

            return [
                    $deliveries->po_details->poNumber,
                    $deliveries->po_details->supplier_name->name,
                    $deliveries->log_type,
                    $deliveries->expectedDeliveryDate,
                    $deliveries->status
                ];

        } else {

            if($this->req->status == 'Pending'){
                return [
                    $deliveries->po_details->poNumber,
                    $deliveries->po_details->supplier_name->name,
                    $deliveries->log_type,
                    $deliveries->expectedDeliveryDate,
                    $deliveries->status
                ];
            }

            if($this->req->status == 'In-Transit'){
                return [
                    $deliveries->po_details->poNumber,
                    $deliveries->po_details->supplier_name->name,
                    $deliveries->waybill,
                    $deliveries->log_type,
                    $deliveries->expectedDeliveryDate,
                    $deliveries->actualManufacturingDate,
                    $deliveries->departure_dt,
                    $deliveries->portArrivalDate,
                    $deliveries->status
                ];
            }

            if($this->req->status == 'Custom Clearing' || $this->req->status == 'For Pick-Up'){
                return [
                    $deliveries->po_details->poNumber,
                    $deliveries->po_details->supplier_name->name,
                    $deliveries->waybill,
                    $deliveries->log_type,
                    $deliveries->expectedDeliveryDate,
                    $deliveries->customStartDate,
                    $deliveries->customClearedDate,
                    $deliveries->status
                ];
            }

            if($this->req->status == 'All' || $this->req->status == 'Delivered'){
                return [
                    $deliveries->po_details->poNumber,
                    $deliveries->po_details->supplier_name->name,
                    $deliveries->waybill,
                    $deliveries->log_type,
                    $deliveries->expectedDeliveryDate,
                    $deliveries->actualManufacturingDate,
                    $deliveries->departure_dt,
                    $deliveries->portArrivalDate,
                    $deliveries->customStartDate,
                    $deliveries->customClearedDate,
                    $deliveries->actualDeliveryDate,
                    $deliveries->status
                ];
            }
        }

        

    }

    public function headings() : array
    {
        if(!isset($this->req->status)){

            return [
                'PO Number',
                'Supplier',
                'Type',
                'Supplier Committed Date',
                'Status'
            ];

        } else {

            if($this->req->status == 'Pending'){
                return [
                    'PO Number',
                    'Supplier',
                    'Type',
                    'Supplier Committed Date',
                    'Status'
                ];
            }

            if($this->req->status == 'In-Transit'){
                return [
                    'PO Number',
                    'Supplier',
                    'Waybill',
                    'Type',
                    'Supplier Committed Date',
                    'Actual Manufactured Date',
                    'Departure Date',
                    'Port Arrival Date',
                    'Status'
                ];
            }

            if($this->req->status == 'Custom Clearing' || $this->req->status == 'For Pick-Up'){
                return [
                    'PO Number',
                    'Supplier',
                    'Waybill',
                    'Type',
                    'Supplier Committed Date',
                    'Custom Start Date',
                    'Custom Cleared Date',
                    'Status'
                ];
            }

            if($this->req->status == 'All' || $this->req->status == 'Delivered'){
                return [
                    'PO Number',
                    'Supplier',
                    'Waybill',
                    'Type',
                    'Supplier Committed Date',
                    'Actual Manufactured Completion Date',
                    'Departure Date',
                    'Port Arrival Date',
                    'Custom Start Date',
                    'Custom Cleared Date',
                    'Delivery Date',
                    'Status'
                ];
            }
        }   
    }
}
