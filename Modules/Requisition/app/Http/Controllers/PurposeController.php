<?php

namespace Modules\Requisition\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Requisition\Services\PurposeService;
use Illuminate\Http\Request;

class PurposeController extends Controller
{
    protected $service;

    public function __construct(PurposeService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['title'] = 'Company Information';
        $data['result'] = $this->service->getDataList();
        return view('requisition::purposes.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Purpose Information';
        return view('requisition::purposes.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'purpose_name' => 'required|string|max:255'
        ]);

        $result = $this->service->saveData($validated);
        //dd($result);

        if ($result) {
            return redirect()->route('purpose.index')->with('success', 'Save Successful');
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
        $data['title'] = 'Purpose Information';
        $data['single'] = $this->service->getSingleData($id);
        return view('requisition::purposes.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'purpose_name' => 'required|string|max:255'
        ]);

        $result = $this->service->updateData($validated, $id);
        
        if ($result) {
            return back()->with('success', 'Updated Successfully');
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
            return back()->with('success', 'Deleted Successfully');
        } else {
            return back()->with('error', 'Delete Failed. Please try again.');
        }
    }
}
