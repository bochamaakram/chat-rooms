<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function store(Request $request, Room $room)
    {
        // Check if user is a member of this room
        if (!$room->hasMember(Auth::id())) {
            return response()->json(['error' => 'You are not a member of this room'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        try {
            $message = Message::create([
                'content' => $request->content,
                'user_id' => Auth::id(),
                'room_id' => $room->id,
            ]);

            // Load user relationship for the response
            $message->load('user');

            return response()->json([
                'success' => true,
                'message' => $message,
                'user_name' => $message->user->name,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to send message: ' . $e->getMessage()
            ], 500);
        }
    }

    public function index(Room $room)
    {
        if (!$room->hasMember(Auth::id())) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $messages = $room->messages()
            ->with('user')
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        return response()->json($messages);
    }
}