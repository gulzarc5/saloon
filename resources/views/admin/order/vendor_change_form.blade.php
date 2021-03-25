
@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12" style="margin-top:50px;">
            <div class="x_panel">

                <div class="x_title">
                    <h2>Order Details</h2>
                    <div class="clearfix"></div>
                </div>

                 <div>
                    @if (Session::has('message'))
                        <div class="alert alert-success">{{ Session::get('message') }}</div>
                    @endif @if (Session::has('error'))
                        <div class="alert alert-danger">{{ Session::get('error') }}</div>
                    @endif
                </div>

                <div class="col-md-4 col-sm-4 col-xs-12">
                    <table>
                        <tr>
                            <th>Invoice No : </th>
                            <td>{{$order->id}}</td>
                        </tr>

                        <tr>
                            <th>Invoice Date : </th>
                            <td>{{$order->created_at}}</td>
                        </tr>
                        <tr>
                            <th>Invoice Amount : </th>
                            <td> Rs.{{ number_format($order->amount,2,".",'') }}</td>
                        </tr>
                        <tr>
                            <th>Paid Amount : </th>
                            <td> Rs.{{ number_format($order->advance_amount,2,".",'') }}</td>
                        </tr>
                        @if ($order->discount > 0)
                            <tr>
                                <th>Discount : </th>
                                <td> Rs.{{ number_format($order->discount,2,".",'') }}</td>
                            </tr>                                    
                        @endif
                        <tr>
                            <th>Payable Amount : </th>
                            <td> Rs.{{ number_format(($order->amount-($order->advance_amount+$order->discount)),2,".",'') }}</td>
                        </tr>
                        <tr>
                            <th>Payment Status : </th>
                            <td>
                                @if ($order->payment_type == '1')
                                    Failed
                                @else
                                    Paid
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6 col-xs-6 col-sm-12" style="padding-top: 16px;">
                    <table class="table">
                        <thead>
                            <tr style="background-color: #0089ff;color:white ">
                                <th>#</th>
                                <th style="min-width: 125px;">Particulars</th>
                                <th>Quantity</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($order->services) && !empty($order->services) && (count($order->services)> 0))
                                @foreach ($order->services as $item)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ $item->job->jobCategory->name ?? ''}} -> {{ $item->job->subCategory->name ?? ''}}  -> {{ $item->job->lastCategory->third_level_category_name ?? ''}}</td>

                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->amount,2,".",'') }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="x_title">
                    <h2>Vendor Change Form</h2>
                    <div class="clearfix"></div>
                </div>

                <div>
                    <div class="x_content">
                        {{Form::open(['method' => 'post','route'=>'admin.vendor_change'])}}
                        <input type="hidden" name="order_id" id="order_id" value="{{$order->id}}">
                        <div class="form-group">
                            {{ Form::label('mobile', 'Vendor Mobile')}}
                            <input type="text" name="mobile" id="mobile" class="form-control" required>
                        </div>
                        <div id="info_div"></div>

                        <div class="form-group" style="display:none;" id="submit_btn">
                            {{ Form::submit('Confirm & Submit', array('class'=>'btn btn-success')) }}
                        </div>
                        <div class="form-group" style="display:block;" id="check_btn">
                            <button type="button" class="btn btn-primary" id="check_vendor">Check Vendor</button>
                        </div>
                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>

    <div class="clearfix"></div>
</div>
@endsection
@section('script')
    <script>        
        $(function(){
            $(document).on('click',"#check_vendor", function(){
                let mobile = $("#mobile").val();
                let order_id = $("#order_id").val();
                if (mobile.length == 10) {                    
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.vendor_check')}}",
                        method: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            mobile:mobile,
                            order_id:order_id,
                        },
                        success: function(data){
                            console.log(data);
                            if (data.status == true) {
                                $("#check_btn").hide();
                                $("#submit_btn").show();
                                var html = `
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" disabled value="${data.data.client.name}">
                                </div>
                                <div class="form-group">
                                    <label for="">Invoice Amount</label>
                                    <input type="text" class="form-control" disabled value="${data.data.amount}">
                                </div>
                                `;
                                $("#info_div").html(html);
                            }else{
                                $("#info_div").html(`<p class="alert alert-danger">${data.message}</p>`);
                            }
                        }
                    });
                }else{
                    $("#info_div").html(`<p class="alert alert-danger">Please enter 10 digit mobile number</p>`);
                }
            });
        });
    </script>
@endsection
