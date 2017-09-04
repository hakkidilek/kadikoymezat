@extends('admin.includes.master-admin')

@section('content')

    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row" id="main">
                <!-- Page Heading -->
                <div class="go-title">
                    <div class="pull-right">
                        <a href="{!! url('admin/users') !!}" class="btn btn-default btn-add"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                    <h3>User Details</h3>
                </div>
                <!-- Page Content -->
                <div class="panel panel-default">
                    <div class="panel-body">

                        <table class="table">
                            <tbody>
                            <tr>
                                <td width="30%" style="text-align: right;"><strong>User ID#</strong></td>
                                <td>{{$customer->id}}</td>
                            </tr>
                            <tr>
                                <td width="30%" style="text-align: right;"><strong>Account Status: </strong></td>
                                @if($customer->status != 0)
                                    <td style="color: #008000;"> <strong>Active</strong></td>
                                @else
                                    <td style="color: #ff0000;"><strong>Banned</strong></td>
                                @endif
                            </tr>
                            <tr>
                                <td width="30%" style="text-align: right;"><strong>User Name:</strong></td>
                                <td>{{$customer->name}}</td>
                            </tr>
                            <tr>
                                <td width="30%" style="text-align: right;"><strong>User Email:</strong></td>
                                <td>{{$customer->email}}</td>
                            </tr>
                            <tr>
                                <td width="30%" style="text-align: right;"><strong>User Phone:</strong></td>
                                <td>{{$customer->phone}}</td>
                            </tr>
                            <tr>
                                <td width="30%" style="text-align: right;"><strong>Country:</strong></td>
                                <td>{{$customer->country}}</td>
                            </tr>
                            <tr>
                                <td width="30%" style="text-align: right;"><strong>Auction Created:</strong></td>
                                <td>{{\App\Auction::where('createdby',$customer->id)->count()}}</td>
                            </tr>
                            <tr>
                                <td width="30%" style="text-align: right;"><strong>Joined:</strong></td>
                                <td>{{$customer->created_at->diffForHumans()}}</td>
                            </tr>
                            <tr>
                                <td width="30%"></td>
                                <td><a href="email/{{$customer->id}}" class="btn btn-primary"><i class="fa fa-send"></i> Contact User</a>
                                </td>
                            </tr>

                            </tbody>
                        </table>

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