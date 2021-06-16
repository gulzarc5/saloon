
@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12" style="margin-top:50px;">
            <div class="x_panel">

                <div class="x_title">
                    <h2>App Setting Vendor</h2>
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
                        {{ Form::open(['method' => 'post','route'=>'admin.app_setting_submit']) }}

                        <div class="well" style="overflow: auto">
                            <div class="form-row mb-10">
                                <input type="hidden" name="set_id" value="{{$description->id}}">
                                <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                    
                                    <label for="about_us">
                                        About Us <span><b style="color: red"> * </b></span>
                                        @if($errors->has('about_us'))
                                            <span class="invalid-feedback" role="alert" style="color:red">
                                                <strong>{{ $errors->first('about_us') }}</strong>
                                            </span>
                                        @enderror
                                    </label>
                                    <textarea class="form-control" name="about_us" required>{{$description->about_us}}</textarea>
                                  
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                    <label for="refund">
                                        Refund And Cancellation Policy <span><b style="color: red"> * </b></span>
                                        @if($errors->has('refund'))
                                            <span class="invalid-feedback" role="alert" style="color:red">
                                                <strong>{{ $errors->first('refund') }}</strong>
                                            </span>
                                        @enderror
                                    </label>
                                    <textarea class="form-control" name="refund" required>{{$description->refund_cancellation}}</textarea>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                    <label for="disclaimers">
                                        Disclaimers <span><b style="color: red"> * </b></span>
                                        @if($errors->has('disclaimers'))
                                            <span class="invalid-feedback" role="alert" style="color:red">
                                                <strong>{{ $errors->first('disclaimers') }}</strong>
                                            </span>
                                        @enderror
                                    </label>
                                    <textarea class="form-control" name="disclaimers" required>{{$description->disclaimers}}</textarea>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                    <label for="privacy_policy">
                                        Privacy Policy <span><b style="color: red"> * </b></span>
                                        @if($errors->has('privacy_policy'))
                                            <span class="invalid-feedback" role="alert" style="color:red">
                                                <strong>{{ $errors->first('privacy_policy') }}</strong>
                                            </span>
                                        @enderror
                                    </label>
                                    <textarea class="form-control" name="privacy_policy" required>{{$description->privacy_policy}}</textarea>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                    <label for="tc">
                                        Terms And Conditions <span><b style="color: red"> * </b></span>
                                        @if($errors->has('tc'))
                                            <span class="invalid-feedback" role="alert" style="color:red">
                                                <strong>{{ $errors->first('tc') }}</strong>
                                            </span>
                                        @enderror
                                    </label>
                                    <textarea class="form-control" name="tc" required>{{$description->tc}}</textarea>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                    <label for="faq">
                                        Frequently Asked Questions <span><b style="color: red"> * </b></span>
                                        @if($errors->has('faq'))
                                            <span class="invalid-feedback" role="alert" style="color:red">
                                                <strong>{{ $errors->first('faq') }}</strong>
                                            </span>
                                        @enderror
                                    </label>
                                    <textarea class="form-control" name="faq" required>{{$description->faq}}</textarea>
                                </div>
                            </div>
                        </div>


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

    <script src="{{ asset('admin/ckeditor4/ckeditor.js')}}"></script>
    <script>
        CKEDITOR.replace( 'about_us', {
            height: 100,
        });
        CKEDITOR.replace( 'refund', {
            height: 100,
        });
        CKEDITOR.replace( 'disclaimers', {
            height: 100,
        });
        CKEDITOR.replace( 'privacy_policy', {
            height: 100,
        });
        CKEDITOR.replace( 'tc', {
            height: 100,
        });
        CKEDITOR.replace( 'faq', {
            height: 100,
        });
    </script>

 @endsection
