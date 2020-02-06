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
        <form action="/StoreMail"  class="col-12" method="post" style="margin: 50px auto;background-color: #fff; padding: 30px;border-radius: 8px;" >
            <p class="text-center text-dark bg-light rounded p-2"> <i class="fas fa-users-cog pr-3"></i> Add New Mail</p>
            {{ csrf_field() }}
            <div class="form-group row">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">To E-Mail:  </label>
                <div class="col-sm-9">
                    <select name="email_to" class="form-control email_to">
                        @foreach($sup_ad_users as $user)
                            <option value="{{ $user->id }}"> {{ $user->email }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Title </label>
                <div class="col-sm-9">
                    <input required name="title"  type="text"  class="form-control">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Message </label>
                <div class="col-sm-9">
                    <textarea required name="message" rows="6" class="form-control"></textarea>
                </div>
            </div>


            <button type="submit" class="btn btn-block btn-outline-dark col-4 offset-4 mt-5">
                Send this Mail   <i class="fas fa-paper-plane"></i>
            </button>
        </form>
        {{--End Form--}}
    </div>
    <script>
        $(document).ready(function() {
            $('.email_to').select2();
        });
    </script>
@endsection


