@extends ('layouts.dashboardmain')

@section('container')

    <div class="content">

        <div class="header-container">
            <a href="{{ url()->previous() }}" class="back">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="edit-profile">Edit Profile</h2>
        </div>
    
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    
        <form action="/dashboard/myprofile/update" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
    
            <div class="form-group">
                <label>Profile Photo</label>
                <input id="customer_photo_input" type="file" name="customer_photo" class="form-control" style="display: none;" onchange="previewImage(event)">
                    
                <label for="customer_photo_input" style="cursor: pointer;">
                    @if(auth('customer')->user()->customer_photo && Storage::disk('public')->exists('customer_photos/' . auth('customer')->user()->customer_photo))
                        <img 
                            src="{{ asset('storage/customer_photos/' . auth('customer')->user()->customer_photo) }}" 
                            alt="Profile Photo" 
                            class="profile-photo" 
                            id="profile-photo-preview"
                        >
                    @else
                        <div class="profile-photo-placeholder">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    @endif
                </label>
            </div>
    
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="customer_first_name" class="form-control @error('customer_first_name') is-invalid @enderror" value="{{ old('customer_first_name', $customers->customer_first_name) }}">
                @error('customer_first_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
    
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="customer_last_name" class="form-control @error('customer_last_name') is-invalid @enderror" value="{{ old('customer_last_name', $customers->customer_last_name) }}">
                @error('customer_last_name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
    
            <div class="form-group">
                <label for="customer_gender">Gender</label>
                <div class="radio-btn">
                    <div class="radio-btn">
                        <input type="radio" name="customer_gender" id="male" class="form-check-input @error('customer_gender') is-invalid @enderror" value="Male"
                            {{ old('customer_gender', $customers->customer_gender) == 'Male' ? 'checked' : '' }}>
                        <label for="male">Male</label>
                    </div>

                    <div class="radio-btn">
                        <input type="radio" name="customer_gender" id="female" class="form-check-input @error('customer_gender') is-invalid @enderror" value="Female"
                            {{ old('customer_gender', $customers->customer_gender) == 'Female' ? 'checked' : '' }}>
                        <label for="female">Female</label>
                    </div>
                    
                    <div class="radio-btn">
                        <input type="radio" name="customer_gender" id="prefer_not_to_say" class="form-check-input @error('customer_gender') is-invalid @enderror" value="Prefer not to say"
                            {{ old('customer_gender', $customers->customer_gender) == 'Prefer not to say' ? 'checked' : '' }}>
                        <label for="prefer_not_to_say">Prefer not to say</label>
                    </div>

                </div>
                @error('customer_gender')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="customer_phone_number" class="form-control @error('customer_phone_number') is-invalid @enderror" value="{{ old('customer_phone_number', $customers->customer_phone_number) }}">
                @error('customer_phone_number')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
    
            <div class="form-group">
                <label>Password (Leave blank if you do not want to change it)</label>
                <input type="password" name="customer_password" class="form-control @error('customer_password') is-invalid @enderror">
                @error('customer_password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                <label>Confirm Password</label>
                <input type="password" name="customer_password_confirmation" class="form-control @error('customer_password_confirmation') is-invalid @enderror" placeholder="Confirm Password">
                @error('customer_password_confirmation')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
    
            <button type="submit" class="button-submit-profile">Save Changes</button>
        </form>
    </div>

@endsection