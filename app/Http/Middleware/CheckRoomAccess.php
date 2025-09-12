<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRoomAccess
{
    public function handle(Request $request, Closure $next)
    {
        $room = $request->route('room');

        // Allow room owners and users with session access
        if ($room->user_id === Auth::id() || session('room_access.' . $room->id)) {
            return $next($request);
        }

        return redirect()->route('rooms.join.form')->with('error', 'You need to enter the password to join this room.');
    }
}