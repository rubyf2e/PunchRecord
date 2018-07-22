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



            <div class="box box-primary">

                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-5">

                            <div class="form-group">
                                <label>請假日期</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <input type="text" class="form-control Default input" id="reservationtime">
                                </div>
                            </div>


                            <div class="form-group">
                                <label>
                                  <input type="radio" name="r3" class="flat-red" checked>
                              </label>
                              <label>
                                  <input type="radio" name="r3" class="flat-red">
                              </label>
                              <label>
                                  <input type="radio" name="r3" class="flat-red" disabled>
                                  Flat green skin radio
                              </label>
                          </div>
                          

                      </div>
                  </div>
              </div>

              <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">送出申請</button>
            </div>


        </div>







    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
</section>



@section('footer_js')
<script>
    $(function () {
        $('#reservationtime').daterangepicker({ 
            locale: {
                direction: "ltr",
                format: "YYYY-MM-DD h:mm A",
                separator: " ~ ",
                applyLabel: "確定",
                cancelLabel: "清除",
                fromLabel: "開始日期",
                toLabel: "結束日期",
                customRangeLabel: "自訂日期區間",
                daysOfWeek: ["日", "一", "二", "三", "四", "五", "六"],
                monthNames: ["1月", "2月", "3月", "4月", "5月", "6月",
                "7月", "8月", "9月", "10月", "11月", "12月"],
            },

            timePicker: true,
            timePickerIncrement: 30,
            format: 'YYYY/MM/DD h:mm A',
            autoUpdateInput:true
        });
    })
</script>

@endsection

@endsection
