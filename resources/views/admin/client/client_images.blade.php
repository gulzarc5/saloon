@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
  <div class="row">
    <div class="x_panel">
      <div class="x_title">
        <h3>Client Images</h3>
        <div class="clearfix"></div>
          <div>
             @if (Session::has('message'))
                <div class="alert alert-success" >{{ Session::get('message') }}</div>
             @endif
             @if (Session::has('error'))
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
             @endif
          </div>
      </div>
      <div class="x_content">
        @if(isset($client) && isset($client_images) && !empty($client) && !empty($client_images))

        @foreach($client_images as $image)
        <div class="col-md-4">
          <div class="thumbnail" style="height: 300px; width: 300px;" >
            <div class="image view view-first" style="height: 300px; width: 300px;">
              <img style="width: 100%; display: block;" src="{{ asset('images/client/thumb/'.$image->image.'')}}" />
            </div>
          </div>
          <div>

            @if($client->image == $image->image)
              <a href="" class="btn btn-sm btn-primary">Thumb Image</a>
            @else
                <a href="{{ route('admin.client_images_cover',['client_id'=>$client->id,'image_id' =>$image->id ])}}" class="btn btn-sm btn-success">Set As Main Image</a>

              <a href="{{ route('admin.client_images_delete',['image_id' =>$image->id])}}" class="btn btn-sm btn-danger" >Delete</a>
            @endif
          </div>
        </div>
        @endforeach
        @endif
      </div>
    </div>
  </div>


</div>
@endsection
