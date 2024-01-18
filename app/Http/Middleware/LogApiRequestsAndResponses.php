<?php
namespace App\Http\Middleware;

use App\Models\ApiLog;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogApiRequestsAndResponses
{
    public function handle($request, Closure $next)
    {
        // Process the request
        $response = $next($request);
        $userId = null;
        if (Auth::check()) {
            $userId = Auth::id();
        }

        ApiLog::create([
            'method' => $request->method(),
            'url' => $request->fullUrl(),
//            'request_data' => json_encode($request->all()),
//            'response_status' => $response->status(),
//            'response_content' => json_encode($response->content()),
            'user_id' => $userId,
        ]);

        return $response;
    }
}
