@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="x_panel">
        <div class="x_title">
          <h2>Wallet History</h2>
          <div class="clearfix"></div>
        </div>
        @if (Session::has('message'))
            <div class="alert alert-success" >{{ Session::get('message') }}</div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif
        <div class="x_content">
            <table id="customer_list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Sl. No</th>
                    <th>Date</th>
                    <th>Transaction Type</th>
                    <th>Amount</th>
                    <th>Wallet Amount</th>
                    <th>Comment</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($wallet_history as $item)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $item->created_at }}</td>
                      <td>
                        @if ($item->transaction_type == '1')
                          Credit
                        @else
                          Debit
                        @endif
                      </td>
                      <td>{{ $item->amount }}</td>
                      <td>{{ $item->total_amount }}</td>
                      <td>{{ $item->comment }}</td>
                    </tr>
                  @empty
                  @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
 @endsection


 @section('script')

  <script type="text/javascript">
    $(function () {
      var table = $('#customer_list').DataTable();
    });
  </script>

 @endsection

