@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="x_panel">
        <div class="x_title">
          <h2>Contact Mail List</h2>
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
                    <th>Booking date</th>
                    <th>Main Category</th>
                    <th>Sub Category</th>
                    <th>Third Category</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Message</th>
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
            ajax: "{{ route('admin.contact_mail_list_ajax') }}",
            columns: [
                {data: 'booking_date', name: 'booking_date'},
                {data: 'category.name', name: 'category.name',searchable: true,rander:function(data,type,raw){
                  if (raw.category) {
                    return raw.category.name
                  } else {
                    return null
                  }
                }},
                {data: 'sub_category.name', name: 'subCategory.name',searchable: true,render:function(data,type,raw){
                  if (raw.sub_category) {
                    return raw.sub_category.name
                  }else{
                    return null;
                  }
                }},
                {data: 'third_category.third_level_category_name', name: 'thirdCategory.third_level_category_name',searchable: true,render:function(data,type,raw){
                  if (raw.third_category) {
                    return raw.third_category.third_level_category_name
                  }else{
                    return null;
                  }
                }},
                {data: 'name', name: 'name',searchable: true},
                {data: 'mobile', name: 'mobile', orderable: false, searchable: false},
                {data: 'message', name: 'message', orderable: false, searchable: false},
            ]
        });

    });
  </script>

 @endsection
