<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'password', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'room_user')->withTimestamps();
    }

    // Set password with hashing
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    // Check if password matches
    public function checkPassword($password)
    {
        return Hash::check($password, $this->password);
    }

    // Check if user is a member of this room
    public function hasMember($userId)
    {
        return $this->members()->where('user_id', $userId)->exists();
    }

    // Add user to room members
    public function addMember($userId)
    {
        return $this->members()->syncWithoutDetaching([$userId]);
    }
}