@extends('admin.template.admin_master')

@section('content')

<link rel="stylesheet" href="{{asset('admin/dialog_master/simple-modal.css')}}">
<div class="right_col" role="main">
    <div class="x_panel">
        <div class="x_title">
          <h2>Refunds</h2>
          <div class="clearfix"></div>
        </div>
        @if (Session::has('message'))
            <div class="alert alert-success" >{{ Session::get('message') }}</div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif
        <div class="x_content">
          <div class="table-responsive">
            <table class="table table-striped jambo_table bulk_action">
                <thead>
                    <tr class="headings" style="font-size: 10.5px;">
                        <th class="column-title">#</th>
                        <th class="column-title">OrderID</th>
                        <th class="column-title">Customer Name</th>
                        <th class="column-title">Bank Name</th>
                        <th class="column-title">Account No.</th>
                        <th class="column-title">IFSC</th>
                        <th class="column-title">Amount</th>
                        <th class="column-title">Status</th>
                        <th class="column-title">Request Date</th>
                        <th class="column-title" style="min-width: 185px;">Action</th>
                    </tr>
                </thead>

                <tbody>
                  @php
                      $refund_count = 1;
                  @endphp
                  @forelse ($refunds as $item)
                      <tr>
                        <td>{{$refund_count++}}</td>
                        <td>{{$item->order_id}}</td>
                        <td>{{ isset($item->order->customer->name) ? $item->order->customer->name : ''}}</td>
                        <td>{{ isset($item->account->bank_name) ? $item->account->bank_name : ''}}</td>
                        <td>{{ isset($item->account->ac_no) ? $item->account->ac_no : ''}}</td>
                        <td>{{ isset($item->account->ifsc) ? $item->account->ifsc : ''}}</td>
                        <td>{{$item->amount}}</td>
                        <td id="status{{$item->id}}">
                          @if ($item->refund_status == '2')
                              <button class="btn btn-xs btn-primary">Done</button>
                          @else
                              <button class="btn btn-xs btn-warning">Pending</button>
                          @endif
                        </td>
                        <td>{{ $item->created_at }}</td>
                        <td id="action{{ $item->id }}">
                          @if ($item->refund_status == '2')
                              <button class="btn btn-xs btn-primary" disabled>Refunded Done</button>
                          @else
                              <button class="btn btn-xs btn-info" onclick="openModal({{ $item->id }},'Are Your Sure To Refunded The Amount ??') ">Refunded</button>
                          @endif
                        </td>
                      </tr>
                  @empty
                    <tr>
                      <td colspan="10" align="center">No Refunds Found</td>
                    </tr>
                  @endforelse
                </tbody>
            </table>
          </div>

        </div>
    </div>
</div>
@endsection
@section('script')
  <script src="{{asset('admin/dialog_master/simple-modal.js')}}"></script>

  <script>
     async function openModal(refund_id,msg) {
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
                      url:"{{url('admin/refund/update/')}}"+"/"+refund_id,

                      beforeSend: function() {
                            // setting a timeout
                            $("#action"+refund_id).html('<i class="fa fa-spinner fa-spin"></i>');
                            $("#status"+refund_id).html('<i class="fa fa-spinner fa-spin"></i>');
                        },
                      success:function(data){
                        if (data) {
                          $("#status"+refund_id).html('<button class="btn btn-xs btn-primary">Done</button>');
                          $("#action"+refund_id).html(`<button class="btn btn-xs btn-primary" disabled>Refunded Done</button>`);
                        }
                      }
                    });
                }
            } catch(err) {
            console.log(err);
            }

        }
  </script>
@endsection