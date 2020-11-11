<?php

namespace App\Http\Controllers\Admin;

use File;
use Validator;
use App\Models\Setting;
use App\Models\Language;
use App\Models\Translate;
use App\Helpers\BaseTranslate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @version 1.0.0
     * @since   1.1.3
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $languages = Language::all();
        return view("admin.languages", compact('languages'));
    }

    /**
     * Display a listing of the resource.
     *
     * @version 1.0.0
     * @since   1.1.3
     * @return \Illuminate\Http\Response
     */
    public function translator(Request $request, $code)
    {
        $result['msg'] = 'warning';
        $result['message'] = __('Sorry! unable to find translate data for the language.');
        $lang = Language::where('code', $code)->first();
        if( $request->filter ){
            return $this->translator_view($lang, $request);
        }
        $tags = BaseTranslate::filterableNames();
        if($lang) {
            $base_lang = Translate::where('name', 'base')->get();;
            foreach ($base_lang as $base) {
                $set_data = $this->set_by_key($base, $base->key, $lang->code);
                $created  = Translate::firstOrCreate(['key' => $base->key, 'name' => $lang->code], $set_data);
            }
            return view('admin.language-translate-intro', compact('lang', 'tags'));
        }
        return back()->with([ $result['msg'] => $result['message'] ]);
    }
    public function translator_view($lang, Request $request)
    {
        $base_lang = Translate::where('name', 'base')
                        ->where(function($q)use($request){
                            $terms = explode(',', $request->category);
                            foreach ($terms as $value) {
                                $term = trim($value);
                                $q->where('pages', 'LIKE', "%{$term}%");
                            }
                        })->get();
        $lang_translates = $lang->translate()->whereIn('key', $base_lang->pluck('key')->toArray())->get();
        $tags = BaseTranslate::filterableNames();
        $translates = [];
        foreach ($base_lang as $base) {
            $ingore_key = ['messages.demo_payment_note', 'messages.demo_user', 'messages.demo_preview'];
            $get_lang = $lang_translates->where('key', $base->key)->first();
            $set_lang = [];
            if($get_lang) {
                if(!in_array($get_lang->key, $ingore_key)) {
                    $set_lang['id'] = $get_lang->id;
                    $set_lang['key'] = $get_lang->key;
                    $set_lang['text'] = $get_lang->text;
                    $set_lang['base'] = $base->text;
                    array_push($translates, $set_lang);
                }
            }
        }
         return view('admin.language-translate', compact(['translates', 'lang', 'tags']));
    }

    /**
     * Controling All action
     *
     * @version 1.0.0
     * @since   1.1.3
     * @return \Illuminate\Http\Response
     */
    public function language_action(Request $request)
    {
        $result['msg'] = 'info';
        $result['icon'] = 'ti ti-info-alt';
        $result['message'] = __('messages.nothing');
        $actions = $request->input('actions');

        if ($actions=='settings') {
            $result = $this->update_settings($request);
        } elseif (in_array($actions, ['update', 'disable', 'enable'])) {
            $result = $this->update_language($request);
        } elseif ($actions=='translation') {
            $result = $this->update_translate($request);
        } elseif ($actions=='generate') {
            $result = $this->generate_translate($request);
        }  elseif ($actions=='language') {
            $result = $this->store($request);
        } elseif ($actions=='delete') {
            $result = $this->destroy($request);
        }

        if ($request->ajax()) { return response()->json($result); }
        return back()->with([$result['msg'] => $result['message']]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @version 1.0.0
     * @since   1.1.3
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($request) {
        $result['error'] = true;
        $result['msg'] = 'warning';
        $result['message'] = __('messages.wrong');
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'unique:languages'],
            'code' => ['required', 'alpha', 'min:2', 'max:6', 'unique:languages'],
            'label' => ['required', 'string'],
            'short' => ['required', 'string'],
        ], [
            'name.unique' => __('The language name should be unique and indentical.'),
            'code.unique' => __('The language code name should be unique.')
        ]);

        if ($validator->fails()) {
            $result['msg'] = 'warning';
            $result['message'] = $validator->errors()->first();
        } else {
            $new_lang = [
                'name' => $request->name,
                'label' => $request->label,
                'short' => strtoupper($request->short),
                'code' => strtolower($request->code)
            ];
            $lang = Language::create($new_lang);
            if($lang) {
                $result['error'] = false;
                $result['msg'] = 'success';
                $result['message'] = __(':name language added successfully.', ['name' => $lang->name]);
                $this->import_new_language($lang->code);
                // $this->generate($lang->code, 'store');
            }
        }
        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @version 1.0.0
     * @since   1.1.3
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_settings($request) {
        $result['error'] = true;
        $result['msg'] = 'warning';
        $result['icon'] = 'ti ti-alert';
        $result['message'] = __('messages.wrong');
        $actions = $request->input('actions');

        if($actions=='settings') {
            Setting::updateValue('languages_show_as', $request->input('languages_show_as'));
            Setting::updateValue('languages_switcher', (isset($request->languages_switcher) ? 1 : 0));
            $result['error'] = false;
            $result['msg'] = 'success';
            $result['icon'] = 'ti ti-info-alt';
            $result['message'] = __('messages.update.success', ['what' => 'Language Settings']);
        }
        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @version 1.0.0
     * @since   1.1.3
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_language($request) {
        $result['error'] = true;
        $result['msg'] = 'warning';
        $result['icon'] = 'ti ti-alert';
        $result['message'] = __('messages.wrong');
        $actions = $request->input('actions');
        $modal = ($request->input('modal')) ?? false;

        $lang_code = $request->input('lang');
        $lang = Language::where('code', $lang_code)->first();
        if($lang) {
            if($actions=='disable'||$actions=='enable') {
                $lang->status = ($actions=='disable') ? false : true;
                $lang->save();
                $result['error'] = false;
                $result['reload'] = true;
                $result['msg'] = 'success';
                $result['icon'] = 'ti ti-check';
                $result['message'] = __('messages.update.success', ['what' => 'Language']);
            } elseif($actions=='update') {
                if($modal=='edit') {
                    $result = ['modal' => view('modals.language', compact('lang'))->render()];
                } else {
                    if ($lang->code!='en'){
                        $lang->status = (isset($request->status) ? true : false);
                    }
                    $lang->label = $request->label;
                    $lang->short = $request->short;
                    $lang->save();
                    $result['error'] = false;
                    $result['msg'] = 'success';
                    $result['icon'] = 'ti ti-check';
                    $result['message'] = __('messages.update.success', ['what' => 'Language']);
                }
            }
        } else {
            $result['message'] = __('Sorry! unable to find the language.');
        }
        return $result;
    }

    /**
     * Store a newly created resource in storage.
     * @version 1.0.0
     * @since   1.1.3
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_translate($request) {
        $result['error'] = true;
        $result['msg'] = 'warning';
        $result['icon'] = 'ti ti-alert';
        $result['message'] = __('messages.wrong');
        $actions = $request->input('actions');

        $lang = strtolower($request->input('lang'));
        if($actions=='translation') {
            if($this->is_lang_exist($lang)) {
                $translates = $request->$lang;
                if(is_array($translates)) {
                    foreach ($translates as $id => $text) {
                        $by_lang = $this->get_by_id($id, $lang);
                        $by_base = $this->get_by_key($by_lang->key, 'base');
                        if(!empty($text)) {
                            $by_lang->text = $text;
                        }
                        if($by_base) {
                            $by_lang->pages = $by_base->pages;
                            $by_lang->group = $by_base->group;
                            $by_lang->panel = $by_base->panel;
                            $by_lang->load = $by_base->load;
                        }
                        $by_lang->save();
                    }
                    add_setting('lang_last_update_'.$lang, time());
                    $result['error'] = false;
                    $result['msg'] = 'success';
                    $result['icon'] = 'ti ti-check';
                    $result['message'] = __('messages.update.success', ['what' => 'Translation']);
                }
            } else {
                $result['message'] = __('Sorry! unable find the language.');
            }
        } else {
            $result['message'] = __('Sorry! unable to update translated text.');
        }
        return $result;
    }

    /**
     * Check key already exist or not in table
     *
     * @version 1.0.0
     * @since   1.1.3
     * @param   string $lang->code
     * @param   string $action
     */
    public function generate_translate($request) {
        $result['error'] = true;
        $result['msg'] = 'warning';
        $result['icon'] = 'ti ti-alert';
        $result['message'] = __('messages.wrong');

        $actions = $request->input('actions');
        if($actions=='generate') {
            $lang = strtolower($request->input('lang'));
            if($this->is_lang_exist($lang)) {
                $translate = $this->get_translation('base', false);
                $output = [];
                foreach ($translate as $base) {
                    $key = $base->key;
                    $get = self::get_by_key($key, $lang);
                    $gen_key = ($base->load==1) ? $key : $base->text;
                    $get_txt = (!empty($get)) ? $get->text : $base->text;
                    $output[$gen_key] = $get_txt;
                }
                $filewrite = false;
                try {
                    File::put(resource_path('lang/test.json'), 'Nio Testing');
                    File::delete(resource_path('lang/test.json'));
                    $filewrite = true;
                } catch (\Exception $e) { }

                if($filewrite){
                    $content = json_encode($output, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
                    $generate = $this->generate_lang_file($lang, $content, 'update');
                    if($generate && $generate->status==true) {
                        add_setting('lang_last_generate_'.$lang, time());
                        $result['error'] = false;
                        $result['msg'] = 'success';
                        $result['icon'] = 'ti ti-check';
                        $result['message'] = __("Successfully generated the language file in '/lang' folder.");
                    } else {
                        $result['message'] = __("Failed to generate the language file.");
                    }
                }else{
                    $result['message'] = __("Unable to generate language file. Please check file permission of your '/lang' folder.");
                }
            } else {
                $result['message'] = __('Sorry! unable find the language.');
            }
        } else {
            $result['message'] = __('Sorry! unable to update translated text.');
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @version 1.0.0
     * @since   1.1.3
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function destroy($request) {
        $result['error'] = true;
        $result['msg'] = 'warning';
        $result['icon'] = 'ti ti-alert';
        $result['message'] = __('messages.wrong');
        $actions = $request->input('actions');

        $lang = strtolower($request->input('lang'));
        if($actions=='delete') {
            if($this->is_lang_exist($lang)) {
                Language::where('code', $lang)->delete();
                Translate::where('name', $lang)->delete();
                $result['error'] = false;
                $result['reload'] = true;
                $result['msg'] = 'success';
                $result['icon'] = 'ti ti-check';
                $result['message'] = __('The language and related translation has been deleted from application.');
            }
        }
        return $result;
    }

    /**
     * Check key already exist or not in table
     *
     * @version 1.0.0
     * @since   1.1.3
     * @param   string $key
     * @param   string $lang->code
     * @return  items::void
     */
    public function get_or_insert($key, $lang) {
        $get = Translate::where(['key' => $key, 'name' => $lang])->first();

        if($get) {
            return $get;
        } else {
            $get_base = $this->get_by_key($key, 'base');
            $set_data = $this->set_by_key($get_base, $key, $lang);
            $created  = Translate::create($set_data);
            return $created;
        }
    }

    /**
     * Get translatable data from table
     *
     * @version 1.0.0
     * @since   1.1.3
     * @param   string $lang->code
     * @param   boolean $only
     * @return  collection::items
     */
    public function get_translation($lang='base', $only=true) {
        $get_only = ($only==true) ? ['key', 'name', 'text', 'load'] : ['key', 'name', 'text', 'pages', 'group', 'panel', 'load'];
        return Translate::where('name', $lang)->get($get_only);
    }

    /**
     * Get translate row by using key for language
     *
     * @version 1.0.0
     * @since   1.1.3
     * @param   string $key
     * @param   string $lang->code
     * @return  items::void
     */
    public function get_by_key($key, $lang='base') {
        return Translate::where(['key' => $key, 'name' => $lang])->first();
    }

    /**
     * Get translate row by using id for language.
     *
     * @version 1.0.0
     * @since   1.1.3
     * @param   string $id
     * @param   string $lang->code
     * @return  items::void
     */
    public function get_by_id($id, $lang='base') {
        return Translate::where(['id' => $id, 'name' => $lang])->first();
    }

    /**
     * Language data set for update or new generate
     *
     * @version 1.0.0
     * @since   1.1.3
     * @param   object $set->$base
     * @param   string $key
     * @param   string $lang->code
     * @return  boolean
     */
    public function set_by_key($base, $key, $lang) {
        if(empty($base) || empty($key) || empty($lang)) return false;
        $data = [
            'key'=>$key,
            'name'=>$lang,
            'text'=>$base->text,
            'pages'=>$base->pages,
            'group'=>$base->group,
            'panel'=>$base->panel,
            'load'=>$base->load
        ];
        return $data;
    }

    /**
     * Import translatable text from base
     *
     * @version 1.0.0
     * @since   1.1.3
     * @param   string $lang->code
     */
    public function import_new_language($lang){
        $translate = $this->get_translation('base', false);

        if($lang != 'base') {
            foreach ($translate as $base) {
                $key    = $base->key;
                $exist  = $this->is_key_exist($key, $lang);
                $saved  = $this->set_by_key($base, $key, $lang);

                if($exist===true){
                    Translate::updateOrCreate(['key' => $key, 'name' => $lang], $saved);
                } else {
                    Translate::create($saved);
                }
            }
        }
    }

    /**
     * Check key already exist or not in table
     *
     * @version 1.0.0
     * @since   1.1.3
     * @param   string $key
     * @param   string $lang->code
     * @return  boolean
     */
    public function generate_lang_file($lang, $content, $action='update'){
        $result = ['status' => false];

        if($action==='store'){
            $lang_file = resource_path('lang/'.$lang.'.json');
            if(File::exists($lang_file)){
                File::delete($lang_file);
            }
            File::put($lang_file, $content);
        }else{
            $file_name = $lang.'.json';
            $lang_file = resource_path('lang/'.$file_name);
            if(File::isWritable(resource_path('lang'))) {
                if(File::exists($lang_file)){
                    File::delete($lang_file);
                }
                File::put($lang_file, $content);
                $result = ['status'=> true];
            }
        }
        return (object) $result;
    }

    /**
     * Check key already exist or not in table
     *
     * @version 1.0.0
     * @since   1.1.3
     * @param   string $key
     * @param   string $lang->code
     * @return  boolean
     */
    public function is_key_exist($key, $lang) {
        $get_key = Translate::where(['key' => $key, 'name' => $lang])->first();
        return ($get_key) ? true : false;
    }

    /**
     * Check lang already exist or not in table
     *
     * @version 1.0.0
     * @since   1.1.3
     * @param   string $lang->code
     * @param   string $column->table
     * @return  boolean
     */
    public function is_lang_exist($lang, $column='code') {
        $get_lang = Language::where($column, $lang)->first();
        return ($get_lang) ? true : false;
    }
}
