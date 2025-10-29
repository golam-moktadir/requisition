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
            <h3 class="text-center">REQUISITION - {{ $single->id }}</h3>                    
            <h3 class="text-center">{{ $single->company_name }}</h3>                    

            <div class="text-center">A/C Name - Software Shop Limited</div>
            <div class="text-center">A/C No - 107-250-8652</div>
        </div>
        <div class="row mt-3">
            <div class="col-4">
                Date: {{ $single->created_at }}
            </div>
            <div class="col-4 text-center">
                Paribahan.com
            </div>
            <div class="col-4">
                Status:
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-4">
                Cheque no: 
            </div>
            <div class="col-4 text-center">
                Cheque Amount:
            </div>
            <div class="col-4">
                Received:
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <table class="table table-sm table-bordered table-hover">
                    <thead>
                        <tr>
                            <th colspan="4" class="text-center">Office Expense</th>
                        </tr>
                        <tr>
                            <th class="text-center">SL No.</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Details</th>
                            <th class="text-center">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td class="text-center">{{ $single->created_at }}</td>
                            <td class="">{{ $single->purpose_name }}</td>
                            <td class="text-end">{{ $single->amount }}</td>
                        </tr>
                        <tr>
                            <td class="text-center">&nbsp;</td>
                            <td class="text-center">&nbsp;</td>
                            <td class="text-end">Total=</td>
                            <td class="text-end">{{ $single->amount }}</td>
                        </tr>
                    </tbody>
                </table>
                <div>
                    {{ \App\Helpers\CommonHelper::get_NumberInWord($single->amount) }} Taka Only
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-6">Prepared By</div>
            <div class="col-6 text-center">Approved By</div>
        </div>
        <div class="row mt-5">
            <div class="col-6">Verified By</div>
        </div>
        @if(in_array($single->status, ['pending', 'rejected']))
        <form action="{{ route('requisition.store_approval', ['id' => $single->id]) }}" method="POST">
            @csrf
            <div class="row mt-3">
                <div class="col-6">
                    <div class="btn-group" role="group">
                        <button type="submit" class="btn btn-primary" name="status" value="approved">Approve</button>
                        <button type="submit" class="btn btn-warning" name="status" value="rejected">Reject</button>
                        <button type="submit" class="btn btn-info" name="status" value="pending">Return</button>
                    </div> 
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-3">
                    <label for="remarks" class="form-label">Remarks</label>
                    <input type="text" class="form-control" id="remarks" name="remarks" value="{{ old('remarks') }}">
                    @error('remarks') 
                        <div class="text-danger">
                            {{ $message }}
                        </div> 
                    @enderror
                </div>
            </div>
        </form>
        @endif
    </div>

@endsection

@section('footerjs')
<script type="text/javascript">
    $(document).ready(function(){
        flatpickr("#activity_date", {
            dateFormat: "Y-m-d",
            defaultDate: "today",
            // maxDate: "today"    
        });
    });
</script>

@endsection
