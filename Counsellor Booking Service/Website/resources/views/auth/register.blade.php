@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <h2 class="h2 text-center mt-5 mb-5">Register</h2>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email"
                                class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password"
                                class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="new-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm"
                                class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row">

                            <div class="col-sm-12 text-center">
                                <div class="custom-control custom-checkbox align-items-center text-muted">
                                    <input type="checkbox" class="custom-control-input" name="requested_verification"
                                        id="verification-requested">
                                    <label class="custom-control-label" for="verification-requested">
                                        <small>
                                            Register as counsellor
                                        </small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-md-6">
                            <input id="verification-requested" type="checkbox" class="form-control"
                                name="requested_verification">
                        </div> --}}
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-5">
                        <button type="submit" class="btn btn-sm btn-soft-primary font-weight-normal ml-3 transition-3d-hover">
                            {{ __('Register') }}
                        </button>
                    </div>
                </div>
                </form>
        </div>

    </div>
</div>
</div>
@endsection