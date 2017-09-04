@extends('includes.master')
@section('content')


<section class="go-slider">
<div id="bootstrap-touch-slider" class="carousel bs-slider fade  control-round indicators-line" data-ride="carousel" data-pause="hover" data-interval="5000" >

    <!-- Indicators -->
    <ol class="carousel-indicators">
        @for ($i = 0; $i < count($sliders); $i++)
            @if($i == 0)
                <li data-target="#bootstrap-touch-slider" data-slide-to="{{$i}}" class="active"></li>
            @else
                <li data-target="#bootstrap-touch-slider" data-slide-to="{{$i}}"></li>
            @endif
        @endfor
    </ol>

    <!-- Wrapper For Slides -->
    <div class="carousel-inner" role="listbox">

        @for ($i = 0; $i < count($sliders); $i++)
            @if($i == 0)
                <!-- Third Slide -->
                    <div class="item active">

                        <!-- Slide Background -->
                        <img src="{{url('/')}}/assets/images/sliders/{{$sliders[$i]->image}}" alt="Bootstrap Touch Slider"  class="slide-image"/>
                        <div class="bs-slider-overlay"></div>

                        <div class="container">
                            <div class="row">
                                <!-- Slide Text Layer -->
                                <div class="slide-text {{$sliders[$i]->text_position}}">

                                    <h1 data-animation="animated fadeInDown">{{$sliders[$i]->title}}</h1>
                                    <p data-animation="animated fadeInUp">{{$sliders[$i]->text}}</p>

                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- End of Slide -->
            @else
            <!-- Second Slide -->
                <div class="item">

                    <!-- Slide Background -->
                    <img src="{{url('/')}}/assets/images/sliders/{{$sliders[$i]->image}}" alt="Bootstrap Touch Slider"  class="slide-image"/>
                    <div class="bs-slider-overlay"></div>
                    <!-- Slide Text Layer -->
                    <div class="slide-text {{$sliders[$i]->text_position}}">
                        <h1 data-animation="animated fadeInDown">{{$sliders[$i]->title}}</h1>
                        <p data-animation="animated fadeInUp">{{$sliders[$i]->text}}</p>
                    </div>
                </div>
                <!-- End of Slide -->
            @endif
    @endfor


    </div><!-- End of Wrapper For Slides -->

        <!-- Left Control -->
        <a class="left carousel-control" href="#bootstrap-touch-slider" role="button" data-slide="prev">
            <span class="fa fa-angle-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>

        <!-- Right Control -->
        <a class="right carousel-control" href="#bootstrap-touch-slider" role="button" data-slide="next">
            <span class="fa fa-angle-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>

    </div> <!-- End  bootstrap-touch-slider Slider -->

</section>

@if($settings[0]->service_status == 1)
<section class="wow fadeInUp go-services hideme">
    <div class="row" style="margin-top:70px;">
        <div class="container">
            <div class="col-md-6 col-md-offset-3">
                <div class="section-title">
                    <h2>{{$languages->service_title}}</h2>
                    <p>{{$languages->service_text}}</p>
                </div>
            </div>
            @foreach($services as $service)
                <div class="col-xs-12 col-md-4">
                    <div class="service-list text-center wow fadeInUp">
                        <img src="{{url('/assets/images/service')}}/{{$service->icon}}" alt="">
                        <h3>{{$service->title}}</h3>
                        <p>{{$service->text}}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="wow fadeInUp go-section">
    <div class="row">
        <div class="container">
            <div class="col-md-6 col-md-offset-3">
                <div class="section-title">
                    <h2>{{$languages->category_title}}</h2>
                    <p>{{$languages->category_text}}</p>
                </div>
            </div>
            @foreach($categories as $category)
                <div class="col-md-4 cats">
                    <a href="category/{{$category->slug}}" class="btn btn-genius btn-block"><strong> {{$category->name}}</strong></a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="wow fadeInUp go-services hideme">
    <div class="container">
        <div class="col-md-6 col-md-offset-3">
            <div class="section-title">
                <h2>{{$languages->pricing_title}}</h2>
                <p>{{$languages->pricing_text}}</p>
            </div>
        </div>
        <div class="col-sm-12">
            <div id="featured-camps" class="owl-carousel">
                @foreach($fauctions as $auction)
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
        </div>
    </div>
</section>

<section class="wow fadeInUp go-services hideme">
    <div class="container">
        <div class="col-md-6 col-md-offset-3">
            <div class="section-title">
                <h2>{{$languages->newcamp_title}}</h2>
                <p>{{$languages->newcamp_text}}</p>
            </div>
        </div>
        <div class="col-sm-12">
            <div id="new-camps" class="owl-carousel">
            @foreach($newauctions as $auction)
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
        </div>
    </div>
</section>

<section id="counter_two" class="wow fadeInUp go-counter counter_two" style="background: url({{url('/')}}/assets/images/{{$settings[0]->background}}) no-repeat center center; background-size: cover;">
    <div class="overlay">
        <div class="container">
            <div class="row">
                <div class="main_counter_two sections text-center">
                    <div class="col-sm-12" style="padding: 0;">
                        <div class="row">
                                <div class="col-sm-4 col-xs-12">
                                    <div class="single_counter_two_right">
                                        <h2 class="counter" data-count="{{\App\Auction::where('status','open')->count()}}">0</h2>
                                        <p>Auctions Running</p>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-xs-12">
                                    <div class="single_counter_two_right">
                                        <h2 class="counter" data-count="{{\App\Bid::count()}}">0</h2>
                                        <p>Total Bids</p>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-xs-12">
                                    <div class="single_counter_two_right">
                                        <h2><span class="counter" data-count="{{\App\Auction::where('status','closed')->count()}}">0</span></h2>
                                        <p>Auctions Completed</p>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if($settings[0]->gallery_status == 1)
<section class="wow fadeInUp projects_area hideme" id="portfolio">
    <div class="container projects">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="section-title">
                    <h2>{{$languages->portfolio_title}}</h2>
                    <p>{{$languages->portfolio_text}}</p>
                </div>
            </div>
            <div class="col-md-12">
                <div class="project_list ">
                    @foreach($portfilos as $portfilo)
                    <div class="mix col-md-4 single_project" data-my-order="1">
                        <img src="{{url('/assets/images/portfolio')}}/{{$portfilo->image}}"/>
                        <div class="project_detail">
                            <div class="project_overlay"></div>
                            <a data-lightbox="example-2" data-title="" href="{{url('/assets/images/portfolio')}}/{{$portfilo->image}}">
                                <i class="fa fa-search"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

@if($settings[0]->testi_status == 1)
<!-- TESTIMONIALS -->
<section class="wow fadeInUp testimonials hideme">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="section-title">
                    <h2>{{$languages->testimonial_title}}</h2>
                    <p>{{$languages->testimonial_text}}</p>
                </div>
            </div>
            <div class="col-sm-12">
                <div id="customers-testimonials" class="owl-carousel">
                @foreach($testimonials as $testimonial)
                    <div class="item">
                        <div class="shadow-effect">
                            <i class="fa fa-quote-right"></i>
                            <div class="item-details">
                                <p class="ctext">{{$testimonial->review}}</p>
                                <h5>{{$testimonial->client}}</h5>
                                <p>{{$testimonial->designation}}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- END OF TESTIMONIALS -->
@endif

@stop

@section('footer')
<script>

</script>
@stop