@extends('auth.master')

@section('content')
    <div id="login">
        <h1>Login</h1>
        <div class="flex">
            @include('auth.errors')
            @include('auth.login-form')
        </div>
    </div>

@endsection
