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
         <select class="form-select form-select-sm" id="account_id" style="max-width: 300px;">
            <option value="">-- Select Account Number --</option>
            @foreach ($accounts as $account)
            <option value="{{ $account->id }}">{{ $account->account_no }}</option>
            @endforeach
         </select>
         <input type="text" id="book_number" class="form-control form-control-sm" placeholder="Search By Cheque Book Number" style="max-width: 200px;">
         <button type="button" id="btn-search" class="btn btn-sm btn-primary">
            <i class="bx bx-search bx-xs"></i> Search
         </button>
      </div>
   </div>
   <table class="table table-sm table-bordered table-hover my-1" id="data-table">
      <thead>
         <tr class="table-primary text-white text-center">
            <th style="width: 8%;">#</th>
            <th style="width: 30%;">Account Number</th>
            <th style="width: 20%;">Book Number</th>
            <th style="width: 15%;">Start Cheque No.</th>
            <th style="width: 15%;">End Cheque No.</th>
            <th style="width: 12%;">Action</th>
         </tr>
      </thead>
   </table>
</div>
@endsection

@section('script')
<script>
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
               d.account_id = $("#account_id").val();
               d.book_number = $("#book_number").val();
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
               orderable: false,
               data: 'account_no'
            },
            {
               orderable: false,
               data: 'book_number',
               className: 'text-center'
            },
            {
               orderable: false,
               data: 'start_cheque_no',
               className: 'text-center'
            },
            {
               orderable: false,
               data: 'end_cheque_no',
               className: 'text-center'
            },
            {
               data: 'id',
               orderable: false,
               className: 'text-center',
               render: function(id) {
                  let edit_url    = edit_route.replace(':id', id);
                  let preview_url = preview_route.replace(':id', id);
                  let delete_url  = delete_route.replace(':id', id);

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

      $('#account_id').on('change', function(e) {
         table.draw();
      });

      $('#book_number').on('keyup', function(e) {
         if (e.which === 13) {
            table.draw();
         }
      });

      $('#btn-search').on('click', function() {
         table.draw();
      });

      $('#btn-refresh').on('click', function() {
         $("#account_id").val('');
         $("#book_number").val('');
         table.order([
            [0, 'desc']
         ]).draw();
      });
   });
</script>
@endsection