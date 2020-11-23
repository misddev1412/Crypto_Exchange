<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\BuySell;
use App\Models\BuySellAdminLogs as Logs;

class BuySellController extends Controller
{

    public function index (Request $request, $status = ''){

        if($status==''){
            $buysell = BuySell::paginate(10);
        }else{
            $buysell = BuySell::where('status',$status)->paginate(10);
        }
        if($request->s){
            $buysell  = BuySell::where('id',$request->s)->paginate(10);
        }
        $is_page = (empty($status) ? 'all' : $status);

        return view('admin.buy_sell', compact('buysell','is_page'));
    }

    public function update(Request $request){
        $data['updated_at']= Carbon::now();
        $id = $request->id;

        $buysell = BuySell::findOrFail($id);
        $buyer = User::findOrFail($buysell->buyer_id);
        $seller = User::findOrFail($buysell->seller_id);

        $buyer_receive =  $buysell->amount + (($buysell->amount * 20) / 100);
        $seller_decrease = $buysell->amount + (($buysell->amount * 25) / 100);
        $admin_receive = (($buysell->amount * 5) / 100);

        if($buysell && $buyer && $seller){
            $data['status']= $request->status;
            BuySell::find($id)->update($data);
            if($request->status == 'approved'){
                User::where('id',$buyer->id)
                ->update(['tokenBalance2'=>($buyer->tokenBalance2 + $buyer_receive )]);

                User::where('id',$seller->id)
                ->update(['tokenBalance2'=>($seller->tokenBalance2 - $seller_decrease )]);

                Logs::insert([
                    'transaction_id' => $buysell->id,
                    'desc' => 'Admin Fee 5%',
                    'amount' => $admin_receive,
                    'created_at' => Carbon::now()
                ]);

            }elseif($request->status == 'canceled'){
                User::where('id',$seller->id)
                ->update(['tokenBalance2'=>($seller->tokenBalance2 + $seller_decrease), 'freezeBalance' => $seller->freezeBalance - $seller_decrease ]);
            }else{
                return false;
            }  
            return true;          
        }else{
            return false;
        }
        
    }
}
