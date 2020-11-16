@extends('admin.template.admin_master')
@section('content')
<!-- page content -->
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12" style="margin-top:50px;">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Edit Service Category</h2>
                    <div class="clearfix"></div>
                </div>
                    @if (Session::has('message'))
                    <div class="alert alert-success" >{{ Session::get('message') }}</div>
                    @endif
                    @if (Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                    @endif
                <div class="x_content">
                    {{ Form::open(['method' => 'post','route'=>'admin.update_service_category', 'enctype'=>'multipart/form-data']) }}
                    <input type="hidden" name="id" value="{{ $job_category->id }}">
                        <div class="well" style="overflow: auto">
                            <div class="form-row mb-10">
                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" name="name" value="{{$job_category->name}}"  placeholder="Enter The Product Name">
                                    @if($errors->has('name'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="image">Image</label>
                                    <input type="file" name="image" id="image" class="form-control">
                                    @if($errors->has('image'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('image') }}</strong>
                                        </span>
                                    @enderror
                                    <div>
                                        <img src="{{ asset('admin/service_category/thumb/'.$job_category->image) }}" alt="photo" width="300" height="300">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <div class="form-inline">
                                        <input type="checkbox" class="form-control" id="man" name="man" value="{{ $job_category->man }}" {{ $job_category->man == 1 ? 'checked' : '' }}>
                                        <label for="man"> Man</label>
                                        <input type="checkbox" class="form-control" id="woman" name="woman" value="{{ $job_category->woman }}" {{ $job_category->woman == 1 ? 'checked' : '' }}>
                                        <label for="woman"> Woman</label>
                                        <input type="checkbox" class="form-control" id="kids" name="kids" value="{{ $job_category->kids }}" {{ $job_category->kids == 1 ? 'checked' : '' }}>
                                        <label for="kids"> Kids</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::submit('Update', array('class'=>'btn btn-success pull-right')) }}
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->
@endsection


