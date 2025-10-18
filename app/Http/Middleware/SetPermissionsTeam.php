<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetPermissionsTeam
{
    /**
     * Handle an incoming request.
     * Sets the Spatie Permission team context based on the authenticated user's organization.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();

            // Set team context to user's organization
            // Platform users (SuperAdmin, Support) have no organization_id, so team_id will be null
            setPermissionsTeamId($user->organization_id);
        }

        return $next($request);
    }
}
