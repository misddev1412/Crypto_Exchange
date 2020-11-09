<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function getRedirectUri()
    {
        $returnUrl = url()->previous();
        parse_str(parse_url($returnUrl, PHP_URL_QUERY), $requestQuery);

        if (isset($requestQuery['return-url']) && filter_var($requestQuery['return-url'], FILTER_VALIDATE_URL)) {
            $newRequest = Request::create($requestQuery['return-url']);
            try {
                $routes = Route::getRoutes();
                $routes->match($newRequest);
                $returnUrl = $requestQuery['return-url'];
            } catch (NotFoundHttpException $exception) {

            }
        }

        return $returnUrl;
    }
}
