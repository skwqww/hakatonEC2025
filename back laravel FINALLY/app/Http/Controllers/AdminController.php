<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Chat;
use App\Models\ChatUser;


class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        // Проверяем, что пользователь - админ или владелец
        if (!$user->hasRole('admin') && !$user->hasRole('owner')) {
            return response()->json([
                'message' => 'У вас нет прав для выполнения этой операции'
            ], 403);
        }

        // Получаем всех пользователей, кроме тех, у кого роль admin или owner
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['owner']);
        })
            ->orderBy('username', 'asc')
            ->get()
            ->map(function ($user) {
                if ($user->hasRole('employer')) {
                    $user->role = 'employer';
                } else if ($user->hasRole('client')) {
                    $user->role = 'client';
                }
                if ($user->start_work && $user->end_work) {
                    $user->start_work = Carbon::createFromFormat('H:i:s', $user->start_work)
                        ->addHours($user->hours_difference)
                        ->format('H:i');

                    $user->end_work = Carbon::createFromFormat('H:i:s', $user->end_work)
                        ->addHours($user->hours_difference)
                        ->format('H:i');
                }
                return $user;
            });

        return response()->json([
            'users' => $users,
        ]);
    }

    public function getRoles()
    {
        $user = auth()->user();

        if ($user->hasRole('admin') || $user->hasRole('owner')) {
            return response()->json([
                'employer' => 'Сотрудник',
                'client' => 'Заказчик'
            ]);
        }
        return response()->json([
            'message' => 'У вас нет прав для выполнения этой операции'
        ], 403);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->hasRole('admin') && !$user->hasRole('owner')) {
            return response()->json([
                'message' => 'У вас нет прав для выполнения этой операции'
            ], 403);
        }

        $request->validate([
            'username' => 'required|string|unique:users,username',
            'name' => 'nullable|string',
            'prefix' => 'required|string',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:employer,client'
        ]);

        $newUser = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'prefix' => $request->prefix,
            'password' => Hash::make($request->password),
            'hours_difference' => $request->hours_difference,
        ]);

        $newUser->assignRole($request->role);

        return response()->json(['message' => 'User created successfully', 'user' => $newUser], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = auth()->user();

        if (!$user->hasRole('admin') && !$user->hasRole('owner')) {
            return response()->json([
                'message' => 'У вас нет прав для выполнения этой операции'
            ], 403);
        }

        $getUser = User::find($id);

        if (!$getUser) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        if ($getUser->hasRole('admin') || $getUser->hasRole('owner')) {
            $getUser->role = 'admin';
        } else if ($getUser->hasRole('employer')) {
            $getUser->role = 'employer';
        } else if ($getUser->hasRole('client')) {
            $getUser->role = 'client';
        }

        return response()->json([
            'user' => $getUser,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = auth()->user();

        if (!$user->hasRole('admin') && !$user->hasRole('owner')) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $userToUpdate = User::find($id);

        if (!$userToUpdate) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $request->validate([
            'username' => 'sometimes|string|unique:users,username,' . $id,
            'name' => 'sometimes|string',
            'prefix' => 'sometimes|string',
            'password' => 'sometimes|string|min:6',
            'hours_difference' => 'sometimes|integer',
            'role' => 'sometimes|string|in:employer,client',
        ]);

        if ($request->has('password')) {
            $request->merge(['password' => Hash::make($request->password)]);
        }

        $userToUpdate->update($request->only(['username', 'name', 'prefix', 'password', 'hours_difference']));

        $userToUpdate->syncRoles($request->role);

        return response()->json(['message' => 'User updated successfully', 'user' => $userToUpdate], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = auth()->user();

        if (!$user->hasRole('admin') && !$user->hasRole('owner')) {
            return response()->json(['error' => 'Access denied'], 403);
        }
        // Find the user by ID
        $userToDelete = User::find($id);

        // If user doesn't exist, return a 404 response
        if (!$userToDelete) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Delete the user
        $userToDelete->delete();

        // Return a success response
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function storeChat(Request $request) {
        $user = auth()->user();

        if (!$user->hasRole('admin') && !$user->hasRole('owner')) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $request->validate([
            'name' => 'required|string',
        ]);

        $chat = Chat::create([
            'name' => $request->name,
            'created_by' => $user->id,
        ]);

        return response()->json([
            'message' => 'Chat created successfully',
            'chat' => $chat
        ], 201);
    }

    public function addUserChat(Request $request) {
        $user = auth()->user();

        if (!$user->hasRole('admin') && !$user->hasRole('owner')) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $request->validate([
            'chat_id' => 'required|integer|exists:chats,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        $chat = Chat::findOrFail($request->chat_id);

        // Проверяем, есть ли уже пользователь в чате
        if ($chat->users()->where('user_id', $request->user_id)->exists()) {
            return response()->json([
                'error' => 'User is already in the chat'
            ], 400);
        }

        // Добавляем пользователя в чат
        $chat->users()->attach($request->user_id);

        return response()->json([
            'message' => 'User added to chat successfully',
            'chat_id' => $chat->id,
            'user_id' => $request->user_id
        ], 200);
    }

    public function getChats() {
        $user = auth()->user();

        // Проверка прав пользователя
        if (!$user->hasRole('admin') && !$user->hasRole('owner')) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        // Получаем все чаты с подгрузкой пользователей и сообщений
        $chats = Chat::with(['users'])->get();

        // Форматируем данные, если нужно (например, убираем внутренности отношений)
        $chats = $chats->map(function($chat) {
            return [
                'chat_id' => $chat->id,
                'name' => $chat->name,
                'users' => $chat->users->map(function($user) {
                    return [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                    ];
                }),
            ];
        });

        return response()->json($chats);
    }
}
