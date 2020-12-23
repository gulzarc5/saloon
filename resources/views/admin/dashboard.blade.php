@extends('admin.template.admin_master')

@section('content')
<link href="{{asset('admin/vendors/morris.js/morris.css')}}" rel="stylesheet">
<div class="right_col" role="main">
     <!-- top tiles -->
     <div class="row tile_count">
      <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count" style="text-align: center">
        <span class="count_top"><i class="fa fa-user"></i> Total Customers</span>
        <div class="count green">{{ $total_customers_count }}</div>
      </div>
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count"  style="text-align: center">
        <span class="count_top"><i class="fa fa-clock-o"></i> Total Freelauncer</span>
        <div class="count green">{{ $total_freelauncer_count }}</div>
      </div>
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count"  style="text-align: center">
          <span class="count_top"><i class="fa fa-user"></i> Total Saloon Shop</span>
          <div class="count green">{{ $saloon_shop_count }}</div>
      </div>
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count"  style="text-align: center">
        <span class="count_top"><i class="fa fa-user"></i> Total Service Category</span>
        <div class="count green">{{ $service_category_count }}</div>
      </div>
      <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count"  style="text-align: center">
        <span class="count_top"><i class="fa fa-user"></i> Total Processing Orders</span>
        <div class="count green">{{ $processing_orders_count }}</div>
      </div>
      
    </div> 
    <!-- /top tiles -->

  <div class="row">

    <div class="col-md-12">
      <div class="col-md-6">
          <div id="order_graph"></div>
      </div>
      <div class="col-md-6">
          <div id="order_graph2"></div>
      </div>
    </div>

    <div class="col-md-12">
      <div class="x_panel">
            <h2>Orders</h2>
            <div class="clearfix"></div>
        <div>
          <div class="x_content">
            <div class="table-responsive">
            <table class="table table-striped jambo_table bulk_action">
              <thead>
                <tr class="headings" style="font-size: 10.5px;">
                  <th class="column-title">#</th>
                  <th class="column-title">OrderID</th>
                  <th class="column-title">OrderBy</th>
                  <th class="column-title">OrderTo</th>
                  <th class="column-title">OrderType</th>
                  <th class="column-title">PayableAmount</th>
                  <th class="column-title">AdvanceAmount</th>
                  <th class="column-title">PaymentStatus</th>
                  <th class="column-title">OrderStatus</th>
                  <th class="column-title">ScheduleTime</th>
                  <th class="column-title">Date</th>
                </tr>
              </thead>
              <tbody class="form-text-element">
                @if(isset($orders) && !empty($orders) && count($orders) > 0)
                @php
                    $count = 1;
                @endphp

                @foreach($orders as $order)
                <tr class="even pointer">
                  <td class=" ">{{ $count++ }}</td>
                  <td class=" ">{{ $order->id }}</td>
                  <td>{{ isset($order->customer->name)?$order->customer->name:'' }}</td>
                  <td>{{ isset($order->client->name)?$order->client->name:'' }}</td>
                  <td class=" ">
                    @if (isset($order->client->clientType))                                          
                      @if($order->client->clientType == '1')
                          <button class='btn btn-xs btn-primary'>Free Launcer</button>
                      @else
                          <button class='btn btn-xs btn-success'>Saloon</button>
                      @endif
                    @endif
                  </td>

                  <td>{{ $order->amount-$order->advance_amount }}</td>
                  <td>{{ $order->advance_amount}}</td>
                  {{-- <td>{{ $order->amount}}</td> --}}
                  <td class=" ">
                    @if($order->payment_status == '1')
                          <a href="#" class="btn btn-xs btn-danger">Failed</a>
                      @elseif($order->payment_status == '2')
                          <a href="#" class="btn btn-xs btn-success">Paid</a>
                      @endif
                  </td>
                  <td id="status{{$count}}">
                      @if($order->order_status == '1')
                          <button class='btn btn-xs btn-warning' disabled>New Order</button>
                      @elseif($order->order_status == '2')
                          <button class='btn btn-xs btn-primary' disabled>Accepted</button>
                      @elseif($order->order_status == '3')
                          <button class='btn btn-xs btn-info' disabled>Rescheduled</button>
                      @elseif($order->order_status == '4')
                          <button class='btn btn-xs btn-success' disabled>Completed</button>
                      @else
                          <button class='btn btn-xs btn-danger' disabled>Cancelled</button>
                      @endif
                  </td>
                  <td>{{ $order->service_time }}</td>

                    <td>{{ $order->created_at }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="11">
                        <a href="{{route('admin.orders')}}" class="btn btn-sm btn-warning">View More</a>
                    </td>
                </tr>
                @else
                    <tr>
                        <td colspan="12" style="text-align: center">Sorry No Data Found</td>
                    </tr>
                @endif
              </tbody>
            </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
 @endsection

 @section('script')
    <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script>
    <script src="{{asset('admin/vendors/morris.js/morris.js')}}"></script>
     <script>
        $(function () {
            Morris.Donut({
                element: 'order_graph',
                data: [
                {value: {{$pie['new_order_pie']}}, label: 'New Order'},
                {value: {{$pie['accepted_order_pie']}}, label: 'Accepted Order'},
                {value: {{$pie['completed_order_pie']}}, label: 'Completed Orders'},
                {value: {{$pie['cancel_order_pie']}}, label: 'Cancel Orders'}
                ],
                backgroundColor: '#ccc',
                labelColor: '#060',
                colors: [
                '#FF5733',
                '#800080',
                '#008000',
                '#FF0000'
                ],
                formatter: function (x) { return x + "%"}
            });
        });

        var data = [
            @for($i = 0; $i < 11; $i++)
                @if($i==10)
                    { y:"{{$chart[$i]['level']}}", a: {{$chart[$i]['completed']}}, b: {{$chart[$i]['cancel']}}}
                @else
                    { y: "{{$chart[$i]['level']}}", a: {{$chart[$i]['completed']}}, b: {{$chart[$i]['cancel']}}},
                @endif
            @endFor
            // @php
            //     for($i = 0; $i < 12; $i++){

            //     }
            // @endphp
            // { y: "Jan-2020", a: 0, b: 0},
            // { y: "Feb-2020", a: 0, b: 0},
            // { y: "Mar-2020", a: 0, b: 0},
            // { y: "Apr-2020", a: 0, b: 0},
            // { y: "May-2020", a: 0, b: 0},
            // { y: "Jun-2020", a: 0, b: 0},
            // { y: "Jul-2020", a: 0, b: 0},
            // { y: "Aug-2020", a: 0, b: 0},
            // { y: "Sep-2020", a: 1, b: 1},
            // { y:" Oct, 2020", a: 0, b: 2}
            // { y: '2014', a: 0, b: 0},
            // { y: '2014-Jun', a: 0, b: 0},
            // { y: '2015', a: 0,  b: 0},
            // { y: '2016', a: 0,  b: 0},
            // { y: '2017', a: 0,  b: 0},
            // { y: '2018', a: 0,  b: 0},
            // { y: '2019', a: 0,  b: 0},
            // { y: '2020', a: 0, b: 0},
            // { y: '2021', a: 0, b: 0},
            // { y: '2022', a: 0, b: 0},
            // { y: '2023', a: 0, b: 0},
            // { y: '2024', a: 0, b: 0}
            ],
            formatY = function (y) {
            return '$'+y;
        },
    formatX = function (x) {
            return x.src.y;
        },
            config = {
                xLabels: 'month',
            data: data,
            xkey: 'y',
            ykeys: ['a', 'b'],
            labels: ['Total Completed', 'Total Cancelled'],
            fillOpacity: 0.6,
            stacked: true,
            hideHover: 'auto',
            behaveLikeLine: true,
            resize: true,
            pointFillColors:['#ffffff'],
            pointStrokeColors: ['black'],
            lineColors:['gray','red']
        };

        config.element = 'order_graph2';
        Morris.Area(config);
     </script>
 @endsection

