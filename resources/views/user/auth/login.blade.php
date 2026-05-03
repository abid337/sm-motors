@extends('layouts.app')

@section('title', 'Login - SM-Autos')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card bg-dark text-white border-0 shadow-lg">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-1 text-center">Welcome Back!</h3>
                    <p class="text-white text-center mb-4">Login to manage your listings</p>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('user.login.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email') }}" required/>
                            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required/>
                            @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember"/>
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 py-2 fw-bold">Login</button>
                    </form>

                    <p class="text-center mt-3 text-white">
                        Don't have an account?
                        <a href="{{ route('user.register') }}" class="text-danger">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection