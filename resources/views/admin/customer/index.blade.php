@extends('admin.layouts.datatableWithForm')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('admin.customer.index') }}">Customer</a>
</li>
<li class="breadcrumb-item">
    All
</li>
@endsection

@section('content-body')
<div class="col-lg-12 mb-2 order-0 mb-3">
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="card-body">
            <h5 class="card-title text-primary">
                {{ $title_sub }}
            </h5>
            @if(session('success'))     
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="alert alert-success" role="alert">{{ session('success') }}</div>                                       
                </div>
            </div>            
            @endif
            
            @if($isEdit)
              @include('admin.customer.edit')
            @else
              @include('admin.customer.create')              
            @endif
                                                               
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-lg-12 mb-2 order-0 mb-3">
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="card-body">
          <h5 class="card-title text-primary">All Users</h5>
            
            <table id="dttable" class="table table-striped table-bordered table-hover" rules="all" cellpadding="3">
            <thead>                            
              <tr>
                  <th>Name</th>
                  <th>Joining Date</th>
                  <th>Present Adress</th>
                  <th>Mobile</th>
                  <th>Contact Person</th>
                  <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($customers as $data)
                <tr>
                  <td>{{ $data->first_name }}</td>
                  <td>{{ $data->org_name }}</td>
                  <td>{{ $data->cust_mobile_no }}</td>
                  <td>{{ $data->cust_email_address }}</td>
                  <td>{{ $data->org_address }}</td>
                  <td>
                    <a href="{{ route('admin.customer.edit', $data->cust_id) }}" class="btn btn-sm btn-primary">Update</a>
                  </td>
                </tr>
              @endforeach                            
            </tbody>
        </table>
        
        </div>
      </div>
    </div>
  </div>
@endsection