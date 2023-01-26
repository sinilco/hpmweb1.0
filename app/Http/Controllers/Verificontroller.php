<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class VerificationController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('register')
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->verification_status = "unverified";
        $user->save();
        
        // Send a verification email to the user
        $this->sendVerificationEmail($user);
        
        return redirect()->route('verification.pending');
    }
    
    public function sendVerificationEmail($user)
    {
        // Implementation for sending a verification email to the user
    }
        public function verify($id)
    {
        $user = User::find($id);
        $user->verification_status = "verified";
        $user->save();
    }
    
    public function checkVerification()
    {
        $user = Auth::user();
        if ($user->verification_status == "unverified") {
            return redirect()->route('verification.pending');
        }
        return view('home');
    }
}