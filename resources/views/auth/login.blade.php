@extends('layouts.app')

@section('content')
<div class="flex-center-100">
    <div class="w-100">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 dark-bg">
                    <h1 class="login-title text-center">Welcome Back!</h1>
                    <p class="subtitle white-text text-center">
                        We value security, so we’re going to have to require you to authenticate first before entering your dashboard. <!--<i class="fa fa-smile-o"></i>-->
                    </p>
                    <div class="row">
                        <div class="col-md-offset-2 col-md-8 col-sm-12">
                            <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <div class="col-md-12">
                                        <input id="email" type="email" placeholder="E-Mail Address" class="" name="email" value="{{ old('email') }}" autofocus>
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <div class="col-md-12">
                                        <input id="password" placeholder="Password" type="password" class="" name="password">
                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="remember"> Remember Me
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-black btn-block">
                                        Login
                                    </button>
                                    <a class="btn btn-link white-text btn-block" href="{{ url('/password/reset') }}">
                                        Forgot Your Password?
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
