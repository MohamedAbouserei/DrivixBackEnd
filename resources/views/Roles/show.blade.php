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
        {{--Start Form--}}
        <form   class="col-12"style="margin: 50px auto;background-color: #fff; padding: 30px;border-radius: 8px;">
            <p class="text-center text-dark bg-light rounded p-2"> <i class="fas fa-car-crash"></i> Role <span class="font-weight-bold">( {{ $role->name }} ) </span> Information</p>
            {{ csrf_field() }}
            <div class="form-group row">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Role Name </label>
                <div class="col-sm-9">
                    <input required type="text" class="form-control"  placeholder="Ex: Role Name ...." value="{{ $role->name }}" disabled>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Role Type </label>
                <div class="col-sm-9">
                    <input required type="text" class="form-control" value="{{ $role->type }}" placeholder="Ex: Kia , BMW ...." disabled>
                </div>
            </div>

            <div class="form-group row mb-3">

                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Status </label>
                <div class="col-sm-9">
                    @if($role->lock == 0)
                        <label class="col-12 disabled text-danger">Locked <i class="fas fa-lock"></i></label>
                    @else
                        <label class="col-12 disabled text-success">Un-Locked <i class="fas fa-lock-open"></i></label>
                    @endif
                </div>

            </div>

            <div class="row col-12 m-0 p-0">
                <div class="form-group col-6 p-0 m-0 mb-3">
                    <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label p-0 pb-3">Work From </label>
                    <div class="col-sm-12 p-0">
                        <input required type="text" class="form-control" value="{{ $role->work_from }}" placeholder=" start date...." disabled>
                    </div>
                </div>
                <div class="form-group col-6 p-0 m-0 mb-3">
                    <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label p-0 pl-3 pb-3">Work To </label>
                    <div class="col-sm-12 p">
                        <input required type="text" class="form-control" value="{{ $role->work_to }}" placeholder=" start date...." disabled>
                    </div>
                </div>
            </div>

            <div class="form-group row mb-5 mt-5">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Description </label>
                <div class="col-sm-9">
                    <textarea rows="5"  name="city" type="text" class="form-control" disabled  placeholder="Description">{{ $role->description }}</textarea>
                </div>
            </div>

            <div class="form-group row">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Working Days </label>
                <div class="col-sm-9">
                    <input required type="text" class="form-control"  placeholder="Ex: Role Name ...." value="{{ $role->workingdays }}" disabled>
                </div>
            </div>

            <div class="row col-12 m-0 p-0 mt-5 mb-3">
                <div class="form-group col-6 p-0 m-0 mb-3">
                    <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label p-0 pb-3">Created At </label>
                    <div class="col-sm-12 p-0">
                        <input required type="text" class="form-control" value="{{ $role->created_at }}" placeholder=" start date...." disabled>
                    </div>
                </div>
                <div class="form-group col-6 p-0 m-0 mb-3">
                    <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label p-0 pl-3 pb-3">Updated At </label>
                    <div class="col-sm-12 p">
                        <input required type="text" class="form-control" value="{{ $role->updated_at }}" placeholder=" start date...." disabled>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Role Phones </label>
                <div class="col-sm-9">
                    <textarea required type="text" class="form-control" disabled> {{ $role->phones }} </textarea>
                </div>
            </div>


            <div class="form-group row mb-5">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Role Location </label>
                <div class="col-sm-9">
                    <textarea required type="text" class="form-control" disabled> {{ $role->locations }} </textarea>
                </div>
            </div>

            <p class="bg-light col-10 m-auto p-2 text-secondary text-center font-weight-bolder mb-5">Role Photos &nbsp; <i class="far fa-images"></i></p>

            @if(isset($role->images))
                <div class="owl-carousel mt-5">
                    @foreach($role->images as $image)
                        <div>
                            <img class="img-thumbnail" src="{{ 'http://www.drivixcorp.com/api/storage/' .  $image->image . '/RolesImgs' }}" width="100%" height="100px">
                        </div>
                    @endforeach
                </div>
            @else
            <p class="mt-3 text-center">there is no image to display !</p>
            @endif

        </form>
        {{--End Form--}}
    </div>
    <!-- /.container-fluid -->
    <script>
        $(document).ready(function(){
            $(".owl-carousel").owlCarousel();
        });
    </script>
@endsection


