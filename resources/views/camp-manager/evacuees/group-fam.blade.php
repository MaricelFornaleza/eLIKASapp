@extends('layouts.mobileBase')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Select Disaster Response -->
            <div class="form-group col-sm-6">
                <label for="disaster_response">Disaster Response</label>
                <select name="disaster_response" class="form-control" id="disaster_response">
                    <option value="">Select</option>
                    <option value="dr-1">Disaster Response 1</option>
                    <option value="dr-2">Disaster Response 2</option>
                    <option value="dr-3">Disaster Response 3</option>

                </select>

            </div>
            <!-- Barangay Information  -->

            <div class="col-md-6">
                <h5 class="font-weight-bold">Choose Family Representative </h5>
                <small>The chosen family member is the one who will receive the relief goods. </small>
            </div>

            <div class="col-md-6 px-0 pt-4 ">
                <ul class="list-group list-group-hover list-group-striped">
                    <li class="list-group-item list-group-item-action ">
                        <div class="form-check">
                            <input class="form-check-input  @error('name') is-invalid @enderror radio" type="radio"
                                value="Name" id="name0" name="barangay[]">
                            <label class="form-check-label" for="name0">
                                Name
                            </label>
                            <span class="float-right my-2">
                                <div class="rounded-circle bg-secondary" style="height: 10px; width:10px;"></div>
                            </span>
                        </div>
                    </li>
                    <li class="list-group-item list-group-item-action ">
                        <div class="form-check">
                            <input class="form-check-input  @error('name') is-invalid @enderror radio" type="radio"
                                value="Name" id="name1" name="barangay[]">
                            <label class="form-check-label" for="name1">
                                Name
                            </label>
                            <span class="float-right my-2">
                                <div class="rounded-circle bg-accent" style="height: 10px; width:10px;"></div>
                            </span>
                        </div>
                    </li>
                    <li class="list-group-item list-group-item-action ">
                        <div class="form-check">
                            <input class="form-check-input  @error('name') is-invalid @enderror radio" type="radio"
                                value="Name" id="name2" name="barangay[]">
                            <label class="form-check-label" for="name2">
                                Name
                            </label>
                            <span class="float-right my-2">
                                <div class="rounded-circle bg-primary" style="height: 10px; width:10px;"></div>
                            </span>
                        </div>
                    </li>
                    <li class="list-group-item list-group-item-action ">
                        <div class="form-check">
                            <input class="form-check-input  @error('name') is-invalid @enderror radio" type="radio"
                                value="Name" id="name3" name="barangay[]">
                            <label class="form-check-label" for="name3">
                                Name
                            </label>
                            <span class="float-right my-2">
                                <div class="rounded-circle bg-success" style="height: 10px; width:10px;"></div>
                            </span>
                        </div>
                    </li>
                    <li class="list-group-item list-group-item-action ">
                        <div class="form-check">
                            <input class="form-check-input  @error('name') is-invalid @enderror radio" type="radio"
                                value="Name" id="name4" name="barangay[]">
                            <label class="form-check-label" for="name4">
                                Name
                            </label>
                            <span class="float-right my-2">
                                <div class="rounded-circle bg-danger" style="height: 10px; width:10px;"></div>
                            </span>

                        </div>
                    </li>
                </ul>
            </div>
            <div class="fixed-bottom mt-5">
                <div class="col-12 center  ">
                    <div class="col-md-6 p-0 ">
                        <a href="#">
                            <button class="btn btn-accent  px-4 ">Admit</button>
                        </a>
                    </div>
                </div>
                <div class="col-12 center mt-4">
                    <div class="col-md-6 mb-4 p-0">
                        <a href="">
                            <button class="btn btn-accent-outline  px-4 ">Cancel</button>
                        </a>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
@endsection