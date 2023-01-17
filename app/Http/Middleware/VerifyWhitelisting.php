<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\Traits\ResponseTrait;

class VerifyWhitelisting
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     *
     * @return mixed
     */

    public function handle(Request $request, \Closure $next, $guard = null)
    {
        if ($this->validate($request->getHost()) === false) {
            return $this->response()->errorForbidden();
        }

        return $next($request);
    }

    private function validate(string $host)
    {
        if (config('app.env') != "production") {
            return true;
        }
        return in_array($host, config('white_listing.host'));
    }
}
