<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CompanyMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('is_delete', 0)->get();
        $companies = CompanyMaster::where('is_delete', 0)->get();
        return view('admin.users', compact('users', 'companies'));
    }

    public function store(Request $request)
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required|in:1,2',
        ];
        if ($request->role_id == 2) {
            $rules['company_id'] = 'required|exists:company_master,id';
        }
        $validated = $request->validate($rules);

        // Generate random 8-character password
        $plainPassword = Str::random(8);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'full_name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'company_id' => $request->role_id == 2 ? $request->company_id : 0,
            'password' => Hash::make($plainPassword),
            'is_active' => 1,
            'is_delete' => 0,
        ]);

        // Send email to user with credentials
        Mail::send('emails.user-credentials', [
            'email' => $user->email,
            'password' => $plainPassword,
            'user' => $user
        ], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Zoom Insurance Brokers Account Credentials');
        });

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'role_id' => 'required|in:admin,user',
        ]);
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'full_name' => $request->first_name . ' ' . $request->last_name,
            'role_id' => $request->role_id,
        ]);
        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->is_delete = 1;
        $user->save();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }
}
