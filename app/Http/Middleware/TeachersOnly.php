<?php

namespace App\Http\Middleware;

use App\Models\Teacher;
use App\Models\User;
use Closure;

class TeachersOnly
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
        /** @var User $user */
        $user = $request->user();
        if ($user && $user->hasOne(Teacher::class))
            return $next($request);
        return response()->json([
            'message' => 'Access denied'
        ])->setStatusCode(401);
    }
}
