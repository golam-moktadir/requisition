@extends('admin.layouts.master')

@section('content-body')
<div class="mt-1 p-2 card">
   @include('admin.layouts.message')
   <form action="{{ route($route.'update', $single->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="row my-1">
         <div class="col-sm-6">
            <label for="account_id" class="form-label">Account Number <span class="text-danger">*</span></label>
            <select class="form-select " id="account_id" name="account_id">
               <option value="">-- Select Account Number --</option>
               @foreach ($accounts as $account)
               <option value="{{ $account->id }}" {{ old('account_id', $single->account_id) == $account->id ? 'selected' : '' }}>
                  {{ $account->account_no }}
               </option>
               @endforeach
            </select>
            @error('account_id')
            <div class="text-danger">{{ $message }}</div>
            @enderror
         </div>
         <div class="col-sm-6">
            <label for="book_number" class="form-label">Book Number <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" id="book_number" name="book_number"
               value="{{ old('book_number', $single->book_number) }}">
            <small class="text-body">
               <strong>Note:</strong> Some cheque books may not have a printed book number.
               Please assign a unique book number for identification.
            </small>
            @error('book_number')
            <div class="text-danger">{{ $message }}</div>
            @enderror
         </div>
      </div>
      <div class="row my-1">
         <div class="col-sm-6">
            <label for="start_cheque_no" class="form-label">Start Cheque Number <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="start_cheque_no" name="start_cheque_no" value="{{ old('start_cheque_no', $single->start_cheque_no) }}">
            @error('start_cheque_no')
            <div class="text-danger">{{ $message }}</div>
            @enderror
         </div>
         <div class="col-sm-6">
            <label for="end_cheque_no" class="form-label">End Cheque Number <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" id="branch_name" name="end_cheque_no"
               value="{{ old('end_cheque_no', $single->end_cheque_no) }}">
            @error('end_cheque_no')
            <div class="text-danger">{{ $message }}</div>
            @enderror
         </div>
      </div>
      <div class="row my-1">
         <div class="col-sm-6">
            <div class="btn-group" role="group">
               <button type="submit" class="btn btn-primary">Save</button>
               <a href="{{route($route.'index')}}" class="btn btn-info">Back</a>
            </div>
         </div>
      </div>
   </form>
</div>
@endsection
@section('footerjs')
@endsection