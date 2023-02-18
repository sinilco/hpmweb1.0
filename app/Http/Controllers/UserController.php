<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Models
use App\Models\User;

class UserController extends Controller
{
    /**
     * dashboard member
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $data['user'] = $user;
        return view('dashboard',$data);
    }

    // list users
    public function index()
    {
        $data['users'] = User::with('roles', 'permissions')->get();
        $data['title'] = 'User List';

        return view('users.index', $data);
    }

    // change status verified/unverified
    public function changeStatus(Request $request)
    {
        // find the user who want to change status
        $user = User::findOrFail($request->userId);

        // change the verified status
        $user->is_verified = $request->status;

        // if user doesnt have role
        // set default to customer
        if(count($user->roles) < 1)
        {
            $user->assignRole(ROLE_CUSTOMER);
        }
        $user->save();

        return redirect()->route('user-list')->withMessage('a');
    }
}
