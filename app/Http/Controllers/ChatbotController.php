<?php

namespace App\Http\Controllers;

use App\Chatbot;
use Illuminate\Http\Request;
use Searchy;
use DB;
use Response;
use Exception;
class ChatbotController extends Controller
{
    public function split()
    {
        $tags=Chatbot::get();
        $step=0;
        //return $tags[0]->tags;
        //
        $Array = array();
        foreach ($tags as $key=>$tag) {
            foreach (explode(',', $tag->tags) as $value) {
                $Array[$key][$step++] = $value;
            }
            $step=0;
        }
        return $Array;
    }
    public function search(Request $request)
    {
        try {
            $tags=[];
            $step=0;
            $match=array();
            $mat=array();

            $sentence=$request->sentence;
            foreach (explode(' ', $sentence) as $key=>$sen) {
                if (strlen($sen)>=3) {
                    $tags[$key] = Searchy::car_problems('tags')->query($sen)->get();
                }
            }

            foreach ($tags as $key => $value) {
                $int=count($value);
                for ($i=0; $i <$int ; $i++) {
                    $mat[$step]=$value[$i];
                    $step++;
                }
            }

            foreach ($mat as $key=>$tag) {
                $match[$tag->id][$key]=(int)$tag->relevance;
            }
            $bag=array();
            $sum=0;


            foreach ($match as $key => $value) {
                foreach ($value as $key2 => $val) {
                    $sum+=$val;
                }
                $bag[$key]=$sum;
                $sum=0;
            }
            $final=array();
            foreach ($bag as $key => $value) {
                break;
            }
            //return $bag;
            $value=array_keys($bag, max($bag));
            $query= Response::json(DB::table('car_problems')->where('id', $value[0])->first());
            return $query;
        }
        catch(Exception $ex)
        {
          return Response::json('Sentence not recognized',400);
        }
    }

}
