<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\Models\Chat;
use App\Models\ChatUser;
use App\Models\UserMessage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         $user1 = \App\Models\User::create([
             'username' => '@nikita45738',
             'name' => 'Никита Пузиков',
             'prefix' => 'Админ',
             'password' => Hash::make('adminpassword'),
             'hours_difference' => 7,
         ]);
        $user2 = \App\Models\User::create([
            'username' => '@skwqww',
            'name' => 'Евгений Андриянов',
            'prefix' => 'Админ',
            'password' => Hash::make('adminpassword'),
            'hours_difference' => 7,
        ]);
        $role = Role::create(['name' => 'owner']);
        $user1->assignRole($role);
        $user2->assignRole($role);

        $role = Role::create(['name' => 'admin']);
        $role = Role::create(['name' => 'employer']);
        $role = Role::create(['name' => 'client']);

        $chat = Chat::create(['name' => 'Студия красоты "Твой образ"']);

        $chatUsers1 = ChatUser::create(['chat_id' => $chat->id, 'user_id' => $user1->id]);
        $chatUsers2 = ChatUser::create(['chat_id' => $chat->id, 'user_id' => $user2->id]);

        UserMessage::create(['chat_id' => $chat->id, 'user_id' => $user1->id, 'message' => 'Добрый вечер!']);
        UserMessage::create(['chat_id' => $chat->id, 'user_id' => $user2->id, 'message' => 'Здравствуйте.']);
        UserMessage::create(['chat_id' => $chat->id, 'user_id' => $user1->id, 'message' => 'Жду ТЗ']);
        UserMessage::create(['chat_id' => $chat->id, 'user_id' => $user2->id, 'message' => 'С утра скину, ждите']);

    }
}
