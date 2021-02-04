
@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="margin-top:50px;">
            <div class="x_panel">

                <div class="x_title">
                    <h2>Admin Commission Edit</h2>                   
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
                        {{Form::model($commission, ['method' => 'put','route'=>['admin.admin_commission_update',$commission->id]])}}
                       

                        <div class="form-group">
                            {{ Form::label('from_amount', 'From Amount')}}
                            <input type="number" value="{{$commission->from_amount}}" name="from_amount" class="form-control">
                            @if($errors->has('from_amount'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('from_amount') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            {{ Form::label('to_amount', 'To Amount')}}
                            <input type="number" value="{{$commission->to_amount}}" name="to_amount" class="form-control">
                            @if($errors->has('to_amount'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('to_amount') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            {{ Form::label('charge_amount', 'Commission Percentage') }}
                            <input type="number"  value="{{$commission->charge_amount}}" name="charge_amount" class="form-control">
                            @if($errors->has('charge_amount'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('charge_amount') }}</strong>
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
