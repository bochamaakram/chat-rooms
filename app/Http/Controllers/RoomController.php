<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function index()
    {
        // Show rooms where user is a member (creator or joined)
        $rooms = Room::whereHas('members', function ($query) {
            $query->where('user_id', Auth::id());
        })->with('latestMessage')->get();

        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'password' => 'required|string|min:2',
        ]);

        $room = Room::create([
            'name' => $request->name,
            'description' => $request->description,
            'password' => $request->password,
            'user_id' => Auth::id(),
        ]);

        // Automatically add creator as a member
        $room->addMember(Auth::id());

        return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
    }

    public function showJoinForm()
    {
        return view('rooms.join');
    }

    public function join(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'password' => 'required|string|min:2',
        ]);

        $room = Room::findOrFail($request->room_id);

        if (!$room->checkPassword($request->password)) {
            return redirect()->back()->with('error', 'Invalid room ID or password.');
        }

        // Add user to room members
        $room->addMember(Auth::id());

        return redirect()->route('rooms.show', $room);
    }

    public function show(Room $room)
    {
        // Check if user is a member of this room
        if (!$room->hasMember(Auth::id())) {
            return redirect()->route('rooms.join')->with('error', 'You need to join this room first.');
        }

        $messages = $room->messages()->with('user')->latest()->take(50)->get()->reverse();
        return view('rooms.show', compact('room', 'messages'));
    }

    public function destroy(Room $room)
    {
        if ($room->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You can only delete your own rooms.');
        }

        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }
}