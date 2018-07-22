@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{$title}}
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">{{$title}}</li>
    </ol>
</section>


<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <!-- /.box-header -->
                <div class="box-body" style="overflow-x: scroll;">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-12">
<!--                               <button type="button" class="btn bg-olive margin btn-sm">6月</button> -->
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-sm-12">
                            <table id="example1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        @foreach($table_title as $key => $item)
                                        <th>
                                            {{$item}}
                                        </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $key => $item)
                                    <tr role="row">
                                        <td>{{$item['date']}}</td>
                                        <td>{{$item['on']}}</td>
                                        <td>{{$item['off']}}</td>
                                        <td>
                                            <span class="label label-{{$item['am_type']['type']}}">
                                                {{$item['am_type']['message']}}
                                            </span>                                       
                                        </td>
                                        <td>
                                            <span class="label label-{{$item['pm_type']['type']}}">
                                                {{$item['pm_type']['message']}}
                                            </span>                                       
                                        </td>
                                        <td>
                                            <span class="label label-{{$item['late']['type']}}">
                                                {{$item['late']['message']}}
                                            </span>                                       
                                        </td>
                                        <td>
                                            <span class="label label-{{$item['leave_early']['type']}}">
                                                {{$item['leave_early']['message']}}
                                            </span>                                       
                                        </td>
                                        <td>{{$item['origin_minute']}}</td>
                                        <td>{{$item['origin_hour']}}</td>
                                        <td>{{$item['real_minute']}}</td>
                                        <td>{{$item['real_hour']}}</td>
                                        <td>{{$item['over_date_minute']}}</td>
                                        <td>{{$item['over_date_hour']}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @include('layouts.page_no')

                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->


    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
</section>

@section('footer_js')
<script>
  $(function () {
    $('#example1').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
  })
})
</script>
@endsection
@endsection
