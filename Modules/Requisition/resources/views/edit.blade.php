@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('requisition.index') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    Edit
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')   
        <div class="col-8">
            <form action="{{ route('requisition.update', $single->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="company_id" class="form-label">Company Name</label>
                    <select class="form-control" id="company_id" name="company_id" required tabindex="1" autofocus>
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id', $single->company_id) == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
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
                            <option value="{{ $purpose->id }}" {{ old('purpose_id', $single->purpose_id) == $purpose->id ? 'selected' : '' }}>{{ $purpose->purpose_name }}</option>
                        @endforeach
                    </select>
                    @error('purpose_id') 
                    <div class="text-danger">
                        {{ $message }}
                    </div> 
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" required placeholder="Description" tabindex="5" rows="6" maxlength="1000">{{ old('description', $single->description )  }}</textarea>
                    @error('description') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount', $single->amount) }}" required placeholder="Amount" tabindex="6" step="0.01">
                    @error('amount') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="requested_to" class="form-label">Requested To</label>
                    <select class="form-control" id="requested_to" name="requested_to" required tabindex="6">
                        <option value="">Select Requested To</option>
                        <option value="ceo" {{ old('requested_to', $single->requested_to) == 'ceo' ? 'selected' : '' }}>CEO</option>
                        <option value="managing_director" {{ old('requested_to', $single->requested_to) == 'managing_director' ? 'selected' : '' }}>Managing Director</option>
                        <option value="manager" {{ old('requested_to', $single->requested_to) == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="accountant" {{ old('requested_to', $single->requested_to) == 'accountant' ? 'selected' : '' }}>Accountant</option>
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
                @if($files->count() > 0)
                <div class="mb-3">
                    <label class="form-label">Existing Files</label>
                    <ul class="list-group">
                        @foreach($files as $file)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ asset('storage/requisitions/'.$file->file_name) }}" target="_blank">
                                    {{ $file->file_name }}
                                </a>
                                <button type="button" class="btn btn-sm btn-danger remove-file" data-file-id="{{ $file->file_name }}">
                                    Delete
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-primary">Save</button>
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

        const deleteFileRoute = "{{ route('requisition.file.destroy', ':id') }}";

        $(".remove-file").on('click', function() {
            const file_id = $(this).data('file-id');
            const button = $(this);
            const url = deleteFileRoute.replace(':id', file_id);

            if (confirm('Are you sure you want to delete this file?')) {
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success:function(response) {
                            if(response){
                                button.closest('li').remove();
                            }
                    }
                });
            }
        });        
    });
    </script>

@endsection
