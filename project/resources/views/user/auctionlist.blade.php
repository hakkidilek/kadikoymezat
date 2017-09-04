@extends('user.includes.master-user')

@section('content')
    <div id="page-wrapper">
        <div class="container-fluid">

            <div class="row" id="main">
                <!-- Page Heading -->
                <div class="go-title">
                    <div class="pull-right">
                        <a href="{!! url('user/auction/create') !!}" class="btn btn-primary btn-add"><i class="fa fa-plus"></i> Create New Auction</a>
                    </div>
                    <h3>Auctions </h3>
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
                        <table class="table table-striped table-responsive table-bordered" cellspacing="0" id="example" width="100%">
                            <thead>
                            <tr>
                                <th width="20%">Auction Title</th>
                                <th>Duration</th>
                                <th>Buy Price</th>
                                <th>Type</th>
                                <th>Total Bids</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($auctions as $auction)
                                <tr>
                                    <td>{{$auction->title}}</td>
                                    <td>{{date('d M',strtotime($auction->created_at))}} - {{date('d M',strtotime($auction->end_date))}}</td>
                                    <td>${{$auction->price}}</td>
                                    <td>
                                        @if($auction->featured == 1)
                                            <label class="label label-primary">Featured</label>
                                        @else
                                            <label class="label label-default">Basic</label>
                                        @endif
                                    </td>
                                    <td>{{\App\Bid::countBid($auction->id)}}</td>
                                    <td>{{ucfirst($auction->status)}}</td>
                                    <td>
                                        <a href="auction/{{$auction->id}}" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> View </a>
                                        @if($auction->status == "pending")
                                            <a href="auction/{{$auction->id}}/publish" class="btn btn-success btn-xs"><i class="fa fa-sign-in"></i> Publish </a>
                                        @else
                                            @if($auction->featured != 1)
                                                <a href="auction/{{$auction->id}}/publish" class="btn btn-success btn-xs"><i class="fa fa-sign-in"></i> Make Feature </a>
                                            @endif
                                        @endif
                                        <a href="auction/{{$auction->id}}/edit" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit </a>

                                        @if($auction->status != "closed")
                                            <a href="auction/{{$auction->id}}/close" class="btn btn-warning btn-xs"><i class="fa fa-toggle-on"></i> Close </a>
                                        @else
                                            <a href="auction/{{$auction->id}}/open" class="btn btn-success btn-xs"><i class="fa fa-toggle-off"></i> Open </a>
                                        @endif
                                        <a href="javascript:;" data-href="{{url('/')}}/user/auction/{{$auction->id}}/delete" data-toggle="modal" data-target="#confirm-delete"class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
    </div>
</div>
<!-- /.end -->
</div>

    <!-- /#page-wrapper -->

    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content panel-danger">
                <div class="modal-header panel-heading">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-circle fa-fw"></i> Confirm Delete</h3>
                </div>
                <div class="modal-body">
                    <p>You are about to delete this auction, All Donations will be deleted under this auction.</p>
                    <h4>Do you want to proceed?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-danger btn-ok">Delete</a>
                </div>
            </div>
        </div>
    </div>

@stop

@section('footer')
    <script>
        $('#confirm-delete').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });
    </script>
@stop