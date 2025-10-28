<?php
namespace Modules\Requisition\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Requisition\Services\CompanyService;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    protected $service;

    public function __construct(CompanyService $service)
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
        return view('requisition::companies.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['title'] = 'Company Information';
        return view('requisition::companies.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'phone'        => 'required|string|max:11|digits_between:3,11',
            'email'        => 'required|max:255|email|regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/',            
            'website'      => 'nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/|max:255',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
            'address'      => 'required|string|max:255',
            'status'       => 'required|in:1,2',
        ]);

        $result = $this->service->saveData($validated, $request);
        //dd($result);

        if ($result) {
            return redirect()->route('company.index')->with('success', 'Created Successfully');
        } else {
            return back()->withInput()->with('error', 'Failed to create City. Please try again.');
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $data['title'] = 'Company Information';
        $data['single'] = $this->service->getSingleData($id);
        return view('requisition::companies.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $data['title'] = 'Company Information';
        $data['single'] = $this->service->getSingleData($id);
        return view('requisition::companies.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'phone'        => 'required|string|max:11|digits_between:3,11',
            'email'        => 'required|max:255|email|regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/',            
            'website'      => 'nullable|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/|max:255',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
            'address'      => 'required|string|max:255',
            'status'       => 'required|in:1,2',
        ]);

        $result = $this->service->updateData($validated, $request, $id);
        
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
