<?php

namespace Modules\IncomeExpense\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Library\DatatableExporter;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\IncomeExpense\DataTables\AccountsHeadDataTable;
use Modules\IncomeExpense\Http\Requests\AccountHeadsRequest;
use Modules\IncomeExpense\Http\Requests\AccountSubHeadsRequest;
use Modules\IncomeExpense\Models\AccountHeads;
use Modules\IncomeExpense\Repositories\AccountHeadsRepository;
use Modules\IncomeExpense\Services\AccountHeadService;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Services\DataTable;

class IncomeExpenseController extends Controller
{
    // function __construct(private AccountHeads $accountHeads, private AccountHeadService $accountHeadService) {
    //     $this->accountHeads = $accountHeads;   
    //     $this->accountHeadService = $accountHeadService;   
    // }

    /**
     * Display a listing of the resource.
     */
    public function accountsHead(AccountsHeadDataTable $dataTable)
    {
        return $dataTable->render('incomeexpense::accounts-head.accounts_head', ['title' => 'Accounts Head']); 

        // $this->accountHeads;
        // $query = DB::table('account_heads');
        // $collection = collect([
        //     ['id' => 1, 'name' => 'John'],
        //     ['id' => 2, 'name' => 'Jane'],
        //     ['id' => 3, 'name' => 'James'],
        // ]);        
        // dd(
        //     DataTables::of(AccountHeads::query())->toJson(),
        //     DataTables::eloquent(AccountHeads::query())->toJson(),
        //     DataTables::query($query)->toJson(),
        //     DataTables::collection($collection)->toJson()
        // );                
        // return view('incomeexpense::accounts_head',[
        //     'title' => 'Accounts Head',
        //     'dataTable' => $dataTable
        // ]);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        abort(403);
        return view('incomeexpense::show');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createAccountsHead()
    {
        return view(
            'incomeexpense::accounts-head.create',
            [
                'title' => 'Create Account Head',
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeAccountsHead(AccountHeadsRequest $request, AccountHeadService $accountHeadService)
    {
        $accountHead = $accountHeadService->create($request->toArray());

        return redirect()->route('income_expense.accounts_head')
                        ->with('success', 'Account head created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editAccountsHead(AccountHeads $accountHeads, Request $request)
    {
        if(empty($accountHeads->id)){
            abort(403);
        }

        $title = 'Update Account Head';

        return view('incomeexpense::accounts-head.edit', compact('accountHeads', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateAccountsHead(AccountHeadsRequest $request, AccountHeads $accountHeads, AccountHeadService $accountHeadService)
    {
        if(empty($accountHeads->id))
            abort(403);

        $accountHead = $accountHeadService->update($accountHeads->id, $request->toArray());

        return redirect()->route('income_expense.edit_accounts_head', $accountHeads->id)
                        ->with('success', 'Account head update successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        abort(403);

        $deleted = $this->productService->deleteProduct($id);
        return $deleted ? response()->json(['message' => 'Product deleted']) : response()->json(['message' => 'Product not found'], 404);
    }

    public $accountSubHeadTableColumn = [
        ['data' => 'id', 'name' => 'id', 'title' => 'S.L.'],
        ['data' => 'account_head_name', 'name' => 'account_head_name', 'title' => 'Name'],
        ['data' => 'parent_head', 'name' => 'parent_head', 'title' => 'Parent Head'],
        ['data' => 'head_category', 'name' => 'head_category', 'title' => 'Category'],
        ['data' => 'action', 'name' => 'action', 'title' => null, 'orderable'=>false, 'searchable' => false],
    ];

    public function accountSubHead(Request $request)
    {
        return view(
            'incomeexpense::accounts-sub-head.accounts_sub_head', 
            [
                'title' => 'Account Sub Head',
                'tableColumnArray' => $this->accountSubHeadTableColumn,
                'tableColumn' => json_encode($this->accountSubHeadTableColumn),
            ]
        );
    }

    public function accountSubHeadJson(Request $request)
    {       
        $datatableExporter = app(DatatableExporter::class);        
        $collection = AccountHeads::query()->with('parent')->where('parent_id', '>', '0'); 

        $dataTableCollection = DataTables::of($collection)
        ->addIndexColumn()
        ->editColumn('head_category', function ($collection) {
            return $collection->head_category ? "Income" : "Expense";
        })
        ->editColumn('parent_head', function ($collection) {
            return $collection->parent->account_head_name;
        })
        ->addColumn('action', function($row){
            $btn = '<a href="'.route('income_expense.edit_accounts_sub_head', $row['id']).'" class="edit btn btn-primary btn-sm">Edit</a>'; 
            return $btn; 
        })
        ->rawColumns(['action'])
        ->filter(function ($query) use ($request) {
           // if ($request->has('amount') and $request->get('amount') != 0) {
           //     $query->whereColumn('paid_amount', '<', 'total_amount');
           // }
           if ($request->get('search')['value'] !="")
           {
               $value = $request->get('search')['value'];
               if(substr($value ,0 ,1) =="B" || substr($value ,0 ,1) =="b")
               {
                   $status = 1;
               }
               else if(substr($value ,0 ,1) =="N" ||substr($value ,0 ,1) =="n")
               {
                   $status = 0;
               }
               else{
                   $status = $value;
               }
               $query  ->where('account_head_name','like','%'.$status.'%')
                       // ->orWhere('halls.name', 'like', '%'.$value.'%')
                       // ->orWhere('paid_amount', 'like', '%'.$value.'%')
                       // ->orWhere('total_amount', 'like', '%'.$value.'%')
                       // ->orWhere('date', 'like', '%'.$value.'%')
                       ;
            }
        })
        ->make(true);

        if ($request->get('action') == 'csv') {
            $headers = [];
            foreach ($this->accountSubHeadTableColumn as $key => $tableHead) {
                if($tableHead['title']!=null){
                    $headers[$tableHead['name']] = $tableHead['title'];
                }
            }
            $datatableExporter->exportCsv(
                'account-sub-head', 
                $headers, 
                json_decode($dataTableCollection->getContent(), true)['data']
            );
            exit;
        }  
        else {
            header('Content-Type: application/json');
            return $dataTableCollection;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createAccountsSubHead()
    {
        return view(
            'incomeexpense::accounts-sub-head.create',
            [
                'title' => 'Create Account Sub Head',
                'account_heads' => AccountHeadsRepository::getParentHeads(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeAccountsSubHead(AccountSubHeadsRequest $request)
    {
        $accountHead = AccountHeadsRepository::storeSubHead($request);

        if(empty($accountHead)){
            return redirect()->route('income_expense.account_sub_head')
                            ->with('error', 'Account sub head create failed!'); 
        }
        else {
            return redirect()->route('income_expense.account_sub_head')
                            ->with('success', 'Account sub head created successfully!');            
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editAccountsSubHead($id, Request $request)
    {
        $accountSubHeads = AccountHeads::where('id', $id)->where('parent_id', '>', '0')->firstOrFail();

        $title = 'Update Account Sub Head';
        $account_heads = AccountHeadsRepository::getParentHeads();

        return view('incomeexpense::accounts-sub-head.edit', 
                    compact('accountSubHeads', 'title', 'account_heads')
                );
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateAccountsSubHead($id, AccountSubHeadsRequest $request)
    {
        $accountHead = AccountHeadsRepository::updateSubHead($id, $request);

        if(empty($accountHead)){
            return redirect()->route('income_expense.edit_accounts_sub_head', $id)
                            ->with('error', 'Account sub head not update.'); 
        }
        else {
            return redirect()->route('income_expense.edit_accounts_sub_head', $id)
                            ->with('success', 'Account sub head updated successfully!');            
        }
    }
}
