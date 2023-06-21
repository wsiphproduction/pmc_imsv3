<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use \Carbon\Carbon;

use App\supplier;
use App\PaymentSchedule;
use App\logistics;
use App\drr;
use PO;

class AnalyticsController extends Controller
{
    
    public function monthly() {   

        $start = (isset($_GET['start'])) ? $_GET['start'] : date('Y-m-d',strtotime("-30 days"));
        $end = (isset($_GET['end'])) ? $_GET['end'] : date('Y-m-d');
        
        $po = $this->committed_delivery($start,$end);
        $data['start'] = $start ;
        $data['end'] = $end;
        $data['com_pass'] = 0;
        $data['com_fail'] = 0;
        $data['raw'] = '<table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>PO</th>
                                    <th>Inco Terms</th>
                                    <th>Supplier</th>
                                    <th>Payment Status</th>
                                    <th>Waybill</th>
                                    <th>Commitment</th>
                                    <th>Actual Delivery</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>';
        foreach($po as $p){
            if($p->performance == 'PASSED'){
                $data['com_pass']++;
            }
            else{
                $data['com_fail']++;
            }

            $data['raw'].='
                <tr>
                    <td><a href="/ims/po/details/'.$p->id.'" target="_blank">'.$p->poNumber.'</a></td>
                    <td>'.$p->incoterms.'</td>
                    <td>'.$p->suppliername.'</td>
                    <td>'.$p->paymentStatus.'</td>
                    <td>'.$p->wayBill.'</td>
                    <td>'.$p->commitment_delivery.'</td>
                    <td>'.$p->actual_delivery.'</td>
                    <td>'.$p->performance.'</td>
                </tr>
            ';

        }

        $data['raw'].='
            </tbody></table>
        ';
        //dd($data);
        return view('analytics.monthly',compact('data'));
       
    }

    public function monthly_all() {   

        $start = (isset($_GET['start'])) ? $_GET['start'] : date('Y-m-d',strtotime("-30 days"));
        $end = (isset($_GET['end'])) ? $_GET['end'] : date('Y-m-d');
        
        $start = '2019-01-01';
        $end = '2020-05-31';
        $raw='<table class="table table-striped">
                <thead>
                    <tr>
                        <th>Month-Year</th>
                        <th>Pass</th>
                        <th>Fail</th>
                    </tr>
                </thead>
                <tbody>

                ';
        $dataset = '[';
        $dataset_passed = '{"seriesname": "Passed","data": [';
        $dataset_failed = '{"seriesname": "Failed","color":"#F6201D","data": [';
        $categories = '[{"category": [';
        $range = $this->getMonthsInRange($start,$end);
        foreach($range as $r){            
            $nstart = date($r['year']."-".$r['month']."-01");
            $nend = date('Y-m-t',strtotime($nstart));
            $passed = $this->committed_delivery_passed($nstart,$nend);
            $failed = $this->committed_delivery_failed($nstart,$nend);

            $categories.='{"label": "'.$r['year']."-".$r['month'].'"},';

            $dataset_passed.='{"value": "'.$passed.'"},';

            $dataset_failed.='{"value": "'.$failed.'"},';
            $raw.='<tr>
                        <td><a target="_blank" href="/ims/analytics/monthly?start='.$nstart.'&end='.$nend.'">'.date('F y',strtotime($r['year']."-".$r['month']."-1")).'</a></td>
                        <td>'.$passed.'</td>
                        <td>'.$failed.'</td>
            </tr>';
            
        }
        $raw.='</tbody>
            </table>';
        $categories = rtrim($categories,',');
        $dataset_failed = rtrim($dataset_failed,',');
        $dataset_failed.=']},';
        $dataset_passed = rtrim($dataset_passed,',');
        $dataset_passed.=']}';
        $categories.=']}]';
        $dataset.=$dataset_failed.$dataset_passed.']';

       
        return view('analytics.monthly_all',compact('dataset','categories','raw'));
       
    }

    public function monthly_dateneeded() {   

        $start = (isset($_GET['start'])) ? $_GET['start'] : date('Y-m-d',strtotime("-30 days"));
        $end = (isset($_GET['end'])) ? $_GET['end'] : date('Y-m-d');
        
        $po = $this->committed_delivery($start,$end);
        $data['start'] = $start ;
        $data['end'] = $end;
        $data['com_pass'] = 0;
        $data['com_fail'] = 0;  
        $data['raw'] = '<table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>PO</th>
                                    <th>Inco Terms</th>
                                    <th>Supplier</th>
                                    <th>Payment Status</th>
                                    <th>Waybill</th>
                                    <th>Commitment</th>
                                    <th>Actual Delivery</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>';
        foreach($po as $p){
            if($p->performance == 'PASSED'){
                $data['com_pass']++;
            }
            else{
                $data['com_fail']++;
            }

            $data['raw'].='
                <tr>
                    <td><a href="/ims/po/details/'.$p->id.'" target="_blank">'.$p->poNumber.'</a></td>
                    <td>'.$p->incoterms.'</td>
                    <td>'.$p->suppliername.'</td>
                    <td>'.$p->paymentStatus.'</td>
                    <td>'.$p->wayBill.'</td>
                    <td>'.$p->commitment_delivery.'</td>
                    <td>'.$p->actual_delivery.'</td>
                    <td>'.$p->performance.'</td>
                </tr>
            ';

        }

        $data['raw'].='
            </tbody></table>
        ';
        //dd($data);
        return view('analytics.monthly',compact('data'));
       
    }

    public function monthly_dateneeded_all() {   

        $start = (isset($_GET['start'])) ? $_GET['start'] : date('Y-m-d',strtotime("-30 days"));
        $end = (isset($_GET['end'])) ? $_GET['end'] : date('Y-m-d');
        
        $start = '2019-01-01';
        $end = '2020-05-31';
        $raw='<table class="table table-striped">
                <thead>
                    <tr>
                        <th>Month-Year</th>
                        <th>Pass</th>
                        <th>Fail</th>
                    </tr>
                </thead>
                <tbody>

                ';
        $dataset = '[';
        $dataset_passed = '{"seriesname": "Passed","data": [';
        $dataset_failed = '{"seriesname": "Failed","color":"#F6201D","data": [';
        $categories = '[{"category": [';
        $range = $this->getMonthsInRange($start,$end);
        foreach($range as $r){            
            $nstart = date($r['year']."-".$r['month']."-01");
            $nend = date('Y-m-t',strtotime($nstart));
            $passed = $this->committed_delivery_passed($nstart,$nend);
            $failed = $this->committed_delivery_failed($nstart,$nend);

            $categories.='{"label": "'.$r['year']."-".$r['month'].'"},';

            $dataset_passed.='{"value": "'.$passed.'"},';

            $dataset_failed.='{"value": "'.$failed.'"},';
            $raw.='<tr>
                        <td><a target="_blank" href="/ims/analytics/monthly?start='.$nstart.'&end='.$nend.'">'.date('F y',strtotime($r['year']."-".$r['month']."-1")).'</a></td>
                        <td>'.$passed.'</td>
                        <td>'.$failed.'</td>
            </tr>';
            
        }
        $raw.='</tbody>
            </table>';
        $categories = rtrim($categories,',');
        $dataset_failed = rtrim($dataset_failed,',');
        $dataset_failed.=']},';
        $dataset_passed = rtrim($dataset_passed,',');
        $dataset_passed.=']}';
        $categories.=']}]';
        $dataset.=$dataset_failed.$dataset_passed.']';

       
        return view('analytics.monthly_all',compact('dataset','categories','raw'));
       
    }

    public function date_needed($start,$end){
        $committed_delivery = DB::table('analytic_logistics_date_needed')->whereRaw("commitment_delivery >= '".$start."' and commitment_delivery <= '".$end."'")->get();

        return $committed_delivery;
    }

    public function date_needed_passed($start,$end){
        $committed_delivery = DB::table('analytic_logistics_date_needed')->whereRaw("commitment_delivery >= '".$start."' and commitment_delivery <= '".$end."'")->where('performance','PASSED')->count();

        return $committed_delivery;
    }

    public function date_needed_failed($start,$end){
        $committed_delivery = DB::table('analytic_logistics_date_needed')->whereRaw("commitment_delivery >= '".$start."' and commitment_delivery <= '".$end."'")->where('performance','FAILED')->count();

        return $committed_delivery;
    }

    public function committed_delivery($start,$end){
    	$committed_delivery = DB::table('analytic_logistics')->whereRaw("commitment_delivery >= '".$start."' and commitment_delivery <= '".$end."'")->get();

    	return $committed_delivery;
    }

    public function committed_delivery_passed($start,$end){
        $committed_delivery = DB::table('analytic_logistics')->whereRaw("commitment_delivery >= '".$start."' and commitment_delivery <= '".$end."'")->where('performance','PASSED')->count();

        return $committed_delivery;
    }

    public function committed_delivery_failed($start,$end){
        $committed_delivery = DB::table('analytic_logistics')->whereRaw("commitment_delivery >= '".$start."' and commitment_delivery <= '".$end."'")->where('performance','FAILED')->count();

        return $committed_delivery;
    }

    public function getMonthsInRange($startDate, $endDate) {
        $months = array();
        while (strtotime($startDate) <= strtotime($endDate)) {
            $months[] = array('year' => date('Y', strtotime($startDate)), 'month' => date('m', strtotime($startDate)), );
            $startDate = date('d M Y', strtotime($startDate.
                '+ 1 month'));
        }

        return $months;
    }

    public function po_summary() {   

        $start = (isset($_GET['start'])) ? $_GET['start'] : date('Y-m-d',strtotime("-30 days"));
        $end = (isset($_GET['end'])) ? $_GET['end'] : date('Y-m-d');
        
        $po = $this->committed_delivery($start,$end);
        $data['start'] = $start ;
        $data['end'] = $end;
        $data['com_pass'] = 0;
        $data['com_fail'] = 0;
        $data['raw'] = '<table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>PO</th>
                                    <th>Inco Terms</th>
                                    <th>Supplier</th>
                                    <th>Payment Status</th>
                                    <th>Waybill</th>
                                    <th>Commitment</th>
                                    <th>Actual Delivery</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>';
        foreach($po as $p){
            if($p->performance == 'PASSED'){
                $data['com_pass']++;
            }
            else{
                $data['com_fail']++;
            }

            $data['raw'].='
                <tr>
                    <td><a href="/ims/po/details/'.$p->id.'" target="_blank">'.$p->poNumber.'</a></td>
                    <td>'.$p->incoterms.'</td>
                    <td>'.$p->suppliername.'</td>
                    <td>'.$p->paymentStatus.'</td>
                    <td>'.$p->wayBill.'</td>
                    <td>'.$p->commitment_delivery.'</td>
                    <td>'.$p->actual_delivery.'</td>
                    <td>'.$p->performance.'</td>
                </tr>
            ';

        }

        $data['raw'].='
            </tbody></table>
        ';
        //dd($data);
        return view('analytics.monthly',compact('data'));
       
    }

    public function waybill_summary(){
        $committed_delivery = logistics::whereNotNull('actualDeliveryDate')->get();
        $x=0;
        foreach($committed_delivery as $c){
            $x++;
            

            $data['manufacturing'.$x] = $this->dateDiff($c->actualManufacturingDate,$c->departure_dt);
            $data['portArrival'.$x] = $this->dateDiff($c->departure_dt,$c->portArrivalDate);
            $data['customStart'.$x] = $this->dateDiff($c->portArrivalDate,$c->customStartDate);
            $data['custom'.$x] = $this->dateDiff($c->customStartDate,$c->customClearedDate);
            $data['actualDelivery'.$x] = $this->dateDiff($c->customClearedDate,$c->actualDeliveryDate);            
            $data['total_days'.$x] = $data['manufacturing'.$x] + $data['portArrival'.$x] + $data['customStart'.$x] + $data['custom'.$x] + $data['actualDelivery'.$x];
        }
        
        return view('analytics.po_summary',compact('data'));
    }

    public function dateDiff($d1, $d2) {

        // Return the number of days between the two dates:    
        return round(abs(strtotime($d2) - strtotime($d1))/86400);

    }



}
