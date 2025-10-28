@extends('admin.layouts.datatableWithForm')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('admin.user.index') }}">Users</a>
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
              @include('admin.user.edit')
            @else
              @include('admin.user.create')              
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
                  <th>S.L.</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Roles</th>
                  <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
                <tr>
                  <td>{{ $user->id }}</td>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>{{ $user->status }}</td>
                  <td>{{ $user->roles }}</td>
                  <td>
                    <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-sm btn-primary">Update</a>
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