<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SellGood;
use App\Models\User;
use App\Http\Requests\SellGoodRequest;
use Auth;
use Carbon\Carbon;

class SellGoodController extends Controller
{
    public function send(Request $request){
        $seller = Auth::user();
        $buyer = User::where('email',$request->email)->first();
        
        if($buyer){
            if($buyer->email == $seller->email){
                $ret['msg'] = 'error';
                $ret['message'] = __('Email is invalid');
            }else{
                $data['status']="pending";
                $data['buyer']=$buyer->id;
                $data['seller']=$seller->id;
                $data['amount']=$request->amount;
                $data['details']=$request->detail;
                $data['created_at']= Carbon::now();
                $data['updated_at']= Carbon::now();
                $sellgoods = SellGood::insert($data);

                $ret['msg']     = 'success';
                $ret['message'] = __('Send successful!');
                $ret['reload']  = true;
            }
        }else{
            $ret['msg'] = 'error';
            $ret['message'] = __('Email is invalid');
        }

        if ($request->ajax()) {
            return response()->json($ret);
        }
        
        
        
    }
    public function show(){
        $sellgoods = SellGood::where('buyer', Auth::user()->id)->orWhere('seller', Auth::user()->id)->get();
        return view('user.sell_goods',compact('sellgoods'));
    }

    public function update(Request $request){
        $data['updated_at']= Carbon::now();
        $id = $request->id;
        $sellgood = SellGood::findOrFail($id);
        if($sellgood){
            $data['status']= $request->status;
            $user = Auth::user();
            if($user->tokenBalance2 >= $sellgood->amount || $request->status == 'canceled'){
                SellGood::find($id)->update($data);
                if($request->status == 'processing'){
                    User::where('id',$user->id)->update(['tokenBalance2'=>($user->tokenBalance2-($sellgood->amount*80)/100)]);
                }
            }else{
                return false;
            }
            
        }else{
            return false;
        }
        
    }
}
