@extends('admin.layouts.yajra')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('requisition.index') }}">{{ $title }}</a>
    </li>
    <li class="breadcrumb-item">
        All
    </li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')
        <form method="POST" action="{{ route('permission.store') }}" id="permission-form" class="">
        @csrf
        <table class="table table-sm table-bordered table-hover mt-2">
            <thead class="table-primary text-center">
                <tr class="">
                    <th colspan="2" class="">Feature Info</th>
                    <th colspan="4" class="">Permission Level</th>
                    <th rowspan="2" class="align-middle">
                        <label for="select-all" class="d-flex align-items-center justify-content-center gap-1">
                            <input type="checkbox" id="select-all" class="checkbox-input"
                                aria-label="Toggle all permissions"> All
                        </label>
                    </th>
                </tr>
                <tr class="">
                    <th class="">#</th>
                    <th class="">Page Name</th>
                    <th class="">
                        <label class="d-flex align-items-center justify-content-center gap-1">
                            <input type="checkbox" class="" data-column="view" aria-label="Toggle column view"> View
                        </label>
                    </th>
                    <th class="">
                        <label class="d-flex align-items-center justify-content-center gap-1">
                            <input type="checkbox" class="" data-column="insert" aria-label="Toggle column insert"> Insert
                        </label>
                    </th>
                    <th class="">
                        <label class="d-flex align-items-center justify-content-center gap-1">
                            <input type="checkbox" class="" data-column="update" aria-label="Toggle column update"> Update
                        </label>
                    </th>
                    <th class="">
                        <label class="d-flex align-items-center justify-content-center gap-1">
                            <input type="checkbox" class="" data-column="delete" aria-label="Toggle column delete"> Delete
                        </label>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($features as $feature)
                    @php $pid = (int) $feature->page_id; @endphp
                    <tr id="row-{{ $pid }}">
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $feature->page_title }}</td>
                        <td class="text-center">
                            <input type="checkbox" name="view_ids[]" value="1" class="" data-row="row-{{ $pid }}"
                                data-type="view" @checked(in_array($pid, old('view_ids', [])))
                                aria-label="View for {{ $feature->page_title }}">
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="insert_ids[]" value="1" class="" data-row="row-{{ $pid }}"
                                data-type="insert" @checked(in_array($pid, old('insert_ids', [])))
                                aria-label="Insert for {{ $feature->page_title }}">
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="update_ids[]" value="1" class="" data-row="row-{{ $pid }}"
                                data-type="update" @checked(in_array($pid, old('update_ids', [])))
                                aria-label="Update for {{ $feature->page_title }}">
                        </td>
                        <td class="text-center">
                            <input type="checkbox" name="delete_ids[]" value="1" class="" data-row="row-{{ $pid }}"
                                data-type="delete" @checked(in_array($pid, old('delete_ids', [])))
                                aria-label="Delete for {{ $feature->page_title }}">
                        </td>
                        <td class="text-center">
                            <input type="checkbox" class="" data-row="row-{{ $pid }}"
                                aria-label="Toggle all for {{ $feature->page_title }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="my-1">
            <button type="submit" class="btn btn-sm btn-info"> Save</button>
            <a href="#" class="btn btn-sm btn-primary">Back</a>
        </div>
        </form>
    </div>
@endsection

@section('footerjs')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#select-all').on('change', function () {
                const checked = $(this).is(':checked');
                $('tbody input[type="checkbox"]').prop('checked', checked);
                $('thead input[data-column]').prop('checked', checked);
            });

            $('thead input[data-column]').on('change', function () {
                const column = $(this).data('column');
                const checked = $(this).is(':checked');
                $('tbody input[data-type="' + column + '"]').prop('checked', checked);
            });

            $('tbody input[data-row][aria-label^="Toggle"]').on('change', function () {
                const row = $(this).data('row');
                const checked = $(this).is(':checked');
                $('tbody input[data-row="' + row + '"]').not(this).prop('checked', checked);
            });
        });

    </script>

@endsection