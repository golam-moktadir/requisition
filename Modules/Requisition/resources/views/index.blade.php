@extends('admin.layouts.master')

@section('content-body')
<div class="mt-1 p-2 card">
    @if(session('success'))
    <div class="row my-1">
        <div class="col-12">
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        </div>
    </div>
    @endif

    <div class="row my-1">
        <div class="col-12 d-flex gap-1">
            <a href="{{ route($route.'create') }}" class="btn btn-sm btn-primary">
                <i class="bx bx-plus bx-xs"></i> Add New
            </a>
            <button type="button" id="btn-refresh" class="btn btn-sm btn-primary">
                <i class="bx bx-refresh bx-xs"></i> Refresh
            </button>
            <input type="text" id="requisition_no" class="form-control form-control-sm" placeholder="Search Requisition No." style="max-width: 150px;">
            <select class="form-select form-select-sm" id="company_id" style="max-width: 150px;">
                <option value="">Select Company</option>
                @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
            <input type="text" class="form-control form-control-sm" id="from_date" placeholder="From Date" style="max-width: 150px;">
            <input type="text" class="form-control form-control-sm" id="to_date" placeholder="To Date" style="max-width: 150px;">

            <button type="button" id="btn-search" class="btn btn-sm btn-primary">
                <i class="bx bx-search bx-xs"></i> Search
            </button>
        </div>
    </div>
    <table class="table table-sm table-bordered table-hover my-1" id="data-table">
        <thead>
            <tr class="table-primary text-white text-center">
                <th>#</th>
                <th>Requision No.</th>
                <th>Date</th>
                <th>Company Name</th>
                <th>Purpose Name</th>
                <th>Amount (TK)</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        let csrf_token = '{{ csrf_token() }}';
        let edit_route = "{{ route($route.'edit', ':id') }}";
        let preview_route = "{{ route($route.'show', ':id') }}";
        let delete_route = "{{ route($route.'destroy', ':id') }}";

        var table = $('#data-table').DataTable({
            searching: false,
            serverSide: true,
            processing: true,
            autoWidth: false,
            ajax: {
                url: "{{ route($route.'get-data-list') }}",
                data: function(d) {
                    d.requisition_no = $("#requisition_no").val();
                    d.company_id = $("#company_id").val();
                    d.from_date = $("#from_date").val();
                    d.to_date = $("#to_date").val();
                }
            },
            order: [
                [0, 'desc']
            ],
            columns: [{
                    data: null,
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    orderable: true,
                    data: 'req_no'
                },
                {
                    orderable: true,
                    data: 'created_date',
                    className: 'text-center'
                },
                {
                    orderable: true,
                    data: 'company_name',
                    className: 'text-left'
                },
                {
                    orderable: true,
                    data: 'purpose_name',
                    className: 'text-left'
                },
                {
                    orderable: false,
                    data: 'total_amount',
                    className: 'text-end'
                },
                {
                    orderable: true,
                    data: 'status',
                    className: 'text-center'
                },
                {
                    data: 'id',
                    orderable: false,
                    className: 'text-center',
                    render: function(id) {
                        let edit_url = edit_route.replace(':id', id);
                        let preview_url = preview_route.replace(':id', id);
                        let delete_url = delete_route.replace(':id', id);

                        return `
                        <a href="${edit_url}" class="btn btn-sm btn-primary btn-icon"><i class="bx bx-edit bx-xs"></i></a>
                        <a href="${preview_url}" class="btn btn-sm btn-info btn-icon" title="Preview"><i class="bx bx-show bx-xs"></i></a>
                        <form action='${delete_url}' method='POST' style='display:inline;'>
                           <input type='hidden' name='_token' value='${csrf_token}'>
                           <input type="hidden" name="_method" value="DELETE">
                           <button type="submit" onclick="return confirm('Are you sure you want to delete this?')" class="btn btn-sm btn-danger btn-icon" title="Delete"><i class="bx bx-trash bx-xs"></i>
                           </button>
                        </form>
                    `;
                    }
                }
            ]
        });

        $('#requisition_no').on('keyup', function(e) {
            if (e.which === 13) {
                table.draw();
            }
        });

        $('#company_id').on('change', function(e) {
            table.draw();
        });

        $('#btn-search').on('click', function() {
            table.draw();
        });

        $('#btn-refresh').on('click', function() {
            $("#requisition_no").val('');
            $("#company_id").val('');
            $("#from_date").val('');
            $("#to_date").val('');
            table.order([
                [0, 'desc']
            ]).draw();
        });

        flatpickr("#from_date, #to_date", {
            dateFormat: "Y-m-d",
            //defaultDate: "today",
            // maxDate: "today"    
        });
    });
</script>

@endsection