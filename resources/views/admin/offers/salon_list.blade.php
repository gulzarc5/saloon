@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="x_panel">
        <div class="x_title">
          <h2>Offer Salon List</h2>      
          <a style="float:right" href="{{ route('admin.offer_add_salon',['offer_id' => $offer_id]) }}" class="btn btn-xs btn-warning">Add New</a>
          <div class="clearfix"></div>
        </div>
        @if (Session::has('message'))
            <div class="alert alert-success" >{{ Session::get('message') }}</div>
        @endif
        @if (Session::has('error'))
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
        @endif
        <div class="x_content">
            <table id="customers" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Sl. No</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>       
                  @forelse ($offer_salon as $item)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->salon->name ?? null }}</td>
                        <td>{{ $item->salon->mobile ?? null }}</td>
                        <td>
                            <a href="{{ route('admin.remove_offer_salon',['offer_salon_id' => $item->id]) }}" class="btn btn-xs btn-warning"><i class="fa fa-trash"></i></a>                                     
                        </td>
                      </tr>
                  @empty
                      <tr>
                        <td colspan="6" align="center">
                          <a href="{{ route('admin.offer_add_salon',['offer_id' => $offer_id]) }}" class="btn btn-xs btn-warning">Add New</a>
                        </td>
                      </tr>
                  @endforelse                
                </tbody>
            </table>
        </div>
    </div>
</div>
 @endsection
