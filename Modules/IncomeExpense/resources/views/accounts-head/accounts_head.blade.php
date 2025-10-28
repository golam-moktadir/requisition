@extends('admin.layouts.yajra')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('income_expense.accounts_head') }}">{{ $title }}</a>
</li>
<li class="breadcrumb-item">
    All
</li>
@endsection

@section('content-body')
    <div class="mt-1 p-2 card">
        @include('admin.layouts.message')
        <p>
            <a href="{{ route('income_expense.create_accounts_head') }}" class="btn btn-md btn-primary">Create New Head</a>
        </p>

        {{ $dataTable->table() }}
    </div>
@endsection

@section('footerjs')
{{ $dataTable->scripts(attributes: ['type' => 'module', 'className'=>'account-head']) }}
<script></script>
@endsection
