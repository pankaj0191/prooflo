<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use App\Contracts\TokenRepository;

class CreateFreshApiToken
{
    /**
     * The token repository implementation.
     *
     * @var \Laravel\Spark\Contracts\Repositories\TokenRepository
     */
    protected $tokens;

    /**
     * Create a new middleware instance.
     *
     * @param  \Laravel\Spark\Contracts\Repositories\TokenRepository  $tokens
     * @return void
     */
    public function __construct(TokenRepository $tokens)
    {

        $this->tokens = $tokens;

    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $response = $next($request);

        if ($this->shouldReceiveFreshToken($request, $response)) {

            $response->withCookie($this->tokens->createTokenCookie($request->user()));
        }
        if(auth()->check()){
//            dd('test out',$request->user(),auth()->user());
        }

        return $response;
    }

    /**
     * Determine if the given request should receive a fresh token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return bool
     */
    protected function shouldReceiveFreshToken($request, $response)
    {
        return true && $this->requestShouldReceiveFreshToken($request) &&
               $this->responseShouldReceiveFreshToken($response);
    }

    /**
     * Determine if the request should receive a fresh token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function requestShouldReceiveFreshToken($request)
    {
        return $request->isMethod('GET') && $request->user() && ! $request->ajax();
    }

    /**
     * Determine if the response should receive a fresh token.
     *
     * @param  \Illuminate\Http\Response  $response
     * @return bool
     */
    protected function responseShouldReceiveFreshToken($response)
    {

        return $response instanceof Response &&
                    !  $this->alreadyContainsToken($response);
    }

    /**
     * Determine if the given response already contains a Spark API token.
     *
     * This avoids us overwriting a just "refreshed" token.
     *
     * @param  \Illuminate\Http\Response  $response
     * @return bool
     */
    protected function alreadyContainsToken($response)
    {

        foreach ($response->headers->getCookies() as $cookie) {
            if ($cookie->getName() === 'spark_token') {
                return true;
            }
        }

        return false;
    }
}
