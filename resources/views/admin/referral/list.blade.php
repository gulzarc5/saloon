@extends('admin.template.admin_master')

@section('content')

<div class="right_col" role="main">
    <div class="x_panel">
        <div class="x_title">
          <h2>Referral Commission</h2>
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
                    <th>Referral Amount</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>       
                  @forelse ($data as $item)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->amount }}</td>                   
                        <td>
                            <a href="{{ route('admin.referral_edit',['referral_id' => $item->id]) }}" class="btn btn-xs btn-warning">Edit</a>                              
                        </td>
                      </tr>
                  @empty
                      <tr>
                        <td colspan="6" align="center">No Data Found</td>
                      </tr>
                  @endforelse                
                </tbody>
            </table>
        </div>
    </div>
</div>
 @endsection
