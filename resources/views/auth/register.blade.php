@extends('layouts.app')

@section('styles')
<style>
    /* Add your custom CSS styles here */
    .toast {
        position: fixed;
        top: 80px;
        right: 0px;
        color: #fff;
        padding: 10px;
        display: none;
        border-radius: 5px;
    }
</style>
@endsection

@section('content')

<main class="d-flex w-100">
    <div class="container d-flex flex-column">
        <div class="row vh-100">
            <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                <div class="mt-5">
                    <div class="text-center">
                        <h1 class="h2"><strong>Cendbad MGMT</strong></h1>
                    </div>
                    <hr>
                    <div class="card">
                        <div class="card-header">
                            <div class="m-sm-3">
                                <h3><strong>Signup</strong></h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="m-sm-3">
                                <form method="POST" action="{{ route('users.register') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Full name</label>
                                        <input class="form-control form-control-lg" type="text" name="name" placeholder="Enter your name" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input class="form-control form-control-lg" type="email" name="email" placeholder="Enter your email" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input class="form-control form-control-lg" type="password" name="password" placeholder="Enter password" />
                                    </div>
                                    <div class="d-grid gap-2 mt-3">
                                        <button type="submit" class="btn btn-lg btn-primary">Sign up</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mb-3">
                        Already have account? <a href="{{route('login')}}">Log In</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<div class="container">
    <!-- Toast Notification -->
    <div id="toast" class="toast">
    </div>
    <!-- <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> -->
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var data = form.serialize();
            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.status == true) {
                        // Show the toast on successful registration
                        $('#toast').html(response.message);
                        $('#toast').css('background-color', 'green');
                        $('#toast').fadeIn().delay(3000).fadeOut();
                    } else if (response.status == false) {
                        // Show the toast on failed registration
                        $('#toast').html(response.message);
                        $('#toast').css('background-color', 'red');
                        $('#toast').fadeIn().delay(3000).fadeOut();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });
</script>
@endsection