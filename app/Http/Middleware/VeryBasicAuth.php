<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VeryBasicAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader('Authorization') === false) {
            // Display login prompt
            header('WWW-Authenticate: Basic realm="KamarDS"');
            exit;
        }

        $credentials = base64_decode(substr($request->header('Authorization'), 6));
        list($username, $password) = explode(':', $credentials);

        if ($username !== config("auth.veryBasicAuth.username") || $password !== config("auth.veryBasicAuth.password")) {
            // Provided username or password does not match, throw an exception
            // Alternatively, the login prompt can be displayed once more
            throw new HttpException(Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
