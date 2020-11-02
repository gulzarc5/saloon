@extends('admin.template.admin_master')

@section('content')
<div class="right_col" role="main">
    <div class="row">
    	{{-- <div class="col-md-2"></div> --}}
    	<div class="col-md-12" style="margin-top:50px;">
    	    <div class="x_panel">

    	        <div class="x_title">
    	            <h2>Customer Details</h2>
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

    	            	{{ Form::open(['method' => 'put','route'=>['admin.customer_update','id'=>$customer->id] ,'id'=>'edit_form']) }}

                        <div class="well" style="overflow: auto">
                            <div class="form-row mb-10">
                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                  <label for="name">Customer name</label>
                                  <input type="text" class="form-control" name="name"  placeholder="Enter Customer Name" value="{{$customer->name}}" disabled>
                                    @if($errors->has('name'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email"  placeholder="Enter Customer Email" value="{{$customer->email}}" disabled>
                                    @if($errors->has('email'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="mobile">Mobile</label>
                                    <input type="number" class="form-control" name="mobile"  placeholder="Enter Customer Mobile" value="{{$customer->mobile}}" disabled>
                                    @if($errors->has('mobile'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('mobile') }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row mb-3">
                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="dob">Date of Birth</label>
                                    <input type="date" class="form-control" name="dob" value="{{$customer->dob}}" disabled>
                                    @if($errors->has('dob'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('dob') }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3" style="margin-top: 20px;">
                                    <label for="color">Gender</label>
                                    <p > Male : <input type="radio"  name="gender" id="genderM" value="M" {{$customer->gender == 'M'?'checked':''}} disabled/>
                                        Female : <input type="radio" name="gender" id="genderF" value="F" {{$customer->gender == 'F'?'checked':''}} disabled/>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="well" style="overflow: auto">
                            <div class="form-row mb-10" >
                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="state">State</label>
                                    <input type="text" class="form-control" placeholder="Enter State Name" name="state" value="{{$customer->state}}" disabled>
                                    @if($errors->has('state'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('state') }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                {{-- <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="state">Select State</label>
                                    <select class="form-control" name="state" id="state" disabled>
                                        <option value="">Select State</option>
                                        @if(isset($state) && !empty($state))
                                            @foreach($state as $item)
                                                <option value="{{ $item->id }}" {{$customer->state == $item->id?'selected':''}}>{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if($errors->has('state'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('state') }}</strong>
                                        </span>
                                    @enderror
                                </div> --}}

                                {{-- <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="city">Select City</label>
                                    <select class="form-control" name="city" id="city" disabled>
                                        <option value="">Select City</option>
                                        @if(isset($city) && !empty($city))
                                            @foreach($city as $item)
                                                <option value="{{ $item->id }}" {{$customer->city == $item->id?'selected':''}}>{{ $item->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @if($errors->has('city'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('city') }}</strong>
                                        </span>
                                    @enderror
                                </div> --}}

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" name="city"  placeholder="Enter City Name" value="{{$customer->city}}" disabled>
                                    @if($errors->has('city'))
                                        <span class="invalid-feedback" role="alert" style="color:red">
                                            <strong>{{ $errors->first('city') }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                    <label for="pin">Pin</label>
                                    <input type="number" class="form-control" name="pin"  placeholder="Enter Pin Code" value="{{$customer->pin}}"disabled>
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
                                    <textarea class="form-control" name="address"  placeholder="Type Address" disabled>{{$customer->address}}</textarea>
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
                                <button class="btn btn-sm btn-warning" type="button" onclick="editForm()">Edit</button>
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



