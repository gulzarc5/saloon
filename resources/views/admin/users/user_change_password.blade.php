@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-6">
      <div class="x_panel">
            <div>
                @if (Session::has('message'))
                    <div class="alert alert-success" >{{ Session::get('message') }}</div>
                @endif
                @if (Session::has('error'))
                    <div class="alert alert-danger" >{{ Session::get('error') }}</div>
                @endif
            </div>
            <div class="x_content">               
                {{ Form::open(['method' => 'post','route'=>'admin.user_change_password'])}}                  
                    <div class="well" style="overflow: auto">
                        <center><h3>User Reset Password</h3></center>
                        <input type="hidden" name="user_id" value="{{$user->id}}">
                        <input type="hidden" name="request_id" value="{{$request_id}}">
                        <input type="hidden" name="user_type" value="{{$user_type}}">
                        <div class="form-row mb-10">
                            <div class="col-md-12 col-sm-12 col-xs-12 mb-3">                                
                                <div class="form-row">
                                    <div class="col-sm-12">
                                        <label class="control-label">User Type</label>
                                        <input type="text" class="form-control" disabled value="{{ $user_type == '1' ? 'Customer' : 'Client'}}">
                                    </div>
                                </div> 
                                <div class="form-row">
                                    <div class="col-sm-12">
                                        <label class="control-label">User Name</label>
                                        <input type="text" class="form-control" disabled value="{{ $user->name }}">
                                    </div>
                                </div> 
                                <div class="form-row">
                                    <div class="col-sm-12">
                                        <label class="control-label">User Mobile</label>
                                        <input type="text" class="form-control" disabled value="{{ $user->mobile }}">
                                    </div>
                                </div> 
                                <div class="form-row">
                                    <div class="col-sm-12">
                                        <label class="control-label">New Password</label>
                                        <input type="password" name="new_password"  id="sheigth" class="form-control" placeholder="New Password" required="">
                                        @if($errors->has('new_password'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('new_password') }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div> 
                                <div class="form-row">
                                    <div class="col-sm-12">
                                        <label class="control-label">Re Enter Password</label>
                                        <input type="password" name="confirm_password"  id="sheigth" class="form-control" placeholder="Re Enter New Password" required="">
                                        @if($errors->has('confirm_password'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('confirm_password') }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>                  
                    <div class="form-group">    	            	
                        {{ Form::submit('Submit', array('class'=>'btn btn-success')) }}  
                        <a onclick="window.close()" class="btn btn-danger">Close</a>
                    </div>
                {{ Form::close() }}
            </div>
      </div>
    </div>
  </div>
</div>
 @endsection