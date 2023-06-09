<?php

namespace App\Http\Controllers\Settings\Teams;

use App\Spark;
use Illuminate\Http\Request;
use App\Invitation;
use App\Http\Controllers\Controller;
use App\Contracts\Interactions\Settings\Teams\AddTeamMember;

class PendingInvitationController extends Controller
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
     * Get all of the pending invitations for the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function all(Request $request)
    {
        return $request->user()->invitations()->with('team')->get();
    }

    /**
     * Accept the given invitations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Spark\Invitation  $invitation
     * @return \Illuminate\Http\Response
     */
    public function accept(Request $request, Invitation $invitation)
    {
        abort_unless($request->user()->id == $invitation->user_id, 404);

        Spark::interact(AddTeamMember::class, [
            $invitation->team, $request->user(), $invitation->role
        ]);

        $invitation->delete();
    }

    /**
     * Reject the given invitations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Spark\Invitation  $invitation
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request, Invitation $invitation)
    {
        abort_unless($request->user()->id == $invitation->user_id, 404);

        $invitation->delete();
    }
}
