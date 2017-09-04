@extends('includes.master')

@section('content')

    <section class="go-section">
        <div class="row">
            <div class="container">
                <div class="col-md-12 text-center services">
                    <div class="services-div">
                        @if($settings[0]->success_msg != "")
                            {!! $settings[0]->success_msg !!}
                        @else
                        <h1 class="text-center" style="color: green">Payment Success.<br> Thank You !!</h1>
                        <h2>You Successfully Bought this item.</h2>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop

@section('footer')

@stop