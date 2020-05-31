<?php

namespace App\Http\Controllers\PassportSample;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class PassportSampleController extends Controller
{
    public function index()
    {
        return view('passport.home', [
            'isCallback' => 'false'
        ]);
    }

    public function begin(Request $request) {
        $grant_type = $request->grant_type;

        if ($grant_type==='authorization_code' || $grant_type==='implicit') {

            $query_param = [
                'client_id' => $request->client_id,
                'redirect_url' => $request->redirect_url,
                'response_type' => $request->response_type,
                'scope' => $request->scope,
            ];

            if ($grant_type==='authorization_code') {
                $request->session()->put('state', $state = Str::random(40));
                $request->session()->put('clientId', $request->client_id);
                $query_param['state'] = $state;
            }

            $query_str = http_build_query($query_param);

            return redirect("http://localhost:8080/oauth/authorize?".$query_str);
        } else {
            $args = [
                'isCallback' => 'true',
                'grantType' => $grant_type,
                'clientId' => $request->client_id,
                'scope' => $request->scope
            ];

            if ($grant_type==='password') {
                $args['username'] = $request->username;
                $args['password'] = $request->password;
                $args['clientSecret'] = $request->client_secret;
            } else if ($grant_type==='client_credentials') {
                $args['clientSecret'] = $request->client_secret;
            }
            return view('passport.home', $args);
        }
    }

    public function callback(Request $request)
    {
        $state = $request->session()->pull('state');

        throw_unless(
            strlen($state) > 0 && $state === $request->state,
            InvalidArgumentException::class
        );

        $client_id = $request->session()->pull('clientId');

        return view('passport.home', [
            'isCallback' => 'true',
            'grantType' => 'authorization_code',
            'code' => $request->code,
            'clientId' => $client_id
        ]);
    }
}
