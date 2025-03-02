<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $id;
    public $user_id;
    public $chat_id;
    public $message;
    public $userIds;
    public $isUpdated;
    public $isDeleted;
    public $user;
    public $now;

    public function __construct($id ,$user_id, $chat_id, $message, $userIds, $isUpdated, $isDeleted, $user, $now)
    {
        $this->id = $id;
        $this->message = $message;
        $this->chat_id = $chat_id;
        $this->user_id = $user_id;
        $this->userIds = $userIds;
        $this->isUpdated = $isUpdated;
        $this->isDeleted = $isDeleted;
        $this->user = $user;
        $this->now = $now;
    }

    public function broadcastOn()
    {
        return ['my-channel'];
    }

    public function broadcastAs()
    {
        return 'my-event';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user->id,
            'chat_id' => $this->chat_id,
            'message' => $this->message,
            'userIds' => $this->userIds,
            'isUpdated' => $this->isUpdated,
            'isDeleted' => $this->isDeleted,
            'user' => $this->user,
            'now' => $this->now,
        ];
    }
}
