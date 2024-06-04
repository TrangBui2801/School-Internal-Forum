<?php

use dosamigos\chartjs\ChartJs;

$this->title = 'Admin';
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>
<link rel="stylesheet" href="../dist/css/homepage.css">

<script src="https://www.gstatic.com/charts/loader.js"></script>

<div class="container-fluid">
    <section class="statistics mt-4">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card mb-4 mb-lg-0 p-3">
                    <div class="card-body ms-3">
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0"><i class="fa fa-envelope" aria-hidden="true"></i> 1,245</h3> <span class="d-block ms-2">Emails</span>
                        </div>
                        <p class="fs-normal mb-0">Lorem ipsum dolor sit amet</p>
                    </div>
                    <a href="#" class="btn btn-primary">Go to</a>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card mb-4 mb-lg-0 p-3">
                    <div class="card-body ms-3">
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0"><i class="fa fa-envelope" aria-hidden="true"></i> 1,245</h3> <span class="d-block ms-2">Emails</span>
                        </div>
                        <p class="fs-normal mb-0">Lorem ipsum dolor sit amet</p>
                    </div>
                    <a href="#" class="btn btn-primary">Go to</a>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card mb-4 mb-lg-0 p-3">
                    <div class="card-body ms-3">
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0"><i class="fa fa-envelope" aria-hidden="true"></i> 1,245</h3> <span class="d-block ms-2">Emails</span>
                        </div>
                        <p class="fs-normal mb-0">Lorem ipsum dolor sit amet</p>
                    </div>
                    <a href="#" class="btn btn-primary">Go to</a>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col col-lg-6 col-md-6 col-sm-12 margin-5 border-radius-5">
            <div class="card margin-5 border-radius-5">
                <div id="myChart"></div>
            </div>
        </div>
        <div class="col col-lg-6 col-md-6 col-sm-12 margin-5 border-radius-5">
            <div class="card margin-5 border-radius-5">
                <div id="myChart2"></div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col col-lg-6 col-md-6 col-sm-12 margin-5 border-radius-5">
            <div class="card margin-5 border-radius-5">
                <div id="myChart3"></div>
            </div>
        </div>
        <div class="col col-lg-6 col-md-6 col-sm-12 margin-5 border-radius-5">
            <div class="card margin-5 border-radius-5">
                <div id="myChart4"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col col-lg-6 col-md-6 col-sm-12 margin-5 border-radius-5">
            <div class="card margin-5 border-radius-5">
                <div id="myChart5"></div>
            </div>
        </div>
        <div class="col col-lg-6 col-md-6 col-sm-12 margin-5 border-radius-5">
            <div class="card margin-5 border-radius-5">
                <div id="myChart6"></div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    google.charts.load('current', {
        packages: ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        // Set Data
        var data = google.visualization.arrayToDataTable([
            ['Price', 'Size'],
            [50, 7],
            [60, 8],
            [70, 8],
            [80, 9],
            [90, 9],
            [100, 9],
            [110, 10],
            [120, 11],
            [130, 14],
            [140, 14],
            [150, 15]
        ]);
        // Set Options
        var options = {
            title: 'House Prices vs. Size',
            hAxis: {
                title: 'Square Meters'
            },
            vAxis: {
                title: 'Price in Millions'
            },
            legend: 'none'
        };
        // Draw
        var chart = new google.visualization.LineChart(document.getElementById('myChart'));
        chart.draw(data, options);

        var data2 = google.visualization.arrayToDataTable([
            ['Contry', 'Mhl'],
            ['Italy', 55],
            ['France', 49],
            ['Spain', 44],
            ['USA', 24],
            ['Argentina', 15]
        ]);

        var options = {
            title: 'World Wide Wine Production'
        };

        var chart2 = new google.visualization.BarChart(document.getElementById('myChart2'));
        chart2.draw(data2, options);

        var data3 = google.visualization.arrayToDataTable([
            ['Contry', 'Mhl'],
            ['Italy', 54.8],
            ['France', 48.6],
            ['Spain', 44.4],
            ['USA', 23.9],
            ['Argentina', 14.5]
        ]);

        var options3 = {
            title: 'World Wide Wine Production'
        };

        var chart3 = new google.visualization.PieChart(document.getElementById('myChart3'));
        chart3.draw(data3, options3);

        var data4 = google.visualization.arrayToDataTable([
            ['Contry', 'Mhl'],
            ['Italy', 54.8],
            ['France', 48.6],
            ['Spain', 44.4],
            ['USA', 23.9],
            ['Argentina', 14.5]
        ]);

        var options4 = {
            title: 'World Wide Wine Production',
            is3D: true
        };

        var chart4 = new google.visualization.PieChart(document.getElementById('myChart4'));
        chart4.draw(data4, options4);
    }
    google.charts.setOnLoadCallback(drawChart);
</script>