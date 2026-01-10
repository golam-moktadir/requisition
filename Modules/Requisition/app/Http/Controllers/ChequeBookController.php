<?php

namespace Modules\Requisition\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Requisition\Services\ChequeBookService;

class ChequeBookController extends Controller
{
    protected $service;
    protected $route = 'requisition.cheque.';

    public function __construct(ChequeBookService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Cheque Information';
        $data['route'] = $this->route;
        $data['accounts']  = $this->service->getBankAccountList();
        return view('requisition::cheques.index', $data);
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
        $data['title'] = 'Cheque Information';
        $data['route'] = $this->route;
        $data['accounts']  = $this->service->getBankAccountList();
        return view('requisition::cheques.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id'  => ['required', 'integer', 'exists:bank_accounts,id'],
            'book_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('cheque_books')
                    ->where(fn($q) => $q->where('account_id', $request->input('account_id'))),
            ],
            'start_cheque_no' => ['required', 'numeric'],
            'end_cheque_no'   => ['required', 'numeric', 'gt:start_cheque_no']
        ]);
        $result = $this->service->saveData($validated);
        if ($result) {
            return redirect()->route($this->route . 'index')->with('success', 'Save Successful');
        } else {
            return back()->withInput()->with('error', 'Save Failed. Please try again.');
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $data['title'] = 'Cheque Information';
        $data['route'] = $this->route;
        $data['single']   = $this->service->getSingleData($id);
        return view('requisition::cheques.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $data['title'] = 'Cheque Information';
        $data['route'] = $this->route;
        $data['accounts'] = $this->service->getBankAccountList();
        $data['single']   = $this->service->getSingleData($id);
        return view('requisition::cheques.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'account_id'  => ['required', 'integer', 'exists:bank_accounts,id'],
            'book_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('cheque_books')
                    ->where(fn($q) => $q->where('account_id', $request->input('account_id')))->ignore($id),
            ],
            'start_cheque_no' => ['required', 'numeric'],
            'end_cheque_no'   => ['required', 'numeric', 'gt:start_cheque_no']
        ]);

        $result = $this->service->updateData($validated, $id);

        if ($result) {
            return redirect()->route($this->route . 'index')->with('success', 'Save Successful');
        } else {
            return back()->withInput()->with('error', 'Save Failed. Please try again.');
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $result = $this->service->deleteData($id);

        if ($result) {
            return redirect()->route($this->route . 'index')->with('success', 'Delete Successful');
        } else {
            return back()->withInput()->with('error', 'Delete Failed. Please try again.');
        }
    }
}
