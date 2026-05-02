@extends('layouts.app')

@section('title', 'Register - SM-Autos')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card bg-dark text-white border-0 shadow-lg">
                <div class="card-body p-5">
                    <h3 class="fw-bold mb-1 text-center">Create Account</h3>
                    <p class="text-muted text-center mb-4">Register to list your vehicles</p>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('user.register.post') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name') }}" required/>
                            @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email') }}" required/>
                            @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required/>
                            @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required/>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 py-2 fw-bold">Register</button>
                    </form>

                    <p class="text-center mt-3 text-muted">
                        Already have an account?
                        <a href="{{ route('user.login') }}" class="text-danger">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection