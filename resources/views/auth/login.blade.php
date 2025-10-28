@extends('layouts.sneat-auth')

@section('content')

              <!-- /Logo -->
              <h4 class="mb-2">Welcome to {{ env('APP_NAME') }} {{ __('Login') }}</h4>
              <p class="mb-4">Please sign-in to your account and start the adventure</p>

              <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('login') }}">
                        @csrf

                <div class="mb-3">
                  <label for="email" class="form-label">{{ __('Email Address') }}</label>

                  <input
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    name="email" 
                    value="{{ old('email') }}" 
                    placeholder="Enter your email"
                    required autocomplete="email" autofocus />

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">{{ __('Password') }}</label>
                    @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                    @endif
                  </div>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control @error('password') is-invalid @enderror" 
                      name="password" 
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password" 
                      required autocomplete="new-password" />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                  </div>
                </div>

                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="" />
                    <input class="form-check-input" type="checkbox" id="remember-me" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember-me">{{ __('Remember Me') }}</label>
                  </div>
                </div>

                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit">{{ __('Login') }}</button>
                </div>
              </form>

              <!-- <p class="text-center">
                <span>New on our platform?</span>
                <a href="#">
                  <span>Create an account</span>
                </a>
              </p> -->

@endsection
