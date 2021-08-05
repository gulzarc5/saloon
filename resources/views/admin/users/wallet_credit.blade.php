
@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="margin-top:50px;">
            <div class="x_panel">

                <div class="x_title">
                    <h2>Wallet Credit</h2>
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
                        {{ Form::open(['method' => 'post','route'=>'admin.user_wallet_credit_submit']) }}

                        <input type="hidden" name="wallet_id" value="{{$wallet->id}}">
                        <div class="form-group">
                            {{ Form::label('name', 'User Name')}}
                            <input type="text" class="form-control" name="name" value="{{$wallet->user->name ?? null}}" disabled>
                        </div>
                        <div class="form-group">
                            {{ Form::label('amount', 'Wallet Amount')}}
                            <input type="text" class="form-control"  value="{{$wallet->amount}}" disabled>
                        </div>
                        <div class="form-group">
                            {{ Form::label('amount', 'Credit Amount')}}
                            <input type="number" class="form-control" name="amount" >
                            @if($errors->has('amount'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('amount') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            {{ Form::label('comment', 'Comment')}}
                            <textarea class="form-control" name="comment"></textarea>
                            @if($errors->has('comment'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('comment') }}</strong>
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
