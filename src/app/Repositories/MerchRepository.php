<?php

namespace App\Repositories;

use App\Interfaces\MerchRepositoryInterface;
use App\Models\Merch as MerchEloquent;
use App\Models\MerchTransaction as MerchTransactionEloquent;
use App\Models\User as UserEloquent;

class MerchRepository implements MerchRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function buyMerchForUser(int $userId, string $merchName): bool
    {
        $merchItem = MerchEloquent::where('name', $merchName)->firstOrFail();
        $user = UserEloquent::findOrFail($userId);
        /** @var UserEloquent $user */
        if ($result = $user->subtractCoins($merchItem->price)) {
            MerchTransactionEloquent::create([
                'merch_id' => $merchItem->id,
                'user_id' => $user->id,
            ]);
        }
        return $result;
    }
}
