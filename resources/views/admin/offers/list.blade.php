@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="x_panel">
        <div class="x_title">
          <h2>Offer List</h2>      
          <a style="float:right" href="{{ route('admin.offer_add_form') }}" class="btn btn-xs btn-warning">Add New</a>
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
                    <th>Category</th>
                    <th>price</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>       
                  @forelse ($offers as $item)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category->name ?? null }}</td>
                        <td>{{ $item->subCategory->name ?? null }}</td>
                        <td>
                          @if ($item->total_user <= $item->offer_received_user)
                            <button type="button" class="btn btn-xs btn-danger">Expired</button>
                          @elseif ($item->status == '1')
                            <button type="button" class="btn btn-xs btn-info">Enabled</button>
                          @else
                            <button type="button" class="btn btn-xs btn-danger">Disabled</button>                              
                          @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.offer_edit_form',['offer_id' => $item->id]) }}" class="btn btn-xs btn-warning">Edit</a>                     
                            <a href="{{ route('admin.offer_edit_salon',['offer_id' => $item->id]) }}" class="btn btn-xs btn-warning">Edit Salons</a>                                
                        </td>
                      </tr>
                  @empty
                      <tr>
                        <td colspan="6" align="center">No Offers Found</td>
                      </tr>
                  @endforelse                
                </tbody>
            </table>
        </div>
    </div>
</div>
 @endsection
