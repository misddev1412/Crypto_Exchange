<?php

namespace App\PayModule;

/**
 * Payments ModuleHelper
 * @version v1.0.0
 * @since v1.0.2
 */
use View;
use Illuminate\Support\HtmlString;

class ModuleHelper
{

    public static function view($path, $data=[], $html = true)
    {
        View::addNamespace('pay_module', app_path('PayModule/'));
        if ($html) {
            return new HtmlString(View::make("pay_module::$path", $data)->render());
        } else {
            return View::make("pay_module::$path", $data)->render();
        }
    }

    public static function str2html($html)
    {
        return new HtmlString($html);
    }



    /**
     * satisfy_version compare with application. 
     *
     * @version 1.0
     * @since 1.1.0
     */
    public static function satisfy_version($requires)
    {
        $laravel = app()->version();
        $app = app_version();
        if( empty($requires) && $requires <= 0 ) return false;
        foreach ($requires as $name => $version) {
            $fversion = self::filter_version($version);
            $operator = self::filter_version($version, true);
            if($name == 'laravel'){
                if(version_compare($laravel, $fversion, $operator) === false){
                    return false;
                }
            }
            if($name == 'app'){
                if(version_compare($app, $fversion, $operator) === false){
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * filter_version controlling
     *
     * @version 1.0
     * @since 1.1.0
     */
    public static function filter_version($version, $operator = false)
    {
        $filter = str_replace(
            ['^', '>=', '<=', '==', '>', '<', '~', '<>'], 
            ['', '', '', '', '', '', '', ''], 
            $version);
        if( starts_with($version, '^') || starts_with($version, '>=') ){
            if($operator) return '>=';
        }
        if( starts_with($version, '<=') ){
            if($operator) return '<=';
        }
        if( starts_with($version, '>') ){
            if($operator) return '>';
        }
        if( starts_with($version, '<') ){
            if($operator) return '<';
        }
        if( starts_with($version, '~') ){
            if($operator) return '<>';
        }

        if($operator) return '<>';
        return $filter;
    }

}
