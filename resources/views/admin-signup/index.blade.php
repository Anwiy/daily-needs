@extends('admin-layouts.main')

@section('container')


<form action="/admin/signup" method="post" enctype="multipart/form-data">
    @csrf

    <div class="title">
        <h1>Sign Up</h1>
    </div>

    <div>
        <p class="login">Already have an account? <a class="go-signup" href="/admin/login">Login</a></p>
    </div>

    <div class="form-container">
        <div class="first-name">
            <label for="admin_first_name">First Name</label>
            <input id="admin_first_name" type="text" name="admin_first_name" class="form-control @error('admin_first_name') is-invalid @enderror" placeholder="Enter your first name" value="{{ old('admin_first_name') }}" required>
            @error('admin_first_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="last-name">
            <label for="admin_last_name">Last Name</label>
            <input type="text" name="admin_last_name" id="admin_last_name" class="form-control @error('admin_last_name') is-invalid @enderror" placeholder="Enter your last name" value="{{ old('admin_last_name') }}" required>
            @error('admin_last_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        
        <div class="email">
            <label for="admin_email">Email</label>
            <input type="email" name="admin_email" id="admin_email" class="form-control @error('admin_email') is-invalid @enderror" placeholder="Enter your email" value="{{ old('admin_email') }}" required>
            @error('admin_email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="password">
            <label for="admin_password">Password</label>
            <div class="password-wrapper">
                <input type="password" name="admin_password" id="admin_password" class="form-control @error('admin_password') is-invalid @enderror" placeholder="Enter your password" required>
                <i class="bi bi-eye-slash toggle-password"></i>
            </div>
            @error('admin_password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="phone-number">
            <label for="admin_phone_number">Phone Number</label>
            <input type="text" name="admin_phone_number" id="admin_phone_number" class="form-control @error('admin_phone_number') is-invalid @enderror" placeholder="Enter your phone number" value="{{ old('admin_phone_number') }}" required>
            @error('admin_phone_number')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="address">
            <label for="admin_address">Address</label>
            <input type="text" name="admin_address" id="admin_address" class="form-control @error('admin_address') is-invalid @enderror" placeholder="Enter your address" value="{{ old('admin_address') }}" required>
            @error('admin_address')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="photo">
            <label for="admin_photo" class="upload-photo">Upload Your Profile Photo</label>
            <input type="file" name="admin_photo" id="admin_photo" value="{{ old('admin_photo') }}">
        </div>

        <div class="gender">
            <div>
                <label for="admin_gender">Select Your Gender</label>
            </div>
            <div>
                <div>
                    <input type="radio" name="admin_gender" id="male" class="form-check-input @error('admin_gender') is-invalid @enderror" value="Male">
                    <label for="male">Male</label>
                </div>

                <div>
                    <input type="radio" name="admin_gender" id="female" class="form-check-input @error('admin_gender') is-invalid @enderror" value="Female">
                    <label for="female">Female</label>
                </div>

                <div>
                    <input type="radio" name="admin_gender" id="prefer_not_to_say" class="form-check-input @error('admin_gender') is-invalid @enderror" value="Prefer not to say">
                    <label for="prefer_not_to_say">Prefer not to say</label>
                </div>
            </div>
            @error('admin_gender')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="button-submit">
            <button type="submit">Sign Up</button>
        </div>
    </div>

    
</form>
        
@endsection