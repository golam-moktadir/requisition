<?php

namespace Modules\Requisition\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Requisition\Services\PayeeService;

class PayeeController extends Controller
{
    protected $service;

    public function __construct(PayeeService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Payee Information';
        $data['result'] = $this->service->getDataList();
        return view('requisition::payees.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Payee Information';
        return view('requisition::payees.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payee_name' => 'required|string|max:255',
            'account_holder_name' => 'nullable|string',
            'account_number' => 'nullable|numeric',
            'phone'        => 'required|string|max:11|digits_between:3,11',
            'email'        => 'required|max:255|email|regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/',            
            'address'      => 'required|string|max:255',
        ]);

        $result = $this->service->saveData($validated);
        dd($result);

        if ($result) {
            return redirect()->route('payee.index')->with('success', 'Save Successful');
        } else {
            return back()->withInput()->with('error', 'Save Failed. Please try again.');
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('requisition::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('requisition::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
