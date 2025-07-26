<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AppleToken;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
    }

    public function callback($provider)
    {
        if ($provider == 'apple') {
            $appleToken = app(AppleToken::class);

            config()->set('services.apple.client_secret', $appleToken->generate());
        }

        return json_encode(Socialite::driver($provider)->stateless()->user());
    }

    public function profile($provider, Request $request)
    {
        $request->validate([
            'access_token' => 'required|string',
        ]);

        try {
            $token = $request->get('access_token');

            $providerUser = Socialite::driver($provider)->userFromToken($token);

            return json_encode($providerUser);
        } catch (\Exception $e) {
            return response([
                'error' => $e->getMessage(),
            ], 403);
        }
    }

    public function token(Request $request, AppleToken $appleToken)
    {
        dd($appleToken->generate());

        // $request->validate([
        //     'code' => 'required|string',
        // ]);

        // dd($providerUser = Socialite::driver('apple')->user());

        // try {
        //     $token = $request->get('access_token');

        //     $providerUser = Socialite::driver($provider)->user($token);

        //     return json_encode($providerUser);
        // } catch (\Exception $e) {
        //     return response([
        //         'error' => $e->getMessage(),
        //     ], 403);
        // }
    }
}
