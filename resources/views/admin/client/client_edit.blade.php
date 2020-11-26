@extends('admin.template.admin_master')

@section('content')
<div class="right_col" role="main">
    <div class="row">
    	{{-- <div class="col-md-2"></div> --}}
    	<div class="col-md-12" style="margin-top:50px;">
    	    <div class="x_panel">

    	        <div class="x_title">
    	            <h2>Client Information Edit</h2>
    	            <div class="clearfix"></div>
    	        </div>
                <div>
                     @if (Session::has('message'))
                        <div class="alert alert-success" >{{ Session::get('message') }}</div>
                     @endif
                     @if (Session::has('error'))
                        <div class="alert alert-danger">{{ Session::get('error') }}</div>
                     @endif

                </div>
    	        <div>
    	            <div class="x_content">

    	            	{{ Form::open(['method' => 'put','route'=>['admin.client_update',$client->id] ,'id'=>'edit_form']) }}

                        <div class="well" style="overflow: auto">
                            <div class="form-row mb-10">
                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                  <label for="name">name</label>
                                  <input type="text" class="form-control" name="name"  placeholder="Enter Customer Name" value="{{$client->name}}" >
                                    @if($errors->has('name'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="mobile">Mobile</label>
                                    <input type="number" class="form-control" name="mobile"  placeholder="Enter Customer Mobile" value="{{$client->mobile}}" >
                                    @if($errors->has('mobile'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('mobile') }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email"  placeholder="Enter Customer Email" value="{{$client->email}}" >
                                    @if($errors->has('email'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @enderror
                                </div>


                            </div>

                            <div class="form-row mb-10">
                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="work_experience">Work Ecperiance (In Years)</label>
                                    <input type="number" class="form-control" name="work_experience" value="{{$client->work_experience}}">
                                    @if($errors->has('work_experience'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('work_experience') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="gst">GST Number</label>
                                    <input type="text" class="form-control" name="gst" value="{{$client->gst}}">
                                    @if($errors->has('gst'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('gst') }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="service_city_id">Service City</label>
                                    <select class="form-control" name="service_city_id" id="">
                                        <option value="">Please Select Service City Id</option>
                                        @if (!empty($service_city))
                                            @foreach ($service_city as $item)
                                                <option value="{{$item->id}}" {{ $client->service_city_id == $item->id?'selected':''}}>{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if($errors->has('service_city_id'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('service_city_id') }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="opening_time">Opening Time</label>
                                    <input type="time" class="form-control" name="opening_time" value="{{$client->opening_time}}">
                                    @if($errors->has('opening_time'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('opening_time') }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="closing_time">Closing Time</label>
                                    <input type="time" class="form-control" name="closing_time" value="{{$client->closing_time}}">
                                    @if($errors->has('closing_time'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('closing_time') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="well" style="overflow: auto">
                            <div class="form-row mb-10" >
                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="state">State</label>
                                    <input type="text" class="form-control" placeholder="Enter State Name" name="state" value="{{$client->state}}">
                                    @if($errors->has('state'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('state') }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" name="city"  placeholder="Enter City Name" value="{{$client->city}}" >
                                    @if($errors->has('city'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('city') }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="pin">Pin</label>
                                    <input type="number" class="form-control" name="pin"  placeholder="Enter Pin Code" value="{{$client->pin}}">
                                    @if($errors->has('pin'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('pin') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row mb-10" >
                                <div class="col-md-12 col-sm-12 col-xs-12 mb-3">
                                    <label for="address">Address</label>
                                    <textarea class="form-control" name="address"  placeholder="Type Address" >{{$client->address}}</textarea>
                                    @if($errors->has('address'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

    	            	<div class="form-group">
                            <span id="form_btn">
                                <button class="btn btn-sm btn-info" type="submit">Save</button>
                            </span>
                            <button class="btn btn-sm btn-danger" type="button" onclick="window.close()">Close</button>

    	            	</div>
    	            	{{ Form::close() }}

    	            </div>
    	        </div>
    	    </div>
    	</div>
    	{{-- <div class="col-md-2"></div> --}}
    </div>
</div>


 @endsection

@section('script')

    <script>
        // $(document).ready(function(){
        //     $("#state").change(function(){
        //         var state_id = $(this).val();
        //         $.ajaxSetup({
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             }
        //         });
        //         $.ajax({
        //             type:"GET",
        //             url:"{{ url('/admin/city/list/byState/')}}"+"/"+state_id+"",
        //             success:function(data){
        //                 $("#city").html("<option value=''>Please Select City</option>");
        //                 $.each( data, function( key, value ) {
        //                     $("#city").append("<option value='"+value.id+"'>"+value.name+"</option>");
        //                 });

        //             }
        //         });
        //     });
        // });

        function editForm(){
            $("#edit_form :input").prop("disabled", false);
            $("#form_btn").html('<button class="btn btn-sm btn-info" type="submit">Save</button>')
        }

    </script>
 @endsection



