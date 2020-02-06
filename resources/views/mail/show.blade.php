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
        <form  class="col-12" method="post" style="margin: 50px auto;background-color: #fff; padding: 30px;border-radius: 8px;" >
            <input type="hidden" name="mail_id" class="mail_id" value="{{$mail->id}}">
            <p class="text-center text-dark bg-light rounded p-2"> <i class="fas fa-users-cog pr-3"></i> Show Mail</p>
            {{ csrf_field() }}
            <div class="form-group row">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">From Mail </label>
                <div class="col-sm-9">
                    <input required disabled type="text" class="form-control" value="{{( $mail->from_user->email === Auth::user()->email ) ? $mail->from_user->email. ' (From You)' : $mail->from_user->email}}">
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">From User Name </label>
                <div class="col-sm-9">
                    <input required  type="text" disabled class="form-control" value="{{ $mail->from_user->name }}">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Message Title </label>
                <div class="col-sm-9">
                    <input disabled type="text" class="form-control" value="{{ $mail->title }}"  >
                </div>
            </div>


            <div class="form-group row mb-3">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-12 mt-4 col-form-label">Message </label>
                <div class="col-sm-12">
                    <textarea rows="6" disabled class="form-control"> {{ $mail->message }} </textarea>
                </div>
            </div>


            <a href="/AddMail" class="btn btn-block btn-outline-dark col-4 offset-4 mt-5">
                Reply to this Mail   <i class="fas fa-reply pl-3"></i>
            </a>
        </form>
        {{--End Form--}}



    </div>
    <!-- /.container-fluid -->
    <script>
        //  delete Gas Station
            var id = $('.mail_id').val();
            // ajax delete data to database
            $.ajax({
                url: '/changeMailStatus/' + id,
                type: "post",
                data: {
                    "_token": "{{ csrf_token()  }}",
                    "id": id
                },
                success: function (response) {
                    console.log(response);
                },
                error: function (err) {
                    console.log(err);
                }
            });
    </script>
@endsection


