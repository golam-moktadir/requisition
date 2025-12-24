<?php

namespace Modules\Requisition\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Modules\Requisition\Models\Cheque;
use Modules\Requisition\Services\BankService;

class BankController extends Controller
{
    protected $service;
    protected $route = 'requisition.bank.';

    public function __construct(BankService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Bank Information';
        $data['route'] = $this->route;
        return view('requisition::banks.index', $data);
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
        $data['title'] = 'Bank Information';
        $data['route'] = $this->route;
        return view('requisition::banks.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name'      => 'required|string|max:255|unique:banks,bank_name'
        ]);

        $result = $this->service->saveData($validated);

        if ($result) {
            return redirect()->route($this->route.'index')->with('success', 'Save Successful');
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
        $data['title']      = 'Bank Information';
        $data['single']     = $this->service->getSingleData($id);
        $data['route']      = $this->route;
        return view('requisition::banks.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'bank_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('banks', 'bank_name')->ignore($id),
            ]
        ]);

        $result = $this->service->updateData($validated, $id);

        if ($result) {
            return redirect()->route($this->route.'index')->with('success', 'Save Successful');
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
            return redirect()->route($this->route.'index')->with('success', 'Delete Successful');
        } else {
            return back()->withInput()->with('error', 'Delete Failed. Please try again.');
        }
    }
}
