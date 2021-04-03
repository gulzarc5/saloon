@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" id="printable">
              <?php
                  // if (isset($_GET['msg'])) {
                  //   showMessage($_GET['msg']);
                  // }
              ?>
                <div class="x_title" style="border-bottom: white;">

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        {{--///////////////////// Company Address ///////////////////////--}}
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <img src="{{asset('logo/logo.png')}}" style="height: 45px;margin-bottom: 12px;margin-top: 10px;">
                            {{-- <b style="font-size: 35px;color: #0194ea;">Salon</b>
                            <b style="font-size: 35px;color: #262161;">ease</b> --}}
                            <table>
                                <tr>
                                <th>Address : </th>
                                    <td>{{$invoice_setting->address}}</td>
                                </tr>

                                <tr>
                                <th>Phone : </th>
                                    <td>{{$invoice_setting->phone}}</td>
                                </tr>
                                @if (!empty($invoice_setting->gst))
                                    <tr>
                                        <th>GST No : </th>
                                        <td>{{$invoice_setting->gst}}</td>
                                    </tr>
                                @endif
                                <tr>
                                <th>Email Id : </th>
                                    <td>{{$invoice_setting->email}}</td>
                                </tr>
                            </table>
                        </div>

                        
                        {{--///////////////// Invoice Details ////////////////////--}}
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <span style="font-size: 38px;color: black;font-weight: bold;">INVOICE</span>
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

                        {{--///////////////// Invoice Details ////////////////////--}}
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <span style="    font-size: 34px;color: #0493f8ab;font-weight: bold;">Vendor Details</span>
                            @if($order->client()->exists())
                                <table>
                                    <tr>
                                        <th>Name : </th>
                                        <td>{{$order->client->name ?? null}}</td>
                                    </tr>

                                    <tr>
                                        <th>Mobile : </th>
                                        <td>{{$order->client->mobile ?? null}}</td>
                                    </tr>
                                    <tr>
                                        <th>Email : </th>
                                        <td>{{$order->client->email ?? null}}</td>
                                    </tr>
                                    <tr>
                                        <th>Service City : </th>
                                        <td>{{$order->client->serviceCity->name ?? null}}</td>
                                    </tr>
                                    <tr>
                                        <th>Client Type : </th>
                                        <td>
                                            @if ($order->client->clientType == '1')
                                                Freelauncer
                                            @else
                                                ShopKeeper
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Address : </th>
                                        <td>{{$order->client->address ?? null}},{{$order->client->city ?? null}},{{$order->client->state ?? null}} - {{$order->client->pin ?? null}}</td>
                                    </tr>
                                </table>
                            @endif
                        </div>
                    </div>

                    {{--//////////////////// Shipping Details And Billing Details ///////////////////--}}
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="col-md-12 col-xs-12 col-sm-12" style="padding-top: 16px;">
                            <table class="table">
                            <thead>
                                <tr style="background-color: #0089ff;color:white ">
                                <th style="min-width: 125px;">Billing Info</th>
                                <th>Customer Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <th>Name :</th>
                                            <td>{{$order->customer->name ?? null}}</td>
                                        </tr>
                                        <tr>
                                            <th>Email :</th>
                                            <td>{{$order->customer->email ?? null}}</td>
                                        </tr>
                                        <tr>
                                            <th>Mobile :</th>
                                            <td>{{$order->customer->mobile ?? null}}</td>
                                        </tr>
                                        <tr>
                                            <th>State :</th>
                                            <td>{{$order->customer->state ?? null}}</td>
                                        </tr>
                                        <tr>
                                            <th>City :</th>
                                            <td>{{$order->customer->city ?? null}}</td>
                                        </tr>
                                        <tr>
                                            <th>Address :</th>
                                            <td>{{$order->customer->address ?? null}}</td>
                                        </tr>
                                    </table>
                                </td>

                                <td>

                                    <table>
                                        <tr>
                                            <th>Name :</th>
                                            <td>{{$order->address->name ?? null}}</td>
                                        </tr>
                                        <tr>
                                            <th>Email :</th>
                                            <td>{{$order->address->email ?? null}}</td>
                                        </tr>
                                        <tr>
                                            <th>Mobile :</th>
                                            <td>{{$order->address->mobile ?? null}}</td>
                                        </tr>
                                        <tr>
                                            <th>State :</th>
                                            <td>{{$order->address->state ?? null}}</td>
                                        </tr>
                                        <tr>
                                            <th>City :</th>
                                            <td>{{$order->address->city ?? null}}</td>
                                        </tr>
                                        <tr>
                                            <th>Address :</th>
                                            <td>{{$order->address->address ?? null}}</td>
                                        </tr>
                                    </table>
                                </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                {{-- ////////////////// Order Details /////////////////////////////--}}
                <div class="x_content table-responsive">
                    <div class="col-md-12 col-xs-12 col-sm-12" style="padding-top: 16px;">
                        <table class="table">
                            <thead>
                                <tr style="background-color: #0089ff;color:white ">
                                    <th>#</th>
                                    <th style="min-width: 125px;">Particulars</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($orderDetails) && !empty($orderDetails) && (count($orderDetails)> 0))
                                    @foreach ($orderDetails as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            @if ($item->job->product_type == '1')
                                                <td>{{ $item->job->jobCategory->name ?? ''}} -> {{ $item->job->subCategory->name ?? ''}}  -> {{ $item->job->lastCategory->third_level_category_name ?? ''}}</td>
                                            @else
                                                <td>
                                                    {{$item->job->description}}
                                                </td>
                                            @endif

                                            <td>{{ number_format($item->amount,2,".",'') }}</td>
                                        </tr>
                                    @endforeach
                                @endif

                                <tr>
                                    <td align='right' colspan='2'>Sub Total : </td>
                                    <td>{{ number_format($order->amount,2,".",'') }}</td>
                                </tr>
                                @if ($order->advance_amount > 0)
                                    <tr>
                                        <td  align='right' colspan='2'>Advance Paid : (-) </td>
                                        <td>{{ number_format($order->advance_amount,2,".",'') }}</td>
                                    </tr>
                                @endif
                                @if ($order->discount > 0)
                                    <tr>
                                        <td  align='right' colspan='2'>Discount : (-) </td>
                                        <td>{{ number_format($order->discount,2,".",'') }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td  align='right' colspan='2'>Net Payable Amount : </td>
                                    <td>{{ number_format(($order->amount-($order->advance_amount+$order->discount)),2,".",'') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-12 col-xs-12 col-sm-12">
                        <div class="col-md-5 col-xs-5 col-sm-5">
                        <table class="table">
                            <thead>
                            <tr style="background-color: #0089ff;color:white ">
                                <td>Notes</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td> * {{$invoice_setting->note1}}</td>
                            </tr>
                            <tr>
                                <td> * {{$invoice_setting->note2}}</td>
                            </tr>
                            <tr>
                                <td> * {{$invoice_setting->note3}} </td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                        <div class="col-md-7 col-xs-7 col-sm-7">
                        <table class="table">
                            <thead>
                            <tr>
                                <td style=" text-align: center;">
                                <b style="color: #00adff;font-size: 25px;">Thanks</b><br>
                                <b>for shopping with us</b>
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td> <img src="{{asset('images/'.$invoice_setting->image.'')}}" style="height: 169px;width: 543px;"></td>
                            </tr>

                            </tbody>
                        </table>
                        </div>

                        <div class="col-md-12 col-xs-12 col-sm-12">
                        <button class="btn btn-info" id="print-btn" onclick="printDiv()">Print</button>
                            <a class="btn btn-danger" onclick="window.close()" id="backprint">Close</a>
                        </div>
                    </div>
                    <div id="thanks_msg"></div>
            </div>
          </div>
        </div>
      </div>
</div>


 @endsection

@section('script')

<script type="text/javascript">
    function printDiv() {
       var printContents = document.getElementById("printable").innerHTML;
       var originalContents = document.body.innerHTML;

       document.body.innerHTML = printContents;
       // document.getElementById("thanks_msg").innerHTML = "Thanks For Shopping With Us";

       //document.getElementById("backprint").hide();
       element = document.getElementById('backprint');
       element.style.display = "none";

        element = document.getElementById('print-btn');
       element.style.display = "none";

       window.print();

       element.style.display = "";
       document.getElementById("thanks_msg").innerHTML ="";
       document.body.innerHTML = originalContents;
    }
  </script>

 @endsection
