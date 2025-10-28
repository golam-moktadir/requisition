<?php

namespace Modules\IncomeExpense\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Http\Request;

use Modules\IncomeExpense\Models\AccountHeads;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use DB;
 
class AccountsHeadDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query, Request $request): EloquentDataTable
    {
        return (new EloquentDataTable($query))
                ->setRowId('id')
                // ->addIndexColumn()
                ->addColumn('head_category', '{{ $head_category=="1"?"Income":"Expense" }}')
                ->addColumn('status', '{{ $status=="1"?"Active":"InActive" }}')
                // ->addColumn('created_at', '{{ date("Y-m-d H:i:sA", strtotime($created_at)) }}')
                // ->rawColumns(['head_category'])
                ->addColumn('action', 'incomeexpense::accounts-head.datatables_actions')
                // ->addColumn('action', function($row){ 
                //     $btn = "<a href='javascript:void(0)' class='edit btn btn-primary btn-sm'>Edit {$row['id']}</a>"; 
                //     return $btn;    
                // })   
                ->filter(function ($query) use ($request){
                    // if ($request->has('account_head_name') and $request->get('account_head_name') != '') {
                    if ($request->get('search')['value'] != '') {
                        $query->where('account_head_name', 'like', "%". $request->get('search')['value'] ."%");
                    }
                })             
                ;
    }
 
    public function query(AccountHeads $model): QueryBuilder
    {
        return $model->newQuery()
                     ->where('parent_id', '0')
                     // ->select(  'account_head_name', 
                     //            'id', 
                     //            DB::raw('head_category AS head_category_id'),
                     //            DB::raw('IF(head_category=1, "Income", "Expense") AS head_category'), 
                     //            'created_at'
                     //        )
                    ;
    }
 
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('account-heads-parent-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(0, 1)
                    ->selectStyleSingle()
                    // ->editors([
                    //     Editor::make()
                    //           ->fields([
                    //               Fields\Text::make('name'),
                    //               Fields\Text::make('email'),
                    //           ]),
                    // ])                    
                    // ->buttons([
                    //     Button::make('add'),
                    //     Button::make('csv'),
                    //     Button::make('excel'),
                    //     Button::make('pdf'),
                    //     Button::make('print'),
                    //     Button::make('reset'),
                    //     Button::make('reload'),
                    // ])
                    ->parameters([
                            'order'     => [[1, 'asc']],
                            'stateSave' => false,
                            // 'layout' => ['topStart'=>'buttons', 'topEnd'=>null],
                            // 'dom' => 'Bfrtip',
                            // 'buttons' => ['csv', 'excel', 'pdf', 'print', 'reset', 'reload'],
                            // 'buttons'   => [
                            //     ['extend' => 'export', 'className' => 'btn btn-default btn-sm no-corner', 'text' => '<span><i class="fa fa-download"></i> Xuất&nbsp;<span class="caret"></span></span>'],
                            //     ['extend' => 'print', 'className' => 'btn btn-default btn-sm no-corner', 'text' => '<span><i class="fa fa-print"></i> In</span>'],
                            //     ['extend' => 'reset', 'className' => 'btn btn-default btn-sm no-corner', 'text' => '<span><i class="fa fa-undo"></i> Cài lại</span>'],
                            //     ['extend' => 'reload', 'className' => 'btn btn-default btn-sm no-corner', 'text' => '<span><i class="fa fa-refresh"></i> Tải lại</span>'],
                            // ],                            
                            'dom' => 'Bfrtip',
                            'buttons' => ['csv', 'excel', 'print', 'reset', 'reload'],
                        ]
                        // , $this->getBuilderParameters()
                    )
                    ->addAction(
                            [
                                'width'          => '80px', 
                                'printable'      => false, 
                                'defaultContent' => '',
                                'data'           => 'action',
                                'name'           => 'action',
                                'title'          => 'Action',
                                'render'         => null,
                                'orderable'      => false,
                                'searchable'     => false,
                                'exportable'     => false,
                                'footer'         => '',
                            ] 
                        )
                    ;
    }
 
    public function getColumns(): array
    {
        $action = new Column([
            'title' => 'Action',
            'printable' => false,
            'orderable' => false,
            'searchable' => false,
            'exportable' => false,
        ]);

        return [
            // Column::make('id'),
            Column::make('row_number')
                    ->title('#')
                    ->render('meta.row + meta.settings._iDisplayStart + 1;')
                    ->width(60)
                    ->orderable(false),

            Column::make('account_head_name')->title('Account Head'),
            Column::make('head_category')->title('Category'),
            Column::make('status'),
            // $action->addClass('data-class')->content('<a href="-id $i">Edit</a>'),
        ];
    }
 
    protected function filename(): string
    {
        return 'Account_Heads_'.date('YmdHis') . '_' . mt_rand();
    }
}