<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()){
            $today = date('Y-m-d');
            $sub_end_at = Auth::user()->clinic->subscription_end_at;
            $demo_end_at = Auth::user()->clinic->demo_end_at;
            $type = Auth::user()->clinic->type;
            if($type == 'Demo' && $today > $demo_end_at && $demo_end_at != NULL){
                return response()->json(['status' => 'error', 'message' => 'Sorry Your '.$type.' Subscription has been Expired'], 410);
            }
            else if($today > $sub_end_at && $sub_end_at != NULL){
                return response()->json(['status' => 'error', 'message' => 'Sorry Your '.$type.' Subscription has been Expired'], 410);
            }

        }
        return $next($request);
    }
}
