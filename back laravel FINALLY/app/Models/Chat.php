<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';

    protected $fillable = [
        'name',
    ];

    public function chatUsers()
    {
        return $this->hasMany(ChatUser::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_users', 'chat_id', 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(UserMessage::class);
    }
}
