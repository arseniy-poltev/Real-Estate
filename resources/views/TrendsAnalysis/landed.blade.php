@extends('layouts.main')

@section('styles')
    <style>
        .discrict-number {
            font-weight: bold !important;
        }

        .search_result_item p {
            text-align: center
        }

        .subscribe-btn {
            height: 28px;
            padding: 0px 16px;
            background-image: none;
            color: #fff;
            text-shadow: none;
            font-size: 16px;
            border: none;
        }

        .subscribe-btn i {
            line-height: 28px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('plugin/select2/css/select2.css') }}">
@endsection

@section('page_title', 'LANDED RESIDENTIAL')
@section('contents')
    <!-- Title, Breadcrumb Start-->
    <div class="breadcrumb-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                    <h2 class="title">LANDED RESIDENTIAL</h2>
                </div>
                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                    <div class="breadcrumbs pull-right">
                        <ul>
                            <li>You are here:</li>
                            <li><a href="index.html">Residential and Analysis</a></li>
                            {{-- <li><a href="#">Pages</a></li> --}}
                            <li>LANDED RESIDENTIAL</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Title, Breadcrumb End-->
    <!-- Main Content start-->
    <div class="content">
        <div class="container">
            <div class="row">
                <h4 class="text-center">ENTER PROJECT NAME OR STREET NAME (LANDED RESIDENTIAL)</h4>
                <div class="input-group col-md-6 m-t-40 m-b-20"  style="margin: auto">
                    <select id="search_project" type="text" value="Project Name or Street Name"
                            class="select2 search-input form-control">
                    </select>
                    <span class="input-group-btn">
                                <button type="button" class="subscribe-btn btn btn-sm" id="bindSearch"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="posts-block col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <article>
                        <h3 class="title">&nbsp</h3>
                        <div class="post-content">
                            <h4 class="text-center">ALTERNATIVELY, SEE WHAT'S TRENDING</h4>
                            <div class="row search_result_item p-40">

                                @foreach($district as $item)
                                    @php
                                        $projects = \App\Models\LandedTransaction::where('Postal District', '>=', 1)->select('Project Name')->groupBy('Project Name')->inRandomOrder()->take(10)->get();
                                    @endphp
                                    <div class="col-md-4 m-b-40">
                                        <h4 class="text-center discrict-number">Districts {{ $item['Postal District'] }}</h4>
                                        @foreach($projects as $project)
                                            <p><a href="{{ asset('/trends-and-analysis/landed/report?p=' . $project['Project Name'] ) }}">{{ $project['Project Name'] }}</a></p>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </article>
                </div>
                <!-- Left Section End -->
            </div>
        </div>
    </div>
    <!-- Main Content end-->

@endsection

@section('scripts')
    <script src="{{ asset('plugin/select2/js/select2.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });

        $('#search_project').select2({
            ajax: {

                // ajax search url
                url: "{{ url('trends-and-analysis/landed/searchData') }}",
                dataType: 'json',
                type:"post",
                delay: 250,   //delay  time of ajax request submit
                data: function (params) {
                    return {
                        //search keyword written in the input box
                        q: params.term || "",
                        page: params.page || 1
                    };
                },
                cache: true
            },
            placeholder: 'Project Name or Street Name',

            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 1,                              // minium number of the text of search box.
            templateResult: formatRepotn,                        // display type of the selectbox.  you can customize it.
            templateSelection: formatRepoSelectiontn              // when you select the item of the selectbox
        });

        function formatRepotn(repo) {
            //if data is loading, display loading msg
            if (repo.loading) {
                return repo.text;
            } else {

                // after loading data display list item
                return "<span style='font-weight: bold'>Project Name: </span>" +repo['Project Name'] + " |  <span style='font-weight: bold'>Address: </span>" + repo['Address'].split('#')[0];
            }
        }

        function formatRepoSelectiontn(repo) {

            if (repo.id) {
                return  "<span style='font-weight: bold'>Project Name: </span>" +repo['Project Name'] + " |  <span style='font-weight: bold'>Address: </span>" + repo['Address'].split('#')[0] || (repo.text);
            }
            else {
                return repo.text;
            }
        }


        $('#bindSearch').on('click', function () {
            if($('#search_project').select2('data').length) {
                var project_name = $('#search_project').select2('data')[0]['Project Name'];
                window.location.href = "{{ url('/trends-and-analysis/landed/report?p=') }}" + project_name;
            }
        })

    </script>
@endsection
