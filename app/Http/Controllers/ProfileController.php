<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit(User $user)
    {
        return view('profiles.edit',compact('user'));
    }

    public function update(request $request,User $user)
    {
        $user->update(request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)],
        ]));

        return redirect()->back()->with('success','Informations changed successfully !');
    }

    public function updatePassword(request $request,User $user)
    {

        if (!(Hash::check(request('current-password'), $user->password)))
        {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }

        if(strcmp(request('current-password'), request('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }

        request()->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:8|confirmed',
        ]);


        $user->update([
            'password' => Hash::make(request('new-password'))
        ]);

        return redirect()->back()->with("success","Password changed successfully !");

    }

}
