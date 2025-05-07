<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !$request->user()->isAdmin()) { // isAdmin() là helper method trong User model
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}