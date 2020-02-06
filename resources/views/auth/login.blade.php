@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="col-12 text-center mt-5">
                <img src="/imgs/Logo-yello.png" style="width: 150px; height: 150px;">
                <h3 style="color: #5e5288; font-family: 'Nunito', sans-serif;" class="mt-3 mb-5">Sign In To <b class="text-white">Dashboard</b></h3>

                <form method="POST" action="{{ route('login') }}" class="col-12">
                    @csrf
                    <div class="form-group row col-12 text-center m-0 mb-3">
                        <div class="col-lg-6 m-auto">
                            <input style="color:#fff ;border-radius: 40px; background-color: rgba(236, 236, 236, 0);" placeholder="Email" id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                            @if ($errors->has('invalid'))
                                <strong class="text-danger text-sm-left">{{ $errors->first('invalid') }}</strong>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row col-12 text-center m-0 mb-3">
                        <div class="col-lg-6 m-auto">
                            <input style="color:#fff ;border-radius: 40px; background-color: rgba(236, 236, 236, 0);" placeholder="password" id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6 offset-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row col-12 m-0 p-0">
                        <div class="col-lg-4 col-md-4 offset-lg-4 offset-md-4">
                            <button type="submit" class="btn btn-outline-light btn-block">
                                {{ __('Login') }}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
