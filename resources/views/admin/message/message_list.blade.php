@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
    	<div class="col-md-12 col-xs-12 col-sm-12" style="margin-top:50px;">
    	    <div class="x_panel">

    	        <div class="x_title">
                    <h2>Messages List</h2>
                    <a class="btn btn-sm btn-info" style="float: right" href="{{ route('admin.message_send_form') }}">Send Message</a>
    	            <div class="clearfix"></div>
    	        </div>
    	        <div>
    	            <div class="x_content">
                        <table id="message" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th>Sl</th>
                              <th>Title</th>
                              <th>Message</th>
                              <th>User Type</th>
                              <th>Vendor Type</th>
                            </tr>
                          </thead>
                          <tbody></tbody>
                        </table>
    	            </div>
    	        </div>
    	    </div>
    	</div>
    </div>
	</div>


 @endsection

@section('script')

<script type="text/javascript">
  $(function () {
    var table = $('#message').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.message_list_ajax') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'title', name: 'title',searchable: true},
            {data: 'message', name: 'message',searchable: true},
            {data: 'type', name: 'type', render:function(data, type, row){
              if (row.type == 'C') {
                return "<button class='btn btn-info'>Customer</a>"
              }else{
                return "<button class='btn btn-primary'>Vendor</a>"
              }
            }},
            {data: 'vendor_type', name: 'vendor_type', render:function(data, type, row){
              if (row.vendor_type == 'F') {
                return "<button class='btn btn-info'>Freelauncer</a>"
              }else if (row.vendor_type == 'S') {
                return "<button class='btn btn-success'>Salon</a>"
              }else if(row.type == 'V'){
                return "<button class='btn btn-primary'>All</a>"
              }else{
                return "=="
              }
            }},
        ]
    });
  });
</script>

 @endsection
