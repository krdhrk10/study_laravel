@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
			@if($isCallback==='false')
				<passport-auth-sample></passport-auth-sample>
			@else
				@if($grantType==='authorization_code')
					<passport-auth-sample-callback grant-type="{{ $grantType }}"
												   :client-id="{{ $clientId }}" code="{{ $code }}">
					</passport-auth-sample-callback>
				@elseif($grantType==='implicit')
					<passport-auth-sample-callback grant-type="{{ $grantType }}"
												   :client-id="{{ $clientId }}"
												   access-token="{{ $accessToken }}"
												   token-type="{{ $tokenType }}"
												   expires-in="{{ $expiresIn }}">
					</passport-auth-sample-callback>
				@elseif($grantType==='password')
					<passport-auth-sample-callback grant-type="{{ $grantType }}"
												   :client-id="{{ $clientId }}"
												   client-secret="{{ $clientSecret }}"
												   username="{{ $username }}"
												   password="{{ $password }}"
												   scope="{{ $scope }}">
					</passport-auth-sample-callback>
				@elseif($grantType==='client_credentials')
					<passport-auth-sample-callback grant-type="{{ $grantType }}"
												   :client-id="{{ $clientId }}"
												   client-secret="{{ $clientSecret }}"
												   scope="{{ $scope }}">
					</passport-auth-sample-callback>
				@endif
			@endif
        </div>
    </div>
</div>
@endsection
