<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

final class SetTenantContext
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->organization_id) {
            // Set tenant context in config for easy access
            config(['tenant.current_organization_id' => $user->organization_id]);

            // Add organization context to logs
            Log::withContext([
                'organization_id' => $user->organization_id,
                'user_id' => $user->id,
            ]);
        }

        return $next($request);
    }
}
