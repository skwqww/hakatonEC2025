<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageEvent;
use Illuminate\Support\Facades\Auth;
use App\Models\UserMessage;
use Carbon\Carbon;

class MessageController extends Controller
{

    public function getChats()
    {
        $user = auth()->user();

        // Получаем чаты и загружаем их пользователей
        $chats = $user->chats()->with('users')->get();

        // Обрабатываем чаты
        $chats->each(function ($chat) use ($user) {
            // Получаем последнее сообщение для чата
            $lastMessage = $chat->messages()->with('user')->latest()->first();


            if ($lastMessage) {
                // Форматируем время последнего сообщения
                $lastMessage->created = \Carbon\Carbon::parse($lastMessage->created_at)
                    ->addHours($user->hours_difference)
                    ->format('H:i');

                // Добавляем последнее сообщение к чату
                $chat->messages = [$lastMessage];
            } else {
                // Если сообщений нет, добавляем пустое сообщение
                $chat->messages = null;
            }

            // Форматируем пользователей чата (по желанию)
            $chat->users->each(function ($user) {
                // Можно добавить дополнительную информацию о пользователях
                // Например, их имя, роль или другие данные
            });
        });

        return response()->json([
            'chats' => $chats,
        ]);
    }

    public function getMessages(Request $request)
    {
        $user = auth()->user();

        // Валидация входных данных
        $validated = $request->validate([
            'chat_id' => 'required|exists:chats,id', // Проверка, что chat_id существует в таблице чатов
        ]);

        $chatId = $validated['chat_id'];

        // Проверка, что пользователь состоит в этом чате
        $isUserInChat = \DB::table('chat_users')
            ->where('chat_id', $chatId)
            ->where('user_id', $user->id)
            ->exists();

        if (!$isUserInChat) {
            return response()->json(['error' => 'You are not a member of this chat.'], 403);
        }

        // Получаем все сообщения из чата, включая информацию о том, обновлено ли сообщение и удалено ли оно
        $messages = UserMessage::where('chat_id', $chatId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        $messages->each(function ($message) use ($user) {
            $message->created = \Carbon\Carbon::parse($message->created_at)
                ->addHours($user->hours_difference)
                ->format('H:i');
        });

        // Отправка сообщений пользователю
        return response()->json([
            'messages' => $messages,
        ]);
    }

    public function sendMessage(Request $request)
    {
        $user = auth()->user();

        // Валидация входных данных
        $validated = $request->validate([
            'message' => 'required|string|max:500', // Сообщение должно быть строкой и не превышать 500 символов
            'chat_id' => 'required|exists:chats,id', // Проверка, что chat_id существует в таблице chats
        ]);

        $message = $validated['message'];
        $chatId = $validated['chat_id'];

        // Проверка, что пользователь состоит в этом чате
        $isUserInChat = \DB::table('chat_users')
            ->where('chat_id', $chatId)
            ->where('user_id', $user->id)
            ->exists();

        if (!$isUserInChat) {
            return response()->json(['error' => 'You are not a member of this chat.'], 403);
        }

        $userIds = \DB::table('chat_users')
            ->where('chat_id', $chatId)
            ->pluck('user_id')
            ->toArray();

        $messageCreate = UserMessage::create([
            'user_id' => $user->id,
            'chat_id' => $chatId,
            'message' => $message,
        ]);

        // Отправляем событие с сообщением
        $now = Carbon::now()->format('H:i');
        event(new MessageEvent($messageCreate->id ,$user->id, $chatId, $message, $userIds, false, false, $user, $now));

        return response()->json([
            'id' => $messageCreate->id,
            'user' => $user->id,
            'chat_id' => $chatId,
            'message' => $message,
            'access_user_ids' => $userIds,
        ]);
    }

    public function updateMessage(Request $request)
    {
        $user = auth()->user();

        // Валидация входных данных
        $validated = $request->validate([
            'message_id' => 'required|exists:user_messages,id', // Проверка, что message_id существует в таблице сообщений
            'message' => 'required|string|max:500', // Сообщение должно быть строкой и не превышать 500 символов
        ]);

        $messageId = $validated['message_id'];
        $newMessage = $validated['message'];

        // Найдем сообщение по ID
        $message = UserMessage::find($messageId);

        // Проверка, что сообщение принадлежит текущему пользователю
        if ($message->user_id !== $user->id) {
            return response()->json(['error' => 'You are not authorized to update this message.'], 403);
        }

        // Обновление сообщения
        $message->update([
            'message' => $newMessage,
            'isUpdated' => 1, // Устанавливаем флаг isUpdated в true
        ]);

        // Получаем список всех пользователей чата
        $userIds = \DB::table('chat_users')->where('chat_id', $message->chat_id)->pluck('user_id')->toArray();
        $now = Carbon::now()->format('H:i');
        // Отправка обновленного сообщения через событие с флагом onUpdated
        event(new MessageEvent($messageId ,$user->id, $message->chat_id, $newMessage, $userIds, true, false, $user, $now));

        return response()->json([
            'message' => $message,
        ]);
    }

    public function deleteMessage(Request $request)
    {
        $user = auth()->user();

        // Валидация входных данных
        $validated = $request->validate([
            'message_id' => 'required|exists:user_messages,id', // Проверка, что message_id существует в таблице сообщений
        ]);

        $messageId = $validated['message_id'];

        // Найдем сообщение по ID
        $message = UserMessage::find($messageId);

        // Проверка, что сообщение принадлежит текущему пользователю
        if ($message->user_id !== $user->id) {
            return response()->json(['error' => 'You are not authorized to delete this message.'], 403);
        }

        // Устанавливаем флаг isDeleted в true, не удаляем запись
        $message->update([
            'isUpdated' => false, // Устанавливаем флаг isUpdated в true
            'isDeleted' => true
        ]);

        // Получаем список всех пользователей чата
        $userIds = \DB::table('chat_users')->where('chat_id', $message->chat_id)->pluck('user_id')->toArray();
        $now = Carbon::now()->format('H:i');
        // Отправка уведомления о удалении через событие
        event(new MessageEvent($messageId ,$user->id, $message->chat_id, 'Сообщение было удалено.', $userIds, false, true, $user, $now));

        return response()->json(['success' => 'Message marked as deleted successfully.']);
    }
}
