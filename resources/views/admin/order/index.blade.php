@extends('admin.template.admin_master')

@section('content')
<style>
    .btn{
        padding:2px !important;
    }
</style>
<link rel="stylesheet" href="{{asset('admin/dialog_master/simple-modal.css')}}">
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="col-md-8">

                        <h2>Orders</h2>
                    </div>
                    <div class="col-md-4">
                        <form action="{{ route('admin.order_search')}}" method="get">
                           
                            <div class="col-md-10">
                                <input type="text" name="search_key" id="" class="form-control" placeholder="Search By Order Id">
                            </div>
                            <div class="col-md-2" style="margin: 0;padding: 0;">
                                <button type="submit" class="btn btn-sm btn-success" style="padding: 6px !important;">Search</button>
                            </div>
                        </form>
                    </div>
                    <div class="clearfix"></div>
                </div>

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
                                    <th class="column-title" style="min-width: 185px;">Action</th>
                                </tr>
                            </thead>

                            <tbody>

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
                                    <td>{{ $order->amount-($order->advance_amount + $order->discount) }}</td>
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
                                    <td>
                                        <b id="action{{$count}}">
                                            {{-- @php
                                                $bank_account = isset($order->customer->bankAccount) ? $order->customer->bankAccount : [];
                                            @endphp --}}
                                          @if ($order->payment_status == '1')
                                            @if ($order->order_status == '5')                                                
                                                <button class="btn btn-xs btn-danger" disabled>Cancelled</button>
                                            @else
                                                <button class="btn btn-xs btn-danger" onclick="openModal({{$order->id}},'5',{{$count}},'Are You Sure To Cancel')">Cancel</button>
                                                
                                            @endif
                                          @else
                                            @if ($order->order_status != '4')
                                                @if ($order->order_status == '5')                                                
                                                    <button class="btn btn-xs btn-danger" disabled>Cancelled</button>
                                                @else
                                                    <button class="btn btn-xs btn-danger" onclick="openModelCancel({{$order->id}},{{$count}})">Cancel</button>
                                                @endif
                                            @endif
                                            @if ($order->order_status < '2')
                                              <button class="btn btn-xs btn-primary" onclick="openModal({{$order->id}},'2',{{$count}},'Are You Sure To Accept')">Accept</button>
                                            @endif
                                            @if ($order->order_status < '4')
                                              <button class="btn btn-xs btn-primary" onclick="openModelReschedule({{$order->id}},{{$count}})">Reschedule</button>
                                            @endif
                                          @endif
                                        </b>
                                        <a href="{{route('admin.order_details',['order_id'=>$order->id])}}" target="_blank" class="btn btn-sm btn-warning">view</a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                	<tr>
	                                    <td colspan="12" style="text-align: center">Sorry No Data Found</td>
                                	</tr>
                                @endif
                            </tbody>
                        </table>
                        {!! $orders->onEachSide(2)->links() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


{{-- Model for Cancel Order --}}

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" id="myModel">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Cancel Form</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="order_id" id="model_order_id">
          <input type="hidden" id="action_input_id">
          <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
              <label for="prescription">Is Refundable?</label>
          </div>
          <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
              <p> Yes : <input type="radio"  name="is_refund"  value="2"  />
                  No : <input type="radio" name="is_refund"  value="1"    checked/>
              </p>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="checkModelInput() ">Submit</button>
        </div>
      </div>
    </div>
</div>

{{-- Model for Reschedule Order --}}

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true" id="reScheduleModel">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel2">Select Account Number</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="order_id" id="re_model_order_id">
          <input type="hidden" id="re_action_input_id">
          <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
              <label for="prescription">Select Schedule Date</label>
          </div>
          <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
              <input type="datetime-local" class="form-control"  name="schedule_date"  id="schedule_date"/>
              <span id="reschedule_error"></span>
          </div>
          <br>
          <br>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="checkModelReschduleInput() ">Submit</button>
        </div>
      </div>
    </div>
</div>


 @endsection

@section('script')

    <script src="{{asset('admin/dialog_master/simple-modal.js')}}"></script>

    <script>

        $(document).ready(function() {
            $("input[name='is_refund']").click(function(){
                if($("input[name='is_refund']:checked").val() == '2'){
                    $("#account_no_div").show();
                }else{
                    $("#account_no_div").hide();
                }
            });
        })

        async function openModal(order_id,status,action_id,msg) {
            this.myModal = new SimpleModal("Attention!", msg);
            try {
                const modalResponse = await myModal.question();
                if (modalResponse) {
                    $.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
					});
					$.ajax({
						type:"GET",
						url:"{{url('admin/order/accept/')}}"+"/"+order_id+"/"+status,

						beforeSend: function() {
					        // setting a timeout
					        $("#action"+action_id).html('<i class="fa fa-spinner fa-spin"></i>');
					        $("#status"+action_id).html('<i class="fa fa-spinner fa-spin"></i>');
					    },
						success:function(data){
                            if (data) {
                                if (status == '2') {
                                    $("#status"+action_id).html('<button class="btn btn-xs btn-primary" disabled>Accepted</button>');
    
                                    $("#action"+action_id).html(`<button class="btn btn-xs btn-info" disabled>Accepted</button>`);
                                } else {
                                    $("#status"+action_id).html('<button class="btn btn-xs btn-danger" disabled>Cancelled</button>');
    
                                    $("#action"+action_id).html(`<button class="btn btn-xs btn-danger" disabled>Cancelled</button>`);
                                }
                            }else{
                                $("#status"+action_id).html("");
					            $("#action"+action_id).html('<button class="btn btn-xs btn-danger" disabled>Try Again</button>');

                            }
						}
					});
                }
            } catch(err) {
            console.log(err);
            }

        }
    </script>

    {{-- // Cancel Order --}}
    <script>
        function openModelCancel(order_id,action_id) {
            $('#myModel').modal({
                keyboard: false,
                backdrop: 'static'
            });
            $('#myModel').on('shown.bs.modal', function (e) {
                $("#model_order_id").val(order_id);
                $("#action_input_id").val(action_id);
                $(this).off('shown.bs.modal');
            })
        }

        function checkModelInput() {
            $("#account_error").html('');
            if($("input[name='is_refund']:checked").val() == '2'){
                    hideModelCancel();
            }else{
                hideModelCancel();
            }
        }

        function hideModelCancel() {
            $('#myModel').modal('hide');
            $('#myModel').on('hidden.bs.modal', function (e) {
                var model_order_id = $("#model_order_id").val();
                var action_input =  $("#action_input_id").val();
                var is_refund =  $("input[name='is_refund']:checked").val();
              
                if (model_order_id && is_refund) {
                    $.ajax({
                        type:"GET",
                        url:"{{url('admin/order/cancel/')}}"+"/"+model_order_id+"/"+is_refund,

                        beforeSend: function() {
                            // setting a timeout
                            $("#action"+action_input).html('<i class="fa fa-spinner fa-spin"></i>');
                            $("#status"+action_input).html('<i class="fa fa-spinner fa-spin"></i>');
                        },
                        success:function(data){
                            if (data) {
                                $("#status"+action_input).html('<button class="btn btn-xs btn-danger" disabled>Cancelled</button>');
                                $("#action"+action_input).html(`<button class="btn btn-xs btn-danger" >Cancelled</button>`);
                            }else{
                                $("#status"+action_input).html("");
                                $("#action"+action_input).html('<button class="btn btn-xs btn-danger" disabled>Try Again</button>');
                            }
                        }
                    });
                }
                $(this).off('hidden.bs.modal');
            })
        }
    </script>

    {{-- //reschedule --}}
    <script>
        function openModelReschedule(order_id,action_id) {
            $('#reScheduleModel').modal({
                keyboard: false,
                backdrop: 'static'
            });
            $('#reScheduleModel').on('shown.bs.modal', function (e) {
                $("#re_model_order_id").val(order_id);
                $("#re_action_input_id").val(action_id);
                $(this).off('shown.bs.modal');
            })
        }

        function checkModelReschduleInput() {
            $("#reschedule_error").html('');
            if (!$('#schedule_date').val()) {
                $("#reschedule_error").html('<p style="color:red">Please Select Schedule Date</p>');
            }else{
                hideModelReSchedule();
            }
        }

        function hideModelReSchedule() {
            $('#reScheduleModel').modal('hide');
            $('#reScheduleModel').on('hidden.bs.modal', function (e) {
                var re_model_order_id = $("#re_model_order_id").val();
                var re_action_input_id =  $("#re_action_input_id").val();
                var schedule_date =  $("#schedule_date").val();
                console.log('re_model_order_id '+re_model_order_id);
                console.log('re_action_input_id '+re_action_input_id);
                console.log('schedule_date '+schedule_date);
                if (re_model_order_id && schedule_date) {
                    $.ajax({
                        type:"GET",
                        url:"{{url('admin/order/reschedule/')}}"+"/"+re_model_order_id+"/"+schedule_date,

                        beforeSend: function() {
                            // setting a timeout
                            $("#action"+re_action_input_id).html('<i class="fa fa-spinner fa-spin"></i>');
                            $("#status"+re_action_input_id).html('<i class="fa fa-spinner fa-spin"></i>');
                        },
                        success:function(data){
                            if (data) {
                                $("#status"+re_action_input_id).html('<button class="btn btn-xs btn-info" disabled>Re Scheduled</button>');
                                $("#action"+re_action_input_id).html(`<button class="btn btn-xs btn-info" >Re Scheduled</button>`);
                            }else{
                                $("#status"+re_action_input_id).html("");
                                $("#action"+re_action_input_id).html('<button class="btn btn-xs btn-danger" disabled>Try Again</button>');
                            }
                        }
                    });
                }
                $(this).off('hidden.bs.modal');
            })
        }
    </script>


 @endsection
