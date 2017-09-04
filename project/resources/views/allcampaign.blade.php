@extends('includes.master')

@section('content')


    <section style="background: url({{url('/')}}/assets/images/{{$settings[0]->background}}) no-repeat center center; background-size: cover;">
        <div class="row" style="background-color:rgba(0,0,0,0.7);">

            <div style="margin: 3% 0px 3% 0px;">
                <div class="text-center" style="color: #FFF;padding: 20px;">
                    <h1>Available Campaigns</h1>
                </div>
            </div>

        </div>


    </section>


    <div id="wrapper" class="go-section">
        <div class="row">
            <div class="container">
                <div id="allcamps">
                    @foreach($campaigns as $campaign)
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="package-list wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
                                <a href="{{url('/')}}/campaign/{{$campaign->id}}">
                                    <div class="package-thumb">
                                        <img width="800" height="570" src="{{url('/assets/images/campaign')}}/{{$campaign->feature_image}}" class="" alt="1">
                                    </div>
                                    <div class="package-info">
                                        <h3>{{$campaign->title}}</h3>
                                        <div class="row">
                                <span class="pull-left">
                                    @if (((strtotime($campaign->end_date)-time())/86400) < 0)
                                        <b>{{0}}</b>
                                    @else
                                        <b>{{ceil((strtotime($campaign->end_date)-time())/86400)}}</b>
                                    @endif
                                     Days Left
                                </span>
                                            <span class="pull-right">
                                    <b>${{\App\Donation::getFund($campaign->id)}}</b>
                                     Funded
                                </span>
                                        </div>

                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" aria-valuenow="{{round(\App\Donation::getPercent($campaign->id))}}"
                                                 aria-valuemin="0" aria-valuemax="100" style="width:{{round(\App\Donation::getPercent($campaign->id))}}%">
                                                {{round(\App\Donation::getPercent($campaign->id))}}%
                                            </div>
                                        </div>

                                        <p>{{ substr(strip_tags($campaign->description), 0, 120) }}...</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                    <div class='col-md-12 margintop'></div>
                </div>

            </div>
        </div>
    </div>

@stop

@section('footer')

@stop