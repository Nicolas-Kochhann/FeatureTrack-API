<?php

namespace App\Http\Controllers;

use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function listReceivedInvites()
    {
        $invites = Auth::user()->receivedInvites();
        return response()->json($invites, 200)->header('Content-Type', 'application/json');
    }

    /**
     * Display a listing of the resource.
     */
    public function listSentInvites()
    {
        $invites = Auth::user()->sentInvites();
        return response()->json($invites, 200)->header('Content-Type', 'application/json');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invite = Invite::findOrFail($id);
        return response()->json($invite, 200)->header('Content-Type', 'application/json');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invite $invite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $invite = Invite::findOrFail($id);
        $invite->delete();
        return response()->json([], 204)->header('Content-Type', 'application/json');
    }
}
