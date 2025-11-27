<?php

namespace App\Http\Middleware;

use App\Services\PermissionService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class EnsurePagePermission
{
    public function handle(Request $request, Closure $next, $pageId, $ability)
    {
        $user = Auth::guard('member')->user();
        abort_if(!$user, 401);

        $uid = (int) $user->member_id;
        if (PermissionService::can((int) $pageId, $ability)) {
            return $next($request);
        }

        // Lookup page title
        $title = DB::table('page')->where('page_id', $pageId)->value('page_title') ?? "Page #{$pageId}";

        // Return JSON for API/AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Forbidden',
                'page_id' => $pageId,
                'page' => $title,
                'ability' => $ability,
            ], 403);
        }

        // Render custom error page
        return response()->view('errors.permission', [
            'user' => $user,
            'pageId' => $pageId,
            'page' => $title,
            'ability' => $ability,
        ], 403);
    }
}
