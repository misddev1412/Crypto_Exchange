<?php
namespace App\Http\Controllers;

/**
* Export Controller
*
* Export the database information as csv.
*
* @package TokenLite
* @author Softnio
* @version 1.0
* @since 1.1.0
*/

use App\Models\KYC;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    /**
     * Export DB Table as CSV.
     *
     * @version 1.0
     * @since 1.1.0
     */
    public function export(Request $request, $which='', $type='')
    {
        if ($which == '') {
            abort(404);
        }

        if(!is_super_admin()){
            if($request->ajax){
                $result['msg'] = 'warning';
                $result['message'] = "You do not have permission to download.";
                return response()->json($result);
            }
            $route = ($which) ? $which : 'home';
            return redirect()->route('admin.'.$which)->with(['global' => 'You do not have permission to download.']);
        }
        if (empty(env_file()) || !nio_status() || empty(app_key())) {
            $response['msg'] = 'warning';
            $response['status'] = 'die';
            $response['message'] = __('auth.health.save_action');
            if ($request->ajax()) {
                return response()->json($response);
            }
            return back()->with([$response['msg'] => $response['message']]);
        }

        switch ($which) {
            case 'transactions':
                return $this->transactions($request, $type);
                break;
            
            case 'users':
                return $this->users($request, $type);
                break;
            
            case 'kycs':
                return $this->kycs($request, $type);
                break;
            
            default:
                abort(404);
                break;
        }
    }

    /**
     * Export Transaction as CSV.
     *
     * @version 1.0
     * @since 1.1.0
     */
    protected function transactions(Request $request, $type='')
    {
        $name = $request->get('table') !== null ? $request->get('table') : 'transactions';
        $file_name = $name.'-'.(($type) ? $type : 'full').'-'.date('m-d-Y');
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=".$file_name.".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $order_by = gmvl('tnx_order_by', 'id');
        $ordered  = gmvl('tnx_ordered', 'DESC');
        $trans = Transaction::whereNotIn('status', ['deleted', 'new'])->whereNotIn('tnx_type', ['withdraw'])->orderBy($order_by, $ordered)->get();
        if($request->s){
            $trans  = Transaction::AdvancedFilter($request)
                                ->orderBy($order_by, $ordered)->get();
        }
        if($request->filter){
            $trans = Transaction::AdvancedFilter($request)
                                ->orderBy($order_by, $ordered)->get();
        }

        if($type == 'entire'){
            $head = ['tnx_id', 'total_tokens', 'tokens', 'bonus_on_base', 'bonus_on_token', 'total_bonus', 'tnx_type', 'status', 'stage', 'user', 'amount', 'currency', 'receive_amount', 'receive_currency', 'base_amount', 'base_currency', 'payment_method', 'tnx_time', 'checked_time', 'details'];

            $callback = function() use ($trans, $head)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $head);

                foreach($trans as $item) {
                    fputcsv($file, [$item->tnx_id, $item->total_tokens, $item->tokens, $item->bonus_on_base, $item->bonus_on_token, $item->total_bonus, $item->tnx_type, $item->status, (get_stage($item->stage, 'name')), (get_user($item->user, 'email')), $item->amount, $item->currency, $item->receive_amount, $item->receive_currency, $item->base_amount, $item->base_currency, $item->payment_method, $item->tnx_time, $item->checked_time, $item->details]);
                }
                fclose($file);
            };
        } elseif($type == 'compact') {
            $head = ['tnx_id', 'total_tokens', 'user', 'tnx_type', 'status'];

            $callback = function() use ($trans, $head)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $head);

                foreach($trans as $item) {
                    fputcsv($file, [$item->tnx_id, $item->total_tokens, (get_user($item->user, 'email')), $item->tnx_type, $item->status]);
                }
                fclose($file);
            };
       } else{
            $head = ['tnx_id', 'total_tokens', 'amount', 'status', 'tnx_type', 'stage',  'user', 'tnx_time', 'checked_time' ];

            $callback = function() use ($trans, $head)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $head);

                foreach($trans as $item) {
                    fputcsv($file, [$item->tnx_id, $item->total_tokens, $item->amount, $item->status, $item->tnx_type, (get_stage($item->stage, 'name')), (get_user($item->user, 'email')), $item->tnx_time, $item->checked_time ]);
                }
                fclose($file);
            };
        }
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export User as CSV.
     *
     * @version 1.0
     * @since 1.1.0
     */
    protected function users(Request $request, $type='')
    {
        $name = $request->get('table') !== null ? $request->get('table') : 'users';
        $file_name = $name.'-'.(($type) ? $type : 'full').'-'.date('m-d-Y');
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=".$file_name.".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $order_by   = (gmvl('user_order_by', 'id')=='token') ? 'tokenBalance' : gmvl('user_order_by', 'id');
        $ordered    = gmvl('user_ordered', 'DESC');
        $items = User::whereNotIn('status', ['deleted'])->orderBy($order_by, $ordered)->get();
        if($request->s){
            $items = User::AdvancedFilter($request)
                        ->orderBy($order_by, $ordered)->get();
        }

        if ($request->filter) {
            $items = User::AdvancedFilter($request)
                        ->orderBy($order_by, $ordered)->get();
        }

        if($type == 'entire'){
            $head = ['name', 'email', 'wallet_type', 'wallet_address', 'token', 'contributed', 'status', 'nationality', 'mobile', 'dob', 'referral', 'register_method', 'join_at'];

            $callback = function() use ($items, $head)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $head);

                foreach($items as $item) {
                    fputcsv($file, [$item->name, $item->email, $item->walletType, $item->walletAddress, $item->tokenBalance, $item->contributed, $item->status, $item->nationality, $item->mobile, $item->dateOfBirth, $item->referral, $item->registerMethod, $item->created_at]);
                }
                fclose($file);
            };
        } elseif($type == 'compact') {
            $head = ['name', 'email', 'wallet_address', 'token'];

            $callback = function() use ($items, $head)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $head);

                foreach($items as $item) {
                    fputcsv($file, [$item->name, $item->email, $item->walletAddress, $item->tokenBalance]);
                }
                fclose($file);
            };
        }else{
            $head = ['name', 'email', 'wallet_address', 'token', 'contributed', 'status'];

            $callback = function() use ($items, $head)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $head);

                foreach($items as $item) {
                    fputcsv($file, [$item->name, $item->email, $item->walletAddress, $item->tokenBalance, $item->contributed, $item->status]);
                }
                fclose($file);
            };
        }
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export KYC as CSV.
     *
     * @version 1.0
     * @since 1.1.0
     */
    protected function kycs(Request $request, $type='')
    {
        $name = $request->get('table') !== null ? $request->get('table') : 'kyc';
        $file_name = $name.'-'.(($type) ? $type : 'full').'-'.date('m-d-Y');
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=".$file_name.".csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $items = KYC::where('status','!=','deleted')->get();
        if($request->s){
            $items = KYC::AdvancedFilter($request)->get();
        }

        if ($request->filter) {
            $items = KYC::AdvancedFilter($request)->get();
        }
        if($type == 'entire'){
            $head = ['user_id', 'name', 'email', 'wallet_type', 'wallet_address', 'phone', 'dob', 'gender', 'address', 'city', 'country',  'doc_type', 'doc1', 'doc2', 'doc3', 'status'];

            $callback = function() use ($items, $head)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $head);

                foreach($items as $item) {
                    fputcsv($file, [$item->userId, $item->firstName.' '.$item->lastName, $item->email, $item->walletName, $item->walletAddress, $item->phone, $item->dob, $item->gender, $item->address.', '.$item->address1, $item->city.'-'.$item->zip, $item->country, $item->documentType, $item->document, $item->document2,  $item->document3, $item->status]);
                }
                fclose($file);
            };
        }else{
            $head = ['user_id','name', 'email', 'wallet_address', 'phone', 'dob', 'city', 'doc_type', 'status'];

            $callback = function() use ($items, $head)
            {
                $file = fopen('php://output', 'w');
                fputcsv($file, $head);

                foreach($items as $item) {
                    fputcsv($file, [$item->userId, $item->firstName.' '.$item->lastName, $item->email, $item->walletAddress,$item->phone, $item->dob, $item->city.'-'.$item->zip, $item->documentType,  $item->status]);
                }
                fclose($file);
            };
        }
        return response()->stream($callback, 200, $headers);
    }

}
