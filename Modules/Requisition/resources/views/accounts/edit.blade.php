@extends('admin.layouts.master')

@section('content-body')
<div class="mt-1 p-2 card">
   @include('admin.layouts.message')
   <form action="{{ route($route.'update', $single->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="row my-1">
         <div class="col-sm-6">
            <label for="bank_id" class="form-label">Bank Name <span class="text-danger">*</span></label>
            <select class="form-select" id="bank_id" name="bank_id">
               <option value="">-- Select Bank --</option>
               @foreach ($banks as $bank)
               <option value="{{ $bank->id }}" {{ old('bank_id', $single->bank_id) == $bank->id ? 'selected' : '' }}>
                  {{ $bank->bank_name }}
               </option>
               @endforeach
            </select>
            @error('bank_id')
            <div class="text-danger">{{ $message }}</div>
            @enderror
         </div>
         <div class="col-sm-6">
            <label for="account_holder_name" class="form-label">Account Holder Name <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" id="account_holder_name" name="account_holder_name"
               value="{{ old('account_holder_name', $single->account_holder_name) }}">
            @error('account_holder_name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
         </div>
      </div>
      <div class="row my-1">
         <div class="col-sm-6">
            <label for="account_number" class="form-label">Account Number <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="account_number" name="account_number" value="{{ old('account_number', $single->account_number) }}">
            @error('account_number')
            <div class="text-danger">{{ $message }}</div>
            @enderror
         </div>
         <div class="col-sm-6">
            <label for="branch_name" class="form-label">Branch Name <span class="text-danger">*</span>
            </label>
            <input type="text" class="form-control" id="branch_name" name="branch_name"
               value="{{ old('branch_name', $single->branch_name) }}">
            @error('branch_name')
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