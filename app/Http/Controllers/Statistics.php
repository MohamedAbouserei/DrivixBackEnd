<?php

namespace App\Http\Controllers;

use App\Order;
use App\Sparesshop;
use App\Workshop;
use Illuminate\Http\Request;
use Carbon\Carbon;
class Statistics extends Controller
{
    //
    public function index () {
        return view('statistics.index');
    }

    public function getStatAjax (Request $request) {
        if( $request->type === 'sps' ) {
            $from = new Carbon($request->s_date);
            $to = new Carbon($request->to_date);
            $return_dates = [];
            $return_counts = [];
            do {
                $to_temp = new Carbon($from);
                $from = new Carbon( $from->addMonths(1) );
                $spares = Sparesshop::whereBetween('created_at' , [$to_temp  , $from])->count();
                // get data to return it
                $return_dates[] = $from->toFormattedDateString();
                $return_counts[] = $spares;
            }while($from <= $to);
            $data = ['dates' => $return_dates , 'stat'=>$return_counts];
            return response()->json($data);
        }
        if( $request->type === 'ws' ) {
            $from = new Carbon($request->s_date);
            $to = new Carbon($request->to_date);
            $return_dates = [];
            $return_counts = [];
            do {
                $to_temp = new Carbon($from);
                $from = new Carbon( $from->addMonths(1) );
                $spares = Workshop::whereBetween('created_at' , [$to_temp  , $from])->count();
                // get data to return it
                $return_dates[] = $from->toFormattedDateString();
                $return_counts[] = $spares;
            }while($from <= $to);
            $data = ['dates' => $return_dates , 'stat'=>$return_counts];
            return response()->json($data);
        }
        if( $request->type === 'o' ) {

            $from = new Carbon($request->s_date);
            $to = new Carbon($request->to_date);
            $return_dates = [];
            $return_counts = [];
            do {
                $to_temp = new Carbon($from);
                $from = new Carbon( $from->addMonths(1) );
                $spares = Order::whereBetween('created_at' , [$to_temp  , $from])->count();
                // get data to return it
                $return_dates[] = $from->toFormattedDateString();
                $return_counts[] = $spares;
            }while($from <= $to);
            $data = ['dates' => $return_dates , 'stat'=>$return_counts];
            return response()->json($data);
        }
    }
}
