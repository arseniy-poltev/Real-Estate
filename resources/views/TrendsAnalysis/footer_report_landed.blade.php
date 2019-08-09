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

        $('.sales-datatable-component').dataTable({
            "searching": false,
            responsive: true,
            "order": [[0, "desc"]],
        });

        $('.rental-container-datatable-component').dataTable({
            "searching": false,
            responsive: true,
            "order": [[0, "desc"]],
        });

        $('.rental-yield-datatable-component').dataTable({
            "searching": false,
            responsive: true,
            "order": [[0, "desc"]],
        });

        $('.unit-size-datatable-component').dataTable({
            "searching": false,
            responsive: true,
        });

        $('.profitable-datatable-component').dataTable({
            "searching": false,
            responsive: true,
            "order": [[0, "desc"]],
        });

    });


    $('#refresh_report').on('click', function () {
        var settig_value = {};
        $.each($('.report_setting_form').serializeArray(), function () {
            settig_value[this.name] = this.value;
        });

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
</script>


@if(\App\Service\GlobalService::checkUserPermission())
<script>
    function initMap() {
        var myLatLng = {
            lat: parseFloat("{!! $project_detail['Latitude'] !!}"),
            lng: parseFloat("{!! $project_detail['Longitude'] !!}")
        }
        var map = new google.maps.Map(document.getElementById('maps'), {
            zoom: 18,
            center: myLatLng
        });
        var geocoder = new google.maps.Geocoder();

        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
        });


        // nearby_map
        var nearby_map = new google.maps.Map(document.getElementById('nearby_map'), {
            zoom: 13,
            center: myLatLng
        });

            @php
                $marker_index = 0;
            @endphp
        var locations = [
                    @foreach($nearby_items as $item)
                    @if($item['Project Name'] != $project['Project Name'])
                {
                    position: {
                        lat: parseFloat("{!! $item['Latitude'] !!}"),
                        lng: parseFloat("{!! $item['Longitude'] !!}"),
                    },
                    marker: "{!! $item['marker'] !!}"
                },
                @endif
                @endforeach
            ];

        locations.push({position: myLatLng, marker: "img/marker/marker0.png"});
        locations.forEach(function (location) {
            var nearby_marker = new google.maps.Marker({
                position: location.position,
                icon: '/' + location.marker,
                map: nearby_map
            });
        })
    }
</script>
{{--<script src="http://maps.google.com/maps/api/js?sensor=false&key=AIzaSyABrKRwDHO6gVhgjSBkP7Z2s98ZgHjTDGM"></script>--}}
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCAY14CXuA_8UTgq6ipb-4Rm4C0jeCiHpY&callback=initMap"
        type="text/javascript"></script>

<script>

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
    am4core.ready(function () {

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
    am4core.ready(function () {

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


    @php
        $story_list = $project_list;
    @endphp
    /* Historical Transaction Price */
    am4core.ready(function() {

        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        // Create chart instance
        var chart = am4core.create("historical_transaction_chart_scatter", am4charts.XYChart);

        @php
            $story_list = $story_list->sortBy('Sale Date');
        @endphp
        // Add data
        chart.data = [
                @foreach($story_list as $item)
            {
                "date": '{!! ($item['Sale Date']) !!}',
                "value": '{!! $item['Unit Price ($ psf)'] !!}',
            },
            @endforeach
        ];


        chart.dateFormatter.inputDateFormat = "yyyy-MM-dd";

        // Create axes
        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
        dateAxis.title.text = 'Sale Date';
        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.title.text = 'Price (S$ psf)';
        // Create series
        var series = chart.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = "value";
        series.dataFields.dateX = "date";
        series.tooltipText = "{date}: {value}";
        series.strokeWidth = 2;
        // series.minBulletDistance = 15;
        series.strokeOpacity = 0;
        series.name = "Price (S$ psf)";

        // Drop-shaped tooltips
        series.tooltip.background.cornerRadius = 20;
        series.tooltip.background.strokeOpacity = 0;
        series.tooltip.pointerOrientation = "vertical";
        series.tooltip.label.minWidth = 40;
        series.tooltip.label.minHeight = 40;
        series.tooltip.label.textAlign = "middle";
        series.tooltip.label.textValign = "middle";

        // Make bullets grow on hover
        var bullet = series.bullets.push(new am4charts.CircleBullet());
        bullet.circle.strokeWidth = 2;
        bullet.circle.radius = 3;
        bullet.circle.fill = am4core.color("#2431ff");
        bullet.stroke = am4core.color('#2431ff');

        var bullethover = bullet.states.create("hover");
        bullethover.properties.scale = 1.3;

        // Make a panning cursor
        chart.cursor = new am4charts.XYCursor();
        chart.cursor.behavior = "panXY";
        chart.cursor.xAxis = dateAxis;
        chart.cursor.snapToSeries = series;

        // Create vertical scrollbar and place it before the value axis
        chart.scrollbarY = new am4core.Scrollbar();
        chart.scrollbarY.parent = chart.leftAxesContainer;
        chart.scrollbarY.toBack();

        // Create a horizontal scrollbar with previe and place it underneath the date axis
        chart.scrollbarX = new am4charts.XYChartScrollbar();
        chart.scrollbarX.series.push(series);
        chart.scrollbarX.parent = chart.bottomAxesContainer;

        // chart.events.on("ready", function () {
        //     dateAxis.zoom({start:0.79, end:1});
        // });


        // Ploynomial Regression
    /* var regseries2 = chart.series.push(new am4charts.LineSeries());
        regseries2.dataFields.valueY = "value";
        regseries2.dataFields.dateX = "date";
        regseries2.strokeWidth = 2;
        regseries2.name = "Polynomial Regression";
        regseries2.tensionX = 0.8;
        regseries2.tensionY = 0.8;
        regseries2.stroke = am4core.color("#c00");

        var reg2 = regseries2.plugins.push(new am4plugins_regression.Regression());
        reg2.method = "polynomial";*/

        chart.legend = new am4charts.Legend();
    });

    /* Historical monthly range */
    am4core.ready(function() {

        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        // Create chart instance
        var chart = am4core.create("historical_monthly_chart_range", am4charts.XYChart);
        chart.hiddenState.properties.opacity = 0;

        @php
            $story_list = $story_list->sortBy('Sale Date');
            $story_list = $story_list->map(function ($item) {
                $month = \Carbon\Carbon::parse($item['Sale Date'])->format('Y-m');
                $item['month'] = $month;
                return $item;
            });

            $histogram_data = $story_list->groupBy('month')->values();
        @endphp

            chart.data = [
            @foreach($histogram_data as $item)
            {
                "date": '{!! ($item[0]['month']) !!}',
                "value": '{!! count($item)  !!}',
                "min": '{!! round($item->min('Unit Price ($ psf)')) !!}',
                "average": '{!! round($item->average('Unit Price ($ psf)')) !!}',
                "max": '{!! round($item->max('Unit Price ($ psf)')) !!}',
            },
            @endforeach
        ];


        chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

        var dateAxis = chart.xAxes.push(new am4charts.DateAxis());

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.tooltip.disabled = true;

        var series = chart.series.push(new am4charts.LineSeries());
        series.name = "Price (S$ psf)";
        valueAxis.title.text = 'Price (S$ psf)';
        series.dataFields.dateX = "date";
        series.dataFields.openValueY = "min";
        series.dataFields.valueY = "max";
        series.tooltipText = "{date} \n Maximum: {max} \n Average: {average} \n Minimum: {min} \n Volume: {value}";



        series.tooltip.background.cornerRadius = 20;
        series.tooltip.background.strokeOpacity = 0;
        series.tooltip.pointerOrientation = "vertical";
        series.tooltip.label.minWidth = 40;
        series.tooltip.label.minHeight = 40;
        series.tooltip.label.textAlign = "left";
        series.tooltip.label.textValign = "middle";
        // series.sequencedInterpolation = true;
        series.fillOpacity = 0.5;
        // series.defaultState.transitionDuration = 1000;
        series.tensionX = 0.8;

        var series2 = chart.series.push(new am4charts.LineSeries());
        series2.dataFields.dateX = "date";
        series2.dataFields.valueY = "min";
        // series2.sequencedInterpolation = true;
        // series2.defaultState.transitionDuration = 1500;
        // series2.stroke = chart.colors.getIndex(6);
        series2.tensionX = 0.8;

        var series_average = chart.series.push(new am4charts.LineSeries());
        series_average.dataFields.valueY = "average";
        series_average.dataFields.dateX = "date";
        series_average.stroke = am4core.color("#c00");



        /* Bar chart series */
        var barSeries = chart.series.push(new am4charts.ColumnSeries());
        barSeries.dataFields.valueY = "value";
        barSeries.dataFields.dateX = "date";
        // barSeries.name = "Visits";
        // barSeries.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";

        // Create vertical scrollbar and place it before the value axis
        chart.scrollbarY = new am4core.Scrollbar();
        chart.scrollbarY.parent = chart.leftAxesContainer;
        chart.scrollbarY.toBack();

        // Create a horizontal scrollbar with previe and place it underneath the date axis
        chart.scrollbarX = new am4charts.XYChartScrollbar();
        chart.scrollbarX.series.push(series);
        chart.scrollbarX.parent = chart.bottomAxesContainer;

        // Make a panning cursor
        chart.cursor = new am4charts.XYCursor();
        chart.cursor.behavior = "panXY";
        chart.cursor.xAxis = dateAxis;
        chart.cursor.snapToSeries = series;


        chart.cursor = new am4charts.XYCursor();
        chart.cursor.xAxis = dateAxis;
        chart.scrollbarX = new am4core.Scrollbar();

        // chart.legend = new am4charts.Legend();

    });

</script>
@else
    <script>
        function initMap() {
            var myLatLng = {
                lat: parseFloat("{!! $project_detail['Latitude'] !!}"),
                lng: parseFloat("{!! $project_detail['Longitude'] !!}")
            };
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
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCAY14CXuA_8UTgq6ipb-4Rm4C0jeCiHpY&callback=initMap"
            type="text/javascript"></script>
    <script>
@endif
