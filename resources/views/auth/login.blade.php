@extends('auth.auth_app')

@section('content')
<div class="login-box-body">
    <p class="login-box-msg">{{ __('Login') }}</p>

    <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
        @csrf
        <div class="form-group has-feedback">
            <input id="member_no" type="member_no" class="form-control{{ $errors->has('member_no') ? ' is-invalid' : '' }}" name="member_no" value="{{ old('member_no') }}" required autofocus placeholder="{{ __('員工編號') }}">
            @if ($errors->has('member_no'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('member_no') }}</strong>
            </span>
            @endif
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback">
            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="{{ __('Password') }}">

            @if ($errors->has('password'))
            <span class="invalid-feedback" role="alert">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
            @endif
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

        <div class="row">
            <div class="col-xs-8">
              <div class="icheck">
                 <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                </label>
            </div>
        </div>
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('Login') }}</button>
      </div>
      <!-- /.col -->
  </div>
</form>
<!-- /.social-auth-links -->

<!-- <a class="btn btn-link" href="{{ route('password.request') }}">
    {{ __('Forgot Your Password?') }}
</a>
<br>
<a class="btn btn-link" href="{{ route('register') }}">
    {{ __('register') }}
</a> -->
</div>
<!-- /.login-box-body -->


@endsection
