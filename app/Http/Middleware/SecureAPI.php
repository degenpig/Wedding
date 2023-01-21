<?php

namespace App\Http\Middleware;

use Closure;

class secureAPI
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
        $api_crednetials = $request->header('apicredentials');
        if ($api_crednetials == '3034wedding6578882c0eaca78ef43ad28f9d7fd4aab91fe120aa57bf637') {
            return $next($request);
        }  
        return response()->json(['status' => false, 'message' => 'API endpoint call unauthenticated.'], 401);

    }
}
?>
