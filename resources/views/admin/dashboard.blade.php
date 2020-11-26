@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
     <!-- top tiles -->
     <div class="row tile_count">
      <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count" style="text-align: center">
        <span class="count_top"><i class="fa fa-user"></i> Total Customers</span>
        <div class="count green">10</div>
      </div>
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count"  style="text-align: center">
        <span class="count_top"><i class="fa fa-clock-o"></i> Total Retailers</span>
        <div class="count green">10</div>
      </div>
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count"  style="text-align: center">
          <span class="count_top"><i class="fa fa-user"></i> Total Products</span>
          <div class="count green">10</div>
      </div>
      <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count"  style="text-align: center">
        <span class="count_top"><i class="fa fa-user"></i> Total Categories</span>
        <div class="count green">10</div>
      </div>
      <div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count"  style="text-align: center">
        <span class="count_top"><i class="fa fa-user"></i> Total New Orders</span>
        <div class="count green">10</div>
      </div>
      
    </div> 
    <!-- /top tiles -->

  <div class="row">

    {{-- <div class="col-md-12">
      <div class="x_panel">
        <div class="x_title">
            <h2>Category List</h2>
            <div class="clearfix"></div>
        </div>
        <div>
          <div class="x_content">
            <div class="table-responsive">
            <table class="table table-striped jambo_table bulk_action">
              <thead>
                <tr>
                    <th>SL No.</th>
                    <th>Slot Number</th>
                    <th>Number</th>
                    <th>Action</th>
                </tr>
              </thead>
              <tbody class="form-text-element">
                  @if (isset($slots) && !empty($slots))
                    @php
                        $count = 1;
                    @endphp
                      @foreach ($slots as $item)
                        <tr>
                          <td>{{$count++}}</td>
                          <td>{{$item->slot_no}}</td>
                          <td>{{$item->number}}</td>
                          <td>
                          <a class="btn btn-warning" href="{{route('admin.slot_edit',['slot_id'=>$item->id])}}">Edit</a>
                          <a class="btn btn-danger" href="{{route('admin.slot_delete',['slot_id'=>$item->id])}}">Delete</a>
                          </td>
                        </tr>
                      @endforeach
                  @endif
              </tbody>
            </table>
            </div>
          </div>
        </div>
      </div>
    </div> --}}
  </div>
</div>
 @endsection
