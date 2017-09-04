@extends('admin.includes.master-admin')

@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">

            <div class="row" id="main">

                <div class="go-title">
                    <div class="pull-right">
                        <a href="{!! url('admin/auction') !!}" class="btn btn-default btn-add"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                    <h3>Pending Auctions</h3>
                    <div class="go-line"></div>
                </div>
                <!-- Page Content -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div id="res">
                            @if(Session::has('message'))
                                <div class="alert alert-success alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {{ Session::get('message') }}
                                </div>
                            @endif
                            @if(Session::has('error'))
                                <div class="alert alert-danger alert-dismissable">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    {{ Session::get('error') }}
                                </div>
                            @endif
                        </div>

                    <!-- Page Content -->
                            <table class="table table-striped table-bordered" cellspacing="0" id="example" width="100%">
                                <thead>
                                <tr>
                                    <th width="20%">Auction Name</th>
                                    <th>Date</th>
                                    <th>Goal</th>
                                    <th>Created By</th>
                                    <th>Category</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($auctions as $auction)
                                    <tr>
                                        <td>{{$auction->title}}</td>
                                        <td>{{date('d M',strtotime($auction->created_at))}} - {{date('d M',strtotime($auction->end_date))}}</td>
                                        <td>${{$auction->goal}}</td>
                                        <td>{{$auction->createdby}}</td>
                                        <td>{{$auction->category}}</td>
                                        <td>
                                            <a href="{{$auction->id}}/pending" class="btn btn-success btn-xs"><i class="fa fa-eye"></i> View </a>
                                            <a href="{{$auction->id}}/accept" class="btn btn-primary btn-xs"><i class="fa fa-check"></i> Accept </a>

                                            <a href="javascript:;" data-title="Reject Auction" data-href="{{url('/')}}/admin/auction/{{$auction->id}}/reject" data-toggle="modal" data-target="#confirm" class="btn btn-warning btn-xs"><i class="fa fa-times"></i> Reject </a>

                                            <a href="javascript:;" data-title="Reject & Delete Auction" data-href="{{url('/')}}/admin/auction/{{$auction->id}}/hardreject" data-toggle="modal" data-target="#confirm" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Reject & Delete </a>

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

    <div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content panel-danger">
                <div class="modal-header panel-heading">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title" id="myModalLabel"></h3>
                </div>
                <div class="modal-body">
                    <h4>Do you want to proceed?</h4>
                    <form method="GET" action="" id="reform" class="form-horizontal form-label-left">
                        <div class="item form-group">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <textarea placeholder="Reject Reason(Required)" rows="6" name="reason" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button type="submit" class="btn btn-primary btn-block btn-ok">Reject</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('footer')
    <script>
        $('#confirm').on('show.bs.modal', function(e) {
            $(this).find('#reform').attr('action', $(e.relatedTarget).data('href'));
            $(this).find('#myModalLabel').html($(e.relatedTarget).data('title'));
            $(this).find('.btn-ok').html($(e.relatedTarget).data('title'));
        });
    </script>
@stop