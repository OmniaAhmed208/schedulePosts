<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $domains = ["http://192.168.1.15:8000"];
        // if(isset($request->server()['HTTP_ORIGIN'])){
        //     $origin = $request->server()['HTTP_ORIGIN'];

        //     if(in_array($origin, $domains)){
        //         header('Access-Control_Allow_Origin: '. $origin);
        //         header('Access-Control_Allow_Headers: Origin, Content-Type, Authorization');
        //     }
        // }
        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, application/json');

        return $response;
    }
}
