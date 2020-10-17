@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="x_panel">
        <div class="x_title">
          <h2>State List</h2>
          <h2 style="float: right"><a href="{{route('admin.add_state')}}" class="btn btn-sm btn-warning">Add New</a></h2>
          <div class="clearfix"></div>
        </div>
        @if (Session::has('message'))
            <div class="alert alert-success" >{{ Session::get('message') }}</div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif
        <div class="x_content">
            <table id="state_list" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Sl. No</th>
                    <th>Name</th>
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

        var table = $('#state_list').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.state_list_ajax') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name',searchable: true},
                {data: 'status', name: 'status', render:function(data, type, row){
                  if (row.status == '1') {
                    return "<button class='btn btn-info'>Enable</a>"
                  }else{
                    return "<button class='btn btn-danger'>Disabled</a>"
                  }
                }},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

    });
  </script>

 @endsection
