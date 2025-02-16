<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\CoinTransaction as CoinTransactionEloquent;
use App\Models\User as UserEloquent;
use App\Services\JWTService;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function getUserIdByUsername(string $username): ?int
    {
        return UserEloquent::query()->where('name', $username)->first()?->id;
    }

    public function authenticateUserByParams(string $username, string $password): ?string
    {
        $user = UserEloquent::query()->where('name', $username)->first();
        if ($user && Hash::check($password, $user->password)) {
            return JWTService::getAccessToken($user->id, $user->getJWTSecret());
        }
        return null;
    }
    public function registerUserByParams(string $username, string $password): string
    {
        $user = UserEloquent::query()->create([
            'name' => $username,
            'password' => Hash::make($password),
            'jwt_secret' => uuid_create(),
        ]);
        return JWTService::getAccessToken($user->id, $user->getJWTSecret());
    }

    public function getUserInfoById(int $id): ?array
    {
        return
            UserEloquent::query()
            ->whereKey($id)
            ->with([
                'sentCoinTransactions:id,sender_id,receiver_id,amount',
                'sentCoinTransactions.receiver:id,name',
            ])
            ->with([
                'receivedCoinTransactions:id,receiver_id,sender_id,amount',
                'receivedCoinTransactions.receiver:id,name'
            ])
            ->with([
                'merchTransactions:id,merch_id,user_id',
                'merchTransactions.merch:id,name'
            ])
            ->get(['id', 'coins'])
            ->map(function (UserEloquent $user): array {
                return [
                    'coins' => $user->getAttribute('coins'),
                    'inventory' => $user->merchTransactions->groupBy('merch.name')
                        ->map(fn($item, $key) => ['type' => $key, 'quantity' => $item->count()])
                        ->values()->toArray(),
                    'coinHistory' => [
                        'received' => $user->receivedCoinTransactions->groupBy('sender.name')
                            ->map(fn($item, $key) => ['fromUser' => $key, 'amount' => $item->sum('amount')])
                            ->values()->toArray(),
                        'sent' => $user->sentCoinTransactions->groupBy('receiver.name')
                            ->map(fn($item, $key) => ['toUser' => $key, 'amount' => $item->sum('amount')])
                            ->values()->toArray(),
                    ]
                ];
            })
            ->first();
    }

    public function sendCoinsBetweenUsers(int $senderId, string $receiverName, int $amount): bool
    {
        $sender = UserEloquent::query()->findOrFail($senderId);
        $receiver = UserEloquent::query()->where('name', $receiverName)->firstOrFail();
        /** @var UserEloquent $sender */
        if ($result = $sender->subtractCoins($amount)) {
            CoinTransactionEloquent::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'amount' => $amount,
            ]);
            $receiver->addCoins($amount);
        }
        return $result;
    }
}
