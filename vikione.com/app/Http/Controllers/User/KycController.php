<?php

namespace App\Http\Controllers\User;
/**
 * Kyc Controller
 *
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0.6
 */
use Auth;
use Validator;
use IcoHandler;
use App\Models\KYC;
use App\Models\User;
use App\Models\UserMeta;
use App\Helpers\ReCaptcha;
use Illuminate\Http\Request;
use App\Notifications\KycStatus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class KycController extends Controller
{
    // Enable recaptcha to the public form 
    use ReCaptcha;


    public function __construct()
    {
        if( application_installed()){
            if (get_setting('kyc_before_email') == '1' && !auth()->guest()) {
                return $this->middleware('verified')->except(['index']);
            }
        }
    }

    /**
     * Show the kyc status
     *
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function index()
    {
        $user_kyc = Auth::user()->kyc_info;

        return view('user.kyc', compact('user_kyc'));
    }

    /**
     * Show the kyc status
     *
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function view()
    {
        $kyc = Auth::user()->kyc_info;

        return view('user.kyc_details', compact('kyc'));
    }

    /**
     * Show the KYC Images
     *
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function get_documents($id, $doc)
    {
        $filename = KYC::FindOrFail($id)->document;
        if ($doc == 2) {
            $filename = KYC::FindOrFail($id)->document2;
        }
        if ($doc == 3) {
            $filename = KYC::FindOrFail($id)->document3;
        }
        if ($filename !== null) {
            $path = storage_path('app/' . $filename);
            if (!file_exists($path)) {
                abort(404);
            }
            $file = \File::get($path);
            $type = \File::mimeType($path);
            $response = response($file, 200)->header("Content-Type", $type);

            return $response;
        } else {
            return abort(404);
        }
    }

    /**
     * Show the kyc application
     *
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function application()
    {
        if (isset(Auth::user()->kyc_info->status)) {
            if (Auth::user()->kyc_info->status == 'pending') {
                return redirect()->route('user.kyc')->with(['info' => __('messages.kyc.wait')]);
            }
        }
        $countries = \IcoHandler::getCountries();
        $user_kyc = Auth::user()->kyc_info;
        if ($user_kyc == null) {
            $user_kyc = new KYC();
        }
        $title = KYC::documents();

        return view('user.kyc_application', compact('user_kyc', 'countries', 'title'));
    }

    /**
     * Submit the kyc form
     *
     * @return \Illuminate\Http\Response
     * @version 1.1
     * @since 1.0
     * @return void
     */
    public function submit(Request $request)
    {
        $type = $request->documentType;
        $validator = Validator::make($request->all(), KYC::rules(), [
            'document_one.required' => __('messages.kyc.forms.document', ['NAME' => KYC::documents($type ?? 'Document')]),
            'document_two.required' => __('messages.kyc.forms.document', ['NAME' => __('National ID Card Back-Side')]),
            'document_image_hand.required' => __('messages.kyc.forms.document', ['NAME' => __('Document on Hand')]),
        ]);
        if ($validator->fails()) {
            $msg = '';
            if ($validator->errors()->first()) {
                $msg = $validator->errors()->first();
            } else {
                $msg = __('messages.somthing_wrong');
            }

            $ret['msg'] = 'warning';
            $ret['message'] = $msg;
            return response()->json($ret);
        } else {
            $doc1 = $request->input('document_one');
            $doc2 = $request->input('document_two');
            $doc3 = $request->input('document_image_hand');
            $firstname = strip_tags($request->input('first_name'));
            $lastname = strip_tags($request->input('last_name'));

            $user = Auth::user();
            if (!$user) {
                $user = User::create([
                    'name' =>  $firstname . ' ' . $lastname,
                    'email' => $request->input('email'),
                    'password' => Hash::make( $request->input('password')),
                    'lastLogin' => date('Y-m-d H:i:s'),
                    'type' => 'user',
                    'registerMethod' => 'KYC',
                ]);
                if ($user) {
                    UserMeta::create([
                        'userId' => $user->id,
                    ]);
                }
            }

            $kyc_submit = new KYC();
            $kyc_submit->userId = $user->id;
            $kyc_submit->firstName = $firstname;
            $kyc_submit->lastName = $lastname;
            $kyc_submit->email = $user->email;
            $kyc_submit->phone = strip_tags($request->input('phone'));
            $kyc_submit->dob = $request->input('dob');
            $kyc_submit->gender = strip_tags($request->input('gender'));
            $kyc_submit->telegram = strip_tags($request->input('telegram'));

            $kyc_submit->country = strip_tags($request->input('country'));
            $kyc_submit->state = strip_tags($request->input('state'));
            $kyc_submit->city = strip_tags($request->input('city'));
            $kyc_submit->zip = strip_tags($request->input('zip'));
            $kyc_submit->address1 = strip_tags($request->input('address_1'));
            $kyc_submit->address2 = strip_tags($request->input('address_2'));

            $kyc_submit->documentType = $request->input('documentType');
            $kyc_submit->document = $doc1;
            $kyc_submit->document2 = $doc2;
            $kyc_submit->document3 = $doc3;
            $kyc_submit->status = 'pending';

            $kyc_submit->walletName = strip_tags($request->input('wallet_name'));
            $kyc_submit->walletAddress = strip_tags($request->input('wallet_address'));
            // $kyc_submit->save();

            $is_valid = (field_value('kyc_wallet', 'show') && !empty($request->input('wallet_address'))) ? IcoHandler::validate_address($request->input('wallet_address'), $request->input('wallet_name')) : true;
            if ($is_valid) {
                if ($kyc_submit->save()) {
                    try{
                        $user->notify(new KycStatus($kyc_submit));
                        // Notification::send($user, new KycStatus($kyc_submit));
                        $ret['msg'] = 'success';
                        $ret['message'] = __('messages.kyc.forms.submitted');
                        $ret['link'] = route('user.kyc') . '?thank_you=true';
                    }catch(\Exception $e){
                        $ret['msg'] = 'success';
                        $ret['message'] = __('messages.kyc.forms.submitted');
                        $ret['link'] = route('user.kyc') . '?thank_you=true';
                    }
                } else {
                    $ret['msg'] = 'error';
                    $ret['message'] = __('messages.kyc.forms.failed');
                }
            } else {
                if (empty(auth()->user())) {
                    $user->delete();
                }
                $ret['msg'] = 'warning';
                $ret['message'] = __('messages.invalid.address');
            }
            if ($request->ajax()) {
                return response()->json($ret);
            }
            return back()->with([$ret['msg'] => $ret['message']]);
        }
    }

    /**
     * Upload the user kyc documents
     *
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function upload(Request $request)
    {
        if (! is_dir(storage_path('app/kyc-files'))) {
            mkdir(storage_path('app/kyc-files'));
        }

        if (isset($request->action)) {
            if ($request->input('action') == 'delete' && !empty($request->input('file'))) {
                if (is_file(storage_path('app/' . $request->input('file')))) {
                    unlink(storage_path('app/' . $request->input('file')));
                    return response()->json(['status' => 'File Removed']);
                }
            }
        }
        
        //passport upload
        if (isset($_FILES['kyc_file_upload'])) {
            $cleanData = Validator::make($request->all(), ['kyc_file_upload' => 'required|mimetypes:image/jpeg,image/png,application/pdf']);
            if ($cleanData->fails()) {
                $ret['msg'] = 'warning';
                $ret['message'] = __('messages.upload.invalid');
            } else {
                try {
                    $file = $request->file('kyc_file_upload');
                    $name = (auth()->check() ? set_id(auth()->id()) : str_random(10)).'-'.$request->input('docType').'-'.time().'.'.$file->getClientOriginalExtension();
                    $save_file = $file->storeAs('kyc-files', $name);
                    $ret['msg'] = 'success';
                    $ret['message'] = __('messages.upload.success', ['what' => "Document"]);
                    $ret['file_name'] = $save_file;
                } catch (\Exception $e) {
                    $ret['errors'] = $e->getMessage();
                    $ret['msg'] = 'error';
                    $ret['message'] = __('messages.upload.failed', ['what' => "Document"]);
                }
            }
            return response()->json($ret);
        }

        if (!$request->ajax()) {
            return back();
        }
    }
}
