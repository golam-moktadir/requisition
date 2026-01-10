@extends('admin.layouts.master')

@section('content-body')
<div class="card">
   <div class="card-header">
      <strong>Cheque Book Preview</strong>
   </div>
   <div class="card-body">
      <div class="row my-1">
         <div class="col-md-6">
            <table class="table table-sm table-bordered">
               <tr>
                  <th width="40%">Account Number</th>
                  <td>{{ $single->account->account_number }}</td>
               </tr>
               <tr>
                  <th>Account Holder</th>
                  <td>{{ $single->account->account_holder_name }}</td>
               </tr>
               <tr>
                  <th>Branch Name</th>
                  <td>{{ $single->account->branch_name }}</td>
               </tr>
               <tr>
                  <th>Bank Name</th>
                  <td>{{ $single->account->bank->bank_name }}</td>
               </tr>
            </table>
         </div>
         <div class="col-md-6">
            <table class="table table-sm table-bordered">
               <tr>
                  <th width="40%">Book Number</th>
                  <td>{{ $single->book_number }}</td>
               </tr>
               <tr>
                  <th>Cheque Range</th>
                  <td>
                     {{ $single->start_cheque_no }} → {{ $single->end_cheque_no }}
                  </td>
               </tr>
            </table>
         </div>
      </div>
      <div class="row my-2">
         <div class="col-md-6">
            <table class="table table-sm table-striped table-bordered text-center">
               <thead class="table-light">
                  <tr>
                     <th width="40%">#</th>
                     <th>Cheque Number</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach($single->cheques as $cheque)
                  <tr>
                     <td>{{ $loop->iteration }}</td>
                     <td>{{ $cheque->cheque_no }}</td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
      <div class="">
         <a href="{{ route($route.'index') }}" class="btn btn-secondary">Back</a>
      </div>
   </div>
   @endsection