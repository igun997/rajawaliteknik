@extends('adminlte::page')

@section('title', $title)

@section('content_header')

@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Analisis Penjualan</div>
                </div>
                <div class="card-body">
                    <canvas id="penjualan" style="width: auto;height: 300px"></canvas>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section("js")
    @include("msg")
    <script>
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }
        $(function () {
            var ctx = document.getElementById("penjualan").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [{!! $date !!}],
                    datasets: [{
                        label: 'Statistik Penjualan Mingguan',
                        data: [{!! $data !!}],
                        backgroundColor: "rgba(255,99,132,0)",
                        borderColor: "rgba(255,99,132,1)",
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true,
                                callback: function (value) {
                                    return "Rp. "+formatNumber(value);
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, chart){
                                var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                                return datasetLabel + ': Rp. ' + formatNumber(tooltipItem.yLabel);
                            }
                        }
                    }
                }
            });
        });
    </script>
@stop

