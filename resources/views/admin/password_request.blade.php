@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="x_panel">
        <div class="x_title">
          <h2>Password Request List</h2>
          <div class="clearfix"></div>
        </div>
        @if (Session::has('message'))
            <div class="alert alert-success" >{{ Session::get('message') }}</div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif
        <div class="x_content">
            <table id="freelauncer_list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Sl. No</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>User Type</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('script')

  <script type="text/javascript">
      $(function () {

        var table = $('#freelauncer_list').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.password_request_ajax') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name',searchable: true},
                {data: 'mobile', name: 'mobile',searchable: true},
                {data: 'user_type', name: 'user_type', render:function(data, type, row){
                  if (row.user_type == '1') {
                    return "<button class='btn btn-info'>Customer</a>"
                  }else if(row.user_type == '2'){
                    return "<button class='btn btn-primary'>Freelauncer</a>"
                  }else{
                    return "<button class='btn btn-success'>Salon Shop</a>"
                  }
                }},
                {data: 'created_at', name: 'created_at',searchable: true},
                {data: 'status', name: 'status', render:function(data, type, row){
                  if (row.status == '1') {
                    return "<button class='btn btn-info'>New</a>"
                  }else{
                    return "<button class='btn btn-success'>Generated</a>"
                  }
                }},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

    });
  </script>

 @endsection
