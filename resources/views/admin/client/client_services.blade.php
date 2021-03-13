@extends('admin.template.admin_master')

@section('content')
<style>
    .error{
        color:red;
    }
</style>
<div class="right_col" role="main">
    <div class="row">
    	{{-- <div class="col-md-2"></div> --}}
    	<div class="col-md-12" style="margin-top:50px;">
    	    <div class="x_panel">

    	        <div class="x_title">
    	            <h2>Edit Client Services</h2>
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
                        @if (isset($client_id) && !empty($client_id))
                            @if(isset($client_services) && !empty($client_services) && count($client_services) > 0)
                                {{-- <div id="product_size_add_form">
                                    {{ Form::open(['method' => 'put','route'=>['admin.product_add_new_colors','product_id'=>$product->id]]) }}
                                        <div class="well" style="overflow: auto" id="size_div">
                                            <div class="form-row mb-3">
                                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                                    <label for="color">Select Color <span><b style="color: red"> * </b></span></label>
                                                    <select class="form-control" name="color[]" id="color_option1"  required onchange="chkColorAdd(1)">
                                                    <option value="">Select Color</option>
                                                        @if (isset($colors) && !empty($colors))
                                                            @foreach ($colors as $color)
                                                                <option value="{{$color->id}}" data-colorid="{{$color->color}}">{{$color->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3" style="margin-top: 34px;">
                                                   <div id="color_code1"></div>
                                                </div>

                                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3" style="margin-top: 25px;">
                                                    <button type="button" class="btn btn-sm btn-info" id="add_more_size" >Add More</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class='btn btn-success'>Submit</button>
                                            <button type="button" class='btn btn-warning' id="size_add_form_back_btn">Back</button>
                                        </div>
                                    {{ Form::close() }}
                                </div> --}}

                                <div id="product_size_edit_form">
                                    <div class="col-md-12">
                                        {{ Form::open(['method' => 'put','route'=>['admin.client_services_update','client_id'=>$client_id]]) }}
                                        <table class="table table-striped jambo_table bulk_action">
                                            <thead>
                                                <tr style="font-size: 10.5px;">
                                                    <th>Main Category</th>
                                                    <th>Sub Category</th>
                                                    <th>Last Category</th>
                                                    <th>Description</th>
                                                    <th >M.R.P.</th>
                                                    <th>Price</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($client_services as $service)
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="service_id[]" value="{{$service->id}}">
                                                            {{isset($service->jobCategory->name) ? $service->jobCategory->name : ''}}
                                                        </td>
                                                        <td>
                                                            {{$service->subCategory->name ?? ''}}
                                                        </td>
                                                        <td>
                                                            {{$service->lastCategory->third_level_category_name ?? ''}}
                                                        </td>
                                                        <td>
                                                            <textarea name="description[]"  rows="1">{{$service->description}}</textarea>
                                                        </td>
                                                        
                                                        <td>
                                                            <div class="form-inline">
                                                                <input type="number" class="form-control" name="mrp[]" value="{{$service->mrp}}" style="width: 70px;"><br>
                                                            </div>
                                                        </td>
                                                        <td> <div class="form-inline">
                                                            <input type="number" class="form-control" name="price[]" value="{{$service->price}}" style="width: 70px;"><br>
                                                            </div>
                                                        </td>
                                                       
                                                        <td>
                                                            @if ($service->status == '1')
                                                                <button type="button" class="btn btn-xs btn-success">Enabled</button>
                                                            @else
                                                                <button type="button" class="btn btn-xs btn-danger">Disabled</button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="8" align="center">
                                                        <button type="submit" class='btn btn-success'>Update Service</button>
                                                        <button class="btn btn-danger" onclick="window.close();">Close Window</button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                            @else
                                <h1>No Services Found</h1>
                                {{-- <div>
                                    {{ Form::open(['method' => 'put','route'=>['admin.product_add_new_colors','product_id'=>$product->id]]) }}
                                        <input type="hidden" name="product_id" value="">
                                        <div class="well" style="overflow: auto" id="size_div">
                                            <div class="form-row mb-3">
                                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                                                    <label for="color">Select Color <span><b style="color: red"> * </b></span></label>
                                                    <select class="form-control size_option" name="color[]"  required id="color_option1"  required onchange="chkColorAdd(1)">
                                                        <option value="">Select Color</option>
                                                        @if (isset($colors) && !empty($colors))
                                                            @foreach ($colors as $color)
                                                                <option value="{{$color->id}}">{{$color->name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3" style="margin-top: 34px;">
                                                    <div id="color_code1"></div>
                                                 </div>

                                                <div class="col-md-4 col-sm-12 col-xs-12 mb-3" style="margin-top: 25px;">
                                                    <button type="button" class="btn btn-sm btn-info" id="add_more_size" >Add More</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class='btn btn-success'>Submit</button>
                                        </div>
                                    {{ Form::close() }}
                                </div> --}}
                            @endif
                        @endif
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
    var size_div_count = 2;
    $(function() {
        $("#product_size_add_form").hide();
        var color_option = $("#color_option1").html();
        $(document).on('click',"#add_more_size", function(){
            var size_html = `<br>
            <div class="form-row mb-3">
                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                    <label for="retailer_price">Select Color</label>
                    <select class="form-control size_option" name="color[]"  required id="color_option${size_div_count}" onchange="chkColorAdd(${size_div_count})">
                        ${color_option}
                    </select>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12 mb-3" style="margin-top: 34px;">
                   <div id="color_code${size_div_count}"></div>
                </div>
                <div class="col-md-4 col-sm-12 col-xs-12 mb-3">
                    <button type="button" class="btn btn-danger btn-sm" style="margin-top: 26px;" onclick="removeSize(${size_div_count})">Remove</button>
                </div>
            </div> `;
            $("#size_div").append("<span id='sizes"+size_div_count+"'>"+size_html+"</span>");
            size_div_count++;
        });

        $(document).on('click',"#add_more_size_btn",function(){
            $("#product_size_add_form").show();
            $("#product_size_edit_form").hide();
        });

        $(document).on('click',"#size_add_form_back_btn",function(){
            $("#product_size_add_form").hide();
            $("#product_size_edit_form").show();
        });
    });



    function removeSize(id) {
        $("#sizes"+id).remove();
        size_div_count--;
    }

    function chkColor(id) {
        var data = $("#colors"+id).find(':selected').attr('data-colorid');
        $('#code'+id).html('<div id="code" style="background-color:'+data+'; height:10px;"></div>');
    }

    function chkColorAdd(id) {
        var data = $("#color_option"+id).find(':selected').attr('data-colorid');
        $('#color_code'+id).html('<div id="code" style="background-color:'+data+'; height:10px;"></div>');
    }
</script>
 @endsection
