<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Telegram\Bot\Laravel\Facades\Telegram;

class WebhookController extends Controller
{
    public function telegram() {
        $update = Telegram::commandsHandler(true);
        $chat_id = $update->getMessage()->getChat()->getId();

        if (!User::query()->where('telegram_chat_id', $chat_id)->exists()) {
            $password = Str::random(8);
            $name = $update->getMessage()->getFrom()->getFirstName();
            $user = User::create([
                'name' => $name,
                'password' => Hash::make($password),
            ]);
            Telegram::sendMessage([
                'chat_id' => $chat_id,
                'text' => 'КВА ' . $name . ' твой пароль ' . $password
            ]);

        }

        Telegram::sendMessage([
            'chat_id' => $chat_id,
            'text' => 'KVA'
        ]);
    }
}
