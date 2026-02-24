<?php

namespace App\Http\Controllers;

use App\Http\Requests\Invite\StoreInviteRequest;
use App\Http\Requests\Invite\UpdateInviteRequest;
use App\Models\Invite;
use Illuminate\Support\Facades\Auth;

class InviteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function listReceivedInvites()
    {
        $invites = Auth::user()->receivedInvites()->get();
        return response()->json($invites, 200)->header('Content-Type', 'application/json');
    }

    /**
     * Display a listing of the resource.
     */
    public function listSentInvites()
    {
        $invites = Auth::user()->sentInvites()->get();
        return response()->json($invites, 200)->header('Content-Type', 'application/json');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInviteRequest $request)
    {
        $invite = Invite::create([
            'sender_id' => Auth::user()->id,
            'receiver_id' => $request->receiver_id,
            'project_id' => $request->project_id,
            'role' => $request->role
        ]);

        return response()->json($invite, 201)->header('Content-Type', 'application/json');
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
    public function update(UpdateInviteRequest $request, $id)
    {
        $invite = Invite::findOrFail($id);
        $invite->update($request->only(['receiver_id', 'role', 'status']));
        return response()->json($invite, 200)->header('Content-Type', 'application/json');
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
