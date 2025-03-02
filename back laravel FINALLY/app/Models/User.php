<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'prefix',
        'password',
        'avatar',
        'hours_difference',
        'start_work',
        'end_work',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'start_work' => 'string',
        'end_work' => 'string',
    ];
    public function chatUsers()
    {
        return $this->hasMany(ChatUser::class);
    }

    public function chats()
    {
        return $this->belongsToMany(Chat::class, 'chat_users');
    }

    public function userMessages()
    {
        return $this->hasMany(UserMessage::class);
    }
}
