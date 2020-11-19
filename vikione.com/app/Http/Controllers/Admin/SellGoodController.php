<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SellGood;
use App\Models\User;
use Auth;
use Carbon\Carbon;


class SellGoodController extends Controller
{
    public function index(Request $request,$status=''){
        if($status==''){
            $sellgoods = SellGood::paginate(10);
        }else{
            $sellgoods = SellGood::where('status',$status)->paginate(10);
        }
        if($request->s){
            $sellgoods  = SellGood::where('id',$request->s)->paginate(10);
        }
        $is_page = (empty($status) ? 'all' : $status);
        
        return view('admin.sell_goods', compact('sellgoods','is_page'));
    }

    public function update(Request $request){
        $data['updated_at']= Carbon::now();
        $id = $request->id;
        $sellgood = SellGood::findOrFail($id);
        $buyer = User::findOrFail($sellgood->buyer);
        $seller = User::findOrFail($sellgood->seller);
        if($sellgood && $buyer && $seller){
            $data['status']= $request->status;
            SellGood::find($id)->update($data);
            if($request->status == 'approved'){
                User::where('id',$seller->id)
                ->update(['tokenBalance2'=>($seller->tokenBalance2+($sellgood->amount*80)/100)]);
            }elseif($request->status == 'canceled'){
                User::where('id',$buyer->id)
                ->update(['tokenBalance2'=>($buyer->tokenBalance2+($sellgood->amount*80)/100)]);
            }else{
                return false;
            }  
            return true;          
        }else{
            return false;
        }
        
    }
    
}
