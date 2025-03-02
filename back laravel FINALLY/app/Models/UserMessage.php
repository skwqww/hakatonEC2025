<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMessage extends Model
{
    use HasFactory;

    protected $table = 'user_messages';

    protected $fillable = [
        'user_id',
        'chat_id',
        'message',
        'isDeleted',
        'isUpdated'
    ];

    public function chatUsers()
    {
        return $this->hasMany(ChatUser::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'chat_user');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(UserMessage::class);
    }
}
