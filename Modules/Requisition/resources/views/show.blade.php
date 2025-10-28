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
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" value="{{ old('title', $requisition->title) }}" required placeholder="Title" tabindex="1" disabled>
                    @error('title') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3  col-8">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" required placeholder="Description" tabindex="2" disabled>{{ old('description', $requisition->description )  }}</textarea>
                    @error('description') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3 col-4">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control" id="amount" value="{{ old('amount', $requisition->amount) }}" required placeholder="Amount" tabindex="3" step="0.01" disabled>
                    @error('amount') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3 col-4">
                    <label for="requested_to" class="form-label">Requested To</label>

                    <select class="form-control" id="requested_to" value="{{ old('requested_to') }}" required tabindex="4" disabled>
                        <option value="">Select Requested To</option>
                        <option value="ceo"{{old('', $requisition->requested_to)=='ceo'?' selected':'' }}>CEO</option>
                        <option value="managing_director"{{old('', $requisition->requested_to)=='managing_director'?' selected':'' }}>Managing Director</option>
                        <option value="manager"{{old('', $requisition->requested_to)=='manager'?' selected':'' }}>Manager</option>
                        <option value="accountant"{{old('', $requisition->requested_to)=='accountant'?' selected':'' }}>Accountant</option>
                    </select>
                    @error('requested_to') <div class="text-danger">{{ $message }}</div> @enderror
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
