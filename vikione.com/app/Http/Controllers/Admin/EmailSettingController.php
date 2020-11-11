<?php

namespace App\Http\Controllers\Admin;
/**
 * Email Settings Controller
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0.0
 */
use Validator;
use App\Models\User;
use App\Models\Setting;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmailSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // var_dump(config('mail'));
        $templates = EmailTemplate::orderBy('slug', 'ASC')->get();
        $admins = User::where('role', 'admin')->get();
        return view('admin.settings-email', compact('templates', 'admins'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     */
    public function update(Request $request)
    {
        $ret['msg'] = 'info';
        $ret['message'] = __('messages.nothing');

        $validator = Validator::make($request->all(), [
            'site_mail_driver' => 'required',
        ]);

        if ($validator->fails()) {
            $msg = '';
            if ($validator->errors()->has('site_mail_driver')) {
                $msg = $validator->errors()->first();
            } else {
                $msg = __('messages.nothing');
            }

            $ret['msg'] = 'warning';
            $ret['message'] = $msg;
        } else {
            $ret['msg'] = 'warning';
            $ret['message'] = __('messages.update.failed', ['what' => 'Email Settings']);

            foreach ($this->default_data() as $value) {
                Setting::updateValue($value, $request->input($value, null));
            }

            $ret['msg'] = 'success';
            $ret['message'] = __('messages.update.success', ['what' => 'Email Settings']);
        }


        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }

    /**
     * Show the template
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     * @throws \Throwable
     */
    public function show_template(Request $request)
    {
        #email_template
        if ($request->input('get_template') == null) {
            return response()->json(['msg'=>'warning', 'message'=>__('messages.wrong')]);
        } else {
            $template = EmailTemplate::get_template($request->input('get_template'));

            if ($template) {
                return view('modals.email_template', compact('template'))->render();
            } else {
                return response()->json(['msg'=>'warning', 'message'=>__('messages.form.wrong')]);
            }
        }
    }

    /**
     * Update the template
     *
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function update_template(Request $request)
    {
        $ret['msg'] = 'info';
        $ret['message'] = __('messages.nothing');

        $validator = Validator::make($request->all(), [
            'slug' => 'required',
            'subject' => 'required|min:5|max:191',
        ]);

        if ($validator->fails()) {
            $msg = '';
            if ($validator->errors()->hasAny(['slug', 'subject'])) {
                $msg = $validator->errors()->first();
            } else {
                $msg = __('messages.form.wrong');
            }

            $ret['msg'] = 'warning';
            $ret['message'] = $msg;
        } else {
            $template = EmailTemplate::where('slug', $request->input('slug'))->orWhere('id', $request->input('id'))->first();
            $template->subject = $request->input('subject');
            $template->greeting = $request->input('greeting');
            $template->message = $request->input('message');
            $template->regards = isset($request->regards) ? 'true' : 'false';
            $template->notify = isset($request->notify) ? 1 : 0;

            if ($template->save()) {
                $ret['msg'] = 'success';
                $ret['message'] = __('messages.update.success', ['what' => 'Email Template']);
            } else {
                $ret['msg'] = 'warning';
                $ret['message'] = __('messages.update.failed', ['what' => 'Email Template']);
            }
        }


        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }

    /**
     * Set the default data
     *
     * @version 1.0.0
     * @since 1.0
     * @return array
     */
    private function default_data()
    {
        $data = [
            'site_mail_driver',
            'site_mail_host' ,
            'site_mail_port' ,
            'site_mail_from_address',
            'site_mail_from_name' ,
            'site_mail_encryption',
            'site_mail_username' ,
            'site_mail_password' ,
            'site_mail_footer' ,
            'send_notification_to' ,
            'send_notification_mails' ,
        ];

        return $data;
    }
}
