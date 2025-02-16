<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MerchTransaction extends Model
{
    /**
     * @inheritdoc
     */
    protected $table = 'merch_transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'merch_id',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function merch(): BelongsTo {
        return $this->belongsTo(Merch::class);
    }
}
