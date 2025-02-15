<?php
namespace App\Http\Middleware;

use App\Models\Token;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = explode(' ', $request->header('authorization'))[1];

        if (! Token::query()->where(['token' => $token, 'is_blacklist' => 0])->isNotExpire()->exists()) {
            return messageResponse('Sorry, the token is invalid, blacklisted, or expires.', 401);
        }

        return $next($request);
    }
}
