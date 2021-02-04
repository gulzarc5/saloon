
@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="margin-top:50px;">
            <div class="x_panel">

                <div class="x_title">
                    <h2>Coupon Edit</h2>                   
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
                        {{Form::model($coupon, ['method' => 'put','route'=>['admin.coupon_update',$coupon->id]])}}
                       

                        <div class="form-group">
                            {{ Form::label('name', 'Coupon Name')}}
                            <input type="text" value="{{$coupon->name}}" disabled class="form-control">
                        </div>
                        <div class="form-group">
                            {{ Form::label('type', 'Coupon Type')}}
                            <input type="text" value="{{$coupon->type == '1' ? 'New User' : 'Old User'}}" disabled class="form-control">
                        </div>
                        <div class="form-group">
                            {{ Form::label('amount', 'Coupon Type')}}
                            <input type="number" name="amount" value="{{$coupon->amount}}" name="amount" class="form-control">
                            @if($errors->has('amount'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('amount') }}</strong>
                                </span>
                            @enderror
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
