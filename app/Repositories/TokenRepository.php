<?php

namespace App\Repositories;


use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\JWT;
use App\Token;
use Illuminate\Support\Str;
use App\Contracts\TokenRepository as Contract;

class TokenRepository implements Contract
{
    /**
     * {@inheritdoc}
     */
    public function all($user)
    {
        return $user->tokens()
                    ->where('transient', false)
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

    /**
     * {@inheritdoc}
     */
    public function validToken($token)
    {
        return Token::where('token', $token)->where(function ($query) {
            return $query->whereNull('expires_at')
                         ->orWhere('expires_at', '>=', Carbon::now());
        })->first();
    }

    /**
     * {@inheritdoc}
     */
    public function createToken($user, $name, array $data = [])
    {
        $this->deleteExpiredTokens($user);

        return $user->tokens()->create([
            'id' => Uuid::uuid4(),
            'user_id' => $user->id,
            'name' => $name,
            'token' => Str::random(60),
            'metadata' => $data,
            'transient' => false,
            'expires_at' => null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function createTokenCookie($user)
    {
        $token = JWT::encode([
            'sub' => $user->id,
            'xsrf' => csrf_token(),
            'expiry' => Carbon::now()->addMinutes(5)->getTimestamp(),
        ]);

        dd('test in rep',$token,$user);
        return cookie(
            'spark_token', $token, 5, null,
            config('session.domain'), config('session.secure'), true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function updateToken(Token $token, $name, array $abilities = [])
    {
        $metadata = $token->metadata;

        $metadata['abilities'] = $abilities;

        $token->forceFill([
            'name' => $name,
            'metadata' => $metadata,
        ])->save();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteExpiredTokens($user)
    {
        $user->tokens()->where('expires_at', '<=', Carbon::now())->delete();
    }
}
