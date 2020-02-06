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
            <p class="text-center text-dark bg-light rounded p-2"> <i class="fab fa-product-hunt"></i> Product Information</p>
            {{ csrf_field() }}
            <div class="form-group row">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Product Name </label>
                <div class="col-sm-9">
                    <input required type="text" class="form-control"  placeholder="Ex: P Name ...." value="{{ $p->name }}" disabled>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Brand </label>
                <div class="col-sm-9">
                    <input required type="text" class="form-control" value="{{ $p->brand }}" placeholder="Ex: Kia , BMW ...." disabled>
                </div>
            </div>
            <div class="form-group row mb-3">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Price </label>
                <div class="col-sm-9">
                    <input required type="text" class="form-control" value="{{ $p->price }}" placeholder="Ex: 3600 $ ...." disabled>
                </div>
            </div>
            <div class="form-group row mb-5">

                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Status </label>
                <div class="col-sm-9">
                    @if($p->status == 0)
                        <label class="col-12 disabled">Locked <i class="fas fa-lock"></i></label>
                    @else
                        <label class="col-12 disabled">Un-Locked <i class="fas fa-lock-open"></i></label>
                    @endif
                </div>

            </div>
            <div class="form-group row mb-5">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Description </label>
                <div class="col-sm-9">
                    <textarea rows="5"  name="city" type="text" class="form-control" disabled  placeholder="Description">{{ $p->description }}</textarea>
                </div>
            </div>

            <p class="bg-light col-10 m-auto p-2 text-secondary text-center font-weight-bolder mb-5">Product Photos &nbsp; <i class="far fa-images"></i></p>

                @if(isset($p->images))
                    <div class="owl-carousel mt-5">
                        @foreach($p->images as $image)
                            <div>
                                <img class="img-thumbnail" src="{{ 'http://www.drivixcorp.com/api/storage/' .  $image->image . '/ProductsImgs' }}" width="100%" height="100px">
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


