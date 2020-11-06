@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="row">
    	<div class="col-md-12 col-xs-12 col-sm-12" style="margin-top:50px;">
    	    <div class="x_panel">

    	        <div class="x_title">
                    <h2>Slider List</h2>
                    <a class="btn btn-sm btn-info" style="float: right" href="{{route('admin.app_slider_add_form')}}">Add New Slider</a>
    	            <div class="clearfix"></div>
    	        </div>
    	        <div>
    	            <div class="x_content">
                        <table id="category" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                          <thead>
                            <tr>
                              <th>Sl</th>
                              <th>Image</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            @if (isset($sliders) && !empty($sliders))
                            @php
                              $count=1;
                            @endphp
                                @foreach ($sliders as $item)
                                    <tr>
                                        <td>{{$count++}}</td>

                                        <td>
                                            <img src="{{asset('images/slider/thumb/'.$item->image.'')}}" height="100" />
                                        </td>

                                      <td>
                                        <a href="{{route('admin.slider_delete',['id'=>$item->id])}}" class="btn btn-danger">Delete</a>
                                      </td>
                                    </tr>
                                @endforeach
                            @else
                              <tr>
                                <td colspan="6" style="text-align: center">No Sliders Found</td>
                              </tr>
                            @endif
                          </tbody>
                        </table>
    	            </div>
    	        </div>
    	    </div>
    	</div>
    </div>
	</div>


 @endsection

@section('script')

     <script type="text/javascript">
         $(function () {
            var table = $('#category').DataTable();
        });
     </script>

 @endsection
