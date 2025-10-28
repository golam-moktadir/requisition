<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Employee;
use App\Models\Customer;

class DashboardController extends Controller
{
    public function indexAction()
    {
        return view('admin/dashboard/index', [
            'title'     => 'Dashboard',
            'title_sub' => 'Quotation',
            'users' => User::all()->count(),
            'employees' => 0,
            'customers' => 0, 
            'petty_cash' => 0, 
        ]);
    }
    
    public function update_password_form(Request $request) {
        
        return view('admin/dashboard/update_password', [
            'title'     => 'Update Password',
            'title_sub' => '',
            'user_id'   => Auth::id(),
        ]);        
    }

    public function update_password(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if ($request->get('logged_in_user_id')!=Auth::id()) {
            throw ValidationException::withMessages([
                'current_password' => ['The provided user does not match with logged in user.'],
            ]);
        }
        // dd($request->current_password, Auth::user()->password, Hash::check($request->current_password, Auth::user()->password));
        // Check if the provided current password matches the user's password
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The provided password does not match our records.'],
            ]);
        }

        // Update the user's password
        $user = User::find(Auth::id());
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Redirect with success message
        return redirect()->route('admin.update.password')->with('status', 'Password changed successfully.');
    }    

}
