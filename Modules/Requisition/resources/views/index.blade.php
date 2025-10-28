@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('requisition.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    All
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')
        <p>
            <a href="{{ route('requisition.create') }}" class="btn btn-md btn-primary">
                Make new requisition
            </a>
        </p>
        <form method="GET" action="{{ route('requisition.index') }}">            
            <div class="row mt-1 p-1">
                <div class="mb-2 col-md-4">
                    <label for="date" class="form-label">Date</label>
                    <input value="{{ old('date') }}" type="date" class="form-control" id="date" aria-describedby="dateHelp" name="date" autofocus tabindex="1" autocomplete="off">
                </div>
                <div class="mb-2 col-md-4">
                    <label for="transaction_mode" class="form-label">Transaction Mode</label>
                    <select class="form-control" id="transaction_mode" name="transaction_mode" value="{{ old('transaction_mode') }}" tabindex="2">
                        <option value="">Select Transaction Mode</option>
                        <option value="cash">Cash</option>
                        <option value="bank">Bank</option>
                        <option value="due">Due</option>
                    </select>
                    @error('transaction_mode') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="mb-2 col-md-4">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status" value="{{ old('status') }}" tabindex="3">
                        <option value="">Select Transaction Mode</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    @error('status') <div class="text-danger">{{ $message }}</div> @enderror
                </div>
                <div class="mb-2 col-md-4">
                    <button type="submit" name="search" value="search" class="btn btn-primary">
                        Search
                    </button>
                </div>
            </div>
        </form>
        <table class="table table-sm table-bordered table-hover mt-2">
            <thead>
                <tr class="table-primary text-white">
                    <th style="width: 60px;">#</th>
                    <th >Company Name</th>
                    <th >Purpose Name</th>
                    <th style="width: 135px;">Requested To</th>
                    <th style="width: 130px;">Amount (TK)</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 140px;">Action</th>                
                </tr>
            </thead>
            <tbody>
                @foreach($requisitions AS $requisition)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $requisition->company_name }}</td>
                    <td>{{ $requisition->purpose_name }}</td>
                    <td>{{ ucwords($requisition->requested_to) }}</td>
                    
                    <td>{{ ucwords($requisition->amount) }}</td>
                    <td>{{ ucwords($requisition->status) }}</td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic example">
<!--                          @if(auth()->user()->id=='1')                  
                    @if($requisition->status!='approved') 
                            <a href="{{ route('requisition.show', $requisition->id) }}" class="btn btn-sm btn-info"> View
                            </a>
                            <a href="{{ route('requisition.edit', $requisition->id) }}" class="btn btn-sm btn-primary">
                                Edit
                            </a>
                            @endif 
                          @endif -->  
                            <a href="{{ route('requisition.show', $requisition->id) }}" class="btn btn-sm btn-info"> View
                            </a>
                            <a href="{{ route('requisition.edit', $requisition->id) }}" class="btn btn-sm btn-primary">
                                Edit
                            </a>

                          @if($requisition->status!='approved')                
                              <a href="{{ route('requisition.approval', $requisition->id) }}" class="btn btn-sm btn-secondary">
                                Approval
                              </a> 
                          @endif        
<!--                           @if(auth()->user()->id=='1' and $requisition->status!='approved')                
                              <a href="{{ route('requisition.approval', $requisition->id) }}" class="btn btn-sm btn-secondary">
                                Approval
                              </a> 
                          @endif   -->                 
                        </div>    
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-primary text-white">
                    <th style="width: 60px;">#</th>
                    <th >Title</th>
                    <th style="width: 130px;">Amount (TK)</th>
                    <th style="width: 135px;">Requested To</th>
                    <th style="width: 130px;">Trans. Mode</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 120px;">Action</th>                
                </tr>
            </tfoot>
        </table>        
    </div>
@endsection

@section('footerjs')


@endsection
