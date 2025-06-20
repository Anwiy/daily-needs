@extends('courier-layouts.main')

@section('container')

    

    <form action="/courier/signup" method="post" enctype="multipart/form-data">
        @csrf
        <div class="title">
            <h1>Sign Up</h1>
        </div>

        <div>
            <div>
                <p class="login">Already have an account? <a class="go-signup" href="/courier/login">Login</a></p>
            </div>
        </div>

        <div class="form-container">
            <div class="first-name">
                <label for="courier_first_name">First Name</label>
                <input id="courier_first_name" type="text" name="courier_first_name" class="form-control @error('courier_first_name') is-invalid @enderror" placeholder="Enter your first name" value="{{ old('courier_first_name') }}" required>
                @error('courier_first_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="last-name">
                <label for="courier_last_name">Last Name</label>
                <input type="text" name="courier_last_name" id="courier_last_name" class="form-control @error('courier_last_name') is-invalid @enderror" placeholder="Enter your last name" value="{{ old('courier_last_name') }}" required>
                @error('courier_last_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="email">
                <label for="courier_email">Email</label>
                <input type="email" name="courier_email" id="courier_email" class="form-control @error('courier_email') is-invalid @enderror" placeholder="Enter your email" value="{{ old('courier_email') }}" required>
                @error('courier_email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="password">
                <label for="courier_password">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="courier_password" id="courier_password" class="form-control @error('courier_password') is-invalid @enderror" placeholder="Enter your password" required>
                    <i class="bi bi-eye-slash toggle-password"></i>
                </div>
                @error('courier_password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="phone-number">
                <label for="courier_phone_number">Phone Number</label>
                <input type="text" name="courier_phone_number" id="courier_phone_number" class="form-control @error('courier_phone_number') is-invalid @enderror" placeholder="Enter your phone number" value="{{ old('courier_phone_number') }}" required>
                @error('courier_phone_number')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="address">
                <label for="courier_address">Address</label>
                <input type="text" name="courier_address" id="courier_address" class="form-control @error('courier_address') is-invalid @enderror" placeholder="Enter your address" value="{{ old('courier_address') }}" required>
                @error('courier_address')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="photo">
                <label for="courier_photo" class="upload-photo">Upload Your Profile Photo</label>
                <input type="file" name="courier_photo" id="courier_photo" value="{{ old('courier_photo') }}">
            </div>

            <div class="gender">
                <div>
                    <label for="courier_gender">Select Your Gender</label>
                </div>

                <div>
                    <div>
                        <input type="radio" name="courier_gender" id="male" class="form-check-input @error('courier_gender') is-invalid @enderror" value="Male">
                        <label for="male">Male</label>
                    </div>

                    <div>
                        <input type="radio" name="courier_gender" id="female" class="form-check-input @error('courier_gender') is-invalid @enderror" value="Female">
                        <label for="female">Female</label>
                    </div>

                    <div>
                        <input type="radio" name="courier_gender" id="prefer_not_to_say" class="form-check-input @error('courier_gender') is-invalid @enderror" value="Prefer not to say">
                        <label for="prefer_not_to_say">Prefer not to say</label>
                    </div>
                </div>
                @error('courier_gender')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="button-submit">
                <button type="submit">Sign Up</button>
            </div>
        </div>
    </div>

    
    </form>

@endsection