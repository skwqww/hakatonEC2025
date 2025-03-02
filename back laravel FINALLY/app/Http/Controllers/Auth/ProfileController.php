<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;


class ProfileController extends Controller
{

    public function login(Request $request) {
        $loginUserData = $request->validate([
            'username' => 'required|string|exists:users,username',
            'name' => 'required|string',
            'password' => 'required|min:8',
            'avatar' => 'nullable',
            'hours' => 'numeric|between:0,23', // Часы, если переданы
        ]);

        $user = User::where('username', $loginUserData['username'])->first();

        if (!$user || !Hash::check($loginUserData['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }

        // Проверяем и обновляем name
        if ($user->name == null) {
            $user->name = $loginUserData['name'];
        }

        // Проверяем и обновляем avatar (если передан в запросе)
        if (isset($loginUserData['avatar']) && $user->avatar !== $loginUserData['avatar']) {
            $user->avatar = $loginUserData['avatar'];
        }
        $serverTime = Carbon::now();
        if (isset($loginUserData['hours'])) {
            $hoursDifference = 1+$serverTime->diffInHours(Carbon::today()->addHours($loginUserData['hours']));
        } else {
            $hoursDifference = 0;
        }


        if (isset($hoursDifference) && $user->hours_difference !== $hoursDifference) {
            $user->hours_difference = $hoursDifference;
        }

        // Сохраняем пользователя в БД
        if ($user->isDirty()) { // Проверяем, изменились ли какие-то поля
            $user->save();
        }

        // Генерируем токен
        $token = $user->createToken($user->username . '-AuthToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user, // Отправляем обновленного пользователя для проверки
            'server_time' => $serverTime->toDateTimeString(),
            'hours_difference' => $hoursDifference, // Разница в часах
        ]);
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
            "message"=>"logged out"
        ]);
    }

    public function getUser()
    {
        $user = auth()->user();

        if ($user->hasRole('admin') || $user->hasRole('owner')) {
            $user->role = 'admin';
        } else if ($user->hasRole('employer')) {
            $user->role = 'employer';
        } else if ($user->hasRole('client')) {
            $user->role = 'client';
        }

        // Проверяем, чтобы значения не были пустыми
        if (!empty($user->start_work) && !empty($user->end_work)) {
            // Определяем правильный формат
            $format = strlen($user->start_work) === 5 ? 'H:i' : 'H:i:s';

            try {
                $startWork = Carbon::createFromFormat($format, $user->start_work);
                $endWork = Carbon::createFromFormat($format, $user->end_work);

                $startWork->addHours($user->hours_difference);
                $endWork->addHours($user->hours_difference);

                // Сохраняем в формате 'H:i'
                $user->start_work = $startWork->format('H:i');
                $user->end_work = $endWork->format('H:i');
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Invalid time format',
                    'details' => $e->getMessage(),
                ], 400);
            }
        } else {
            // Если значения пустые, можно вернуть null или дефолтное время
            $user->start_work = null;
            $user->end_work = null;
        }

        return response()->json([
            'user' => $user,
        ]);
    }

    public function getUserRole(){
        $user = auth()->user();
        if ($user->hasRole('admin') || $user->hasRole('owner')) {
            $role = 'admin';
        } else if ($user->hasRole('employer')) {
            $role = 'employer';
        } else if ($user->hasRole('client')) {
            $role = 'client';
        }

        return response()->json([
            'user' => $role,
        ]);
    }

    public function setWorkTime(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('admin') || $user->hasRole('owner') || $user->hasRole('employer')) {
            // Изменили валидацию на regex для формата времени H:i
            $workTime = $request->validate([
                'start_work' => ['required', 'regex:/^([01]?[0-9]|2[0-3]):([0-5][0-9])$/'],
                'end_work' => ['required', 'regex:/^([01]?[0-9]|2[0-3]):([0-5][0-9])$/'],
            ]);

            // Преобразуем start_work и end_work в объекты Carbon
            $startWork = Carbon::createFromFormat('H:i', $workTime['start_work']);
            $endWork = Carbon::createFromFormat('H:i', $workTime['end_work']);

            // Вычитаем разницу во времени
            $startWork->subHours($user->hours_difference);
            $endWork->subHours($user->hours_difference);

            // Сохраняем откорректированные значения в базе данных
            $user->start_work = $startWork->format('H:i');  // Форматируем в нужный формат
            $user->end_work = $endWork->format('H:i');      // Форматируем в нужный формат
            $user->save();

            // Возвращаем успешный ответ
            return response()->json([
                'message' => 'Рабочее время успешно обновлено',
                'start_work' => $workTime['start_work'],
                'end_work' => $workTime['end_work']
            ], 200);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
