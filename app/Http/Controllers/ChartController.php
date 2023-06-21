<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Carbon\Carbon;
use Auth;
use DB;

use App\PO;

class ChartController extends Controller
{
    public function deliveries_per_origin()
    {
        $result = PO::select('origin',DB::raw('COUNT(origin) as totalDelivery'))->groupBy('origin')->get();

        return Response($result);
    }
}
