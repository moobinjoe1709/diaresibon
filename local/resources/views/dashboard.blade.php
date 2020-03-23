@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-12 text-center">
        <h1 class="text-purple">Diaresibon</h1>
        <br>
        <h2 class="text-purple">Packing Calculation v2.0</h2>
    </div>
</div>
@endsection

@section('js')
<script>
            $(document).ready(function() {
                $('#customers').DataTable( {
                    "scrollX": false
                } );
            } );

            $('.countMe').each(function() {
              var $this = $(this),
                  countTo = $this.attr('data-count');

              $({ countNum: $this.text()}).animate({
                countNum: countTo
              },

              {

                duration: 2000,
                easing:'linear',
                step: function() {
                  $this.text(Math.floor(this.countNum));
                },
                complete: function() {
                  $this.text(this.countNum);
                  //alert('finished');
                }
              });  
            });           
        </script>

        <!-- JQV Map -->
        <script src="{{ asset('assets/js/vendor/jqvmap/jquery.vmap.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/jqvmap/maps/jquery.vmap.world.js') }}"></script>
        <script>
            jQuery('#vmap').vectorMap(
                {
                    map: 'world_en',
                    backgroundColor: '#ffffff',
                    borderColor: 'white',
                    borderOpacity: 0.25,
                    borderWidth: 1,
                    color: '#797979',
                    enableZoom: false,
                    hoverColor: '#a8a8a8',
                    hoverOpacity: null,
                    normalizeFunction: 'linear',
                    scaleColors: ['#0e7bd9', '#0a69bc'], 
                    selectedColor: '#a7a7a7',
                    selectedRegions: null,
                    showTooltip: true
            });
        </script>

        <!-- Dynamic Update Chart -->
        <script type="text/javascript">
            $(document).ready(function () {

                Highcharts.setOptions({
                    global: {
                        useUTC: false
                    },

                    chart: {
                        backgroundColor: '#ffffff'
                    },
                    colors: ['#b5c900'],
                    title: {
                        style: {
                            color: 'silver'
                        }
                    },
                    tooltip: {
                        style: {
                            color: 'black'
                        }
                    }
                });

                Highcharts.chart('dash1-server-update', {
                    chart: {
                        type: 'spline',
                        animation: Highcharts.svg, // don't animate in old IE
                        marginRight: 10,
                        events: {
                            load: function () {

                                // set up the updating of the chart each second
                                var series = this.series[0];
                                setInterval(function () {
                                    var x = (new Date()).getTime(), // current time
                                        y = Math.random();
                                    series.addPoint([x, y], true, true);
                                }, 1000);
                            }
                        }
                    },
                    title: {
                        text: false
                    },
                    xAxis: {
                        type: 'datetime',
                        tickPixelInterval: 150
                    },
                    yAxis: {
                        title: {
                            text: false
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },
                    tooltip: {
                        formatter: function () {
                            return '<b>' + this.series.name + '</b><br/>' +
                                Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                                Highcharts.numberFormat(this.y, 2);
                        }
                    },
                    legend: {
                        enabled: false
                    },
                    exporting: {
                        enabled: false
                    },
                    series: [{
                        name: 'Visitors',
                        data: (function () {
                            // generate an array of random data
                            var data = [],
                                time = (new Date()).getTime(),
                                i;

                            for (i = -19; i <= 0; i += 1) {
                                data.push({
                                    x: time + i * 1000,
                                    y: Math.random()
                                });
                            }
                            return data;
                        }())
                    }]
                });
            });
        </script>

@endsection