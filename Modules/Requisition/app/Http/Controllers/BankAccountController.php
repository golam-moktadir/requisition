<?php

namespace Modules\Requisition\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Modules\Requisition\Models\Cheque;
use Modules\Requisition\Services\BankAccountService;

class BankAccountController extends Controller
{
   protected $service;

   public function __construct(BankAccountService $service)
   {
      $this->service = $service;
   }

   /**
    * Display a listing of the resource.
    */
   public function index()
   {
      $data['title'] = 'Bank Account Information';
      return view('requisition::accounts.index', $data);
   }

   public function getDataList(Request $request)
   {
      $result = $this->service->getDataList($request);
      return response()->json($result);
   }
   /**
    * Show the form for creating a new resource.
    */
   public function create()
   {
      $data['title'] = 'Bank Account Information';
      $data['banks']  = $this->service->getBankList();
      return view('requisition::accounts.create', $data);
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request)
   {
      $validated = $request->validate([
         'bank_id'             => 'required|integer|exists:banks,id',
         'account_holder_name' => 'required|string|max:255',
         'account_number'      => [
                                 'required',
                                 'string',
                                 Rule::unique('bank_accounts')->where(fn($query) => $query->where('bank_id', $request->input('bank_id'))),
                              ],
         'branch_name'         => 'required|string|max:255',
      ]);

      $result = $this->service->saveData($validated);

      if ($result) {
         return redirect()->route('bank.index')->with('success', 'Save Successful');
      } else {
         return back()->withInput()->with('error', 'Save Failed. Please try again.');
      }
   }

   /**
    * Show the specified resource.
    */
   public function show(int $requisition_id) {}

   /**
    * Show the form for editing the specified resource.
    */
   public function edit($id)
   {
      $data['title'] = 'Bank Account Information';
      $data['banks']  = $this->service->getBankList();
      $data['single']     = $this->service->getSingleData($id);
      return view('requisition::accounts.edit', $data);
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, $id)
   {
      $validated = $request->validate([
         'bank_id'             => 'required|integer|exists:banks,id',
         'account_holder_name' => 'required|string|max:255',
         'account_number'      => [
                                 'required',
                                 'string',
                                 Rule::unique('bank_accounts')
                                 ->where(fn($query) => $query->where('bank_id', $request->input('bank_id')))->ignore($id),
                              ],
         'branch_name'         => 'required|string|max:255',
      ]);

      $result = $this->service->updateData($validated, $id);

      if ($result) {
         return redirect()->route('bank.account.index')->with('success', 'Save Successful');
      } else {
         return back()->withInput()->with('error', 'Save Failed. Please try again.');
      }
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy($id)
   {
      $result = $this->service->deleteData($id);

      if ($result) {
         return redirect()->route('bank.index')->with('success', 'Delete Successful');
      } else {
         return back()->withInput()->with('error', 'Delete Failed. Please try again.');
      }
   }

   /**
    * Show the form for editing the specified resource.
    */
   public function chequeList(int $id)
   {
      $data['title']  = 'Cheque Information';
      $data['single'] = $this->service->getSingleData($id);
      $data['result'] = $this->service->getChequeList($id);
      return view('requisition::banks.cheque-list', $data);
   }

   public function createCheque(int $id)
   {
      $data['title']  = 'Cheque Information';
      $data['single'] = $this->service->getSingleData($id);
      return view('requisition::banks.create-cheque', $data);
   }
   /**
    * Show the form for editing the specified resource.
    */
   public function saveCheques(Request $request, $id)
   {
      $validated = $request->validate([
         'start_no' => 'required|numeric',
         'end_no'   => 'required|numeric|gte:start_no'
      ]);

      $result = $this->service->saveCheques($validated, $id);

      if ($result) {
         return back()->with('success', 'Save Successful');
      } else {
         return back()->withInput()->with('error', 'Save Failed. Please try again.');
      }
   }

   public function editCheque($id)
   {
      $data['title']  = 'Cheque Information';
      $data['single'] = Cheque::with('bank.company')->findOrFail($id);
      return view('requisition::banks.edit-cheque', $data);
   }

   public function activityChequeStatus(Request $request, $id)
   {
      //dd($request->all());
      $model = Cheque::find($id);

      $model->status  = $request->input('status');
      $model->remarks = $request->input('remarks');
      $model->save();

      if ($request->hasFile('files')) {
         $data = [];
         foreach ($request->file('files') as $key => $file) {
            $fileName = date('YmdHis') . rand() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/cheques', $fileName);

            $data[$key] = [
               'cheque_id'  => $model->id,
               'file_name'  => $fileName,
               'created_at' => now(),
               'updated_at' => now()
            ];
         }
         DB::table('cheque_files')->insert($data);
      }
      return back()->with('success', 'Save Successful');
   }

   public function activeToggle($id)
   {
      $model = Cheque::findOrFail($id);
      $model->status = $model->status == 1 ? 2 : 1; // toggle between 1=Active, 2=Inactive
      $model->save();
      return back()->with('success', 'status updated');
   }

   public function usedToggle($id)
   {
      $model = Cheque::findOrFail($id);
      $model->status = $model->status == 1 ? 3 : 1; // toggle between 1=Active, 3=Used
      $model->save();
      return back()->with('success', 'status updated');
   }
}
