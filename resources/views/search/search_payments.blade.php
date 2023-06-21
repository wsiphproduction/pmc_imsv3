
@forelse($payments as $payment)
    <tr style="background: {{ \App\PaymentSchedule::is_weekly_payment(date('ymd',strtotime($payment->paymentDate))) }}">
        <td>{{ $payment->poNumber }}</td>
        <td>{{ $payment->name }}</td>
        <td>{{ $payment->paymentDate }}</td>
        <td>{{ $payment->actualPaymentDate }}</td>
        <td class="text-right">{{ number_format($payment->amount,2) }}</td>
        <td>{{ $payment->remarks }}</td>
        <td>{!! \App\PaymentSchedule::is_paid($payment->isPaid) !!}</td>
        <td>
            @if($payment->isPaid == 1)
                <a href="{{env('APP_URL')}}/ims/po/details/{{$payment->poId}}" class="btn btn-sm purple"><i class="fa fa-eye"></i></a>
            @else
                <div class="btn-toolbar margin-bottom-2">
                    <div class="btn-group btn-group-sm btn-group-solid">
                        <a href="#paidModal" data-toggle="modal" data-amount="{{number_format($payment->amount,2)}}" data-poid="{{$payment->poId}}" data-ppid="{{ $payment->id }}" data-pon="{{ $payment->poNumber }}" class="paid btn btn-sm btn-danger">
                            <i class="icon-check"></i>
                        </a>
                        <a href="{{env('APP_URL')}}/ims/payment/edit/{{$payment->id}}" class="btn btn-sm btn-primary">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="{{env('APP_URL')}}/ims/po/details/{{$payment->poId}}" class="btn btn-sm purple">
                            <i class="fa fa-eye"></i>
                        </a>
                    </div>
                </div>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8"><center>No Payments found.</center></td>
    </tr>
@endforelse