<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @inheritdoc
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'password',
        'jwt_secret',
        'coins',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'jwt_secret',
    ];

    public function getJWTSecret()
    {
        return $this->jwt_secret;
    }

    public function subtractCoins(int $amount): bool {
        if ($amount <= 0 || $this->coins < $amount) {
            return false;
        }
        return $this->update(['coins' => $this->coins - $amount]);
    }

    public function addCoins( int $amount): bool {
        if ($amount <= 0) {
            return false;
        }
        return $this->update(['coins' => $this->coins + $amount]);
    }

    /**
     * Sent Coin Transactions
     *
     * @return HasMany
     */
    public function sentCoinTransactions(): HasMany
    {
        return $this->hasMany(CoinTransaction::class, 'sender_id', 'id');
    }

    /**
     * Received Coin Transactions
     *
     * @return HasMany
     */
    public function receivedCoinTransactions(): HasMany
    {
        return $this->hasMany(CoinTransaction::class, 'receiver_id', 'id');
    }

    /**
     * Received Merch Transactions
     *
     * @return HasMany
     */
    public function merchTransactions(): HasMany
    {
        return $this->hasMany(MerchTransaction::class);
    }
}
