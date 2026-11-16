<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProtectDeveloperCredit
{
    public function handle(Request $request, Closure $next): Response
    {
        // KILL-SWITCH DIMATIKAN OLEH SISTEM
        return $next($request);
    }
}
