<?php

namespace App\Http\Controllers\Settings\Security;

use App\Spark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|confirmed|min:'.Spark::minimumPasswordLength(),
        ]);

        if (! Hash::check($request->current_password, $request->user()->password)) {
            return response()->json([
                'errors' => [
                    'current_password' => [__('The given password does not match our records.')]
                ]
            ], 422);
        }

        $request->user()->forceFill([
            'password' => bcrypt($request->password)
        ])->save();
    }
}
