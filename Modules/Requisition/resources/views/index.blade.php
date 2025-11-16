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
            <a href="{{ route('requisition.create') }}" class="btn btn-sm btn-primary">
                Add New
            </a>
        </p>
        <form method="GET" action="{{ route('requisition.index') }}">            
            <div class="row mt-1 p-1 align-items-end">
                <div class="col-md-2 mb-2">
                    <label for="requisition_no" class="form-label">Requisition No.</label>
                    <input type="text" class="form-control" id="requisition_no" name="requisition_no" value="{{ request('requisition_no') }}" tabindex="1" autocomplete="off">
                </div>
                <div class="col-md-2 mb-2">
                    <label for="from_date" class="form-label">From Date</label>
                    <input type="text" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}" tabindex="2" autocomplete="off">
                </div>
                <div class="col-md-2 mb-2">
                    <label for="to_date" class="form-label">To Date</label>
                    <input type="text" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}" tabindex="3" autocomplete="off">
                </div>
                <div class="col-md-2 mb-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status" tabindex="4">
                        <option value="" {{ request('status') === null || request('status') === '' ? 'selected' : '' }}>All Status</option>
                        <option value="pending" {{ request()->has('status') ? (request('status') == 'pending' ? 'selected' : '') : 'selected' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Returned</option>
                        <option value="issued" {{ request('status') == 'issued' ? 'selected' : '' }}>Issued</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" name="search" value="search" class="btn btn-primary ms-auto w-50">Search</button>
                </div>
            </div>
        </form>
        <table class="table table-sm table-bordered table-hover mt-2">
            <thead>
                <tr class="table-primary text-white text-center">
                    <th>#</th>
                    <th>Requision No.</th>
                    <th>Date</th>
                    <th>Company Name</th>
                    <th>Purpose Name</th>
                    <th>Amount (TK)</th>
                    <th>Status</th>
                    <th>Action</th>                
                </tr>
            </thead>
            <tbody>
                @foreach($requisitions AS $requisition)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $requisition->req_no }}</td>
                    <td class="text-center">{{ $requisition->created_at }}</td>
                    <td>{{ $requisition->company_name }}</td>
                    <td>{{ $requisition->purpose_name }}</td>                    
                    <td class="text-end">{{ ucwords($requisition->total_amount) }}</td>
                    <td class="text-center">{{ ucwords($requisition->status) }}</td>
                    <td class="text-center">
                        <div class="btn-group" role="group" aria-label="Basic example"> 
                            <a href="{{ route('requisition.show', $requisition->id) }}" class="btn btn-sm btn-info"> View
                            </a>
                            @if($requisition->status!='approved') 
                            <a href="{{ route('requisition.edit', $requisition->id) }}" class="btn btn-sm btn-primary">
                                Edit
                            </a>
                            @endif                
                        </div>    
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>        
    </div>
@endsection

@section('footerjs')
<script type="text/javascript">
    $(document).ready(function(){
        flatpickr("#from_date, #to_date", {
            dateFormat: "Y-m-d",
            //defaultDate: "today",
            // maxDate: "today"    
        });
    });
</script>

@endsection
