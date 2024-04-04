<h4 class="text-decoration-underline">Account Details</h4>
<div class="row">
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="name">
            Name *
        </label>
        <input type="text" class="form-control" name="name" id="name" placeholder="Enter User Name" value="@isset($user){{$user->name}}@endisset" required>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="email">
            Email *
        </label>
        <input type="email" class="form-control" name="email" id="email" placeholder="Enter User Email" value="@isset($user){{$user->email}}@endisset" required>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="password">
            Password
        </label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Enter User Password" @if(!Auth::user()->hasRole('admin')) disabled @endif>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="user_type">
            User Type *
        </label>
        <select type="user_type" class="form-control" name="user_type" id="user_type" @isset($user) @if($user->user_type == '1') disabled @endif @endisset>
            <option value="">Select User Type</option>
            @foreach ($roles as $role)
                <option value="{{$role->name}}" @if ($role->id == 1) disabled @endif @isset($user) @if($user->user_type == $role->name) selected @endif @endisset>{{ucfirst($role->name)}}</option>
            @endforeach
        </select>
    </div>
</div>
<hr>
<h4 class="text-decoration-underline">Personal Details</h4>
<div class="row">
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="phone_number">
            Phone Number *
        </label>
        <br>
        <div class="input-group">
            <div class="input-group-prepend">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img id="selected-country-flag" src="https://flagcdn.com/ae.svg" alt="">
                    <span id="selected-country-code">+971</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end" id="country-dropdown">
                    <!-- Dynamic options will be added here -->
                </div>
            </div>
            <input type="hidden" id="hidden-country-code" name="country_code" value="+971">
            <input type="number" maxlength="15" class="form-control" name="phone_number" id="phone_number" placeholder="Enter phone number" value="@isset($user->user_details){{$user->user_details->phone_number}}@endisset" required>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="street">
            Street *
        </label>
        <br>
        <input type="text" class="form-control" name="street" id="street" placeholder="Enter street name" value="@isset($user->user_details){{$user->user_details->street}}@endisset" required>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="state_city">
            State / City *
        </label>
        <br>
        <input type="text" class="form-control" name="state_city" id="state_city" placeholder="Enter state or city name" value="@isset($user->user_details){{$user->user_details->state_city}}@endisset" required>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="country">
            Country *
        </label>
        <br>
        <input type="text" class="form-control" name="country" id="country" placeholder="Enter country name" value="@isset($user->user_details){{$user->user_details->country}}@endisset" required>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="image">
            User Profile Photo
        </label>
        <input type="file" class="form-control" name="image" id="image" placeholder="Upload User Photo" value="@isset($user->user_details){{$user->user_details->image}}@endisset">
        <div class="image-preview">
            <div class="color-box">
                <img id="previewImage" src="
                @isset($user->user_details)
                    @if($user->user_details->image != '')
                        {{asset('public/img/photos/'.$user->user_details->image)}}
                    @else {{asset('public/img/avatars/user.png')}}
                    @endif
                @else
                    {{asset('public/img/avatars/user.png')}}
                @endisset" alt="User Image">
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="active">
            Active / Inactive
        </label>
        <br>
        <input type="checkbox" class="form-check-input border border-secondary" name="active_status" id="active" @isset($user) @if($user->active_status == 'active') checked @endif @endisset>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-3">
        <button type="submit" class="d-flex btn btn-primary save-button" id="save-button">Save <div class="loader d-none"></div></button>
    </div>
</div>