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
        <div class="p-2" id="print-body">
        <div class="row">
            <h3 class="text-center text-dark">{{ $single->company_name }}</h3>                    
            <h3 class="text-center text-dark">REQUISITION - {{ $single->req_no }}</h3>                    
            <h3 class="text-center text-dark">{{ $single->payee_name }}</h3>                    

            <div class="text-center text-dark">A/C Name - {{ $single->account_holder_name }}</div>
        </div>
        <div class="row mt-3 text-dark">
            <div class="col-4">
                Date: {{ $single->created_at }}
            </div>
            <div class="col-4 text-center">
                &nbsp;
            </div>
            <div class="col-4">
                Status: {{ ucfirst($single->status) }}
            </div>
        </div>
        <div class="row mt-3 text-dark">
            <div class="col-4">
                Payment Mode: {{ $payment->payment_type_text ?? '' }}
            </div>
            <div class="col-4 text-center">
                @if($payment?->payment_type == 1)
                    Cheque No.: {{ $payment->cheque->cheque_no ?? '' }}
                @endif
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
                            <th colspan="3" class="text-center text-dark">{{ $single->purpose_name }}</th>
                        </tr>
                        <tr class="text-center">
                            <th class="text-dark">SL No.</th>
                            <th class="text-dark">Details</th>
                            <th class="text-dark">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $grand_total_amount = 0;
                        @endphp
                        @foreach($details as $row)
                        @php
                            $grand_total_amount += $row->amount;
                        @endphp
                        <tr>
                            <td class='text-center text-dark'>{{ $loop->iteration }}</td>
                            <td class='text-dark'>{{ $row->description }}</td>
                            <td class='text-end text-dark'>{{ $row->amount }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="text-dark" colspan="2">Total : {{ \App\Helpers\CommonHelper::get_NumberInWord($grand_total_amount) }} Taka</td>
                            <td class="text-end text-dark">{{ number_format($grand_total_amount, 2, '.', '') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="row mt-5 text-dark">
            <div class="col-6">Prepared By : {{ $single->prepared_by }} </div>
            <div class="col-6 text-center">
                Approved By : {{ $approved?->user?->name }}
            </div>
        </div>
        <div class="row mt-5 text-dark">
            <div class="col-6">Verified By</div>
        </div>
        </div>
        @if(in_array($single->status, ['pending', 'rejected']) && Auth::user()->role == 'admin')
        <form action="{{ route('requisition.store_approval', ['id' => $single->id]) }}" method="POST">
            @csrf
            <div class="row mt-3">
                <div class="col-3">
                    <label for="remarks" class="form-label text-dark">Remarks</label>
                    <input type="text" class="form-control" id="remarks" name="remarks" value="{{ old('remarks') }}" placeholder="Remarks">
                    @error('remarks') 
                        <div class="text-danger">
                            {{ $message }}
                        </div> 
                    @enderror
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-6">
                    <div class="btn-group" role="group">
                        <button type="submit" class="btn btn-primary" name="status" value="approved">Approve</button>
                        <button type="submit" class="btn btn-warning" name="status" value="rejected">Reject</button>
                        <button type="submit" class="btn btn-info" name="status" value="returned">Return</button>
                    </div> 
                </div>
            </div>
        </form>
        @endif
        <div class="row mt-3">
            <div class="col-3">
                <button type="button" class="btn btn-secondary" id="printButton">Print</button>
                @if(in_array($single->status, ['approved', 'issued']))
                    @if($single->payment_count == 0)
                        <a href="{{ route('requisition.add-payment', ['id' => $single->id]) }}" class="btn btn-info">Add Payment</a>
                    @else
                        <a href="{{ route('requisition.edit-payment', ['id' => $single->id]) }}" class="btn btn-success">Edit Payment</a>
                    @endif
                @endif
            </div>
        </div>
        @if(isset($payment->files))
        <div class="row mt-3">
            <div class="col-9">
                <table class="table table-bordered table-hover table-sm">
                    <thead>
                        <tr class="text-center">
                            <th class="text-dark">#</th>
                            <th class="text-dark">Payment Attached Files</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(json_decode($payment->files) as $file)
                        <tr>
                            <td class="text-center text-dark">{{ $loop->iteration }}</td>
                            <td class="text-dark">
                                <a href="{{ asset('storage/payments/'.$file->name) }}" target="_blank">
                                    {{ $file->title }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        @if(count($files) > 0)
        <div class="row mt-3">
            <div class="col-9">
                <table class="table table-bordered table-hover table-sm">
                    <thead>
                        <tr class="text-center">
                            <th class="text-dark">#</th>
                            <th class="text-dark">Requisition Attached Files</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($files as $file)
                        <tr>
                            <td class="text-center text-dark">{{ $loop->iteration }}</td>
                            <td class="text-dark">
                                <a href="{{ asset('storage/requisitions/'.$file->file_name) }}" target="_blank">
                                    {{ $file->title ?? $file->file_name }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        @if(count($approvals) > 0)
        <div class="row mt-3">
            <div class="col-9">
                <table class="table table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                            <th colspan="5" class="text-center text-dark">Status History</th>
                        </tr>
                        <tr class="text-center">
                            <th class="text-dark">#</th>
                            <th class="text-dark">Status</th>
                            <th class="text-dark">Remarks</th>
                            <th class="text-dark">Updated By</th>
                            <th class="text-dark">Action Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($approvals AS $approval)
                        <tr>
                            <td class="text-center text-dark">{{ $loop->iteration }}</td>
                            <td class="text-center text-dark">{{ ucfirst($approval->status) }}</td>
                            <td class="text-dark">{{ $approval->remarks }}</td>
                            <td class="text-dark">{{ $approval->user->name }}</td>
                            <td class="text-center text-dark">{{ $approval->created_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
@endsection
@section('footerjs')
<script>
$('#printButton').on('click', function() {
    var printContents = document.getElementById('print-body').innerHTML;

    // Open new print window
    var printWindow = window.open('', '', 'height=800,width=1000');
    printWindow.document.write('<html><head><title>Print</title>');

    printWindow.document.write('<link rel="stylesheet" href="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/css/core.css" class="template-customizer-core-css">');

    printWindow.document.write('<link rel="stylesheet" href="{{ URL('/') }}/sneat-bootstrap5-theme/assets/vendor/css/theme-default.css" class="template-customizer-theme-css">');

    printWindow.document.write('<style>@media print { *{ background: transparent !important; color: #000 !important; box-shadow: none !important; } }</style>');

    printWindow.document.write('</head><body>');
    printWindow.document.write(printContents);
    printWindow.document.write('</body></html>');

    printWindow.document.close();
    printWindow.focus();

    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 500);
});
</script>

@endsection

