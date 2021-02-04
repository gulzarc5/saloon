@extends('admin.template.admin_master')
@section('content')
<!-- page content -->
<div class="right_col" role="main">
        <div class="row">
                {{-- <div class="col-md-2"></div> --}}
                <div class="col-md-12" style="margin-top:50px;">
                    <div class="x_panel">
                        <div class="x_title">
                            @if (isset($sub_category) && !empty($sub_category))
                                <h2>Edit Sub Category</h2>
                            @else
                                <h2>Add Sub Category</h2>
                            @endif
                            <div class="clearfix"></div>
                        </div>
                         @if (Session::has('message'))
                            <div class="alert alert-success" >{{ Session::get('message') }}</div>
                         @endif
                         @if (Session::has('error'))
                            <div class="alert alert-danger">{{ Session::get('error') }}</div>
                         @endif
                        <div class="x_content">
                            @if (isset($sub_category) && !empty($sub_category))
                                {{ Form::open(['method'=> 'PUT', 'route'=>['category.update', $sub_category], 'enctype'=>'multipart/form-data']) }}
                            @else
                                {{ Form::open(['method' => 'post','route'=>'category.store', 'enctype'=>'multipart/form-data']) }}
                            @endif
                                <div class="well" style="overflow: auto">
                                    <div class="form-row">
                                        <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                            <label for="name">Select Service Category</label>
                                            <select name="category" id="category" class="form-control">
                                                <option value="" selected disabled>--Select Service Category--</option>
                                                @if (isset($service_categories) && !empty($service_categories))
                                                    @foreach ($service_categories as $service_category)
                                                        @if (isset($sub_category) && !empty($sub_category))
                                                            <option value="{{ $service_category->id }}" {{ $sub_category->category_id == $service_category->id ? 'selected' : '' }}>{{ $service_category->name }}</option>
                                                        @else
                                                            <option value="{{ $service_category->id }}" {{ old('category') == $service_category->id ? 'selected' : '' }}>{{ $service_category->name }}</option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if($errors->has('category'))
                                                <span class="invalid-feedback" role="alert" style="color:red">
                                                    <strong>{{ $errors->first('category') }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" name="name" value="{{ isset($sub_category) ? $sub_category->name : old('name')}}"  placeholder="Enter The Product Name">
                                            @if($errors->has('name'))
                                                <span class="invalid-feedback" role="alert" style="color:red">
                                                    <strong>{{ $errors->first('name') }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                            <label for="image">Image</label>
                                            <input type="file" name="image" id="image" class="form-control">
                                            @if (isset($sub_category) && !empty($sub_category))
                                                <img src="{{ asset("admin/service_category/sub_category/thumb/".$sub_category->image) }}" alt="PHOTO" width="200">
                                            @endif
                                            @if($errors->has('image'))
                                                <span class="invalid-feedback" role="alert" style="color:red">
                                                    <strong>{{ $errors->first('image') }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="form-row mb-10">
                                        <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                            <div class="form-inline">
                                                <br>
                                                @if (isset($sub_category) && !empty($sub_category))
                                                    <input type="checkbox" class="form-control" id="man" name="man" value="{{ $sub_category->man }}" {{ $sub_category->man == 1 ? 'checked' : '' }}>
                                                    <label for="man"> Man</label>
                                                    <input type="checkbox" class="form-control" id="woman" name="woman" value="{{ $sub_category->woman }}" {{ $sub_category->woman == 1 ? 'checked' : '' }}>
                                                    <label for="woman"> Woman</label>
                                                    <input type="checkbox" class="form-control" id="kids" name="kids" value="{{ $sub_category->kids }}" {{ $sub_category->kids == 1 ? 'checked' : '' }}>
                                                    <label for="kids"> Kids</label>
                                                @else
                                                    <input type="checkbox" class="form-control" id="man" name="man" value="1" checked>
                                                    <label for="man"> Man</label>
                                                    <input type="checkbox" class="form-control" id="woman" name="woman" value="1" checked>
                                                    <label for="woman"> Woman</label>
                                                    <input type="checkbox" class="form-control" id="kids" name="kids" value="1" checked>
                                                    <label for="kids"> Kids</label>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">    
                                    @if (isset($sub_category) && !empty($sub_category))	            	
                                        {{ Form::submit('Update', array('class'=>'btn btn-success pull-right')) }}  
                                    @else
                                        {{ Form::submit('Submit', array('class'=>'btn btn-success pull-right')) }}  
                                    @endif
                                </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
        </div>
</div>    
<!-- /page content -->
@endsection


