@extends('admin.includes.master-admin')

@section('content')

    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row" id="main">
                <!-- Page Heading -->
                <div class="go-title">

                    <h3>Users</h3>
                    <div class="go-line"></div>
                </div>
                <!-- Page Content -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="response">
                            @if(Session::has('message'))
                                <div class="alert alert-success alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {{ Session::get('message') }}
                                </div>
                            @endif
                        </div>
                        <table class="table table-striped table-bordered" cellspacing="0" id="example" width="100%">
                            <thead>
                            <tr>
                                <th>Full Name</th>
                                <th width="10%">Email</th>
                                <th>Phone</th>
                                <th width="10%">Country</th>
                                <th width="5%">Auctions</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($customers as $customer)
                                <tr>
                                    <td>{{$customer->name}}</td>
                                    <td>{{$customer->email}}</td>
                                    <td>{{$customer->phone}}</td>
                                    <td>{{$customer->country}}</td>
                                    <td>{{\App\Auction::where('createdby',$customer->id)->count()}}</td>
                                    <td>
                                        @if($customer->status != 0)
                                            Active
                                        @else
                                            Banned
                                        @endif
                                    </td>

                                    <td>

                                            <a href="users/{{$customer->id}}" class="btn btn-primary btn-xs"><i class="fa fa-check"></i> View Details </a>
                                            @if($customer->status != 0)
                                                <a href="users/status/{{$customer->id}}/0" class="btn btn-danger btn-xs"><i class="fa fa-toggle-off"></i> Ban</a>
                                            @else
                                                <a href="users/status/{{$customer->id}}/1" class="btn btn-success btn-xs"><i class="fa fa-toggle-on"></i> Activate</a>
                                            @endif

                                    </td>
                                </tr>
                            @endforeach
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