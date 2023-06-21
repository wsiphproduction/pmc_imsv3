<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable  as AuditableContract;
use OwenIt\Auditing\Auditable; 

use \Carbon\Carbon;

class PaymentSchedule extends Model  implements AuditableContract
{

    use Auditable;

	protected $guarded = [];
	
	public $table = 'payment_schedule';

	public $timestamps = false;

    protected $auditInclude = [
        'paymentDate',
        'amount',
        'poId',
        'isPaid',
        'actualPaymentDate',
        'remarks',
        'origPaymentDate',
        'files',
        'addedBy',
        'addedDate',
        'payment_type'
    ];
	public function po_details()
    {
        return $this->belongsTo('App\PO', 'poId');
    }

	public static function is_paid($date){

        if($date < Carbon::today()){
            return '<span class="label label-danger">OVERDUE</span>'; 
        } else {
            return '<span class="label label-default">PENDING</span>';
        }
		
	}

    public static function count_payments($id)
    {
        $total = PaymentSchedule::where('poId',$id)->count();

        return $total;
    }

	public static function paymnt_aging($payment_date){

        $diff = Carbon::parse($payment_date)->diffInDays(Carbon::today());

        return $diff.' day(s)';
    }

    public static function totalOverduePayables()
    {
    	$total = PaymentSchedule::where('paymentDate','<',Carbon::today())->where('isPaid',0)->count();

    	return $total;	
    }

    public static function total_payment_for_the_week()
    {
    	$data = PaymentSchedule::where('isPaid',0)->whereBetween('paymentDate',[Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->count();

    	return $data;
    }

    public static function header_pending_payments()
    {
        $payments = PaymentSchedule::where('isPaid',0)->whereBetween('paymentDate',[Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])->get();
        $output = "";

        foreach($payments as $p){
            $output .= '<li>
                            <a href="javascript:;">
                                <span class="details">
                                    <span class="label label-sm label-icon label-success md-skip">PO#'.$p->po_details->poNumber.'</span> '.$p->paymentDate.'</span>
                                <span class="time">'.number_format($p->amount,2).'</span>
                            </a>
                        </li>';
        }

        return $output;
    }
}
