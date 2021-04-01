
@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="margin-top:50px;">
            <div class="x_panel">

                <div class="x_title">
                    
                    <h2>Send Message To User</h2>
                    
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

                        {{ Form::open(['method' => 'post','route'=>'admin.message_send']) }}


                        <div class="form-group">
                            {{ Form::label('title', 'Message Title')}}
                            <input type="text"  name="title" class="form-control">
                            @if($errors->has('title'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            {{ Form::label('user_type', 'User Type')}}
                            <select name="user_type"  class="form-control" id="user_type">
                                <option value="">Select User</option>
                                <option value="C">Customer</option>
                                <option value="V">Vendor</option>
                            </select>
                            @if($errors->has('user_type'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('user_type') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group" style="display:none" id="vendor_type">
                            {{ Form::label('vendor_type', 'Vendor Type')}}
                            <select name="vendor_type"  class="form-control" >
                                <option value="">Select Vendor Type</option>
                                <option value="S">Salon</option>
                                <option value="F">Freelauncer</option>
                            </select>
                            @if($errors->has('vendor_type'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('vendor_type') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            {{ Form::label('message', 'Message')}}
                            <textarea  name="message" class="form-control"></textarea>
                            @if($errors->has('message'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('message') }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-group">                           
                            {{ Form::submit('Submit', array('class'=>'btn btn-success')) }}                           
                            <a href="{{route('admin.message_list')}}" class="btn btn-warning">Back</a>
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
    $(document).ready(function() {
        $('#user_type').change(function(){
            let type = $(this).val();
            if (type == 'V') {
                $("#vendor_type").show();
            }else{
                $("#vendor_type").hide();
            }
        });
    });
</script>
@endsection