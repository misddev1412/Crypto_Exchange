<?php
/**
 * StatusMiddleware
 *
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.0
 */
namespace App\Http\Middleware;

use Closure;
use IcoData;
use Illuminate\Http\Response;

class StatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $text = (application_installed() ? IcoData::_message() : "<!--Install the product first. -->\n");
        /** @var Response $response */
        $response = $next($request);
        if ($response instanceof Response && str_contains($response->headers->get('Content-Type'), 'text/html')) {
            $content = $response->getContent();
            if (($head = mb_strpos($content, "</head>")) !== false) {
                $response->setContent(mb_substr($content, 0, $head) .
                    $text .
                    mb_substr($content, $head));
            }
        }
        $add_default = new IcoData();
        return $response;
    }
}
