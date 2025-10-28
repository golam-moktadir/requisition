@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('requisition.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    Approval
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')   

        <form action="{{ route('requisition.store_approval', $single->id) }}" method="POST">
            @csrf
            <div class="row">

                <div class="mb-3 col-4">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" value="{{ old('title', $single->title) }}" required placeholder="Title" tabindex="1" disabled>
                    @error('title') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3  col-8">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" required placeholder="Description" tabindex="2" disabled>{{ old('description', $single->description )  }}</textarea>
                    @error('description') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3 col-4">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control" id="amount" value="{{ old('amount', $single->amount) }}" required placeholder="Amount" tabindex="3" step="0.01" disabled>
                    @error('amount') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3 col-4">
                    <label for="requested_to" class="form-label">Requested To</label>

                    <select class="form-control" id="requested_to" value="{{ old('requested_to') }}" required tabindex="4" disabled>
                        <option value="">Select Requested To</option>
                        <option value="ceo"{{old('', $single->requested_to)=='ceo'?' selected':'' }}>CEO</option>
                        <option value="managing_director"{{old('requested_to', $single->requested_to)=='managing_director'?' selected':'' }}>Managing Director</option>
                        <option value="manager"{{old('requested_to', $single->requested_to)=='manager'?' selected':'' }}>Manager</option>
                        <option value="accountant"{{old('requested_to', $single->requested_to)=='accountant'?' selected':'' }}>Accountant</option>
                    </select>
                    @error('requested_to') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3 col-4">
                    <label for="transaction_mode" class="form-label">Transaction Mode</label>

                    <select class="form-control" id="transaction_mode" value="{{ old('transaction_mode') }}" required tabindex="5" disabled>
                        <option value="">Select Transaction Mode</option>
                        <option value="cash"{{old('transaction_mode', $single->transaction_mode)=='cash'?' selected':'' }}>Cash</option>
                        <option value="bank"{{old('transaction_mode', $single->transaction_mode)=='bank'?' selected':'' }}>Bank</option>
                        <option value="due"{{old('transaction_mode', $single->transaction_mode)=='due'?' selected':'' }}>Due</option>
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

                <div class="clearboth">&nbsp;</div>
                <hr />

                <div class="mb-3 col-4">
                    <label for="status" class="form-label">Status</label>

                    <select class="form-control" id="status" required tabindex="11" autofocus name="status">
                        <option value="">Select Status</option>
                        <option value="approved"{{old('status')=='approved'?' selected':'' }}>
                            Approved
                        </option>
                        <option value="rejected"{{old('status')=='rejected'?' selected':'' }}>
                            Reject
                        </option>
                    </select>


                    @error('status') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3 col-8">
                    <label for="remarks" class="form-label">Remarks</label>
                    <input type="text" class="form-control" id="remarks" value="{{ old('remarks') }}" required tabindex="12" name="remarks" />
                    @error('remarks') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <hr />

                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{route('requisition.index')}}" class="btn btn-info">Return back</a>
                </div> 
                
            </div>
        </form>   
    </div>

@endsection

@section('footerjs')


@endsection
