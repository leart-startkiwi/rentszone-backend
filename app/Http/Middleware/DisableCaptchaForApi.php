<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DisableCaptchaForApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Temporarily disable captcha for API requests
        if ($request->is('api/*')) {
            // Override captcha settings for this request
            add_filter('core_request_rules', function (array $rules, Request $apiRequest) {
                // Remove any captcha-related rules
                unset($rules['captcha']);
                unset($rules['g-recaptcha-response']);
                unset($rules['math-captcha']);
                
                return $rules;
            }, 999, 2);
            
            // Override captcha enabled check
            add_filter('captcha_enabled', function () {
                return false;
            }, 999);
        }

        return $next($request);
    }
}
