
@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="margin-top:50px;">
            <div class="x_panel">

                <div class="x_title">
                    <h2>Invoice Setting</h2>
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
                        {{ Form::open(['method' => 'post','route'=>'admin.invoiceUpdate','enctype'=>'multipart/form-data']) }}

                        <div class="well" style="overflow: auto">
                            <div class="form-row mb-10">
                                <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                  <label for="address">Company Address <span><b style="color: red"> * </b></span></label>
                                  <textarea class="form-control" name="address"  placeholder="Enter Company Address" required>{{$invoice->address}}</textarea>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                    <label for="phone">Phone Numbers<span><b style="color: red"> * </b></span></label>
                                    <input type="text" class="form-control" name="phone"  placeholder="+91-8659824515 / +91-6589251457" required value="{{$invoice->phone}}">
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                    <label for="gst">GST</label>
                                    <input type="text" class="form-control" name="gst"  placeholder="Enter GST Number"  value="{{$invoice->gst}}">
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                    <label for="email">Email<span><b style="color: red"> * </b></span></label>
                                    <input type="text" class="form-control" name="email"  placeholder="Enter Email Id" required value="{{$invoice->email}}">
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                    <label for="note1">First Note<span><b style="color: red"> * </b></span></label>
                                    <textarea class="form-control" name="note1"  placeholder="Enter Invoice Note" required>{{$invoice->note1}}</textarea>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                  <label for="note2">Second Note<span><b style="color: red"> * </b></span></label>
                                  <textarea class="form-control" name="note2"  placeholder="Enter Invoice Note" required>{{$invoice->note2}}</textarea>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                  <label for="note3">Third Note<span><b style="color: red"> * </b></span></label>
                                  <textarea class="form-control" name="note3"  placeholder="Enter Invoice Note" required>{{$invoice->note3}}</textarea>
                                </div>

                                <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                  <label for="image">Invoice Banner</label>
                                  <input type="file" class="form-control" name="image">
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
