
@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="margin-top:50px;">
            <div class="x_panel">

                <div class="x_title">
                    @if(isset($state) && !empty($state))
                        <h2>Update State</h2>
                    @else
                        <h2>Add New State</h2>
                    @endif
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
                        @if(isset($state) && !empty($state))
                            {{Form::model($state, ['method' => 'put','route'=>['admin.update_state',$state->id]])}}
                        @else
                            {{ Form::open(['method' => 'post','route'=>'admin.insert_state']) }}
                        @endif

                        <div class="form-group">
                            {{ Form::label('name', 'State Name')}}
                            {{ Form::text('name',null,array('class' => 'form-control','placeholder'=>'Enter State name')) }}
                            @if($errors->has('name'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            @if(isset($state) && !empty($state))
                                {{ Form::submit('Save', array('class'=>'btn btn-success')) }}
                                <button onclick="window.close()" class="btn btn-danger">Close</button>
                            @else
                                {{ Form::submit('Submit', array('class'=>'btn btn-success')) }}
                                <a href="{{route('admin.state')}}" class="btn btn-warning">Back</a>
                            @endif

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
