@extends('layouts.authBase')

@section('content')

@if(\App\Models\User::count() > 0)
@include('errors.registration_error')
@else

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card-group">
                <div class="card p-4">
                    <div class="card-body">

                        <h3 class=" register">Create an Account</h3>
                        <p class="text-muted ">Sign up to create an account.</p>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="input-group mb-3 mt-5">

                                <input id="name" class="form-control @error('name') is-invalid @enderror" type="text"
                                    placeholder="{{ __('Name') }}" name="name" value="{{ old('name') }}" required
                                    autofocus>
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="input-group mb-3 ">

                                <input id="email" class="form-control @error('email') is-invalid @enderror" type="email"
                                    placeholder="{{ __('E-Mail Address') }}" name="email" value="{{ old('email') }}"
                                    required>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="input-group mb-3 ">

                                <input id="contact_no" class="form-control @error('contact_no') is-invalid @enderror"
                                    type="number" placeholder="{{ __('Contact Number') }}" name="contact_no"
                                    value="{{ old('contact_no') }}" required>
                                @error('contact_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="input-group mb-3 ">

                                <input id="branch" class="form-control @error('branch') is-invalid @enderror"
                                    type="text" placeholder="{{ __('Branch') }}" name="branch"
                                    value="{{ old('branch') }}" required>
                                @error('branch')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="input-group mb-4">

                                <input id="password" class="form-control @error('password') is-invalid @enderror"
                                    type="password" placeholder="{{ __('Password') }}" name="password" required>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="input-group mb-4">

                                <input id="password-confirm" class="form-control" type="password"
                                    placeholder="{{ __('Confirm Password') }}" name="password_confirmation" required
                                    autocomplete="new-password">
                            </div>

                            <div class="input-group mb-4">
                                <div class="col-12 center">
                                    <button class="btn btn-register px-4 " type="submit">{{ __('Register') }}</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endif


@endsection

@section('javascript')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>



<script>
$(document).ready(function() {

    $.ajax({
        type: "GET",
        dataType: "json",
        headers: {
            "Accept": "application/json"
        },
        url: "http://bkintanar-psgc.herokuapp.com/api/provinces?include=cities,municipalities",
        success: function(result) {
            console.log(response);
        },
        error: function() {
            alert("Local error callback.");
        },
        complete: function() {
            alert("Local completion callback.");
        }
    });
})
</script>
@endsection