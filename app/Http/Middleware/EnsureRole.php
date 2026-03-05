<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            if ($request->user()) {
                return $this->redirectByRole($request->user()->role);
            }

            return redirect()->route('login');
        }

        return $next($request);
    }

    protected function redirectByRole(string $role): Response
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'agent' => redirect()->route('agent.dashboard'),
            default => redirect()->route('login'),
        };
    }
}
