
@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="margin-top:50px;">
            <div class="x_panel">

                <div class="x_title">
                    <h2>Add New Offer</h2>
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
                        {{Form::open(['method' => 'post','route'=>'admin.insert_offer'])}}                       

                        <div class="form-group">
                            {{ Form::label('name', 'Offer Name')}}
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            {{ Form::label('category', 'Category')}}
                            <select name="category" id="category" class="form-control" required>
                                <option value="">Select Category</option>
                                @if (isset($category) && !empty($category))
                                    @foreach ($category as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            {{ Form::label('sub_category', 'Sub Category')}}
                            <select name="sub_category" class="form-control" id="sub_category" >
                                <option value="">Select Sub Category</option>
                            </select>
                        </div>
                        <div class="form-group" >
                            {{ Form::label('third_category', 'Third Category')}}
                            <select name="third_category" id="third_category" class="form-control">
                                <option value="">Select Third Category</option>
                            </select>
                        </div>
                        <div class="form-group" >
                            {{ Form::label('range_type', 'Range Type')}}
                            <select name="range_type" id="range_type" class="form-control" required>
                                <option value="1">Limited Period</option>
                                <option value="2">Date Range</option>
                            </select>
                        </div>
                        <div class="form-group" id="from_date_div" style="display:none">
                            {{ Form::label('from_date', 'From Date')}}
                            <input type="date" name="from_date" id="from_date" class="form-control" >
                        </div>
                        <div class="form-group" id="to_date_div" style="display:none">
                            {{ Form::label('to_date', 'To Date')}}
                            <input type="date" name="to_date" id="to_date" class="form-control" >
                        </div>
                        <div class="form-group">
                            {{ Form::label('image', 'Image')}}
                            <input type="file" name="image" id="image" class="form-control" required>
                        </div>
                        <div class="form-group" >
                            {{ Form::label('total_user', 'How Many User')}}
                            <input type="number" name="total_user"  class="form-control" required>
                        </div>

                        <div class="form-group" >
                            {{ Form::label('salon_mobile', 'Salon Mobile')}}
                            <div class="inline">
                                <input type="number" name="salon_mobile"  class="form-control">
                                <button class="btn btn-success"><i class="fa fa-plus"></i></button>
                            </div>
                            <span id="salon_mobile_err"></span>
                        </div>

                        <div class="form-group" >
                            {{ Form::label('salon_mobile', 'Salon Mobile')}}
                            <div class="inline">
                                <input type="number" name="salon_mobile"  class="form-control">
                                <button class="btn btn-danger"><i class="fa fa-trash"></i></button>
                            </div>
                            <span id="salon_mobile_err"></span>
                        </div>
                        <div id="salon_div"></div>

                        <div class="form-group" >
                            {{ Form::label('description', 'Description')}}
                            <textarea name="description"  class="form-control"></textarea>
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
    <script>
        $(function(){
            $(document).on('change',"#category", function(){
                let category_id = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('fetch_sub_category')}}",
                    method: "POST",
                    data: {category_id:category_id},
                    success: function(data){
                        $('#sub_category').html(data);
                    }
                });
            });

            $(document).on('change',"#sub_category", function(){
                let category_id = $(this).val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('admin.fetch_third_category_ajax')}}",
                    method: "POST",
                    data: {category_id:category_id},
                    success: function(data){
                        $('#third_category').html(data);
                    }
                });
            });

            $(document).on('change',"#range_type", function(){
                let range_type = $(this).val();
                if (range_type == '2') {
                    $("#from_date_div").show();
                    $("#to_date_div").show();
                    $("#from_date").attr("required", "true");
                    $("#to_date").attr("required", "true");

                }else{
                    $("#from_date_div").hide();
                    $("#to_date_div").hide();
                    $("#from_date").attr("required", "false");
                    $("#to_date").attr("required", "false");
                }
            });
        });
    </script>
@endsection
