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

        <!-- Success Message -->
        <div  id="SuccessDelete" class="flash-message " style="display: none;width: 50%; margin: auto;box-shadow: 1px 1px 2px #fff , -1px -1px 1px #fff;">
            <p class="alert alert-success text-white" style="text-align: center;"> &nbsp; Gas Station Deleted Successfully <i class="fas fa-check-double"></i></p>
        </div>

        <div  id="SessionMessage" class="flash-message" style="width: 50%; margin: auto;box-shadow: 1px 1px 2px #fff , -1px -1px 1px #fff;">
            @if(Session::has('success'))
                <p class="alert alert-success text-white" style="text-align: center;">{{ Session::get('success') }} &nbsp; <i class="fas fa-check-double"></i></p>
            @endif
        </div>

        <div  id="SessionWarning" class="flash-message" style="width: 50%; margin: auto;box-shadow: 1px 1px 2px #fff , -1px -1px 1px #fff;">
            @if(Session::has('warning'))
                <p class="alert alert-warning text-dark" style="text-align: center;">{{ Session::get('warning') }} &nbsp; <i class="fas fa-warning"></i></p>
            @endif
        </div>

        <!-- Content Row -->
        <div class="table-responsive m-0 p-0 m-auto p-3">
            <table class="table col-11 m-auto p-0 table-light table-hover" id="GasStation-table">
                <thead class="thead-dark">
                <tr>
                    <th>#id</th>
                    <th>Gas Name</th>
                    <th>icon</th>
                    <th>city</th>
                    <th>created date</th>
                    <th>updated date</th>
                    <th>options</th>
                </tr>
                </thead>
            </table>
        </div>
        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-sm-center text-secondary" style="font-size: 14px;" id="exampleModalLabel">Gas Station will Delete Permanently , Are you sure ?</h5>
                        <input type="hidden" value="" id="RemoveItem">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" data-backdrop="false" onclick="DeleteItem()">Delete</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
    <script>
        $(document).ready(function () {
           // when docs ready call data table
           idCounter = 0;
            table = $('#GasStation-table').DataTable({
                dom: 'lBfrtip',
                buttons: [
                    { extend: 'copy', className: 'btn btn-default btn-outline-dark' },
                    { extend: 'csv', className: 'btn btn-default btn-outline-primary' },
                    { extend: 'excel', className: 'btn btn-default btn-outline-success' },
                    { extend: 'pdf', className: 'btn btn-default btn-outline-danger' } ,
                    { extend: 'print', className: 'btn btn-default btn-outline-info' }

                ],
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{!! route('getGasStationAjax') !!}',
                    type: "GET",
                },
                columns: [
                    {
                        data: 'id', render: function (name) {
                            idCounter++;
                            return idCounter;
                        }
                    },
                    {
                        data: 'name', render: function (name) {
                            return name.substring(0,30) + '...';
                        }
                    },
                    {
                        data: 'icon', render: function (icon) {
                          return '<img src="'+icon+'" class="img-thumbnail" height="50px" width="50px">';
                        }
                    },
                    {data: 'city', name: 'city'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'updated_at', name: 'updated_at'},
                    {
                        data: 'id', render: function (data) {

                            edit = '<a href="/editGasStation/'+ data +'" class="btn btn-sm btn-info text-white"><i class="fas fa-marker"></i> </a>';
                            remove = '<a  class="btn btn-sm btn-danger text-white" data-toggle="modal" data-target="#deleteModal" onclick="openModal('+ data +')" > <i class="far fa-trash-alt"></i> </a>';
                            return edit + '&nbsp;' + remove;
                        }
                    },
                ]
            });

        });

        // open modal to delete item
        function openModal(id) {
            $('#RemoveItem').val(id);
        }
        //  delete Gas Station
        function DeleteItem() {
            var id = $('#RemoveItem').val();
            // ajax delete data to database
            $.ajax({
                url: '/deleteGasStation',
                type: "post",
                data: {
                    "_token": "{{ csrf_token()  }}",
                    "id": id
                },
                success: function (response) {
                    if (response == 'true') {
                        $('#SuccessDelete').fadeIn(200);
                        $('#GasStation-table').DataTable().ajax.reload(null, false);
                        setTimeout(function () {
                            $('#SuccessDelete').fadeOut('fast');
                        }, 2000); // <-- time in milliseconds
                    } else {
                        alert('Drawing not deleted !!  try again later');
                    }
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }
        setTimeout(function () {
            $('#SessionMessage').fadeOut('fast');
        }, 2000); // <-- time in milliseconds
        setTimeout(function () {
            $('#SessionWarning').fadeOut('fast');
        }, 4000); // <-- time in milliseconds
    </script>

@endsection
