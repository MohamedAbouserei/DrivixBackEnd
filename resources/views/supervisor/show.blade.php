@extends('layouts.DashBoardStructure')
@section('content')
    <!-- Main Content -->
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-outline-dark shadow-sm"><i class="fas fa-download fa-sm text-white-90"></i> Generate Report</a>
        </div>
        <!-- Content Row -->
        <div  id="SessionMessage" class="flash-message" style="width: 50%; margin: auto;box-shadow: 1px 1px 2px #fff , -1px -1px 1px #fff;">
            @if(Session::has('success'))
                <p class="alert alert-success text-white" style="text-align: center;">{{ Session::get('success') }} &nbsp; <i class="fas fa-check-double"></i></p>
            @endif
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-white">{{ $error }} <i class="fas fa-times-circle" style="float:right;"></i> </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{--Start Form--}}
        <form  action="/updateSupervisor" class="col-12" method="post" style="margin: 50px auto;background-color: #fff; padding: 30px;border-radius: 8px;" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{$sup->id}}">
            <p class="text-center text-dark bg-light rounded p-2"> <i class="fas fa-users-cog pr-3"></i> Edit Supervisor [ {{ $sup->User->name }}]</p>
            {{ csrf_field() }}
            <div class="form-group row">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Supervisor Name </label>
                <div class="col-sm-9">
                    <input required name="name" type="text" class="form-control"  placeholder="Ex: Total ...." value="{{$sup->User->name}}">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Email </label>
                <div class="col-sm-9">
                    <input required name="email" type="email" class="form-control" value="{{ $sup->User->email }}"  placeholder="Ex: test@gmail.com ....">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">New Password  (optional) </label>
                <div class="col-sm-9">
                    <input  name="npassowrd" type="password" class="form-control" value=""  placeholder="Ex  ***********">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Salary </label>
                <div class="col-sm-9">
                    <input required name="salary" type="number" class="form-control" value="{{ $sup->salary }}"  placeholder="4500 E.G">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">National ID </label>
                <div class="col-sm-9">
                    <input required name="nationalID" type="number" class="form-control" value="{{ $sup->national_id }}"  placeholder="452135456465123">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">work hours </label>
                <div class="col-sm-9">
                    <input required name="work_hours" type="number" class="form-control" value="{{ $sup->work_hours }}"  placeholder="6 hours">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Hire date </label>
                <div class="col-sm-9">
                    <input required name="hire_date" type="date" class="form-control" value="{{ $sup->hire_date }}"  placeholder="8/11/2017">
                </div>
            </div>

            <div class="form-group row col-12  p-0 m-0">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label p-0">Status </label>
                <div class="col-sm-9">
                    <div class="custom-control custom-switch" style="margin-left: 11px;">
                        <input name="status" type="checkbox" {{ ($sup->User->status == '1')? 'checked' : '' }} class="custom-control-input" id="customSwitch1" >
                        <label class="custom-control-label" for="customSwitch1"></label>
                    </div>
                </div>
            </div>

            <div class="p-3 m-auto">
                <p class="p-2 bg-light text-center col-12 mt-5 mb-3"> Upload File </p>
                <div class="input-group mb-3 col-10 offset-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupFileAddon01">Supervisor Image</span>
                    </div>
                    <div class="custom-file">
                        <input type="file" name="image" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                        <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                    </div>
                </div>
                <div>
                    <img class="img-thumbnail" style="width: 300px; height: 200px; object-fit: cover;" src="http://www.drivixcorp.com/api/storage/{{$sup->User->profile->image}}/users" >
                </div>
            </div>


            <button class="btn btn-block btn-outline-dark col-4 offset-4 mt-5">
                Update Supervisor
            </button>
        </form>
        {{--End Form--}}



    </div>
    <!-- /.container-fluid -->

@endsection


