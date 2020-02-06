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


        <div  id="SessionMessage" class="flash-message" style="width: 50%; margin: auto;box-shadow: 1px 1px 2px #fff , -1px -1px 1px #fff;">
            @if(Session::has('warning'))
                <p class="alert alert-warning text-dark" style="text-align: center;">{{ Session::get('warning') }} &nbsp; <i class="fas fa-warning"></i></p>
            @endif
                @if(Session::has('Success'))
                    <p class="alert alert-success text-dark" style="text-align: center;">{{ Session::get('Success') }} &nbsp; <i class="fas fa-check"></i></p>
                @endif
        </div>

       <form method="post" action="/site-setting-update" style="margin: 50px auto;background-color: #fff; padding: 30px;border-radius: 8px;" class="col-12">
           {{ csrf_field() }}
           <div class="form-group row">
               <input type="hidden" value="{{ $Settings[0]->id }}" name="set_id">
               <label for="inputPassword" style="padding-top: 5px;" class="col-sm-2 col-form-label font-weight-bold">Site Title</label>
               <div class="col-sm-8">
                   <input value="{{ $Settings[0]->value  }}" required name="value" type="text" class="form-control"  placeholder="Ex: My Site Name ....">
               </div>
               <button class="btn btn-outline-success btn-block col-sm-2 col-6 m-auto m-md-0 m-1">Update &nbsp; <i class="fas fa-pen-alt"></i></button>
           </div>
       </form>

        <form method="post" action="/site-setting-update" style="margin: 50px auto;background-color: #fff; padding: 30px;border-radius: 8px;" class="col-12">
            {{ csrf_field() }}
            <div class="form-group row">
                <input type="hidden" value="{{ $Settings[1]->id }}" name="set_id">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-10 col-form-label font-weight-bold">About Us Content</label>
                <button class="btn btn-outline-success btn-block col-sm-2 col-6 m-auto m-md-0 m-1">Update &nbsp; <i class="fas fa-pen-alt"></i></button>
                <div class="col-sm-12 mt-3">
                    <textarea id="Aboutus" required name="value" class="form-control"  placeholder="Ex: My Site Name ...."> {{ $Settings[1]->value  }} </textarea>
                </div>
            </div>
        </form>

        <form method="post" action="/site-setting-update" style="margin: 50px auto;background-color: #fff; padding: 30px;border-radius: 8px;" class="col-12">
            {{ csrf_field() }}
            <div class="form-group row">
                <input type="hidden" value="{{ $Settings[2]->id }}" name="set_id">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-2 col-form-label font-weight-bold">Phones</label>
                <div class="col-sm-8">
                    <input value="{{ $Settings[2]->value  }}" required name="value" type="text" class="form-control"  placeholder="Ex: My Site Phones 1234 , 4565 , 789 ....">
                </div>
                <button class="btn btn-outline-success btn-block col-sm-2 col-6 m-auto m-md-0 m-1">Update &nbsp; <i class="fas fa-pen-alt"></i></button>
                <div class="help-disck col-12"> *Tip: phones should be seprates by ( , ) EX: 123 , 020 </div>
            </div>
        </form>

        <form method="post" action="/site-setting-update" style="margin: 50px auto;background-color: #fff; padding: 30px;border-radius: 8px;" class="col-12">
            {{ csrf_field() }}
            <div class="form-group row">
                <input type="hidden" value="{{ $Settings[3]->id }}" name="set_id">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-10 col-form-label font-weight-bold">Addresses</label>
                <button class="btn btn-outline-success btn-block col-sm-2 col-6 m-auto m-md-0 m-1">Update &nbsp; <i class="fas fa-pen-alt"></i></button>
                <div class="col-sm-12 mt-3">
                    <textarea style="resize: none;" required name="value" type="text" class="form-control" rows="4"  placeholder="Ex: Maadi - Cairo - Egypt ....">{{  $Settings[3]->value }}</textarea>
                </div>
                <div class="help-disck col-12"> *Tip: Addresses should be seprates by ( , ) EX: address1 , address2 </div>
            </div>
        </form>

        <form method="post" action="/site-setting-update" style="margin: 50px auto;background-color: #fff; padding: 30px;border-radius: 8px;" class="col-12">
            {{ csrf_field() }}
            <div class="form-group row">
                <input type="hidden" value="{{ $Settings[4]->id }}" name="set_id">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-2 col-form-label font-weight-bold">Theme Color</label>
                <div class="col-sm-8">
                    <input value="{{ $Settings[4]->value  }}" required name="value" type="color" class="form-control" >
                </div>
                <button class="btn btn-outline-success btn-block col-sm-2 col-6 m-auto m-md-0 m-1">Update &nbsp; <i class="fas fa-pen-alt"></i></button>
            </div>
        </form>

        <form method="post" action="/site-setting-update-logo" enctype="multipart/form-data" style="margin: 50px auto;background-color: #fff; padding: 30px;border-radius: 8px;" class="col-12">
            {{ csrf_field() }}
            <div class="form-group row">
                <input type="hidden" value="{{ $Settings[5]->id }}" name="set_id">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-2 col-form-label font-weight-bold">Website Logo</label>
                <div class="col-sm-8">
                    <input value="{{ $Settings[5]->value  }}" required name="image" type="file" class="form-control" >
                </div>
                <button class="btn btn-outline-success btn-block col-sm-2 col-6 m-auto m-md-0 m-1">Update &nbsp; <i class="fas fa-pen-alt"></i></button>
                <img src="{{ $Settings[5]->value  }}" class="img-thumbnail mt-4" style="width:150px; height:150px; object-fit:contain;">
            </div>
        </form>

    </div>
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script>
        setTimeout(function () {
            $('#SessionMessage').fadeOut('fast');
        }, 2000); // <-- time in milliseconds

        CKEDITOR.config.defaultLanguage = 'en';
        CKEDITOR.replace( 'Aboutus' );

    </script>
@endsection
