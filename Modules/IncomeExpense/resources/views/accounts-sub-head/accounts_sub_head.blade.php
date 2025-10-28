@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('income_expense.account_sub_head') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    All
</li>
@endsection

@section('content-body')

    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')
        <p>
            <a href="{{ route('income_expense.create_accounts_sub_head') }}" class="btn btn-md btn-primary">
                Create New Sub Head
            </a>
        </p>

        <table class="account_sub_head_data mdl-data-table dataTable table" cellspacing="0" width="100%" role="grid" style="width: 100%;">
            <thead>
                <tr>
                    @foreach($tableColumnArray AS $tableHead)
                        @if($tableHead['title']!=null)
                            <th>{{ $tableHead['title'] }}</th>
                        @endif
                    @endforeach
                    <th width="100px">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    
@endsection

@section('footerjs')
<script>
    $(document).ready(function() {

        function serverSideButtonAction(){
            // alert('CSV OK');
        }

        var oTable = $('.account_sub_head_data').DataTable({
            "order": [[1, 'asc']],
            "scrollX": true,
            processing: true,
            serverSide: true,
            // ajax: '{{ route('income_expense.account_sub_head_data') }}',
            ajax: {
                url: '{{ route('income_expense.account_sub_head_data') }}',
                type: 'GET',
                // data: function (d) {
                //     d.amount = $('select option:selected').val();
                //     d.date_from = $('input[name=date_from]').val();
                //     d.date_to = $('input[name=date_to]').val();
                // }
            },            
            // columnDefs: [{
            //     targets: [0, 1, 2, 3],
            //     className: 'mdl-data-table__cell--non-numeric'
            // }],
           // columns: [
           //          { data: 'id', name: 'id' },
           //          { data: 'head_category', name: 'head_category' },
           //          { data: 'account_head_name', name: 'account_head_name' },
           //          { data: 'parent_id', name: 'parent_id' },
           //          { data: 'action', name: 'action', orderable: false, searchable: false},
           //       ],
           columns: {!! $tableColumn !!},
            "createdRow": function(row, data, dataIndex) {
                // Add the row number (auto-increment) to the first column
                $('td', row).eq(0).html(dataIndex + 1);  // dataIndex is the zero-based index of the row
            },
           dom: 'Blfrtip', 
            // dom: 'B<"clear">lfrtip', 
            buttons: [
                // {
                //     extend: 'collection',
                //     text: 'Selection',
                //     buttons: ['selectAll', 'selectNone']
                // },
                'csv', 
                // 'excel', 
                // 'pdf', 
                // 'print', 
                'reset', 
                'reload'
            ],       
            buttons_bak: [
                {
                    extend: 'collection',
                    text: 'Selection',
                    buttons: ['selectAll', 'selectNone']
                },
                {
                    extend: 'collection',
                    text: 'Export',
                    buttons: [
                        // 'export', 
                        'csv', 
                        'excel', 
                        'pdf'
                        // , {
                        //     extend: 'csv',
                        //     text: 'CSV',
                        //     className: 'btn btn-success btn-sm',
                        //     action: serverSideButtonAction
                        // }
                        // ,{ 
                        //     extend: 'excel',
                        //     text: 'Export Current Page',
                        //     exportOptions: {
                        //         modifier: {
                        //             page: 'current'
                        //         }
                        //     },
                        //     customize: function (xlsx)
                        //     {
                        //         var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        //         $('row:first c', sheet).attr('s', '7');
                        //     }
                        // }
                    ]
                }                
            ]          

        });

        $('#account_sub_head_data-search-form').on('submit', function (e) {
            oTable.draw();
            e.preventDefault();
        });

        // Export CSV functionality
        // $('.buttons-csv').on('click', function(e) {
        //     e.preventDefault();
        //     alert('OK...');
        //     return false;
        //     // Capture the search term (if any)
        //     var searchTerm = table.search();
        //     window.location.href = '{{ route('income_expense.account_sub_head_data') }}' + '?search[value]=' + searchTerm;
        // });        

    });
</script>
@endsection
