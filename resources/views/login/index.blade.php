@extends('account.main')

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


<div class="container">
    <div class="left-side">
        <img src="assets/image/customer-login-background.jpg" alt="" class="image">
    </div>
    

    <div class="form-container">
        <form action="/login" method="post" class="customer-login-container">
        @csrf
        
        <div class="title-login">
            <h1>Login</h1>
        </div>
    
        <div>
            <div>
                <p class="signup">Don't have any account? <a href="/signup">Sign Up</a></p>
            </div>
            <div class="form-group">    
                <label for="customer_email">Email</label>
                <input type="email" name="customer_email" id="customer_email" class="form-control @error('customer_email') is-invalid @enderror" placeholder="Enter your email" value="{{ old('customer_email') }}" autofocus>
                @error('customer_email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
    
            <div class="form-group">
                <label for="customer_password">Password</label>
                <div class="input-icon-wrapper">
                    <input 
                        type="password" 
                        name="customer_password" 
                        id="customer_password" 
                        class="form-control @error('customer_password') is-invalid @enderror" 
                        placeholder="Enter your password"
                        
                    >
                    <i class="bi bi-eye-slash toggle-password"></i>
                </div>
                @error('customer_password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    
        <div class="container-button">
            <button type="submit">Login</button>
        </div>

        </form>
    </div>
</div>

@endsection
