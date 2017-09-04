@extends('includes.master')

@section('content')

<!-- {{url('/assets/images/package')}}/{{$auction->feature_image}} -->

    <section style="background: url({{url('/')}}/assets/images/{{$settings[0]->background}}) no-repeat center center; background-size: cover;">
        <div class="row" style="background-color:rgba(0,0,0,0.7);">

            <div style="margin: 4% 0px 4% 0px;">
                <div class="text-center" style="color: #FFF;padding: 20px;">
                    <h1>{{$auction->title}}</h1>
                </div>
            </div>

        </div>
    </section>

    <div id="wrapper" class="go-section">
        <div class="row">
            <div class="container">
                <div class="col-md-8">

                    <div class="profile-section">
                        <div class="row">

                            <div id="gallery" style="display:none;">

                                <img alt="Image 1 Title" src="{{url('/assets/images/auction/'.$auction->feature_image)}}"
                                     data-image="{{url('/assets/images/auction/'.$auction->feature_image)}}"
                                     data-description="Image 1 Description">
                                @foreach($gallerys as $gallery)
                                <img alt="Image 2 Title" src="{{url('/assets/images/gallery/'.$gallery->image)}}"
                                     data-image="{{url('/assets/images/gallery/'.$gallery->image)}}"
                                     data-description="Image 2 Description">
                                @endforeach
                            </div>

                        </div>
                    </div>
              
                    <div style="margin-bottom:20px;">
                        @if(!empty($ads728x90))
                        <div class="desktop-advert">
                            @if($ads728x90->type == "banner")
                                <a class="ads" href="{{$ads728x90->redirect_url}}" target="_blank">
                                    <img class="banner-728x90" src="{{url('/')}}/assets/images/ads/{{$ads728x90->banner_file}}" alt="Advertisement">
                                </a>
                            @else
                                {!! $ads728x90->script !!}
                            @endif
                        </div>
                        @endif

                    </div>

                    <div class="information-blocks">
                        <div class="card">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#description" aria-controls="home" role="tab" data-toggle="tab">Description</a></li>
                                <li role="presentation"><a href="#bid-history" aria-controls="profile" role="tab" data-toggle="tab"> Bid History</a></li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="description">
                                    <p>{!! $auction->description !!}</p>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="bid-history">
                                    <table class="table">
                                        <thead>
                                        <tr class="success">
                                            <th>Bidder</th>
                                            <th>Bid Amount</th>
                                            <th>Date</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($bids as $bid)
                                            <tr>
                                                <td>{{$bid->bidder->name}}</td>
                                                <td>${{$bid->bid_amount}}</td>
                                                <td>{{$bid->created_at}}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">No Bid Placed Yet.</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-4 contact-info">

                    @if(Session::has('pmail'))
                        <div class="alert alert-success"> {{ Session::get('pmail') }} </div>
                    @endif

                    <div class="col-md-12">
                        {{--<h3 class="text-center">Donate</h3><hr>--}}
                        <div class="row" style="margin-bottom: 20px;">
                            <div><h4>Created by:<strong class="cby pull-right">{{$auction->createdby->name}}</strong></h4></div>
                            <div><h4>Item Condition:<strong class="cby pull-right">{{$auction->condition}}</strong></h4></div>
                            <div><h4>Highest Bid:<strong class="cby pull-right">{{\App\Bid::maxBid($auction->id)}}</strong></h4></div>
                            <div><h4>Buy Now:<strong class="cby pull-right">${{$auction->price}}</strong></h4></div>
                        </div>

                            <div class="row">
                                <div>
                                    <h4>
                                        <i class="fa fa-clock-o fa-fw fa-2x"></i>
                                        @if (((strtotime($auction->end_date)-time())/86400) < 0)
                                            <b>{{0}}</b>
                                        @else
                                            <b>{{ceil((strtotime($auction->end_date)-time())/86400)}}</b>
                                        @endif
                                        Days Left
                                    </h4>
                                </div>

                                <div>
                                    <h4>
                                        <i class="fa fa-check-circle fa-fw fa-2x"></i>
                                        {{\App\Bid::countBid($auction->id)}} Bids
                                    </h4>
                                </div>

                            </div>

                            <hr>
                        <div class="row">
                            <div class="profile-group">
                                <!-- AddToAny BEGIN -->
                                <div class="a2a_kit a2a_kit_size_40 a2a_default_style">
                                    <a class="a2a_button_facebook"></a>
                                    <a class="a2a_button_twitter"></a>
                                    <a class="a2a_button_google_plus"></a>
                                    <a class="a2a_button_linkedin"></a>
                                    <a class="a2a_dd" href="https://www.geniusocean.com"></a>
                                </div>
                                <script async src="https://static.addtoany.com/menu/page.js"></script>
                                <!-- AddToAny END -->
                            </div>
                        </div>


                    <form action="{{action('FrontEndController@bid' , ['id'=>$auction->id])}}" method="get">
                            {{csrf_field()}}

                            <div class="form-group row" style="margin-bottom: 10px;">
                                <label class="">Bid Amount(USD):</label>
                                <div>
                                    <input type="text" id="donation"  pattern="[0-9]+(\.[0-9]{0,2})?%?"
                                           title="Price must be a numeric or up to 2 decimal places." name="amount" class="form-control costs" value="{{$auction->default_amount}}" required>
                                </div>
                            </div>
                        <div class="col-md-12" style="padding: 0">

                            @if(Session::has('error'))
                                <div class="alert alert-danger alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {{ Session::get('error') }}
                                </div>
                            @endif
                            @if(Session::has('message'))
                                <div class="alert alert-success alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {{ Session::get('message') }}
                                </div>
                            @endif
                        </div>
                            <div class="form-group text-center">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-6">
                                    @if($auction->status != "open")
                                        <button type="submit" class="btn btn-ocean btn-block" disabled="disabled"> Place Bid Now</button>
                                    @else
                                        <button type="submit" class="btn btn-ocean btn-block"> Place Bid Now</button>
                                    @endif
                                </div>
                            </div>

                        </form>
			@if(str_replace('$','',\App\Bid::maxBid($auction->id)) < $auction->price)
			
                            <div class="col-md-12"><h3 class="text-center">Or</h3></div>

                            <div class="form-group text-center">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-md-6">
                                    @if($auction->status != "open")
                                        <a href="javascript:;"><button type="submit" class="btn btn-ocean btn-block" disabled> Buy Now</button></a>
                                    @else
                                        <a href="{{url('/auction/'.$auction->id.'/buy')}}"><button type="submit" class="btn btn-ocean btn-block"> Buy Now</button></a>
                                    @endif
                                </div>
                            </div>
			@endif

                        <div class="row">
                            <div class="col-md-12" style="padding: 0;">
                                <hr>
                                <h3>Recent Bids</h3>
                                <hr>
                                @forelse($recentbids as $recentbid)
                                    <div class="bid-recent">
                                        <span>$ {{$recentbid->bid_amount}}</span>
                                        <p><i class="fa fa-check-circle"></i>  {{$recentbid->bidder->name}} - {{$recentbid->updated_at->diffForHumans()}}</p>
                                    </div>
                                @empty
                                    <div class="bid-recent">
                                        <p>No Bid Placed Yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>


                    </div>

                    <div class="col-md-12 text-center" style="margin-top: 20px;">
                        @if(!empty($ads300x250))
                        <div class="desktop-advert">
                            @if($ads300x250->type == "banner")
                                <a class="ads" href="{{$ads300x250->redirect_url}}" target="_blank">
                                    <img class="banner-300x250" src="{{url('/')}}/assets/images/ads/{{$ads300x250->banner_file}}" alt="Advertisement">
                                </a>
                            @else
                                {!! $ads300x250->script !!}
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-12" style="padding:0;">

	            	<h3 style="padding:15px;" class="">Related Auctions</h3>
				@if(!empty($popauctions))
			            <div id="featured-camps" class="owl-carousel">
			            @foreach($popauctions as $auction)
			            <div class="col-xs-12 col-sm-12 col-md-12">
			                <div class="package-list wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
			                    <a href="{{url('/')}}/auction/{{$auction->id}}">
			                        <div class="package-thumb">
			                            <img width="800" height="570" src="{{url('/assets/images/auction')}}/{{$auction->feature_image}}" class="" alt="1">
			                        </div>
			                        <div class="package-info text-center">
			
			                            <h4 class="">${{$auction->price}}</h4>
			                            <h3>{{$auction->title}}</h3>
			                            <h4 class="">Highest Bid: {{\App\Bid::maxBid($auction->id)}}</h4>
			
			                            <div class="row">
			                                <div class="col-md-4 col-sm-4 col-xs-4 small-box">
			                                    <b>{{\App\Bid::countBid($auction->id)}}</b>
			                                    <span>Bids</span>
			                                </div>
			                                <div class="col-md-4 col-sm-4 col-xs-4 small-box">
			                                    <b>{{$auction->condition}}</b>
			                                    <span>Condition</span>
			                                </div>
			                                <div class="col-md-4 col-sm-4 col-xs-4 small-box">
			                                    @if (((strtotime($auction->end_date)-time())/86400) < 0)
			                                        <b>{{0}}</b>
			                                    @else
			                                        <b>{{ceil((strtotime($auction->end_date)-time())/86400)}}</b>
			                                    @endif
			                                    <span>Days Left</span>
			                                </div>
			
			                            </div>
			                        </div>
			                    </a>
			                </div>
			            </div>
			            @endforeach
			            </div>
			            @else
			            	No Related Auction Found.
			            @endif
	                </div>
            </div>


        </div>
    </div>


@stop

@section('footer')
<script>

    jQuery("#gallery").unitegallery({
        gallery_theme: "compact",
        gallery_autoplay: false,						//true / false - begin slideshow autoplay on start
        gallery_play_interval: 3000,				//play interval of the slideshow
        slider_scale_mode: "fit",
        slider_enable_play_button: false,	//show, hide the theme fullscreen button. The position in the theme is constant
        slider_enable_fullscreen_button: false			//show, hide the theme play button. The position in the theme is constant
    });



</script>
@stop