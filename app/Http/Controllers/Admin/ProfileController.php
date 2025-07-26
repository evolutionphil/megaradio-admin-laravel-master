<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $user = \Auth::user();

        return view('pages.admin.profile.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'string',
            'email' => 'email',
            'current_password' => ['required_with:new_password', 'current_password'],
            'new_password' => ['confirmed'],
        ]);

        \Auth::user()->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->new_password),
        ]);

        return redirect()->back()->with('success', 'Saved.');
    }
}
