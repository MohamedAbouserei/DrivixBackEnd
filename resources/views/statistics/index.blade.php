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
        <div  class="col-12" style="margin: 50px auto;background-color: #fff; padding: 30px;border-radius: 8px;" >
            <p class="text-center text-dark bg-light rounded p-2"> <i class="fas fa-chart-line"></i> Generate Report</p>
            <select class="form-control selectTypeFile" onchange="getFileType(this)">
                <option disabled selected>Select type of report</option>
                <option value="sps">Spares Part Shop </option>
                <option value="ws">Workshop</option>
                <option value="o">Orders</option>
            </select>

            <div class="mt-2 mb-2"></div>
            <select class="form-control col-12 selectTypeGraph mt-3 d-none" onchange="getTypeofGraph(this)">
                <option disabled selected>Select Representation Type</option>
                <option value="bar">Bar Chart</option>
                <option value="pie">Pie Chart</option>
                <option value="line">Line Chart</option>
            </select>
            <div class="mt-2 mb-2"></div>

            <div class="form-group col-12 row m-0 p-0">
                <div class="col-6 m-0 p-0 pr-5 fromDate d-none">
                    <div class="row m-0 p-0">
                        <label class="col-4 p-0 m-0 pt-2"> From Date</label>
                        <input type="date" onchange="from(this.value)" name="dateFfrom" class="col-8 form-control">
                    </div>
                </div>
                <div class="col-6 m-0 p-0 pl-5 toDate d-none">
                    <div class="row m-0 p-0">
                        <label class="col-4 p-0 m-0 pt-2"> To Date</label>
                        <input type="date" onchange="to(this.value)" name="dateTo" class="col-8 m-0 p-0 form-control">
                    </div>
                </div>
            </div>

            <div class="form-group col-12 row m-0 p-0 mt-5">
                <span class="btn btn-outline-success d-none generateReport col-8 m-auto m-block" onclick="generateReport()"> Generate Report </span>
            </div>

        </div>
        {{--End Form--}}

        {{--start Canvas--}}
        <div  class="col-12" style="margin: 50px auto;background-color: #fff; padding: 30px;border-radius: 8px;" >
            <button class="float-right btn btn-outline-dark" onclick="PrintElem()">Print Data Set</button>
            <canvas id="myChart"></canvas>
        </div>
        {{--End Canvas--}}

    </div>
    <script>
        fileType = '';
        graph = '';
        from_date = '';
        to_date = '';
        function getFileType (file) {
            fileType = file.options[file.selectedIndex].value;
            $('.selectTypeGraph').removeClass('d-none');
        }
        function getTypeofGraph (g) {
            graph = g.options[g.selectedIndex].value;
            $('.fromDate').removeClass('d-none');
        }
        function from(from) {
            from_date = from;
            $('.toDate').removeClass('d-none');

        }
        function to(to) {
            to_date = to;
            $('.generateReport').removeClass('d-none');
        }

        function generateReport () {
            $.ajax({
                url: '/getStatAjax',
                type: "get",
                data: {
                    "_token": "<?php echo (csrf_token()); ?>",
                    "type": fileType ,
                    "s_date": from_date ,
                    "e_date": to_date
                },
                success: function (response) {
                    // Canvas
                    DataSet_Label = '';
                    if (fileType === 'sps' ) { DataSet_Label = 'Spares Part Shop Data Set'; }
                    if (fileType === 'o' ) { DataSet_Label = 'Winch Orders Data Set'; }
                    if (fileType === 'ws' ) { DataSet_Label = 'Work Shop Data Set'; }

                    graph_type = '';
                    if ( graph === 'line') { graph_type = 'line'; }
                    if ( graph === 'bar' ) {graph_type = 'bar';}
                    if ( graph === 'pie' ) { graph_type = 'pie';}

                    // colors
                    var coloR = [];
                    var dynamicColors = function() {
                        var r = Math.floor(Math.random() * 255);
                        var g = Math.floor(Math.random() * 255);
                        var b = Math.floor(Math.random() * 255);
                        return "rgb(" + r + "," + g + "," + b + ")";
                    };

                    for (var i in response.dates) {
                        coloR.push(dynamicColors());
                    }

                    var ctx = document.getElementById('myChart').getContext('2d');
                    // chart type
                    var chart = new Chart(ctx, {
                            // The type of chart we want to create
                            type: graph_type,

                            // The data for our dataset
                            data: {
                                labels: response.dates,
                                datasets: [{
                                    label: DataSet_Label ,
                                    backgroundColor: coloR,
                                    borderColor: 'rgb(255, 99, 132)',
                                    data: response.stat
                                }]
                            },

                            // Configuration options go here
                            options: {}
                        });

                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

        function PrintElem()
        {
            var dataUrl = document.getElementById('myChart').toDataURL(); //attempt to save base64 string to server using this var
            var windowContent = '<!DOCTYPE html>';
            windowContent += '<html>'
            windowContent += '<head><title>Print Data Set (Drivix Dashboard)</title></head>';
            windowContent += '<body>'
            windowContent += '<img src="' + dataUrl + '">';
            windowContent += '</body>';
            windowContent += '</html>';
            var printWin = window.open('','','width=400,height=600');
            printWin.document.open();
            printWin.document.write(windowContent);
            printWin.document.close();
            printWin.focus();
            printWin.print();
            printWin.close();
        }
    </script>
@endsection


