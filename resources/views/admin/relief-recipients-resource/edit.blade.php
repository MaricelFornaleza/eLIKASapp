@extends('layouts.webBase')

@section('css')

@endsection

@section('content')

<div class="container-fluid">
    <div class="fade-in">

        <div class="row center">
            <div class="col-lg-8 ">
                <div class="card">
                    <div class="card-header">
                        Edit Resident

                    </div>
                    <div class="card-body ">
                        <form method="POST" action="{{ route('residents.update', $family_member->id) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="lead">Name<a style="color:red"> *</a></label>
                                        <input class="form-control @error('name') is-invalid @enderror" type="text" placeholder="{{ __('Enter Name') }}" name="name" value="{{ $family_member->name }}" required autofocus>
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                           
                            <!-- /.row-->
                            <!-- <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="lead">Address<a style="color:red"> *</a></label>
                                        <input class="form-control @error('address') is-invalid @enderror" type="text" placeholder="{{ __('Enter Address') }}" name="address" required autofocus>
                                        @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div> -->

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                    <label class="lead">Birthdate</label>
                                  <input class="form-control @error('birthdate') is-invalid @enderror" type="date" placeholder="{{ __('Enter Birthdate') }}" name="birthdate" value="{{ $family_member->birthdate }}"required autofocus>
                                        @error('birthdate')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                           
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="lead">Sectoral Classification</label>
                                        <select class="form-control @error('sectoral_classification') is-invalid @enderror" aria-label=".form-select-lg example" name="sectoral_classification"  required autofocus>
                                            <option value="" disabled>Select</option>
                                            @if($family_member->sectoral_classification == 'None')
                                            <option value="None" selected>None</option>
                                            @else
                                            <option value="None">None</option>
                                            @endif
                                            @if($family_member->sectoral_classification == 'Lactating')
                                            <option value="Lactating" selected>Lactating</option>
                                            @else
                                            <option value="Lactating">Lactating</option>
                                            @endif
                                            @if($family_member->sectoral_classification == 'PWD')
                                            <option value="PWD" selected>PWD</option>
                                            @else
                                            <option value="PWD">PWD</option>
                                            @endif
                                            @if($family_member->sectoral_classification == 'Senior Citizen')
                                            <option value="Senior Citizen" selected>Senior Citizen</option>
                                            @else
                                            <option value="Senior Citizen">Senior Citizen</option>
                                            @endif
                                            @if($family_member->sectoral_classification == 'Children')
                                            <option value="Children" selected>Children</option>
                                            @else
                                            <option value="Children">Children</option>
                                            @endif
                                            @if($family_member->sectoral_classification == 'Pregnant')
                                            <option value="Pregnant" selected>Pregnant</option>
                                            @else
                                            <option value="Pregnant">Pregnant</option>
                                            @endif
                                            @if($family_member->sectoral_classification == 'Solo Parent')
                                            <option value="Solo Parent" selected>Solo Parent</option>
                                            @else
                                            <option value="Solo Parent">Solo Parent</option>
                                            @endif
                                        </select>
                                        @error('sectoral_classification')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label class="lead">Gender</label>
                                    </div>
                                    
                                    <div class="px-3 row justify-content-start">
                                        <div class="col-sm-2">
                                            <div class="form-group row px-3">
                                                @if($family_member->gender == 'Female')
                                                <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="radio_female" value="Female" checked required autofocus>
                                                @else
                                                <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="radio_female" value="Female" required autofocus>    
                                                @endif
                                            <label class="form-check-label" for="radio_female">
                                                Female
                                            </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group row px-3">
                                                @if($family_member->gender == 'Male')
                                                <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="radio_male" value="Male" checked required autofocus>
                                                @else
                                                <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="radio_male" value="Male" required autofocus>
                                                @endif
                                            <label class="form-check-label" for="radio_male">
                                                Male
                                            </label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('gender')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mt-5 center">
                                <div class="col-4 ">
                                    <button class="btn btn-primary px-4 " type="submit">{{ __('Update') }}</button>
                                </div>
                                <div class="col-4 ">
                                    <a href="{{ route('residents.index') }}" class="btn btn-outline-primary px-4 "
                                        >{{ __('Cancel') }}</a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <!-- /.col-->
        </div>
        <!-- /.row-->
    </div>
</div>

@endsection

@section('javascript')

@endsection
