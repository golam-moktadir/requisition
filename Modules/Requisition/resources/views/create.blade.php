@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('requisition.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    Create
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')   
        <div class="col-8">
            <form action="{{ route('requisition.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="company_id" class="form-label">Company Name</label>
                    <select class="form-control" id="company_id" name="company_id" required tabindex="1" autofocus>
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                    @error('company_id') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="purpose_id" class="form-label">Purpose Name</label>
                    <select class="form-control" id="purpose_id" name="purpose_id" required tabindex="2" autofocus>
                        <option value="">Select Purpose</option>
                        @foreach($purposes as $purpose)
                            <option value="{{ $purpose->id }}" {{ old('purpose_id') == $purpose->id ? 'selected' : '' }}>{{ $purpose->purpose_name }}</option>
                        @endforeach
                    </select>
                    @error('purpose_id') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="payee_id" class="form-label">Payee Name</label>
                    <select class="form-control" id="payee_id" name="payee_id" tabindex="2" autofocus>
                        <option value="">Select Payee</option>
                        @foreach($payees as $row)
                            <option value="{{ $row->id }}" {{ old('payee_id') == $row->id ? 'selected' : '' }}>{{ $row->payee_name }}</option>
                        @endforeach
                    </select>
                    @error('payee_id') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" placeholder="Description" tabindex="4" rows="6" maxlength="1000">{{ old('description') }}</textarea>
                    @error('description') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" required placeholder="Amount" tabindex="5" step="0.01">
                    <p><strong>In Words:</strong> <span id="inWords" class="text-danger"></span></p>
                    @error('amount') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="requested_to" class="form-label">Requested To</label>
                    <select class="form-control" id="requested_to" name="requested_to" required tabindex="6">
                        <option value="">Select Requested To</option>
                        <option value="ceo" {{ old('requested_to') == 'ceo' ? 'selected' : '' }}>CEO</option>
                        <option value="managing_director" {{ old('requested_to') == 'managing_director' ? 'selected' : '' }}>Managing Director</option>
                        <option value="manager" {{ old('requested_to') == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="accountant" {{ old('requested_to') == 'accountant' ? 'selected' : '' }}>Accountant</option>
                    </select>
                    @error('requested_to') 
                        <div class="text-danger">
                            {{ $message }}
                        </div> 
                    @enderror
                </div>
                <div id="file-inputs">                
                    <div class="mb-1">
                        <label for="files" class="form-label">Attach Files</label>
                        <input type="file" class="form-control" id="files" name="files[]">
                    </div>
                </div>
                <div class="mb-3">
                    <button type="button" class="btn btn-sm btn-secondary" id="add-more">Add More</button>
                </div>
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <a href="{{route('requisition.index')}}" class="btn btn-info">Return back</a>
                </div>   
            </form>   
        </div>
    </div>
@endsection
@section('footerjs')
    <script type="text/javascript">
    $(document).ready(function() {
        $('#add-more').on('click', function() {
            let input = $('<div class="mb-1"><input type="file" name="files[]" class="form-control"></div>');
            $('#file-inputs').append(input);
        });
    });

    function numberToWords(num) {
        const a = [
            '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
            'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen',
            'Eighteen', 'Nineteen'
        ];
        const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        function inWords(n) {
            if (n < 20) return a[n];
            if (n < 100) return b[Math.floor(n / 10)] + (n % 10 ? ' ' + a[n % 10] : '');
            if (n < 1000) return a[Math.floor(n / 100)] + ' Hundred' + (n % 100 ? ' ' + inWords(n % 100) : '');
            if (n < 100000) return inWords(Math.floor(n / 1000)) + ' Thousand' + (n % 1000 ? ' ' + inWords(n % 1000) : '');
            if (n < 10000000) return inWords(Math.floor(n / 100000)) + ' Lakh' + (n % 100000 ? ' ' + inWords(n % 100000) : '');
            return inWords(Math.floor(n / 10000000)) + ' Crore' + (n % 10000000 ? ' ' + inWords(n % 10000000) : '');
        }

        if (num === 0) return 'Zero Taka';

        const parts = num.toString().split('.');
        const taka = parseInt(parts[0]);
        const paisa = parts[1] ? parseInt(parts[1].substring(0, 2).padEnd(2, '0')) : 0;

        let words = '';
        if (taka > 0) words += inWords(taka) + ' Taka';
        if (paisa > 0) words += (words ? ' and ' : '') + inWords(paisa) + ' Paisa';

        return words || 'Zero Taka';
    }

    // attach listener
    document.getElementById('amount').addEventListener('input', function() {
        const amount = parseFloat(this.value);
        document.getElementById('inWords').textContent = isNaN(amount) ? '' : numberToWords(amount);
    });
    </script>

@endsection
