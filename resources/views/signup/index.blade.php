@extends('account.main')

@section('container')


<form action="/signup" method="post" enctype="multipart/form-data">
    @csrf
    

    <div class="container">
        <div class="column1">
            <div class="title-signup">
                <div>
                    <h1>Sign Up</h1>
                </div>
                <p class="login" >Already have an account? <a href="\login">Login</a></p>
            </div>
            <div>
                <label for="customer_first_name">First Name</label>
                <input id="customer_first_name" type="text" name="customer_first_name" class="form-control @error('customer_first_name') is-invalid @enderror mb-3" placeholder="Enter your first name" value="{{ old('customer_first_name') }}">
                @error('customer_first_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div>
                <label for="customer_last_name">Last Name</label>
                <input type="text" name="customer_last_name" id="customer_last_name" class="form-control @error('customer_last_name') is-invalid @enderror mb-3" placeholder="Enter your last name" value="{{ old('customer_last_name') }}">
                @error('customer_last_name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
            <div>
                <label for="customer_email">Email</label>
                <input type="email" name="customer_email" id="customer_email" class="form-control @error('customer_email') is-invalid @enderror mb-3" placeholder="Enter your email" value="{{ old('customer_email') }}">
                @error('customer_email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div>
                <label for="customer_password">Password</label>
                <div class="password-container">
                    <input type="password" name="customer_password" id="customer_password" class="form-control @error('customer_password') is-invalid @enderror mb-3" placeholder="Enter your password">
                    <i class="bi bi-eye-slash" id="togglePassword"></i>
                </div>
                @error('customer_password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

        </div>

        <div class="column2">
            <div>
                <label for="customer_phone_number">Phone Number</label>
                <input type="text" name="customer_phone_number" id="customer_phone_number" class="form-control @error('customer_phone_number') is-invalid @enderror mb-3" placeholder="Enter your phone number" value="{{ old('customer_phone_number') }}">
                @error('customer_phone_number')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div>
                <label for="customer_photo" class="upload-photo mb-3">Upload Your Profile Photo</label>
                <input type="file" name="customer_photo" id="customer_photo" value="{{ old('customer_photo') }}">
            </div>  

            <div>
                <div>
                    <label for="customer_gender">Select Your Gender</label>
                </div>
                <div>
                    <div class="option">
                        <input type="radio" name="customer_gender" id="male" class="form-check-input @error('customer_gender') is-invalid @enderror" value="Male">
                        <label for="male">Male</label>
                    </div>

                    <div class="option">
                        <input type="radio" name="customer_gender" id="female" class="form-check-input @error('customer_gender') is-invalid @enderror" value="Female">
                        <label class="option" for="female">Female</label>
                    </div>

                    <div class="option">
                        <input type="radio" name="customer_gender" id="prefer_not_to_say" class="form-check-input @error('customer_gender') is-invalid @enderror" value="Prefer not to say">
                        <label class="option" for="prefer_not_to_say">Prefer not to say</label>
                    </div>
                </div>  
                @error('customer_gender')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>  
        </div>

    </div>

    <div class="button-submit">
        <button type="submit" >Sign Up</button>
    </div>
</form>
        
@endsection