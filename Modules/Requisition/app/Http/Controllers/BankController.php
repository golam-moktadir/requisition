<?php

namespace Modules\Requisition\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Requisition\Models\Requisition;
use Modules\Requisition\Models\Approval;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //dd();
        return view('requisition::banks.create', [
            'title' => 'Create Bank Information',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (strtolower($request->get('transaction_mode')) == 'bank') {
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'amount' => 'required',
                'requested_to' => 'required',
                'transaction_mode' => 'required',
                'bank_check_info' => 'required',
            ]);
        } else {
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'amount' => 'required',
                'requested_to' => 'required',
                'transaction_mode' => 'required',
            ]);
        }

        $requisition = new Requisition([
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'requested_to' => $request->requested_to,
            'transaction_mode' => $request->transaction_mode,
            'bank_check_info' => ($request->bank_check_info ?? ''),
            'created_by' => auth()->id(),
        ]);

        $requisition->save();

        return back()->with('success', 'Requisition created succefully!');
    }

    /**
     * Show the specified resource.
     */
    public function show(int $requisition_id)
    {
        return view('requisition::show', [
            'title' => 'Show Requisition',
            'requisition' => Requisition::findOrFail($requisition_id),
            'approvals' => Approval::where('requisition_id', $requisition_id)->with('user')->get(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('requisition::edit', [
            'title' => 'Edit requisition',
            'requisition' => Requisition::whereIn('status', ['pending', 'rejected'])->findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $requisition = Requisition::whereIn('status', ['pending', 'rejected'])
            ->where('id', $id)
            ->firstOrFail();

        if (strtolower($request->get('transaction_mode')) == 'bank') {
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'amount' => 'required',
                'requested_to' => 'required',
                'transaction_mode' => 'required',
                'bank_check_info' => 'required',
            ]);
        } else {
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'amount' => 'required',
                'requested_to' => 'required',
                'transaction_mode' => 'required',
            ]);
        }

        $requisition->title = $request->title;
        $requisition->description = $request->description;
        $requisition->amount = $request->amount;
        $requisition->requested_to = $request->requested_to;
        $requisition->transaction_mode = $request->transaction_mode;
        $requisition->bank_check_info = ($request->bank_check_info ?? '');

        $requisition->save();

        return back()->with('success', 'Requisition update succefully?');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function approval(int $requisition_id)
    {
        // if( ! in_array(Auth::user()->id, [1])){
        //     abort(404);
        // }

        return view('requisition::approval', [
            'title' => 'Approve Requisition',
            'requisition' => Requisition::whereIn('status', ['pending', 'rejected'])->findOrFail($requisition_id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function storeAapproval(int $requisition_id, Request $request)
    {
        // if( ! in_array(Auth::user()->id, [1])) {
        //     abort(404);
        // }

        $requisition = Requisition::whereIn('status', ['pending', 'rejected'])->findOrFail($requisition_id);

        if ($request->has('status') && in_array($request->status, ['rejected', 'approved'])) {

            DB::beginTransaction();

            try {
                $approval = new Approval();
                $approval->requisition_id = $requisition_id;
                $approval->status = $request->status;
                $approval->remarks = $request->remarks;
                $approval->user_id = Auth::id();
                $approval->save();

                if ($approval->id > 0) {
                    $requisition->status = $approval->status;
                    $requisition->save();
                } else {
                    throw new Exception("Approval update failed.", 500);
                }

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();
                about(500);
            }

            return redirect()->route('requisition.show', $requisition_id)
                ->with('Requisition update successfully!');
        } else {
            abort(404);
        }
    }
}
