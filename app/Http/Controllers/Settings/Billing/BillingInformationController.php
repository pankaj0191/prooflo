<?php

namespace App\Http\Controllers\Settings\Billing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BillingInformationController extends Controller
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
     * Update the user's extra billing information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'information' => 'max:2048',
        ]);

        $request->user()->forceFill([
            'extra_billing_information' => $request->information,
        ])->save();
    }
}
