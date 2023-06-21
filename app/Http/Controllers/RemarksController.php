<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\remarks;

class RemarksController extends Controller
{
    public function list($remarks){
    	
    	$rs = remarks::where('logisticsId','=',$remarks)->get();
    	
    	$data = '<table class="table table-hover table-striped text-center"><tbody>';

    	foreach($rs as $r){
    		$data.='
				<tr>
					<td>'.$r->remarks.'</td>
					<td>'.$r->updated_at.'</td>
    			</tr>';
    	}

    	$data.='</tbody></table>';
    	return $data;
    }
}
