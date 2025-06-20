@extends('courier-layouts.main')

@section('container')

@if(session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">

        </button>
    </div>
@endif

@if(session()->has('loginError'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('loginError') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">

        </button>
    </div>
@endif


<div class="d-flex justify-content-center align-items-center min-vh-100">
    <form action="/courier/login" method="post" class= "courier-login-container">
        @csrf

        <div>
            <h1>Login</h1>
        </div>

        <div>
            <div>
                <p class="signup">Don't have any account? <a href="/courier/signup" class="go-signup">Sign Up</a></p>
            </div>
            <div class = "form-group">    
                <label for="courier_email">Email</label>
                <input type="email" name="courier_email" id="courier_email" class="form-control @error('courier_email') is-invalid @enderror" placeholder="Enter your email" value="{{ old('courier_email') }}" autofocus required>
                @error('courier_email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        
            <div class = "form-group">
                <label for="courier_password">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="courier_password" id="courier_password" class="form-control @error('courier_password') is-invalid @enderror" placeholder="Enter your password" required>
                    <i class="bi bi-eye-slash toggle-password"></i>
                </div>
            </div>
        </div>

        <div>
            <button type="submit">Login</button>
        </div>

    </form>
</div>


@endsection
