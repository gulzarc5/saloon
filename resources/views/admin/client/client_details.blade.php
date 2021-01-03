@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">

    <div class="">

      <div class="clearfix"></div>

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Client Details</h2>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
                @if (isset($client) && !empty($client))
                    <div class="col-md-12 col-sm-12 col-xs-12" style="border:0px solid #e5e5e5;">
                        <h3 class="prod_title">{{$client->name}} <a href="{{route('admin.client_edit',['client_id'=>$client->id])}}" class="btn btn-warning" style="float:right;margin-top: -8px;"><i class="fa fa-edit"></i></a></h3>
                        {{-- <p>{{$product->p_short_desc}}</p> --}}
                        <div class="row product-view-tag">
                            <h5 class="col-md-4 col-sm-12 col-xs-12"><strong>Name:</strong>
                                    {{$client->name}}
                            </h5>
                            <h5 class="col-md-4 col-sm-12 col-xs-12"><strong>Client Type:</strong>
                                @if ($client->clientType == '1')
                                    Freelauncer
                                @else
                                    Saloon Shop
                                @endif
                            </h5>
                            <h5 class="col-md-4 col-sm-12 col-xs-12"><strong>Mobile:</strong>
                                {{$client->mobile}}
                            </h5>
                            <h5 class="col-md-4 col-sm-12 col-xs-12"><strong>Email:</strong>
                                {{$client->email}}
                            </h5>
                            <h5 class="col-md-4 col-sm-12 col-xs-12"><strong>work_experience:</strong>
                                {{$client->work_experience}}
                            </h5>

                            <h5 class="col-md-4 col-sm-12 col-xs-12"><strong>Service City:</strong>
                                @if (!empty($client->service_city_id))
                                    {{$client->serviceCity->name}}
                                @endif
                            </h5>

                            <h5 class="col-md-4 col-sm-12 col-xs-12"><strong>State:</strong>
                                {{$client->state}}
                            </h5>
                            <h5 class="col-md-4 col-sm-12 col-xs-12"><strong>City:</strong>
                                {{$client->city}}
                            </h5>
                            <h5 class="col-md-4 col-sm-12 col-xs-12"><strong>Address:</strong>
                                {{$client->address}}
                            </h5>
                            <h5 class="col-md-4 col-sm-12 col-xs-12"><strong>Opening time:</strong>
                                {{$client->opening_time}}
                            </h5>
                            <h5 class="col-md-4 col-sm-12 col-xs-12"><strong>Closing Time:</strong>
                                {{$client->closing_time}}
                            </h5>

                            <h5 class="col-md-4 col-sm-4 col-xs-12"><strong>Status :</strong>
                                @if ($client->status == '1')
                                    <button class="btn btn-sm btn-primary">Enabled</button>
                                @else
                                    <button class="btn btn-sm btn-danger">Disabled</button>
                                @endif
                            </h5>
                            <h5 class="col-md-4 col-sm-4 col-xs-12"><strong>Profile Update Status :</strong>
                                @if ($client->profile_status == '2')
                                    <button class="btn btn-sm btn-primary">Yes</button>
                                @else
                                    <button class="btn btn-sm btn-danger">No</button>
                                @endif
                            </h5>
                            <h5 class="col-md-4 col-sm-4 col-xs-12"><strong>Service Update Status :</strong>
                                @if ($client->job_status == '2')
                                    <button class="btn btn-sm btn-primary">Yes</button>
                                @else
                                    <button class="btn btn-sm btn-danger">No</button>
                                @endif
                            </h5>
                            @if ($client->clientType == '2')
                                <h5 class="col-md-4 col-sm-4 col-xs-12"><strong>Air Conditioner : </strong>
                                    @if ($client->ac == '2')
                                        <button class="btn btn-sm btn-primary">Yes</button>
                                    @else
                                        <button class="btn btn-sm btn-danger">No</button>
                                    @endif
                                </h5>
                                <h5 class="col-md-4 col-sm-4 col-xs-12"><strong>Parking : </strong>
                                    @if ($client->parking == '2')
                                        <button class="btn btn-sm btn-primary">Yes</button>
                                    @else
                                        <button class="btn btn-sm btn-danger">No</button>
                                    @endif
                                </h5>
                                <h5 class="col-md-4 col-sm-4 col-xs-12"><strong>WIFI : </strong>
                                    @if ($client->wifi == '2')
                                        <button class="btn btn-sm btn-primary">Yes</button>
                                    @else
                                        <button class="btn btn-sm btn-danger">No</button>
                                    @endif
                                </h5>
                                <h5 class="col-md-4 col-sm-4 col-xs-12"><strong>Music : </strong>
                                    @if ($client->music == '2')
                                        <button class="btn btn-sm btn-primary">Yes</button>
                                    @else
                                        <button class="btn btn-sm btn-danger">No</button>
                                    @endif
                                </h5>
                            @endif
                        </div>
                        <br/>

                    </div>
                    @if (isset($client->images))
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h3 class="prod_title">Images <a href="{{route('admin.client_images',['client_id' => $client->id])}}" class="btn btn-warning" style="float:right;margin-top: -8px;"><i class="fa fa-edit"></i></a></h3>
                            <div class="product-image" style="text-align: center">
                                <img src="{{asset('images/client/thumb/'.$client->image.'')}}" alt="..." style="height: 200px;width: 300px;"/>
                            </div>

                            <div class="product_gallery">
                                @foreach ($client->images as $item)
                                    @if ($client->image != $item->image)
                                    <a>
                                        <img src="{{asset('images/client/thumb/'.$item->image.'')}}" alt="..." />
                                    </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (isset($client->jobs))
                        <div class="col-md-12">
                            <hr>
                            <h3>Client Service List <a class="btn btn-warning" style="float:right" href="{{route('admin.client_services_edit',['client_id'=>$client->id])}}">Edit Services</a></h3>
                            <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Service Name</th>
                                    <th>Description</th>
                                    <th>M.R.P.</th>
                                    <th>Price</th>
                                    <th>Service Available</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($client->jobs as $item)
                                    <tr>
                                        <td>{{$item->jobCategory->name}}</td>
                                        <td>{{$item->description}}</td>
                                        <td>{{$item->mrp}}</td>
                                        <td>{{$item->price}}</td>
                                        <td>
                                            @if($item->is_man == 2)
                                               <label class="label label-success">MAN</label>
                                            @endif
                                            @if($item->is_woman == 2)
                                                <label class="label label-success">WOMAN</label>
                                            @endif
                                            @if($item->is_kids == 2)
                                                <label class="label label-success">KIDS</label>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->status == 1)
                                               <label class="label label-success">Enabled</label>
                                           @else
                                            <label class="label label-danger">Disabled</label>
                                           @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            </table>
                        </div>
                    @endif

                    
                    <div class="col-md-12">
                        <hr>
                        <h3>Documents Uploades </h3>
                        <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>File Type</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           @if (!empty($client->address_proof) && !empty($client->address_proof_file))
                               <tr>
                                   <td>Address Proof</td>
                                   <td>{{$client->address_proof}}</td>
                                   <td><a href="{{asset('images/client/files/'.$client->address_proof_file.'')}}" target="_blank" class="btn btn-sm btn-info">View File</a></td>
                               </tr>
                           @endif
                           @if (!empty($client->photo_proof) && !empty($client->photo_proof_file))
                               <tr>
                                   <td>Photo Proof</td>
                                   <td>{{$client->photo_proof}}</td>
                                   <td><a href="{{asset('images/client/files/'.$client->photo_proof_file.'')}}" target="_blank" class="btn btn-sm btn-info">View File</a></td>
                               </tr>
                           @endif
                           @if (!empty($client->business_proof) && !empty($client->business_proof_file))
                               <tr>
                                   <td>Business Proof</td>
                                   <td>{{$client->business_proof}}</td>
                                   <td><a href="{{asset('images/client/files/'.$client->business_proof_file.'')}}" target="_blank" class="btn btn-sm btn-info">View File</a></td>
                               </tr>
                           @endif
                           @if ($client->verify_status == '2')  
                               <tr>
                                   <td colspan="3" align="center">
                                        <button type="button" class="btn btn-primary">Verified</button>
                                   </td>
                               </tr>
                           @endif
                        </tbody>
                        </table>
                    </div>
 

                @endif
                <div class="col-md-12">
                    @if ($client->verify_status == '1' || $client->verify_status =='3')  
                        <a href="{{route('admin.client_verify_status_update',['id'=>$client->id,2])}}"class="btn btn-success" onclick="return confirm('Are you sure to verify ??')">Verify</a>
                    @endif
                    <button class="btn btn-danger" onclick="window.close();">Close Window</button>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /page content -->

 @endsection
