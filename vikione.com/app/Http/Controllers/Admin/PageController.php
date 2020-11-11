<?php

namespace App\Http\Controllers\Admin;
/**
 * Page Controller
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0.3
 */
use Validator;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function index()
    {
        $pages = Page::where('status', '!=', 'inactive')->orderBy('id', 'ASC')->get();

        return view('admin.pages', compact('pages'));
    }

    /**
     * Display the specified resource for edit.
     *
     * @param $slug
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     */
    public function edit($slug)
    {
        $page_data = Page::where('slug', $slug)->first();
        return view('admin.edit-page', compact('page_data'));
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
        $page = $request->input('page_id');
        $ret['msg'] = 'info';
        $ret['message'] = __('messages.nothing');

        $validator = Validator::make($request->all(), [
            'page_id' => 'required',
            'custom_slug' => 'required|unique:pages,custom_slug,' . $page,
            'title' => 'required',
            'menu_title' => 'required',
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has(['title', 'menu_title', 'custom_slug'])) {
                $msg = $validator->errors()->first();
            } else {
                $msg = __('messages.form.wrong');
            }
            $ret['msg'] = 'warning';
            $ret['message'] = $msg;
        } else {
            $data = Page::where('id', $page)->first();
            $data->title = $request->input('title');
            $data->menu_title = $request->input('menu_title');
            $data->custom_slug = preg_replace('/\s+/', '-', $request->input('custom_slug'));
            $data->description = str_replace(['<script>', '</script>'], ['&lt;script&gt;', '&lt;/script&gt;'], trim($request->input('description')));
            $data->status = $request->input('status');
            $data->save();

            if ($data) {
                $ret['msg'] = 'success';
                $ret['message'] = __('messages.update.success', ['what' => 'Page']);
            } else {
                $ret['msg'] = 'error';
                $ret['message'] = __('messages.update.failed', ['what' => 'Page']);
            }
        }

        if ($request->ajax()) {
            return response()->json($ret);
        }
        return back()->with([$ret['msg'] => $ret['message']]);
    }

    /**
     * Upload the files
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     * @version 1.0.0
     * @since 1.0
     * @return void
     */
    public function upload_zone(Request $request)
    {
        //passport upload
        if ($request->hasFile('whitepaper')) {
            $cleanData = Validator::make($request->all(), ['whitepaper' => 'required|mimetypes:application/pdf']);
            $old = storage_path('app/public/' . get_setting('site_white_paper'));
            if ($cleanData->fails()) {
                $ret['msg'] = 'warning';
                $ret['message'] = __('messages.upload.invalid');
            } else {
                $file = $request->file('whitepaper');
                $name = 'white-paper' . time() . '.' . $file->extension();
                $file->move(storage_path('app/public/'), $name);
                Setting::updateValue('site_white_paper', $name);

                $ret['msg'] = 'success';
                $ret['message'] = __('messages.upload.success', ['what' => "White Paper"]);
                $ret['file_name'] = $name;
                if (! is_dir($old) && ! starts_with($old, 'assets')) {
                    unlink($old);
                }
            }
            return response()->json($ret);
        }
    }
}
