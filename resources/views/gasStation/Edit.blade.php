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
        <form  action="/updateGasStation" class="col-12" method="post" style="margin: 50px auto;background-color: #fff; padding: 30px;border-radius: 8px;" enctype="multipart/form-data">
            <input type="hidden" name="id" value="{{$gasStation->id}}">
            <p class="text-center text-dark bg-light rounded p-2"> <i class="fas fa-fw fa-gas-pump pr-3"></i> Add Gas Station</p>
            {{ csrf_field() }}
            <div class="form-group row">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Gas Station Name </label>
                <div class="col-sm-9">
                    <input required name="name" type="text" class="form-control"  placeholder="Ex: Total ...." value="{{$gasStation->name}}">
                </div>
            </div>
            <div class="form-group row mb-5">
                <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">City </label>
                <div class="col-sm-9">
                    <input required name="city" type="text" class="form-control" value="{{ $gasStation->city }}"  placeholder="Ex: Cairo , Giza ....">
                </div>
            </div>

            <div class="col-12 row p-0 m-0">
                <div class="col-md-6 m-0 p-0">
                   <div class="form-group row">
                       <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Tier Repare </label>
                       <div class="col-sm-9">
                           <div class="custom-control custom-switch" style="margin-left: 11px;">
                               <input name="tier" type="checkbox" {{ ($gasStation->tier_repare == 1)? 'checked' : '' }} class="custom-control-input" id="customSwitch1" >
                               <label class="custom-control-label" for="customSwitch1"></label>
                           </div>
                       </div>
                   </div>

                   <div class="form-group row">
                       <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">blowing Air </label>
                       <div class="col-sm-9">
                           <div class="custom-control custom-switch" style="margin-left: 11px;">
                               <input name="blowair" type="checkbox"  {{ ($gasStation->blowing_air == 1)? 'checked' : '' }}   class="custom-control-input" id="customSwitch2" >
                               <label class="custom-control-label" for="customSwitch2"></label>
                           </div>
                       </div>
                   </div>

                   <div class="form-group row">
                       <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">petrol 80 </label>
                       <div class="col-sm-9">
                           <div class="custom-control custom-switch" style="margin-left: 11px;">
                               <input name="p80" type="checkbox" {{ ($gasStation->petrol_80 == 1)? 'checked' : '' }} class="custom-control-input" id="customSwitch3" >
                               <label class="custom-control-label" for="customSwitch3"></label>
                           </div>
                       </div>
                   </div>
               </div>
                <div class="col-md-6 m-0 p-0">
                    <div class="form-group row">
                        <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">petrol 92</label>
                        <div class="col-sm-9">
                            <div class="custom-control custom-switch" style="margin-left: 11px;">
                                <input name="p92" {{ ($gasStation->petrol_92 == 1)? 'checked' : '' }} type="checkbox" class="custom-control-input" id="customSwitch11" >
                                <label class="custom-control-label" for="customSwitch11"></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">petrol 95 </label>
                        <div class="col-sm-9">
                            <div class="custom-control custom-switch" style="margin-left: 11px;">
                                <input name="p95" {{ ($gasStation->petrol_95 == 1)? 'checked' : '' }} type="checkbox" class="custom-control-input" id="customSwitch21" >
                                <label class="custom-control-label" for="customSwitch21"></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Align wheel</label>
                        <div class="col-sm-9">
                            <div class="custom-control custom-switch" style="margin-left: 11px;">
                                <input name="awheel" {{ ($gasStation->align_wheel == 1)? 'checked' : '' }} type="checkbox" class="custom-control-input" id="customSwitch31" >
                                <label class="custom-control-label" for="customSwitch31"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 row p-0 m-0 ">
                <div class="col-md-6 m-0 p-0">
                    <div class="form-group row">
                        <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Solar </label>
                        <div class="col-sm-9">
                            <div class="custom-control custom-switch" style="margin-left: 11px;">
                                <input name="solar" {{ ($gasStation->sollar == 1)? 'checked' : '' }} type="checkbox" class="custom-control-input" id="customSwitch4" >
                                <label class="custom-control-label" for="customSwitch4"></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Gas</label>
                        <div class="col-sm-9">
                            <div class="custom-control custom-switch" style="margin-left: 11px;">
                                <input name="gas" {{ ($gasStation->gas == 1)? 'checked' : '' }} type="checkbox" class="custom-control-input" id="customSwitch5" >
                                <label class="custom-control-label" for="customSwitch5"></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">car washing</label>
                        <div class="col-sm-9">
                            <div class="custom-control custom-switch" style="margin-left: 11px;">
                                <input name="carwashing" {{ ($gasStation->car_washing == 1)? 'checked' : '' }} type="checkbox" class="custom-control-input" id="customSwitch6" >
                                <label class="custom-control-label" for="customSwitch6"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 m-0 p-0">
                    <div class="form-group row">
                        <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">blowing Nitro</label>
                        <div class="col-sm-9">
                            <div class="custom-control custom-switch" style="margin-left: 11px;">
                                <input name="pnitro" {{ ($gasStation->blowing_nitro == 1)? 'checked' : '' }} type="checkbox" class="custom-control-input" id="customSwitch7" >
                                <label class="custom-control-label" for="customSwitch7"></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">fix suspension</label>
                        <div class="col-sm-9">
                            <div class="custom-control custom-switch" style="margin-left: 11px;">
                                <input {{ ($gasStation->fix_suspension == 1)? 'checked' : '' }} name="fixsus" type="checkbox" class="custom-control-input" id="customSwitch8" >
                                <label class="custom-control-label" for="customSwitch8"></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" style="padding-top: 5px;" class="col-sm-3 col-form-label">Oil Change</label>
                        <div class="col-sm-9">
                            <div class="custom-control custom-switch" style="margin-left: 11px;">
                                <input {{ ($gasStation->oil_change == 1)? 'checked' : '' }} name="ochange" type="checkbox" class="custom-control-input" id="customSwitch9" >
                                <label class="custom-control-label" for="customSwitch9"></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row" style="cursor: pointer; margin: auto;">
                <p class="p-2 bg-light text-center col-12"> Set Location on Map <i class="pl-4 fas fa-map-marker-alt fa-lg"></i> </p>
            </div>


            {{--Here is google map api --}}
            <div class="p-3 col-10 m-auto">
                <div class="form-group input-group">
                    <input type="text" id="search_location" class="form-control" placeholder="Search location">
                    <div class="input-group-btn">
                        <button class="btn btn-default get_map" type="submit">
                            Locate
                        </button>
                    </div>
                </div>
                <!-- display google map -->
                <div id="geomap" style="height: 350px; width: 100%;"></div>
                <!-- display selected location information -->
                <p class="m-0 p-0 col-12 mb-3 mt-2">
                    Address:
                    <input name="address" required  type="text" class="search_addr form-control form-control-sm" size="45" value="{{ $gasStation->address }}">
                </p>
                <div class="col-12 row m-0 p-0">
                    <p class="col-md-6 m-0 p-0 pr-2">
                        Latitude:
                        <input required type="text" name="lat" class="search_latitude form-control form-control-sm" size="30" value="{{ $gasStation->lat }}">
                    </p>
                    <p class="col-md-6 m-0 p-0 pl-2">
                        Longitude:
                        <input required type="text" name="long" class="search_longitude form-control form-control-sm" size="30" value="{{ $gasStation->long  }}">
                    </p>
                </div>

                {{--Here is google map api --}}
            </div>


            <div class="p-3 m-auto">
                <p class="p-2 bg-light text-center col-12 mt-5 mb-3"> Upload File </p>
                <div class="input-group mb-3 col-10 offset-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="inputGroupFileAddon01">Gas Station Icon</span>
                    </div>
                    <div class="custom-file">
                        <input type="file" name="image" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                        <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                    </div>
                </div>
                <div>
                    <img class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;" src="{{$gasStation->icon}}" >
                </div>
            </div>


            <button class="btn btn-block btn-outline-dark col-4 offset-4 mt-5">
               Update Gas Station
            </button>
        </form>
        {{--End Form--}}



    </div>
    <!-- /.container-fluid -->

    {{-- Start Scripts Area--}}
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAb1qML5aW84D-NJg4bnu3YPoFyNlZ387E"></script>
    <script>
        setTimeout(function() {
            $('#SessionMessage').fadeOut('1000');
        }, 2000); // <-- time in milliseconds

        /*
            * Google Map with marker
        */
        var geocoder;
        var map;
        var marker;

        $(document).ready(function () {
            //load google map
            initialize();
            var PostCodeid = '#search_location';
            $(function () {
                $(PostCodeid).autocomplete({
                    source: function (request, response) {
                        geocoder.geocode({
                            'address': request.term
                        }, function (results, status) {
                            response($.map(results, function (item) {
                                console.log('inside search');
                                console.log(item);
                                return {
                                    label: item.formatted_address,
                                    value: item.formatted_address,
                                    lat: item.geometry.location.lat(),
                                    lon: item.geometry.location.lng()
                                };
                            }));
                        });
                    },
                    select: function (event, ui) {
                        $('.search_addr').val(ui.item.value);
                        $('.search_latitude').val(ui.item.lat);
                        $('.search_longitude').val(ui.item.lon);
                        var latlng = new google.maps.LatLng(ui.item.lat, ui.item.lon);
                        marker.setPosition(latlng);
                        initialize();
                    }
                });
            });

            /*
             * Point location on google map
             */
            $('.get_map').click(function (e) {
                var address = $(PostCodeid).val();
                geocoder.geocode({'address': address}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        map.setCenter(results[0].geometry.location);
                        marker.setPosition(results[0].geometry.location);
                        console.log(results);
                        $('.search_addr').val(results[0].formatted_address);
                        $('.search_latitude').val(marker.getPosition().lat());
                        $('.search_longitude').val(marker.getPosition().lng());
                    } else {
                        alert("Geocode was not successful for the following reason: " + status);
                    }
                });
                e.preventDefault();
            });

            //Add listener to marker for reverse geocoding
            google.maps.event.addListener(marker, 'drag', function () {
                geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            console.log(results);
                            $('.search_addr').val(results[0].formatted_address);
                            $('.search_latitude').val(marker.getPosition().lat());
                            $('.search_longitude').val(marker.getPosition().lng());
                        }
                    }
                });
            });
        });

        function initialize() {
            var initialLat = $('.search_latitude').val();
            var initialLong = $('.search_longitude').val();
            initialLat = initialLat?initialLat:30.044420;
            initialLong = initialLong?initialLong:31.235712;

            var latlng = new google.maps.LatLng(initialLat, initialLong);
            var options = {
                zoom: 16,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            map = new google.maps.Map(document.getElementById("geomap"), options);

            geocoder = new google.maps.Geocoder();

            marker = new google.maps.Marker({
                map: map,
                draggable: true,
                position: latlng
            });

            google.maps.event.addListener(marker, "dragend", function () {
                var point = marker.getPosition();
                map.panTo(point);
                geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        map.setCenter(results[0].geometry.location);
                        marker.setPosition(results[0].geometry.location);
                        $('.search_addr').val(results[0].formatted_address);
                        $('.search_latitude').val(marker.getPosition().lat());
                        $('.search_longitude').val(marker.getPosition().lng());
                    }
                });
            });
        }

    </script>
    {{-- End Scripts Area--}}
@endsection


