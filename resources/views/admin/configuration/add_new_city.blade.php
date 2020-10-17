
@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="margin-top:50px;">
            <div class="x_panel">

                <div class="x_title">
                    @if(isset($city) && !empty($city))
                        <h2>Update City</h2>
                    @else
                        <h2>Add New City</h2>
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
                        @if(isset($city) && !empty($city))
                            {{Form::model($city, ['method' => 'put','route'=>['admin.update_city',$city->id]])}}
                        @else
                            {{ Form::open(['method' => 'post','route'=>'admin.insert_city']) }}
                        @endif

                        <div class="form-group">
                            <label for="state_id">Select State</label>
                            <select class="form-control size" name="state_id">
                                <option value="">Please Select State</option>
                                @if(isset($state) && !empty($state))
                                    @foreach ($state as $item)
                                        @if(isset($city) && !empty($city))
                                            <option value="{{$item->id}}" {{$item->id == $city->state_id ? 'selected' : ''}}>{{$item->name}}</option>
                                        @else
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endif
                                    @endforeach
                                @else
                                @endif
                            </select>
                            @if($errors->has('state_id'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('state_id') }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            {{ Form::label('name', 'City Name')}}
                            {{ Form::text('name',null,array('class' => 'form-control','placeholder'=>'Enter City name')) }}
                            @if($errors->has('name'))
                                <span class="invalid-feedback" role="alert" style="color:red">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            @if(isset($city) && !empty($city))
                                {{ Form::submit('Save', array('class'=>'btn btn-success')) }}
                                <button onclick="window.close()" class="btn btn-danger">Close</button>
                            @else
                                {{ Form::submit('Submit', array('class'=>'btn btn-success')) }}
                                <a href="{{route('admin.city')}}" class="btn btn-warning">Back</a>
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
