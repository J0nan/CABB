@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href={{ asset('css/login.css') }}>
@endpush
@push('scripts')
    <script> URI_POST_LOG_IN = '{{ route('index') }}'</script>
    <script src={{ asset('js/logIn.js') }}></script>
@endpush

@section('page-content')
        <div>
            <h1>Login</h1>
        </div>
        <div class="container-sm" style="max-width: 30em; margin-top: 5%">
            <form onsubmit="return submitForm(event)">
                {{-- <img class="mb-4" src="/docs/5.2/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57"> --}}
                <legend class="h3 mb-3 fw-normal">Enter your credentials</legend>
            
                <div class="form-floating" style="margin-top: 2em">
                    <input type="email" class="form-control" id="email" placeholder="name@example.com">
                    <label for="email">Email address</label>
                    <div id="emailErrorInfo" class="invalid-feedback" style="z-index: 11; position: fixed">Email must not be empty</div>
                </div>
                <div class="form-floating" style="margin-top: 2em">
                    <input type="password" class="form-control" id="password" placeholder="Password">
                    <label for="password">Password</label>
                    <div id="passwordErrorInfo" class="invalid-feedback" style="z-index: 11; position: fixed">Password must not be empty</div>
                </div>
            
                {{-- <div class="checkbox mb-3">
                    <label>
                        <input type="checkbox" value="remember-me"> Remember me
                    </label>
                </div> --}}
                <div class="d-flex justify-content-center" style="margin-top: 2em">
                    <button type="submit" class="btn btn-primary btn-hover" id="logInButton" style="min-width: 150px">Log in</button>
                </div>
                <div id="serverError" class="alert alert-dismissible alert-danger" style="margin-top: 2em; display: none">
                    <button type="button" class="btn-close" onclick="hideServerError()"></button>
                    <h4 id="statusError" class="alert-heading">Error</h4>
                    <div id="textError">There was an error</div>
                </div>
            </form>
        </div>
@endsection

@stack('styles')
@stack('scripts')