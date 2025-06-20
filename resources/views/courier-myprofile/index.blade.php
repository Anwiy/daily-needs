@extends ('courier-sidebar.index')

@section('container')

<div class="content-myprofile">
    <div class="container-header">
        <a href="{{ url()->previous() }}" class="back">
            <i class="bi bi-arrow-left-circle"></i>
        </a>
        <h2 class="edit-profile">Edit Profile</h2>
    </div>
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success" id="success-alert">
                {{ session('success') }}
            </div>
        @endif

        <form action="/courier/myprofile/update" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <div id="profile-pic">
                    <label>Profile Photo</label>
                    <div class="form-wrapper">
                        <input id="courier_photo_input" type="file" name="courier_photo" class="form-control" style="display: none;" onchange="previewImage(event)">
                        <label for="courier_photo_input" style="cursor: pointer;">
                            @if(auth('courier')->user()->courier_photo && Storage::disk('public')->exists('courier_photos/' . auth('courier')->user()->courier_photo))
                                <img 
                                    src="{{ asset('storage/courier_photos/' . auth('courier')->user()->courier_photo) }}" 
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
                </div>
            </div>

            <div class="form-group">
                <label>First Name</label>
                <div class="form-wrapper">
                    <input type="text" name="courier_first_name" class="form-control @error('courier_first_name') is-invalid @enderror" value="{{ old('courier_first_name', $couriers->courier_first_name) }}" id="first-name-input" oninput="hidePenIcon('first-name-icon')">
                    <i class="bi bi-pen edit-icon" id="first-name-icon"></i>
                    @error('courier_first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Last Name</label>
                <div class="form-wrapper">
                    <input type="text" name="courier_last_name" class="form-control @error('courier_last_name') is-invalid @enderror" value="{{ old('courier_last_name', $couriers->courier_last_name) }}" id="last-name-input" oninput="hidePenIcon('last-name-icon')">
                    <i class="bi bi-pen edit-icon" id="last-name-icon"></i>
                    @error('courier_last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <div class="form-wrapper">
                    <input type="text" name="courier_phone_number" class="form-control @error('courier_phone_number') is-invalid @enderror" value="{{ old('courier_phone_number', $couriers->courier_phone_number) }}" id="phone-number-input" oninput="hidePenIcon('phone-number-icon')">
                    <i class="bi bi-pen edit-icon" id="phone-number-icon"></i>
                    @error('courier_phone_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Address</label>
                <div class="form-wrapper">
                    <textarea name="courier_address" class="form-control @error('courier_address') is-invalid @enderror" id="address-input" oninput="hidePenIcon('address-icon')">{{ old('courier_address', $couriers->courier_address) }}</textarea>
                    <i class="bi bi-pen edit-icon" id="address-icon"></i>
                    @error('courier_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Password (Leave blank if you do not want to change it)</label>
                <div class="form-wrapper">
                    <input type="password" name="courier_password" class="form-control @error('courier_password') is-invalid @enderror" id="password-input" oninput="hidePenIcon('password-icon')">
                    <i class="bi bi-pen edit-icon" id="password-icon"></i>
                    @error('courier_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <input type="password" name="courier_password_confirmation" class="form-control @error('courier_password_confirmation') is-invalid @enderror" placeholder="Confirm Password" id="confirm-password-input" oninput="hidePenIcon('confirm-password-icon')">
                @error('courier_password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            
            <button type="submit" class="btn btn-success">Save Changes</button>
            

        </form>
    </div>
</div>

@endsection
