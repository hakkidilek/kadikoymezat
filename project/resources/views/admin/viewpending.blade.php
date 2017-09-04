@extends('admin.includes.master-admin')

@section('content')

    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row" id="main">
                <!-- Page Heading -->
                <div class="go-title">
                    <div class="pull-right">
                        <a href="{!! url('admin/campaign/pending') !!}" class="btn btn-default btn-add"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                    <h3>Campaign Details</h3>
                    
                </div>
                <!-- Page Content -->
                <div class="panel panel-default">
                    <div class="panel-body">

                        <div class="col-xs-12" style="padding: 0">
                            <!-- Tab panes -->

                        <div class="go-title">
                            <h4>{{$campaign->title}}</h4>
                            <div class="go-line"></div>
                        </div>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Campaign ID#</strong></td>
                                    <td>{{$campaign->id}}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Campaign Status:</strong></td>
                                    <td>{{ucfirst($campaign->status)}}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Created On:</strong></td>
                                    <td>{{date('Y-m-d h:i:sa',strtotime($campaign->created_at))}}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Created By:</strong></td>
                                    <td>{{$campaign->createdby->name}},{{$campaign->createdby->country}}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Campaign Title:</strong></td>
                                    <td>{{$campaign->title}}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Campaign Category:</strong></td>
                                    <td>{{$campaign->category}}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Feature Image:</strong></td>
                                    <td><img style="max-width: 300px;" src="{{url('assets/images/campaign')}}/{{$campaign->feature_image}}" ></td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Campaign Video:</strong></td>
                                    <td>{!! \App\Campaign::getVideo($campaign->id) !!}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Campaign Description:</strong></td>
                                    <td>{!! $campaign->description !!}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Campaign Goal:</strong></td>
                                    <td>${{$campaign->goal}}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="text-align: right;"><strong>Campaign End Date:</strong></td>
                                    <td>{{$campaign->end_date}}</td>
                                </tr>
                                {{--@if($campaign->createdby->id != 0)--}}
                                {{--<tr>--}}
                                    {{--<td width="30%"></td>--}}
                                    {{--<td><a href="email/{{$campaign->id}}" class="btn btn-primary"><i class="fa fa-send"></i> Contact Owner</a>--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                                {{--@endif--}}
                            </tbody>
                        </table>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->


@stop

@section('footer')

@stop