@extends('admin.includes.master-admin')

@section('content')

    <div id="page-wrapper">
        <div class="container-fluid">
            <div class="row" id="main">
                <!-- Page Heading -->
                <div class="go-title">

                    <h3>Withdraws <a class="btn btn-info" href="{{url('admin/withdraws/pending')}}"><strong>{{\App\Withdraw::withdrawPending()}} Pending</strong></a></h3>
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
                                <th>Campaign Name</th>
                                <th width="10%">Owner Email</th>
                                <th>Withdraw Amount</th>
                                <th width="10%">Method</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($withdraws as $withdraw)
                                <tr>
                                    <td>{{$withdraw->userid->name}}</td>
                                    <td>{{$withdraw->userid->email}}</td>
                                    <td>${{$withdraw->amount}}</td>
                                    <td>{{$withdraw->method}}</td>
                                    <td>{{$withdraw->created_at}}</td>
                                    <td>
                                        <a href="withdraws/{{$withdraw->id}}" class="btn btn-primary btn-xs"><i class="fa fa-check"></i> View Details </a>
                                        @if($withdraw->status == "pending")
                                        <a href="withdraws/accept/{{$withdraw->id}}" class="btn btn-success btn-xs"><i class="fa fa-check-circle"></i> Accept</a>

                                        <a href="withdraws/reject/{{$withdraw->id}}" class="btn btn-danger btn-xs"><i class="fa fa-times-circle"></i> Reject</a>
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