
@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="margin-top:50px;">
            <div class="x_panel">

                <div class="x_title">
                    <h2>Add New Offer Salon</h2>
                    <div class="clearfix"></div>
                </div>

                 <div>
                    @if (Session::has('message'))
                        <div class="alert alert-success">{{ Session::get('message') }}</div>
                    @endif @if (Session::has('error'))
                        <div class="alert alert-danger">{{ Session::get('error') }}</div>
                    @endif
                </div>

                <div>
                    <div class="x_content">
                        {{Form::open(['method' => 'post','route'=>'admin.insert_offer_salon'])}}  
                        
                        <input type="hidden" name="offer_id" value="{{$offer_id}}">

                        <div class="form-group" >
                            {{ Form::label('salon_mobile', 'Salon Mobile')}}
                            <input type="number" id="salon_mobile" name="salon_mobile"  class="form-control">
                            @if($errors->has('salon_mobile'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('salon_mobile') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div id="salon_info"></div>

                        <div class="form-group">
                            {{ Form::submit('Submit', array('class'=>'btn btn-success')) }}
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
            $(document).on('blur',"#salon_mobile", function(){
                var mobile = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{url('admin/offer/salon/data/fetch/')}}"+"/"+mobile,
                    method: "GET",
                    success: function(data){
                        if (data) {
                            var html = `<div class="form-group" >
                            <label for="name">Name</label>
                            <input type="text" value="${data.name}" disabled class="form-control">
                        </div>
                        <div class="form-group" >
                            <label for="name">Mobile</label>
                            <input type="text" value="${data.mobile}" disabled class="form-control">
                        </div>`;
                        $("#salon_info").html(html);
                        }else{
                            $("#salon_info").html(`<div style="text-align: center;color: red;font-size: 19px;">No Salon Found</div>`);
                        }
                    }
                });
            });
        });
    </script>
@endsection
