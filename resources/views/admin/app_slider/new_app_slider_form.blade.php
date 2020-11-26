
@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="margin-top:50px;">
            <div class="x_panel">

                <div class="x_title">
                    <h2>Add New App Slider</h2>
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

                        {{ Form::open(['method' => 'post','route'=>'admin.insert_app_slider','enctype'=>'multipart/form-data']) }}
                        <div class="well" style="overflow:auto">
                            <div class="well" style="overflow: auto" id="image_div">
                                <div class="form-row mb-10">
                                    <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                        <label for="size">Images <span><b style="color: red"> *  Image Dimension Should Be (1920x504) </b></span></label>
                                        <input type="file" name="images[]" class="form-control" multiple required>
                                        @if($errors->has('images'))
                                            <span class="invalid-feedback" role="alert" style="color:red">
                                                <strong>{{ $errors->first('images') }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::submit('Submit', array('class'=>'btn btn-success')) }}
                            <a href="{{route('admin.app_slider_list')}}" class="btn btn-warning">Back</a>
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

