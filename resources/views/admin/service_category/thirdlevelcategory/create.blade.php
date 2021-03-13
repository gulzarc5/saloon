@extends('admin.template.admin_master')
@section('content')
<!-- page content -->
<div class="right_col" role="main">
    <div class="row">
        {{-- <div class="col-md-2"></div> --}}
        <div class="col-md-12" style="margin-top:50px;">
            <div class="x_panel">
                <div class="x_title">
                        <h2>Add Third Level Category</h2>
                    <div class="clearfix"></div>
                </div>
                    @if (Session::has('message'))
                    <div class="alert alert-success" >{{ Session::get('message') }}</div>
                    @endif
                    @if (Session::has('error'))
                    <div class="alert alert-danger">{{ Session::get('error') }}</div>
                    @endif
                <div class="x_content">
                        {{ Form::open(['method' => 'post','route'=>'level.store', 'enctype'=>'multipart/form-data']) }}
                        <div class="well" style="overflow: auto">
                            <div class="form-row">
                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="name">Select Service Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="" selected disabled>--Select Service Category--</option>
                                        @if (isset($service_categories) && !empty($service_categories))
                                            @foreach ($service_categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category') == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
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
                                    <label for="name">Select Sub Category</label>
                                    <select name="sub_category" id="sub_category" class="form-control">
                                        <option value="" selected disabled>--Select Sub Category--</option>
                                    </select>
                                    @if($errors->has('sub_category'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('sub_category') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}"  placeholder="Enter The Product Name">
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
                                </div>
                            </div>
                            
                            {{-- <div class="form-row mb-10">
                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <div class="form-inline">
                                        <br>
                                            <input type="checkbox" class="form-control" id="man" name="man" value="1" checked>
                                            <label for="man"> Man</label>
                                            <input type="checkbox" class="form-control" id="woman" name="woman" value="1" checked>
                                            <label for="woman"> Woman</label>
                                            <input type="checkbox" class="form-control" id="kids" name="kids" value="1" checked>
                                            <label for="kids"> Kids</label>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                        <div class="form-group">    
                            {{ Form::submit('Submit', array('class'=>'btn btn-success pull-right')) }}  
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>    
<!-- /page content -->
@endsection

@section('script')
    <script>
        $(function(){
            $(document).on('change',"#category", function(){
                let category_id = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('fetch_sub_category')}}",
                    method: "POST",
                    data: {category_id:category_id},
                    success: function(data){
                        $('#sub_category').html(data);
                    }
                });
            });
        });
    </script>
@endsection
