<?php

namespace App\Http\Middleware;

use App\Modules\Proxycheck\ProxycheckService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class VpnBlockMiddleware
{
    /**
     * @param ProxycheckService $proxycheckService
     */
    public function __construct(private readonly ProxycheckService $proxycheckService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ips = $request->ips();

        if ($this->proxycheckService->isVpn(end($ips))) {
            throw new AccessDeniedHttpException('You are using VPN');
        }

        return $next($request);
    }
}
