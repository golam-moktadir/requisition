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
         <a href="{{ route('bank.account.create') }}" class="btn btn-sm btn-primary">
            <i class="bx bx-plus bx-xs"></i> Add New
         </a>
         <button type="button" id="btn-refresh" class="btn btn-sm btn-primary">
            <i class="bx bx-refresh bx-xs"></i> Refresh
         </button>
         <input type="text" id="bank_name" class="form-control form-control-sm" placeholder="Search Bank Name" style="max-width: 200px;">
         <button type="button" id="btn-search" class="btn btn-sm btn-primary">
            <i class="bx bx-search bx-xs"></i> Search
         </button>
      </div>
   </div>
   <table class="table table-sm table-bordered table-hover my-1" id="data-table">
      <thead>
         <tr class="table-primary text-white text-center">
            <th style="width: 10%;">#</th>
            <th style="width: 20%;">Account Number</th>
            <th style="width: 20%;">Bank Name</th>
            <th style="width: 20%;">Account Holder Name</th>
            <th style="width: 20%;">Branch Name</th>
            <th style="width: 10%;">Action</th>
         </tr>
      </thead>
   </table>
</div>
@endsection

@section('script')
<script>
   $(document).ready(function() {
      let csrf_token = '{{ csrf_token() }}';
      let edit_route = "{{ route('bank.account.edit', ':id') }}";
      let delete_route = "{{ route('bank.destroy', ':id') }}";

      var table = $('#data-table').DataTable({
         searching: false,
         serverSide: true,
         processing: true,
         autoWidth: false,
         // ajax: '{{ route("bank.get-data-list") }}',
         ajax: {
            url: "{{ route('bank.account.get-data-list') }}",
            data: function(d) {
               d.bank_name = $("#bank_name").val();
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
               data: 'account_number',
               orderable: false
            },
            {
               data: 'bank_name'
            },
            {
               data: 'account_holder_name'
            },
            {
               data: 'branch_name'
            },
            {
               data: 'id',
               orderable: false,
               className: 'text-center',
               render: function(id) {
                  let edit_url = edit_route.replace(':id', id);
                  let delete_url = delete_route.replace(':id', id);

                  return `
                        <a href="${edit_url}" class="btn btn-sm btn-primary"><i class="bx bx-edit bx-xs"></i></a>

                        <form action='${delete_url}' method='POST' style='display:inline;'>
                           <input type='hidden' name='_token' value='${csrf_token}'>
                           <input type="hidden" name="_method" value="DELETE">
                           <button type="submit" onclick="return confirm('Are you sure you want to delete this?')" class="btn btn-sm btn-danger" title="Delete"><i class="bx bx-trash bx-xs"></i>
                           </button>
                        </form>
                    `;
               }
            }
         ]
      });

      $('#bank_name').on('keyup', function() {
         table.draw();
      });

      $('#btn-search').on('click', function() {
         table.draw();
      });

      $('#btn-refresh').on('click', function() {
         $("#bank_name").val('');
         table.order([[0, 'desc']]).draw();
      });
   });
</script>
@endsection