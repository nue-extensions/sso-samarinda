<?php

namespace Nue\SSOSamarinda\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Nue\SSOSamarinda\SSOSamarinda;

class OAuthController extends Controller
{
	/**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

        $this->redirectTo = SSOSamarinda::config('redirect_to');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        if(is_null(SSOSamarinda::config('server_url'))):
            return response()->json([
                '401' => 'Unauthorized', 
                'message' => 'Beberapa parameter yang dibutuhkan untuk broker. Bisa ditemukan di https://sso.samarindakota.go.id'
            ]);
        endif;

        $queries = http_build_query([
            'name' => env('SSO_BROKER_NAME'), 
            'secret' => env('SSO_BROKER_SECRET'), 
            'redirect_uri' => urlencode(request()->url()), 
            'response_type' => 'code', 
        ]);

        return redirect(env('SSO_SERVER_URL') . '/oauth/sso/authorize?' . $queries);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function callback(Request $request)
    {
    	$broker = new \Nue\SSOSamarinda\Service\Broker;

    	if($request->filled('code') && $request->filled('uid') && $request->filled('pwd')) {
            if($broker->login(base64_decode($request->uid), base64_decode($request->pwd))) {
                $request->session()->regenerate();

                return redirect()->to($this->redirectTo);
            }
        }

        if($request->filled('code') && !$request->filled('uid') && !$request->filled('pwd')) {
            if($broker->token($request->code)) {
                $request->session()->regenerate();

                return redirect()->to($this->redirectTo);
            }
        }

        return response()->json([
            'status' => 'error', 
            'message' => 'Tidak dapat terhubung dengan SSO.'
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $broker = new \Nue\SSOSamarinda\Service\Broker;
        $broker->logout();
        
        $this->guard()->logout();
        $request->session()->invalidate();
        
        return redirect('/');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return \Auth::guard(config('sso.guard'));
    }
}
