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
<!--                 <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required placeholder="Title" tabindex="3" autofocus>
                    @error('title') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div> -->

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" required placeholder="Description" tabindex="4" rows="6" maxlength="1000">{{ old('description') }}</textarea>
                    @error('description') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" required placeholder="Amount" tabindex="5" step="0.01">
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
    </script>
@endsection
