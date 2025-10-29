@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('requisition.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    Show
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')   
            <div class="row">
                <div class="mb-3 col-4">
                    <label for="company_id" class="form-label">Company Name</label>
                    <input type="text" class="form-control" id="company_id" value="{{ $single->company_id }}" disabled>
                </div>
                <div class="mb-3  col-4">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" tabindex="2" disabled>{{ $single->description }}</textarea>
                </div>
                <div class="mb-3 col-4">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control" id="amount" value="{{ $single->amount) }}" required tabindex="3" step="0.01" disabled>
                </div>
                <div class="mb-3 col-4">
                    <label for="requested_to" class="form-label">Requested To</label>
                    <input type="text" class="form-control" id="requested_to" value="{{ $single->requested_to }}" tabindex="4" disabled>
                </div>
                <div class="mb-3 col-4">
                    <label for="transaction_mode" class="form-label">Transaction Mode</label>

                    <select class="form-control" id="transaction_mode" value="{{ old('transaction_mode') }}" required tabindex="5" disabled>
                        <option value="">Select Transaction Mode</option>
                        <option value="cash"{{old('', $requisition->transaction_mode)=='cash'?' selected':'' }}>Cash</option>
                        <option value="bank"{{old('', $requisition->transaction_mode)=='bank'?' selected':'' }}>Bank</option>
                        <option value="due"{{old('', $requisition->transaction_mode)=='due'?' selected':'' }}>Due</option>
                    </select>
                    @error('transaction_mode') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3 col-4">
                    <label for="bank_check_info" class="form-label">Bank Check Info</label>

                    <select class="form-control" id="bank_check_info" value="{{ old('bank_check_info') }}" tabindex="6" disabled>
                        <option value="">Select Bank Check Info</option>
                    </select>
                    @error('bank_check_info') <div class="text-danger">{{ $message }}</div> @enderror
                </div> 

                <div class="col-12">
                    <h3>Approval Status</h3>
                    <table class="table table-bordered table-hover table-sm">
                        <thead>
                            <tr class="table-info">
                                <th style="width: 60px;">#</th>
                                <th style="width: 140px;">Status</th>
                                <th>Remarks</th>
                                <th style="width: 225px;">Updated By</th>
                                <th style="width: 200px;">Action Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvals AS $approval)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ ucwords($approval->status) }}</td>
                                <td>{{ $approval->remarks }}</td>
                                <td>{{ $approval->user->name }}</td>
                                <td>{{ $approval->created_at }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>  
    </div>

@endsection

@section('footerjs')


@endsection
