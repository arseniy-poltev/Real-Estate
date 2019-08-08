<script>
    function initMap() {
        var myLatLng = {lat: parseFloat("{!! $project_detail['Latitude'] !!}"), lng: parseFloat("{!! $project_detail['Longitude'] !!}")}
        var map = new google.maps.Map(document.getElementById('maps'), {
            zoom: 18,
            center: myLatLng
        });
        var geocoder = new google.maps.Geocoder();

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
        });
    }
</script>
{{--<script src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyABrKRwDHO6gVhgjSBkP7Z2s98ZgHjTDGM"></script>--}}
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCAY14CXuA_8UTgq6ipb-4Rm4C0jeCiHpY&callback=initMap" type="text/javascript"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>

<script>
    $(document).ready(function () {
        $('.datatable-component').dataTable({
            "searching": false,
            responsive: true
        });

        $('#refresh_report').on('click', function () {
            var settig_value = {};
            $.each($('.report_setting_form').serializeArray(), function () {
                settig_value[this.name] = this.value;
            });

            console.log(settig_value);
        });


        /* Set Report Configuration */
            @php
                $config_data = \Illuminate\Support\Facades\Cookie::get(\App\Service\GlobalConstant::REPORT_LANDED_CONFIG_COOKIE);
            @endphp
        var config_data = '{!! $config_data !!}';

        if (config_data) {
            $.each(JSON.parse(config_data), function (key, value) {
                $('input[name="' + key + '"]').val(value);
                $('input[name="' + key + '"]').attr('checked', value);
                $('select[name="' + key + '"]').val(value);
            });
        }
        /* End report Configuration */

        /* Buyer Profile Chart */
        am4core.ready(function () {

            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create chart instance
            var chart = am4core.create("buyer_profile_chart_div", am4charts.PieChart);
            // Set data
            var selected;
            var types = [{
                type: "Singaporean",
                percent: '{!! $profile_data['Singaporean'] !!}',
                color: chart.colors.getIndex(16),
                subs: [{
                    type: "Singaporean",
                    percent: '{!! $profile_data['Singaporean'] !!}',
                }]
            }, {
                type: "Pr",
                percent: '{!! $profile_data['Pr'] !!}',
                color: chart.colors.getIndex(5),
                subs: [{
                    type: "Pr",
                    percent: '{!! $profile_data['Pr'] !!}',
                }]
            }, {
                type: "Foreigner",
                percent: '{!! $profile_data['Foreigner (NPR)'] !!}',
                color: chart.colors.getIndex(2),
                subs: [{
                    type: "Foreigner",
                    percent: '{!! $profile_data['Foreigner (NPR)'] !!}',
                }]
            }, {
                type: "Company",
                percent: '{!! $profile_data['Company'] !!}',
                color: chart.colors.getIndex(9),
                subs: [{
                    type: "Company",
                    percent: '{!! $profile_data['Company'] !!}',
                }]
            }, {
                type: "Unknown",
                percent: '{!! $profile_data['Unknown'] !!}',
                color: chart.colors.getIndex(12),
                subs: [{
                    type: "Unknown",
                    percent: '{!! $profile_data['Unknown'] !!}',
                }]
            }];

            // Add data
            chart.data = generateChartData();

            var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "percent";
            pieSeries.dataFields.category = "type";
            pieSeries.slices.template.propertyFields.fill = "color";
            pieSeries.slices.template.propertyFields.isActive = "pulled";
            pieSeries.slices.template.strokeWidth = 0;

            function generateChartData() {
                var chartData = [];
                for (var i = 0; i < types.length; i++) {
                    if (i == selected) {
                        for (var x = 0; x < types[i].subs.length; x++) {
                            chartData.push({
                                type: types[i].subs[x].type,
                                percent: types[i].subs[x].percent,
                                color: types[i].color,
                                pulled: true
                            });
                        }
                    } else {
                        chartData.push({
                            type: types[i].type,
                            percent: types[i].percent,
                            color: types[i].color,
                            id: i
                        });
                    }
                }
                return chartData;
            }

            pieSeries.slices.template.events.on("hit", function (event) {
                if (event.target.dataItem.dataContext.id != undefined) {
                    selected = event.target.dataItem.dataContext.id;
                } else {
                    selected = undefined;
                }
                chart.data = generateChartData();
            });

        });

        /* Nearby price compare chart */
        am4core.ready(function() {

            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create chart instance
            var chart = am4core.create("nearby_price_compare_chart", am4charts.XYChart);


            // Add data
            chart.data = [{
                "project": "{!! $project['Project Name'] !!}",
                "min": "{!! round($residental_rental->min('Monthly Gross Rent($)'), 2) !!}",
                "average": "{!! round($residental_rental->average('Monthly Gross Rent($)'), 2) !!}",
                "max": "{!! round($residental_rental->max('Monthly Gross Rent($)'), 2) !!}",
            },
                    @foreach($nearby_items as $item)
                    @if($item['Project Name'] != $project['Project Name'])
                    @php
                        $nearby_projects_list = \App\Service\ResidentialService::getRentalData($item['Project Name']);
                    @endphp
                {
                    "project": "{!! $item['Project Name'] !!}",
                    "min": "{!! round($nearby_projects_list->min('Monthly Gross Rent($)'), 2) !!}",
                    "average": "{!! round($nearby_projects_list->average('Monthly Gross Rent($)'), 2) !!}",
                    "max": "{!! round($nearby_projects_list->max('Monthly Gross Rent($)'), 2) !!}",
                },
                @endif
                @endforeach
            ];

            var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "project";
            categoryAxis.renderer.grid.template.location = 0;


            var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.inside = true;
            valueAxis.renderer.labels.template.disabled = true;
            valueAxis.min = 0;

            // Create series
            function createSeries(field, name) {

                // Set up series
                var series = chart.series.push(new am4charts.ColumnSeries());
                series.name = name;
                series.dataFields.valueX = field;
                series.dataFields.categoryY = "project";
                series.sequencedInterpolation = true;

                // Make it stacked
                series.stacked = true;

                // Configure columns
                series.columns.template.width = am4core.percent(60);
                series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryY}: {valueX}";

                // Add label
                var labelBullet = series.bullets.push(new am4charts.LabelBullet());
                labelBullet.label.text = "{valueY}";
                labelBullet.locationY = 0.5;

                return series;
            }

            createSeries("min", "Min");
            createSeries("average", "Average");
            createSeries("max", "Max");

            // Legend
            chart.legend = new am4charts.Legend();

        });

        /* Nearby rental compare chart */
        am4core.ready(function() {

            // Themes begin
            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create chart instance
            var chart = am4core.create("nearby_rental_compare_chart", am4charts.XYChart);

            chart.data = [{
                "project": "{!! $project['Project Name'] !!}",
                "min": "{!! round($residental_rental->min('rental'), 2) !!}",
                "average": "{!! round($residental_rental->average('rental'), 2) !!}",
                "max": "{!! round($residental_rental->max('rental'), 2) !!}",
            },
                    @foreach($nearby_items as $item)
                    @if($item['Project Name'] != $project['Project Name'])
                    @php
                        $nearby_projects_list = \App\Service\ResidentialService::getRentalData($item['Project Name']);
                        $nearby_projects_list = $nearby_projects_list->map(function($s_item) {
                            if ($s_item['Floor Area ll']) {
                             $s_item['rental'] = $s_item['Monthly Gross Rent($)']/$s_item['Floor Area ll'];
                            } else {
                             $s_item['rental'] = null;
                            }

                             return $s_item;
                        });
                    @endphp
                {
                    "project": "{!! $item['Project Name'] !!}",
                    "min": "{!! round($nearby_projects_list->min('rental'), 2) !!}",
                    "average": "{!! round($nearby_projects_list->average('rental'), 2) !!}",
                    "max": "{!! round($nearby_projects_list->max('rental'), 2) !!}",
                },
                @endif
                @endforeach
            ];

            // Create axes
            var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "project";
            categoryAxis.renderer.grid.template.location = 0;


            var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
            valueAxis.renderer.inside = true;
            valueAxis.renderer.labels.template.disabled = true;
            valueAxis.min = 0;

            // Create series
            function createSeries(field, name) {

                // Set up series
                var series = chart.series.push(new am4charts.ColumnSeries());
                series.name = name;
                series.dataFields.valueX = field;
                series.dataFields.categoryY = "project";
                series.sequencedInterpolation = true;

                // Make it stacked
                series.stacked = true;

                // Configure columns
                series.columns.template.width = am4core.percent(60);
                series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryY}: {valueX}";

                // Add label
                var labelBullet = series.bullets.push(new am4charts.LabelBullet());
                labelBullet.label.text = "{valueY}";
                labelBullet.locationY = 0.5;

                return series;
            }

            createSeries("min", "Min");
            createSeries("average", "Average");
            createSeries("max", "Max");

            // Legend
            chart.legend = new am4charts.Legend();

        });
    })

</script>
